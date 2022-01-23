<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "history";
    protected $primaryKey = 'id_history';
    protected $fillable = [
        'id_lelang',
        'id_barang',
        'id_pengguna',
        'status',
    ];
}
