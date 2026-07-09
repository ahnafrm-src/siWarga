<?php

namespace App\Services;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PdfService{
    public function exportData($data, $filters = []){
        $pdf = Pdf::loadView('pdf.export', ["data" => $data, "filters" => $filters]);

        return $pdf;
    }
}