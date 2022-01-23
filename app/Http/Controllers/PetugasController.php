<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Petugas;

class PetugasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $petugas = Petugas::all();
        return Response()->json(['data'=>$petugas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_petugas'  => 'required',
            'no_hp'         => 'required|min:10',
            'username'      => 'required',
            'password'      => 'required|min:4',
        ]);

        $simpan                 = new Petugas;
        $simpan->nama_petugas   = $request->nama_petugas;
        $simpan->no_hp          = $request->no_hp;
        $simpan->username       = $request->username;
        $simpan->password       = Hash::make($request ->password);
        $simpan->save();

        if($simpan){
            return Response()->json(['status'=>'berhasil']);
        }else{
            return Response()->json(['status'=>'gagal']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $petugas = Petugas::find($id);
        return Response()->json(['data'=>$petugas]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_petugas)
    {
        $request->validate([
            'nama_petugas'     => 'required',
            'no_hp'            => 'required|min:10',
            'username'         => 'required',
            'password'         => 'required|min:4',
        ]);
        
        $update = Petugas::find($id_petugas);
        
        $update->nama_petugas  = $request->nama_petugas;
        $update->no_hp         = $request->no_hp;
        $update->username      = $request->username;
        $update->password      = Hash::make($request ->password);
        $update->update();

        if($update){
            return Response()->json(['status'=>'update berhasil']);
        }else{
            return Response()->json(['status'=>'gagal']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_petugas)
    {
        $data = Petugas::find($id_petugas);
        $status = $data->delete();

        if ($status) {
            return response()->json(['status'=>'Data Berhasil Dihapus']);
        } else {
            return response()->json(['status'=>'Data Gagal Dihapus']);
        }
    }
}
