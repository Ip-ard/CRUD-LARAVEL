<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;

class siteController extends Controller
{
    public function home()
    {
        $posts = post::all();
    	return view('sites.home', compact(['posts']));
    }

    public function about()
    {
    	return view('sites.about');
    }

    public function register()
    {
    	return view('sites.register');
    }

    public function postregister(Request $request)
    {
    	// Input Pendaftaran Sebagai user dulu
    	$user = new \App\Models\User;
        $user->role = 'siswa';
        $user->name = $request->nama_depan;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $request->request->add(['user_id' => $user->id]);
        $siswa = \App\Models\siswa::create($request->all());

        \Mail::raw('Selamat Datang '.$user->name, function ($message) use($user) {
            $message->to($user->email, $user->name);
            $message->subject('Selamat Anda Sudah Terdaftar Di Sekolah Kami');
        });

        return redirect('/')->with('sukses','Data Pendaftaran Berhasil Di Kirim');
    }

    public function singlepost($slug)
    {
        $post = post::where('slug','=',$slug)->first();
        return view('sites.singlepost',compact(['post']));
    }
}
