<?php

namespace App\Models\ManajemenData;

use App\Models\HistoryData\HistoryDataOmtPengukuran;
use App\Models\ManajemenData\DataGardu;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OmtPengukuran extends Model
{
    use HasFactory;

    protected $table = 'omt_pengukuran';

    protected $fillable = [
        'kd_gardu',
        'waktu_pengukuran',
        'ian',
        'iar',
        'ias',
        'iat',
        'ibn',
        'ibr',
        'ibs',
        'ibt',
        'icn',
        'icr',
        'ics',
        'ict',
        'idn',
        'idr',
        'ids',
        'idt',
        'vrn',
        'vrs',
        'vsn',
        'vst',
        'vtn',
        'vtr',
        'iun',
        'iur',
        'ius',
        'iut'
    ];

    public function gardu()
    {
        return $this->belongsTo(DataGardu::class, 'kd_gardu', 'kd_gardu');
    }

    public function history()
    {
        return $this->hasMany(HistoryDataOmtPengukuran::class, 'id_omt_pengukuran');
    }

    public function getBebanKvaTrafoAttribute()
    {
        return $this->gardu->beban_kva_trafo ?? null;
    }

    public function getPersentaseBebanAttribute()
    {
        return $this->gardu->persentase_beban ?? null;
    }
}
