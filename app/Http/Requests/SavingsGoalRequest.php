<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SavingsGoalRequest extends FormRequest
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
            'goal_name' => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'min:1', 'max:1000000'],
            'current_savings' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'target_date' => ['required', 'date', 'after_or_equal:today'],
            'monthly_capacity' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'priority' => ['nullable', 'in:faible,moyenne,forte'],
            'current_living_balance' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'security_margin' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
        ];
    }
}
