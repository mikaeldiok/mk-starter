<?php

namespace Modules\Benefactor\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class DonatorsRequest extends FormRequest
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
            'donator_name'              => 'required|max:191',
            'donator_phone'    => 'nullable|numeric',
            'donator_email'     => 'nullable|email',
        ];
    }
}
