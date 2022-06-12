<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email_address' => [
                'required',
                'string',
                'email:rfc,dns',
                Rule::unique('subscribers', 'email_address')->ignore($this->subscriber->id)
            ],
            'name' => 'required|string',
            'state' => 'required|string|in:active,unsubscribed,junk,bounced,unconfirmed'
        ];
    }
}
