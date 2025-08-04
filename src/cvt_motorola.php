<?php

function create_motorola_sms($xrtttl)
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

  $tmpHeader="L35&2 ";
  $tmpStr="";
  
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    if($tmpa>=35)
    {
      $convert_notes.="There are more than 35 notes. Tone has been truncated to 35 notes.\n";
      break;
    }

    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave < $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 4;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 5;
    elseif($rtlOctave >= $autoOctave+2)
      $rtlOctave = 6;

    if($rtlNote=="p")
    {
      $tmpStr.="R";
    } elseif(!empty($rtlNote))
    {
      $tmpStr.=strtoupper($rtlNote);
      if($rtlOctave=="4")
        $tmpStr.="-";
      elseif($rtlOctave=="6")
        $tmpStr.="+";

      if($rtlTime==32)
        $tmpStr.="1";
      elseif($rtlTime==16)
        $tmpStr.="2";
      elseif($rtlTime==8)
        $tmpStr.="3";
      elseif($rtlTime==4)
        $tmpStr.="4";
      elseif($rtlTime==2)
        $tmpStr.="5";
      elseif($rtlTime==1)
        $tmpStr.="6";
    }
  }
  $tXOR=0;
  for($tmpa=0;$tmpa<strlen($tmpStr);$tmpa++)
  {
    $tXOR=ord(substr($tmpStr,$tmpa,1)) ^ $tXOR;
  }

  $byte1=((hexdec(substr(dechex($tXOR),0,1) . "0")) >> 4) + 0x30;
  $byte2=hexdec(("0" . substr(dechex($tXOR),1,1))) + 0x30;
  $converted = $tmpHeader . $tmpStr . "&&" . chr($byte1) . chr($byte2);

  $convert_notes.="Send the message to your phone as a text message using your cellular operator's SMS gateway.\n";  
  return($converted);
}

?>
