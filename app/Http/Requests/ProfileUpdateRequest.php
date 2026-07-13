<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * AUTHORIZE
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * VALIDATION RULES
     */
    public function rules(): array
    {
        return [

            'nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                'unique:users,username,' . $this->user()->id,
            ],
            'no_telp' => ['required', 'string', 'max:20'],
            'foto' => [
    'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:2048',
],

        ];
    }
}