<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "nomComplet"  => 'string|required',
            "telephone"=>'string|unique:users|regex:/^(7[76508]{1})(\\d{7})$/',
            "login"  => 'string|required|unique:users|regex:/^(7[76508]{1})(\\d{7})$/',
            "password"  => 'string|required',
            "password_confirm"  => 'string|required|same:password',
        ];
    }
}
