<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = ['nama_belakang', 'nama_depan', 'jenis_kelamin', 'agama', 'alamat', 'avatar', 'user_id'];

    public function getavatar()
    {
        if (!$this->avatar) {
            return asset('images/default.jpg');
        }

        return asset('images/' . $this->avatar);
    }

    public function mapel()
    {
        return $this->belongsToMany(mapel::class)->withPivot(['nilai'])->withTimeStamps();
    }

    public function rataRataNilai()
    {
        //ambil Nilai2
        $total = 0;
        $hitung = 0;
        foreach ($this->mapel as $mapel) {
            $total += $mapel->pivot->nilai;
            $hitung++;
        }

        return $total != 0 ? round($total/$hitung) : $total;
    }

    public function nama_lengkap()
    {
        return $this->nama_depan.' '.$this->nama_belakang;
    }

    public function user()
    {
        return $this->belongsTo(user::class)->withDefault(['avatar' => 'default.jpg']);
    }
}
