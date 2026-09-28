<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Tugas Akhir - FEB UB";	
$var['icon'] = "jadwal.png";		
$var['active'] = "kelas";
$this->load->view('template/header',$var);
$data['ps'] = $akses[0]['id_ps'];
$data['psj'] = $akses[0]['id_ps']."-".$jalur[0]['id_jalur'];
$judul = $mhs->judul_ta;
$no_sk = $mhs->no_sk;
$nPemb = $akses[0]['p1'];
$nPeng = $akses[0]['p2'];
$this->load->view('head_kelas',$data);
$this->load->view('template/navigation',$var);
$hak = $this->session->userdata('hak_thesis');	
if ($hak==1) $subT = "Admin Sistem";	
elseif ($hak==2) $subT = "Admin Program Studi";	
elseif ($hak==3) $subT = "Admin Jurusan";	
elseif ($hak==4) $subT = "KPS";	
elseif ($hak==5) $subT = "Dosen";	
elseif ($hak==6) $subT = "Mahasiswa";	
?>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h1>Beranda <label id="lb-stat" class="text-primary"><?php echo "" ?></label></h1>
			<h4 style="padding-left:3px"><?php echo $subT; ?></h4>
		</div>
</div>

<div class="modal fade" id="modal_message" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
				<h4 class="modal-title">Alert</h4>
      </div>
			<div class="modal-body">        
				<p class="alert-msg">tes</p>       
			</div>			
		</div>
	</div>
</div>
<div class="modal fade" id="modal_confirm" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Hapus</h3>
      </div>
			<form action="#" id="fHapus" class="form-horizontal" method="POST">
      <div class="modal-body">       
				<p class="confirm-msg">tes</p>  				
				<input name="ops" id="ops" type="hidden"/>									
				<input name="idHapus" id="idHapus" type="hidden"/>									
			</div>
			<div class="modal-footer">
				<button type="submit" id="btHapus" class="btn btn-primary">Ya</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
			</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_form" role="dialog">
  
</div>

<div class="container"> 
	<div class="row">
		<div class="col-md-6">
<?php if (strlen(trim($judul))==0) { ?>
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4><i class="fa fa-edit"></i> Input data Tugas Akhir </h4>
				</div>
				<form action="#" id="fThesis" method="POST">
				<input name="nPemb" id="nPemb" type="hidden" value="<?php echo $nPemb; ?>"/>
				<div class="panel-body form">
					<div class="form-body">
						<div class="form-group">
							<label for="judul">Judul Tugas Akhir</label>						
							<textarea name="judul" id="judul" class="form-control" style="resize:none" rows="5"/></textarea>						
						</div>						
<?php 
	for ($i=1; $i<=$nPemb; $i++) 
	{
		echo "
			<div class='form-group'>
				<label for='pemb'".$i.">Pembimbing ".$i."</label>						
				<select class='form-control selectpicker dosen' name='pemb".$i."' id='pemb'".$i." data-show-subtext='true' data-live-search='true'><option value=''> - Pilih Dosen - </option>";
		foreach($dosen as $d) 
		{ 
			$str = "<option value='".$d['id_dosen']."'>";
			if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
			$str .= $d['nm_dosen'];
			if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
			$str .= "</option>";
			echo $str; 
		} 			
		echo "</select></div>";
	}
?>
					</div>
					<hr/>	
					<button type="button" id="btThesis" class="btn btn-primary pull-right">Simpan</button>
				</div>
				</form>
			</div>
<?php } else { ?>
			<div class="well bg-white">
				<h3 class="h3 text-info">Data Tugas Akhir</h3><hr/>
				Judul <b><?php echo $judul; ?></b><br/>
				Prodi <b><?php echo $akses[0]['jenjang']." ".$akses[0]['nm_ps']; ?></b><br/>				
			</div>
<?php } ?>
			<div class="well bg-white">
				<h3 class="h3 text-info">Box 2</h3>
			</div>
		</div>
		<div class="col-md-6">
			<div class="well bg-white">
				<h3 class="h3 text-info">Box 3</h3>
			</div>			
		</div>
		<div class="col-md-12">
			<div class="well bg-white">
				<h3 class="h3 text-info">Box 4</h3>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>