<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' =>['email',
                Rule::unique('users')->ignore($this->id),
            ],
            'password' => 'confirmed',
            'allow_export' => '',
            'allow' => 'digits_between:0,3',
            'brand_id' => 'array',
            'country_id' => 'array',
            'partner_id' => 'array',
        ];
    }
}
