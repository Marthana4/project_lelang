<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lelang;
use App\Models\Barang;
use App\Models\History;
use Carbon\Carbon; //memanggil tanggal
use Illuminate\Support\Facades\DB;
use JWTAuth;

// use Tymon\JWTAuth\Facades\JWTAuth;

class LelangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $users;

    public function __construct()
    {
        $this->users = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $lelang = DB::table('lelang')->join('barang', 'lelang.id_barang', '=', 'barang.id_barang')
                                    ->join('users', 'lelang.id_petugas', '=', 'users.id')
                ->select('lelang.*', 'barang.nama_barang', 'users.nama', 'barang.harga_awal', 'barang.deskripsi', 'barang.foto')
                ->get();

        return Response()->json($lelang);
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

        $cek = Lelang::where('lelang.id_barang', $request->id_barang)
                ->where('status', 'dibuka')
                ->join('barang', 'lelang.id_barang', '=', 'barang.id_barang')
                ->get();
        
        $cek2 = count($cek);
        
        if ($cek2 == 1) {
            return Response()->json(['status'=>'gagal, barang sudah di lelang']);
        } else {
            $simpan = new Lelang;
            $simpan->id_barang = $request->id_barang;
            $simpan->tgl_lelang = Carbon::now();
            $simpan->id_petugas = $this->users->id;
            $simpan->status = 'dibuka';
            $simpan->save();

            $data = Lelang::where('id_lelang', '=', $simpan->id_lelang)->first();
        
            return response()->json([
            'success' => true,
            'message' => 'Auction data sucessfully added',
            'data' => $data
        ]);
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
        $lelangcek = Lelang::select('*')->where('id_lelang', '=', $id)->first();
        $lelang = DB::table('lelang')->leftJoin('barang', 'lelang.id_barang', '=', 'barang.id_barang')
                                     ->leftJoin('users', 'lelang.id_pengguna', '=', 'users.id')
                                     ->select('lelang.*', 'barang.nama_barang', 'users.nama', 'barang.foto', 'barang.harga_awal')
                                     ->where('lelang.id_lelang', '=', $id)
                                     ->first();

        return Response()->json($lelang);
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
        $update=Lelang::find($id);

        $update->id_barang = $request->id_barang;
        $update->tgl_lelang = $request->tgl_lelang;
        $update->status = $request->status;

        $update->update();
        return response()->json([
            'success' => true,
            'message' => 'Auction data sucessfully updated',
            'data' => $update
        ]);
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

    public function reportlelang(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tahun' => 'required',
            'bulan' => 'required'
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $tahun = $request->tahun;
        $bulan = $request->bulan;
        
        $data = DB::table('lelang') ->leftJoin('barang', 'lelang.id_barang', '=', 'barang.id_barang')
                                    ->leftJoin('users', 'lelang.id_pengguna', '=', 'users.id')
                                    ->select('lelang.*', 'barang.nama_barang', 'users.nama', 'barang.foto', 'barang.harga_awal')
                                    // ->where('lelang.id_lelang', '=', $id)
                                    ->whereYear('tgl_lelang', '=', $tahun)
                                    ->whereMonth('tgl_lelang', '=', $bulan)
                                    ->get();

        return response()->json($data);
    }
}
