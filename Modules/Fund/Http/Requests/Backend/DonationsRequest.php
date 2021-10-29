<?php

namespace Modules\Fund\Http\Requests\Backend;

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
            'donation_name'              => 'required|max:191',
            'donation_phone'    => 'nullable|numeric',
            'donation_email'     => 'nullable|email',
        ];
    }
}
