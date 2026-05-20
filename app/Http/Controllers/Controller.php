<?php

namespace App\Http\Controllers;

use App\Models\SavedSimulation;
use App\Support\ToolCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Controller
{
    use AuthorizesRequests;

    protected function showTool(Request $request, string $toolKey): View
    {
        $tool = ToolCatalog::get($toolKey);
        abort_if($tool === [], 404);

        $inputs = [];
        $simulationId = $request->integer('simulation');

        if ($simulationId > 0 && $request->user() !== null) {
            $simulation = SavedSimulation::query()->findOrFail($simulationId);
            $this->authorize('view', $simulation);

            if ($simulation->tool_key === $toolKey) {
                $inputs = $simulation->input_data;
            }
        }

        return view('tools.show', [
            'tool' => $tool,
            'tools' => ToolCatalog::all(),
            'inputs' => $inputs,
            'result' => null,
            'pendingKey' => null,
            'savedSimulation' => null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $result
     */
    protected function renderToolResult(Request $request, string $toolKey, array $input, array $result): View
    {
        $tool = ToolCatalog::get($toolKey);
        abort_if($tool === [], 404);

        $pendingKey = (string) Str::uuid();
        $request->session()->put('pending_simulations.'.$pendingKey, [
            'tool_key' => $toolKey,
            'title' => (string) ($result['title'] ?? $tool['name']),
            'input_data' => $input,
            'result_data' => $result,
        ]);

        return view('tools.show', [
            'tool' => $tool,
            'tools' => ToolCatalog::all(),
            'inputs' => $input,
            'result' => $result,
            'pendingKey' => $pendingKey,
            'savedSimulation' => null,
        ]);
    }
}
