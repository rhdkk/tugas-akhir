<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "pakai.png";		
$var['active'] = "utama";
$this->load->view('template/header',$var);
$this->load->view('head_matkul');
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
			<form action="#" id="fMatkul" class="form-horizontal" method="POST">
			<input name="idMatkul" id="idMatkul" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-4">Kode Mata Kuliah</label>
						<div class="col-md-3">
							<input name="kode" id="kode" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Tahun Kurikulum</label>
						<div class="col-md-3">
							<input name="thkur" id="thkur" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Mata Kuliah</label>
						<div class="col-md-7">
							<input name="nama" id="nama" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">sks</label>
						<div class="col-md-3">
							<select class="form-control" name="sks" id="sks">
			<?php for ($i=1; $i<=6; $i++) { echo "<option>".$i."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Semester</label>
						<div class="col-md-3">
							<select class="form-control" name="sem" id="sem">
			<?php for ($i=1; $i<=8; $i++) { echo "<option>".$i."</option>"; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Jenis</label>
						<div class="col-md-3">
							<select class="form-control" name="jenis" id="jenis">
								<option value="1">Wajib</option>
								<option value="2">Pilihan</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Ditawarkan</label>
						<div class="col-md-4">
							<select class="form-control" name="tawar" id="tawar">
								<option value="1">Ganjil</option>
								<option value="2">Genap</option>
								<option value="3">Ganjil - Genap</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Program Studi</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="prodi" id="prodi"><option value="" style="width:100%"> - Pilih Prodi - </option>
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
	<h3>Mata Kuliah</h3>	
	<button type="button" class="btn btn-primary btn-tambah" style="margin-bottom:15px"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
	<table id="tbMatkul" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Kode MK</th><th>Th. Kur.</th><th>Mata kuliah</th><th>sks</th><th>Smt</th><th>Ditawarkan</th><th>Jenis</th><th>Program Studi</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>