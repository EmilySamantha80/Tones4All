<?php

function create_panasonic_gd75($xrtttl)
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
      $rtlOctave = 3;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 4;
    elseif($rtlOctave >= $autoOctave+2)
      $rtlOctave = 5;

    if(empty($rtlNote))
      break;
      
    if($rtlTime==32)
      $tpCount=2;
    elseif($rtlTime==16)
      $tpCount=2;
    elseif($rtlTime==8)
      $tpCount=1;
    elseif($rtlTime==4)
      $tpCount=0;
    elseif($rtlTime==2)
      $tpCount=4;
    elseif($rtlTime==1)
      $tpCount=3;

    if(stristr($rtlNote,"e"))
    {
      if($rtlOctave==5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=2;
      elseif($rtlOctave==6)
        $npCount=3;
    } elseif(stristr($rtlNote,"b"))
    {
      if($rtlOctave>=5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=2;
    }
    else
    {
      if($rtlOctave==5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=3;
      elseif($rtlOctave==6)
        $npCount=5;
        
      if(stristr($rtlNote,"#"))
        $npCount++;
    }

    if(stristr($rtlNote,"c"))
      $npKey=1;
    elseif(stristr($rtlNote,"d"))
      $npKey=2;
    elseif(stristr($rtlNote,"e"))
      $npKey=3;
    elseif(stristr($rtlNote,"f"))
      $npKey=4;
    elseif(stristr($rtlNote,"g"))
      $npKey=5;
    elseif(stristr($rtlNote,"a"))
      $npKey=6;
    elseif(stristr($rtlNote,"b"))
      $npKey=7;
    elseif(stristr($rtlNote,"p"))
    {
      $npKey=0;
      $npCount=1;
    }

    for($tmpCnt=0;$tmpCnt<$npCount;$tmpCnt++)
      $tmpStr.=$npKey;
    $tmpStr.=" ";

    for($tmpCnt=0;$tmpCnt<$tpCount;$tmpCnt++)
      $tmpStr.="*";
    
    if($rtlDotted)
    {
      if(substr($rtl_notes[$tmpa+1][1],0,1)!="p")
      {
        $tmpStr.=" 0";
        $rtlNote="p";
        if($rtlTime==32)
          $tpCount=2;
        elseif($rtlTime==16)
          $tpCount=2;
        elseif($rtlTime==8)
          $tpCount=2;
        elseif($rtlTime==4)
          $tpCount=1;
        elseif($rtlTime==2)
          $tpCount=0;
        elseif($rtlTime==1)
          $tpCount=4;
        
        if($tpCount>0)
          $tmpStr.=" ";
          
        for($tmpCnt=0;$tmpCnt<$tpCount;$tmpCnt++)
          $tmpStr.="*";
        $tmpStr.=" ";
      }
    }
    
    if($tmpa<count($rtl_notes)-1)
      if(substr($rtlNote,0,1)==substr($rtl_notes[$tmpa+1][1],0,1))
        $tmpStr.=" >";

    $tmpStr.=" ";
  }
  $convert_notes.="These codes are for the GD75 (other handsets may vary slightly - try the GD67 if these codes do not work).\n";
  $convert_notes.="Where you see  >,  this means press the right arrow button (or push joystick right) on your handset.\n";
  return($tmpStr);
}

function create_panasonic_gd67($xrtttl)
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
      $rtlOctave = 4;
    elseif($rtlOctave == $autoOctave+1)
      $rtlOctave = 5;
    elseif($rtlOctave >= $autoOctave+2)
      $rtlOctave = 6;

    if(empty($rtlNote))
      break;
      
    if($rtlTime==32)
      $tpCount=5;
    elseif($rtlTime==16)
      $tpCount=5;
    elseif($rtlTime==8)
      $tpCount=1;
    elseif($rtlTime==4)
      $tpCount=0;
    elseif($rtlTime==2)
      $tpCount=3;
    elseif($rtlTime==1)
      $tpCount=4;

    if(stristr($rtlNote,"e"))
    {
      if($rtlOctave==5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=2;
      elseif($rtlOctave==6)
        $npCount=3;
    } elseif(stristr($rtlNote,"b"))
    {
      if($rtlOctave>=5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=2;
    }
    else
    {
      if($rtlOctave==5)
        $npCount=1;
      elseif($rtlOctave==4)
        $npCount=3;
      elseif($rtlOctave==6)
        $npCount=5;
      
      if(stristr($rtlNote,"#"))
      {
        if($rtlOctave!=6)
          $npCount++;
      }
    }

    if(stristr($rtlNote,"c"))
      $npKey=1;
    elseif(stristr($rtlNote,"d"))
      $npKey=2;
    elseif(stristr($rtlNote,"e"))
      $npKey=3;
    elseif(stristr($rtlNote,"f"))
      $npKey=4;
    elseif(stristr($rtlNote,"g"))
      $npKey=5;
    elseif(stristr($rtlNote,"a"))
      $npKey=6;
    elseif(stristr($rtlNote,"b"))
      $npKey=7;
    elseif(stristr($rtlNote,"p"))
    {
      $npKey=0;
      $npCount=1;
    }

    for($tmpCnt=0;$tmpCnt<$npCount;$tmpCnt++)
      $tmpStr.=$npKey;
    $tmpStr.=" ";

    if($rtlOctave==6)
      if(stristr($rtlNote,"#"))
        $tmpStr.="# ";

    for($tmpCnt=0;$tmpCnt<$tpCount;$tmpCnt++)
      $tmpStr.="*";
    
    if($rtlDotted)
    {
      if(substr($rtl_notes[$tmpa+1][1],0,1)!="p")
      {
        $tmpStr.=" 0";
        $rtlNote="p";
        if($rtlTime==32)
          $tpCount=5;
        elseif($rtlTime==16)
          $tpCount=5;
        elseif($rtlTime==8)
          $tpCount=5;
        elseif($rtlTime==4)
          $tpCount=1;
        elseif($rtlTime==2)
          $tpCount=0;
        elseif($rtlTime==1)
          $tpCount=3;
        
        if($tpCount>0)
          $tmpStr.=" ";
          
        for($tmpCnt=0;$tmpCnt<$tpCount;$tmpCnt++)
          $tmpStr.="*";
        $tmpStr.=" ";
      }
    }
    
    if($tmpa<count($rtl_notes)-1)
      if(substr($rtlNote,0,1)==substr($rtl_notes[$tmpa+1][1],0,1))
        $tmpStr.=" >";

    $tmpStr.=" ";
  }
  $convert_notes.="These codes are for the GD67 (other handsets may vary slightly - try the GD75 if these codes do not work).\n";
  $convert_notes.="Where you see  >,  this means press the right arrow button (or push joystick right) on your handset.\n";

  return($tmpStr);
}

?>
