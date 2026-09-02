<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanHariLibur extends Model
{
    protected $table = 'pengaturan_hari_libur';

    protected $fillable = ['hari', 'keterangan'];
}