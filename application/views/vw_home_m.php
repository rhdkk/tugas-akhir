<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$var['title'] = "Tugas Akhir - FEB UB";	
$var['icon'] = "thesis.png";		
$var['active'] = "kelas";
$this->load->view('template/header',$var);
$data['ps'] = $akses[0]['id_ps'];
$data['jenjang'] = $akses[0]['jenjang'];
$nPemb = $akses[0]['p1'];
$nPeng = $akses[0]['p2'];
$jenjang = $akses[0]['jenjang'];
$tipeReg = $akses[0]['tipe_reg'];
$namaPG = $mhs->nm_tampil;
$totProg = (7*($nUjian->nUji));
if ($tipeReg==1) $totProg += 4; else $totProg += 3;
$urut = $mhs->urut_progress;
$stage = $mhs->stage;
$urutUji = $mhs->urut_tuji;

if ($stage==1) $pos = $urut;
elseif ($stage==2 or $stage==3) 
{
	if ($tipeReg==1) $pos = ($urut*$urutUji)+(3*($urutUji-1));
	elseif ($tipeReg==2) $pos = ($urut*$urutUji)+(2*($urutUji-1));
}
elseif ($stage==4) $pos = $totProg;

$judul = $mhs->judul_ta;
$no_sk = $mhs->no_sk;
$progr = $mhs->nm_progress;
$data['nim'] = $mhs->nim;
$data['urut'] = $urut;
$data['stage'] = $stage;
$data['nPemb'] = $nPemb;
$data['nPeng'] = $nPeng;
$bln = array(1 => "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
$hr = array(1 => "Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
$this->load->view('head_home_m',$data);
$this->load->view('template/navigation',$var);
?>
<body>
<div class="page-header" id="head-aktif" style="margin:40px 0px -20px 0px">
		<div class="container" id="body-aktif" style="margin-top:15px; margin-bottom:15px">
			<h2>Beranda <label id="lb-stat" class="text-primary"><?php echo "" ?></label></h2>
			<h5 style="padding-left:3px">Mahasiswa</h5>			
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
<div class="modal fade" id="modal_info" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Informasi</h3>
      </div>
			<div class="modal-body alert-info">       
				<p class="info-msg">tes</p>  								
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_formRev" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleRev">Form Input Revisi</h3>
      </div>
			<form action="#" id="fRev" class="form-horizontal" method="POST">
				<input name="idDU" id="idDU" type="hidden"/>				
				<input name="tpRev" id="tpRev" type="hidden"/>				
				<input name="nim" id="nim" type="hidden"/>				
				<input name="nmUji" id="nmUji" type="hidden" value="<?php echo $mhs->nm_tuji ?>"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12 form-comp">
								<label for="tglRev" class="control-label">Tanggal</label>
								<input class="form-control" name="tglRev" id="tglRev" type='text' readonly/>
							</div>
							<div class="col-md-12 form-comp" id="ctDosen">
								<label for="dosen" class="control-label">Dosen</label>
								<select class="form-control ext-select dosen" name="dosenRev" id="dosenRev"><option value="" style="width:100%"> - Pilih Dosen - </option></select>								
							</div>
						</div>
					</div><br/>
					<div class="form-body well skin-yellow" id="ketRev">
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12">
									<h5 style="margin-bottom:3px">
										<span id="lbTUji">Tahap Uji</span></h5>	
									<h4 style="margin-bottom:15px">
										<span id="lbTglRev" class="label label-default" style="font-weight:normal">ini adalah tanggal</span></h4>	
									<b><span class="text-primary">Revisi/Saran</span></b>									
									<h5><span id="lbKetRev">ini adalah keterangan</span></h5>
							</div>
						</div>						
					</div>					
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btRev" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>
<!--<div class="modal fade" id="modal_formBerkas" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleBimb">Revisi Berkas Pengajuan Ujian</h3>
      </div>
			<form action="#" id="fBimb" class="form-horizontal" method="POST">
				<input name="nim" id="nim" type="hidden"/>				
				<input name="nmUji" id="nmUji" type="hidden" value="<?php echo $mhs->nm_tuji ?>"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12 form-comp">
								<label for="tglBimb" class="control-label">Tanggal</label>
								<input class="form-control" name="tglBimb" id="tglBimb" type='text' readonly/>
							</div>
							<div class="col-md-12 form-comp">
								<label for="dosen" class="control-label">Dosen</label>
								<select class="form-control ext-select dosen" name="dosen" id="dosen"><option value="" style="width:100%"> - Pilih Dosen - </option></select>								
							</div>
							<div class="col-md-12 form-comp">
								<input name="jnsBimb" id="jnsBimb" type="hidden">
								<label for="ketBimb" class="control-label">Keterangan Bimbingan</label>
								<textarea name="ketBimb" id="ketBimb" class="form-control" style="resize:none" rows="5"/></textarea><br/>
								<button type="button" id="btUjian" class="btn btn-primary btn-xs" style="margin-top:0px">Ajukan Peromohonan Persetujuan Ujian</button>
							</div>
						</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btBimb" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>-->
<div class="modal fade" id="modal_formBimb" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="titleBimb">Form Input Bimbingan</h3>
      </div>
			<form action="#" id="fBimb" class="form-horizontal" method="POST">
				<input name="nim" id="nim" type="hidden"/>				
				<input name="nmUji" id="nmUji" type="hidden" value="<?php echo $mhs->nm_tuji ?>"/>				
				<div class="modal-body form">        
					<div class="form-body">
						<div class="form-group row" style="margin-bottom:0px">
							<div class="col-md-12 form-comp">
								<label for="tglBimb" class="control-label">Tanggal</label>
								<input class="form-control" name="tglBimb" id="tglBimb" type='text' readonly/>
							</div>
							<div class="col-md-12 form-comp">
								<label for="dosenBimb" class="control-label">Dosen</label>
								<select class="form-control ext-select dosen" name="dosenBimb" id="dosenBimb"><option value="" style="width:100%"> - Pilih Dosen - </option></select>								
							</div>
							<div class="col-md-12 form-comp">
								<input name="jnsBimb" id="jnsBimb" type="hidden">
								<label for="ketBimb" class="control-label">Keterangan Bimbingan</label>
								<textarea name="ketBimb" id="ketBimb" class="form-control" style="resize:none" rows="5"/></textarea><br/>
								<button type="button" id="btUjian" class="btn btn-primary btn-xs" style="margin-top:0px">Ajukan Peromohonan Persetujuan Ujian</button>
								<button type="button" id="btJudul" class="btn btn-warning btn-xs" style="margin-top:0px">Ajukan Perubahan Judul</button>
								<textarea name="judulNew" id="judulNew" class="form-control" placeholder="Masukkan Judul Baru" style="resize:none; margin-top:5px" rows="5"/></textarea><br/>
							</div>
						</div>
					</div>        
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" id="btBimb" class="btn btn-primary">Simpan</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
			</div>			
		</div>
	</div>
</div>

<div class="container"> 
	<div class="row">
		<div class="col-md-6">
			<div class="well bg-white">
				<h4><i class="glyphicon glyphicon-th-list"></i> &nbsp;<b>Proses</b> <?php echo $namaPG." [".$pos."/".$totProg."]"; ?></h4>
			</div>
<?php  /* === 01 INPUT DATA TUGAS AKHIR === */ 
	if ($urut==1) { ?>
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4><i class="fa fa-edit"></i>&nbsp;Input data Tugas Akhir </h4>
				</div>
				<form action="#" id="fThesis" method="POST">
				<input name="nPemb" id="nPemb" type="hidden" value="<?php echo $nPemb; ?>"/>
				<div class="panel-body form">
					<div class="form-body">
						<div class="form-group">
							<label for="judul" class="control-label">Rancangan Judul Tugas Akhir</label>						
							<textarea name="judul" id="judul" class="form-control" style="resize:none" rows="5"/></textarea>						
						</div>						
<?php 
	if ($tipeReg=='1') {
		for ($i=1; $i<=$nPemb; $i++) 
		{
			echo "<div class='form-group'>";
			if ($jenjang=="S3") 
			{
				if ($i==1) echo "<label for='pemb".$i."' class='control-label'>Promotor</label>";
				else echo "<label for='pemb".$i."' class='control-label'>Co-Promotor ".($i-1)."</label>";
			}
			else echo "<label for='pemb".$i."' class='control-label'>Pembimbing ".$i."</label>";
			echo "<select class='form-control ext-select dosen' name='pemb".$i."' id='pemb".$i."'><option value=''> - Pilih Dosen - </option>";
			foreach($dosen as $d) 
			{ 
				$str = "<option value='".$d['id_dosen']."'>";
				if (trim($d['gelar1'])!="") $str .= $d['gelar1']." ";
				$str .= $d['nm_dosen'];
				if (trim($d['gelar2'])!="") $str .= ", ".$d['gelar2'];
				$str .= "</option>";
				echo $str; 
			} 			
			echo "</select></div>";
		}
	}
?>
					</div>
					<div class="alert alert-danger error-form hide" id="errThesis">
						<b>Simpan Gagal</b> 
					</div>
					<hr/>	
					<button type="button" id="btThesis" class="btn btn-primary pull-right">Simpan</button>
				</div>
				</form>
			</div>
<?php  /* === 02 BIMBINGAN === */ 
	}	elseif (($tipeReg==1 and $urut==4) or ($tipeReg==2 and $urut==3)) { ?>
			<div class="well bg-white">
				<h3 class="h4">Data Bimbingan <button type="button" id="addBimb" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Tambah</button></h4><hr/>
				<table id="tbBimbM" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
					<thead><tr><th>Tanggal</th><th>Dosen dan Pembahasan</th><th>Stat</th></tr></thead>
					<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
				</table>
			</div>
<?php	 /* === 03 PENGAJUAN UJIAN === */ 
	}	elseif (($tipeReg==1 and $urut==5) or ($tipeReg==2 and $urut==4)) {
?>
			<div class="panel panel-primary">
				<div class="panel-heading clearfix">
					<h4><i class="fa fa-edit"></i>&nbsp;Pengajuan Ujian </h4>
				</div>
				<form action="#" id="fAjuan" method="POST" enctype="multipart/form-data">
				<input name="nS" id="nS" type="hidden" value="<?php echo count($syarat); ?>"/>
				<div class="panel-body form">
					<h4><?php echo $mhs->nm_tuji ?></h4><hr/>
					<div class="form-body">
<?php 
	$i=0; $nS=count($syarat);
	foreach($syarat as $s) 
	{ 
		$i++;
		$str = "<div class='form-group'><label for='berkas".$i."' class='control-label'>".$s['nm_berkas']."</label>"; 
		$str .= "<input type='hidden' name='idS".$i."' id='idS".$i."' value='".$s['id_syarat']."'>";
		$str .= "<input type='hidden' name='idB".$i."' id='idB".$i."' value='".$s['id_berkas']."'>";
		$str .= "<input type='hidden' name='nskB".$i."' id='nskB".$i."' value='".$s['naskah']."'>";		
		if ($s['filename']!='x') 
		{
			$str .= "<input type='hidden' name='fnB".$i."' id='fnB".$i."' value='".$s['filename']."'>";
			$str .= "<input type='hidden' name='idBM".$i."' id='idBM".$i."' value='".$s['id_berma']."'>";			
		}
		$str .= "<input type='file' class='form-control berkas' name='berkas".$i."' id='berkas".$i."'>";
		if ($s['naskah']==1) $str .= "<hr/>";
		$str .= "</div>";
		echo $str; 
	}
?>
					</div>
					<div class="alert alert-danger error-form hide" id="errAjuan">
						<b>Simpan Gagal</b> 
					</div>
					<hr/>	
					<button type="button" id="btAjuan" class="btn btn-primary pull-right">Ajukan Ujian</button>
				</div>
				</form>
			</div>
<?php  /* === 04 VERIFIKASI PENGAJUAN UJIAN === */ 
	} elseif (($tipeReg==1 and $urut==6) or ($tipeReg==2 and $urut==5)) { 
?>
			<div class="well bg-white">
				<h3 class="h4">Berkas Pengajuan Ujian</h4><hr/>
				<table id="tbBerkasA" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
					<thead><tr><th>Berkas</th><th>Stat</th></tr></thead>
				</table>
			</div>
			
<?php  /* === 05 REVISI BERKAS UJIAN === */ 
	} elseif (($tipeReg==1 and $urut==7) or ($tipeReg==2 and $urut==6)) { 
?>
			<div class="panel panel-danger">
				<div class="panel-heading clearfix">
					<h3><i class="fa fa-edit"></i>&nbsp;Revisi Berkas Pengajuan Ujian</h3>
				</div>		
				<form action="#" id="fRevAjuan" method="POST" enctype="multipart/form-data">				
				<div class="panel-body form">
					<h4><?php echo $mhs->nm_tuji ?></h4><hr/>
					<div class="form-body">
						<input name="nBRA" id="nBRA" type="hidden"/>
						<table id="tbBerkasRA" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
							<thead><tr><th>Berkas</th><th>Stat</th></tr></thead>
						</table>
						<div class="alert alert-danger error-form hide" id="errRevAjuan">
							<b>Simpan Gagal</b> 
						</div>
						<hr/>	
						<button type="button" id="btRevAjuan" class="btn btn-primary pull-right">Upload Berkas Revisi</button>
					</div>
				</div>
				</form>
			</div>

<?php /* === 06 PENJADWALAN UJIAN === */ 
	} elseif (($tipeReg==1 and ($urut==8 or $urut==9)) or ($tipeReg==2 and ($urut==7 or $urut==8))  ) {	
		if (!empty($ujian->id_uji)) 
		{
			$tTgl = explode("-",$ujian->tgl_uji);
			$jam1 = $ujian->jam1_uji;
			$jam2 = $ujian->jam2_uji;
			$date = 
			$day = date_format(date_create($ujian->tgl_uji),"w");
			$strTgl = $hr[$day].", ".(int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];
			$strJam = $jam1." - ".$jam2." WIB";
			$strRuang = $ujian->nm_ruang;
		} 
		else 
		{
			$strTgl = "-";
			$strJam = "-";
			$strRuang = "-";
		}
?>
			<div class="well bg-white">
				<h3 class="h4">Jadwal Pelaksanaan Ujian</h3><hr/>
				<b>Tahap Ujian</b><br/><?php echo $mhs->nm_tuji ?><hr/>
				<div style='margin-top:8px'>
					<b>Hari </b><?php echo $strTgl ?>
				</div>
				<div style='margin-top:8px'>
					<b>Pukul </b><?php echo $strJam ?>
				</div>
				<div style='margin-top:8px'>
					<b>Ruang </b><?php echo $strRuang ?>
				</div>
			</div>			
<?php /* === 07 REVISI UJIAN === */ 
		} elseif (($tipeReg==1 and $urut==10) or ($tipeReg==2 and $urut==9)) {	?>
			<div class="well bg-white">
				<h3 class="h4">Data Revisi Ujian <button type="button" id="addRev" class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Tambah</button></h3><hr/>
				<table id="tbRevM" class="table table-striped table-hover display responsive" cellspacing="0" width="100%">
					<thead><tr><th>Tanggal</th><th>Dosen</th><th>Stat</th></tr></thead>
					<tfoot><tr><td></td><td></td><td></td></tr></tfoot>
				</table>
			</div>		
<?php } if ($urut>1) { ?>
			<div class="well bg-white">
				<h3 class="h4">Riwayat Proses</h4><hr/>
				<ul class="custom-list">
<?php	
	$stg = "x";
	foreach($history as $h) 
	{
		if ($h['nm_tampil']!=$stg)
		{
			$tDt = explode(" ",$h['dt_progression']);
			$tTgl = explode("-",$tDt[0]);
			$strTgl = (int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];
			echo "<li><span class='list-icon text-success'><i class='glyphicon glyphicon-ok-sign'></i></span>
						<div class='list-content'>
							<div style='margin-bottom:-2px'>".$h['nm_tampil']."</div>
							<small class='text-muted'>".$strTgl."</small>
						</div></li>";
			$stg=$h['nm_tampil'];
		}
	}
?>
			</ul>
			</div>
<?php } ?>			
		</div>
		<div class="col-md-6">
			<div class="well bg-white">
				<h4 class="h4">Data Tugas Akhir</h4><hr/>
<?php 
	if ($urut==1) echo "<span class='text-muted'>Belum ada data</span>"; 
	else 
	{ 
		echo "<b>Judul</b><br/>".$judul."<hr/>";
		
		$i1=0; $i2=0; $aryPemb=array(); $aryPeng=array(); $arySt=array();
		foreach($tim as $tm) /* Masih awal */ 
		{ 			
			$nm="";
			if (trim($tm['gelar1'])!="") $nm .= $tm['gelar1']." ";
			$nm .= $tm['nm_dosen'];
			if (trim($tm['gelar2'])!="") $nm .= ", ".$tm['gelar2'];
			
			if ($tm['jab_dosen']==1) { $i1++; $aryPemb[$i1]=$nm; }
			else if ($tm['jab_dosen']==2) 
			{
				$i2++; $aryPeng[$i2]=$nm; $arySt[$i2]=$tm['stat_dospem'];
			}
		}
		
		if (count($aryPeng)==0) echo "<span class='label label-info' style='padding:0.1em 0.5em 0em'>USULAN</span>";
		
		for ($i=1; $i<=$nPemb; $i++) 
		{
			echo "<div style='margin-top:8px'>";
			if ($jenjang=="S3") 			
			{
				if ($i==1) $str="Promotor";
				else $str="Co-Promotor ".($i-1);
			} 
			else 
			{
				$str = "Pembimbing";
				if ($nPemb>1) $str.=" ".$i;
			}
			
			if (count($aryPemb)>0)
			{
				echo "<b>".$str." 
					<span id='lbDsn1".$i."1' class='label label-warning' style='padding:0.1em 0.5em 0em; font-weight:normal' title=''></span> 
					<span id='lbDsn1".$i."2' class='label label-default' style='padding:0.1em 0.5em 0em; font-weight:normal' title=''></span>
					<span id='lbDsn1".$i."3' class='label label-danger' style='padding:0.1em 0.5em 0em; font-weight:normal' title=''></span>
					</b><br/><h5>".$aryPemb[$i]."</h5></b>";
			}
			else echo "<b id='lbDsn1".$i."'>".$str."</b><br/><h5>-</h5>";
			echo "</div>";
		}
		echo "<hr/>";
		
		for ($i=1; $i<=$nPeng; $i++)
		{
			echo "<div style='margin-top:8px'>";
			if (count($aryPeng)>0) 
			{
				if ($jenjang=="S1")
				{
					if (count($tujian)>0 or (isset($arySt[$i]) and $arySt[$i]!=1)) $strPeng=$aryPeng[$i]; 
					else $strPeng="-";
				}
				else $strPeng=$aryPeng[$i];
				echo "<b>Penguji ".$i."
					<span id='lbDsn2".$i."2' class='label label-default' style='padding:0.1em 0.5em 0em; font-weight:normal' title=''></span>
					<span id='lbDsn2".$i."3' class='label label-danger' style='padding:0.1em 0.5em 0em; font-weight:normal' title=''></span>
					</b><br/><h5>".$strPeng."</h5>";
			}
			else echo "<b id='lbDsn2".$i."'>Penguji ".$i."</b><br/><h5>-</h5>";
			echo "</div>";
		}	
		echo "<hr/><div style='margin-top:8px'>";
		if ($mhs->tgl_sk)
		{
			$tDt = explode(" ",$h['dt_progression']);
			$tTgl = explode("-",$mhs->tgl_sk);
			$strTgl = (int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];
		} 
		else $strTgl = ""; 
		echo "<b>Nomor SK </b>".$mhs->no_sk."<br/><b>Tanggal </b>".$strTgl;
		echo "</div>";
	} 
?>
			</div>
			<div class="well bg-white">
				<h3 class="h4">Riwayat Ujian</h4><hr/>
<?php 
	if (count($tujian)==0) echo "<span class='text-muted'>Belum ada data</span>"; 
	else {
?>
				<ul class="custom-list">
<?php	
	$stg = "x";
	foreach($tujian as $tu) 
	{
		if ($tu['nm_tuji']!=$stg)
		{
			$tDt = explode(" ",$tu['tgl_uji']);
			$tTgl = explode("-",$tDt[0]);
			$strTgl = (int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];
			echo "<li>
							<span class='list-icon text-success'><i class='glyphicon glyphicon-ok-sign'></i></span>							
							<div class='list-content'>
								<div style='margin-bottom:-2px'>".$tu['nm_tuji']."
									<div class='btn-group pull-right' role='group'>
										<a class='btn btn-info btn-xs' href='javascript:void()' title='Berita Acara' onclick='exp_pdf(\"ba\",\"".$tu['id_uji']."\")'><span class='glyphicon glyphicon-edit'></span></a>
										<a class='btn btn-warning btn-xs' href='javascript:void()' title='Nilau Ujian' onclick='exp_pdf(\"nu\",\"".$tu['id_uji']."\")'><span class='glyphicon glyphicon-stats'></span></a>
										<a class='btn btn-danger btn-xs' href='javascript:void()' title='Revisi Ujian' onclick='exp_pdf(\"ru\",\"".$tu['id_uji']."\")'><span class='glyphicon glyphicon-tasks'></span></a>
									</div>
								</div>
								<small class='text-muted'>".$strTgl.", ".$tu['jam1_uji']." - ".$tu['jam2_uji']." WIB</small>								
							</div>							
						</li>";
			$stg=$tu['nm_tuji'];
		}
	}
?>
			</ul>
<?php } ?>
			</div>
		</div>		
	</div>
</div>

<?php $this->load->view('template/footer');	?>

