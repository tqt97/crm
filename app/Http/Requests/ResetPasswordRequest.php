<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ResetPasswordRequest extends BaseApiRequest
{
    protected $table = 'password_reset_tokens';
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'string',
                'max:255',
            ],
            'password' => [
                'string',
                'min:4',
                'confirmed',
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
