<?php

function create_ericsson_kp($xrtttl)
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
  $autoOctave=getAutoOctave(4);

  $kpOct=1;
  $kpDur=3;

  $convert_notes .= "Do not enter spaces. They are just for making things easier to read";

  $tmpStr="";
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave <= $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 1;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 2;
    elseif($rtlOctave == $autoOctave+2)
      $rtlOctave = 3;
    elseif($rtlOctave >= $autoOctave+3)
      $rtlOctave = 4;

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
    elseif(stristr($partNote,"p"))
      $tmpNote="*";

    $tmpStr.=$tmpNote;

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

    if($rtlOctave>$kpOct)
    {
      for($tmpCnt=0;$tmpCnt<($rtlOctave-$kpOct);$tmpCnt++)
        $tmpStr.="0";
      $kpOct=$rtlOctave;
    } elseif($rtlOctave!=$kpOct) {
      for($tmpCnt=0;$tmpCnt<(4-$kpOct)+$rtlOctave;$tmpCnt++)
        $tmpStr.="0";
    	$kpOct=$rtlOctave;
    }

    if(stristr($rtlNote,"#"))
      $tmpStr.="##";

    if($rtlDotted)
    {
      $tmpStr.="*";
      if($kpDur!=6)
      {
        $kpDur++;
        $tmpStr.="8";
      }
    }

    $tmpStr.=" ";
  }
  return($tmpStr);
}

function create_ems_emelody($xrtttl)
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
  $autoOctave=getAutoOctave(2);

  $strTone = "BEGIN:EMELODY\nVERSION:1.0\n";

  $tmpStr = "MELODY:";
  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave <= $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 4;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 5;

    if($rtlNote!="")
    {
      if($rtlTime==1)
        $noteLong=true;
      elseif($rtlTime==2)
        $noteLong=true;
      elseif($rtlTime==4)
        $noteLong=true;
      elseif($rtlTime==8)
        $noteLong=false;
      elseif($rtlTime==16)
        $noteLong=false;
      elseif($rtlTime==32)
        $noteLong=false;
      
      if(stristr($rtlNote,"p"))
      {
        if($noteLong==false)
          $tmpStr.="p";
        else
          $tmpStr.="pp";
      } else {
        if($rtlOctave==5)
          $tmpStr.="+";
        if(stristr($rtlNote,"#"))
          $tmpStr.="#";
        if($noteLong)
          $tmpStr.=strtoupper(substr($rtlNote,0,1));
        else
          $tmpStr.=substr($rtlNote,0,1);
      }
    }
  }
  return($strTone . $tmpStr . "\nEND:EMELODY");
}

function create_ems_imelody($xrtttl)
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
  $autoOctave=getAutoOctave(4);

  $strTone="BEGIN:IMELODY\nVERSION:1.2\nFORMAT:CLASS1.0\n";
  $strTone.="NAME:" . $rtl_name . "\nBEAT:" . $rtl_props["b"] . "\n";
  $tmpStr="MELODY:";

  for($tmpa=0;$tmpa<count($rtl_notes);$tmpa++)
  {
    $rtlTime = $rtl_notes[$tmpa][0];
    $rtlDotted = $rtl_notes[$tmpa][3];
    $rtlBPM = $rtl_props["b"];
    $rtlNote = $rtl_notes[$tmpa][1];
    $rtlOctave = $rtl_notes[$tmpa][2];

    if($rtlOctave <= $autoOctave) //Compensate for AutoOctave
      $rtlOctave = 4;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 5;
    elseif($rtlOctave == $autoOctave+2)
      $rtlOctave = 6;
    elseif($rtlOctave >= $autoOctave+3)
      $rtlOctave = 7;

    if($rtlNote!="")
    {
      if($rtlTime==1)
        $tmpDur=0;
      if($rtlTime==2)
        $tmpDur=1;
      if($rtlTime==4)
        $tmpDur=2;
      if($rtlTime==8)
        $tmpDur=3;
      if($rtlTime==16)
        $tmpDur=4;
      if($rtlTime==32)
        $tmpDur=5;
      
      if((strlen($tmpStr)+7)>75)
      {
        $strTone.=$tmpStr . "\n";
        $tmpStr="";
      }

      if($rtlNote=="p")
      {
        if($rtlTime==32)
          $tmpPause="r4";
        elseif($rtlTime==16)
          $tmpPause="r4";
        elseif($rtlTime==8)
          $tmpPause="r4r4";
        elseif($rtlTime==4)
          $tmpPause="r4r3";
        elseif($rtlTime==2)
          $tmpPause="r3r3";
        elseif($rtlTime==1)
          $tmpPause="r2";
        
        $tmpStr.=$tmpPause;
        
        if($rtlDotted)
          $tmpStr.=".";
      } else {
        if($rtlOctave!=4)
          $tmpStr.="*" . $rtlOctave;
        
        if(stristr($rtlNote,"#"))
          $tmpStr.="#";
          
        $tmpStr.=substr($rtlNote,0,1) . $tmpDur;
        
        if($rtlDotted)
          $tmpStr.=".";
      }
    }
  }
  $strTone.=$tmpStr . "\nEND:IMELODY";
  return($strTone);
}

?>
