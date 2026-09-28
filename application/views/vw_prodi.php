<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "pakai.png";		
$var['active'] = "utama";
$this->load->view('template/header',$var);
$this->load->view('head_prodi');
$this->load->view('template/navigation',$var);
?>
<body>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h2>Program Studi<label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
			<h5 style="padding-left:3px">Program Studi dalam Sistem Tugas Akhir</h5>			
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
			<form action="#" id="fProdi" class="form-horizontal" method="POST">
			<input name="idProdi" id="idProdi" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-4">Jurusan</label>
						<div class="col-md-8">
							<select class="form-control" name="jur" id="jur">			
								<option value="11">Akuntansi</option>
								<option value="12">Ilmu Ekonomi</option>
								<option value="13">Manajemen</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Jenjang</label>
						<div class="col-md-3">
							<select class="form-control" name="jen" id="jen">			
								<option value="S1">Sarjana</option>
								<option value="S2">Magister</option>
								<option value="S3">Doktor</option>
								<option value="XP">Profesi</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Nama Prodi</label>
						<div class="col-md-8">
							<input name="prodi" id="prodi" class="form-control" type="text"/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Pembimbing</label>
						<div class="col-md-3">
							<div class="input-group">
								<select class="form-control" name="pemb" id="pemb">
<?php for ($i=1; $i<=3; $i++) { echo "<option>".$i."</option>"; } ?>
								</select>
							<span class="input-group-addon">Orang</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Penguji</label>
						<div class="col-md-3">
							<div class="input-group">
								<select class="form-control" name="peng" id="peng">
<?php for ($i=1; $i<=9; $i++) { echo "<option>".$i."</option>"; } ?>
								</select>
							<span class="input-group-addon">Orang</span>
							</div>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-4">Pilih Pembimbing?</label>
						<div class="col-md-3">
							<select class="form-control" name="reg" id="reg">			
								<option value="1">Ya</option>
								<option value="2">Tidak</option>
							</select>
						</div>
					</div>	
					<div class="form-group">
						<label class="control-label col-md-4">ACC Revisi Ujian</label>
						<div class="col-md-5">
							<select class="form-control" name="rev" id="rev">			
								<option value="1">Pembimbing</option>
								<option value="2">Pembimbing dan Penguji</option>
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
<div class="modal fade" id="modal_tahap" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="title-tahap">Form</h3>
      </div>
			<div class="modal-body form">
				<div class="well bg-white" id="form_tahap" style="display: none; padding:0px">
					<form action="#" id="fTahap" class="form-horizontal" method="POST">
						<input name="idProdiThp" id="idProdiThp" type="hidden"/>
						<input name="idTahap" id="idTahap" type="hidden"/>
						<input name="nPemb" id="nPemb" type="hidden"/>
						<input name="nPeng" id="nPeng" type="hidden"/>
						<div class="modal-header">
							<h4 class="modal-title" id="title-form">Form Tahap Ujian</h4>
						</div>
						<div class="modal-body form">        
							<div class="form-body">
								<div class="form-group">
									<label class="control-label col-md-3">Nama Tahap</label>
									<div class="col-md-8">
										<input name="tahap" id="tahap" class="form-control" type="text"/>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Jenis</label>
									<div class="col-md-3">
										<select class="form-control" name="jenis" id="jenis">
											<option value="1">Wajib</option>
											<option value="2">Tidak</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-md-3">Penilai</label>
									<div class="col-md-4">
										<select class="form-control" name="penilai" id="penilai">
											<option value="1">Pembimbing & Penguji</option>
											<option value="2">Pembimbing</option>
										</select>
									</div>
								</div>	
								<div class="form-group">
									<label class="control-label col-md-3">% Nilai</label>
									<div class="col-md-2">
										<input name="pNilai" id="pNilai" class="form-control skor" type="text" oninput="this.value = this.value.replace(/[^0-9]/g,'')"/>
									</div>
								</div>	
								<div class="form-group" id="groupPemb">
									<label class="control-label col-md-3">% Pembimbing</label>
									<div class="col-md-2">
										<input name="pPemb" id="pPemb" class="form-control skor" type="text" oninput="this.value = this.value.replace(/[^0-9]/g,'')"/>
									</div>
								</div>
								<div class="form-group" id="groupPeng">
									<label class="control-label col-md-3">% Penguji</label>
									<div class="col-md-2">
										<input name="pPeng" id="pPeng" class="form-control skor" type="text" oninput="this.value = this.value.replace(/[^0-9]/g,'')"/>
									</div>
								</div>	
							</div>        
						</div>
						<div class="modal-footer">
							<button type="submit" id="btSaveTahap" class="btn btn-primary">Save</button>
							<button type="button" id="btBatalTahap" class="btn btn-danger">Batal</button>
						</div>
					</form>
				</div>
				<div class="well bg-white" id="data_tahap" style="padding-bottom:0px">
					<button type="button" id="addTahap" class="btn btn-xs btn-primary"><span class="	glyphicon glyphicon-plus"></span> Tambah</button>
					<input name="idPS" id="idPS" type="hidden"/><input name="p1" id="p1" type="hidden"/><input name="p2" id="p2" type="hidden"/>
					<table id="tbTahap" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
						<thead><tr><th>#</th><th>Tahap Ujian</th><th>Jenis</th><th>Penilai</th><th>%</th><th>% Pembimbing</th><th>% Penguji</th><th></th></tr></thead>
						<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
					</table>
				</div>
			</div>			
		</div>
	</div>
</div>
		
<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<div class="well bg-white">
				<table id="tbProdi" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
					<thead><tr><th>Jurusan</th><th>Program Studi</th><th>Jumlah Pembimbing</th><th>Jumlah Penguji</th><th>Pilih Pembimbing?</th><th>ACC Revisi</th><th>Tahap Ujian</th><th></th></tr></thead>
					<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>