<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "user";
$this->load->view('template/header',$var);
$this->load->view('head_pass');
$this->load->view('template/navigation',$var);
?>
<body>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
	<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
		<h2>Password<label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
		<h5 style="padding-left:3px">Ubah Password Pengguna Sistem</h5>			
	</div>
</div>
		
<div class="container"> 
	<div class="row">
		<div class="col-md-12">
			<div class="well bg-white">
				<div class="panel panel-default">
					<div class="panel-heading">Form Password</div>
					<form action="#" id="fPass" class="form-horizontal" method="POST">
						<div class="modal-body form">        
						<div class="form-body">
							<div class="form-group">
								<label class="control-label col-md-5">Password Lama</label>
								<div class="col-md-7">								
									<input name="pass0" id="pass0" class="form-control" type="password"/>
									<span class="help-block hide" id="errPass">Password Salah</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5">Password Baru</label>
								<div class="col-md-7">
									<input name="pass1" id="pass1" class="form-control" type="password"/>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-5">Confirm Password Baru</label>
								<div class="col-md-7">
									<input name="pass2" id="pass2" class="form-control" type="password"/>
								</div>
							</div>					
						</div>        
					</div>
					<div class="panel-footer text-center">
						<button type="submit" id="btSave" class="btn btn-primary">Ubah Password</button>					
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>