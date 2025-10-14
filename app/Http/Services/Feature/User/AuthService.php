<?php

namespace App\Http\Services\Feature\User;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Traits\Response;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    use Response;


    /**
     * @param array $payload
     * @return array
     */
    public function registration(array $payload): array
    {
        try {
            $user = User::create( $this->formatRegisterData( $payload));

            return $this->response(['user' => $user])->success('Registration successfully');
        }
        catch (\Exception $exception) {
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
            if(Auth::attempt(['email' => $payload['email'], 'password' => $payload['password']])){
                $user = Auth::user();
                $authorization = $this->authorize( $user);
                return $this->response(['authorization' => $authorization])->success("Logged In Successfully.");
            }

            return throw new \Exception("Invalid credentials.");
        }
        catch (\Exception $exception) {
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


    public function authorize (object $user): array
    {
        return [
            'token' =>  $user->createToken($user->email)->plainTextToken,
            'token_type' =>  'Bearer'
        ];
    }
}

