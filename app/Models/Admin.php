<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'status',
        'nama_penanggung_jawab',
        'nama_apotek',
        'id_apotek',
        'temp_password',
    ];

    public function apotek()
    {
        return $this->belongsTo(Apotek::class, 'id_apotek', 'id_apotek');
    }
}
