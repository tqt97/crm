<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\Users\UserService;

class AuthController extends BaseController
{
    /**
     * Login user
     *
     * @param  [string] email
     * @param  [string] password
     */
    public function login(LoginRequest $request)
    {
        try {
            $result = App::make(UserService::class)->actionLogin($request);

            if (!$result['success']) {
                return $this->sendError($result['message'], [], $result['code']);
            }

            return $this->sendResponse($request['message'], [
                'accessToken' => $result['token'],
                'token_type' => 'Bearer',
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Logout user
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->sendResponse(__('logout successful'), []);
        } catch (\Throwable $th) {
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Register new user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $token = App::make(UserService::class)->registerNewUser($request->validated());
            DB::commit();
            return $this->sendResponse(__('you have successfully created objects'), [
                'accessToken' => $token,
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError($th->getMessage());
        }
    }

    /**
     * Create token password reset.
     *
     * @param  ForgotPasswordRequest $request
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = App::make(UserService::class)->forgotPassword($request);
            if (!$result) {
                return $this->sendError(__('You have made too many attempts.'), [], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return $this->sendResponse(__(self::MSG_RETRIEVED), [], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Reset Password
     *
     * @param  ResetPasswordRequest $request
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = App::make(UserService::class)->resetPassword($request);
            if (!$result) {
                return $this->sendError(__('Invalid token or email.'), [], Response::HTTP_NOT_FOUND);
            }
            DB::commit();
            return $this->sendResponse(__(self::MSG_UPDATED), [], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th->getMessage() . ' - Line:' . $th->getLine());
            return $this->sendError(__(self::MSG_ERROR), [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        Auth::user()->update(['password' => $request->password]);
        return $this->sendResponse(__('Password Updated Successfully'), [], Response::HTTP_OK);
    }
}
