<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Ruangan";		
$var['icon'] = "ruang.png";	
$this->load->view('template/header',$var);
$this->load->view('head_jadwal');
?>
<body style="background:linear-gradient(to left top, #510000, #b00) fixed;">
<div class="modal fade" id="modal_detil" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title"></h3>
      </div>
			<div class="modal-body">     
			</div>			
		</div>
	</div>
</div>
<div class="container" style="padding-top:10px"> 
	<div class="panel panel-default">
		<div class="panel-heading" style="font-size:2em">Pemakaian Ruangan</div>
		<div class="panel-body">
			<table id="tbPakai" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
				<thead><tr><th>Tanggal</th><th>Hari</th><th>Waktu</th><th>Ruangan</th><th>Peserta</th><th>Acara</th><th></th></tr></thead>
			</table>
		</div>
	</div>		
</div>
<?php $this->load->view('template/footer');	?>