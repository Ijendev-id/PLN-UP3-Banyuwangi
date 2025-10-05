<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Gardu</title>
    <link rel="stylesheet" href="{{ public_path('asset_pln/css/css_pdf/css-pdf-gardu.css') }}">
</head>

<body>
    <div class="title">DATA GARDU: {{ $gardu->kd_gardu }}</div>
    <table class="detail">
        <tr>
            <td>Gardu Induk</td>
            <td>: {{ $gardu->gardu_induk }}</td>
            <td>Trafo GI</td>
            <td>: {{ $gardu->kd_trf_gi }}</td>
        </tr>
        <tr>
            <td>Penyulang</td>
            <td>: {{ $gardu->kd_pylg }}</td>
            <td>Kode Gardu</td>
            <td>: {{ $gardu->kd_gardu }}</td>
        </tr>
        <tr>
            <td>Jumlah Trafo</td>
            <td>: {{ $gardu->jml_trafo }}</td>
            <td>Daya Trafo</td>
            <td>: {{ $gardu->daya_trafo }}</td>
        </tr>
        <tr>
            <td>Hubungan</td>
            <td>: {{ $gardu->hubungan }}</td>
            <td>Impedansi</td>
            <td>: {{ $gardu->impedansi }}</td>
        </tr>
        <tr>
            <td>Fasa</td>
            <td>: {{ $gardu->fasa }}</td>
            <td>Frekuensi</td>
            <td>: {{ $gardu->frekuensi }}</td>
        </tr>
        <tr>
            <td>Tegangan TM</td>
            <td>: {{ $gardu->tegangan_tm }}</td>
            <td>Tegangan TR</td>
            <td>: {{ $gardu->tegangan_tr }}</td>
        </tr>
        <tr>
            <td>Beban kVA Trafo</td>
            <td>: {{ $gardu->beban_kva_trafo }}</td>
            <td>Persentase Beban</td>
            <td>: {{ $gardu->persentase_beban }}</td>
        </tr>
        <tr>
            <td>Section LBS</td>
            <td>: {{ $gardu->section_lbs }}</td>
            <td>Nilai SKD Utama</td>
            <td>: {{ $gardu->nilai_sdk_utama }}</td>
        </tr>
        <tr>
            <td>Nilai Primer</td>
            <td>: {{ $gardu->nilai_primer }}</td>
            <td>Tap No</td>
            <td>: {{ $gardu->tap_no }}</td>
        </tr>
        <tr>
            <td>Tap kV</td>
            <td>: {{ $gardu->tap_kv }}</td>
            <td>Rekondisi/Preman</td>
            <td>: {{ $gardu->rekondisi_preman }}</td>
        </tr>
        <tr>
            <td>Bengkel</td>
            <td colspan="3">: {{ $gardu->bengkel }}</td>
        </tr>
    </table>
    
    <div class="title">DATA TRAFO</div>
    <table class="detail">
        <tr>
            <td>Merk Trafo I</td>
            <td>: {{ $gardu->merek_trafo }}</td>
            <td>Merk Trafo II</td>
            <td>: {{ $gardu->merek_trafo_2 }}</td>
            <td>Merk Trafo III</td>
            <td>: {{ $gardu->merek_trafo_3 }}</td>
        </tr>
        <tr>
            <td>No Seri I</td>
            <td>: {{ $gardu->no_seri }}</td>
            <td>No Seri II</td>
            <td>: {{ $gardu->no_seri_2 }}</td>
            <td>No Seri III</td>
            <td>: {{ $gardu->no_seri_3 }}</td>
        </tr>
        <tr>
            <td>Tahun I</td>
            <td>: {{ $gardu->tahun }}</td>
            <td>Tahun II</td>
            <td>: {{ $gardu->tahun_2 }}</td>
            <td>Tahun III</td>
            <td>: {{ $gardu->tahun_3 }}</td>
        </tr>
        <tr>
            <td>Berat Total I</td>
            <td>: {{ $gardu->berat_total }}</td>
            <td>Berat Total II</td>
            <td>: {{ $gardu->berat_total_2 }}</td>
            <td>Berat Total III</td>
            <td>: {{ $gardu->berat_total_3 }}</td>
        </tr>
        <tr>
            <td>Minyak I</td>
            <td>: {{ $gardu->berat_minyak }}</td>
            <td>Minyak II</td>
            <td>: {{ $gardu->berat_minyak_2 }}</td>
            <td>Minyak III</td>
            <td>: {{ $gardu->berat_minyak_3 }}</td>
        </tr>
    </table>
</body>

</html>