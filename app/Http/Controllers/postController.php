<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\post;

class postController extends Controller
{
    public function index()
    {
    	$posts = post::all();
    	return view('posts.index',compact(['posts']));
    }

    public function add()
    {
    	return view('posts.add');
    }

    public function create(Request $request)
    {
    	$post = post::create([
    		'title' => $request->title,
    		'content' => $request->content,
    		'user_id' => auth()->user()->id,
    		'thumbnail' => $request->thumbnail
    	]);

    	return redirect()->route('posts.index')->with('sukses','Post Berhasil Di Submit');
    }
}
