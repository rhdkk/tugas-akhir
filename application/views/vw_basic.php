<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "SI - Jadwal";	
$var['icon'] = "jadwal.png";		
$var['active'] = "kelas";
$this->load->view('template/header',$var);
$data['ps'] = $akses[0]['id_ps'];
$data['psj'] = $akses[0]['id_ps']."-".$jalur[0]['id_jalur'];
$this->load->view('head_basic',$data);
$this->load->view('template/navigation',$var);
$hak = $this->session->userdata('hak_thesis');	
?>

<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:0px">
			<h1>Beranda <label id="lb-stat" class="text-primary"><?php echo "" ?></label></h1>
			<h4 style="margin-top:0px; padding-left:3px">Sub Title</h4>
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
				<input name="ops" id="ops" type="hidden"/>									
				<input name="idHapus" id="idHapus" type="hidden"/>									
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
  
</div>

<div class="container"> 
	<div class="row">
		<div class="col-md-6">
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 1</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 2</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 3</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 4</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 5</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 6</h3>
			</div>
		</div>
		<div class="col-md-6">
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 7</h3>
			</div>
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 8</h3>
			</div>
		</div>
		<div class="col-md-12">
			<div class="well bg-white">
				<h3 class="h3 text-info">Well 9</h3>
			</div>
		</div>
	</div>
</div>
<?php $this->load->view('template/footer');	?>