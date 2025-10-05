<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Gardu</title>
    <link rel="stylesheet" href="{{ public_path('asset_pln/css/css_pdf/css-pdf-pemeliharaan.css') }}" type="text/css">
</head>

<body>
    <div class="container">
        <!-- Header Section -->
        <table class="header-table">
            <tr>
                <td colspan="8" class="title">PEMELIHARAAN GARDU : {{ $pemeliharaan->gardu->kd_gardu ?? '-' }}</td>
            </tr>
            <tr>
                <td width="12.5%">Kode Gardu</td>
                <td width="12.5%">: {{ $pemeliharaan->gardu->kd_gardu ?? 'No Data' }}</td>
                <td width="12.5%">Merk I</td>
                <td width="12.5%">: {{ $pemeliharaan->gardu->merek_trafo ?? 'No Data' }}</td>
                <td width="12.5%">Merk II</td>
                <td width="12.5%">: {{ $pemeliharaan->gardu->merek_trafo_2 ?? 'No Data' }}</td>
                <td width="12.5%">Merk III</td>
                <td width="12.5%">: {{ $pemeliharaan->gardu->merek_trafo_3 ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td>Gardu Induk</td>
                <td>: {{ ucfirst($pemeliharaan->gardu->gardu_induk ?? 'No Data') }}</td>
                <td>Tahun I</td>
                <td>: {{ $pemeliharaan->gardu->tahun ?? 'No Data' }}</td>
                <td>Tahun II</td>
                <td>: {{ $pemeliharaan->gardu->tahun_2 ?? 'No Data' }}</td>
                <td>Tahun III</td>
                <td>: {{ $pemeliharaan->gardu->tahun_3 ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td>Penyulang</td>
                <td>: {{ $pemeliharaan->gardu->kd_pylg ?? 'No Data' }}</td>
                <td>No Seri I</td>
                <td>: {{ $pemeliharaan->gardu->no_seri ?? 'No Data' }}</td>
                <td>No Seri II</td>
                <td>: {{ $pemeliharaan->gardu->no_seri_2 ?? 'No Data' }}</td>
                <td>No Seri III</td>
                <td>: {{ $pemeliharaan->gardu->no_seri_3 ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td>Trato GI</td>
                <td>: {{ $pemeliharaan->gardu->kd_trf_gi ?? 'No Data' }}</td>
                <td>Oli I</td>
                <td>: {{ $pemeliharaan->gardu->berat_minyak ?? 'No Data' }}</td>
                <td>Oli II</td>
                <td>: {{ $pemeliharaan->gardu->berat_minyak_2 ?? 'No Data' }}</td>
                <td>Oli III</td>
                <td>: {{ $pemeliharaan->gardu->berat_minyak_3 ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $pemeliharaan->gardu->alamat ?? 'No Data' }}</td>
                <td>Berat I</td>
                <td>: {{ $pemeliharaan->gardu->berat_total ?? 'No Data' }}</td>
                <td>Berat II</td>
                <td>: {{ $pemeliharaan->gardu->berat_total_2 ?? 'No Data' }}</td>
                <td>Berat III</td>
                <td>: {{ $pemeliharaan->gardu->berat_total_3 ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="3">Waktu Pemeliharaan : {{ \Carbon\Carbon::parse($pemeliharaan->waktu_pemeliharaan)->format('d/m/Y H:i') }}</td>                
                <td colspan="2">Diubah Oleh : {{ $historyPemeliharaanTerbaru->diubah_oleh ?? 'No Data' }}</td>                
                <td colspan="3">Keterangan: {{ $historyPemeliharaanTerbaru->keterangan ?? 'No Data' }}</td>                
            </tr>
            <tr>
                <td colspan="5">SUTM (mm)</td>
                <td colspan="3"> : {{ $pemeliharaan->sutm_mm ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JUMPER SUTM (BH/mm2) OUT</td>
                <td colspan="1">R: {{ $pemeliharaan->jumper_sutm_out_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->jumper_sutm_out_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->jumper_sutm_out_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">COND. SUTM KE CO (BH/mm2)</td>
                <td colspan="1">R: {{ $pemeliharaan->cond_sutm_co_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->cond_sutm_co_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->cond_sutm_co_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JUMPER SUTM KE CO (BH/mm2) INCOME</td>
                <td colspan="1">R: {{ $pemeliharaan->jumper_sutm_co_income_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->jumper_sutm_co_income_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->jumper_sutm_co_income_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">Fuse Link</td>
                <td colspan="1">R: {{ $pemeliharaan->fuse_link_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->fuse_link_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->fuse_link_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">Keramik/Poilmer</td>
                <td colspan="3">: {{ $pemeliharaan->keramik_polimer ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JUMPER CO KE TRAFO PRIMER (BH/mm2) OUT</td>
                <td colspan="1">R: {{ $pemeliharaan->jumper_co_trafo_primer_out_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->jumper_co_trafo_primer_out_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->jumper_co_trafo_primer_out_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">COND. CO KE TRAFO BUSH PRIMER (BH/mm2)</td>
                <td colspan="1">R: {{ $pemeliharaan->cond_co_trafo_bush_primer_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->cond_co_trafo_bush_primer_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->cond_co_trafo_bush_primer_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JUMPER CO KE BUSH TRAFO PRIMER (BH/mm2) INCOME</td>
                <td colspan="1">R: {{ $pemeliharaan->jumper_co_bush_trafo_primer_income_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->jumper_co_bush_trafo_primer_income_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->jumper_co_bush_trafo_primer_income_fasa_r ?? 'No Data' }}</td>
            </tr>            
            <tr>
                <td colspan="5">COND. BUSH PRIMER KE ARESTER (BH/mm2)</td>
                <td colspan="1">R: {{ $pemeliharaan->cond_bush_primer_arester_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->cond_bush_primer_arester_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->cond_bush_primer_arester_fasa_t ?? 'No Data' }}</td>
            </tr>            
            <tr>
                <td colspan="8" class="bold-text">LIGHTNING ARESTER 5 Ka / 24 KV</td>                
            </tr>
            <tr>
                <td colspan="5">Arrester</td>
                <td colspan="1">R: {{ $pemeliharaan->arester_fasa_t ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->arester_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->arester_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">KERAMIK/POLIMER</td>           
                <td colspan="3"> : {{ $pemeliharaan->keramik_polimer_lighting_arester ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER DUDUKAN ARESTER</td>
                <td colspan="1">R: {{ $pemeliharaan->jumper_dudukan_arester_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->jumper_dudukan_arester_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->jumper_dudukan_arester_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">COND. DUDUKAN LA</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dudukan_la ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER BODY TRF KE LA</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_body_trf_la ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">COND. BODY TRF KE LA</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_body_trf_la ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER COND. LA DG BODY TRF</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_cond_la_dg_body_trf ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">COND. GROUND LA KE PANEL</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_ground_la_panel ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">ISOLASI (M&#x03A9;)</td>
                <td colspan="1">R: {{ $pemeliharaan->isolasi_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->isolasi_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->isolasi_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">ARUS BOCOR (mA)</td>           
                <td colspan="3"> : {{ $pemeliharaan->arus_bocor ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER TRF BUSH SKUNDER 4X (mm) KE PANEL</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_trf_bush_skunder_4x_panel ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="8" class="bold-text">INFOR</td>                
            </tr>
            <tr>
                <td colspan="5">COND. OUT TRF KE PANEL 4X (mm)</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_out_trf_panel ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">TAHANAN ISOLASI (MΩ) P-P</td>           
                <td colspan="3"> : {{ $pemeliharaan->tahanan_isolasi_pp ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">TAHANAN ISOLASI (MΩ) P-G</td>           
                <td colspan="3"> : {{ $pemeliharaan->tahanan_isolasi_pg ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER IN PANEL/SAKLAR</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_in_panel_saklar ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER IN NOL</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_in_nol ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER NOL KE GROUND</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_nol_ground ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JENIS SAKLAR UTAMA (Amp)</td>           
                <td colspan="3"> : {{ $pemeliharaan->jenis_saklar_utama ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER DR SAKLAR OUT</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_dr_saklar_out ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JENIS COND. DR SAKLAR KE NH UTAMA</td>           
                <td colspan="3"> : {{ $pemeliharaan->jenis_cond_dr_saklar_nh_utama ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">DATA PROTEKSI UTAMA</td>
                <td colspan="1">R: {{ $pemeliharaan->data_proteksi_utama_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->data_proteksi_utama_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->data_proteksi_utama_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JENIS COND. DR NH UTAMA KE NH JURUSAN</td>           
                <td colspan="3"> : {{ $pemeliharaan->jenis_cond_dr_nh_utama_jurusan ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER DR NH JURUSAN (IN)</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_dr_nh_jurusan_in ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="8" class="bold-text">DATA PROTEKSI (NH FUSE (NH / SMIL DRAAD (SD) JURUSAN</td>                
            </tr>
            <tr>
                <td colspan="5">LINE A</td>
                <td colspan="1">R: {{ $pemeliharaan->data_proteksi_line_a_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->data_proteksi_line_a_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->data_proteksi_line_a_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">LINE B</td>
                <td colspan="1">R: {{ $pemeliharaan->data_proteksi_line_b_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->data_proteksi_line_b_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->data_proteksi_line_b_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">LINE C</td>
                <td colspan="1">R: {{ $pemeliharaan->data_proteksi_line_c_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->data_proteksi_line_c_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->data_proteksi_line_c_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">LINE D</td>
                <td colspan="1">R: {{ $pemeliharaan->data_proteksi_line_d_fasa_r ?? 'No Data' }}</td>
                <td colspan="1">S: {{ $pemeliharaan->data_proteksi_line_d_fasa_s ?? 'No Data' }}</td>
                <td colspan="1">T: {{ $pemeliharaan->data_proteksi_line_d_fasa_t ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JUMPER OUT DR NH JURUSAN KE COND. OUT JTR</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_out_dr_nh_jurusan_cond_out_jtr ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="8" class="bold-text">JENIS COND. DR NH JURUSAN KE OUT JTR (TO FOR)</td>                
            </tr>
            <tr>
                <td colspan="5">LINE A NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_nh_jurusan_out_jtr_line_a ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE B NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_nh_jurusan_out_jtr_line_b ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE C NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_nh_jurusan_out_jtr_line_c ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE D NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_nh_jurusan_out_jtr_line_d ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="8" class="bold-text">JENIS COND. JURUSAN JTR</td>                
            </tr>
            <tr>
                <td colspan="5">LINE A NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_jurusan_jtr_line_a ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE B NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_jurusan_jtr_line_b ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE C NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_jurusan_jtr_line_c ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">LINE D NAMA COND. 4X</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_jurusan_jtr_line_d ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JUMPER LA+BODY PANEL</td>           
                <td colspan="3"> : {{ $pemeliharaan->jumper_la_body_panel ?? 'No Data' }}</td>     
            </tr>            
            <tr>
                <td colspan="5">JENIS COND. DR GROUND LA KE BODY</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_ground_la_body ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">JENIS COND DR NOL KE GROUND</td>           
                <td colspan="3"> : {{ $pemeliharaan->cond_dr_nol_ground ?? 'No Data' }}</td>     
            </tr>            
            <tr>
                <td colspan="5">NILAI R TANAH NOL (Ω)</td>           
                <td colspan="3"> : {{ $pemeliharaan->nilai_r_tanah_nol ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">NILAI R TANAH LA (Ω)</td>           
                <td colspan="3"> : {{ $pemeliharaan->nilai_r_tanah_la ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="8" class="bold-text">PANEL GTT</td>
            </tr>
            <tr>
                <td colspan="5">PINTU</td>           
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_pintu ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">KUNCI</td>           
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_kunci ?? 'No Data' }}</td>     
            </tr>
            <tr>
                <td colspan="5">NO GTT</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_no_gtt ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">KONDISI</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_kondisi ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">LUBANG PIPA</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_lubang_pipa ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">TANDA PERINGATAN</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_tanda_peringatan ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">JENIS GARDU</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_jenis_gardu ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">PERJAAN PEMELIHARAAN</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_pekerjaan_pemeliharaan ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">CATATAN</td>
                <td colspan="3"> : {{ $pemeliharaan->panel_gtt_catatan ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="8" class="bold-text">TAHAN ISOLASI TRAFO (MΩ)</td>
            </tr>
            <tr>
                <td colspan="5">TRAFO I</td>
                <td colspan="1">P-B: {{ $pemeliharaan->tahan_isolasi_trafo_1_pb ?? 'No Data' }}</td>
                <td colspan="1">S-B: {{ $pemeliharaan->tahan_isolasi_trafo_1_sb ?? 'No Data' }}</td>
                <td colspan="1">P-S: {{ $pemeliharaan->tahan_isolasi_trafo_1_ps ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">TRAFO II</td>
                <td colspan="1">P-B: {{ $pemeliharaan->tahan_isolasi_trafo_2_pb ?? 'No Data' }}</td>
                <td colspan="1">S-B: {{ $pemeliharaan->tahan_isolasi_trafo_2_sb ?? 'No Data' }}</td>
                <td colspan="1">P-S: {{ $pemeliharaan->tahan_isolasi_trafo_2_ps ?? 'No Data' }}</td>
            </tr>
            <tr>
                <td colspan="5">TRAFO III</td>
                <td colspan="1">P-B: {{ $pemeliharaan->tahan_isolasi_trafo_3_pb ?? 'No Data' }}</td>
                <td colspan="1">S-B: {{ $pemeliharaan->tahan_isolasi_trafo_3_sb ?? 'No Data' }}</td>
                <td colspan="1">P-S: {{ $pemeliharaan->tahan_isolasi_trafo_3_ps ?? 'No Data' }}</td>
            </tr>

        </table>
        
    </div>
</body>

</html>