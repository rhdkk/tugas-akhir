<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Karya Tulis FH-UB";		
$var['active'] = "karya";
$this->load->view('template/header',$var);
$this->load->view('head_karya');
$this->load->view('template/navigation',$var);
?>
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
        <h3 class="modal-title">Hapus Karya Tulis</h3>
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
        <h3 class="modal-title">Form Karya Tulis</h3>
      </div>
			<form action="#" id="fKarya" class="form-horizontal" method="POST">
			<div class="modal-body form">        
				<div class="form-body">
					<input name="idKarya" id="idKarya" type="hidden"/>
					<div class="form-group has-feedback">
						<label class="control-label col-md-3">NIM</label>
						<div class="col-md-9"><input name="nim" id="nim" placeholder="Nomor Induk Mahasiswa" class="form-control" type="text"/></div>							
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>
						<div class="col-md-9"><input name="nama" id="nama" placeholder="Nama Mahasiswa" class="form-control" type="text"/></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Judul</label>
						<div class="col-md-9"><textarea class="form-control" rows="3" name="judul" id="judul" style="resize:none"></textarea></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Abstrak</label>
						<div class="col-md-9"><textarea class="form-control" rows="10" name="abstrak" id="abstrak" style="resize:none; font-size:10pt"></textarea></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-md-3">Program Studi</label>
						<div class="col-md-9">
							<select class="form-control" name="idProdi" id="idProdi">								
<?php foreach($data['prodi'] as $d) { echo "<option value='".$d['id_prodi']."'>".$d['nm_prodi']."</option>"; } ?>
							</select>
						</div>
					</div>				
					<div class="form-group">
						<label class="control-label col-md-3">Konsentrasi</label>
						<div class="col-md-9">
							<select class="form-control" name="idKonsentrasi" id="idKonsentrasi">								
<?php foreach($data['konsentrasi'] as $d) { echo "<option value='".$d['id_konsentrasi']."'>".$d['nm_konsentrasi']."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Pembimbing 1</label>
						<div class="col-md-9">
							<select class="form-control" name="dosen1" id="dosen1">								
<?php foreach($data['dosen'] as $d) { echo "<option>".$d['nm_dosen']."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Pembimbing 2</label>
						<div class="col-md-9">
							<select class="form-control" name="dosen2" id="dosen2">								
<?php foreach($data['dosen'] as $d) { echo "<option>".$d['nm_dosen']."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Pembimbing 3</label>
						<div class="col-md-9">
							<select class="form-control" name="dosen3" id="dosen3">								
<?php foreach($data['dosen'] as $d) { echo "<option>".$d['nm_dosen']."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Tahun</label>
						<div class="col-md-4"><input type="text" class="form-control" name="tahun" id="tahun"></div>
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
<div class="modal fade" id="modal_detil" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Detil Karya Tulis</h3>
      </div>
			<div class="modal-body">     

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>
		
<div class="container">
	<h3>Daftar Karya Tulis</h3>
	<?php 
	$hak = $this->session->userdata('hak_thesis');
	if ($hak==1 or $hak==2) { ?>
<button type="button" class="btn btn-success btn-tambah" style="margin-bottom:15px;"><i class="glyphicon glyphicon-plus"></i> Tambah Karya Tulis</button> <br/><br/> <?php } ?>	
	<table id="tbKarya" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead><tr><th>NIM</th><th>Nama</th><th>Judul</th><th>Jenis</th><th>Aksi</th></tr></thead>		
		<tfoot><tr><th>NIM</th><th>Nama</th><th>Judul</th><th>Jns</th><th></th></tr></tfoot>		
	</table>
</div>
<?php $this->load->view('template/footer');	?>