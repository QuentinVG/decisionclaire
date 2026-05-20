<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseDecisionRequest;
use App\Services\Tools\PurchaseDecisionCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PurchaseDecisionController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'purchase_decision');
    }

    public function calculate(PurchaseDecisionRequest $request, PurchaseDecisionCalculator $calculator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'purchase_decision', $input, $calculator->calculate($input));
    }
}
