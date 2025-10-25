<?php

namespace App\Http\Services\Feature\User;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\User\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Http\Services\Systems\OtpService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Traits\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    use Response;

    public function __construct(readonly private OtpService $otpService){}

    /**
     * @param array $payload
     * @return array
     */
    public function registration(array $payload): array
    {
        try {
            DB::beginTransaction();
            $user = User::create( $this->formatRegisterData( $payload));

            // Generate and send verification OTP
            $this->otpService->generate($user->email, 'verify');

            DB::commit();
            return $this->response(['user' => $user])->success('Registration successfully');
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function verifyRegisterOtp(array $payload): array
    {
        try {
            // Find user
            $user = User::where('email', $payload['email'])->first();

            if (!$user) {
                return $this->response()->error('User not found');
            }

            // Check if already verified
            if ($user->email_verified_at) {
                return $this->response()->error('Email is already verified. You can log in now.');
            }

            // Verify OTP
            $isValid = $this->otpService->verify( $payload['email'],'verify', $payload['otp']);

            if (!$isValid) {
                return $this->response()->error('Invalid or expired OTP. Please request a new one.');
            }

            DB::beginTransaction();
            $user->update([
                'email_verified_at' => now(),
            ]);

            // Invalidate all remaining verify OTPs
            $this->otpService->invalidateAll($user->email, 'verify');

            DB::commit();

            return $this->response(['user' => $user])->success('Email verified successfully. You can now log in.');
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function login (array $payload): array
    {
        try {
            // Find user by email
            $user = User::where('email', $payload['email'])->first();

            // Check credentials
            if (!$user || !Hash::check($payload['password'], $user->password)) {
                return $this->response()->error('Invalid email or password.');
            }

            // Check if email is verified
            if (!$user->email_verified_at) {
                return $this->response()->error('Please verify your email before logging in. Check your inbox for the verification code.');
            }

            // Create Sanctum token
            $authorize = $this->authorize( $user);

            return $this->response(['user' => $authorize])->success('Logged in successfully.');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @param array $payload
     * @return array
     */
    public function resendOtp(array $payload): array
    {
        try {
            $user = User::where('email', $payload['email'])->first();

            if (!$user) {
                return $this->response()->error('User not found');
            }

            // Check if already verified
            if ($user->email_verified_at) {
                return $this->response()->error('Email is already verified. You can log in now.');
            }

            // Check remaining wait time
            $wait = $this->otpService->remainingWaitForResend($payload['email'], 'verify');
            if ($wait > 0) {
                return $this->response()->success('Please wait {$wait} seconds before requesting another OTP.');
            }

            // Generate and send new OTP
            $this->otpService->generate($payload['email'], 'verify');

            return $this->response()->success('Verification code has been resent to your email.');

        } catch (\Exception $e) {
            return $this->response()->error($e->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function sendOtp(array $payload): array
    {
        try {
            $user = User::where('email', $payload['email'])->first();

            if(!$user){
                return $this->response()->error('User not found');
            }

            $this->otpService->generate($user->email, 'reset');

            return $this->response()->success('Verification code has been sent to your email.');
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function reset(array $payload): array
    {
        try {
            $user = User::where('email', $payload['email'])->first();

            if (!$user) {
                return $this->response()->error('User not found');
            }

            // Verify reset OTP
            $isValid = $this->otpService->verify($payload['email'], 'reset', $payload['otp']);

            if (!$isValid) {
                return $this->response()->error('Invalid or expired OTP. Please request a new one.');
            }

            DB::beginTransaction();
            $user->update([
                'password' => Hash::make($payload['new_password']),
            ]);

            // Invalidate all reset OTPs
            $this->otpService->invalidateAll($user->email, 'reset');

            // Optionally revoke all existing tokens (force re-login)
            $user->tokens()->delete();

            DB::commit();
            return $this->response()->success('Password has been reset successfully. Please log in with your new password.');
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->response()->error($exception->getMessage());
        }
    }

    /**
     * @return array
     */
    public function logout (): array
    {
        try {
            Auth::user()->token()->revoke();
            return $this->response()->success("Logged Out Successfully");
        }
        catch (\Exception $exception) {
            return $this->response()->error($exception->getMessage());
        }
    }


    /**
     * @param array $payload
     * @return array
     */
    public function changePassword(array $payload): array
    {
        try {
            $user = User::where('id', Auth::id())->where('role', ROLE_USER)->first();

            if(!$user){
                return $this->response()->error('Not authenticated');
            }

            if(!Hash::check($payload['old_password'], $user->password)){
                return $this->response()->error('Old password is incorrect');
            }

            $user->password = Hash::make($payload['new_password']);
            $user->save();

            return $this->response()->success('Password has been changed successfully.');
        }
        catch (\Exception $exception){
            return $this->response()->error($exception->getMessage());
        }
    }



    /**
     * @param array $payload
     * @return array
     */
    private function formatRegisterData(array $payload): array
    {
        $data = [
            "name" => $payload['name'],
            "email" => $payload['email'],
            "phone" => $payload['phone'],
            "password" => Hash::make($payload['password']),
        ];

        if(!empty($payload['nid']))                 $data['nid']                = $payload['nid'];
        if(!empty($payload['referral_code']))       $data['referral_code']      = $payload['referral_code'];

        return $data;
    }


    /**
     * @param object $user
     * @return array
     */
    public function authorize (object $user): array
    {
        return [
            'token' =>  $user->createToken($user->email)->plainTextToken,
            'token_type' =>  'Bearer',
            'info' => new UserResource($user),
        ];
    }
}

