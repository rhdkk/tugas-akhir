<?php
require('fpdf.php');

class Mysql_table extends FPDF
{
	var $ProcessingTable=false;
	var $aCols=array();
	var $TableX;
	var $HeaderColor;
	var $RowColors;
	var $ColorIndex;

	function Header()
	{
		//Print the table header if necessary
		if($this->ProcessingTable)
			$this->TableHeader();
	}

	function TableHeader()
	{
		$this->SetFont('Arial','',9);
		$this->SetX($this->TableX);
		$fill=!empty($this->HeaderColor);
		if($fill)
			$this->SetFillColor($this->HeaderColor[0],$this->HeaderColor[1],$this->HeaderColor[2]);
		foreach($this->aCols as $col)
			$this->Cell($col['w'],6,$col['c'],1,0,'C',$fill);
		$this->Ln();
	}

	function Row($data)
	{
		$this->SetX($this->TableX);
		$ci=$this->ColorIndex;
		$fill=!empty($this->RowColors[$ci]);
		if($fill)
			$this->SetFillColor($this->RowColors[$ci][0],$this->RowColors[$ci][1],$this->RowColors[$ci][2]);
		foreach($this->aCols as $col)
			$this->Cell($col['w'],5,$data[$col['f']],1,0,$col['a'],$fill);
		$this->Ln();
		$this->ColorIndex=1-$ci;
	}

	function CalcWidths($width,$align)
	{
		//Compute the widths of the columns
		$TableWidth=0;
		foreach($this->aCols as $i=>$col)
		{
			$w=$col['w'];
			if($w==-1)
				$w=$width/count($this->aCols);
			elseif(substr($w,-1)=='%')
				$w=$w/100*$width;
			$this->aCols[$i]['w']=$w;
			$TableWidth+=$w;
		}
		//Compute the abscissa of the table
		if($align=='C')
			$this->TableX=max(($this->w-$TableWidth)/2,0);
		elseif($align=='R')
			$this->TableX=max($this->w-$this->rMargin-$TableWidth,0);
		else
			$this->TableX=$this->lMargin;
	}

	function AddCol($field=-1,$width=-1,$caption='',$align='L')
	{
		//Add a column to the table
		if($field==-1)
			$field=count($this->aCols);
		$this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
	}

	function Table($query,$prop=array())
	{
		//Issue query
		$res=mysql_query($query) or die('Error: '.mysql_error()."<BR>Query: $query");
		//Add all columns if none was specified
		if(count($this->aCols)==0)
		{
			$nb=mysql_num_fields($res);
			for($i=0;$i<$nb;$i++)
				$this->AddCol();
		}
		//Retrieve column names when not specified
		foreach($this->aCols as $i=>$col)
		{
			if($col['c']=='')
			{
				if(is_string($col['f']))
					$this->aCols[$i]['c']=ucfirst($col['f']);
				else
					$this->aCols[$i]['c']=ucfirst(mysql_field_name($res,$col['f']));
			}
		}
		//Handle properties
		if(!isset($prop['width']))
			$prop['width']=0;
		if($prop['width']==0)
			$prop['width']=$this->w-$this->lMargin-$this->rMargin;
		if(!isset($prop['align']))
			$prop['align']='C';
		if(!isset($prop['padding']))
			$prop['padding']=$this->cMargin;
		$cMargin=$this->cMargin;
		$this->cMargin=$prop['padding'];
		if(!isset($prop['HeaderColor']))
			$prop['HeaderColor']=array();
		$this->HeaderColor=$prop['HeaderColor'];
		if(!isset($prop['color1']))
			$prop['color1']=array();
		if(!isset($prop['color2']))
			$prop['color2']=array();
		$this->RowColors=array($prop['color1'],$prop['color2']);
		//Compute column widths
		$this->CalcWidths($prop['width'],$prop['align']);
		//Print header
		$this->TableHeader();
		//Print rows
		$this->SetFont('Arial','',9);
		$this->ColorIndex=0;
		$this->ProcessingTable=true;
		while($row=mysql_fetch_array($res))
			$this->Row($row);
		$this->ProcessingTable=false;
		$this->cMargin=$cMargin;
		$this->aCols=array();
	}
	
	function GetMultiCellHeight($w, $h, $txt, $border=0, $align='L')
	{
		$cw = &$this->CurrentFont['cw'];
		if($w==0)
			$w = $this->w - $this->rMargin - $this->x;
		
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		
		if($nb > 0 && $s[$nb-1] == "\n")
			$nb--;
		
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		
		while($i < $nb)
		{
			$c = $s[$i];
			if($c == "\n")
			{
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if($c == ' ')
				$sep = $i;
			
			$l += $cw[$c];
			
			if($l > $wmax)
			{
				if($sep == -1)
				{
					if($i == $j)
						$i++;
				}
				else
					$i = $sep + 1;
				
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			}
			else $i++;
		}
		
		return $h * $nl;
	}
	
	function drawTextBox($strText,$w,$h,$hrow,$align='L',$valign='T',$border=true)
	{
		$xi=$this->GetX();
		$yi=$this->GetY();
		
		$textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
		$maxrows=floor($h/$this->FontSize);
		$rows=min($textrows,$maxrows);

		$dy=0;
		if (strtoupper($valign)=='M')
			$dy=($h-$rows*$this->FontSize)/2;
		if (strtoupper($valign)=='B')
			$dy=$h-$rows*$this->FontSize;

		$this->SetY($yi+$dy);
		$this->SetX($xi);

		$this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);

		if ($border)
			$this->Rect($xi,$yi,$w,$h);
	}
	
	function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
{
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 && $s[$nb-1]=="\n")
        $nb--;
    $b=0;
    if($border)
    {
        if($border==1)
        {
            $border='LTRB';
            $b='LRT';
            $b2='LR';
        }
        else
        {
            $b2='';
            if(is_int(strpos($border,'L')))
                $b2.='L';
            if(is_int(strpos($border,'R')))
                $b2.='R';
            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
        }
    }
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $ns=0;
    $nl=1;
    while($i<$nb)
    {
        //Get next character
        $c=$s[$i];
        if($c=="\n")
        {
            //Explicit line break
            if($this->ws>0)
            {
                $this->ws=0;
                if ($prn==1) $this->_out('0 Tw');
            }
            if ($prn==1) {
                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
            }
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border && $nl==2)
                $b=$b2;
            if ( $maxline && $nl > $maxline )
                return substr($s,$i);
            continue;
        }
        if($c==' ')
        {
            $sep=$i;
            $ls=$l;
            $ns++;
        }
        $l+=$cw[$c];
        if($l>$wmax)
        {
            //Automatic line break
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
                if($this->ws>0)
                {
                    $this->ws=0;
                    if ($prn==1) $this->_out('0 Tw');
                }
                if ($prn==1) {
                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
                }
            }
            else
            {
                if($align=='J')
                {
                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
                    if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
                }
                if ($prn==1){
                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
                }
                $i=$sep+1;
            }
            $sep=-1;
            $j=$i;
            $l=0;
            $ns=0;
            $nl++;
            if($border && $nl==2)
                $b=$b2;
            if ( $maxline && $nl > $maxline )
                return substr($s,$i);
        }
        else
            $i++;
    }
    //Last chunk
    if($this->ws>0)
    {
        $this->ws=0;
        if ($prn==1) $this->_out('0 Tw');
    }
    if($border && is_int(strpos($border,'B')))
        $b.='B';
    if ($prn==1) {
        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
    }
    $this->x=$this->lMargin;
    return $nl;
}
}
?>
