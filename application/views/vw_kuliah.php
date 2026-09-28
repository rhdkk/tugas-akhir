<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "report";
$this->load->view('template/header',$var);
$this->load->view('head_kuliah');
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
<div class="container"> 
	<h3>Rekapitulasi Kelas</h3>	
	<table id="tbKuliah" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Hari</th><th>Sesi</th><th>Jumlah</th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>