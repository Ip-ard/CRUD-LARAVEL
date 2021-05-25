<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\guru; 


class GuruController extends Controller
{
    public function profile($id)
    {
    	$guru = guru::find($id);
    	return view('guru.profile',['guru' => $guru]);
    }
}
