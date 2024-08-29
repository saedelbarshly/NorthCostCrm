<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class CreateClient extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            //
            'Name' => 'required',
            'identity' => 'nullable|unique:clients,identity|digits:10',
            'email' => 'nullable|email|required_without:cellphone|unique:clients,email',
            'phone' => 'nullable|numeric|unique:clients,phone|digits:10',
            'cellphone' => 'required|required_without:email|numeric|unique:clients,cellphone|digits_between:10,13|digits:10',
            'whatsapp' => 'nullable|numeric|unique:clients,whatsapp'
        ];
    }
}
