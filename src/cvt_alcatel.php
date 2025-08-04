<?php

function create_alcatel($xrtttl)
{
  global $rtl_name;
  global $rtl_props;
  global $rtl_notes;
  global $error_rtttl;
  global $convert_errors;
  global $convert_notes;

  $convert_errors = 0;
  $convert_notes = "";

  $status = pharse_rtttl($xrtttl);
  if($status == 0) /* An error has occured */
  {
    $rtl_name = "";
    $rtl_props = array();
    $rtl_notes = array();
    $xrtttl = $error_rtttl;
    pharse_rtttl($xrtttl);
    $convert_errors = 2;
		$convert_notes .= "Invalid RTTTL. Could not convert.\n";
    return(0);
  }
  $autoOctave=getAutoOctave(3);

  $convert_notes .= "Do not enter spaces. They are just for making things easier to read";

  $tmpStr="";
  
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if(!empty($rtlNote))
    {
      if($rtlTime==1)
        $tmpDur="0";
      elseif($rtlTime==2)
        $tmpDur="8";
      elseif($rtlTime==4)
        $tmpDur="088";
      elseif($rtlTime==8)
        $tmpDur="88";
      elseif($rtlTime==16)
        $tmpDur="88";
      elseif($rtlTime==32)
        $tmpDur="";

      if(stristr($rtlNote,"c"))
        $tmpNote="1";
      elseif(stristr($rtlNote,"d"))
        $tmpNote="2";
      elseif(stristr($rtlNote,"e"))
        $tmpNote="3";
      elseif(stristr($rtlNote,"f"))
        $tmpNote="4";
      elseif(stristr($rtlNote,"g"))
        $tmpNote="5";
      elseif(stristr($rtlNote,"a"))
        $tmpNote="6";
      elseif(stristr($rtlNote,"b"))
        $tmpNote="7";
      elseif(stristr($rtlNote,"p"))
        $tmpNote="0";
  
      if($rtlOctave < $autoOctave) //Compensate for AutoOctave
        $tmpOct="";
      elseif($rtlOctave == $autoOctave+1)
        $tmpOct="*";
      elseif($rtlOctave >= $autoOctave+2)
        $tmpOct="**";

      $tmpStr.=$tmpNote.$tmpOct;
      if(stristr($rtlNote,"#"))
        $tmpStr.="#";
      $tmpStr.=$tmpDur." ";
    }
  }
  return($tmpStr);
}

