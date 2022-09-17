<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jam;
use App\Models\Hari;
use App\Models\WktTdkBersedia;
use App\Models\Jadkul;
use App\Models\Ruang;
use  App\Models\Pengampu;
use App\Models\Matkul;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

class PenjadwalanModel extends Model

{
    public function __construct()
    {
    }

    //1
    // public function GetKelas($param) {
    //     $result = DB::table('pengampu')
    //     ->select('pengampu.kode', 'matakuliah.sks', 'pengampu.kode_dosen', 'matakuliah.jenis')
    //     ->leftJoin('matakuliah', 'pengampu.kode_mk', '=', 'matakuliah.kode')
    //     ->where([['matakuliah.semester', '2', 0], ['pengampu.tahun_akademik', '=', $param]])
    //     ->get();
    //     return $result; 
    //     }

    //2
        public function GetKelas($semester, $tahun_akademik)
    {
        $result = DB::select('SELECT
        a.kode, 
        b.sks, 
        a.kode_dosen, 
        b.jenis
        FROM pengampu a
        LEFT JOIN matakuliah b ON a.kode_mk = b.kode
        WHERE b.semester  %2= :semester
        AND a.tahun_akademik = :tahun', ["semester" => 0, "tahun" => $tahun_akademik]);
    return $result;
    // echo $result;
    }

    //3
    // function GetKelas()
    // {
    //         $result = DB::select('SELECT
    //         a.kode, 
    //         b.sks, 
    //         a.kode_dosen, 
    //         b.jenis
    //         FROM pengampu a
    //         LEFT JOIN matakuliah b ON a.kode_mk = b.kode
    //         WHERE b.semester % 2 = 0
    //         AND a.tahun_akademik = "2011-2012"');
    //     return $result;
    //     echo $result;
    // }
    

    function GetJam()
    {
        $result = DB::table('jam')
            ->select('kode')
            ->get();
        return $result;
    }

    function GetHari()
    {
        $result = DB::table('hari')
            ->select('kode')
            ->get();
        return $result;
    }

    function GetRuang($param)
    {
        $result = DB::table('ruang')
            ->select('kode')
            ->where('jenis', '=', $param)
            ->get();
        return $result;
    }

    function GetTimeOffDosen()
    {
        $result = DB::table('waktu_tidak_bersedia')
            ->select('kode_dosen', DB::raw('CONCAT_WS(\':\', kode_hari, kode_jam) AS kode_hari_jam'))
            ->get();
        return $result;
    }

    function KosongkanJadwal()
    {
        $result = DB::statement('TRUNCATE TABLE jadwalkuliah');
        return $result;
    }

    function InsertJadwal($kode_pengampu, $kode_jam, $kode_hari, $kode_ruang)
    {
        // $request = new \Illuminate\Http\Request();
        $result = DB::table('jadwalkuliah')->insert([
            'kode_pengampu'  => $kode_pengampu,
            'kode_jam'       => $kode_jam,
            'kode_hari'      => $kode_hari,
            'kode_ruang'     => $kode_ruang
        ]);
        return $result;
    }

    function GetJadwal()
    {
        $result = DB::select("SELECT e.nama AS hari,CONCAT('(',g.kode, '-', g.kode + c.sks - 1,')') AS sesi, CONCAT_WS('-', MID(g.range_jam,1,5), (SELECT MID(range_jam,7,5) FROM jam WHERE kode = g.kode + c.sks - 1)) AS jam_kuliah, c.nama AS nama_mk, c.sks AS sks, c.semester AS semester, b.kelas AS kelas, d.nama AS dosen, f.nama AS ruang FROM jadwalkuliah a LEFT JOIN pengampu b ON a.kode_pengampu = b.kode LEFT JOIN matakuliah c ON b.kode_mk = c.kode LEFT JOIN dosen d ON b.kode_dosen = d.kode LEFT JOIN hari e ON a.kode_hari = e.kode LEFT JOIN ruang f ON a.kode_ruang = f.kode LEFT JOIN jam g ON a.kode_jam = g.kode ORDER BY e.kode ASC, Jam_Kuliah ASC");

        return $result;
    }

    // function GetJam()
    // {
    //     $result = Jam::get();
    //     return $result;
    // }

    // function GetHari()
    // {
    //     $result = Hari::get();
    //     return $result;
    // }

    // function GetRuang($param)
    // {
    //     $result = Ruang::all('jenis')
    //         ->where('jenis', $param);
    //     return $result;
    //     echo $result;
    // }

    // function GetKelas()
    // {
    //         $result = DB::select('SELECT
    //         a.kode, 
    //         b.sks, 
    //         a.kode_dosen, 
    //         b.jenis
    //         FROM pengampu a
    //         LEFT JOIN matakuliah b ON a.kode_mk = b.kode
    //         WHERE b.semester % 2 = 0
    //         AND a.tahun_akademik = "2011-2012"');

    //     // $result =DB::table("pengampu")
    //     //     ->leftJoin("matakuliah", function($join){
    //     //         $join->on("kode_mk", "=", "kode");
    //     //     })
    //     //     ->select("kode", "kode_dosen as pengampu", "sks", "jenis as matakuliah")
    //     //     ->where("b semester % 2", 0)
    //     //     ->where("a tahun_akademik", $param)
    //     //     ->get();    
    //     return $result;
    //     echo $result;
    // }

    // function GetTimeOffDosen()
    // {
    //     $result = WktTdkBersedia::get();
    //     return  $result;
    // }

    // function KosongkanJadwal()
    // {
    //     $result = Jadkul::truncate();
    //     return $result;
    // }

    // function InsertJadwal($id_pengampu, $id_jam, $id_hari, $id_ruang) {
    //     // $request = new \Illuminate\Http\Request();
    //         $result = DB::table('jadkul')->insert([
    //                 'id_pengampu'  => $id_pengampu,
    //                 'id_jam'       => $id_jam,
    //                 'id_hari'      => $id_hari,
    //                 'id_ruang'     => $id_ruang
    //                 ]);
    //         return $result;
    // }


    // function GetJadwal()
    // {
    //     $result = DB::select("SELECT e.nama AS hari,CONCAT('(',g.kode, '-', g.kode + c.sks - 1,')') AS sesi, CONCAT_WS('-', MID(g.range_jam,1,5), (SELECT MID(range_jam,7,5) FROM jam WHERE kode = g.kode + c.sks - 1)) AS jam_kuliah, c.nama AS nama_mk, c.sks AS sks, c.semester AS semester, b.kelas AS kelas, d.nama AS dosen, f.nama AS ruang FROM jadwalkuliah a LEFT JOIN pengampu b ON a.kode_pengampu = b.kode LEFT JOIN matakuliah c ON b.kode_mk = c.kode LEFT JOIN dosen d ON b.kode_dosen = d.kode LEFT JOIN hari e ON a.kode_hari = e.kode LEFT JOIN ruang f ON a.kode_ruang = f.kode LEFT JOIN jam g ON a.kode_jam = g.kode ORDER BY e.kode ASC, Jam_Kuliah ASC");

    //     return $result;
    //     echo $result;
    // }
}