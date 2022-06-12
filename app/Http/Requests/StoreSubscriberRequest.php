<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriberRequest extends FormRequest
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
            'email_address' => 'required|string|email:rfc,dns|unique:subscribers,email_address',
            'name' => 'required|string',
            'state' => 'required|string|in:active,unsubscribed,junk,bounced,unconfirmed'
        ];
    }
}
