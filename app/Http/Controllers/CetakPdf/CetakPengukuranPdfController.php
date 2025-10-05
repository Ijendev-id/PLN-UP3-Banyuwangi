<?php

namespace App\Http\Controllers\CetakPdf;

use App\Http\Controllers\Controller;
use App\Models\HistoryData\HistoryDataOmtPengukuran;
use App\Models\ManajemenData\OmtPengukuran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakPengukuranPdfController extends Controller
{
    public function cetak($id)
    {
        $pengukuran = OmtPengukuran::with('gardu')->findOrFail($id);

        // ambil history terbaru sesuai id_omt_pengukuran
        $historyTerbaruPengukuran = HistoryDataOmtPengukuran::where('id_omt_pengukuran', $pengukuran->id)
            ->latest('created_at')
            ->first();

        $pdf = Pdf::loadView(
            'manajemen-data.template-pdf.pdf-omt-pengukuran',
            [
                'pengukuran' => $pengukuran,
                'historyTerbaruPengukuran' => $historyTerbaruPengukuran
            ]
        )->setPaper('a4', 'portrait');

        return $pdf->stream("Pengukuran Gardu {$pengukuran->gardu->kd_gardu}.pdf");
    }
}
