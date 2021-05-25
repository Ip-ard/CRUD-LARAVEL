<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use App\Exports\siswaExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\siswa;

class siswaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('cari')) {
            $data_siswa = \App\Models\siswa::where('nama_depan', 'LIKE', '%' . $request->cari . '%')->paginate(10);
        } else {
            $data_siswa = \App\Models\siswa::all();
        }
        return view('siswa.index', ['data_siswa' => $data_siswa]);
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'nama_depan' => 'min:5',
            'nama_belakang' => 'required',
            'email' => 'required|email|unique:users',
            'jenis_kelamin' => 'required',
            'agama' => 'required',
            'avatar' => 'mimes:jpg,png',
        ]);
        //Insert ke table users
        $user = new \App\Models\User;
        $user->role = 'siswa';
        $user->name = $request->nama_depan;
        $user->email = $request->email;
        $user->password = bcrypt('rahasia');
        $user->save();

        //Insert ke table siswa 
        $request->request->add(['user_id' => $user->id]);
        $siswa = \App\Models\siswa::create($request->all());
        if ($request->hasFile('avatar')) {
            $request->file('avatar')->move('images/', $request->file('avatar')->getClientOriginalName());
            $siswa->avatar = $request->file('avatar')->getClientOriginalName();
            $siswa->save();
        }
        return redirect('/siswa')->with('sukses', 'Data Berhasil Di Input');
    }

    public function edit($id)
    {
        $siswa = \App\Models\siswa::find($id);
        return view('siswa/edit', ['siswa' => $siswa]);
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $siswa = \App\Models\siswa::find($id);
        $siswa->update($request->all());
        if ($request->hasFile('avatar')) {
            $request->file('avatar')->move('images/', $request->file('avatar')->getClientOriginalName());
            $siswa->avatar = $request->file('avatar')->getClientOriginalName();
            $siswa->save();
        }
        return redirect('/siswa')->with('sukses', 'Data Berhasil Di Update');
    }

    public function delete($id)
    {
        $siswa = \App\Models\siswa::find($id);
        $siswa->delete($siswa);
        return redirect('/siswa')->with('sukses', 'Data Berhasil Di Hapus');
    }

    public function profile($id)
    {
        $siswa = \App\Models\siswa::find($id);
        $matapelajaran = \App\Models\mapel::all();

        // Menyimpkan Data Untuk Chart
        $categories = [];
        $data = [];

        foreach ($matapelajaran as $mp) {
        if($siswa->mapel()->wherePivot('mapel_id',$mp->id)->first()){
            $categories[] = $mp->nama;
            $data[] = $siswa->mapel()->wherePivot('mapel_id', $mp->id)->first()->pivot->nilai;
            }
        }

        //dd($data);

        return view('siswa.profile', ['siswa' => $siswa, 'matapelajaran' => $matapelajaran, 'categories' => $categories, 'data' => $data]);
    }

    public function addnilai(Request $request, $idsiswa)
    {
        $siswa = \App\Models\siswa::find($idsiswa);
        if ($siswa->mapel()->where('mapel_id', $request->mapel)->exists()) {
            return redirect('siswa/' . $idsiswa . '/profile')->with('error', 'Data mata pelajaran sudah ada.');
        }
        $siswa->mapel()->attach($request->mapel, ['nilai' => $request->nilai]);

        return redirect('siswa/' . $idsiswa . '/profile')->with('sukses', 'Data Nilai Berhasil Dimasukkan');
    }

    public function deletenilai($idsiswa, $idmapel)
    {
        $siswa = \App\Models\siswa::find($idsiswa);
        $siswa->mapel()->detach($idmapel);
        return redirect()->back()->with('sukses', 'Data Berhasil Di Hapus');
    }

    public function exportExcel() 
    {
        return Excel::download(new siswaExport, 'siswa.xlsx');
    }

    public function exportPdf()
    {
        $siswa = \App\Models\siswa::all();
        $pdf = PDF::loadView('export.siswapdf',['siswa' => $siswa]);
        return $pdf->download('siswa.pdf');
    }

    public function getdatasiswa()
    {
        $siswa = siswa::select('siswa.*');

        return \DataTables::eloquent($siswa)
        ->addColumn('nama_lengkap',function($s){
            return $s->nama_depan.''.$s->nama_belakang;
        })
        ->addColumn('rata2_nilai',function($s){
            return $s->rataRataNilai();
        })
        ->addColumn('aksi',function($s){
            return '<a href="/siswa/{{$siswa->id}}/edit" class="btn btn-warning">Edit</a>';
        })
        ->rawColumns(['nama_lengkap','rata2_nilai','aksi'])
        ->toJson();
    }

    public function profilsaya()
    {
        $siswa = auth()->user()->siswa;
        return view('siswa.profilsaya',compact(['siswa']));
    }

    public function importexcel(Request $request)
    {
        Excel::import(new \App\Imports\siswaimport,$request->file('data_siswa'));
    }
}
