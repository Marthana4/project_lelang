<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;
use Carbon\Carbon; //memanggil tanggal

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $barang = Barang::all();
        return Response()->json($barang);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
            'nama_barang' => 'required',
            'harga_awal' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($request->get('foto')) {
            $image = $request->get('foto');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('foto'))->save(public_path('foto/').$name);
        }

        $simpan = new Barang;
        $simpan->nama_barang = $request->nama_barang;
        $simpan->tanggal_daftar = Carbon::now();
        $simpan->foto = $name;
        $simpan->harga_awal = $request->harga_awal;
        $simpan->deskripsi = $request->deskripsi;
        $simpan->save();

        $data = Barang::where('id_barang', '=', $simpan->id_barang)->first();
        
        return response()->json([
            'success' => true,
            'message' => 'Item data sucessfully added',
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = Barang::find($id);
        return Response()->json($barang);
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
    public function update(Request $request, $id)
    {
        $update = Barang::find($id);

        $update->nama_barang        = $request->nama_barang;
        $update->tanggal_daftar     = $request->tanggal_daftar;
        $update->harga_awal         = $request->harga_awal;
        $update->deskripsi          = $request->deskripsi;
        
        if ($request->foto != $update->foto) {
            $image = $request->get('foto');
            $name = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            \Image::make($request->get('foto'))->save(public_path('foto/').$name);
            $update->foto = $name;
        }

        $update->update();
        return response()->json([
            'success' => true,
            'message' => 'Item data sucessfully updated',
            'data' => $update
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_barang)
    {
        $data = Barang::find($id_barang);
        $status = $data->delete();

        if ($status) {
            return response()->json(['status'=>'Data berhasil dihapus']);
        } else {
            return response()->json(['status'=>'Data gagal dihapus']);
        }
    }
}
