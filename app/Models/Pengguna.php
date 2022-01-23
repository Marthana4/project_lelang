<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "pengguna";
    protected $primaryKey = 'id_pengguna';
    protected $fillable = [
        'nama_pengguna',
        'alamat',
        'no_hp',
        'username',
        'password',
    ];
}
