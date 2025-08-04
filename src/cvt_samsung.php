<?php

function create_samsung_kp1($xrtttl)
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

  if(count($rtl_notes)>=100)
    $convert_notes.="Note count exceeds the maximum limit of 100 notes.\n";

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
        $tmpDur=2;
      elseif($rtlTime==2)
        $tmpDur=1;
      elseif($rtlTime==4)
        $tmpDur=0;
      elseif($rtlTime==8)
        $tmpDur=4;
      elseif($rtlTime==16)
        $tmpDur=3;
      elseif($rtlTime==32)
        $tmpDur=3;

      if(!stristr($rtlNote,"p"))
      {
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
  
        if($rtlOctave < $autoOctave) //Compensate for AutoOctave
          $tmpOct="88";
        elseif($rtlOctave == $autoOctave+1)
          $tmpOct="";
        elseif($rtlOctave >= $autoOctave+2)
          $tmpOct="8";

        $tmpStr.=$tmpNote;
      
        if(stristr($rtlNote,"#"))
          $tmpStr.="#";

        $tmpStr.=$tmpOct;
        for($td=0;$td<$tmpDur;$td++)
          $tmpStr.="*";
        $tmpStr.=" ";

        if($rtlDotted)
        {
          if($rtlTime==1)
            $tmpDur=1;
          elseif($rtlTime==2)
            $tmpDur=0;
          elseif($rtlTime==4)
            $tmpDur=4;
          elseif($rtlTime==8)
            $tmpDur=3;
          elseif($rtlTime==16)
            $tmpDur=3;
          elseif($rtlTime==32)
            $tmpDur=3;

          $rtlNote="p";
        }
      }
    }
    if(stristr($rtlNote,"p"))
    {
        $tmpStr.="0";
        for($td=0;$td<$tmpDur;$td++)
          $tmpStr.="*";    
        $tmpStr.=" ";
    }
  }
  return($tmpStr);
}

function create_samsung_kp2($xrtttl)
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

  if(count($rtl_notes)>=100)
    $convert_notes.="Note count exceeds the maximum limit of 100 notes.\n";

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
        $tmpDur=4;
      elseif($rtlTime==2)
        $tmpDur=3;
      elseif($rtlTime==4)
        $tmpDur=0;
      elseif($rtlTime==8)
        $tmpDur=1;
      elseif($rtlTime==16)
        $tmpDur=2;
      elseif($rtlTime==32)
        $tmpDur=2;

      if(!stristr($rtlNote,"p"))
      {
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
  
        if($rtlOctave < $autoOctave) //Compensate for AutoOctave
          $tmpOct="88";
        elseif($rtlOctave == $autoOctave+1)
          $tmpOct="";
        elseif($rtlOctave >= $autoOctave+2)
          $tmpOct="8";

        $tmpStr.=$tmpNote;
      
        if(stristr($rtlNote,"#"))
          $tmpStr.="^";

        $tmpStr.=$tmpOct;
        for($td=0;$td<$tmpDur;$td++)
          $tmpStr.="<";
        $tmpStr.=" ";
      } elseif(stristr($rtlNote,"p"))
      {
        if($rtlTime==1)
          $tmpDur=4;
        elseif($rtlTime==2)
          $tmpDur=4;
        elseif($rtlTime==4)
          $tmpDur=1;
        elseif($rtlTime==8)
          $tmpDur=2;
        elseif($rtlTime==16)
          $tmpDur=3;
        elseif($rtlTime==32)
          $tmpDur=3;
         for($td=0;$td<$tmpDur;$td++)
          $tmpStr.=">";    
        $tmpStr.=" ";
      }
    }
  }
  $convert_notes.="< > and ^ correspond to the left, right, and up arrow buttons on the handset.\n";
  return($tmpStr);
}

?>
