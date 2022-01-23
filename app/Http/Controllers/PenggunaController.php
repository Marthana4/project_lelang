<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Pengguna;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pengguna = Pengguna::all();
        return Response()->json(['data'=>$pengguna]);
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
            'nama_pengguna' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|min:10',
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        $simpan = new Pengguna;
        $simpan->nama_pengguna = $request->nama_pengguna;
        $simpan->alamat = $request->alamat;
        $simpan->no_hp = $request->no_hp;
        $simpan->username = $request->username;
        $simpan->password = Hash::make($request ->password);
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
        $pengguna = Pengguna::find($id);
        return Response()->json(['data'=>$pengguna]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
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
    public function update(Request $request, $id_pengguna)
    {
        $request->validate([
            'nama_pengguna' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required|min:10',
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        $update = Pengguna::find($id_pengguna);
        
        $update->nama_pengguna = $request->nama_pengguna;
        $update->alamat        = $request->alamat;
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
    public function destroy($id_pengguna)
    {
        $data = Pengguna::find($id_pengguna);
        $status = $data->delete();

        if ($status) {
            return response()->json(['status'=>'Data Berhasil Dihapus']);
        } else {
            return response()->json(['status'=>'Data Gagal Dihapus']);
        }
    }
}
