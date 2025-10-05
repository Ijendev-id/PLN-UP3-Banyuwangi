<?php

namespace App\Http\Controllers\CetakPdf;

use App\Http\Controllers\Controller;
use App\Models\ManajemenData\DataGardu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakGarduPdfController extends Controller
{
    public function cetak($id)
    {
        $gardu = DataGardu::findOrFail($id);

        $pdf = Pdf::loadView(
            'manajemen-data.template-pdf.pdf-gardu',
            [
                'gardu' => $gardu,
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream("Data Gardu {$gardu->gardu}.pdf");
    }
}
