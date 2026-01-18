<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Buku extends Model
{
    use SoftDeletes;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    protected $guarded = ['id_buku'];

    // Pastikan 'deleted_at' dianggap tanggal
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'judul',
        'kategori',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'jumlah_stok',
        'deskripsi',
        'url_sampul',
        'rak',
    ];
}
