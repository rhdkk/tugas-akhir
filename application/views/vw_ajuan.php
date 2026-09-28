<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Layanan Mahasiswa";	
$var['icon'] = "jadwal.png";		
$var['active'] = "setting";
$this->load->view('template/header',$var);
$this->load->view('head_ajuan');
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
			
			<form action="#" id="form1" class="form-horizontal" method="POST" enctype="multipart/form-data">				
				<input name="idF1" id="idF1" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">NIM</label>
							<div class="col-sm-8 controls">
								<div id="nimF1">nim</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Nama</label>
							<div class="col-sm-8 controls">
								<div id="namaF1">nama</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Judul</label>
							<div class="col-sm-8 controls">
								<div id="judulF1">judul</div>
							</div>
						</div>		
						<div class="form-group">
							<label class="control-label col-md-3">File</label>
							<div class="col-sm-8 controls">
								<a href="" class="btn btn-danger btn-xs" id="fileF1" target="_blank">View File</a>
							</div>
						</div>
						<hr class="divider"/>						
						<div class="form-group">
							<div class="col-md-12 controls" style="text-align:center">								
								<div id="konfF1">Konfirmasi</div>
							</div>								
						</div>									
					</div>        
				</div>
			</form>
			
			<form action="#" id="form2" class="form-horizontal" method="POST" enctype="multipart/form-data">				
				<input name="idF2" id="idF2" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body">
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
						<div class="form-group">
							<label class="control-label col-md-3" id="lbDosen0">Penguji 1</label>
							<input class="noD" name="noD0" id="noD0" type="hidden" value="0"/>
							<div class="col-md-8 controls">								
								<select class="form-control ext-select dosen" name="dosen0" id="dosen0"><option value=""> - Pilih Dosen - </option>
				<?php foreach($dosen as $d) { echo "<option value='".$d['id_user']."'>".$d['nm_user']."</option>"; } ?>
								</select>									
								<div class="alert alert-danger error-form-2 hide" id="errDosen0">
									<b>Gagal!</b> Ruangan sudah dipakai untuk acara yang lain.
								</div>
							</div>
						</div>	
						<div class="form-group">
							<label class="control-label col-md-3" id="lbDosen0">Penguji 2</label>
							<input class="noD" name="noD1" id="noD1" type="hidden" value="1"/>
							<div class="col-md-8 controls">
								<select class="form-control ext-select dosen" name="dosen1" id="dosen1"><option value=""> - Pilih Dosen - </option>
				<?php foreach($dosen as $d) { echo "<option value='".$d['id_user']."'>".$d['nm_user']."</option>"; } ?>
								</select>																	
								<div class="alert alert-danger error-form-2 hide" id="errDosen1">Error</div>								
							</div>
						</div>						
						<div class="alert alert-danger hide" id="alert-dosen">
							<p class="alert-unik-dosen">tes</p>
						</div>							
					</div>        
				</div>
			</form>
			
			<form action="#" id="form3" class="form-horizontal" method="POST" enctype="multipart/form-data">				
				<input name="idF3" id="idF3" type="hidden"/>
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">Judul</label>
							<div class="col-sm-8 controls">
								<div id="judulF3">judul</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Penguji 1</label>
							<div class="col-sm-8 controls">
								<div id="penguji1F3">dosen</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Penguji 2</label>
							<div class="col-sm-8 controls">
								<div id="penguji2F3">dosen</div>
							</div>
						</div>						
						<hr class="divider"/>						
						<div class="form-group">
							<label class="control-label col-md-3">Tanggal</label>						
							<div class="col-sm-8" id="singledate">
								<input name="tglF3" id="tglF3" class="form-control" type="text" readonly/>
								<div class="alert alert-danger error-form-3 hide" id="errTgl">Error</div>
							</div>							
						</div>					
						<div class="form-group">
							<label class="control-label col-md-3">Waktu</label>
							<div class="col-sm-8 controls">
								<div class="input-group">
									<input type="text" name="mulaiF3" id="mulaiF3" class="form-control col-md-3" placeholder="mulai" readonly/>
									<span class="input-group-addon">-</span>
									<input type="text" name="selesaiF3" id="selesaiF3" class="form-control col-md-3" placeholder="selesai" readonly/>
								</div>
							</div>
						</div>						
						<div class="alert alert-danger hide" id="alert-waktu">
							<p class="alert-dt-waktu">tes</p>
						</div>							
					</div>        
				</div>
			</form>
			
			<form action="#" id="form4" class="form-horizontal" method="POST" enctype="multipart/form-data">				
				<input name="idF4" id="idF4" type="hidden"/>
				<input name="nBF4" id="nBF4" type="hidden"/>
				<div class="modal-body form">    
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">NIM</label>
							<div class="col-sm-8 controls">
								<div id="nimF4">nim</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Nama</label>
							<div class="col-sm-8 controls">
								<div id="namaF4">nama</div>
							</div>
						</div>					
						<div class="form-group">
							<label class="control-label col-md-3">Hari, Tanggal</label>
							<div class="col-sm-8 controls">
								<div id="tglF4">hartang</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Waktu</label>
							<div class="col-sm-8 controls">
								<div id="wktF4">jam</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Penguji 1</label>
							<div class="col-sm-8 controls">
								<div id="penguji1F4">dosen</div>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Penguji 2</label>
							<div class="col-sm-8 controls">
								<div id="penguji2F4">dosen</div>
							</div>
						</div>						
						<hr class="divider"/>	
						<div class="form-group">						
							<label class="control-label col-md-3" id="lbSyarat0">Upload Berkas</label>
							<input class="noB" name="noB0" id="noB0" type="hidden" value="0"/>
							<input class="idB" name="idB0" id="idB0" type="hidden"/>
							<div class="col-sm-8 controls">								
								<input type="file" class="berkas" name="berkas0" id="berkas0">																	
								<div class="alert alert-danger error-form-4 hide" id="errBerkas0">
									<b>Gagal!</b>
								</div>
							</div>
						</div>
					</div>        
				</div>
			</form>
			
			<form action="#" id="fAjuan" class="form-horizontal" method="POST" enctype="multipart/form-data">
				<input name="nS" id="nS" type="hidden" value="1"/>
				<div class="modal-body form">        
					<div>
						<div class="form-group">
							<label class="control-label col-md-3">Permohonan</label>
							<div class="col-sm-8 controls">
								<input name="edJenis" id="edJenis" type="hidden"/>
								<select class="form-control" name="jenis" id="jenis"><option value=""> - Pilih Permohonan - </option>
<?php 
	foreach($layanan as $d) echo "<option value='".$d['id_jenis']."'>".$d['nm_jenis']."</option>"; 
	foreach($ujian as $d) echo "<option value='".$d['id_jenis']."'>".$d['nm_jenis']."</option>"; 
?>
								</select>																	
							</div>
						</div>	
						<div class="form-group jdl" style="display:none">
							<label class="control-label col-md-3">Judul</label>
							<div class="col-sm-8 controls">
								<input name="yJ" id="yJ" type="hidden"/>
								<textarea class="form-control" id="judul" name="judul" rows="5" style="resize: none;"></textarea>							
							</div>
						</div>
						<div class="form-group dpt" style="display:none">
							<label class="control-label col-md-3">Konsetrasi</label>
							<div class="col-sm-8 controls">
								<select class="form-control" name="kons" id="kons">
									<option value="1">Hukum Pidana</option>
									<option value="2">Hukum Perdata</option>
									<option value="3">Hukum Administras Negara</option>
									<option value="4">Hukum Tata Negara</option>
									<option value="5">Hukum Internasional</option>
									<option value="6">Hukum Islam</option>
								</select>								
							</div>
						</div>
						<hr class="divider" style="display:none"/>
						<div class="form-group row copyS after-add-more" style="display:none">
							<label class="control-label col-md-3" id="lbSyaratA0">Prasyarat</label>							
							<input class="noS" name="noS0" id="noS0" type="hidden" value="0"/>
							<input class="idS" name="idS0" id="idS0" type="hidden"/>
							<input class="nmS" name="nmS0" id="nmS0" type="hidden"/>
							<div class="col-sm-8 controls">								
								<input type="file" class="syarat" name="syarat0" id="syarat0">																	
								<div class="alert alert-danger error-form-ajuan hide" id="errAjuan0">
									<b>Gagal!</b>
								</div>
							</div>								
						</div>			
						<div id="kwS"></div>
						<div class="alert alert-danger hide" id="alert-prodi">
							<p class="alert-unik-prodi">tes</p>
						</div>
					</div>        
				</div>
			</form>
			
			<div class="modal-footer">
				<button type="button" id="btSave" class="btn btn-primary">Save</button>				
				<button type="button" id="btYF1" class="btn btn-primary">Setuju</button>				
				<button type="button" id="btYF2" class="btn btn-primary">Simpan</button>
				<button type="button" id="btYF3" class="btn btn-primary">Simpan</button>
				<button type="button" id="btYF4" class="btn btn-primary">Simpan</button>
				<button type="button" id="btNF1" class="btn btn-danger">Tidak</button>				
				<button type="button" id="btNo" class="btn btn-danger" data-dismiss="modal">Batal</button>
				
			</div>			
		</div>
	</div>
</div>
		
<div class="container"> 
	<h3>Pengajuan Layanan</h3>	
<?php if ($hak==2) { ?>
	<button type="button" class="btn btn-primary btn-tambah" style="margin-bottom:15px"><i class="glyphicon glyphicon-plus"></i> Baru</button>
<?php } ?>
	<table id="tbAjuan" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>NIM</th><th>Nama</th><th>Pengajuan</th><th>Waktu</th><th>Progress</th><th></th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>