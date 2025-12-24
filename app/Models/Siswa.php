<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';
    protected $guarded = ['id_siswa'];

    // Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }
}