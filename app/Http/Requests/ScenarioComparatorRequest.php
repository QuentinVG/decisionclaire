<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ScenarioComparatorRequest extends FormRequest
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
            'template' => ['required', 'in:acheter_maintenant_vs_attendre,neuf_vs_occasion,comptant_vs_plusieurs_fois,garder_vs_remplacer,mensuel_vs_annuel,economique_vs_confort'],
            'scenarios' => ['required', 'array', 'min:2', 'max:4'],
            'scenarios.*.name' => ['required', 'string', 'max:80'],
            'scenarios.*.initial_cost' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'scenarios.*.monthly_cost' => ['required', 'numeric', 'min:0', 'max:1000000'],
            'scenarios.*.duration' => ['required', 'integer', 'min:1', 'max:240'],
            'scenarios.*.utility' => ['required', 'in:faible,moyenne,forte'],
            'scenarios.*.flexibility' => ['required', 'in:faible,moyenne,forte'],
            'scenarios.*.risk' => ['required', 'in:faible,moyen,élevé'],
            'scenarios.*.savings_impact' => ['nullable', 'numeric', 'min:0', 'max:1000000'],
            'scenarios.*.comment' => ['nullable', 'string', 'max:240'],
        ];
    }
}
