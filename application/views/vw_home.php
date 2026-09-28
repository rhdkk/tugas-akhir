<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "home";
$this->load->view('template/header',$var);
//$this->load->view('head_home',$data);
$this->load->view('template/navigation',$var);
?>
<body>
Ini adalah Halaman Home
<?php $this->load->view('template/footer');	?>