<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mapel extends Model
{
    protected $table = 'mapel';
    protected $fillable = ['kode', 'nama', 'semester'];

    public function siswa()
    {
        return $this->belongsToMany(siswa::class)->withPivot(['nilai']);
    }

    public function guru()
    {
    	return $this->belongsTo(guru::class);
    }
}
