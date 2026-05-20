<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionAuditRequest;
use App\Services\Tools\SubscriptionAuditCalculator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SubscriptionAuditController extends Controller
{
    public function show(Request $request): View
    {
        return $this->showTool($request, 'subscription_audit');
    }

    public function calculate(SubscriptionAuditRequest $request, SubscriptionAuditCalculator $calculator): View
    {
        $input = $request->validated();

        return $this->renderToolResult($request, 'subscription_audit', $input, $calculator->calculate($input));
    }
}
