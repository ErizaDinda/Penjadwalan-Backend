<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Genetik;
use App\Models\PenjadwalanModel;
use Illuminate\Support\Facades\Validator;

class Penjadwalan extends Controller
{
	//1
	// public function uji()
	// {
	// 	$obj_penjadwalan = new PenjadwalanModel();
	// 	$data_kelas = $obj_penjadwalan->GetKelas('2011-2012');
	// 	$data_jam = $obj_penjadwalan->GetJam();
	// 	$data_hari = $obj_penjadwalan->GetHari();
	// 	$data_ruang_teori = $obj_penjadwalan->GetRuang('TEORI');
	// 	$data_ruang_praktikum = $obj_penjadwalan->GetRuang('LABORATORIUM');
	// 	$data_time_off_dosen = $obj_penjadwalan->GetTimeOffDosen();

	//2
	public function penjadwalan(Request $request)
	{
		$validator = Validator::make($request->all(),[
			'semester' => 'required',
			'tahun_akademik' => 'required'
		]);
		
		if ($validator->fails()) {
			return response()->json($validator->errors(), 400);
		};

		$i_semester       = $request->semester;
		$i_tahun_akademik = $request->tahun_akademik;

		$obj_penjadwalan = new PenjadwalanModel();
		$data_kelas = $obj_penjadwalan->GetKelas($i_semester,$i_tahun_akademik);
		$data_jam = $obj_penjadwalan->GetJam();
		$data_hari = $obj_penjadwalan->GetHari();
		$data_ruang_teori = $obj_penjadwalan->GetRuang('TEORI');
		$data_ruang_praktikum = $obj_penjadwalan->GetRuang('LABORATORIUM');
		$data_time_off_dosen = $obj_penjadwalan->GetTimeOffDosen();

		//   return $data_hari; die;

		/*   var_dump($data_kelas);
        var_dump($data_jam);
        var_dump($data_hari);
        var_dump($data_ruang_teori);
        var_dump($data_ruang_praktikum);
        var_dump($data_time_off_dosen);
        die;*/

		if (count($data_kelas) == 0) {

			$data['msg'] = 'Tidak Ada Data dengan Semester dan Tahun Akademik ini <br>Data yang tampil dibawah adalah data dari proses sebelumnya';
			echo $data['msg'];

			//redirect(base_url() . 'web/penjadwalan','reload');
		} else {
			$jenis_semester = '';
			$tahun_akademik = '';
			$jumlah_populasi = 10;
			$crossOver = 0.70;
			$mutasi = 0.40;
			$jumlah_generasi = 10000;

			$genetik = new Genetik(
				$jenis_semester,
				$tahun_akademik,
				$jumlah_populasi,
				$crossOver,
				$mutasi,
				//~BUG!
				/*                               
                             1 senin 5
                             2 selasa 4
                              3 rabu 3
                              4 kamis 2
                              5 jumat 1                                 
                             */
				5, //kode hari jumat                                
				'4-5-6', //kode jam jumat
				//jam dhuhur tidak dipake untuk sementara
				6
			); //kode jam dhuhur
			$genetik->AmbilData($data_kelas, $data_jam, $data_hari, $data_ruang_teori, $data_ruang_praktikum, $data_time_off_dosen);
			$genetik->Inisialisai();

			date_default_timezone_set("Asia/Jakarta");
			$time_start = microtime(true);
			$date_start = date('m/d/Y h:i:s a', time());

			$total_waktu_fitness = 0;
			$total_waktu_seleksi = 0;
			$total_waktu_crossover = 0;
			$total_waktu_mutasi = 0;

			$found = false;
			$fitnessAfterMutation = array();

			for ($i = 0; $i < $jumlah_generasi; $i++) {
				if (empty($fitnessAfterMutation)) {
					$t_start = microtime(true);

					$fitness = $genetik->HitungFitness();

					$t_end = microtime(true);
					$total_waktu_fitness += $t_end - $t_start;
				} else {
					$fitness = $fitnessAfterMutation;
				}

				$t_start = microtime(true);

				$genetik->Seleksi($fitness);
				//$child_index = $genetik->StartCrossOver();

				$t_end = microtime(true);
				//echo '<br>seleksi:'.$t_end.'-'.$t_start;
				$total_waktu_seleksi += $t_end - $t_start;

				$t_start = microtime(true);

				$genetik->StartCrossOver();

				$t_end = microtime(true);
				$total_waktu_crossover += $t_end - $t_start;

				$t_start = microtime(true);

				//$fitnessAfterMutation = $genetik->Mutasi($fitness, $child_index);
				$fitnessAfterMutation = $genetik->Mutasi($fitness);

				$t_end = microtime(true);
				$total_waktu_mutasi += $t_end - $t_start;

				/*echo '<pre>';
              echo '<br>fitness after mutation:';
              print_r($fitnessAfterMutation);
              echo '</pre>';*/

				for ($j = 0; $j < count($fitnessAfterMutation); $j++) {
					//test here
					if ($fitnessAfterMutation[$j] == 1) {

						//$this->db->query("TRUNCATE TABLE jadwalkuliah");

						$jadwal_kuliah = array(array());
						$jadwal_kuliah = $genetik->GetIndividu($j);

						//echo "iterasi ke: $i";
						//print_r($jadwal_kuliah);

						$obj_penjadwalan->KosongkanJadwal();
						for ($k = 0; $k < count($jadwal_kuliah); $k++) {
							$obj_penjadwalan->InsertJadwal($jadwal_kuliah[$k][0], $jadwal_kuliah[$k][1], $jadwal_kuliah[$k][2], $jadwal_kuliah[$k][3]);
						}

						/*               for($k = 0; $k < count($jadwal_kuliah);$k++){
                       
                       $kode_pengampu = intval($jadwal_kuliah[$k][0]);
                       $kode_jam = intval($jadwal_kuliah[$k][1]);
                       $kode_hari = intval($jadwal_kuliah[$k][2]);
                       $kode_ruang = intval($jadwal_kuliah[$k][3]);
                       $this->db->query("INSERT INTO jadwalkuliah(kode_pengampu,kode_jam,kode_hari,kode_ruang) ".
                                    "VALUES($kode_pengampu,$kode_jam,$kode_hari,$kode_ruang)");
                       
                       
                    }*/

						//var_dump($jadwal_kuliah);
						//exit();

						$found = true;
					}

					if ($found) {
						break;
					}
				}
				//echo '<br>xxx';var_dump($x);
				//echo 'generasi:'.$i.' ';
				//var_dump($found);
				/*         echo '<br>total waktu fitness pertama saja:'.$total_waktu_fitness;
              echo '<br>total waktu seleksi:'.$total_waktu_seleksi;
              echo '<br>total waktu crossover:'.$total_waktu_crossover;
              echo '<br>total waktu mutasi & fitness:'.$total_waktu_mutasi;
              echo '<br>total waktu fitness:'.$genetik->total_waktu_fitness;*/

				// --- tambahan
				//$fitness = $genetik->HitungFitness();
				//$genetik->Seleksi($fitness);
				// --- end tambahan

				if ($found) {
					break;
				}
			}

			if (!$found) {
				$data['msg'] = 'Tidak Ditemukan Solusi Optimal';
				echo $data['msg'];
			}
		}

		//   $time_start = microtime(true);
		$time_end = microtime(true);
		$date_end = date('m/d/Y h:i:s a', time());
		$durasi = $time_end - $time_start;

		/*echo '<br>'.$date_start;
        echo '<br>'.$date_end;*/
		// echo '<br>'.'durasi proses: '.$durasi;

		echo '<br>generasi: ' . $i;

		//echo '<br>durasi proses: '.$durasi/60 .' menit';
		echo '<br>durasi proses: ' . intdiv($durasi, 60) . ':' . ($durasi % 60);

		/*   foreach ($data_kelas as $key => $value) {
           if (++$key%2 == 1) {
              $tmpl->addVar('oddeven', 'IS_ODD', '1');
           } else {
              $tmpl->addVar('oddeven', 'IS_ODD', '0');
           }
           $tmpl->parseTemplate('oddeven', 'a');
           $value['NO'] = $key;
     
           $tmpl->addVars('kelas', $value, '');
           $tmpl->parseTemplate('kelas', 'a');
        }*/

		//   $jadwal = $obj_penjadwalan->GetJadwal();

		//   foreach ($jadwal as $key => $value) {
		//      if (++$key%2 == 1) {
		//         $tmpl->addVar('oddeven_jadwal', 'IS_ODD', '1');
		//      } else {
		//         $tmpl->addVar('oddeven_jadwal', 'IS_ODD', '0');
		//      }
		//      $tmpl->parseTemplate('oddeven_jadwal', 'a');
		//      $value['NO'] = $key;

		//      $tmpl->addVars('jadwal', $value, '');
		//      $tmpl->parseTemplate('jadwal', 'a');
		//   }     
		return view('Penjadwalan.index');
	}
}