<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SubscriptionAuditRequest extends FormRequest
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
            'subscriptions' => ['required', 'array', 'min:1', 'max:20'],
            'subscriptions.*.name' => ['required', 'string', 'max:80'],
            'subscriptions.*.price' => ['required', 'numeric', 'min:0', 'max:10000'],
            'subscriptions.*.usage' => ['required', 'in:jamais,rarement,parfois,souvent,quotidiennement'],
            'subscriptions.*.importance' => ['required', 'in:faible,moyenne,forte'],
            'subscriptions.*.duplicate' => ['nullable', 'boolean'],
            'subscriptions.*.commitment' => ['nullable', 'boolean'],
            'subscriptions.*.cancellable' => ['nullable', 'boolean'],
        ];
    }
}
