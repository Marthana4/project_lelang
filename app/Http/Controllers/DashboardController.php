<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Barang;
use App\Models\History;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::where('level', '=', 'Pengguna')->count();
        $barang = Barang::count();
        $history = History::count();

        return response()->json([
            'user' => $user,
            'barang' => $barang,
            'history' => $history,
        ]);
    }

    public function history()
    {
        $penawaran = History::select('history.id_history as id_history',
        'lelang.id_lelang as id_lelang','barang.id_barang as id_barang','barang.nama_barang as nama_barang', 'lelang.tgl_lelang as tgl_lelang', 'lelang.harga_akhir as harga_akhir',
        'users.id as id_pengguna','users.nama as nama','history.penawaran_harga as penawaran_harga', 'history.status_pemenang as status_pemenang')
        
        ->join('barang', 'history.id_barang', '=', 'barang.id_barang')
        ->join('users', 'history.id_pengguna', '=', 'users.id')
        ->join('lelang', 'history.id_lelang', '=', 'lelang.id_lelang')
        ->where('status_pemenang', '=', 'proses')
        ->orderBy('lelang.tgl_lelang', 'ASC')
        ->get(5);
        return response()->json($penawaran);
    }
}