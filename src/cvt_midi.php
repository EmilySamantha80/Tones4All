<?php
//-----------------------------------------------------------------------------------
// Please Do not remove this line
$copright_string = "MidiGen CopyRight under GPL written by G.Babakhani 2001";
//-----------------------------------------------------------------------------------

$error_rtttl = "Error:d=32,o=5,b=63:4c,4e,4g";

$rtl_name  = "";
$rtl_props  = array();
$rtl_notes = array();
$convert_errors = 0;
$convert_notes = "";

function create_midi($xrtttl,$program) {
  global $rtl_name;
  global $rtl_props;
  global $rtl_notes;
  global $error_rtttl;

  $status = pharse_rtttl($xrtttl);
  
  if ($status == 0) 
  { 
    $rtl_name  = "";
    $rtl_props  = array();
    $rtl_notes = array();
  
    $xrtttl = $error_rtttl;
    pharse_rtttl($xrtttl);
  }
  
  $head    =  mf_write_header_chunk(0,1,384);
  $track_data =   copy_right();
  $track_data =   $track_data .track_name("MIDI by MidiGen 0.9");
  $track_data =   $track_data . volumeup();
  $track_data =   $track_data . mf_write_tempo($rtl_props["b"]);
  
  $track_data =  $track_data . add_program($program);

  $track_data =  $track_data . notes2midi();

  $track_data =   $track_data . end_track();
  $track_head  =  mf_write_track_chunk($track_data);
  $track = $track_head . $track_data;
  $midi = $head . $track;
  return($midi);
}

function eputc($ch) {
  return(chr($ch));
}
  
function write32bit($data) {
  $r = "";
    $r = $r . eputc((($data >> 24) & 0xff));
     $r = $r . eputc((($data >> 16) & 0xff));
    $r = $r . eputc((($data >> 8 ) & 0xff));
     $r = $r . eputc(($data & 0xff));
  return($r);
}
  
function write16bit($data) {
  $r = "";
    $r = $r . eputc((($data & 0xff00) >> 8));
     $r = $r . eputc(($data & 0xff));
  return($r);
}  
  
function mf_write_header_chunk($format,$ntracks,$division)
{
  $r = "";
    $ident=0;
  $length=0;
    $ident = 0x4d546864;
    $length = 6;

    $r = $r . write32bit($ident);
    $r = $r . write32bit($length);
    $r = $r . write16bit($format);
    $r = $r . write16bit($ntracks);
    $r = $r . write16bit($division);
  return($r);
}
  
function mf_write_track_chunk($track)
{
  $r = "";
  $trkhdr = 0x4d54726b;
  $r = $r . write32bit($trkhdr);
  $r = $r . write32bit(strlen($track));
  return($r);
}

function WriteVarLen($value)
{
  $buffer=0;
  $r = "";

  $buffer = $value & 0x7f;
  while(($value >>= 7) > 0)
  {
  $buffer <<= 8;
  $buffer |= 0x80;
  $buffer += ($value & 0x7f);
  }
  while(1){
       $r = $r . eputc(($buffer & 0xff));
       
  if($buffer & 0x80)
    $buffer >>= 8;
  else
    return($r);
  }
}

function mf_write_tempo($t)
{
  $tempo  = (60000000.0 / ($t));
    $r = "";
  $r = $r .  eputc(0);
    $r = $r .  eputc(0xff);
    $r = $r .  eputc(0x51);

    $r = $r .  eputc(3);
    $r = $r .  eputc((0xff & ($tempo >> 16)));
    $r = $r .  eputc((0xff & ($tempo >> 8)));
    $r = $r .  eputc((0xff & $tempo));
  return($r);
}

function mf_write_midi_event($delta_time, $type, $chan, $data)
{
    $i=0;
    $c = 0;
    $r = WriteVarLen($delta_time);

    $c = $type | $chan;

    $r = $r . eputc($c);

    for($i = 0; $i < count($data); $i++)
    $r = $r . eputc($data[$i]);

    return($r);
}

function data($p1,$p2) {
  $r = array();
  $r[0] = $p1;
  $r[1] = $p2;
  return($r);
}

function data1($p1) {
  $r = array();
  $r[0] = $p1;
  return($r);
}

function end_track() {
  $r = "";
  $r = $r . eputc(0);
  $r = $r . eputc(0xFF);
  $r = $r . eputc(0x2f);
  $r = $r . eputc(0);
  return($r);
}

function add_program($prg) {
  $r = "";
  $r = mf_write_midi_event(0,0xc0,0,data1($prg));
  return($r);
}

function note($s,$d,$p,&$td) {
  $r = "";
  $r = $r . mf_write_midi_event($s,0x90,0,data($p,100));
  $r = $r . mf_write_midi_event($d,0x80,0,data($p,0));
  $td = $td . $r;
  //return($r);
}

function volume() {
  $r = "";
  return($r);
}

function copy_right() {
  global $copy_right_string;
    $c = $copy_right_string;
    $r = "";
  $r = $r .  eputc(0);
    $r = $r .  eputc(0xff);
    $r = $r .  eputc(0x02);

    $r = $r .  eputc(strlen($c));
    $r = $r .  $c;
  return($r);
}

function track_name($str) {
    $c = $str;
    $r = "";
  $r = $r .  eputc(0);
    $r = $r .  eputc(0xff);
    $r = $r .  eputc(0x03);

    $r = $r .  eputc(strlen($c));
    $r = $r .  $c;
  return($r);
}


function volumeup() {
  $r = "";
  $r = mf_write_midi_event(0,0xB0,0,data(0x07,127));
  return($r);
}

function dotted($nt) {
  $r = $nt + ($nt/2);
  return($r);
}


function fdebug($str,$w=0) {
  if($w==0) {
    $f = fopen("debug.txt","a");
  } else {
    $f = fopen("debug.txt","w");
  }
  fputs($f,$str);
  fputs($f,"\n");
  fclose($f);
}

function get_pitch($nt,$oc) {
  $nt = strtolower(trim($nt));
  $r =0;
  if($nt == "p") { 
    $r = -1;
  } else {
    switch($nt) {
      case "c"  :  $r = 0; break;
      case "c#"  :  $r = 1; break;
      case "d"  :  $r = 2; break;
      case "d#"  :  $r = 3; break;
      case "e"  :  $r = 4; break;
      case "f"  :  $r = 5; break;
      case "f#"  :  $r = 6; break;
      case "g"  :  $r = 7; break;
      case "g#"  :  $r = 8; break;
      case "a"  :  $r = 9; break;
      case "a#"  :  $r = 10; break;
      case "b"  :  $r = 11; break;
    }
    $r = 12 + (12*$oc) + $r;
  }
  return($r);
}

function get_time($t,$isd) {
  $r = 0;
  switch($t) {
    case 1    : $r = 1536; break;
    case 2    : $r = 768; break;
    case 4    : $r = 384; break;
    case 8    : $r = 192; break;
    case 16    : $r = 96; break;
    case 32    : $r = 48; break;
    case 64    : $r = 24; break;
  }
  
  if($isd) {
    $r = $r + ($r/2);
  }
  return($r);
}

function notes2midi() {
  $r = "";
  $rest = 0;
  global $rtl_notes;
  $notes = $rtl_notes;
  $tmp = array();

  for($a = 0; $a != count($notes); $a++) {
    $pt = get_pitch($notes[$a][1],$notes[$a][2]-1);
    $tm = get_time($notes[$a][0],$notes[$a][3]);
    if($pt == -1) {
      $rest = $rest + $tm;
    } else {
      note($rest,$tm,$pt,$r);
      $rest = 0;
    }
  }
  return($r);
}

function clean_spaces($str) {
  $r = preg_replace("/ /","",$str);
  return($r);
}
  
    
function exist($c,$s) {
  $s = "!" . $s;
  $r = strpos($s,$c,0);
  return($r);
}
  
function is_dotted(&$nt) {
  $r = 0;
  for($a = 0; $a != strlen($nt); $a++) {
    if($nt[$a] == ".") {
      $nt = substr_replace($nt, '', $a, 1);
      $r = 1;
      break;
    }
  }
//  $b = trim($b);
  return($r);
}

?>
