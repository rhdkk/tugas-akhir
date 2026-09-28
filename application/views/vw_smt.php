<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "settings";
$this->load->view('template/header',$var);
$this->load->view('head_smt');
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
        <h3 class="modal-title">Aktif</h3>
      </div>			
      <div class="modal-body"> 
				<p class="confirm-msg">tes</p>  												
			</div>
			<div class="modal-footer">
				<form action="#" id="fAktif" class="form-horizontal" method="POST">
					<input type="hidden" name="idOn" id="idOn" class="form-control"/>	
					<button type="submit" id="btAktif" class="btn btn-primary">Ya</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
				</form>
				<form action="#" id="fSmt" class="form-horizontal" method="POST">
					<input type="hidden" name="idNext" id="idNext" class="form-control"/>	
					<button type="submit" id="btNext" class="btn btn-primary">Yes</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
				</form>
			</div>
		</div>
	</div>
</div>
		
<div class="container"> 
	<h3>Semester</h3>	
	<button type="button" class="btn btn-primary btn-next" style="margin-bottom:15px"><i class="glyphicon glyphicon-forward"></i> Next Semester</button>
	<table id="tbSmt" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Aktif</th><th>Semester</th><th>Tahun Akademik</th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>