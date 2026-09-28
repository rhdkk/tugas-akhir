<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SATRIA - FEB UB";	
$var['icon'] = "thesis.png";		
$var['active'] = "user";
$this->load->view('template/header',$var);
$this->load->view('head_user');
$this->load->view('template/navigation',$var);
?>
<body>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h2>User Sistem<label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
			<h5 style="padding-left:3px">Pengguna Sistem Tugas Akhir</h5>			
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
					<p class="confirm-msg-hapus">tes</p>  				
					<input name="idHapus" id="idHapus" class="form-control" type="hidden"/>									
				</div>
				<div class="modal-footer">
					<button type="submit" id="btHapus" class="btn btn-primary">Ya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
				</div>
			</form>
			<form action="#" id="fReset" class="form-horizontal" method="POST">
				<div class="modal-body">       
					<p class="confirm-msg-reset">tes</p>  				
					<input name="idReset" id="idReset" class="form-control" type="hidden"/>									
				</div>
				<div class="modal-footer">
					<button type="submit" id="btReset" class="btn btn-primary">Ya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_import" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Import Data Mahasiswa</h3>
			</div>

			<div class="modal-body">
				<!-- Toolbar: Unduh Template + Pilih File -->
				<div class="row" style="margin-bottom:10px;">
					<div class="col-xs-12">
						<!--
							Tombol Unduh Template:
							Menggunakan <a> biasa dengan href ke controller.
							Controller mengirim header:
								Content-Disposition: attachment; filename="..."
								Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
							Browser akan memunculkan dialog Save As (jika pengaturan browser
							"Ask where to save each file before downloading" diaktifkan),
							atau langsung download ke folder Downloads default.
							Tidak ada cara memaksa dialog Save As dari JavaScript/server
							tanpa mengubah pengaturan browser pengguna.
						-->
						<a href="<?= base_url('user/download_template') ?>" class="btn btn-default btn-sm"> <i class="fa fa-download"></i> Unduh Template </a>&nbsp;

						<!-- Pilih File Excel: label membungkus input[type=file] -->
						<label class="btn btn-info btn-sm" style="margin-bottom:0; font-weight:normal; cursor:pointer;">
							<i class="fa fa-folder-open-o"></i> Pilih File Excel <input type="file" id="fileExcel" accept=".xls,.xlsx" style="display:none;" onchange="onFileSelected(this)">
						</label>
						<span id="namaFile" style="margin-left:8px; font-size:12px; color:#777;"></span>
					</div>
				</div>

				<!-- Loading -->
				<div id="divLoading" style="display:none; margin-bottom:8px;"><i class="fa fa-spinner fa-spin"></i> Membaca file Excel...</div>

				<!-- Pesan error / warning -->
				<div id="divPesanFile" style="display:none;"></div>

				<!-- Tabel preview -->
				<div id="divPreview" style="display:none;">
					<div style="max-height:340px; overflow-y:auto; border:1px solid #ddd;">
						<table class="table table-bordered table-condensed table-hover" style="margin-bottom:0;">
							<thead style="background:#f5f5f5;">
								<tr>
									<th style="width:40px;">#</th>
									<th>NIM</th>
									<th>Nama</th>
									<th>Program Studi</th>
									<th>Jalur</th>
									<th style="width:90px; text-align:center;">Status</th>
								</tr>
							</thead>
							<tbody id="tbodyPreview"></tbody>
						</table>
					</div>
					<p id="infoJumlah" style="margin:6px 0 0; font-size:12px; color:#555;"></p>
				</div>

				<!-- Ringkasan hasil setelah simpan -->
				<div id="divHasil" style="display:none;"></div>
			</div><!-- /.modal-body -->

			<div class="modal-footer">
				<button type="button" id="btSave" class="btn btn-primary" style="display:none;" onclick="simpanImport()"><i class="fa fa-save"></i> Save </button>
				<button type="button" id="btBatal" class="btn btn-danger" data-dismiss="modal"> Batal </button>
			</div>
		</div>
	</div>
</div><!-- /#modal_import -->

<div class="modal fade" id="modal_mhs" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fMhs" class="form-horizontal" method="POST">
			<input name="nmA" id="nmA" type="hidden"/>
			<input name="jlrA" id="jlrA" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">NIM</label>
						<div class="col-md-4">
							<input name="nim" id="nim" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>
						<div class="col-md-9">
							<input name="nama" id="nama" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Program Studi</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="prodi" id="prodi"><option value="" style="width:100%"> - Pilih Prodi - </option>
<?php 
	foreach($prodi as $d) 
	{ 
		echo "<option>".$d['jenjang']." ".$d['nm_ps']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Jalur Seleksi</label>
						<div class="col-md-4">
							<select class="form-control" name="jalur" id="jalur">
								<option value="1">Reguler 1</option>
								<option value="2">Reguler 2</option>
								<option value="3">Internasional</option>
								<option value="3">Kerjasama</option>
							</select>
						</div>
					</div>					
				</div>        
				<div id="alert-mhs" class="alert alert-danger" style="display:none; margin-bottom:15px;"></div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="btSaveMhs" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
			</form>
		</div>
	</div>
</div> <!-- /#modal_mhs -->

<div class="modal fade" id="modal_dosen" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fDosen" class="form-horizontal" method="POST">
			<input name="idUD" id="idUD" type="hidden"/>
			<input name="psDA" id="psDA" type="hidden"/>
			<input name="jurDA" id="jurDA" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>
						<div class="col-md-9"><input name="namaDA" id="namaDA" class="form-control" type="text" readonly/></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>						
						<div class="col-md-9">							
							<select class="form-control ext-select" name="namaD" id="namaD"><option value="" style="width:100%"> - Pilih Dosen - </option>
<?php 
	foreach($dosen as $d) 
	{ 
		echo "<option value=".$d['id_dosen'].">".$d['nm_dosen']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Jabatan</label>
						<div class="col-md-5">
							<select class="form-control" name="jabD" id="jabD"><option value="" style="width:100%"> Pembimbing/Penguji </option>
								<option value=1>Ketua Departemen</option>
								<option value=2>Ketua Program Studi</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Departemen</label>
						<div class="col-md-4">
							<select class="form-control" name="jurD" id="jurD"><option value="" style="width:100%"> - Pilih Departemen - </option>
<?php 
	foreach($jurusan as $d) 
	{ 
		echo "<option value=".$d['id_jur'].">".$d['nm_jur']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-3">Program Studi</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="prodiD" id="prodiD"><option value="" style="width:100%"> - Pilih Prodi - </option>
<?php 
	foreach($prodi as $d) 
	{ 
		echo "<option>".$d['jenjang']." ".$d['nm_ps']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>									
				</div>        
				<div id="alert-dosen" class="alert alert-danger" style="display:none; margin-bottom:15px;"></div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="btSaveDosen" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
			</form>
		</div>
	</div>
</div> <!-- /#modal_dosen -->

<div class="modal fade" id="modal_admin" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Form</h3>
      </div>
			<form action="#" id="fAdmin" class="form-horizontal" method="POST">
			<input name="idUAdm" id="idUAdm" type="hidden"/>
			<input name="namaAA" id="namaAA" type="hidden"/>
			<input name="psAA" id="psAA" type="hidden"/>
			<input name="jurAA" id="jurAA" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-3">Username</label>
						<div class="col-md-9"><input name="unameAdm" id="unameAdm" class="form-control" type="text"/></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Nama</label>
						<div class="col-md-9"><input name="namaAdm" id="namaAdm" class="form-control" type="text"/></div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Jenis</label>
						<div class="col-md-5">
							<select class="form-control" name="jabAdm" id="jabAdm">
								<option value=3 style="width:100%">Admin Departemen</option>
								<option value=2>Admin Program Studi</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-3">Departemen</label>
						<div class="col-md-4">
							<select class="form-control" name="jurAdm" id="jurAdm"><option value="" style="width:100%"> - Pilih Departemen - </option>
<?php 
	foreach($jurusan as $d) 
	{ 
		echo "<option value=".$d['id_jur'].">".$d['nm_jur']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-3">Program Studi</label>
						<div class="col-md-7">
							<select class="form-control ext-select" name="prodiAdm" id="prodiAdm"><option value="" style="width:100%"> - Pilih Prodi - </option>
<?php 
	foreach($prodi as $d) 
	{ 
		echo "<option>".$d['jenjang']." ".$d['nm_ps']."</option>"; 
	} 
?>
							</select>
						</div>
					</div>									
				</div>        
				<div id="alert-admin" class="alert alert-danger" style="display:none; margin-bottom:15px;"></div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="btSaveAdmin" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>
			</form>
		</div>
	</div>
</div> <!-- /#modal_admin -->
		
<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<div class="well bg-white">
				<div class="panel with-nav-tabs panel-default">
					<div class="panel-heading">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tab0" data-toggle="tab" class="text-muted">Mahasiswa &nbsp;<span class="badge" id="badge0"></span></a></li>
							<li><a href="#tab1" data-toggle="tab" class="text-muted">Dosen &nbsp;<span class="badge" id="badge1"></span></a></li>
							<li><a href="#tab2" data-toggle="tab" class="text-muted">Admin &nbsp;<span class="badge" id="badge2"></a></li>
						</ul>
					</div>
					<div class="panel-body">
						<div class="tab-content">
							<div class="tab-pane fade in active" id="tab0">
								<div class="btn-group" role="group" style="margin-bottom:10px">
									<button type="button" id="addMhs" class="btn btn-primary"><span class="	glyphicon glyphicon-plus"></span> Tambah</button>
									<button type="button" id="importMhs" class="btn btn-success"><i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i> Import</button>
								</div>
								<table id="tbMhs" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>NIM</th><th>Nama</th><th>Program Studi</th><th>Jalur</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>
							<div class="tab-pane fade" id="tab1">
								<button type="button" id="addDosen" class="btn btn-primary" style="margin-bottom:10px"><span class="	glyphicon glyphicon-plus"></span> Tambah</button>
								<table id="tbDosen" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Username</th><th>Nama</th><th>Jabatan</th><th>Departemen</th><th>Program Studi</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
								</table>
							</div>	
							<div class="tab-pane fade" id="tab2">
								<button type="button" id="addAdmin" class="btn btn-primary" style="margin-bottom:10px"><span class="	glyphicon glyphicon-plus"></span> Tambah</button>
								<table id="tbAdmin" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
									<thead><tr><th>Username</th><th>Nama</th><th>Jenis</th><th>Departemen</th><th>Program Studi</th><th></th></tr></thead>
									<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
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