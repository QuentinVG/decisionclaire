<?php

namespace App\Http\Controllers;

use App\Models\SavedSimulation;
use App\Services\Pdf\SimulationPdfGenerator;
use Symfony\Component\HttpFoundation\Response;

class PdfExportController extends Controller
{
    public function __invoke(SavedSimulation $simulation, SimulationPdfGenerator $generator): Response
    {
        $this->authorize('view', $simulation);

        return $generator->download($simulation);
    }
}
