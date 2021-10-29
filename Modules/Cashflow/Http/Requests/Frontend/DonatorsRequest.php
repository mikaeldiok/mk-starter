<?php

namespace Modules\Cashflow\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class DonationsRequest extends FormRequest
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
            'Donation_name'              => 'required|max:191',
            'Donation_phone'    => 'nullable|numeric',
            'Donation_email'     => 'nullable|email',
        ];
    }
}
