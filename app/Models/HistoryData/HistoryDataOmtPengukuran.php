<?php

namespace App\Models\HistoryData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDataOmtPengukuran extends Model
{
    use HasFactory;

    protected $table = 'history_omt_pengukuran';

    protected $fillable = [
        'id_omt_pengukuran',
        'data_lama',
        'aksi',
        'diubah_oleh',
        'keterangan',
    ];

    protected $casts = [
        'data_lama' => 'array',
    ];
}
