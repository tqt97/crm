<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseApiRequest;

class ChangePasswordRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string|current_password|different:password',
            'password' => 'required|string|confirmed'
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => __('Current Password'),
            'password' => __('Password'),
            'password_confirmation' => __('Password Confirmation'),
        ];
    }
}
