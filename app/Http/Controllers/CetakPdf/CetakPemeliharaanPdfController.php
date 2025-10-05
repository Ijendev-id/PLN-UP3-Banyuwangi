<?php

namespace App\Http\Controllers\CetakPdf;

use App\Http\Controllers\Controller;
use App\Models\HistoryData\HistoryDataPemeliharaan;
use App\Models\ManajemenData\Pemeliharaan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakPemeliharaanPdfController extends Controller
{
    public function cetak($id)
    {
        $pemeliharaan = Pemeliharaan::with('gardu')->findOrFail($id);

        $historyPemeliharaanTerbaru = HistoryDataPemeliharaan::where('id_pemeliharaan', $pemeliharaan->id)
            ->latest('created_at')
            ->first();

        $pdf = Pdf::loadView(
            'manajemen-data.template-pdf.pdf-pemelliharaan',
            [
                'pemeliharaan' => $pemeliharaan,
                'historyPemeliharaanTerbaru' => $historyPemeliharaanTerbaru
            ]
        )
        ->setPaper('a4', 'portrait');

        return $pdf->stream("Pemeliharaan Gardu {$pemeliharaan->gardu->kd_gardu}.pdf");
    }
}
