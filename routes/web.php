<?php

use App\Http\Controllers\authController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', 'siteController@home');
Route::get('/register', 'siteController@register');
Route::post('/postregister', 'siteController@postregister');

Route::get('/login', 'authController@login')->name('login');
Route::post('/postlogin', 'authController@postlogin');
Route::get('/logout', 'authController@logout');

Route::group(['middleware' => ['auth', 'checkRole:admin']], function () {
    Route::get('/siswa', 'siswaController@index');
    Route::post('/siswa/create', 'siswaController@create');
    Route::get('/siswa/{id}/edit', 'siswaController@edit');
    Route::post('/siswa/{id}/update', 'siswaController@update');
    Route::get('/siswa/{id}/delete', 'siswaController@delete');
    Route::get('/siswa/{id}/profile', 'siswaController@profile');
    Route::post('/siswa/{id}/addnilai', 'siswaController@addnilai');
    Route::get('/siswa/{id}/{idmapel}/deletenilai', 'siswaController@deletenilai');
    Route::get('siswa/exportexcel', 'siswaController@exportExcel');
    Route::get('siswa/exportpdf', 'siswaController@exportPdf');
    Route::post('siswa/import', 'siswaController@importexcel')->name('siswa.import');
    Route::get('/guru/{id}/profile', 'GuruController@profile');
    Route::get('/posts', 'postController@index')->name('posts.index');
    Route::get('post/add',[
            'uses' => 'postController@add',
            'as' => 'posts.add',
        ]);
    Route::post('post/create',[
            'uses' => 'postController@create',
            'as' => 'posts.create',
        ]);
});

Route::group(['middleware' => ['auth', 'checkRole:admin,siswa']], function () {
    Route::get('/dashboard', 'DashboardController@index');
});

Route::group(['middleware' => ['auth','checkRole:siswa']],function(){
    Route::get('profilsaya','siswaController@profilsaya');
});

Route::get('getdatasiswa', [
    'uses' => 'siswaController@getdatasiswa',
    'as' => 'ajax.get.data.siswa',
]);

Route::get('/{slug}',[
    'uses' => 'siteController@singlepost',
    'as' => 'site.single.post'
]);