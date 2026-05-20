<?php

namespace App\Http\Controllers;

use App\Support\ToolCatalog;
use Illuminate\Contracts\View\View;

class ToolController extends Controller
{
    public function index(): View
    {
        return view('tools.index', [
            'tools' => ToolCatalog::all(),
        ]);
    }
}
