<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "pakai.png";		
$var['active'] = "utama";
$this->load->view('template/header',$var);
$this->load->view('head_ruang');
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
			<form action="#" id="fRuang" class="form-horizontal" method="POST">
			<input name="idRuang" id="idRuang" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-4">Kode Ruang</label>
						<div class="col-md-6">
							<input name="kode" id="kode" class="form-control" type="text" style="position:relative"/>
							<div class="alert alert-danger error-form hide" id="errKode">
								<b>Gagal!</b> 
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Nama Ruang</label>
						<div class="col-md-6">
							<input name="ruang" id="ruang" class="form-control" type="text" style="position:relative"/>
							<div class="alert alert-danger error-form hide" id="errRuang">
								<b>Gagal!</b> 
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Gedung</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="gedung" id="gedung">
<?php 
	foreach($gedung as $d) echo "<option value='".$d['kd_ged']."'>".$d['nm_ged']."</option>"; 
?>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-4">Lantai</label>
						<div class="col-md-3">
							<select class="form-control" name="lantai" id="lantai">
			<?php for ($i=1; $i<=20; $i++) { echo "<option>".$i."</option>"; } ?>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-4">Kapasitas Kuliah</label>
						<div class="col-md-6">
							<input name="kapkul" id="kapkul" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Kapasitas Ujian</label>
						<div class="col-md-6">
							<input name="kapuji" id="kapuji" class="form-control" type="text"/>
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
	<h3>Ruang</h3>	
	<button type="button" class="btn btn-primary btn-tambah" style="margin-bottom:15px"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
	<table id="tbRuang" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Kode</th><th>Nama Ruang</th><th>Gedung</th><th>Lantai</th><th>Kap. Kuliah</th><th>Kap. Ujian</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>