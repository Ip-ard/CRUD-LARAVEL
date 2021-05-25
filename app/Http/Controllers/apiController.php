<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class apiController extends Controller
{
    public function editnilai(Request $request, $id)
    {
    	$siswa = \App\Models\siswa::find($id);
    	$siswa->mapel()->updateExistingPivot($request->pk,['nilai' => $request->value]);
    }
}
