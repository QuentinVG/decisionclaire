<?php

namespace App\Services\Pdf;

use App\Models\SavedSimulation;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

final class SimulationPdfGenerator
{
    public function download(SavedSimulation $simulation): Response
    {
        $pdf = Pdf::loadView('pdf.simulation', [
            'simulation' => $simulation,
            'result' => $simulation->result_data,
            'input' => $simulation->input_data,
        ])->setPaper('a4');

        $filename = 'decisionclaire-'.str($simulation->tool_key)->slug().'-'.$simulation->id.'.pdf';

        return $pdf->download($filename);
    }
}
