<?php

/*
  ***** IMPORTANT!!! READ THIS INFORMATION *****
  This script is intended to be run on PHP 8.x

  NOTE: You must have	the following set in your php.ini file:
    register_globals = On
  If you don't, then the variable $midi, $kws, etc won't be passed.
*/

/*
  The following are the possible values of $convert_errors:
  0 = No errors
  1 = Minor Error(s), see $convert_notes for detailed info
  2 = Fatal Error(s), see $convert_notes for detailed info
*/

/* Include our conversion functions */
include('cvt_rtttl.php');      /* All scripts rely on RTTTL.PHP         */
include('cvt_midi.php');       /* Conversion to MIDI                    */
include('cvt_kyocera.php');    /* Conversion to KWS                     */
include('cvt_ericsson.php');   /* Conversion to Ericsson                */
include('cvt_motorola.php');   /* Conversion to Motorola                */
include('cvt_nokia.php');      /* Conversion to Nokia                   */
include('cvt_samsung.php');    /* Conversion to Samsung                 */
include('cvt_panasonic.php');  /* Conversion to Panasonic               */
include('cvt_alcatel.php');    /* Conversion to Alcatel                 */

/* Uncomment next line to report simple running errors only, if wanted  */
/* error_reporting  (E_ERROR | E_WARNING | E_PARSE);                    */

$rtttlstring=$_GET['rtttlstring'] ?? null;
$midi=$_GET['midi'] ?? null;
$kws=$_GET['kws'] ?? null;
$composer=$_GET['composer'] ?? null;
$keypress=$_GET['keypress'] ?? null;
$ericssonkp=$_GET['ericssonkp'] ?? null;
$emelody=$_GET['emelody'] ?? null;
$imelody=$_GET['imelody'] ?? null;
$pangd67=$_GET['pangd67'] ?? null;
$pangd75=$_GET['pangd75'] ?? null;
$motosms=$_GET['motosms'] ?? null;
$samkp1=$_GET['samkp1'] ?? null;
$samkp2=$_GET['samkp2'] ?? null;
$alcatel=$_GET['alcatel'] ?? null;

if(isset($rtttlstring))
  $rtttlstring = str_replace(" ","",$rtttlstring);

if(isset($midi)) { /* Output MIDI File */
  $prop_name = get_prop("name",$rtttlstring); /* Grab the name of the tone */
  /* You must include these header statements before any other echo
     or print statements. This tells the browser that you want to
     download the MIDI, not just print it. */
  header("Content-type: audio/x-midi");
  header("Content-Disposition: attachment; filename=\"" . substr($prop_name,0,8) . ".mid\"");
  echo create_midi($rtttlstring,11); 
  /* An exit statement must be called to end the script immediately */
  exit;

} elseif(isset($kws)) { /* Output KWS File */
  $prop_name = get_prop("name",$rtttlstring); /* Grab the name of the tone */
  /* You must include these header statements before any other echo
     or print statements. This tells the browser that you want to
     download the MIDI, not just print it. */
  header("Content-type: application/binary");
  header("Content-Disposition: attachment; filename=\"" . substr($prop_name,0,8) . ".kws\"");
  echo create_kws($rtttlstring); 
  /* An exit statement must be called to end the script immediately */
  exit;

} elseif(isset($composer)) {                             /* Convert to Nokia Composer            */
  $new_tone = create_nokia_composer($rtttlstring);
  $convert_to = "Nokia Composer";
} elseif(isset($keypress)) {                             /* Convert to Nokia Keypress            */
  $new_tone = create_nokia_keypress($rtttlstring);
  $convert_to = "Nokia Keypress";
} elseif(isset($ericssonkp)) {                           /* Convert to Ericsson Keypress         */
  $new_tone = create_ericsson_kp($rtttlstring);
  $convert_to = "Sony Ericsson Composer";
} elseif(isset($emelody)) {                              /* Convert to Ericsson eMelody          */
  $new_tone = create_ems_emelody($rtttlstring);
  $convert_to = "Ericsson EMS eMelody";
} elseif(isset($imelody)) {                              /* Convert to Nokia Ericsson iMelody    */
  $new_tone = create_ems_imelody($rtttlstring);
  $convert_to = "Ericsson EMS eMelody";
} elseif(isset($pangd75)) {                              /* Convert to Nokia Panasonic GD75      */
  $new_tone = create_panasonic_gd75($rtttlstring);
  $convert_to = "Panasonic GD75";
} elseif(isset($pangd67)) {                              /* Convert to Nokia Panasonic GD67      */
  $new_tone = create_panasonic_gd67($rtttlstring);
  $convert_to = "Panasonic GD67";
} elseif(isset($motosms)) {                              /* Convert to Motorola by SMS           */
  $new_tone = create_motorola_sms($rtttlstring);
  $convert_to = "Motorola by SMS";
} elseif(isset($samkp1)) {                               /* Convert to Samsung Keypress Type 1   */
  $new_tone = create_samsung_kp1($rtttlstring);
  $convert_to = "Samsung Keypress Type 1";
} elseif(isset($samkp2)) {                               /* Convert to Samsung Keypress Type 2   */
  $new_tone = create_samsung_kp2($rtttlstring);
  $convert_to = "Samsung Keypress Type 2";
} elseif(isset($alcatel)) {                              /* Convert to Samsung Keypress Type 2   */
  $new_tone = create_alcatel($rtttlstring);
  $convert_to = "Alcatel";
} elseif(isset($rtttlstring)) {           /* Show conversion form */
  $rtttlconv = urlencode($rtttlstring);   /* We encode the string so it can be sent via HTTP GET */
  ?>
    <title>Tones4All PHP</title>
    <body alink=BLUE vlink=BLUE>
    <center>
    <font size=5><a href="http://t4a.vulc.in">Tones4All</a> PHP Version</font><br>
    <font size=4>Framework v1.0</font><br>
    <font size=4>Code v1.0</font><br>
    (c)2002-2003, Emily Johnson <a href="mailto:emilysamantha80@gmail.com">emilysamantha80@gmail.com</a><br><br>
    </center>
    <table border=1 align=center>
    <tr><td>
    <font size=4>RTTTL Ringtone to be converted:</font><br>
    <?=$rtttlstring?><br>
    <a href="index.php">Convert a different tone</a>
    </td></tr>
    </table>
    <br>
    <table align=center>
      <tr><td align=center colspan=2><font size=4>Convert from RTTTL to:</font></td></tr>
    	<tr><td align=right><a href="index.php?midi=1&rtttlstring=<?=$rtttlconv?>">Convert to MIDI</a></td><td>for playing/previewing on computers</td></tr>
      <tr><td align=right><a href="index.php?kws=1&rtttlstring=<?=$rtttlconv?>">Convert to Kyocera KWS</a></td><td>for 2119, 2135, 2255, 3035, and possibly others</td></tr>
      <tr><td align=right><a href="index.php?composer=1&rtttlstring=<?=$rtttlconv?>">Convert to Nokia Composer</a></td><td>for 3210, 3310, 3330, 3390, 8250 (This is what the phone displays)</td></tr>
      <tr><td align=right><a href="index.php?keypress=1&rtttlstring=<?=$rtttlconv?>">Convert to Nokia Keypress</a></td><td>for 3210, 3310, 3330, 3390, 8250 (This is the actual keypresses)</td></tr>
      <tr><td align=right><a href="index.php?ericssonkp=1&rtttlstring=<?=$rtttlconv?>">Convert to Sony Ericsson Composer</a></td><td>for T68m, T68i, T300</td></tr>
      <tr><td align=right><a href="index.php?emelody=1&rtttlstring=<?=$rtttlconv?>">Convert to Ericsson EMS eMelody</a></td><td>for R520, R600, T20e, T29, T39, T66, T68m</td></tr>
      <tr><td align=right><a href="index.php?imelody=1&rtttlstring=<?=$rtttlconv?>">Convert to Ericsson EMS iMelody</a></td><td>for T65, T68i, T300</td></tr>
      <tr><td align=right><a href="index.php?pangd67=1&rtttlstring=<?=$rtttlconv?>">Convert to Panasonic GD67</a></td><td>for GD67, and possibly some others</td></tr>
      <tr><td align=right><a href="index.php?pangd75=1&rtttlstring=<?=$rtttlconv?>">Convert to Panasonic GD75</a></td><td>for GD75 and most other Panasonics with composers</td></tr>
      <tr><td align=right><a href="index.php?motosms=1&rtttlstring=<?=$rtttlconv?>">Convert to Motorola by SMS</a></td><td>for T193, T191, v100, v50</td></tr>
      <tr><td align=right><a href="index.php?samkp1=1&rtttlstring=<?=$rtttlconv?>">Convert to Samsung Keypress Type 1</a></td><td>for A200, A288, N105, N400, Q300, R220, R225, S100, T100, V100</td></tr>
      <tr><td align=right><a href="index.php?samkp2=1&rtttlstring=<?=$rtttlconv?>">Convert to Samsung Keypress Type 2</a></td><td>for A300, A308, A400, A408, Q100, Q105, Q200</td></tr>
      <tr><td align=right><a href="index.php?alcatel=1&rtttlstring=<?=$rtttlconv?>">Convert to Alcatel Composer</a></td><td>for OneTouch 300, 301, 302, 303, 311, 511</td></tr>
      <tr><td align=center colspan=2><a href="t4aphp.chm"><br>Download Help File/Usage Instructions</a></td></tr>
      <tr><td align=center colspan=2><a href="t4aphp.zip">Download Source Code</a></td></tr>
    </table>
    </body>
  <?php
  exit;
} else {
  ?>
    <title>Tones4All PHP</title>
    <body alink=BLUE vlink=BLUE>
    <center>
    <font size=5><a href="http://t4a.vulc.in">Tones4All</a> PHP Version</font><br>
    <font size=4>Framework v1.0</font><br>
    <font size=4>Code v1.0</font><br>
    (c)2002-2003, Emily Johnson <a href="mailto:emilysamantha80@gmail.com">emilysamantha80@gmail.com</a><br><br>
    <form method="GET" action="index.php">
    <b>Paste RTTTL Text Here:</b><br>
    <textarea name="rtttlstring" rows="6" cols="60">TocattaFugue:d=32,o=5,b=100:a#.,g#.,2a#,g#,f#,f,d#.,4d.,2d#,a#.,g#.,2a#,8f,8f#,8d,2d#,8d,8f,8g#,8b,8d6,4f6,4g#.,4f.,1g,32p,</textarea>
    <br><INPUT type="submit" name="submitbutton" value="Choose Conversion Method"><INPUT type="reset">
    <br><br><a href="t4aphp.zip">Download Source Code</a>
    </form>
    </center>
    </body>
  <?php
  exit;
}

$prop_bpm = get_prop("bpm",$rtttlstring);   /* Grab the BPM of the tone */
$prop_name = get_prop("name",$rtttlstring); /* Grab the name of the tone */
$convert_notes = str_replace("\n","<br>",$convert_notes);
$new_tone = str_replace("\n","<br>",$new_tone);
?>
  <body alink=BLUE vlink=BLUE> 
<?php
  if($convert_errors==2)
  {
?>
    <title>Tones4All PHP</title>
    <body alink=BLUE vlink=BLUE>
    <font size=5>A fatal error has occured!</font><br><br>
    <b>Errorlevel:</b> <?=$convert_errors?><br><br>
    <b>Description:</b><br><?=$convert_notes?><br>
    <br><a href="index.php">Convert a different tone</a>
    </body>
<?php
    exit;
  }
  $rtttlconv = urlencode($rtttlstring); /* We encode the string so it can be sent via HTTP GET */
?>
  <title>Tones4All PHP</title>
  <body alink=BLUE vlink=BLUE>
  <b><font size=4>Converting from RTTTL to <?=$convert_to?></font></b><br><br>
  <b>Tone Name:</b> <?=$prop_name?><br>
  <b>Tone BPM:</b> <?=$prop_bpm?><br><br>
  <b>Original RTTTL:</b><br><?=$rtttlstring?><br><br>
  <b>Converted Tone:</b><br><?=$new_tone?><br><br>
  <b>Additional Notes:</b><br><?=$convert_notes?><br><br>
  <b>Errorlevel:</b> <?=$convert_errors?><br><br>
  <a href="index.php?rtttlstring=<?=$rtttlconv?>">Back</a>
  <br><br><font size=3><i>Created by Tones4All PHP Version<br>(c)2002-2003, Emily Johnson <a href="mailto:emilysamantha80@gmail.com">emilysamantha80@gmail.com</a><br/>
    <a href="http://t4a.vulc.in/php" target=_t4a>Tones4All PHP</a></font>
    </body>
