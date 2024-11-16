<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ForgotPasswordRequest extends BaseApiRequest
{
    protected $table = 'password_reset_tokens';
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'max:255',
                Rule::exists('users', 'email'),
            ],
        ];
    }
    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
