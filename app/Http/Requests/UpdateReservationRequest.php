<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
            'guest_name' => 'string|max:100',
            'guest_email' => 'email',
            'property_name' => 'string|max:100',
            'check_in' => 'date_format:Y-m-d H:i:s|after_or_equal:now',
            'check_out' => 'date_format:Y-m-d H:i:s|after:check_in',
            'status' => 'in:pending,confirmed,cancelled',
            'amount' => 'integer',
            'notes' => 'max:500'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'guest_email.email' => 'The email is invalid',
            'check_in.after_or_equal' => 'The check in must be a date after or equal to now.',
            'check_out.after' => 'The check out must be a date after check in.',
            'status.in' => 'The status is invalid',
        ];
    }
}
