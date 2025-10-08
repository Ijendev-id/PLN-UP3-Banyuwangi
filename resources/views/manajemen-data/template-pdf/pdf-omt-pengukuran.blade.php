<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Pengukuran</title>
    <link rel="stylesheet" href="{{ public_path('asset_pln/css/css_pdf/css_pdf_omt_pengukuran.css') }}" type="text/css">
</head>

<body>

    <table>
        <td colspan="4" class="judul">
            PENGUKURAN GARDU : {{ $pengukuran->gardu->kd_gardu ?? '-' }}
        </td>

        <tr>
            <td>Waktu, Tanggal</td>
            <td>: {{ $pengukuran->waktu_pengukuran }}</td>
            <td>Diubah Oleh</td>
            <td>: {{ $historyTerbaruPengukuran->diubah_oleh ?? '-' }}</td>
        </tr>
        <tr>
            <td>Gardu Induk</td>
            <td>: {{ $pengukuran->gardu->gardu_induk ?? '-' }}</td>
            <td>Daya Trafo</td>
            <td>: {{ $pengukuran->gardu->daya_trafo ?? '-' }}</td>
        </tr>
        <tr>
            <td>Trafo Gardu Induk</td>
            <td>: {{ $pengukuran->gardu->kd_trf_gi ?? '-' }}</td>
            <td>No Seri</td>
            <td>: {{ $pengukuran->gardu->no_seri ?? '-' }}</td>
        </tr>
        <tr>
            <td>Penyulang</td>
            <td>: {{ $pengukuran->gardu->kd_pylg ?? '-' }}</td>
            <td>Tahun</td>
            <td>: {{ $pengukuran->gardu->tahun ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kode Gardu</td>
            <td>: {{ $pengukuran->gardu->kd_gardu ?? '-' }}</td>
            <td>Beban kVA Trafo</td>
            <td>: {{ $pengukuran->gardu->beban_kva_trafo ?? '-' }}</td>
        </tr>
        <tr>
            <td>Merek Trafo</td>
            <td>: {{ $pengukuran->gardu->merek_trafo ?? '-' }}</td>
            <td>Persentase Beban</td>
            <td>: {{ $pengukuran->gardu->persentase_beban ?? '-' }}%</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td colspan="3">: {{ $pengukuran->gardu->alamat ?? '-' }}</td>
        </tr>
    </table>

    <br>

    <table class="table-arus">
        <tr>
            <td>Ian</td>
            <td>: {{ $pengukuran->ian }}</td>
            <td>Iar</td>
            <td>: {{ $pengukuran->iar }}</td>
            <td>Ias</td>
            <td>: {{ $pengukuran->ias }}</td>
            <td>Iat</td>
            <td>: {{ $pengukuran->iat }}</td>
        </tr>
        <tr>
            <td>Ibn</td>
            <td>: {{ $pengukuran->ibn }}</td>
            <td>Ibr</td>
            <td>: {{ $pengukuran->ibr }}</td>
            <td>Ibs</td>
            <td>: {{ $pengukuran->ibs }}</td>
            <td>Ibt</td>
            <td>: {{ $pengukuran->ibt }}</td>
        </tr>
        <tr>
            <td>Icn</td>
            <td>: {{ $pengukuran->icn }}</td>
            <td>Icr</td>
            <td>: {{ $pengukuran->icr }}</td>
            <td>Ics</td>
            <td>: {{ $pengukuran->ics }}</td>
            <td>Ict</td>
            <td>: {{ $pengukuran->ict }}</td>
        </tr>
        <tr>
            <td>Idn</td>
            <td>: {{ $pengukuran->idn }}</td>
            <td>Idr</td>
            <td>: {{ $pengukuran->idr }}</td>
            <td>Ids</td>
            <td>: {{ $pengukuran->ids }}</td>
            <td>Idt</td>
            <td>: {{ $pengukuran->idt }}</td>
        </tr>

        <tr>
            <td>Iun</td>
            <td>: {{ $pengukuran->iun }}</td>
            <td>Iur</td>
            <td>: {{ $pengukuran->iur }}</td>
            <td>Ius</td>
            <td>: {{ $pengukuran->ius }}</td>
            <td>Iut</td>
            <td>: {{ $pengukuran->iut }}</td>
        </tr>
    </table>


    <br>

    <table class="voltage-table">
        <tr>
            <td>Vrn</td>
            <td>: {{ $pengukuran->vrn }}</td>
            <td>Vsn</td>
            <td>: {{ $pengukuran->vsn }}</td>
            <td>Vtn</td>
            <td>: {{ $pengukuran->vtn }}</td>
            <td>Vrs</td>
            <td>: {{ $pengukuran->vrs }}</td>            
            <td>Vst</td>
            <td>: {{ $pengukuran->vst }}</td>            
            <td>Vtr</td>
            <td>: {{ $pengukuran->vtr }}</td>
        </tr>
    </table>

    <br>

    <table>
        <tr>
            <td style="height:80px;">
                <span class="keterangan-label">Keterangan :</span>
                <span class="keterangan-text">{{ $historyTerbaruPengukuran->keterangan ?? '-' }}</span>
            </td>

        </tr>
    </table>

</body>

</html>