<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\siswa;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboards.index');
    }
}
