<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    public $timestamps = true;
    protected $fillable = [
        'username',
        'password',
        'nama_penanggung_jawab',
        'nama_apotek',
        'role',
    ];
}
