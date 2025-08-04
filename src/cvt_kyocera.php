<?php

#This uses functions from MidiGen.
#These libraries are included in a different file.

function create_kws($xrtttl)
{
  global $rtl_name;
  global $rtl_props;
  global $rtl_notes;
  global $error_rtttl;

  $status = pharse_rtttl($xrtttl);
  if($status == 0)
  {
    $rtl_name = "";
    $rtl_props = array();
    $rtl_notes = array();
    $xrtttl = $error_rtttl;
    pharse_rtttl($xrtttl);
  }
  $autoOctave=getAutoOctave(3);

  $tmpRun = 0;
  for($tt=1;$tt<count($rtl_notes);$tt++)
  {
    $rtlNote = $rtl_notes[$tt][1];
    $rtlOctave = $rtl_notes[$tt][2];
    $rtlNote2 = $rtl_notes[$tt-1][1];
    $rtlOctave2 = $rtl_notes[$tt-1][2];
    if($rtlNote==$rtlNote2 && $rtlOctave==$rtlOctave2)
      $tmpRun++;
  }
  $filelen = 10 + ((count($rtl_notes) + $tmpRun) * 4);
  $filelenbin = str_pad(decbin($filelen),32,"0",STR_PAD_LEFT);
  $tlen = array();
  $tlen[0] = substr($filelenbin,24,8);
  $tlen[1] = substr($filelenbin,16,8);
  $tlen[2] = substr($filelenbin,8,8);
  $tlen[3] = substr($filelenbin,0,8);
  $tmp32bit = "";
  $kwsstring = "";
  $kwsheader = "";
  for($tt=0;$tt<4;$tt++)
  {
    $ddt = "";
    for($tb=0;$tb<8;$tb++)
    {
      $ta = substr($tlen[$tt],$tb,1);
      if($ta=="0")
        $ddt .= "1";
      else
        $ddt .= "0";
    }
    $kwsheader .= chr(bindec($ddt));
  }
  $notecountbin = str_pad(decbin(count($rtl_notes)+$tmpRun),16,"0",STR_PAD_LEFT);
  $kwsnotecount = chr(bindec(substr($notecountbin,8,8))) . chr(bindec(substr($notecountbin,0,8)));
  $kwsstring = $kwsheader . $kwsnotecount;
 
  for($tt=0;$tt<count($rtl_notes);$tt++)
  {
    $rtlTime = $rtl_notes[$tt][0];
    $rtlDotted = $rtl_notes[$tt][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tt][1];
    $rtlOctave = $rtl_notes[$tt][2];

    if($tt>0)
    {
      $rtlNote2 = $rtl_notes[$tt-1][1];
      $rtlOctave2 = $rtl_notes[$tt-1][2];
      if($rtlNote==$rtlNote2 && $rtlOctave==$rtlOctave2)
        $kwsstring = $kwsstring . chr(0) . chr(0) . chr(8) . chr(0);
    }

    $kwsBPM = 125;
    if($rtlTime==1)
      $tmpTime = (2000 / $rtlBPM) * $kwsBPM;
    elseif($rtlTime==2)
      $tmpTime = (1000 / $rtlBPM) * $kwsBPM;
    elseif($rtlTime==4)
      $tmpTime = (500 / $rtlBPM) * $kwsBPM;
    elseif($rtlTime==8)
      $tmpTime = (250 / $rtlBPM) * $kwsBPM;
    elseif($rtlTime==16)
      $tmpTime = (125 / $rtlBPM) * $kwsBPM;
    elseif($rtlTime==32)
      $tmpTime = (63 / $rtlBPM) * $kwsBPM;

    if($rtlDotted)
      $tmpTime *= 1.5;

    $tmpTime = ceil($tmpTime);

    if($tmpTime <= 255)
      $kwsDur = chr($tmpTime) . chr(0);
    else
    {
      $tmpd = str_pad(dechex($tmpTime),4,"0",STR_PAD_LEFT);
      $tmpa = hexdec(substr($tmpd,0,2));
      $tmpa1 = $tmpa << 1;
      $tmpb = hexdec(substr($tmpd,2,2));
      $kwsDur = $tmpa1 ^ $tmpb;
      $kwsDur = chr($kwsDur) . chr($tmpa);
    }

    if($rtlNote != "p")
    {
      if($rtlOctave <= $autoOctave)
        $rtlOctave = 4;
      elseif($rtlOctave == $autoOctave+1)
        $rtlOctave = 5;
      elseif($rtlOctave >= $autoOctave+2)
        $rtlOctave = 6;

      if(stristr($rtlNote,"c#"))
        $mNote = 2;
      elseif(stristr($rtlNote,"c"))
        $mNote = 1;        
      elseif(stristr($rtlNote,"d#"))
        $mNote = 4;
      elseif(stristr($rtlNote,"d"))
        $mNote = 3;
      elseif(stristr($rtlNote,"e"))
        $mNote = 5;
      elseif(stristr($rtlNote,"f#"))
        $mNote = 7;
      elseif(stristr($rtlNote,"f"))
        $mNote = 6;
      elseif(stristr($rtlNote,"g#"))
        $mNote = 9;
      elseif(stristr($rtlNote,"g"))
        $mNote = 8;
      elseif(stristr($rtlNote,"a#"))
        $mNote = 11;
      elseif(stristr($rtlNote,"a"))
        $mNote = 10;
      elseif(stristr($rtlNote,"b"))
        $mNote = 12;

      $mNote = (12 * ($rtlOctave - 5)) + $mNote;
      $kwsNote = chr($mNote) . chr(4);
    }else
    {
      $kwsNote = chr(0) . chr(0);
    }
    $kwsstring .= $kwsNote . $kwsDur;
  }
  $kwsstring .= $kwsheader;

  return($kwsstring);
}


?>
