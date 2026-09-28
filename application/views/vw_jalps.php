<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "pakai.png";		
$var['active'] = "settings";
$this->load->view('template/header',$var);
$this->load->view('head_jalps');
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
			<form action="#" id="fJalps" class="form-horizontal" method="POST">
			<input name="idPS" id="idPS" type="hidden"/>
			<div class="modal-body form">        
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-md-4">Program Studi</label>						
						<div class="col-md-7">
							<input name="prodi" id="prodi" class="form-control" type="text" readonly/>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Reguler (1)</label>
						<input name="edKode1" id="edKode1" type="hidden"/>
						<div class="col-md-2">
							<select class="form-control" name="kode1" id="kode1"><option></option>
<?php for($i=1; $i<=26; $i++) { echo "<option>".chr(64+$i)."</option>"; } ?>	
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Reguler 2</label>
						<input name="edKode2" id="edKode2" type="hidden"/>
						<div class="col-md-2">
							<select class="form-control" name="kode2" id="kode2"><option></option>
<?php for($i=1; $i<=26; $i++) { echo "<option>".chr(64+$i)."</option>"; } ?>	
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Internasional</label>
						<input name="edKode3" id="edKode3" type="hidden"/>
						<div class="col-md-2">
							<select class="form-control" name="kode3" id="kode3"><option></option>
<?php for($i=1; $i<=26; $i++) { echo "<option>".chr(64+$i)."</option>"; } ?>	
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-4">Kerjasama</label>
						<input name="edKode4" id="edKode4" type="hidden"/>
						<div class="col-md-2">
							<select class="form-control" name="kode4" id="kode4"><option></option>
<?php for($i=1; $i<=26; $i++) { echo "<option>".chr(64+$i)."</option>"; } ?>	
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
	<h3>Jalur Program Studi</h3>		
	<table id="tbJalps" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Program Studi</th><th>R(1)</th><th>R2</th><th>Int</th><th>KS</th><th></th></tr></thead>		
	</table>
</div>
<?php $this->load->view('template/footer');	?>