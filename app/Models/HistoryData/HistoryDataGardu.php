<?php

namespace App\Models\HistoryData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDataGardu extends Model
{
    use HasFactory;

    protected $table = 'history_data_gardu';

    protected $fillable = [
        'id_data_gardu',
        'data_lama',
        'aksi',
        'diubah_oleh',
        'keterangan',
    ];

    protected $casts = [
        'data_lama' => 'array',
    ];
}
