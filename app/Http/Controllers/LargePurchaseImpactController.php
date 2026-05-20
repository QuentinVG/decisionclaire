<?php

namespace App\Http\Controllers;

use App\Http\Requests\LargePurchaseImpactRequest;
use App\Services\Tools\LargePurchaseImpactCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LargePurchaseImpactController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'large_purchase_impact');
    }

    public function calculate(LargePurchaseImpactRequest $request, LargePurchaseImpactCalculator $calculator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'large_purchase_impact', $input, $calculator->calculate($input));
    }
}
