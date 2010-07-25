<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2010 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

error_reporting(E_ERROR | E_PARSE);

$tmpDir = "viewer";
$fileUploadLimitBytes = 4194304;
$fileUploadLimitText = round(($fileUploadLimitBytes / 1048576), 0) . "MB";

$debugHelpText = "a flashing chessboard signals errors in the PGN data, click on the top left chessboard square for debug messages";

if (!($goToView = get_pgn())) { $pgnText = $krabbeStartPosition = get_krabbe_position(); }
set_mode();
print_header();
print_form();
check_tmpDir();
print_chessboard();
print_footer();

function set_mode() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  $mode = $_REQUEST["mode"];

  if (!$mode) {
    $mode = "normal";
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $mobileagents = array ("Android", "Blackberry", "iPhone", "iPod", "Nokia", "Opera Mini", "Palm", "PlayStation Portable", "Pocket", "Smartphone", "Symbian", "WAP", "Windows CE"); 
    foreach ($mobileagents as $ma) {
      if(stristr($ua, $ma)) { $mode = "compact"; } 
    }
  }
}

function get_krabbe_position() {

  $krabbePositions = array('',
    '[Round "1"][FEN "rnq2rk1/1pn3bp/p2p2p1/2pPp1PP/P1P1Pp2/2N2N2/1P1B1P2/R2QK2R b KQ - 1 16"] 16... Nc6',
    '[Round "2"][FEN "8/8/4kpp1/3p1b2/p6P/2B5/6P1/6K1 b - - 2 47"] 47... Bh3',
    '[Round "3"][FEN "5rk1/pp4pp/4p3/2R3Q1/3n4/2q4r/P1P2PPP/5RK1 b - - 1 23"] 23. Qg3',
    '[Round "4"][FEN "1r6/4k3/r2p2p1/2pR1p1p/2P1pP1P/pPK1P1P1/P7/1B6 b - - 0 48"] 48... Rxb3+',
    '[Round "5"][FEN "2k2b1r/pb1r1p2/5P2/1qnp4/Npp3Q1/4B1P1/1P3PBP/R4RK1 w - - 4 21"] 21. Qg7',
    '[Round "6"][FEN "r1bq1rk1/1p3ppp/p1pp2n1/3N3Q/B1PPR2b/8/PP3PPP/R1B3K1 w - - 0 14"] 14. Rxh4',
    '[Round "7"][FEN "r4k1r/1b2bPR1/p4n2/3p4/4P2P/1q2B2B/PpP5/1K4R1 w - - 0 26"] 26. Bh6',
    '[Round "8"][FEN "r1b2r1k/4qp1p/p2ppb1Q/4nP2/1p1NP3/2N5/PPP4P/2KR1BR1 w - - 4 18"] 18. Nc6',
    '[Round "9"][FEN "8/5B2/6Kp/6pP/5b2/p7/1k3P2/8 b - - 3 69"] 69... Be3',
    '[Round "10"][FEN "4r1k1/q6p/2p4P/2P2QP1/1p6/rb2P3/1B6/1K4RR w - - 1 38"] 38. Qxh7+',
    '[Round "11"][FEN "6k1/3Q4/5p2/5P2/8/1KP5/PP4qp/2B5 w - - 0 99"] 99. Bg5',
    '[Round "12"][FEN "k4b1r/p3pppp/B1p2n2/3rB1N1/7q/8/PPP2P2/R2Q1RK1 w - - 1 18"] 18. c4',
    '[Round "13"][FEN "1nbk1b1r/r3pQpp/pq2P3/1p1P2B1/2p5/2P5/5PPP/R3KB1R b KQ - 0 15"] 15... Rd7',
    '[Round "14"][FEN "5r2/7k/1pPP3P/8/4p3/3p4/P4R1P/7K b - - 0 48"] 48... e3',
    '[Round "15"][FEN "rnb1kr2/pp1p1pQp/6q1/4PpB1/1P6/8/1PP2PPP/2KR3R w q - 2 15"] 15. e6',
    '[Round "16"][FEN "7k/1p1P2pp/p7/3P4/1Q5P/5pPK/PP3r2/1q5B b - - 1 37"] 37... h5',
    '[Round "17"][FEN "r2q1rk1/pp2bpp1/4p2p/2pPB2P/2P3n1/3Q2N1/PP3PP1/2KR3R w - - 1 17"] 17. Bxg7',
    '[Round "18"][FEN "r2qk2r/1b3ppp/p2p1b2/2nNp3/1R2P3/2P5/1PN2PPP/3QKB1R w Kkq - 3 17"] 17. Rxb7',
    '[Round "19"][FEN "r3kbnr/p1pp1qpp/b1n1P3/6N1/1p6/8/Pp3PPP/RNBQR1K1 b kq - 0 12"] 12... O-O-O',
    '[Round "20"][FEN "r2qkb1r/pb1p1p1p/1pn2np1/2p1p3/2P1P3/2NP1NP1/PP3PBP/R1BQ1RK1 w kq - 0 9"] 9. Nxe5',
    '');

  return $krabbePositions[rand(0, count($krabbePositions)-1)];
}

function get_pgn() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  $pgnDebugInfo = $_REQUEST["debug"];

  $pgnText = $_REQUEST["pgnText"];
  if (!$pgnText) { $pgnText = $_REQUEST["pgnTextbox"]; }
  if (!$pgnText) { $pgnText = $_REQUEST["pt"]; }

  $pgnUrl = $_REQUEST["pgnUrl"];
  if (!$pgnUrl) { $pgnUrl = $_REQUEST["pu"]; }

  if ($pgnText) {
    $pgnStatus = "PGN games from textbox input";
    $pgnTextbox = $pgnText = str_replace("\\\"", "\"", $pgnText);

    $pgnText = preg_replace("/\[/", "\n\n[", $pgnText);
    $pgnText = preg_replace("/\]/", "]\n\n", $pgnText);
    $pgnText = preg_replace("/([012\*])(\s*)(\[)/", "$1\n\n$3", $pgnText);
    $pgnText = preg_replace("/\]\s*\[/", "]\n[", $pgnText);
    $pgnText = preg_replace("/^\s*\[/", "[", $pgnText);
    $pgnText = preg_replace("/\n[\s*\n]+/", "\n\n", $pgnText);
    
    $pgnTextbox = $pgnText;

    return TRUE;
  } else if ($pgnUrl) {
    $pgnStatus = "PGN games from URL: <a href='" . $pgnUrl . "'>" . $pgnUrl . "</a>";
    $isPgn = preg_match("/\.(pgn|txt)$/i",$pgnUrl);
    $isZip = preg_match("/\.zip$/i",$pgnUrl);
    if ($isZip) {
      $zipFileString = "<a href='" . $pgnUrl . "'>zip URL</a>";
      $tempZipName = tempnam($tmpDir, "pgn4webViewer");
      $pgnUrlHandle = fopen($pgnUrl, "rb");
      $tempZipHandle = fopen($tempZipName, "wb");
      $copiedBytes = stream_copy_to_stream($pgnUrlHandle, $tempZipHandle, $fileUploadLimitBytes + 1, 0);
      fclose($pgnUrlHandle);
      fclose($tempZipHandle);
      if (($copiedBytes > 0) & ($copiedBytes <= $fileUploadLimitBytes)) {
        $pgnSource = $tempZipName;
      } else {
	$pgnStatus = "failed to get " . $zipFileString . ": file not found, file exceeds " . $fileUploadLimitText . " size limit or server error";
        if (($tempZipName) & (file_exists($tempZipName))) { unlink($tempZipName); }
        return FALSE;
      }
    } else {
      $pgnSource = $pgnUrl;
    }
  } elseif (count($_FILES) == 0) {
    $pgnStatus = "please enter chess games in PGN format&nbsp; &nbsp;<span style='color: gray;'>file and URL inputs must not exceed " . $fileUploadLimitText . "</span>";
    return FALSE;
  } elseif ($_FILES['pgnFile']['error'] == UPLOAD_ERR_OK) {
    $pgnFileName = $_FILES['pgnFile']['name'];
    $pgnStatus = "PGN games from file: " . $pgnFileName;
    $pgnFileSize = $_FILES['pgnFile']['size'];
    if ($pgnFileSize == 0) {
      $pgnStatus = "failed uploading PGN games: file not found, file empty or upload error";
      return FALSE;
    } elseif ($pgnFileSize > $fileUploadLimitBytes) {
      $pgnStatus = "failed uploading PGN games: file exceeds " . $fileUploadLimitText . " size limit";
      return FALSE;
    } else { 
      $isPgn = preg_match("/\.(pgn|txt)$/i",$pgnFileName);
      $isZip = preg_match("/\.zip$/i",$pgnFileName);
      $pgnSource = $_FILES['pgnFile']['tmp_name'];
    }
  } elseif ($_FILES['pgnFile']['error'] == (UPLOAD_ERR_INI_SIZE | UPLOAD_ERR_FORM_SIZE)) {
    $pgnStatus = "failed uploading PGN games: file exceeds " . $fileUploadLimitText . " size limit";
    return FALSE;
  } elseif ($_FILES['pgnFile']['error'] == (UPLOAD_ERR_PARTIAL | UPLOAD_ERR_NO_FILE | UPLOAD_ERR_NO_TMP_DIR | UPLOAD_ERR_CANT_WRITE | UPLOAD_ERR_EXTENSION)) {
    $pgnStatus = "failed uploading PGN games: server error";
    return FALSE;
  } else {
    $pgnStatus = "failed uploading PGN games";
    return FALSE;
  }

  if ($isZip) {
    if ($pgnUrl) { $zipFileString = "<a href='" . $pgnUrl . "'>zip URL</a>"; }
    else { $zipFileString = "zip file"; }
    $pgnZip = zip_open($pgnSource);
    if (is_resource($pgnZip)) {
      while (is_resource($zipEntry = zip_read($pgnZip))) {
	if (zip_entry_open($pgnZip, $zipEntry)) {
	  if (preg_match("/\.pgn$/i",zip_entry_name($zipEntry))) {
	    $pgnText = $pgnText . zip_entry_read($zipEntry, zip_entry_filesize($zipEntry)) . "\n\n\n";
          }
          zip_entry_close($zipEntry);
	} else {
          $pgnStatus = "failed reading " . $zipFileString . " content";
          zip_close($pgnZip);
          if (($tempZipName) & (file_exists($tempZipName))) { unlink($tempZipName); }
          return FALSE;
        }
      }
      zip_close($pgnZip);
      if (($tempZipName) & (file_exists($tempZipName))) { unlink($tempZipName); }
      if (!$pgnText) {
        $pgnStatus = "PGN games not found in " . $zipFileString;
        return FALSE;
      } else {
        return TRUE;
      }
    } else {
      $pgnStatus = "failed opening " . $zipFileString;
      return FALSE;
    }
  }

  if($isPgn) {
    if ($pgnUrl) { $pgnFileString = "<a href='" . $pgnUrl . "'>pgn URL</a>"; }
    else { $pgnFileString = "pgn file"; }
    $pgnText = file_get_contents($pgnSource, NULL, NULL, 0, $fileUploadLimitBytes + 1);
    if (!$pgnText) {
      $pgnStatus = "failed reading " . $pgnFileString . ": file not found or server error";
      return FALSE;
    }
    if ((strlen($pgnText) == 0) | (strlen($pgnText) > $fileUploadLimitBytes)) {
      $pgnStatus = "failed reading " . $pgnFileString . ": file exceeds " . $fileUploadLimitText . " size limit or server error";
      return FALSE;
    }
    return TRUE;
  } 

  if($pgnSource) {
    $pgnStatus = "only PGN and ZIP (zipped pgn) files are supported";
    return FALSE;
  }

  return TRUE;
}

function check_tmpDir() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  $tmpDirHandle = opendir($tmpDir);
  while($entryName = readdir($tmpDirHandle)) {
    if (($entryName !== ".") & ($entryName !== "..") & ($entryName !== "index.html")) {
      if ((time() - filemtime($tmpDir . "/" . $entryName)) > 3600) { 
        $unexpectedFiles = $unexpectedFiles . " " . $entryName;
      }
    }
  }
  closedir($tmpDirHandle);

  if ($unexpectedFiles) {
    $pgnDebugInfo = $pgnDebugInfo . "message for sysadmin: clean temporary directory " . $tmpDir . ":" . $unexpectedFiles; 
  }
}

function print_header() {

  print <<<END

<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"> 

<title>pgn4web games viewer</title> 

<style type="text/css">

body {
  color: black;
  background: white; 
  font-family: 'pgn4web Liberation Sans', sans-serif;
  line-height: 1.3em;
  padding: 20px;
  $bodyFontSize
}

a:link, a:visited, a:hover, a:active { 
  color: black; 
  text-decoration: none;
}

.formControl {
  font-size: smaller;
}

</style>

</head>

<body>

<table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr>
<td align="left" valign="middle"> 
<h1 name="top" style="font-family: sans-serif; color: red;"><a style="color: red;" href=.>pgn4web</a> games viewer</h1> 
</td>
<td align="right" valign="middle">
<a href=.><img src=pawns.png border=0></a>
</td>
</tr></tbody></table>

<div style="height: 1em;">&nbsp;</div>

END;
}


function print_form() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  $thisScript = $_SERVER['SCRIPT_NAME'];

  print <<<END

<script type="text/javascript">

  function setPgnUrl(newPgnUrl) {
    if (!newPgnUrl) { newPgnUrl = ""; }
    document.getElementById("urlFormText").value = newPgnUrl;
    return false;
  }

  function checkPgnUrl() {
    theObject = document.getElementById("urlFormText");
    if (theObject === null) { return false; }
    if (!theObject.value.match(/\\.(zip|pgn|txt)\$/i)) {
      alert("only PGN and ZIP (zipped pgn) files are supported");
      return false;
    }
    return (theObject.value !== "");
  }

  function checkPgnFile() {
    theObject = document.getElementById("uploadFormFile");
    if (theObject === null) { return false; }
    if (!theObject.value.match(/\\.(zip|pgn|txt)\$/i)) {
      alert("only PGN and ZIP (zipped pgn) files are supported");
      return false;
    }
    return (theObject.value !== "");
  }

  function checkPgnFormTextSize() {
    document.getElementById("pgnFormButton").title = "PGN textbox size is " + document.getElementById("pgnFormText").value.length;
    if (document.getElementById("pgnFormText").value.length == 1) {
      document.getElementById("pgnFormButton").title += " char;";
    } else {
      document.getElementById("pgnFormButton").title += " chars;";
    }
    document.getElementById("pgnFormButton").title += " $debugHelpText";
    document.getElementById("pgnFormText").title = document.getElementById("pgnFormButton").title;
  }

  function loadPgnFromForm() {
    theObjectPgnFormText = document.getElementById('pgnFormText');
    if (theObjectPgnFormText === null) { return; }
    if (theObjectPgnFormText.value === "") { return; }

    theObjectPgnText = document.getElementById('pgnText');
    if (theObjectPgnText === null) { return; }

    theObjectPgnText.value = theObjectPgnFormText.value;

    theObjectPgnText.value = theObjectPgnText.value.replace(/\\[/g,'\\n\\n[');
    theObjectPgnText.value = theObjectPgnText.value.replace(/\\]/g,']\\n\\n');
    theObjectPgnText.value = theObjectPgnText.value.replace(/([012\\*])(\\s*)(\\[)/g,'\$1\\n\\n\$3');
    theObjectPgnText.value = theObjectPgnText.value.replace(/\\]\\s*\\[/g,']\\n[');
    theObjectPgnText.value = theObjectPgnText.value.replace(/^\\s*\\[/g,'[');
    theObjectPgnText.value = theObjectPgnText.value.replace(/\\n[\\s*\\n]+/g,'\\n\\n');

    document.getElementById('pgnStatus').innerHTML = "PGN games from textbox input";
    document.getElementById('uploadFormFile').value = "";
    document.getElementById('urlFormText').value = "";

    firstStart = true;
    start_pgn4web();
    if (window.location.hash == "view") { window.location.reload(); }   
    else {window.location.hash = "view"; }  
 
    return;
  }

  function urlFormSelectChange() {
    theObject = document.getElementById("urlFormSelect");
    if (theObject === null) { return; }
  
    switch (theObject.value) {
      case "twic":
        givenTwicNumber = 765;
        epochTimeOfGivenTwic = 1246921199; // Mon July 6th, 23:59:59 GMT
        nowDate = new Date();
        epochTimeNow = nowDate.getTime() / 1000;
        twicNum = givenTwicNumber + Math.floor((epochTimeNow - epochTimeOfGivenTwic) / (60 * 60 * 24 * 7));
	setPgnUrl("http://www.chesscenter.com/twic/zips/twic" + twicNum + "g.zip");
        theObject.value = "header";
      break;

      case "nic":
	givenNicYear = 2009;
        givenNicIssue = 1;
        epochTimeOfGivenNic = 1232585999; // Jan 21st, 23:59:59 GMT
        nowDate = new Date();
	epochTimeNow = nowDate.getTime() / 1000;
        nicYear = givenNicYear + Math.floor((epochTimeNow - epochTimeOfGivenNic) / (60 * 60 * 24 * 365.25));
        nicIssue = 1 + Math.floor((epochTimeNow - (epochTimeOfGivenNic + (nicYear - givenNicYear) * (60 * 60 * 24 * 365.25))) / (60 * 60 * 24 * 365.25 / 8));
        setPgnUrl("http://www.newinchess.com/Magazine/GameFiles/mag_" + nicYear + "_" + nicIssue + "_pgn.zip");
        theObject.value = "header";
      break;

      default:
        setPgnUrl("");
        theObject.value = "header";
      break;
    }
  }

function reset_viewer() {
   document.getElementById("uploadFormFile").value = "";
   document.getElementById("urlFormText").value = "";
   document.getElementById("pgnFormText").value = "";
   checkPgnFormTextSize();
   document.getElementById("pgnStatus").innerHTML = "please enter chess games in PGN format&nbsp; &nbsp;<span style='color: gray;'>file and URL inputs must not exceed $fileUploadLimitText</span>";
   document.getElementById("pgnText").value = '$krabbeStartPosition';

   firstStart = true;
   start_pgn4web();
   if (window.location.hash == "top") { window.location.reload(); }
   else {window.location.hash = "top"; }
}

</script>

<table width="100%" cellspacing=0 cellpadding=3 border=0><tbody>

  <tr>
    <td align="left" valign="top">
      <form id="uploadForm" action="$thisScript" enctype="multipart/form-data" method="POST" style="display: inline;">
        <input id="uploadFormSubmitButton" type="submit" class="formControl" value="show games from PGN (or zipped PGN) file" style="width:100%" title="PGN and ZIP files must be smaller than $fileUploadLimitText; $debugHelpText" onClick="return checkPgnFile();">
    </td>
    <td colspan=2 width="100%" align="left" valign="top">
        <input type="hidden" name="mode" value="$mode">
        <input type="hidden" name="MAX_FILE_SIZE" value="$fileUploadLimitBytes">
        <input id="uploadFormFile" name="pgnFile" type="file" class="formControl" style="width:100%" title="PGN and ZIP files must be smaller than $fileUploadLimitText; $debugHelpText">
      </form>
    </td>
  </tr>

  <tr>
    <td align="left" valign="top">
      <form id="urlForm" action="$thisScript" method="POST" style="display: inline;">
	<input id="urlFormSubmitButton" type="submit" class="formControl" value="show games from PGN (or zipped PGN) URL" title="PGN and ZIP files must be smaller than $fileUploadLimitText; $debugHelpText" onClick="return checkPgnUrl();">
    </td>
    <td width="100%" align="left" valign="top">
        <input id="urlFormText" name="pgnUrl" type="text" class="formControl" value="" style="width:100%" onFocus="disableShortcutKeysAndStoreStatus();" onBlur="restoreShortcutKeysStatus();" title="PGN and ZIP files must be smaller than $fileUploadLimitText; $debugHelpText">
        <input type="hidden" name="mode" value="$mode">
      </form>
    </td>
    <td align="right" valign="top">
        <select id="urlFormSelect" class="formControl" title="preset the URL saving the time for downloading locally and then uploading the latest PGN from The Week In Chess or New In Chess; please note the URL of the latest issue of the online chess magazines is estimated and might occasionally need manual adjustment; please show your support to the online chess magazines visiting the TWIC website http://www.chess.co.uk/twic/twic.html and the NIC website http://www.newinchess.com" onChange="urlFormSelectChange();">
          <option value="header">preset URL</option>
          <option value="twic">latest TWIC</option>
          <option value="nic">latest NIC</option>
          <option value="clear">clear URL</option>
        </select>
    </td>
  </tr>

  <tr>
    <td align="left" valign="top">
      <form id="textForm" style="display: inline;">
        <input id="pgnFormButton" type="button" class="formControl" value="show games from PGN textbox" style="width:100%;" onClick="loadPgnFromForm();">
    </td>
    <td colspan=2 rowspan=2 width="100%" align="right" valign="bottom">
        <input type="hidden" name="mode" value="$mode">
        <textarea id="pgnFormText" class="formControl" name="pgnTextbox" rows=4 style="width:100%;" onFocus="disableShortcutKeysAndStoreStatus();" onBlur="restoreShortcutKeysStatus();" onChange="checkPgnFormTextSize();">$pgnTextbox</textarea>
      </form>
    </td>
  </tr>

  <tr>
  <td align="left" valign="bottom">
    <input id="clearButton" type="button" class="formControl" value="reset PGN viewer" onClick="if (confirm('reset PGN viewer, current games and inputs will be lost')) { reset_viewer(); }" title="reset PGN viewer, current games and inputs will be lost">
  </td>
  </tr>

</tbody></table>

END;
}

function print_chessboard() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  if ($mode == "compact") {
    $squareSize = 30;
    $pieceSize = 26;
  } else {
    $squareSize = 42;
    $pieceSize = 38;
  }

  print <<<END

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td valign=top align=left>
<a name="view"></a><div id="pgnStatus" style="font-weight: bold; margin-top: 3em; margin-bottom: 3em;">$pgnStatus</div>
</td><td valign=top align=right>
<div style="padding-top: 1em;">
&nbsp;&nbsp;&nbsp;<a href="#moves" style="color: gray; font-size: 66%;">moves</a>&nbsp;&nbsp;&nbsp;<a href="#view" style="color: gray; font-size: 66%;">board</a>&nbsp;&nbsp;&nbsp;<a href="#top" style="color: gray; font-size: 66%;">form</a>
</div>
</tr></table>

<link href="$toolRoot/fonts/pgn4web-fonts.css" type="text/css" rel="stylesheet" />
<style type="text/css">

.boardTable {
  border-style: double;
  border-color: #a0a0a0;
  border-width: 3;
}

.pieceImage {
  width: $pieceSize;
  height: $pieceSize;
}

.whiteSquare,
.blackSquare,
.highlightWhiteSquare,
.highlightBlackSquare {
  width: $squareSize;
  height: $squareSize;
  border-style: solid;
  border-width: 2;
}

.whiteSquare,
.highlightWhiteSquare {
  border-color: #ede8d5;
  background: #ede8d5;
}

.blackSquare,
.highlightBlackSquare {
  border-color: #cfcbb3;
  background: #cfcbb3;
}

.highlightWhiteSquare,
.highlightBlackSquare {
  border-color: yellow;
  border-style: solid;
}

.selectControl {
/* a "width" attribute here must use the !important flag to override default settings */
  width: 100% !important;
}

.buttonControl {
/* a "width" attribute here must use the !important flag to override default settings */
}

.buttonControlSpace {
/* a "width" attribute here must use the !important flag to override default settings */
}

.searchPgnButton {
/* a "width" attribute here must use the !important flag to override default settings */
  width: 10% !important;
}

.searchPgnExpression {
/* a "width" attribute here must use the !important flag to override default settings */
  width: 90% !important;
}

.move,
.moveOn {
  color: black;
  font-weight: normal;
  text-decoration: none;   
  font-family: 'pgn4web ChessSansUsual', 'pgn4web Liberation Sans', sans-serif;
  line-height: 1.3em;
}

.moveOn {
  background: yellow;
}

.comment {
  color: gray;
  font-family: 'pgn4web Liberation Sans', sans-serif;
  line-height: 1.3em;
}

.label {
  color: gray;
  line-height: 1.3em;
}

</style>

<link rel="shortcut icon" href="pawn.ico" />

<script src="pgn4web.js" type="text/javascript"></script>
<script type="text/javascript">
  SetImagePath("merida/$pieceSize"); 
  SetImageType("png");
  SetHighlightOption(true); 
  SetCommentsIntoMoveText(true);
  SetCommentsOnSeparateLines(true);
  SetInitialGame(1); 
  SetInitialHalfmove(0);
  SetGameSelectorOptions(" Event         Site          Rd  White            Black            Res  Date", true, 12, 12, 2, 15, 15, 3, 10);
  SetAutostartAutoplay(false);
  SetAutoplayDelay(2000);
  SetShortcutKeysEnabled(true);

  function customFunctionOnPgnTextLoad() { 
    document.getElementById('numGm').innerHTML = numberOfGames; 
  }
  function customFunctionOnPgnGameLoad() {
    document.getElementById('currGm').innerHTML = currentGame+1;
    document.getElementById('numPly').innerHTML = PlyNumber;
  }
  function customFunctionOnMove() { 
    document.getElementById('currPly').innerHTML = CurrentPly; 
  }
</script>

<!-- paste your PGN below and make sure you dont specify an external source with SetPgnUrl() -->
<form style="display: inline"><textarea style="display:none" id="pgnText">

$pgnText

</textarea></form>
<!-- paste your PGN above and make sure you dont specify an external source with SetPgnUrl() -->

<table width=100% cellspacing=0 cellpadding=5>

END;

  if ($mode != "compact") print <<<END

  <tr valign=bottom>
    <td align="center" colspan=2>

      <div id="GameSelector"></div>

      <div id="GameSearch"></div>

      <div style="padding-top: 1em;">&nbsp;</div>

    </td>
  </tr>

END;

  print <<<END

  <tr valign=top>
    <td valign=top align=center width=50%>
      <span id="GameBoard"></span> 
      <p></p>
      <div id="GameButtons"></div> 
    </td>
    <td valign=top align=left width=50%>

      <span class="label">Date:</span> <span id="GameDate"></span> 
      <br>
      <span class="label">Site:</span> <span style="white-space: nowrap;" id="GameSite"></span> 
      <br>
      <span class="label">Event:</span> <span style="white-space: nowrap;" id="GameEvent"></span> 
      <br>
      <span class="label">Round:</span> <span id="GameRound"></span> 
      <p></p>

      <span class="label">White:</span> <span style="white-space: nowrap;" id="GameWhite"></span> 
      <br>
      <span class="label">Black:</span> <span style="white-space: nowrap;" id="GameBlack"></span> 
      <br>
      <span class="label">Result:</span> <span id="GameResult"></span> 
      <p></p>

      <span class="label">game:</span> <span id=currGm>0</span> (<span id=numGm>0</span>)
      <br>
      <span class="label">ply:</span> <span id=currPly>0</span> (<span id=numPly>0</span>)
      <br>
      <span class="label">Side to move:</span> <span id="GameSideToMove"></span> 
      <br>
      <span class="label">Last move:</span> <span class="move"><span id="GameLastMove"></span></span> 
      <br>
      <span class="label">Next move:</span> <span class="move"><span id="GameNextMove"></span></span> 
      <p></p>

      <span class="label">Move comment:</span><br><span id="GameLastComment"></span> 
      <p></p>

    </td>
  </tr>
</table>

<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td valign=bottom align=right>
&nbsp;&nbsp;&nbsp;<a name="moves" href="#moves" style="color: gray; font-size: 66%;">moves</a>&nbsp;&nbsp;&nbsp;<a href="#view" style="color: gray; font-size: 66%;">board</a>&nbsp;&nbsp;&nbsp;<a href="#top" style="color: gray; font-size: 66%;">form</a>
</tr></table>

END;
 
  if ($mode != "compact") print <<<END

<table width=100% cellspacing=0 cellpadding=5>
  <tr>
    <td colspan=2>
      <div style="padding-top: 2em; padding-bottom: 1em; text-align: justify;" id="GameText"></div>
    </td>
  </tr>
</table>

END;
}

function print_footer() {

  global $pgnText, $pgnTextbox, $pgnUrl, $pgnFileName, $pgnFileSize, $pgnStatus, $tmpDir, $debugHelpText, $pgnDebugInfo;
  global $fileUploadLimitText, $fileUploadLimitBytes, $krabbeStartPosition, $goToView, $mode;

  if ($goToView) { $hashStatement = "window.location.hash = 'view';"; }
  else { $hashStatement = ""; }
  print <<<END

<div>&nbsp;</div>
<table width=100% cellpadding=0 cellspacing=0 border=0><tr><td valign=bottom align=left>
<div style="color: gray; margin-top: 1em; margin-bottom: 1em;">$pgnDebugInfo</div>
</td><td valign=bottom align=right>
&nbsp;&nbsp;&nbsp;<a href="#moves" style="color: gray; font-size: 66%;">moves</a>&nbsp;&nbsp;&nbsp;<a href="#view" style="color: gray; font-size: 66%;">board</a>&nbsp;&nbsp;&nbsp;<a href="#top" style="color: gray; font-size: 66%;">form</a>
</tr></table>

<script type="text/javascript">

function new_start_pgn4web() {
  setPgnUrl("$pgnUrl");
  checkPgnFormTextSize();
  start_pgn4web();
  $hashStatement
}

window.onload = new_start_pgn4web;

</script>


<!-- start of google analytics code -->

<!-- end of google analytics code -->


</body>

</html>

END;
}

?>
