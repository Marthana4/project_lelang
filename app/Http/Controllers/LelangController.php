<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lelang;
use App\Models\Barang;
use App\Models\History;
use Carbon\Carbon; //memanggil tanggal

class LelangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lelang = Lelang::select('lelang.id_lelang as id_lelang',
        'barang.id_barang as id_barang','barang.nama_barang as nama_barang', 'lelang.tgl_lelang as tgl_lelang', 'lelang.harga_akhir as harga_akhir',
        'pengguna.id_pengguna as id_pengguna', 'petugas.id_petugas as petugas', 'lelang.status as status')
        ->join('barang', 'lelang.id_barang', '=', 'barang.id_barang')
        ->join('users', 'lelang.id_pengguna', '=', 'users.id_pengguna')
        ->join('users', 'lelang.id_petugas', '=', 'users.id_petugas')
        ->get();
        return Response()->json(['data'=>$lelang]);
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
        // $request -> validate([
        //     'id_barang' => 'required',
        //     'tgl_lelang' => 'required',
        //     // 'harga_akhir' => 'required',
        //     // 'id_pengguna' => 'required',
        //     'id_petugas' => 'required',
        //     // 'status' => 'required',
        // ]);

        $cek = Lelang::where('lelang.id_barang',$request->id_barang)
                ->where('status','dibuka')
                ->join('barang','lelang.id_barang','=','barang.id_barang')
                ->get();
        
        $cek2 = count($cek);
        
        if($cek2 == 1){
            return Response()->json(['status'=>'gagal, barang sudah di lelang']);

        }else{
            $simpan = new Lelang;
            $simpan->id_barang = $request->id_barang;
            $simpan->tgl_lelang = Carbon::now(); //diganti pake now
            // $simpan->harga_akhir = 10000;
            $simpan->id_pengguna = 1;
            $simpan->id_petugas = $request->id_petugas;
            $simpan->status = 'dibuka';
            $simpan->save();
                
            // return Response()->json(['status'=>'berhasil']);

            if ($simpan) {
                return Response()->json(['status'=>'berhasil']);
            } else {
                return Response()->json(['status'=>'gagal']);
            }
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
        $lelang = Lelang::select('lelang.id_lelang as id_lelang',
        'barang.id_barang as id_barang','barang.nama_barang as nama_barang', 'lelang.tgl_lelang as tgl_lelang', 'lelang.harga_akhir as harga_akhir',
        'pengguna.id_pengguna as id_pengguna', 'petugas.id_petugas as petugas', 'lelang.status as status')
        ->join('barang', 'lelang.id_barang', '=', 'barang.id_barang')
        ->join('pengguna', 'lelang.id_pengguna', '=', 'pengguna.id_pengguna')
        ->join('petugas', 'lelang.id_petugas', '=', 'petugas.id_petugas')
        ->first();
        return Response()->json(['data'=>$lelang]);
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Lelang::find($id);
        $status = $data->delete();

        if ($status) {
            return response()->json(['status'=>'Data berhasil dihapus']);
        } else {
            return response()->json(['status'=>'Data gagal dihapus']);
        }
    }

    // public function penawaran(Request $request, $id_lelang){
    //     $request -> validate([
    //         'harga_akhir' => 'required',
    //     ]);

    //     $lelang = Lelang::find($id_lelang);
    //     $harga = $request -> harga_akhir;

    //     if ($harga >= $lelang->barang->harga_awal) {
    //         $data = new History;
    //         $data->id_lelang = $id_lelang;
    //         $data->id_barang = $id_barang;
    //         $data->id_pengguna = $id_pengguna;
    //         $data->penawaran_harga = $penawaran_harga;
    //         $data->save();

    //         if($data){
    //             if ($harga > $lelang->harga_akhir) {
    //                 $lelang->harga_akhir = $harga;
    //                 $lelang->id_pengguna = $id_pengguna;
    //                 $status = $lelang->update();

    //                 if ($status) {
    //                     return Response()->json(['status'=>'berhasil']);
    //                 } else {
    //                     return Response()->json(['status'=>'gagal']);
    //                 }
    //             }
    //         } else{
    //             return Response()->json(['status'=>'gagal tambah']);
    //         }
    //     } else{
    //         return Response()->json(['status'=>'gagal beneran']);
    //     }
    // }
}
