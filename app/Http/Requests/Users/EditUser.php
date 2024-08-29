<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class EditUser extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
            'name' => 'required',
            'profile_photo' => 'nullable|image|max:1024',
            'identity' => 'nullable|image|max:1024',
            'email' => 'nullable|email|unique:users,email,'.$this->id,
            'phone' => 'required|numeric|unique:users,phone,'.$this->id,
            'another_phone' => 'nullable|numeric|unique:users,another_phone,'.$this->id
        ];
    }
}
