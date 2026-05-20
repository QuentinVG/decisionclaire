<?php

namespace App\Http\Controllers;

use App\Support\ToolCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('dashboard', [
            'tools' => ToolCatalog::all(),
            'simulations' => $request->user()?->savedSimulations()
                ->latest()
                ->get() ?? collect(),
        ]);
    }
}
