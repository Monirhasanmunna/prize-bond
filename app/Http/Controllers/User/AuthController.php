<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\User\VerifyOtpRequest;
use App\Http\Requests\User\Auth\ForgotPasswordRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\PasswordUpdateRequest;
use App\Http\Requests\User\Auth\RegistrationRequest;
use App\Http\Requests\User\Auth\ResendOtpRequest;
use App\Http\Requests\User\Auth\ResetPasswordRequest;
use App\Http\Services\Feature\User\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct( private readonly AuthService $service){}

    /**
     * @param RegistrationRequest $request
     * @return JsonResponse
     */
    public function registration(RegistrationRequest $request): JsonResponse
    {
        return response()->json($this->service->registration( $request->all()));
    }

    /**
     * @param VerifyOtpRequest $request
     * @return JsonResponse
     */
    public function verificationOtp(VerifyOtpRequest $request): JsonResponse
    {
        return response()->json($this->service->verifyRegisterOtp( $request->all()));
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        return response()->json($this->service->login( $request->all()));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeUserFcmToken(Request $request): JsonResponse
    {
        $request->validate([
            'fcm_token' => 'required',
        ]);

        return response()->json($this->service->storeUserFcmToken( $request->all()));
    }

    /**
     * @param ResendOtpRequest $request
     * @return JsonResponse
     */
    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        return response()->json($this->service->resendOtp( $request->all()));
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return JsonResponse
     */
    public function sendOtp(ForgotPasswordRequest $request): JsonResponse
    {
        return response()->json($this->service->sendOtp( $request->all()));
    }

    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return response()->json($this->service->reset( $request->all()));
    }

    /**
     * @param PasswordUpdateRequest $request
     * @return JsonResponse
     */
    public function changePassword(PasswordUpdateRequest $request): JsonResponse
    {
        return response()->json($this->service->changePassword( $request->all()));
    }
}
