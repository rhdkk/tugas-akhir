<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "report";
$this->load->view('template/header',$var);
$this->load->view('head_lengkap');
$this->load->view('template/navigation',$var);
?>
<body>	
<div class="container"> 
	<h3>Jadwal Mengajar Dosen</h3>	
	<table id="tbLengkap" class="table table-striped table-bordered table-hover display responsive" cellspacing="0" width="100%">
		<thead><tr><th>Nama Dosen</th><th>Homebase</th><th>Program Studi</th><th>Mata Kuliah</th><th>Kls</th><th>Hari</th><th>Jam</th><th>Ruang</th></tr></thead>
		<tfoot><tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tfoot>
	</table>
</div>
<?php $this->load->view('template/footer');	?>