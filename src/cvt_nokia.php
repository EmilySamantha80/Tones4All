<?php

function create_nokia_keypress($xrtttl)
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

  $kpOct=1;
  $kpDur=3;
  
  $convert_notes .= "() around a note means to hold the button for 3 seconds.\n";
  $convert_notes .= "Do not enter spaces. They are just for making things easier to read";

  if(count($rtl_notes)>50)
  {
    $convert_errors = 1;
    $convert_notes .= "Note count exceeds the maximum of 50 notes\n";
  }

  $tmpStr="";
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave <= $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 5;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 6;
    elseif($rtlOctave >= $autoOctave+2)
      $rtlOctave = 7;

    if($rtlNote!="")
    {
      if($rtlTime==1)
        $tmpDur=1;
      elseif($rtlTime==2)
        $tmpDur=2;
      elseif($rtlTime==4)
      	$tmpDur=3;
      elseif($rtlTime==8)
        $tmpDur=4;
      elseif($rtlTime==16)
        $tmpDur=5;
      elseif($rtlTime==32)
        $tmpDur=6;
    }

    if(!stristr($rtlNote,"p"))
    {
      $partNote=substr($rtlNote,0,1);
      if(stristr($partNote,"c"))
        $tmpNote="1";
      elseif(stristr($partNote,"d"))
        $tmpNote="2";
      elseif(stristr($partNote,"e"))
        $tmpNote="3";
      elseif(stristr($partNote,"f"))
        $tmpNote="4";
      elseif(stristr($partNote,"g"))
        $tmpNote="5";
      elseif(stristr($partNote,"a"))
        $tmpNote="6";
      elseif(stristr($partNote,"b"))
        $tmpNote="7";
      else
        $tmpNote="";

      if($rtlDotted)
        $tmpStr.= "(" . $tmpNote . ")";
      else
        $tmpStr .= $tmpNote;

      $tmpOct = $rtlOctave - 4;
      
      if($tmpDur>$kpDur)
      {
        for($tmpCnt=0;$tmpCnt<($tmpDur-$kpDur);$tmpCnt++)
          $tmpStr.="8";
        $kpDur=$tmpDur;
      } elseif($tmpDur!=$kpDur) {
        for($tmpCnt=0;$tmpCnt<($kpDur-$tmpDur);$tmpCnt++)
          $tmpStr.="9";
        $kpDur=$tmpDur;
      }
      
      if(stristr($rtlNote,"#"))
        $tmpStr.="#";

      if($tmpOct==$kpOct)
      {
        
      }
      elseif($tmpOct>$kpOct)
      {
        for($tmpCnt=0;$tmpCnt<($tmpOct-$kpOct);$tmpCnt++)
          $tmpStr.="*";
        $kpOct=$tmpOct;      
      } else 
      {
        for($tmpCnt=0;$tmpCnt<((3-$kpOct)+$tmpOct);$tmpCnt++)
          $tmpStr.="*";
        $kpOct=$tmpOct;
      }
      $tmpStr.=" ";
    } elseif($rtlNote=="p") {
      $tmpStr.="0";
      if($tmpDur>$kpDur)
      {
        for($tmpCnt=0;$tmpCnt<($tmpDur-$kpDur);$tmpCnt++)
          $tmpStr.="8";
      } elseif($tmpDur!=$kpDur) {
        for($tmpCnt=0;$tmpCnt<($kpDur-$tmpDur);$tmpCnt++)
          $tmpStr.="9";
      }
      $tmpStr.=" ";
    }
  }
  return($tmpStr);
}

function create_nokia_composer($xrtttl)
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

  if(count($rtl_notes)>50)
  {
    $convert_errors = 1;
    $convert_notes .= "Note count exceeds the maximum of 50 notes\n";
  }

  $tmpStr = "";
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave <= $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 5;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 6;
    elseif($rtlOctave == $autoOctave+2)
      $rtlOctave = 7;

    if(empty($rtlNote))
      break;

    $tmpStr .= $rtlTime;
    if($rtlDotted)
      $tmpStr .= ".";
    
    if($rtlNote == "p")
      $tmpStr .= "-";
    elseif(empty($rtlNote));
    else
    {
      if(stristr($rtlNote,"#"))
        $tmpStr .= "#" . substr($rtlNote,0,1);
      else
        $tmpStr .= substr($rtlNote,0,1);
      
      $tmpStr .= $rtlOctave - 4;
    }
    $tmpStr .= " ";
  }
  return($tmpStr);
}

?>
