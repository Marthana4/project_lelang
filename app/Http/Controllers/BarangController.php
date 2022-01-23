<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Barang;

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
        return Response()->json(['data'=>$barang]);
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
            'tanggal_daftar' => 'required',
            'foto' => 'required|mimes:jpeg,png',
            'harga_awal' => 'required',
            'deskripsi' => 'required',
        ]);

        if($foto = $request->file('foto')){
            $destination_path = public_path('/foto/');
            $foto_barang = date('Ymd'). '.' . $foto->getClientOriginalExtension();
            $foto -> move($destination_path, $foto_barang);
        }

        $simpan = new Barang;
        $simpan->nama_barang = $request->nama_barang;
        $simpan->tanggal_daftar = $request->tanggal_daftar;
        $simpan->foto = $foto_barang;
        $simpan->harga_awal = $request->harga_awal;
        $simpan->deskripsi = $request->deskripsi;
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
        //
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
        $model = Barang::where('id_barang',$id)->get();
        if($request->hasFile('foto')){
            $resorce = $request->file('foto');
            $namefile =  $resorce->getClientOriginalName();// mengambil nama file original yang diupload
            $resorce->move(\base_path() ."/public/foto", $namefile);// upload ke directory ke public/fotos
            $path = public_path().'\\foto\\';// buat directory baru
            $file_old = $path.$model->foto;// menggabung string dari alamat file yang lama
            if(file_exists($path) && $model->foto != null) { // pengecekan apakah directory file yang lama ada atau data kolom foto di database sudah ada
                unlink($file_old);
            }
            // edit database sesuai dengan data
            $update = Menu::where('id_menu', $id)->update([
                "name_food" => $request->name_food,
                "foto" => $namefile,
                "price" => $request->price,
                "deskripsif" => $request->deskripsif,
                "id_store" => $request->name_store,
            ]);
        }else {
            $update = Menu::where('id_menu', $id)->update([
                "name_food" => $request->name_food,
                "price" => $request->price,
                "deskripsif" => $request->deskripsif,
                "id_store" => $request->name_store,
            ]);
        }
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
