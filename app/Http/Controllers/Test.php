<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WktTdkBersedia;
use Illuminate\Support\Facades\Validator;
use App\Models\PenjadwalanModel;
use App\Http\Controllers\Penjadwalan;

class Test extends Controller
{
    public function index()
    {
        //get data from model penjadwalan
        $data = new PenjadwalanModel();
        $posts = $data -> GetJadwal();

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'List Jadwal',
            'data'    => $posts
        ], 200);
        
    }

    /**
     * index
     *
     * @return void
     */
    // public function index()
    // {
    //     $p = new PenjadwalanModel();
    //     //make response JSON
    //     return response()->json([
    //         // 'jam' => $p->GetJam(),
    //         'kelas' => $p->GetKelas(['2011-2012']),
    //         // 'hari' => $p->GetHari(),
    //         // 'off' => $p->GetTimeOffDosen(),
    //         // 'delete' => $p->KosongkanJadwal(),
    //         // 'insert' => $p->InsertJadwal([2], [1], [1], [1]),
    //         // 'get' => $p->GetJadwal(),
    //         // 'ruang' => $p->GetRuang("TEORI")
    //     ], 200);
    // }
}