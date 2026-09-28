<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once FCPATH . 'vendor/autoload.php';
    
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

/* jadikan semua sel pada File Template Excel bertipe Teks */
class ForceTextBinder extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder
{
	public function bindValue(\PhpOffice\PhpSpreadsheet\Cell\Cell $cell, mixed $value): bool
	{
		$cell->setValueExplicit((string) $value, DataType::TYPE_STRING);
		return true;
	}
}

class User extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('md_user');	
		$this->load->model('md_mahasiswa');	
		$this->load->model('md_prodi');				
		$this->load->model('md_dosen');				
		$this->load->model('md_progress');				
		$this->load->model('md_tuji');				
		$this->load->helper(['url']);		
	} 
	
	public function index() 
	{
		$this->load->helper('url');		
		$hak = $this->session->userdata('hak_thesis');
		if (!empty($hak))
		{
			$data['dosen'] = $this->md_dosen->getNoUser();
			$data['prodi'] = $this->md_prodi->getData();
			$data['jurusan'] = $this->md_prodi->getJurusan();
			$this->load->view('vw_user',$data);			
		} 
		else redirect('login','refresh');
	}
	
	public function hash_password($password)
	{
		return password_hash($password, PASSWORD_ARGON2ID);
	}
	
	public function verify_password($password, $hashed_pass)
	{
		return password_verify($password, $hashed_pass);
	}
	
	public function ajax_list_mhs()
	{
		$data = $this->md_user->getUserMhs();
		$ary = array();
		foreach($data as $d)
		{ 
			$row = array(); 
			$row[] = $d['nim'];
			$row[] = $d['nm_mhs'];					
			if ($d['jenjang']=="S1") $tPS="Sarjana ";			
			elseif ($d['jenjang']=="S2") $tPS="Magister ";			
			elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
			elseif ($d['jenjang']=="S3") $tPS="Doktor ";			
			$tPS .= $d['nm_ps'];
			$row[] = $tPS;	
			if ($d['jalur']==1) $tJalur = "Reguler 1";
			elseif ($d['jalur']==2) $tJalur = "Reguler 2";
			elseif ($d['jalur']==3) $tJalur = "Internasional";
			elseif ($d['jalur']==4) $tJalur = "Kerjasama";
			$row[] = $tJalur;	
			$tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Reset' onclick='res_pass(1,".$d['nim'].")'><i class='fa fa-refresh'></i></a><a class='btn btn-xs btn-info' href='javascript:void()' title='Edit' onclick='edit_mhs(".$d['nim'].")'><i class='fas fa-pencil-alt'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='del_user(1,".$d['nim'].")'><i class='fa fa-times'></i></a></div>";					
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_list_dosen()
	{
		$data = $this->md_user->getUserDosen();
		$ary = array();
		foreach($data as $d)
		{ 
			$row = array(); 
			$row[] = $d['username'];
			$row[] = $d['nm_user'];					
			
			$tPS=""; $tDep=""; $tJab = "";
			if (strlen($d['nm_ps'])>0)
			{
				if ($d['jenjang']=="S1") $tPS="Sarjana ";			
				elseif ($d['jenjang']=="S2") $tPS="Magister ";			
				elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
				elseif ($d['jenjang']=="S3") $tPS="Doktor ";
				$tJab = "Ketua Program Studi";
				$tPS .= $d['nm_ps'];
			}
			if (strlen($d['nm_jur'])>0) 
			{
				if (strlen($d['nm_ps'])==0) $tJab = "Ketua Departemen"; 
				$tDep = $d['nm_jur']; 
			}
			
			$row[] = $tJab;	
			$row[] = $tDep;	
			$row[] = $tPS;	
			$tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Reset' onclick='res_pass(2,\"".$d['username']."\")'><i class='fa fa-refresh'></i></a><a class='btn btn-xs btn-info' href='javascript:void()' title='Edit' onclick='edit_dosen(\"".$d['username']."\")'><i class='fas fa-pencil-alt'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='del_user(2,\"".$d['username']."\")'><i class='fa fa-times'></i></a></div>";					
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_list_admin()
	{
		$data = $this->md_user->getUserAdmin();
		$ary = array();
		foreach($data as $d)
		{ 
			$row = array(); 
			$row[] = $d['username'];
			$row[] = $d['nm_user'];					
			
			$tPS=""; $tDep=""; 
			if (strlen($d['nm_ps'])>0)
			{
				if ($d['jenjang']=="S1") $tPS="Sarjana ";			
				elseif ($d['jenjang']=="S2") $tPS="Magister ";			
				elseif ($d['jenjang']=="XP") $tPS="Profesi ";			
				elseif ($d['jenjang']=="S3") $tPS="Doktor ";
				$tJab = "Admin Program Studi";
				$tPS .= $d['nm_ps'];
			}
			if (strlen($d['nm_jur'])>0) 
			{
				if (strlen($d['nm_ps'])==0) $tJab = "Admin Departemen"; 
				$tDep = $d['nm_jur']; 
			}
			
			$row[] = $tJab;	
			$row[] = $tDep;	
			$row[] = $tPS;	
			$tBtn = "<div class='btn-group' role='group'><a class='btn btn-xs btn-warning' href='javascript:void()' title='Reset' onclick='res_pass(3,\"".$d['username']."\")'><i class='fa fa-refresh'></i></a><a class='btn btn-xs btn-info' href='javascript:void()' title='Edit' onclick='edit_admin(\"".$d['username']."\")'><i class='fas fa-pencil-alt'></i></a><a class='btn btn-xs btn-danger' href='javascript:void()' title='Hapus' onclick='del_user(3,\"".$d['username']."\")'><i class='fa fa-times'></i></a></div>";					
			$row[] = $tBtn;	
			$ary[] = $row;					
		}
		$output = array("data" => $ary);
		echo json_encode($output);
	}
	
	public function ajax_edit_admin($id)
	{
		$data = $this->md_user->getDetAdmin($id);
		$row = array(
			'id_user' => $data->id_user,
			'username' => $data->username,
			'nm_user' => $data->nm_user,		
			'hak' => $data->hak,		
			'id_jur' => $data->id_jur,		
			'id_ps' => $data->id_ps,
			'jenjang' => $data->jenjang,
			'nm_ps' => $data->nm_ps
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function download_template()
	{
		// Terapkan ValueBinder untuk semua nilai yang ditulis
		\PhpOffice\PhpSpreadsheet\Cell\Cell::setValueBinder(new ForceTextBinder());
		
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Sheet1');
		
		// Set default font
		$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);
		
		// Style header
		$headerStyle = [
			'font' => ['bold' => true],
			'fill' => [
				'fillType' => 'solid',
				'startColor' => ['rgb' => 'FFC800']
			],
			'alignment' => [
				'horizontal' => 'center'
			]
		];
		
		// Data program studi
		$dtPS = $this->md_prodi->getData();
		$row = 1;
		foreach ($dtPS as $d) {
			$sheet->setCellValue('C'.$row, $d['jenjang']." ".$d['nm_ps']);
			$row++;
		}
		
		// Jalur Masuk 
		$sheet->setCellValue('D1', "1 - Reguler 1");
		$sheet->setCellValue('D2', "2 - Reguler 2");
		$sheet->setCellValue('D3', "3 - Internasional");
		
		// Header
		$headerRow = $row + 1;
		$sheet->setCellValue('A' . $headerRow, 'NIM');
		$sheet->setCellValue('B' . $headerRow, 'Nama');
		$sheet->setCellValue('C' . $headerRow, 'Prodi');
		$sheet->setCellValue('D' . $headerRow, 'Jalur');
		$sheet->getStyle('A' . $headerRow . ':D' . $headerRow)->applyFromArray($headerStyle);
		
		// ========== KRUSIAL: Set FORMAT TEXT untuk SEMUA CELL ==========
		// Hitung baris maksimal yang mungkin terisi (header row + data rows)
		$lastRow = $headerRow + 1000; // Beri buffer 1000 baris kosong
		// Atau jika ingin lebih aman, set sampai batas maksimal Excel (1048576)
		// $lastRow = 1048576;
		
		// Set format text untuk kolom A, B, C dari baris 1 sampai $lastRow
		$sheet->getStyle('A1:D' . $lastRow)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
		// ===============================================================
		
		// Auto-size columns (lakukan setelah set format)
		foreach(range('A','D') as $col) {
			$sheet->getColumnDimension($col)->setAutoSize(true);
		}
		
		// Simpan file
		$fileLocation = 'Template_User_Mhs.xlsx';
		$writer = new Xlsx($spreadsheet);
		$writer->save($fileLocation);
		
		// Download
		header('Content-Description: File Transfer');
		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header("Content-Disposition: attachment; filename=" . basename($fileLocation));
		header("Content-Transfer-Encoding: binary");
		header("Expires: 0");
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Length: ' . filesize($fileLocation));
		
		ob_clean();
		flush();
		readfile($fileLocation);
		unlink($fileLocation);
		exit(0);
	}
 
	/* -------------------------------------------------------
	 * POST: Simpan import dari JSON (hasil SheetJS di client)
	 * URL : /mahasiswa/simpan_import
	 *
	 * Request JSON:
	 *   { "data": [{"nim":"...","nama":"...","program_studi":"..."}, ...] }
	 *
	 * Response JSON:
	 *   { "status":"success", "berhasil":N, "gagal":N,
	 *     "detail": [{"index":0,"status":"ok","pesan":""}, ...] }
	 * ------------------------------------------------------- */
	public function save_import()
	{
		if (!$this->input->is_ajax_request())
			return $this->_json(['status' => 'error', 'message' => 'Akses tidak diizinkan.']);
		
		$body = json_decode(file_get_contents('php://input'), TRUE);

		if (json_last_error() !== JSON_ERROR_NONE || empty($body['data']) || !is_array($body['data']))
			return $this->_json(['status' => 'error', 'message' => 'Data tidak valid atau kosong.']);
		
		$detail   = [];
		$berhasil = 0;
		$gagal    = 0;
		
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');

		foreach ($body['data'] as $idx => $row) 
		{
			$nim   = trim(strip_tags($row['nim']   ?? ''));
			$nama  = trim(strip_tags(strtoupper($row['nama'])  ?? ''));
			$prodi = trim(strip_tags($row['prodi'] ?? ''));
			$jalur = trim(strip_tags($row['jalur'] ?? ''));

			/* Validasi per baris */
			if ($nim === '') 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'NIM kosong'];
				$gagal++;
				continue;
			}
			if ($nama === '') 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'Nama kosong'];
				$gagal++;
				continue;
			}
			if ($prodi === '') 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'Program Studi kosong'];
				$gagal++;
				continue;
			}
			if ($jalur === '') 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'Jalur Masuk kosong'];
				$gagal++;
				continue;
			}

			// CEK APAKAH NIM SUDAH ADA DI DATABASE
			$nim_exists = $this->md_user->cekUserMhs($nim);
			
			if ($nim_exists) {
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'NIM sudah terdaftar di sistem'];
				$gagal++;
				continue;
			}

			
			$jenjang = substr($prodi,0,2);				
			$nama_ps = substr($prodi,3,strlen($prodi)-3);				
			$dataPS = $this->md_prodi->getDataPS($jenjang,$nama_ps);
			if ($dataPS) 
			{
				$idPS = $dataPS->id_ps;
				$tipeReg = $dataPS->tipe_reg;
				$idProgress = $this->md_progress->getFirst($tipeReg);
				$idTahapUji = $this->md_tuji->getFirst($idPS);
			}
			else 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => 'Prodi tidak dikenali'];
				$gagal++;
				continue;
			}
			
			$pass = $this->hash_password($nim);
			$idUser = $this->md_user->getMaxID($th);
			$dataUser['id_user'] = $idUser;
			$dataUser['username'] = $nim;
			$dataUser['nm_user'] = $nama;
			$dataUser['pass'] = $pass;
			$dataUser['hak'] = 6;
			$dataUser['id_ps'] = $idPS;
			$savedUser = $this->md_user->save($dataUser);		
			
			$dataMhs['nim'] = $nim;
			$dataMhs['id_ps'] = $idPS;
			$dataMhs['id_user'] = $idUser;
			$dataMhs['nm_mhs'] = $nama;
			$dataMhs['jalur'] = $jalur;
			$dataMhs['id_progress'] = $idProgress;
			$dataMhs['id_tuji'] = $idTahapUji;
			$savedMhs = $this->md_mahasiswa->save($dataMhs);		
			
			if ($savedUser && $savedMhs) 
			{
				$id_user = $this->session->userdata('id_user_thesis');
				$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
				$dtLogUser['id_opt'] = $id_user;
				$dtLogUser['dt'] = $tgl." ".$jam;			
				$dtLogUser['act'] = 1;	
				$dtLogUser['id_user'] = $idUser;
				$dtLogUser['username'] = $nim;
				$dtLogUser['nm_user'] = $nama;
				$dtLogUser['pass'] = $pass;
				$dtLogUser['hak'] = 6;
				$dtLogUser['id_ps'] = $idPS;
				$this->md_user->saveLog($dtLogUser);
				
				$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
				$dtLogMhs['id_user_log'] = $id_user;
				$dtLogMhs['dt'] = $tgl." ".$jam;
				$dtLogMhs['act'] = 1;
				$dtLogMhs['nim'] = $nim;
				$dtLogMhs['id_ps'] = $idPS;
				$dtLogMhs['id_user'] = $idUser;
				$dtLogMhs['nm_mhs'] = $nama;
				$dtLogMhs['jalur'] = $jalur;
				$dtLogMhs['id_progress'] = $idProgress;
				$dtLogMhs['id_tuji'] = $idTahapUji;
				$this->md_mahasiswa->saveLog($dtLogMhs);
			
				$detail[] = ['index' => $idx, 'status' => 'ok', 'pesan' => ''];
				$berhasil++;
			} 
			else 
			{
				$detail[] = ['index' => $idx, 'status' => 'error', 'pesan' => $nim.' Gagal disimpan'];
				$gagal++;
			}
		}

		$this->_json([
			'status'   => 'success',
			'berhasil' => $berhasil,
			'gagal'    => $gagal,
			'detail'   => $detail,
		]);
	}
				
	public function ajax_edit($key)
	{
		$tmp = explode("-", $key);
		$id = $tmp[1];
		$data = $this->md_user->getDet($id);
		$row = array(
			'id_user' => $data->id_user,
			'username' => $data->username,		
			'nm_user' => $data->nm_user,
			'id_ps' => $data->id_ps
		);			
		$output = array("data" => $row);
		echo json_encode($output);
	}
	
	public function reset_pass()
	{
		$tmp = explode("-", $this->input->post('idReset'));
		$tipe = $tmp[0];
		$id = $tmp[1];
		$pass = $this->hash_password($id);
		$dtUser['pass'] = $pass;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$idUser = $this->md_user->getDet($id)->id_user;
		$dtLogUser['id_user'] = $idUser;
		$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLogUser['id_opt'] = $this->session->userdata('id_user_thesis');;
		$dtLogUser['dt'] = $tgl." ".$jam;
		$dtLogUser['act'] = 2;		
		$dtLogUser['pass'] = $pass;
		
		$this->md_user->update($dtUser,array('id_user' => $idUser)); 
		$this->md_user->saveLog($dtLogUser);
		
		echo json_encode(array("status" => TRUE));
	}
	
	public function delete_data()
	{
		$tmp = explode("-", $this->input->post('idHapus'));
		$tipe = $tmp[0];
		$id = $tmp[1];
		$dtUser['stat'] = 2;		
		$wkt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $wkt->format('y');
		$tgl = $wkt->format('Y-m-d');
		$jam = $wkt->format('H:i:s');
		
		$idUser = $this->md_user->getDet($id)->id_user;
		$idDosen = $this->md_dosen->getDataUser($id)->id_dosen;
		$dtLogUser['id_user'] = $idUser;
		$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLogUser['id_opt'] = $this->session->userdata('id_user_thesis');;
		$dtLogUser['dt'] = $tgl." ".$jam;
		$dtLogUser['act'] = 3;		
		$dtLogUser['stat'] = 2;
		
		$this->md_user->update($dtUser,array('id_user' => $idUser)); 
		$this->md_user->saveLog($dtLogUser);
		
		//Jika User Mahasiswa
		if ($tipe==1) 
		{
			$dtMhs['stat'] = 2;
			
			$dtLogMhs['nim'] = $id;
			$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
			$dtLogMhs['id_user_log'] = $this->session->userdata('id_user_thesis');;
			$dtLogMhs['dt'] = $tgl." ".$jam;
			$dtLogMhs['act'] = 3;		
			$dtLogMhs['stat'] = 2;
			
			$this->md_mahasiswa->update($dtMhs,array('id_user' => $idUser)); 
			$this->md_mahasiswa->saveLog($dtLogMhs);
		}
		else if ($tipe==2) 
		{
			$dtDosen['id_user'] = 0;
						
			$dtLogDosen['id_dosen'] = $idDosen;
			$dtLogDosen['id_log_dosen'] = $this->md_dosen->getMaxLogID($th);
			$dtLogDosen['id_user_log'] = $this->session->userdata('id_user_thesis');;
			$dtLogDosen['dt'] = $tgl." ".$jam;
			$dtLogDosen['act'] = 2;		
			$dtLogDosen['id_user'] = 0;
			
			$this->md_dosen->update($dtDosen,array('id_dosen' => $idDosen)); 
			$this->md_dosen->saveLog($dtLogDosen);
		}
		
		echo json_encode(array("status" => TRUE));
	}	
	
	public function add_data_mhs()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$nim = $this->input->post('nim');
    $nama = trim(strtoupper($this->input->post('nama')));
    $prodi = $this->input->post('prodi');
    $jalur = $this->input->post('jalur');
		
		// CEK APAKAH NIM SUDAH ADA DI DATABASE
		$nim_exists = $this->md_user->cekUserExist($nim);		
    if ($nim_exists) 
		{
      echo json_encode(array(
				"status" => FALSE, 
				"save" => FALSE, 
				"message" => "NIM '{$nim}' sudah terdaftar di sistem"
			));
			return;
    }
		
		$jenjang = substr($prodi,0,2);				
		$nama_ps = substr($prodi,3,strlen($prodi)-3);				
		$dataPS = $this->md_prodi->getDataPS($jenjang,$nama_ps);
		$idPS = $dataPS->id_ps;
		$tipeReg = $dataPS->tipe_reg;
		$idProgress = $this->md_progress->getFirst($tipeReg);
		$idTahapUji = $this->md_tuji->getFirst($idPS);
		
		$pass = $this->hash_password($nim);
		$idUser = $this->md_user->getMaxID($th);
		$dataUser['id_user'] = $idUser;
		$dataUser['username'] = $nim;			
		$dataUser['nm_user'] = $nama;			
		$dataUser['pass'] = $pass;		
		$dataUser['hak'] = 6;
		$dataUser['id_ps'] = $idPS;			
		$this->md_user->save($dataUser);
		
		$id_user = $this->session->userdata('id_user_thesis');
		$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLogUser['id_opt'] = $id_user;
		$dtLogUser['dt'] = $tgl." ".$jam;			
		$dtLogUser['act'] = 1;	
		$dtLogUser['id_user'] = $idUser;
		$dtLogUser['username'] = $nim;
		$dtLogUser['nm_user'] = $nama;
		$dtLogUser['pass'] = $pass;
		$dtLogUser['hak'] = 6;
		$dtLogUser['id_ps'] = $idPS;
		$this->md_user->saveLog($dtLogUser);	
		
		$dataMhs['nim'] = $nim;
		$dataMhs['id_ps'] = $idPS;	
		$dataMhs['id_user'] = $idUser;
		$dataMhs['nm_mhs'] = $nama;
		$dataMhs['jalur'] = $jalur;
		$dataMhs['id_progress'] = $idProgress;
		$dataMhs['id_tuji'] = $idTahapUji;
		$savedMhs = $this->md_mahasiswa->save($dataMhs);

		$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
		$dtLogMhs['id_user_log'] = $id_user;
		$dtLogMhs['dt'] = $tgl." ".$jam;
		$dtLogMhs['act'] = 1;
		$dtLogMhs['nim'] = $nim;
		$dtLogMhs['id_ps'] = $idPS;
		$dtLogMhs['id_user'] = $idUser;
		$dtLogMhs['nm_mhs'] = $nama;
		$dtLogMhs['jalur'] = $jalur;
		$dtLogMhs['id_progress'] = $idProgress;
		$dtLogMhs['id_tuji'] = $idTahapUji;
		$this->md_mahasiswa->saveLog($dtLogMhs);
				
		echo json_encode(array("status" => TRUE, "save" => TRUE));
	}
	
	public function add_data_dosen()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$detDosen = $this->md_dosen->getDetDosen($this->input->post('namaD'));
		$nama = trim(strtoupper($detDosen->nm_dosen));
		$nid = trim(strtoupper($detDosen->no_dosen));
		$hak=5; $idJur=NULL; $idPS=NULL;
		$jab = $this->input->post('jabD');
		if (!empty($jab))
		{
			if ($jab==1) { $idJur = $this->input->post('jurD'); $hak=7; }
			elseif ($jab==2) 
			{ 
				$prodi = $this->input->post('prodiD');
				$jenjang = substr($prodi,0,2);	
				$namaPS = substr($prodi,3,strlen($prodi)-3);
				$dataPS = $this->md_prodi->getDataPS($jenjang,$namaPS);
				$idPS = $dataPS->id_ps;
				$hak=4; 
			}
		}
		
		$pass = $this->hash_password($nid);
		$idUser = $this->md_user->getMaxID($th);
		$dataUser['id_user'] = $idUser;
		$dataUser['username'] = $nid;			
		$dataUser['nm_user'] = $nama;			
		$dataUser['pass'] = $pass;		
		$dataUser['hak'] = $hak;
		$dataUser['id_jur'] = $idJur;			
		$dataUser['id_ps'] = $idPS;			
		$this->md_user->save($dataUser);
		
		$id_user = $this->session->userdata('id_user_thesis');
		$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLogUser['id_opt'] = $id_user;
		$dtLogUser['dt'] = $tgl." ".$jam;			
		$dtLogUser['act'] = 1;	
		$dtLogUser['id_user'] = $idUser;
		$dtLogUser['username'] = $nid;
		$dtLogUser['nm_user'] = $nama;
		$dtLogUser['pass'] = $pass;
		$dtLogUser['hak'] = $hak;
		$dtLogUser['id_jur'] = $idJur;
		$dtLogUser['id_ps'] = $idPS;
		$this->md_user->saveLog($dtLogUser);	
		
		$dtDosen['id_user'] = $idUser;
		$this->md_dosen->update($dtDosen,array('id_dosen' => $this->input->post('namaD')));

		$dtLogDosen['id_log_dosen'] = $this->md_dosen->getMaxLogID($th);
		$dtLogDosen['id_user_log'] = $id_user;
		$dtLogDosen['dt'] = $tgl." ".$jam;
		$dtLogDosen['act'] = 2;
		$dtLogDosen['id_dosen'] = $this->input->post('namaD');
		$dtLogDosen['id_user'] = $idUser;
		$this->md_dosen->saveLog($dtLogDosen);
				
		echo json_encode(array("status" => TRUE, "save" => TRUE));
	}
	
	public function add_data_admin()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$username = $this->input->post('unameAdm');
    $nama = trim(strtoupper($this->input->post('namaAdm')));
    
		// CEK APAKAH NIM SUDAH ADA DI DATABASE
		$uname_exists = $this->md_user->cekUserExist($username);		
    if ($uname_exists) 
		{
      echo json_encode(array(
				"status" => FALSE, 
				"save" => FALSE, 
				"message" => "Username '{$username}' sudah terdaftar di sistem"
			));
			return;
    }
		
		$idJur=NULL; $idPS=NULL;
		$jab = $this->input->post('jabAdm');
		if (!empty($jab))
		{
			if ($jab==3) { $idJur = $this->input->post('jurAdm'); }
			elseif ($jab==2) 
			{ 
				$prodi = $this->input->post('prodiAdm');
				$jenjang = substr($prodi,0,2);	
				$namaPS = substr($prodi,3,strlen($prodi)-3);
				$dataPS = $this->md_prodi->getDataPS($jenjang,$namaPS);
				$idPS = $dataPS->id_ps;				
			}
			$hak = $jab;
		}
		
		$pass = $this->hash_password($username);
		$idUser = $this->md_user->getMaxID($th);
		$dataUser['id_user'] = $idUser;
		$dataUser['username'] = $username;			
		$dataUser['nm_user'] = $nama;			
		$dataUser['pass'] = $pass;		
		$dataUser['hak'] = $hak;
		$dataUser['id_jur'] = $idJur;			
		$dataUser['id_ps'] = $idPS;			
		$this->md_user->save($dataUser);
		
		$id_user = $this->session->userdata('id_user_thesis');
		$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
		$dtLogUser['id_opt'] = $id_user;
		$dtLogUser['dt'] = $tgl." ".$jam;			
		$dtLogUser['act'] = 1;	
		$dtLogUser['id_user'] = $idUser;
		$dtLogUser['username'] = $username;
		$dtLogUser['nm_user'] = $nama;
		$dtLogUser['pass'] = $pass;
		$dtLogUser['hak'] = $hak;
		$dtLogUser['id_jur'] = $idJur;
		$dtLogUser['id_ps'] = $idPS;
		$this->md_user->saveLog($dtLogUser);	
				
		echo json_encode(array("status" => TRUE, "save" => TRUE));
	}
	
	public function update_data_mhs()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$nim = $this->input->post('nim');
    $nama = trim(strtoupper($this->input->post('nama')));
    $jalur = $this->input->post('jalur');
		$namaAsal = $this->input->post('nmA');
    $jalurAsal = $this->input->post('jlrA');
		
		$id_user = $this->session->userdata('id_user_thesis');
		$change = 0;
		if ($nama!==$namaAsal) 
		{ 
			$change++; 
			$dataMhs['nm_mhs'] = $nama; 
			$dtLogMhs['nm_mhs'] = $nama;
			
			$idUser = $this->md_user->getDet($nim)->id_user;
			$dtUser['nm_user'] = $nama;					
			$dtLogUser['id_user'] = $idUser;
			$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
			$dtLogUser['id_opt'] = $this->session->userdata('id_user_thesis');;
			$dtLogUser['dt'] = $tgl." ".$jam;
			$dtLogUser['act'] = 2;
			$dtLogUser['nm_user'] = $nama;
			$this->md_user->update($dtUser,array('id_user' => $idUser));
			$this->md_user->saveLog($dtLogUser);
		}
		if ($jalur!==$jalurAsal) 
		{ 
			$change++;
			$dataMhs['jalur'] = $jalur; 
			$dtLogMhs['jalur'] = $jalur; 
		}
		if ($change>0) 
		{
			$this->md_mahasiswa->update($dataMhs,array('nim' => $nim)); 

			$dtLogMhs['id_log_mahasiswa'] = $this->md_mahasiswa->getMaxLogID($th);
			$dtLogMhs['id_user_log'] = $id_user;
			$dtLogMhs['dt'] = $tgl." ".$jam;
			$dtLogMhs['act'] = 2;
			$dtLogMhs['nim'] = $nim;
			$this->md_mahasiswa->saveLog($dtLogMhs);
			echo json_encode(array("status" => TRUE, "save" => TRUE));
		}			
		else echo json_encode(array("status" => TRUE, "save" => FALSE));
	}
	
	public function update_data_dosen()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$id_user = $this->input->post('idUD');
    $prodi = $this->input->post('prodiD');
    $dept = $this->input->post('jurD');
		$prodiAsal = $this->input->post('psDA');
    $deptAsal = $this->input->post('jurDA');
		$jab = $this->input->post('jabD');
		
		$change = 0;
		if ($prodi!==$prodiAsal) 
		{ 
			$change++; 
			$jenjang = substr($prodi,0,2);	
			$namaPS = substr($prodi,3,strlen($prodi)-3);
			$dataPS = $this->md_prodi->getDataPS($jenjang,$namaPS);
			$idPS = $dataPS->id_ps;
			$dtUser['id_ps'] = $idPS;			
			$dtLogUser['id_ps'] = $idPS;			
		}
		if ($dept!==$deptAsal) 
		{ 
			$change++;
			if ($jab!=1) $dept = NULL;
			$dtUser['id_jur'] = $dept; 
			$dtLogUser['id_jur'] = $dept; 
		}
		
		if ($change>0) 
		{
			$hak = 5;
			if (!empty($dept)) $hak = 7; 
			elseif (!empty($prodi)) $hak = 4; 
			$dtUser['hak'] = $hak; 
			$dtLogUser['hak'] = $hak; 
			
			$dtLogUser['id_user'] = $id_user;
			$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
			$dtLogUser['id_opt'] = $this->session->userdata('id_user_thesis');;
			$dtLogUser['dt'] = $tgl." ".$jam;
			$dtLogUser['act'] = 2;
			$this->md_user->update($dtUser,array('id_user' => $id_user));
			$this->md_user->saveLog($dtLogUser);
			
			echo json_encode(array("status" => TRUE, "save" => TRUE));
		}			
		else echo json_encode(array("status" => TRUE, "save" => FALSE));
	}
	
	public function update_data_admin()
	{
		$dt = new DateTime("now", new DateTimeZone('Asia/jakarta'));
		$th = $dt->format('y');
		$tgl = $dt->format('Y-m-d');
		$jam = $dt->format('H:i:s');
		
		$id_user = $this->input->post('idUAdm');
    $prodi = $this->input->post('prodiAdm');
    $dept = $this->input->post('jurAdm');
    $nama = $this->input->post('namaAdm');
    $jab = $this->input->post('jabAdm');
		$prodiAsal = $this->input->post('psAA');
    $deptAsal = $this->input->post('jurAA');
    $namaAsal = $this->input->post('namaAA');
		
		$change = 0;
		if ($prodi!==$prodiAsal) 
		{  
			$change++; 			
			$jenjang = substr($prodi,0,2);	
			$namaPS = substr($prodi,3,strlen($prodi)-3);
			$dataPS = $this->md_prodi->getDataPS($jenjang,$namaPS);
			$idPS = $dataPS->id_ps;				
			$dtUser['id_ps'] = $idPS;			
			$dtLogUser['id_ps'] = $idPS;			
		}
		if ($dept!==$deptAsal) 
		{ 
			$change++;			
			if ($jab!=3) $dept = NULL;
			$dtUser['id_jur'] = $dept; 
			$dtLogUser['id_jur'] = $dept; 
		}
		if ($nama!==$namaAsal) 
		{ 
			$change++;
			$dtUser['nm_user'] = $nama; 
			$dtLogUser['nm_user'] = $nama; 
		}
		
		if ($change>0) 
		{
			if (!empty($dept)) $hak = 3;
			elseif (!empty($prodi)) $hak = 2; 
			$dtUser['hak'] = $hak; 
			$dtLogUser['hak'] = $hak; 
			
			$dtLogUser['id_user'] = $id_user;
			$dtLogUser['id_log_user'] = $this->md_user->getMaxLogID($th);
			$dtLogUser['id_opt'] = $this->session->userdata('id_user_thesis');;
			$dtLogUser['dt'] = $tgl." ".$jam;
			$dtLogUser['act'] = 2;
			$this->md_user->update($dtUser,array('id_user' => $id_user));
			$this->md_user->saveLog($dtLogUser);
			
			echo json_encode(array("status" => TRUE, "save" => TRUE));
		}			
		else echo json_encode(array("status" => TRUE, "save" => FALSE));
	}

	/* -------------------------------------------------------
	 * POST: Hapus satu data
	 * URL : /mahasiswa/hapus
	 * ------------------------------------------------------- */
	public function hapus()
	{
		$id = (int) $this->input->post('id');
		if ($id <= 0) {
			return $this->_json(['status' => 'error', 'message' => 'ID tidak valid.']);
		}
		$ok = $this->Mahasiswa_model->delete($id);
		$this->_json($ok
			? ['status' => 'success']
			: ['status' => 'error', 'message' => 'Gagal menghapus data.']
		);
	}

	/* -------------------------------------------------------
	 * PRIVATE: Output JSON
	 * ------------------------------------------------------- */
	private function _json($data)
	{
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}
} 
?>