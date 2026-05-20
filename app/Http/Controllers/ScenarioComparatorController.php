<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScenarioComparatorRequest;
use App\Services\Tools\ScenarioComparator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ScenarioComparatorController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'scenario_comparator');
    }

    public function calculate(ScenarioComparatorRequest $request, ScenarioComparator $comparator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'scenario_comparator', $input, $comparator->calculate($input));
    }
}
