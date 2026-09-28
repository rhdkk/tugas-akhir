<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "report";
$this->load->view('template/header',$var);
$this->load->view('head_ajar');
$this->load->view('template/navigation',$var);
?>
<body>
<div class="modal fade" id="modal_form" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>        
				<h4 class="modal-title">Alert</h4>
      </div>
			<div class="modal-body">        
				<form action="#" id="fDet" class="form-horizontal" method="POST">
					<input name="idD" id="idD" type="hidden"/>				
				</form>
				<table id="tbDet" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
					<thead><tr><th></th><th>Hari</th><th>Waktu</th><th>Kelas MK</th><th>Program Studi</th><th>sks</th></tr></thead>				
				</table>
			</div>			
		</div>
	</div>
</div>		
<div class="container"> 
	<h3>Beban Mengajar Dosen</h3>	
	<table id="tbAjar" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Nama Dosen</th><th>Jabatan Fungsional</th><th>Homebase</th><th>Beban Ajar (sks)</th><th>S1 (sks)</th><th>S2 (sks)</th><th>S3 (sks)</th><th>Profesi (sks)</th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>