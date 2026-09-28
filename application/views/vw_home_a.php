<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Tugas Akhir - FEB UB";	
$var['icon'] = "jadwal.png";		
$var['active'] = "kelas";
$this->load->view('template/header',$var);
$hak = $this->session->userdata('hak_thesis');
$data['ps'] = $akses[0]['id_ps'];
$nPemb = $akses[0]['p1'];
$nPeng = $akses[0]['p2'];
$jenjang = $akses[0]['jenjang'];
$data['nPemb'] = $nPemb;
$data['nPeng'] = $nPeng;
$this->load->view('head_home_a',$data);
$this->load->view('template/navigation',$var);
?>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h2>Beranda <label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
			<h5 style="padding-left:3px">
<?php 
	if ($hak==1) echo "Admin Sistem";
	elseif ($hak==2) echo "Admin Program Studi";
	if ($hak==3) echo "Admin Jurusan";
?>
			</h5>
		</div>
</div>

<div class="modal fade" id="modal_formMhs" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fMhs" class="form-horizontal" method="POST">
				<input name="nimMhs" id="nimMhs" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body well skin-yellow">
						<div class="form-group row">
							<div class="col-md-12">
								<b id="lbNamaMhs" class="text-muted">Nama</b><br/>
								<span id="lbNimMhs">NIM. </span>								
							</div>
						</div>
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12">
									<h4 style="margin-bottom:7px">
										<span id="" class="label label-default" style="font-weight:normal">ini adalah tanggal</span></h4>	
									<b><span class="text-primary" id="">Pembahasan Bimbingan</span></b>									
									<h4><span id="">ini adalah keterangan</span></h4>
									<br/><button type="button" id="" class="btn btn-primary btn-xs">Show History</button>
							</div>
						</div>						
					</div>										
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btMhs" class="btn btn-primary">Simpan</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formUji" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fUji" class="form-horizontal" method="POST">
				<input name="nimUji" id="nimUji" type="hidden"/>				
				<input name="idAjuan" id="idAjuan" type="hidden"/>				
				<input name="fnUji" id="fnUji" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row">
							<div class="col-md-6">
								<b id="nmUji">Nama</b><br/>						
								<span id="nimDiuji">NIM.</span><br/>								
							</div><div class="col-md-6">
								<b id="thpUji">Tahap Ujian</b><br/>						
								<span id="nmPS">Program Studi</span>								
							</div>
							<hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-4 form-comp">
								<label for="tglUji" class="control-label">Tanggal</label>
								<div><input class="form-control" name="tglUji" id="tglUji" type='text' readonly/></div>
								<div class="alert alert-info hide" id="infoTgl" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div>
							<div class="col-md-4 form-comp">
								<label for="jamUji1" class="control-label">Mulai</label>
								<div><input class="form-control" name="jamUji1" id="jamUji1" type='text' readonly/></div>
								<div class="alert alert-info hide" id="infoJam" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div>
							<div class="col-md-4 form-comp">
								<label for="jamUji2" class="control-label">Selesai</label>
								<div><input class="form-control" name="jamUji2" id="jamUji2" type='text' readonly/></div>
								<div class="alert alert-info hide" id="infoJam" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div>
							<div class="col-md-12 form-comp">
								<label for="ruangUji" class="control-label">Ruang</label>
								<div>
									<select class="form-control ext-select ruang" name="ruangUji" id="ruangUji" style="width: 100%">
										<option value=""> - Ruang - </option>
<?php foreach($ruang as $d) { echo "<option value='".$d['id_ruang']."'>".$d['nm_ruang']."</option>"; } ?>
									</select>
								</div>
								<div class="alert alert-info hide" id="infoRuang" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div>
							<hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-12" id="txPembUji"></div>
							<div class="col-md-12" id="txPengUji"></div>	
						</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btUji" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formVerif" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
			<form action="#" id="fVerif" class="form-horizontal" method="POST">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleBimb">Verifikasi Berkas</h3>
      </div>
				<input name="idAju" id="idAju" type="hidden"/>
				<input name="nim" id="nim" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body well skin-yellow">
						<div class="form-group row">
							<div class="col-md-12">
								<b id="lbNama" class="text-muted">Nama</b><br/>
								<span id="lbKetMhs">NIM. </span><br/>							
								<span id="lbProdi" class="text-muted">Prodi. </span>								
							</div>
						</div>
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12">
									<h4 style="margin-bottom:5px"><span id="lbNmUji" style="font-weight:normal">Ujiannya</span></h4>
							</div>
						</div>						
					</div>
					<table id="tbVerif" class="table table-striped table-hover table-sm display responsive" style="margin-bottom:0px" cellspacing="0" width="100%">
						<thead><tr><th>Berkas Prasyarat</th><th style="width:10%">Verf</th></tr></thead>
						<tbody></tbody>
					</table>
				</div>
			<div class="modal-footer">
					<button type="submit" id="btVerif" class="btn btn-primary">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>	
			</form>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formSah" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fSah" class="form-horizontal" method="POST">
				<input name="nimSah" id="nimSah" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row">
							<div class="col-md-12">
								<b id="nmMhs0">Nama</b><br/>						
								<span id="nimMhs0">NIM.</span>								
							</div><hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-12">
								<b>Judul</b><br/>						
								<span id="judul">Ini adalah judulnya</span>								
							</div>
							<hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-12" id="txPemb"></div>
							<hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-12" id="txPeng"></div>		
							<hr class="col-md-12" style="padding:0px; margin-bottom:5px"/>
							<div class="col-md-6 form-comp">
								<label for="noSK" class="control-label">No. SK</label>
								<div><input class="form-control" name="noSK" id="noSK" type='text'/></div>
								<div class="alert alert-info hide" id="infoNoSK" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div><br/>
							<div class="col-md-6 form-comp">
								<label for="tglSK" class="control-label">Tanggal SK</label>
								<div><input class="form-control" name="tglSK" id="tglSK" type='text' readonly/></div>
								<div class="alert alert-info hide" id="infoNoSK" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>								
							</div>							
						</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btSah" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>

<div class="modal fade" id="modal_formReg" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fDosen" class="form-horizontal" method="POST">
				<input name="nimReg" id="nimReg" type="hidden"/>				
				<input name="nPemb" id="nPemb" type="hidden"/>				
				<input name="nPeng" id="nPeng" type="hidden"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row">
							<div class="col-md-12">
								<label class="control-label">Judul</label>						
								<h5 id="judulReg">Ini adalah judulnya</h5>
								<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/>
							</div>
							<div class="col-md-12 form-comp copyPemb">
								<label for="pemb1" class="control-label" id="lbPemb0">Pembimbing</label>
								<div class="input-group" id="par1">
									<input name="idPemb1" id="idPemb1" type="hidden"/>																		
									<select class="form-control ext-select dosen" name="pemb1" id="pemb1"><option value='' style="width:100%"> - Pilih Dosen - </option>
<?php 
	foreach($dosen as $d) 
	{ 
		$str = "<option value='".$d['id_dosen']."'>";
		if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
		$str .= $d['nm_dosen'];
		if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
		$str .= "</option>";
		echo $str; 
	} 
?>
									</select>	
									<div class="input-group-btn">
										<button class="btn btn-primary btn-pemb" type="button" id="jmlPemb0">0</button>										
									</div>									
								</div>
								<div class="alert alert-info hide" id="infoPemb0" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px">
									Jumlah Mahasiswa bimbingan dosen yang bersangkutan:
									<ul>
										<li><b>1</b> Mahasiswa, pada Prodi ini</li>
										<li><b>2</b> Mahasiswa, pada Prodi yang lain</li>
									</ul>
								</div>
								<hr class='col-md-12' style='padding:0px; margin-bottom:5px'/>
							</div>
							<div id="kwPemb"></div>
							
							<div class="col-md-12 form-comp copyPeng">
								<label for="peng1" id="lbPeng1" class="control-label">Penguji 1</label>
								<div>
									<input name="idPeng1" id="idPeng1" type="hidden"/>									
									<select class="form-control ext-select" name="peng1" id="peng1"><option value="" style="width:100%"> - Pilih Dosen - </option>
<?php
	foreach($dosen as $d) 
	{ 
		$str = "<option value='".$d['id_dosen']."'>";
		if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
		$str .= $d['nm_dosen'];
		if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
		$str .= "</option>";
		echo $str; 
	} 
?>
									</select>	
								</div>
								<div class="alert alert-info hide" id="infoPeng1" style="margin-bottom:0px; margin-top:-8px; padding-bottom:7px"></div>
							</div>
							<div id="kwPeng"></div>
							<div class="col-md-12"/><hr class="col-md-12" style="padding:0px; margin-bottom:5px"/></div>
							<div class="col-md-6 form-comp">
								<label for="noSKReg" class="control-label">No. SK</label>
								<div><input class="form-control" name="noSKReg" id="noSKReg" type='text'/></div>
							</div><br/>
							<div class="col-md-6 form-comp">
								<label for="tglSKReg" class="control-label">Tanggal SK</label>
								<div><input class="form-control" name="tglSKReg" id="tglSKReg" type='text' readonly/></div>
							</div>	
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
</div

<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<div class="well bg-white">
				<h3 class="h4">Data Tugas Akhir Mahasiswa</h4><hr/>
				<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab0" data-toggle="tab" class="text-muted">Pembimbing &nbsp;<span class="badge" id="badge0"></span></a></li>
							<li><a href="#tab1" data-toggle="tab" class="text-muted">Pengajuan &nbsp;<span class="badge" id="badge1"></span></a></li>
							<li><a href="#tab2" data-toggle="tab" class="text-muted">Ujian &nbsp;<span class="badge" id="badge2"></a></li>
							<li><a href="#tab3" data-toggle="tab" class="text-muted">Mahasiswa &nbsp;<span class="badge" id="badge3"></a></li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab0">
								<table id="tbSah" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
							<div class="tab-pane fade" id="tab1">
								<table id="tbAju" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Ujian</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>	
							<div class="tab-pane fade" id="tab2">
								<table id="tbUji" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Ujian</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>							
							<div class="tab-pane fade" id="tab3">
								<table id="tbMhs" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Aksi</th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td></tr></tfoot>
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