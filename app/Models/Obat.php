<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'id_apotek',
        'id_admin',
        'nama_obat',
        'kategori',
        'harga',
        'stok',
        'status',
        'gambar_obat',
    ];

    // Relasi ke model Apotek
    public function apotek()
    {
        return $this->belongsTo(Apotek::class, 'id_apotek', 'id_apotek');
    }

    // Relasi ke model Admin
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id');
    }
}
