<?php

namespace App\Models\HistoryData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDataPemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'history_pemeliharaan';

    protected $fillable = [
        'id_pemeliharaan',
        'data_lama',
        'aksi',
        'diubah_oleh',
        'keterangan',
    ];

    protected $casts = [
        'data_lama' => 'array',
    ];
}
