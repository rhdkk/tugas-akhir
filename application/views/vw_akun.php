<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "setting";
$this->load->view('template/header',$var);
$data['ps'] = $akses[0]['id_ps'];
$this->load->view('head_akun',$data);
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fAkun" class="form-horizontal" method="POST">
				<input name="idAkun" id="idAkun" type="hidden"/>
				<input name="edNP" id="edNP" type="hidden"/>
				<input name="nProdi" id="nProdi" type="hidden" value="1"/>
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group">
							<input class="noA" type="hidden" value="-2"/>
							<label class="control-label col-md-3">Username</label>
							<div class="col-sm-8 controls">
								<input name="edUser" id="edUser" type="hidden"/>								
								<input name="user" id="user" class="form-control" type="text" style="position:relative"/>								
								<div class="alert alert-danger error-form hide" id="errAkun-2">
									<b>Gagal!</b>
								</div>
							</div>
						</div>	
						<div class="form-group">
							<input class="noA" type="hidden" value="-1"/>
							<label class="control-label col-md-3">Nama User</label>
							<div class="col-sm-8 controls">
								<input name="edNama" id="edNama" type="hidden"/>								
								<input name="nama" id="nama" class="form-control" type="text" style="position:relative"/>								
								<div class="alert alert-danger error-form hide" id="errAkun-1">
									<b>Gagal!</b>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Jenis User</label>
							<div class="col-sm-8 controls">
								<input name="edJenis" id="edJenis" type="hidden"/>
								<select class="form-control" name="jenis" id="jenis">
									<option value="1"> Super Admin </option>	
									<option value="2"> Admin </option>	
									<option value="3"> Pimpinan </option>		
									<option value="4"> Admin PS </option>							
								</select>								
							</div>
						</div>						
						<hr class="divider"/>
						<div class="form-group row copyA after-add-more">
							<label class="control-label col-md-3" id="lbAkun0">Akses Prodi</label>							
							<input class="noA" name="noA0" id="noA0" type="hidden" value="0"/>
							<div class="col-sm-8 controls">
								<div class="input-group">
									<input name="edProdi0" id="edProdi0" type="hidden"/>
									<select class="form-control ext-select prodi" name="prodi0" id="prodi0"><option value=""> - Pilih Prodi - </option>
				<?php foreach($prodi as $d) { echo "<option value='".$d['id_ps']."'>".$d['nm_ps']."</option>"; } ?>
									</select>
									<div class="input-group-btn">
										<button class="btn btn-success add-prodi" type="button"><i class="glyphicon glyphicon-plus"></i></button>
										<button class="btn btn-danger remove-prodi" type="button" disabled><i class="glyphicon glyphicon-minus"></i></button>										
									</div>
								</div>
								<div class="alert alert-danger error-form-akun hide" id="errAkun0">
									<b>Gagal!</b>
								</div>
							</div>								
						</div>			
						<div id="kwA"></div>
						<div class="alert alert-danger hide" id="alert-prodi">
							<p class="alert-unik-prodi">tes</p>
						</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btSave" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>
		
<div class="container"> 
	<h3>Akun Pengguna Sistem</h3>	
	<button type="button" class="btn btn-primary btn-tambah" style="margin-bottom:15px"><i class="glyphicon glyphicon-plus"></i> Tambah Data</button>
	<table id="tbAkun" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Username</th><th>Nama User</th><th>Hak Akses</th><th>Akses Program Studi</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>