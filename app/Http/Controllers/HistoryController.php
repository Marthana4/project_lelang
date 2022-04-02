<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\History;
use App\Models\Lelang;
use App\Models\Barang;
use App\Models\User;
use JWTAuth;
use Auth;
use DB;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $history = History::select(
            'history.id_history as id_history',
            'lelang.id_lelang as id_lelang',
            'barang.id_barang as id_barang',
            'barang.nama_barang as nama_barang',
            'lelang.tgl_lelang as tgl_lelang',
            'lelang.harga_akhir as harga_akhir',
            'users.id as id_pengguna',
            'users.nama as nama',
            'history.penawaran_harga as penawaran_harga',
            'history.status_pemenang as status_pemenang'
        )
        
        ->join('barang', 'history.id_barang', '=', 'barang.id_barang')
        ->join('users', 'history.id_pengguna', '=', 'users.id')
        ->join('lelang', 'history.id_lelang', '=', 'lelang.id_lelang')
        ->get();
        return Response()->json($history);
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
        $cek = DB::table('lelang')->where('id_lelang', $request->id_lelang)
                ->join('barang', 'lelang.id_barang', '=', 'barang.id_barang')->first();
        
        $max = DB::table('history')->where('id_lelang', $request->id_lelang)
        ->max('penawaran_harga');
        $row = DB::table('history')->where('id_lelang', $request->id_lelang)->count();
        if ($row==0) {
            if ($request->penawaran_harga < $cek->harga_awal) {
                return response()->json(['gagal','Harga tidak boleh kurang dari harga awal']);//tambahnotif
            }
        } else {
            if ($request->penawaran_harga < $max) {
                return response()->json(['gagal','Harga tidak boleh kurang dari harga penawaran sebelumnya']);//tambahnotif
            }
        }
        $simpan = new History;
        $simpan->id_lelang = $request->id_lelang;
        $simpan->id_barang = $request->id_barang;
        // $simpan->id_pengguna = $request->id_pengguna;
        $simpan->id_pengguna = $this->user->id;
        $simpan->penawaran_harga = $request->penawaran_harga;
        $simpan->save();
    
        $data = History::where('id_history', '=', $simpan->id_history)->first();

        if ($data) {
            return response()->json([
                'success' => true,
            'message' => 'Bid data sucessfully added',
            'data' => $data
            ]);
        } else {
            return response()->json([
                'success' => false,
            'message' => 'Your bid must be bigger',
            'data' => $data
            ]);
        }
    }

    public function status($id)
    {
        $update_menang = DB::table('history')->where('id_history', $id)->update([
            'status_pemenang' => 'menang'
        ]);

        $cek = DB::table('history')->where('id_history', $id)->first();
        
        $id_lelang = $cek->id_lelang;

        $update_kalah = DB::table('history')->where('id_lelang', $id_lelang)->where('status_pemenang', 'proses')->update([
            'status_pemenang' =>'kalah'
        ]);

        // $ceklagi=DB::table('history')->where('id_history', $id)->first();

        DB::table('lelang')->where('id_lelang', $id_lelang)->update([
            'harga_akhir'=>$cek->penawaran_harga,
            'id_pengguna'=>$cek->id_pengguna,
            'status'=>'ditutup'
        ]);

        $data = [$update_menang, $update_kalah];

        return Response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $history=DB::table('history')->leftJoin('users', 'history.id_pengguna', '=', 'users.id')
                    ->leftJoin('barang', 'history.id_barang', '=', 'barang.id_barang')
                    ->select('users.*', 'history.*')
                    ->where('id_lelang', $id)
                    ->where('history.id_lelang', '=', $id)
                    ->orderBy('history.penawaran_harga', 'DESC')
                    ->get();

        return Response()->json($history);
    }

    public function show2()
    {
        $history=DB::table('history')->leftJoin('users', 'history.id_pengguna', 'users.id')
                    ->leftJoin('barang', 'history.id_barang', 'barang.id_barang')
                    ->select('users.*', 'history.*', 'barang.*')
                    ->where('id_pengguna', Auth::user()->id)
                    ->orderBy('history.id_history', 'ASC')
                    ->get();

        return Response()->json($history);
    }

    public function show3($id)
    {
        $history=DB::table('history')->leftJoin('users', 'history.id_pengguna', 'users.id')
                    ->leftJoin('barang', 'history.id_barang', 'barang.id_barang')
                    ->select('users.*', 'history.*', 'barang.*')
                    ->where('history.id_lelang', '=', $id)
                    ->orderBy('history.id_history', 'ASC')
                    ->get();

        return Response()->json($history);
    }

    public function show4($id)
    {
        $penawaran = DB::table('history')->where('id_lelang', $id)->where('id_pengguna', Auth::user()->id)->first();
        return Response()->json($penawaran);
    }

    public function reporthistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_pemenang' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $status_pemenang = $request->status_pemenang;
        
        $data = DB::table('history') ->join('barang', 'history.id_barang', '=', 'barang.id_barang')
                                    ->join('users', 'history.id_pengguna', '=', 'users.id')
                                    ->join('lelang', 'history.id_lelang', '=', 'lelang.id_lelang')
                                    ->select('history.*', 'barang.nama_barang', 'lelang.tgl_lelang', 'lelang.harga_akhir', 'users.nama', 'users.no_hp')
                                    ->where('status_pemenang', '=', $status_pemenang)
                                    ->get();

        return response()->json($data);
    }
}
