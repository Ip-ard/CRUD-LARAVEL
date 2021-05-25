<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\siswa;

class siswaimport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
        	if($key >= 3){
        		$tanggal_lahir = ($row[5] - 25569) * 86400;
        		siswa::create([
        			'nama_depan' => $row[1],
        			'nama_belakang' => ' ',
        			'jenis_kelamin' => $row[2],
        			'agama' => $row[3],
        			'alamat' => $row[4],
        			'tgl_lahir' => gmdate('Y-m-d',$tanggal_lahir),
        		]);
        	}
        }
    }
}
