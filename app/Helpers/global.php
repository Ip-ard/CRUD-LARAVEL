<?php

use App\Models\siswa;
use App\Models\Guru;

function ranking5Besar()
{
	$siswa = siswa::all();
	$siswa->map(function($s){
		$s->rataRataNilai = $s->rataRataNilai();
		return $s;
	});
	$siswa = $siswa->sortByDesc('rataRataNilai')->take(5);
	return $siswa;
}

function totalSiswa()
{
	return siswa::count();
}

function totalGuru()
{
	return Guru::count();
}