<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingsGoalRequest;
use App\Services\Tools\SavingsGoalCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SavingsGoalController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'savings_goal');
    }

    public function calculate(SavingsGoalRequest $request, SavingsGoalCalculator $calculator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'savings_goal', $input, $calculator->calculate($input));
    }
}
