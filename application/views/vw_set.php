<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "settings";
$this->load->view('template/header',$var);
$this->load->view('head_set');
$this->load->view('template/navigation',$var);
?>
<body>
<div class="container"> 	
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">Setting Sistem</div>
				<form action="#" id="fSet" class="form-horizontal" method="POST">
				<table class="table table-hover display responsive" cellspacing="0" width="100%" id="dataset" style="margin-bottom:0px">										
				</table>				
				<div class="panel-footer text-center">
					<button type="submit" id="btSave" class="btn btn-default">Save Settings</button>					
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>