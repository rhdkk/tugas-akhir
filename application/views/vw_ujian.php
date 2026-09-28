<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Layanan Mahasiswa";	
$var['icon'] = "jadwal.png";		
$var['active'] = "setting";
$this->load->view('template/header',$var);
$this->load->view('head_ujian');
$this->load->view('template/navigation',$var);
$nama = $this->session->userdata('nama_layanan'); 
$hak = $this->session->userdata('hak_layanan');
$nim = $this->session->userdata('nim_layanan');
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
			<form action="#" id="fUjian" class="form-horizontal" method="POST" enctype="multipart/form-data">
				<input name="nN" id="nN" type="hidden" value="1"/>
				<div class="modal-body form">        
					<div class="form-group">
						<label class="control-label col-md-3">NIM</label>
						<div class="col-sm-8 controls">
							<div id="nimF2">nim</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>
						<div class="col-sm-8 controls">
							<div id="namaF2">nama</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Judul</label>
						<div class="col-sm-8 controls">
							<div id="judulF2">judul</div>
						</div>
					</div>		
					<div class="form-group">
						<label class="control-label col-md-3">File</label>
						<div class="col-sm-8 controls">
							<a href="" class="btn btn-danger btn-xs" id="fileF2" target="_blank">View File</a>
						</div>
					</div>
					<hr class="divider"/>
					<div class="form-group row copyS after-add-more">
						<label class="control-label col-md-3" id="lbNilai0">Nilai</label>							
						<input class="noN" name="noN0" id="noN0" type="hidden" value="0"/>												
						<div class="col-sm-8 controls">
							<select class="form-control" name="skor0" id="skor0">
<?php for ($i=0; $i<=100; $i+5) { echo "<option>".$i."</option>"; } ?>
							</select>								
						</div>
					</div>			
					<div id="kwN"></div>					
				</div>
			</form>
			
			<div class="modal-footer">
				<button type="button" id="btSave" class="btn btn-primary">Save</button>				
				<button type="button" id="btNF1" class="btn btn-danger">Tidak</button>				
				<button type="button" id="btNo" class="btn btn-danger" data-dismiss="modal">Batal</button>				
			</div>			
		</div>
	</div>
</div>
		
<div class="container"> 
	<h3>Ujian Tugas Mahasiswa</h3>	
	<table id="tbUjian" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>NIM</th><th>Nama</th><th>Ujian</th><th>Progrram Studi</th><th>Waktu Ujian</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>