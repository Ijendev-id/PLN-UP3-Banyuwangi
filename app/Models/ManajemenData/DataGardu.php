<?php

namespace App\Models\ManajemenData;

use App\Models\HistoryData\HistoryDataGardu;
use App\Models\ManajemenData\OmtPengukuran;
use App\Models\ManajemenData\Pemeliharaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataGardu extends Model
{
    use HasFactory;

    protected $table = 'data_gardu';
    public $timestamps = false; // <-- tambahkan ini

    protected $fillable = [
        'gardu_induk',
        'kd_trf_gi',
        'kd_pylg',
        'kd_gardu',
        'daya_trafo',
        'jml_trafo',
        'alamat',
        'desa',
        'no_seri',
        'berat_total',
        'berat_minyak',
        'hubungan',
        'impedansi',
        'tegangan_tm',
        'tegangan_tr',
        'frekuensi',
        'tahun',
        'merek_trafo',
        'beban_kva_trafo',
        'persentase_beban',
        'section_lbs',
        'fasa',
        'nilai_sdk_utama',
        'nilai_primer',
        'tap_no',
        'tap_kv',
        'rekondisi_preman',
        'bengkel',
        'merek_trafo_2',
        'merek_trafo_3',
        'no_seri_2',
        'no_seri_3',
        'tahun_2',
        'tahun_3',
        'berat_minyak_2',
        'berat_minyak_3',
        'berat_total_2',
        'berat_total_3'
    ];

    public function pengukuran()
    {
        return $this->hasOne(OmtPengukuran::class, 'kd_gardu', 'kd_gardu');
    }

    public function pemeliharaan()
    {
        return $this->hasOne(Pemeliharaan::class, 'kd_gardu', 'kd_gardu');
    }

    public function history()
    {
        return $this->hasMany(HistoryDataGardu::class, 'id_data_gardu');
    }
}
