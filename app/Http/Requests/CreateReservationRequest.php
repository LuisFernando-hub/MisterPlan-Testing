<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email',
            'property_name' => 'required|string|max:100',
            'check_in' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'check_out' => 'required|date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'in:pending,confirmed',
            'amount' => 'required|integer',
            'notes' => 'max:500'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guest_name.required' => 'The guest_name is required',
            'guest_email.required' => 'The guest_email is required',
            'guest_email.email' => 'The email is invalid',
            'property_name.required' => 'The property_name is required',
            'check_in.required' => 'The check_in is required',
            'check_in.after_or_equal' => 'The check in must be a date after or equal to now.',
            'check_out.required' => 'The check_out is required',
            'check_out.after' => 'The check out must be a date after check in.',
            'amount.required' => 'The amount is required',
            'status.in' => 'The status is invalid',
        ];
    }
}
