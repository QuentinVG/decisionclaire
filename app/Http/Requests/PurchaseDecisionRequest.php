<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseDecisionRequest extends FormRequest
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
            'price' => ['required', 'numeric', 'min:0.01', 'max:1000000'],
            'available_monthly' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'available_savings' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'urgency' => ['required', 'in:faible,moyenne,forte'],
            'utility' => ['required', 'in:faible,moyenne,forte'],
            'usage_frequency' => ['required', 'in:rare,mensuelle,hebdo,quotidienne'],
            'minimum_savings' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'usage_duration_months' => ['nullable', 'integer', 'min:1', 'max:240'],
            'cheaper_alternative' => ['nullable', 'boolean'],
            'alternative_price' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'payment_type' => ['nullable', 'in:comptant,plusieurs_fois'],
            'monthly_payment' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'payment_duration' => ['nullable', 'integer', 'min:1', 'max:120'],
            'planned_timing' => ['nullable', 'in:maintenant,plus_tard'],
        ];
    }
}
