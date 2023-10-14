<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessionalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'name_arm' => 'required_if:is_organization,0',
             'last_name_arm' => 'required_if:is_organization,0',
             'email' => 'required',
             'password' => 'required|min:5|max:255',
             'phone_mobile_1' => 'required|min:5|max:255',
             'professions' => 'required',
            'inner_roles' => 'required_if:professions,-3,-2,-1',
             'organization' => 'required_if:is_organization,1',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required_if' => 'Պարտադիր է լրացման համար։',
        ];
    }
}
