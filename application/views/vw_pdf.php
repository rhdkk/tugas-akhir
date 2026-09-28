<?php
	class PDF extends Mysql_table
	{
		function header()
		{
			$this->Image(base_url()."/assets/img/Logo.jpg",10,9,34);
			$this->SetFont('Arial','',16); 
			$this->setX(41); $this->Cell(0,16,"KEMENTERIAN PENDIDIKAN TINGGI, ",0,0,'L'); $this->Ln(9);
			$this->setX(41); $this->Cell(0,9,"SAINS, DAN TEKNOLOGI",0,0,'L'); $this->Ln(6);
			$this->SetFont('Arial','B',18); 
			$this->SetTextColor(43,95,133);
			$this->setX(41); $this->Cell(0,10,"UNIVERSITAS BRAWIJAYA",0,0,'L'); $this->Ln(5);
			$this->SetTextColor(0,0,0);
			$this->SetFont('Arial','B',10); $this->setY(10);
			$this->setX(150); $this->Cell(0,10,"Fakultas Ekonomi dan Bisnis",0,0,'L'); $this->Ln(4);
			$this->SetFont('Arial','',10);
			$this->setX(150); $this->Cell(0,10,"Jalan Veteran No. 12-16,",0,0,'L'); $this->Ln(4);
			$this->setX(150); $this->Cell(0,10,"Malang 65145, Indonesia",0,0,'L'); $this->Ln(4);
			$this->setX(150); $this->Cell(0,10,"Telp. +62341 553834",0,0,'L'); $this->Ln(4);
			$this->setX(150); $this->Cell(0,10,"e-mail: feb@ub.ac.id",0,0,'L'); $this->Ln(4);
			$this->setX(150); $this->Cell(0,10,"feb.ub.ac.id",0,0,'L'); $this->Ln(4);
		}
		//Page footer
		/*function Footer()
		{
			$this->SetY(-15);
			$this->SetFont('Arial','I',8);
			$this->Cell(200,5,'Isikan Footernya disini','T',0,'R');	
		}*/
		function DCell($width,$h,$hmx,$lnLim,$str,$align,$valign,$border=TRUE)
		{
			$n = count($str); 
			$y1 = $this->getY();
			$x1 = $this->getX();
			
			if (!empty($lnLim) and $y1>=$lnLim) 
			{
				$this->AddPage(
					( $this->w > $this->h ) ? 'L' : 'P'
				);
				$y1 = $this->getY();
				$this->setX(15);
			}		
			
			if (!empty($hmx)) $hMax=$hmx; else $hMax=$h;
				for ($i=0; $i<$n; $i++)
				{
					$tH[$i] = $this->GetMultiCellHeight($width[$i],$h,$str[$i]); 
					if (empty($hmx) and $tH[$i]>=$hMax) $hMax=$tH[$i]; 
				}
			$x = $x1;		
			
			for ($i=0; $i<$n; $i++)
			{
				$x += $width[$i];
				if ($tH[$i]<$hMax && $valign[$i]=='M') $this->drawTextBox($str[$i],$width[$i],$hMax,$h,$align[$i],$valign[$i],$border);							
				else $this->drawTextBox($str[$i],$width[$i],$hMax,$h,$align[$i],'',$border); 
				if ($i<($n-1)) $this->setXY($x,$y1); 
				else 
				{
					if (($y1+$hMax)<($this->h-30)) $this->setXY($x1,$y1+$hMax);
					else{
						$this->addPage(($this->w > $this->h) ? 'L' : 'P');
						$this->setX($x1);
					}
				}
			}
		}
		
		function c_DCell($width, $h, $hmx, $lnLim, $str, $align, $valign, $border = TRUE)
    {
			$str = (array) $str;
			$n = count($str);
			$y1 = $this->GetY();
			$x1 = $this->GetX();
			
			if (!empty($lnLim) && $y1 >= $lnLim) 
			{
				$this->AddPage(($this->w > $this->h) ? 'L' : 'P');
				$y1 = $this->GetY();
				$this->SetX(15);
				$x1 = $this->GetX();
			}		
			
			$align = array_pad($align, $n, 'L'); 
			$valign = array_pad($valign, $n, '');
			$width = array_pad($width, $n, 0);
			
			if (!empty($hmx)) $hMax=$hmx; else $hMax=$h;
			for ($i=0; $i<$n; $i++)
			{
				$tH[$i] = $this->GetMultiCellHeight($width[$i],$h,$str[$i]); 
				if (empty($hmx) and $tH[$i]>=$hMax) $hMax=$tH[$i]; 
			}
			$x = $x1;		
			
			for ($i=0; $i<$n; $i++)
			{
				$cellBorder = is_array($border) ? (isset($border[$i]) ? $border[$i] : TRUE) : $border;
				
				if ($tH[$i]<$hMax && $valign[$i]=='M')
					$this->c_drawTextBox($str[$i],$width[$i],$hMax,$h,$align[$i],$valign[$i],$cellBorder);					
				else $this->c_drawTextBox($str[$i],$width[$i],$hMax,$h,$align[$i],'',$cellBorder);				
				
				if ($i < ($n - 1)) 
				{
					$x += $width[$i];
					$this->SetXY($x, $y1);
				} 
				else 
				{
					if (($y1 + $hMax) < ($this->h - 30))
						$this->SetXY($x1, $y1 + $hMax);
					else 
					{
						$this->AddPage(($this->w > $this->h) ? 'L' : 'P');
						$this->SetX($x1);
					}
				}
			}
    }
		
		function c_drawTextBox($txt, $w, $hMax, $h, $align = 'L', $valign = '', $border = 0) 
		{
			$xi = $this->GetX();
			$yi = $this->GetY();
			
			$textrows = $this->drawRows($w, $h, $txt, 0, $align, 0, 0, 0);
			$maxrows = floor($hMax / $this->FontSize); 
			$rows = min($textrows, $maxrows);

			$dy = 0;
			if (strtoupper($valign) == 'M')
				$dy = ($hMax - $rows * $this->FontSize) / 2;
			if (strtoupper($valign) == 'B')
				$dy = $hMax - $rows * $this->FontSize;
			
			$this->SetY($yi + $dy);
			$this->SetX($xi);
			$this->drawRows($w, $h, $txt, 0, $align, false, $rows, 1);

			if ($border) 
			{
				if (is_string($border)) 
				{
					if (strpos(strtoupper($border), 'T') !== false) 
						$this->Line($xi, $yi, $xi + $w, $yi);
					if (strpos(strtoupper($border), 'B') !== false)
						$this->Line($xi, $yi + $hMax, $xi + $w, $yi + $hMax);
					if (strpos(strtoupper($border), 'L') !== false)
						$this->Line($xi, $yi, $xi, $yi + $hMax);
					if (strpos(strtoupper($border), 'R') !== false)
						$this->Line($xi + $w, $yi, $xi + $w, $yi + $hMax);
				} 
				else if ($border === true)
					$this->Rect($xi, $yi, $w, $hMax);				
			}

			$this->SetXY($xi, $yi + $hMax);
		}
	}
	
	$tanggal=date("j"); $bln=(int)date("m"); $thn=date("Y"); 
	$bulan[1] = "Januari";		$bulan[5] = "Mei";		 	$bulan[9] = "September";				
	$bulan[2] = "Februari"; 	$bulan[6] = "Juni"; 	 	$bulan[10] = "Oktober";
	$bulan[3] = "Maret";    	$bulan[7] = "Juli";   	$bulan[11] = "November";
	$bulan[4] = "April";    	$bulan[8] = "Agustus"; 	$bulan[12] = "Desember";
	$hari[1] = "Senin";				$hari[2] = "Selasa";		$hari[3] = "Rabu";		$hari[4] = "Kamis";
	$hari[5] = "Jumat";				$hari[6] = "Sabtu";		$hari[7] = "Minggu";		

	/* portrait_width=200; writeable=185;  
	landscape_width=285; writeable=270; */

	$left=15; $c1=30; $c2=75; $c3=10; $c4=10; $c5=20; $c6=12; $c7=10; $c8=18; $c11=35; $c21=55; $c31=90;
	$LineLimL=179; //Landscpae line limit 
	$hField=7; $xField=$left+$c1+$c2; $h1=5; $h2=8;

	if ($tipe=="ba")
	{
		$pdf = new PDF('P','mm','Legal');
		$pdf->AliasNbPages();
		/* FIRST */
		$pdf->AddPage('P');
		$pdf->SetFont('helvetica','B',14); $pdf->Ln(15);
		$pdf->Cell(0,10,"BERITA ACARA UJIAN",0,0,'C'); $pdf->Ln(5);
		$pdf->SetFont('helvetica','B',12);
		$pdf->Cell(0,10,strToUpper($list[0]['nm_tuji']),0,0,'C');
		
		$pdf->SetFont('helvetica','B',10);
		$pdf->setX($left+165); $pdf->Cell(20,10,"SPEP - 4",1,0,'C'); $pdf->Ln(20);
		
		$tgl = explode("-",$list[0]['tgl_uji']);
		$strDate = ($tgl[2]*1)." ".$bulan[$tgl[1]*1]." ".$tgl[0];
		$dayNum = date('w', strtotime($list[0]['tgl_uji']));
		$pdf->SetFont('helvetica','',11);		
		$tx	= "Pada hari ini, ".$hari[$dayNum*1]." tanggal ".$strDate.", telah dilaksanakan ".$list[0]['nm_tuji']." bagi mahasiswa: ";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(5);
		
		$ta="";
		if ($list[0]['jenjang']=="S1") $ta="Skripsi";
		elseif ($list[0]['jenjang']=="S2" OR $list[0]['jenjang']=="XP") $ta="Tesis";
		elseif ($list[0]['jenjang']=="S3") $ta="Disertasi";
		$pdf->SetFont('helvetica','',11);		
		$width = array(35,5,145); 
		$align = array("L","C","L");
		$valign = array("T","T","T");
		$str = array("Nama",":",$list[0]['nm_mhs']);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("NIM",":",$list[0]['nim']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Departemen",":",$list[0]['nm_jur']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Konsentrasi",":","[konsentrasi]");
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Ujian ke",":",$list[0]['jml_uji']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Judul ".$ta,":",$list[0]['judul_ta']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(5);
		
		$pdf->SetFont('helvetica','',11);
		$tx	= "Kepada mahasiswa yang bersangkutan, setelah memperhatikan draft proposal, presentasi dan diskusi diberikan nilai __".$list[0]['nilai_uji']."__, dengan demikian yang bersangkutan dinyatakan [LAYAK/TIDAK LAYAK] untuk melanjutkan penelitian.";
		$pdf->setX($left);
		$pdf->MultiCell(185,6,$tx); $pdf->Ln(10);
		
		$pdf->setX($left+95); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(6);
		$pdf->setX($left); $pdf->Cell(0,10,"Dosen Pembahas,",0,0,'L');
		$pdf->setX($left+95); $pdf->Cell(0,10,"Dosen Pembimbing,",0,0,'L'); $pdf->Ln(30);
		$y=$pdf->getY(); $pdf->setX($left); $pdf->MultiCell(90,5,$list[1]['nm_dosen']); 
		$pdf->setX($left); $pdf->Cell(0,5,"[jenis ID Pembahas]. ".$list[1]['no_dosen']); 
		$pdf->setY($y); $pdf->setX($left+95); $pdf->MultiCell(90,5,$list[0]['nm_dosen']); $pdf->Ln(1);		
		$pdf->setX($left+95); $pdf->Cell(0,5,"[jenis ID Pembimbing]. ".$list[0]['no_dosen']); $pdf->Ln(15);
		/*$pdf->setX($left+50); $pdf->Cell(0,10,"Mengetahui,",0,0,'L'); $pdf->Ln(6);
		$pdf->setX($left+50); $pdf->Cell(0,10,"Sekretaris Departemen ".$list[0]['nm_jur'].",",0,0,'L'); $pdf->Ln(30);
		$pdf->setX($left+50); $pdf->MultiCell(185,0,"[SekDep]"); $pdf->Ln(1);
		$pdf->setX($left+50); $pdf->Cell(0,10,"[jenis ID SekDep]. [No ID SekDep]");*/

		/* SECOND */
		$pdf->AddPage('P');
		$pdf->SetFont('helvetica','B',14); $pdf->Ln(15);
		$pdf->Cell(0,10,"SARAN PERBAIKAN",0,0,'C'); $pdf->Ln(5);
		$pdf->SetFont('helvetica','B',12);
		$pdf->Cell(0,10,$list[0]['nm_tuji'],0,0,'C'); 
		
		$pdf->SetFont('helvetica','B',10);
		$pdf->setX($left+165); $pdf->Cell(20,10,"SPEP - 5",1,0,'C'); $pdf->Ln(20);
		
		$pdf->SetFont('helvetica','',11);		
		$width = array(35,5,145); 
		$align = array("L","C","L");
		$valign = array("T","T","T");
		$str = array("Hari, Tanggal",":",$hari[$dayNum*1].", ".$strDate);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Nama",":",$list[0]['nm_mhs']);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("NIM",":",$list[0]['nim']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Departemen",":",$list[0]['nm_jur']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Konsentrasi",":","[konsentrasi]");
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Ujian ke",":",$list[0]['jml_uji']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Judul ".$ta,":",$list[0]['judul_ta']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(5);
		
		$pdf->SetFont('helvetica','B',11); 
		$pdf->setX($left); $pdf->Cell(0,10,"SARAN PERBAIKAN",0,0,'L'); $pdf->Ln(12);
		$pdf->SetFont('helvetica','',11);		
		$width = array(10,175); 
		$align = array("R","L");
		$valign = array("T","T");
		$i=0;
		foreach ($list as $dt)
		{
			$aryRev = explode("|-|",$dt['revisi_uji']);				
			if (count($aryRev)>1)
			{
				for ($j=0; $j<count($aryRev); $j++)
				{
					$i++;
					$str = array($i.".",$aryRev[$j]);						
					$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
				}
			}
			else 
			{ 
				$i++;
				$str = array($i.".",$dt['revisi_uji']); 
				$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			}			
		}		
		$pdf->Ln(5);
		$pdf->setX($left+95); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(30);
		$pdf->setX($left+95); $pdf->MultiCell(90,5,$list[0]['nm_dosen']); $pdf->Ln(1);
		$pdf->setX($left+95); $pdf->Cell(0,5,"[jenis ID Pembimbing]. ".$list[0]['no_dosen']); $pdf->Ln(15);
	}
	elseif ($tipe=="nu")
	{
		$pdf = new PDF('P','mm','Legal');
		$pdf->AliasNbPages();	
		/* FIRST */
		$akhir=0; $id=0;
		foreach ($list["nu"] as $nu)
		{
			$id++;
			if ($id==1)
			{ 
				$tgl = explode("-",$nu['tgl_uji']);
				$strDate = ($tgl[2]*1)." ".$bulan[$tgl[1]*1]." ".$tgl[0];
				$dayNum = date('w', strtotime($nu['tgl_uji']));
			}
			
			$pdf->AddPage('P');
			$pdf->SetFont('helvetica','B',14); $pdf->Ln(15);
			$pdf->Cell(0,10,"PENILAIAN ".strToUpper($nu['nm_tuji']),0,0,'C'); $pdf->Ln(15);
			
			$pdf->SetFont('helvetica','',11);		
			$tx	= "Berdasarkan ".$nu['nm_tuji']." yang telah dilakukan oleh mahasiswa: ";
			$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(5);
			
			$ta="";
			if ($nu['jenjang']=="S1") $ta="Skripsi";
			elseif ($nu['jenjang']=="S2" OR $nu['jenjang']=="XP") $ta="Tesis";
			elseif ($nu['jenjang']=="S3") $ta="Disertasi";
			if ($nu['jenjang']=="S1") $jn="Sarjana";
			elseif ($nu['jenjang']=="S2" OR $nu['jenjang']=="XP") $jn="Magister";
			elseif ($nu['jenjang']=="S3") $jn="Doktor";
			
			if ($nu['jab_dosen']==1) 
			{
				$jab = "Pembimbing";
				$bobot = $nu["nilai_p1"]/$nu["n_p1"];
			}
			else 
			{
				$jab = "Penguji ".$nu['urut_dosen'];
				$bobot = $nu["nilai_p2"]/$nu["n_p2"];
			}
			
			$pdf->SetFont('helvetica','',11);		
			$width = array(35,5,145); 
			$align = array("L","C","L");
			$valign = array("T","T","T");
			$str = array("Nama",":",$nu['nm_mhs']);		
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("NIM",":",$nu['nim']);
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("Departemen",":",$nu['nm_jur']);
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("Program Studi",":",$jn." ".$nu['nm_ps']);
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("Konsentrasi",":","[konsentrasi]");
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("Ujian ke",":",$nu['jml_uji']);
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
			$str = array("Judul ".$ta,":",$nu['judul_ta']);
			$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(5);
			
			$pdf->SetFont('helvetica','',11);
			$tx	= "Maka dapat diberikan penilaian sebagai berikut (dengan bobot ".$bobot."%):";
			$pdf->setX($left);
			$pdf->MultiCell(185,6,$tx); $pdf->Ln(5);
			
			$pdf->SetFont('helvetica','B',11);
			$width = array(10,115,15,15,30);
			$align = array("C","C","C","C","C");
			$valign = array("T","T","T","T","T");		
			$str = array("No.","Komponen Penilaian","Nilai","Bobot","Nilai X Bobot");			
			$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
			
			$i=0;
			$pdf->SetFont('helvetica','',11);
			$align = array("C","L","C","C","C");
			$total = 0;
			foreach ($nu["det"] as $dnu)
			{
				$i++; $total+=$dnu['na'];				
				$str = array($i,$dnu['nm_kriteria'],$dnu['nilai'],$dnu['pers_nilai']."%",number_format($dnu['na'],1,',','.'));			
				$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
			}			
			$width = array(140,15,30);
			$pdf->SetFont('helvetica','B',11);
			$align = array("C","C","C");
			$valign = array("T","T","T");		
			$str = array("Total Nilai","100%",number_format($total,1,',','.'));			
			$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
			
			$pdf->Ln(5); $pdf->SetFont('helvetica','',11);
			$tx	= "Demikian untuk dapat dijadikan periksa dan atas perhatiannya kami ucapkan terima kasih.";
			$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(10);
								
			$pdf->setX($left+95); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(6);
			$pdf->setX($left+95); $pdf->Cell(0,10,$jab.",",0,0,'L'); $pdf->Ln(30);
			$pdf->setX($left+95); $pdf->MultiCell(90,5,$nu['nm_dosen']); $pdf->Ln(1);
			$pdf->setX($left+95); $pdf->Cell(0,5,"[jenis ID Pembimbing]. ".$nu['no_dosen']); $pdf->Ln(15);
			
			$tAry["dosen"] = $nu['nm_dosen'];
			$tAry["bobot"] = $bobot;
			$tAry["total"] = $total;
			$aryTim[] = $tAry;
		}
		
		/* SECOND */
		$pdf->AddPage('P');
		$pdf->SetFont('helvetica','B',14); $pdf->Ln(15);
		$pdf->Cell(0,10,"REKAPITULASI NILAI ".strToUpper($list["nu"][0]['nm_tuji']),0,0,'C'); $pdf->Ln(15);
		$pdf->SetFont('helvetica','',11);		
		$tx	= "Ujian ".$ta." dari mahasiswa: ";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(2);
				
		$width = array(35,5,145); 
		$align = array("L","C","L");
		$valign = array("T","T","T");
		$str = array("Nama",":",$list["nu"][0]['nm_mhs']);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("NIM",":",$list["nu"][0]['nim']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Departemen",":",$list["nu"][0]['nm_jur']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Program Studi",":",$jn." ".$list["nu"][0]['nm_ps']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Konsentrasi",":","[konsentrasi]");
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Ujian ke",":",$list["nu"][0]['jml_uji']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Judul ".$ta,":",$list["nu"][0]['judul_ta']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(6);
		
		$tx	= "Telah diselenggarakan pada: ";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(2);
		$str = array("Hari, Tanggal",":",$hari[$dayNum*1].", ".$strDate);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Pukul",":",$list["nu"][0]['jam1_uji']." - ".$list["nu"][0]['jam2_uji']." WIB");		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Tempat",":",$list["nu"][0]['nm_ruang']);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(6);
		
		$tx	= "dengan hasil penilaian sebagai berikut:";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(2);		
		$pdf->SetFont('helvetica','B',11);
		$width = array(10,145,30);
		$align = array("C","C","C");
		$valign = array("T","T","T");		
		$str = array("No.","Penilai","Nilai Angka");			
		$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
		
		$i=0; $na=0;
		foreach ($aryTim as $tim)
		{
			$i++; $na+=($tim["total"]*($tim["bobot"]/100));
			$pdf->SetFont('helvetica','',11);
			$align = array("C","L","C");
			$str = array($i,$tim["dosen"],number_format($tim["total"],1,',','.'));			
			$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);			
		}
		
		$pdf->SetFont('helvetica','B',11);
		$width = array(155,30);
		$align = array("C","C");
		$str = array("Total Nilai",number_format($na,1,',','.'));			
		$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
		
		$pdf->Ln(5); 
		$pdf->SetFont('helvetica','',11);
		$tx	= "Demikian untuk dapat dijadikan periksa dan atas perhatiannya kami ucapkan terima kasih.";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(10);
		
		$pdf->setX($left+100); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(6);
		$pdf->setX($left+100); $pdf->Cell(0,10,"Pembimbing ".$ta.",",0,0,'L'); $pdf->Ln(30);
		$pdf->setX($left+100); $pdf->MultiCell(185,0,$list["nu"][0]['nm_dosen']); $pdf->Ln(1);
		$pdf->setX($left+100); $pdf->Cell(0,10,"[jenis ID Pembimbing]. ".$list["nu"][0]['no_dosen']); $pdf->Ln(15);
		
		/* THIRD */
		$pdf->AddPage('P');
		$pdf->SetFont('helvetica','B',14); $pdf->Ln(15);
		$pdf->Cell(0,10,"BERITA ACARA PENILAIAN ".strToUpper($ta),0,0,'C'); $pdf->Ln(15);
		$pdf->SetFont('helvetica','',11);		
		$tx	= "Kami yang bertanda tangan di bawah ini, Pembimbing dari mahasiswa:";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(6);
		
		$width = array(35,5,145); 
		$align = array("L","C","L");
		$valign = array("T","T","T");
		$str = array("Nama",":",$list["nu"][0]['nm_mhs']);		
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("NIM",":",$list["nu"][0]['nim']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Departemen",":",$list["nu"][0]['nm_jur']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Program Studi",":",$jn." ".$list["nu"][0]['nm_ps']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Konsentrasi",":","[konsentrasi]");
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Ujian ke",":",$list["nu"][0]['jml_uji']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(2);
		$str = array("Judul ".$ta,":",$list["nu"][0]['judul_ta']);
		$pdf->setX($left); $pdf->DCell($width,$h1,null,null,$str,$align,$valign,FALSE); $pdf->Ln(6);
		
		$pdf->SetFont('helvetica','',11);
		$tx	= "Menilai ".$ta." mahasiswa tersebut dengan dasar nilai dari komponen-komponen kegiatan ".$ta.":";
		$pdf->setX($left);
		$pdf->MultiCell(185,6,$tx); $pdf->Ln(5);
		
		$pdf->SetFont('helvetica','B',11);
		$width = array(10,115,15,15,30);
		$align = array("C","C","C","C","C");
		$valign = array("T","T","T","T","T");		
		$str = array("No.","Kegiatan","Nilai","Bobot","Nilai X Bobot");			
		$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
		
		$pdf->SetFont('helvetica','',11);
		$align = array("C","L","C","C","C");
		$i=0; $na=0;
		foreach ($list["hu"] as $hu)
		{
			$i++; $nilai=$hu["nilai_uji"]*($hu["pers_tuji"]/100); $na+=$nilai;
			$str = array($i,$hu["nm_tuji"],number_format($hu["nilai_uji"],1,',','.'),$hu["pers_tuji"],number_format($nilai,1,',','.'));			
			$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
		}
		
		$pdf->SetFont('helvetica','B',11);
		$width = array(140,15,30);
		$align = array("C","C","C");
		$str = array("TOTAL","100",number_format($na,1,',','.'));			
		$pdf->setX($left); $pdf->DCell($width,$h2,null,null,$str,$align,$valign);
		
		if ($na>80) $nh="A";
		elseif ($na>75) $nh="B+";
		elseif ($na>69) $nh="B";
		elseif ($na>60) $nh="C+";
		elseif ($na>55) $nh="C";
		elseif ($na>50) $nh="D+";
		elseif ($na>40) $nh="D";
		else $nh="E";
		
		if ($na>55) $ket="LULUS";
		else $ket="TIDAK LULUS";
		
		$pdf->Ln(5); $pdf->SetFont('helvetica','',11);
		$tx	= "dan dengan demikian, nilai ".$ta." (nilai angka) dari mahasiswa tersebut di atas adalah __".number_format($na,1,',','.')."__ dan berdasarkan itu, mahasiswa bersangkutan dinyatakan:";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx);
		$pdf->Ln(1); $pdf->SetFont('helvetica','B',12);
		$pdf->setX($left); $pdf->MultiCell(185,6,$ket,0,'C',0);
		$pdf->Ln(1); $pdf->SetFont('helvetica','',11);
		$pdf->setX($left); $pdf->MultiCell(185,6,"dengan Grade (nilai huruf)");
		$pdf->Ln(1); $pdf->SetFont('helvetica','B',12);
		$pdf->setX($left); $pdf->MultiCell(185,6,$nh,0,'C',0);
		$pdf->Ln(1); $pdf->SetFont('helvetica','',11);
		$tx	= "Demikian untuk dapat dijadikan periksa dan atas perhatiannya kami ucapkan terima kasih.";
		$pdf->setX($left); $pdf->MultiCell(185,6,$tx); $pdf->Ln(10);
				
		$pdf->setX($left+95); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(6);
		$pdf->setX($left+95); $pdf->Cell(0,10,"Pembimbing ".$ta.",",0,0,'L'); $pdf->Ln(30);
		$pdf->setX($left+95); $pdf->MultiCell(90,5,$list["nu"][0]['nm_dosen']); $pdf->Ln(1);
		$pdf->setX($left+95); $pdf->Cell(0,5,"[jenis ID Pembimbing]. ".$list["nu"][0]['no_dosen']); $pdf->Ln(15);
	}
	elseif ($tipe=="ru")
	{
		$pdf = new PDF('P','mm','Legal');
		$pdf->AliasNbPages();
		
		/* FIRST */
		$i=0;
		foreach ($list["nu"] as $nu)
		{
			$i++; $ta="";
			if ($nu['jenjang']=="S1") $ta="Skripsi";
			elseif ($nu['jenjang']=="S2" OR $nu['jenjang']=="XP") $ta="Tesis";
			elseif ($nu['jenjang']=="S3") $ta="Disertasi";
			if ($nu['jenjang']=="S1") $jn="Sarjana";
			elseif ($nu['jenjang']=="S2" OR $nu['jenjang']=="XP") $jn="Magister";
			elseif ($nu['jenjang']=="S3") $jn="Doktor";
			
			if ($nu['jab_dosen']==1) $jab="PEMBIMBING";
			else $jab="PENGUJI ".$nu['urut_dosen'];
			
			$pdf->AddPage('P');
			$pdf->SetFont('helvetica','BU',14); $pdf->Ln(15);
			$pdf->Cell(0,10,"REVISI ".strToUpper($ta)." (".$jab.")",0,0,'C'); $pdf->Ln(5);
			$pdf->SetFont('helvetica','',11);
			$pdf->Cell(0,12,strToUpper($nu['nm_dosen']),0,0,'C'); $pdf->Ln(15);			
			
			$tgl = explode("-",$nu['tgl_uji']);
			$strDate = ($tgl[2]*1)." ".$bulan[$tgl[1]*1]." ".$tgl[0];
			$dayNum = date('w', strtotime($nu['tgl_uji']));
			
			$pdf->SetFont('helvetica','',11);		
			$width = [40,5,140]; 
			$align = ["L","C","J"];
			$valign = array("T","T","T");		
			$str = array("Hari, Tanggal Ujian",":",$hari[$dayNum*1].", ".$strDate); $borders = ["TL","T","TR"];		
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders); 
			$str = array("Nama",":",$nu['nm_mhs']); $borders = ["L","","R"];		
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders); 
			$str = array("NIM",":",$nu['nim']); $borders = ["L","","R"];
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders);
			$str = array("Departemen",":",$nu['nm_jur']);
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders);
			$str = array("Konsentrasi",":","[konsentrasi]");
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders);
			$str = array("Uraian Meliputi",":","BAB I, II, III, IV, dan V");
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders);
			$str = array("Judul ".$ta,":",$nu['judul_ta']); $borders = ["BL","B","BR"];
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders);
			
			$pdf->Ln(5);
			$pdf->SetFont('helvetica','B',11);
			$pdf->setX($left);
			$pdf->MultiCell(185,7,"REVISI",1,'C');
			
			$pdf->SetFont('helvetica','',11);
			
			$i=0;
			$aryRev = explode("|-|",$nu['revisi_uji']);							
			$width=[7,178]; $align=["R","L"]; $valign=["T","T"]; $borders=["L","R"]; 
			$pdf->setX($left); $pdf->c_DCell($width,3,null,null," ",$align,$valign,$borders); 
			if (count($aryRev)>1)
			{
				for ($j=0; $j<count($aryRev); $j++)
				{
					$i++;
					$str=[$i.".",$aryRev[$j]];
					$pdf->setX($left); $pdf->c_DCell($width,$h1,null,null,$str,$align,$valign,$borders); 
				}
			}
			else 
			{ 
				$i++;
				$str=["1.",$nu['revisi_uji']];
				$pdf->setX($left); $pdf->c_DCell($width,$h1,null,null,$str,$align,$valign,$borders); 
			}		
			
			$pdf->SetFont('helvetica','I',10);
			$width=[185]; $align=["L"]; $valign=["T"]; $str=["Catatan:"]; $borders=["LR"];
			$pdf->setX($left); $pdf->c_DCell($width,$h1+5,null,null,[""],$align,$valign,$borders); 
			$pdf->setX($left); $pdf->c_DCell($width,$h1+2,null,null,$str,$align,$valign,$borders); 
			$pdf->SetFont('helvetica','',10);
			$width=[7,178]; $align=["R","J"]; $valign=["T","T"]; $borders=["L","R"];
			$str=["1.","Hasil Ujian dengan Revisi, belum berhak mendapatkan Transkrip dan Surat Keterangan LULUS."]; 		
			$pdf->setX($left); $pdf->c_DCell($width,$h1,null,null,$str,$align,$valign,$borders); 
			$borders=["L","R"];
			$str=["2.","Batas waktu revisi 1 (satu) bulan sejak tanggal pelaksanaan ujian. Sedangkan batas waktu menyerahkan berkas yudisium adalah 3 (tiga) bulan setelah tanggal pelaksanaan ujian. Apabila melewati batas waktu tersebut, mahasiswa WAJIB mengulang ujian akhir studi/komprehensif."]; 		
			$pdf->setX($left); $pdf->c_DCell($width,$h1,null,null,$str,$align,$valign,$borders);
			$width=[185]; $align=["L"]; $valign=["T"]; $borders=["LRB"];
			$pdf->setX($left); $pdf->c_DCell($width,3,null,null,[""],$align,$valign,$borders); 
			
			if ($i==1) { $nama=$nu['nm_dosen']; $no=$nu['no_dosen']; }
			$pdf->Ln(8);
			$pdf->SetFont('helvetica','',11);
			$pdf->setX($left+95); $pdf->Cell(0,10,"Malang, ".$strDate,0,0,'L'); $pdf->Ln(6);
			$pdf->setX($left+95); $pdf->Cell(0,10,"Pembimbing ".$ta.",",0,0,'L'); $pdf->Ln(30);
			$pdf->setX($left+95); $pdf->MultiCell(90,5,$nama); $pdf->Ln(1);
			$pdf->setX($left+95); $pdf->Cell(0,5,"[jenis ID Pembimbing]. ".$no); $pdf->Ln(15);
		}
	}

	$pdf->Output();
?>