<?php

function get_prop($prop, $xrtttl) /* $prop can be either "name" or "bpm" */
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
  $prop = strtolower($prop);
  if($prop=="name")
    $get_prop = $rtl_name;
  elseif($prop=="bpm")
    $get_prop = $rtl_props["b"];
  return($get_prop);
}

function pharse_rtttl($str) {
  global $rtl_name;
  global $rtl_props;
  global $rtl_notes;
  
  $r = 0;
  $cnt = 0;
  $a = explode(":",$str);
  if(count($a) == 3) {
    $rtl_name = $a[0];
    
    $b = explode(",",$a[1]);
    if(count($b) == 3) {
    
      if(strpos($a[1],"o") !== false && strpos($a[1],"d") !== false && strpos($a[1],"b") !== false ) {
      
        $c = explode("=",$b[0]);
        $d = explode("=",$b[1]);
        $e = explode("=",$b[2]);
        $rtl_props = array(  $c[0] => $c[1],
                  $d[0] => $d[1],
                  $e[0] => $e[1]
                  );
        $nts = explode(",",clean_spaces($a[2]));
        $time = "";
        $pitch = "";
        $octave = "";
        $dotted = 0;

        foreach ($nts as $a => $b){ 
          $o = $b;
          list($time) = sscanf($b,"%d");
          $b = strrev(substr($b,strlen($time),strlen($b) - strlen($time)));
          list($octave) = sscanf($b,"%d");
          $b = strrev(substr($b,strlen($octave),strlen($b) - strlen($octave)));
          
          if(is_dotted($b)) {
            $dotted = 1;
          }else {
            $dotted = 0;
          }
          
          $pitch = $b;
          
          if($time == "") $time = $rtl_props["d"];
          if($octave == "") $octave = $rtl_props["o"];
          $rtl_notes[$cnt] = array(trim($time),trim($pitch),trim($octave),$dotted);
          $cnt++;
          $r = 1;
        }
      }
    }
  } 
  return($r);
}

function getAutoOctave($numOct)
{
  $tOctave = array();
  $tOctNum = array();
  $tOctCount = array();

  global $rtl_notes;

  for($tCnt=0;$tCnt<=10;$tCnt++)
  {
    $tOctCount[$tCnt] = 0;
    $tOctNum[$tCnt] = $tCnt;
  }

  for($tCnt=0;$tCnt<count($rtl_notes);$tCnt++)
  {
    $tmpOctave = $rtl_notes[$tCnt][2];
    $tOctCount[$tmpOctave] += 1;
  }


  for($ti=0;$ti<count($tOctCount)-1;$ti++)
  {
    for($tj=0;$tj<count($tOctCount)-1;$tj++)
    { 
      if($tOctCount[$tj] > $tOctCount[$tj+1])
      {
        $tmpInt = $tOctCount[$tj];
        $tmpOctNum = $tOctNum[$tj];
        $tOctCount[$tj] = $tOctCount[$tj+1];
        $tOctNum[$tj] = $tOctNum[$tj+1];
        $tOctCount[$tj+1] = $tmpInt;
        $tOctNum[$tj+1] = $tmpOctNum;
      }
    }
  }

  $tmpInt = 0;
  $tmpOctNum = $numOct;
  for($tCnt=0;$tCnt<3;$tCnt++)
  {
    if($tOctCount[10-$tCnt]==0)
      $tmpOctNum -= 1;
    else
      $tmpInt += $tOctNum[10-$tCnt];
  }

  $tmpCnt = ($tmpInt / $tmpOctNum) - ($numOct / 2);
  $tmpCnt = ceil($tmpCnt);
  if($tmpCnt<4)
    $tmpCnt=4;
  return($tmpCnt);
}

?>
