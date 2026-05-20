<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LargePurchaseImpactRequest extends FormRequest
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
            'purchase_name' => ['required', 'string', 'max:100'],
            'total_price' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'payment_mode' => ['required', 'in:comptant,mensualise'],
            'monthly_income' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'monthly_charges' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'current_savings' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'minimum_savings' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'down_payment' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'monthly_payment' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'payment_duration' => ['nullable', 'integer', 'min:1', 'max:120'],
            'planned_expenses' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'delay_months' => ['nullable', 'integer', 'min:0', 'max:120'],
        ];
    }
}
