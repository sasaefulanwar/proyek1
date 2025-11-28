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
        'id_apotek',
        'username',
        'password',
        'email',
        'google_id',
        'temp_password',
        'nama_penanggung_jawab',
        'nama_apotek',
        'role',
        'status',
    ];

    public function apotek()
    {
        return $this->belongsTo(Apotek::class, 'id_apotek', 'id_apotek');
    }
}
