<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = Admin::all();
        return Response()->json(['data'=>$admin]);
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
            'nama_admin' => 'required',
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        $simpan = new Admin;
        $simpan->nama_admin = $request->nama_admin;
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
        $admin = Admin::find($id);
        return Response()->json(['data'=>$admin]);
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
    public function update(Request $request, $id_admin)
    {
        $request->validate([
            'nama_admin' => 'required',
            'username' => 'required',
            'password' => 'required|min:4',
        ]);

        $update = Admin::find($id_admin);
        
        $update->nama_admin    = $request->nama_admin;
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
    public function destroy($id_admin)
    {
        $data = Admin::find($id_admin);
        $status = $data->delete();

        if ($status) {
            return response()->json(['status'=>'Data Berhasil Dihapus']);
        } else {
            return response()->json(['status'=>'Data Gagal Dihapus']);
        }
    }
}
