<?php

namespace App\Http\Controllers;

use App\Support\ToolCatalog;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'tools' => ToolCatalog::all(),
        ]);
    }
}
