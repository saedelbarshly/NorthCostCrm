<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class EditClient extends FormRequest
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
            'identity' => 'nullable|digits:10|unique:clients,identity,'.$this->id,
            'email' => 'nullable|email|unique:clients,email,'.$this->id,
            'phone' => 'nullable|digits:10|required_without:cellphone|numeric|unique:clients,phone,'.$this->id,
            'cellphone' => 'nullable|digits:10|required_without:phone|numeric|unique:clients,cellphone,'.$this->id,
            'whatsapp' => 'nullable|numeric|unique:clients,whatsapp,'.$this->id
        ];
    }
}
