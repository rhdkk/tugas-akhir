<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Tugas Akhir - FEB UB";	
$var['icon'] = "jadwal.png";		
$var['active'] = "kelas";
$this->load->view('template/header',$var);
$hak = $this->session->userdata('hak_thesis');
if ($hak!=5)
{	
	$data['ps'] = $akses[0]['id_ps'];
	$nPemb = $akses[0]['p1'];
	$nPeng = $akses[0]['p2'];
	$jenjang = $akses[0]['jenjang'];
	$data['nPemb'] = $nPemb;
	$data['nPeng'] = $nPeng;
	$this->load->view('head_home_d',$data);
} 
else $this->load->view('head_home_d');
$this->load->view('template/navigation',$var);
?>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h2>Beranda <label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
			<h5 style="padding-left:3px">Ketua Program Studi</h5>
		</div>
</div>

<div class="modal fade" id="modal_formRU" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleRev">Validasi Revisi</h3>
      </div>
			<form action="#" id="fRU" class="form-horizontal" method="POST">
				<input name="idRev" id="idRev" type="hidden"/>
				<input name="idDPRev" id="idDPRev" type="hidden"/>
				<input name="tipeRev" id="tipeRev" type="hidden"/>
				<input name="idDURev" id="idDURev" type="hidden"/>
				<input name="nimRev" id="nimRev" type="hidden"/>
				<input name="accRev" id="accRev" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body well skin-yellow">
						<div class="form-group row">
							<div class="col-md-12">
								<b id="lbNamaRev" class="text-muted">Nama</b><br/>
								<span id="lbPSRev">NIM. </span>								
							</div>
						</div>
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12">								
								<b><span class="text-primary" id="lbUjiRev">Tahap Ujian</span></b><br/>
								<span class="text-primary" id="lbTglUjiRev" style="font-size:0.9em">Tanggal Ujian</span>									
								<h5 style="margin-top:7px" class="well skin-white"><span id="lbKetRev">ini adalah Saran/Revisi</span></h5>
								<div class="alert alert-info" role="alert">
									<span>Tanggal Pengajuan Validasi Revisi/Saran </span>
									<span id="lbTglRev" class="label label-primary" style="font-weight:normal; font-size:1em">ini adalah tanggal</span>
								</div>								
									
								<br/><button type="button" id="viewRev" class="btn btn-primary btn-xs">Show History</button>
							</div>
						</div>						
					</div>
					<div class="form-body well" id="histRU">
						<table id="tbHR" class="table table-striped table-hover table-sm display responsive" style="margin-bottom:0px" cellspacing="0" width="100%">
							<thead><tr><th>Tanggal dan Revisi/Saran</th><th>Status</th></tr></thead>
							<tbody></tbody>
						</table>
					</div>
					<span id="lbTanyaRev">Apakah data Pengajuan Revisi/Saran di atas valid?</span>					
				</div>
			</form>
			<div class="modal-footer">
					<button type="button" id="btYesRU" class="btn btn-success"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;&nbsp;Ya</button>
					<button type="button" id="btNoRU" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i>&nbsp;Tidak</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formPU" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fPU" class="form-horizontal" method="POST">
				<input name="idU" id="idU" type="hidden"/>				
				<input name="idDU" id="idDU" type="hidden"/>				
				<input name="nPU" id="nPU" type="hidden"/>		
				<input name="idDPU" id="idDPU" type="hidden"/>				
				<input name="nimPU" id="nimPU" type="hidden"/>				
				<input name="jmlP3" id="jmlP3" type="hidden"/>				
				<input name="jmlP4" id="jmlP4" type="hidden"/>				
				<input name="nTim" id="nTim" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row" style="margin-bottom: 0px">
							<div class="col-md-12">
								<h5 id="lbMhs">Nama</h5><br/>						
								<h5 id="lbUjian">Tahap Ujian</h5>
								<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/>
							</div>
							<div class="col-md-12">
								<table id="tbNilai" class="table table-striped table-hover display responsive" cellspacing="0" width="100%" style="margin-bottom:0px">
									<thead><tr><th>Kriteria</th><th width="70px">Nilai</th></tr></thead>
									<tbody id="rowTbNilai"></tbody>
								</table>
								<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/>
							</div>
							<div class="col-md-12 form-comp">
								<label for="revPU" class="control-label align-middle" style="margin-bottom:5px" id="lbSaran">Saran / Revisi</label>
								<div class="textarea-group">
									<textarea name="revPU1" id="revPU1" class="form-control textarea-block" style="resize: none;" rows="3" placeholder="Saran/Revisi 1"></textarea>
									<textarea name="revPU2" id="revPU2" class="form-control textarea-block" style="resize: none;" rows="3" placeholder="Saran/Revisi 2"></textarea>
									<textarea name="revPU3" id="revPU3" class="form-control textarea-block" style="resize: none;" rows="3" placeholder="Saran/Revisi 3"></textarea>
									<textarea name="revPU4" id="revPU4" class="form-control textarea-block" style="resize: none;" rows="3" placeholder="Saran/Revisi 4"></textarea>
								</div>
							</div>
						</div>						
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btNilai" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formBD" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleBimb">Validasi Bimbingan</h3>
      </div>
			<form action="#" id="fBD" class="form-horizontal" method="POST">
				<input name="idBimb" id="idBimb" type="hidden"/>
				<input name="idDP" id="idDP" type="hidden"/>				
				<input name="nimBD" id="nimBD" type="hidden"/>
				<input name="nPb" id="nPb" type="hidden"/>
				<input name="idPg" id="idPg" type="hidden"/>
				<input name="judulNew" id="judulNew" type="hidden"/>
				<input name="jnsBimb" id="jnsBimb" type="hidden"/>
				<input name="accBimb" id="accBimb" type="hidden"/>
				<input name="accUji" id="accUji" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body well skin-yellow">
						<div class="form-group row">
							<div class="col-md-12">
								<b id="lbNama" class="text-muted">Nama</b><br/>
								<span id="lbKetMhs">NIM. </span>								
							</div>
						</div>
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12">
									<h4 style="margin-bottom:7px">
										<span id="lbTglBimb" class="label label-default" style="font-weight:normal">ini adalah tanggal</span></h4>	
									<b><span class="text-primary" id="lbKet">Pembahasan Bimbingan</span></b>									
									<h4><span id="lbKetBimb">ini adalah keterangan</span></h4>
									<br/><button type="button" id="viewBimb" class="btn btn-primary btn-xs">Show History</button>
							</div>
						</div>						
					</div>
					<div class="form-body well" id="histBM">
						<table id="tbHB" class="table table-striped table-hover table-sm display responsive" style="margin-bottom:0px" cellspacing="0" width="100%">
							<thead><tr><th>Tanggal</th><th>Pembahasan</th><th>Stat</th></tr></thead>
							<tbody></tbody>
						</table>
					</div>
					<span id="lbTanya">Apakah data Bimbingan di atas valid?</span>					
				</div>
			</form>
			<div class="modal-footer">
					<button type="button" id="btYesBD" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i>&nbsp;&nbsp;&nbsp;Ya</button>
					<button type="button" id="btNoBD" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i>&nbsp;Tidak</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formPemb" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fDosen" class="form-horizontal" method="POST">
				<input name="nim" id="nim" type="hidden"/>				
				<input name="nPemb" id="nPemb" type="hidden"/>				
				<input name="nPeng" id="nPeng" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row">
							<div class="col-md-12">
								<label class="control-label">Judul</label>						
								<h5 id="judul">Ini adalah judulnya</h5>
								<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/>
							</div>
<?php 
	echo "<div class='col-md-12 form-comp'>";
	for ($i=1; $i<=$nPemb; $i++) 
	{ 		
		if ($jenjang=="S3") 
		{
			if ($i==1) echo "<label for='pemb".$i."' class='control-label'>Promotor</label>";
			else echo "<label for='pemb".$i."' class='control-label'>Co-Promotor ".($i-1)."</label>";
		}
		else echo "<label for='pemb".$i."' class='control-label'>Pembimbing ".$i."</label>";
		echo "		<div class='input-group' id='par".$i."'>
								<input name='idPemb".$i."' id='idPemb".$i."' type='hidden'/>									
								<input name='idDP".$i."' id='idDP".$i."' type='hidden'/>									
								<select class='form-control ext-select dosen' name='pemb".$i."' id='pemb".$i."'><option value='' style='width:100%'> - Pilih Dosen - </option>";
		foreach($dosen as $d) 
		{ 
			$str = "<option value='".$d['id_dosen']."'>";
			if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
			$str .= $d['nm_dosen'];
			if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
			$str .= "</option>";
			echo $str; 
		} 
		echo "			</select>	
								<div class='input-group-btn'>
									<button class='btn btn-primary btn-pemb' type='button' id='jmlPemb".$i."'>9</button>										
								</div>									
							</div>
							<div class='alert alert-info hide' id='infoPemb".$i."' style='margin-bottom:0px; margin-top:-8px; padding-bottom:7px'>
								Jumlah Mahasiswa bimbingan dosen yang bersangkutan:
								<ul>
									<li><b>1</b> Mahasiswa, pada Prodi ini</li>
									<li><b>2</b> Mahasiswa, pada Prodi yang lain</li>
								</ul>
							</div>";						
	}
	echo "<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/></div>";
	for ($i=1; $i<=$nPeng; $i++) 
	{ 
		echo "<div class='col-md-12 form-comp'>";
		echo "	<label for='peng".$i."' class='control-label'>Penguji ".$i."</label>
							<div>
								<input name='idPeng".$i."' id='idPeng".$i."' type='hidden'/>									
								<select class='form-control ext-select' name='peng".$i."' id='peng".$i."'><option value='' style='width:100%'> - Pilih Dosen - </option>";
		foreach($dosen as $d) 
		{ 
			$str = "<option value='".$d['id_dosen']."'>";
			if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
			$str .= $d['nm_dosen'];
			if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
			$str .= "</option>";
			echo $str; 
		} 
		echo "			</select>	
							</div>
							<div class='alert alert-info hide' id='infoPeng".$i."' style='margin-bottom:0px; margin-top:-8px; padding-bottom:7px'></div>
						</div>";
	}
?>
					</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btDosen" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>

<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<div class="well bg-white">
				<h3 class="h4">Data Tugas Akhir Mahasiswa</h4><hr/>
				<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading">
						<ul class="nav nav-tabs">
<?php if ($hak==4) { ?>
							<li class="active"><a href="#tab0" data-toggle="tab" class="text-muted">Pengajuan Pembimbing &nbsp;<span class="badge" id="badge0"></span></a></li>
<?php } ?> 
							<li<?php if ($hak==5) { echo " class='active'"; } ?>><a href="#tab1" data-toggle="tab" class="text-muted">Bimbingan &nbsp;<span class="badge" id="badge1"></a></li>
							<li><a href="#tab2" data-toggle="tab" class="text-muted">Nilai Ujian &nbsp;<span class="badge" id="badge2"></a></li>
							<li><a href="#tab3" data-toggle="tab" class="text-muted">Revisi Ujian &nbsp;<span class="badge" id="badge3"></a></li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="tab-content">
<?php if ($hak==4) { ?>
							<div class="tab-pane fade in active" id="tab0">
								<table id="tbDP" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Nama</th><th>Judul</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
<?php } ?> 
							<div class="tab-pane fade <?php if ($hak==5) { echo "in active"; } ?>" id="tab1">
								<table id="tbBD" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Tanggal</th><th>Pembahasan</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
							<div class="tab-pane fade" id="tab2">
								<table id="tbPU" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Tanggal</th><th>Ujian</th><th>Mahasiswa</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
							<div class="tab-pane fade" id="tab3">
								<table id="tbRU" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Tanggal</th><th>Tahap Ujian</th><th>Program Studi</th><th>Mahasiswa</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
						</div>
					</div>
			</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>