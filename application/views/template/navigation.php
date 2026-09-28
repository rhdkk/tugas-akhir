<?php 
	$nama = $this->session->userdata('nama_thesis'); 
	$hak = $this->session->userdata('hak_thesis');
	$smt = $this->session->userdata('nm_smt_thesis');
?>
<body class="skin-yellow">
<nav class="navbar navbar-fixed-top navbar-inverse">
	<div class="container-fluid">			
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php if ($hak==1 or $hak==2) echo base_url('kelas'); elseif ($hak==3) echo base_url('matkul'); elseif ($hak==4) echo base_url('ajar'); ?>">Fakultas Ekonomi dan Bisnis Universitas Brawijaya</a>
		</div>
<?php if (isset($hak)) { ?>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
<?php if ($hak==1) { ?>
				<li <?php if ($active=="kelas") echo "class='active'"; ?>><a href="<?php echo base_url('kelas');?>"><i class="glyphicon glyphicon-book"></i>&nbsp;&nbsp;Ujian</a></li>						
				<li class="dropdown <?php if ($active=="utama") echo "active"; ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-hdd"></i>&nbsp;&nbsp;Master Data<span class="caret"></span></a>
					<ul class="dropdown-menu dropdown-menu-left">
						<li><a href="<?php echo base_url('prodi');?>"><i class="glyphicon glyphicon-book"></i>&nbsp;&nbsp;Program Studi</a></li>
						<li><a href="<?php echo base_url('user');?>"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;User</a></li>						
					</ul>					
				</li>					
<?php } else {?>
				
<?php } ?>
				<li class="dropdown <?php if ($active=="user") echo "active"; ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i>&nbsp;&nbsp;<?php echo $nama; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="<?php echo base_url('pass');?>"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Ganti Password</a></li>																
						<li><a href="<?php echo base_url('login/logout');?>"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</a></li>																
					</ul>
				</li>
			</ul>
		</div>
<?php } ?>
	</div>
</nav>