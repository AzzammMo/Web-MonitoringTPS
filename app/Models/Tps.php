<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini
    protected $table = 'tps';

    // Tentukan kolom mana yang dapat diisi (mass assignment)
    protected $fillable = [
        'namaTps',
        'lat',
        'lng',
        'alamat',
    ];

    // Jika Anda menggunakan timestamp otomatis, tambahkan ini
    public $timestamps = true;
}
