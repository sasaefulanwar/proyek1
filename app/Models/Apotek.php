<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apotek extends Model
{
    use HasFactory;

    protected $table = 'apotek';

    protected $primaryKey = 'id_apotek';

    protected $fillable = [
        'nama_apotek',
        'alamat',
        'telepon',
        'email',
        'jam_operasional',
        'deskripsi',
        'status_buka',
        'foto_apotek',
    ];

    // Relasi ke tabel admin (1 apotek punya 1 admin apotek)
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_apotek', 'id_apotek');
    }

    public function obats()
    {
        return $this->hasMany(Obat::class, 'id_apotek', 'id_apotek');
    }
}
