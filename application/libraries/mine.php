<?php
class mine
{
	public function dateStr($dt,$kind)
	{
		$bln = array(1 => "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		$tTgl = explode("-",$dt);
		$strTgl = (int)$tTgl[2]." ".$bln[(int)$tTgl[1]]." ".$tTgl[0];		
		
		if ($kind==1)	return $strTgl;
		else if ($kind==2)	
		{
			$hr = array(0 => "Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
			$h = date("w", strtotime($dt));
			$strHari = $hr[$h].", ".$strTgl;
			return $strHari;
		}
	}
	
	public function PSstr($jenjang)
	{
		if ($jenjang=="S1") return "Sarjana";			
		elseif ($jenjang=="S2") return "Magister";			
		elseif ($jenjang=="XP") return "Profesi";			
		elseif ($jenjang=="S3") return "Doktor";	
	}
	
	public function strDosen($nama,$gelar1,$gelar2)
	{
		
	}
}
?>