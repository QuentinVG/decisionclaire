<?php

namespace App\Http\Controllers;

use App\Http\Requests\LivingBalanceRequest;
use App\Services\Tools\LivingBalanceCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LivingBalanceController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'living_balance');
    }

    public function calculate(LivingBalanceRequest $request, LivingBalanceCalculator $calculator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'living_balance', $input, $calculator->calculate($input));
    }
}
