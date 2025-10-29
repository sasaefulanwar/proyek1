<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apotek extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'apotek';

    // Primary key
    protected $primaryKey = 'id_apotek';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama_apotek',
        'alamat',
        'telepon',
        'email',
        'jam_operasional',
        'deskripsi',
        'foto_apotek',
    ];

    // Relasi ke tabel admin (1 apotek punya 1 admin apotek)
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_apotek', 'id_apotek');
    }
}
