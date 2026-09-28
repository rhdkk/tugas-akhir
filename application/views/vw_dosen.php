<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "pakai.png";		
$var['active'] = "utama";
$this->load->view('template/header',$var);
$this->load->view('head_dosen');
$this->load->view('template/navigation',$var);
?>
<body>
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
				<input name="idHapus" id="idHapus" class="form-control" type="hidden"/>									
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fDosen" class="form-horizontal" method="POST">
			<input name="idDosen" id="idDosen" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-4">Nama Dosen</label>
						<div class="col-md-7">
							<input name="nama" id="nama" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Jenis Dosen</label>
						<div class="col-md-5">
							<select class="form-control" name="jenis" id="jenis">
								<option value="1"> Tetap PNS </option>
								<option value="2"> Tetap Non-PNS </option>
								<option value="3"> NIDK/NUK </option>
								<option value="4"> LB PNS </option>
								<option value="5"> LB Non-PNS </option>
								<option value="6"> LB </option>
							</select>	
						</div>
					</div>
					<div class="form-group" id="secHB">
						<label class="control-label col-md-4">Homebase</label>
						<div class="col-md-7">
							<select class="form-control" name="prodi" id="prodi">
<?php 
	foreach($prodi as $d) 
	{ 
		if ($d['jenjang']=="S1") $strata = "Sarjana ";
		elseif ($d['jenjang']=="S2") $strata = "Magister ";
		elseif ($d['jenjang']=="S3") $strata = "Doktor ";
		else $strata = "Pendidikan Profesi ";
		echo "<option value='".$d['id_ps']."'>".$strata.$d['nm_ps']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>	
					<div class="form-group" id="secAsal">
						<label class="control-label col-md-4">Asal</label>
						<div class="col-md-7">
							<input name="txAsal" id="txAsal" class="form-control" type="text"/>
							<select class="form-control" name="cbAsal" id="cbAsal">
								<option>FH - UB</option>
								<option>FIA - UB</option>
								<option>FP - UB</option>
								<option>FAPET - UB</option>
								<option>FT - UB</option>
								<option>FK - UB</option>
								<option>FPIK - UB</option>
								<option>FMIPA - UB</option>
								<option>FTP - UB</option>
								<option>FISIP - UB</option>
								<option>FIB - UB</option>
								<option>FKH - UB</option>
								<option>FILKOM - UB</option>
								<option>FKG - UB</option>
							</select>
						</div>
					</div>					
					<div class="form-group">
						<label class="control-label col-md-4">Golongan (Pangkat)</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="gol" id="gol">
<?php foreach($pangkat as $d) echo "<option value='".$d['id_pangkat']."'>".$d['id_pangkat']." (".$d['nm_pangkat'].")</option>"; ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Jabatan Fungsional</label>
						<div class="col-md-5">
							<select class="form-control ext-select" name="jab" id="jab">
								<option value="1"> Tenaga Pendidik </option>
								<option value="2"> Asisten Ahli </option>
								<option value="3"> Lektor </option>
								<option value="4"> Lektor Kepala </option>
								<option value="5"> Guru besar </option>
							</select>	
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">No. Induk Dosen</label>
						<div class="col-md-7">
							<input name="no" id="no" class="form-control" type="text" style="position:relative"/>
							<div class="alert alert-danger error-form hide" id="errNo">
								<b>Gagal!</b> 
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">NIDN</label>
						<div class="col-md-5">
							<input name="nidn" id="nidn" class="form-control" type="text"/>
						</div>
					</div>					
					<div class="form-group">
						<label class="control-label col-md-4">Gelar Depan</label>
						<div class="col-md-7">
							<input name="gelar1" id="gelar1" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Gelar Belakang</label>
						<div class="col-md-7">
							<input name="gelar2" id="gelar2" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Jenis Kelamin</label>
						<div class="col-md-5">
							<select class="form-control" name="jk" id="jk">
								<option value="L">Laki-Laki</option>
								<option value="P">Perempuan</option>
							</select>
						</div>
					</div>					
					<div class="form-group">
						<label class="control-label col-md-4">Pend. Akhir</label>
						<div class="col-md-4">
							<select class="form-control" name="pend" id="pend">
								<option value="1">Sarjana (S1)</option>
								<option value="2">Magister (S2)</option>
								<option value="3">Doktor (S3)</option>
							</select>
						</div>
					</div>									
				</div>        
			</div>
			<div class="modal-footer">
				<button type="submit" id="btSave" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
			</form>
		</div>
	</div>
</div>
		
<div class="container"> 
	<h3>Dosen</h3>	
	<button type="button" class="btn btn-primary btn-tambah" style="margin-bottom:15px"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
	<table id="tbDosen" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Nama</th><th>No. Induk</th><th>NIDN</th><th>Gol.</th><th>Jabatan Fungsional</th><th>JK</th><th>jenis</th><th>Asal Dosen</th><th>Pend. Akhir</th><th>Homebase</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>