<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LivingBalanceRequest extends FormRequest
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
            'monthly_income' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'fixed_charges' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'groceries' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'transport' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'subscriptions' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'debts' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'planned_savings' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'aids' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'other_income' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'energy' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'insurance' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'phone_internet' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'family_expenses' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'other_charges' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'security_margin' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
        ];
    }
}
