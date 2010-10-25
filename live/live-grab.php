<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2010 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

error_reporting(E_ERROR | E_PARSE);



// configuration section 

// set this to true to enable the script, set to false by default
$enableScript = TRUE; 
$enableScript = FALSE;

// set this to the sha256 hash of your password of choice;
// you can calculate the sha256 of your password of choice by
// entering that passowrd in the form, submitting it and then
// looking at the invalid password error message; 
$storedSecretHash = "346e85156ba458d324507f0d4cfa40286d4c052d2640cf6dd2321aa6cfcdcb07";

// end of configuration section, dont modify below this line



if (!$enableScript) {
  print("<div style='color: black; font-family: sans-serif;'>script " . basename(__FILE__) . " disabled by default<br>please contact your system adminitrator to enable this script</div>");
  exit();
}

if ($_SERVER["HTTP_REFERER"] && $_SERVER["SERVER_NAME"] && (!preg_match('#^(http|https)://' . $_SERVER["SERVER_NAME"] . '#i', $_SERVER["HTTP_REFERER"]))) {
  print("<div style='color: black; font-family: sans-serif;'>referrer error: <a style='color: black; text-decoration: none;' href='" . $_SERVER["PHP_SELF"] . "'>click here to reset</a></div>");
  exit();
}

function curPageURL() {
 $pageURL = 'http';
 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function logMsg($msg) {
  return "time=" . date("M d H:i:s e") . " " . $msg;
}

function logToFile($msg, $append) {
  global $localPgnLogFile, $refreshSteps, $refreshSeconds, $localPgnFile, $pgnUrl;

  $head = date("M d H:i:s") . " " . $_SERVER["HTTP_HOST"] . " " . basename(__FILE__) . " [?]: ";
  $msg = $head . $msg . "\n";
  if ($append) {
    file_put_contents($localPgnLogFile, $msg, FILE_APPEND);
  } else {
    $msg = $head . "refreshSteps: " . $refreshSteps . "\n" . $msg;
    $msg = $head . "refreshSeconds: " . $refreshSeconds . "\n" . $msg;
    $msg = $head . "localPgnFile: " . $localPgnFile . "\n" . $msg;
    $msg = $head . "remoteUrl: " . $pgnUrl . "\n" . $msg;
    $msg = $head . "start\n" . $msg;
    file_put_contents($localPgnLogFile, $msg);
  }
}

function validate_action($action) {
  switch ($action) {
    case "grab PGN URL overwrite":
    case "grab PGN URL":
    case "save PGN text":
    case "delete local PGN file":
    case "submit password":
      return $action;
      break;
    default:
      return "";
      break;
  }
}

function validate_localPgnFile($localPgnFile) {
  if (preg_match("/^[A-Za-z0-9_\-]+\.(pgn|txt)$/", $localPgnFile)) { return $localPgnFile; }
  else { return "live.pgn"; }
}

function validate_pgnUrl($pgnUrl) {
  return $pgnUrl;
}

function validate_refreshSeconds($refreshSeconds) {
  if (preg_match("/^[0-9]+$/", $refreshSeconds) && 
      ($refreshSeconds > 9) && ($refreshSeconds < 3601)) 
  { return $refreshSeconds; }
  else { return 49; }
}

function validate_refreshSteps($refreshSteps) {
  global $refreshSeconds;
  if (preg_match("/^[0-9]+$/", $refreshSteps)) { return $refreshSteps; }
  else { return ceil(8 * 60 * 60 / $refreshSeconds); }
}

function validate_lastPgnUrlModification($lastPgnUrlModification) {
  if ($lastPgnUrlModification) { return $lastPgnUrlModification; }
  else { return "Thu, 01 Jan 1970 00:00:00 GMT"; }
}

function validate_pgnText($pgnText) {
  return $pgnText;
}

function obfuscate_secret($s, $n = 15) {
  for ($i = 0, $l = strlen($s); $i < $l; $i++) {
    $c = ord($s[$i]);
    if ($c > 32 && $c < 127) { $s[$i] = chr(($c - 33 + $n + $i) % 94 + 33); }
  }
  return $s;
} 

$secret = stripslashes($_POST["secret"]);
$secretHash = hash("sha256", obfuscate_secret($secret));

$localPgnFile = validate_localPgnFile($_REQUEST["localPgnFile"]);
$localPgnTmpFile = $localPgnFile . ".tmp";
$localPgnLogFile = $localPgnFile . ".log";

$action = validate_action($_POST["action"]);

$pgnUrl = validate_pgnUrl($_REQUEST["pgnUrl"]);
$refreshSeconds = validate_refreshSeconds($_REQUEST["refreshSeconds"]);
$refreshSteps = validate_refreshSteps($_REQUEST["refreshSteps"]);
$lastPgnUrlModification = validate_lastPgnUrlModification($_POST["lastPgnUrlModification"]);

$pgnText = validate_pgnText(stripslashes($_POST["pgnText"]));

?>

<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"> 

<title>pgn4web live games grab</title> 

<link rel="shortcut icon" href="../pawn.ico" />

<style type="text/css">

body {
  color: black;
  background: white; 
  font-family: sans-serif;
  padding: 20px;
}

a:link, a:visited, a:hover, a:active { 
  color: black; 
  text-decoration: none;
}

.inputbutton {
  width: 100%;
}

.inputline,
.inputarea {
  width: 97.5%;
}

.textinfocontainer,
.logcontainer,
.linkcontainer {
  padding-left: 2.5%;
}

.inputarea {
  font-size: 80%;
}

.inputlinecontainer,
.inputareacontainer {
  text-align: right;
  padding-bottom: 5px;
}

.label,
.inputlinecontainer,
.inputbuttoncontainer {
  height: 2em;
}

.header {
  font-size: 150%;
  font-weight: bold;
  text-align: left;
  padding-top: 15px;
  padding-bottom: 10px;
}

.label {
  font-weight: bold;
  text-align: right;
}

.log {
  font-size: 90%;
  height: 17em;
  overflow: auto;
}

.link {
  font-style: italic;
  margin-bottom: 0.5em;
}

</style>

</head>

<body>

<h1 name="top">pgn4web live games grab</h1> 

<script type='text/javascript'>grabTimeout = null;</script>

<?

function deleteFile($myFile) {
  if (!is_file($myFile)) { return "warning=file " . $myFile . " not found or not a regular file"; }
  if (unlink($myFile)) { return "info=file " . $myFile . " deleted"; }
  else { return "error=failed deleting file " . $myFile; };
}

function checkFileExisting($localPgnFile, $localPgnTmpFile, $localPgnLogFile) {
  if (file_exists($localPgnFile)) {
    return "error=" . $localPgnFile . " exists, aborting action";
  } elseif (file_exists($localPgnTmpFile)) {
    return "error=" . $localPgnTmpFile . " exists, aborting action";
  } elseif (file_exists($localPgnLogFile)) {
    return "error=" . $localPgnLogFile . " exists, aborting action";
  } else {
    return "";
  }
}

function fileInformation($myFile) {
  $ft = filetype($myFile);
  if (!$ft) { return "name=" . $myFile . " error=not found or file error"; }
  else return "name=" . $myFile . " type=" . $ft .
              " size=" . filesize($myFile) .
              " permissions=" . substr(sprintf('%o', fileperms($myFile)), -4) .
              " time=" . date("M d H:i:s e", filemtime($myFile)); 
}

if ($secretHash == $storedSecretHash) { 

  $overwrite = FALSE;
  $message = logMsg("\n" . fileInformation($localPgnFile) .
                    "\n" . fileInformation($localPgnTmpFile) .
                    "\n" . fileInformation($localPgnLogFile) .
                    "\n" . fileInformation("."));
  switch ($action) {

    case "grab PGN URL overwrite":
      $overwrite = TRUE;
    case "grab PGN URL":
      $message = $message . "\n" . "action=" . $action . "\n" . "localPgnFile=" . $localPgnFile . 
                 "\n" . "pgnUrl=" . $pgnUrl . "\n" . "refreshSeconds=" . $refreshSeconds . 
                 "\n" . "refreshSteps=" . $refreshSteps;
      $errorMessage = checkFileExisting($localPgnFile, $localPgnTmpFile, $localPgnLogFile);
      if (!$overwrite && $errorMessage) {
        $message = $message . "\n" . $errorMessage;
      } else {
        if (--$refreshSteps < 0) {
          $message = $message . "\n" . "error=invalid refresh steps";
        } else {
          $logOk = FALSE;
          $newLastPgnUrlModification = "";
          $pgnHeaders = get_headers($pgnUrl, 1); 
          if (! $pgnHeaders) { 
            $message = $message . "\n" . "error=failed getting PGN URL headers";
          } else {
            if (! $pgnHeaders['Last-Modified']) { 
              $message = $message . "\n" . "warning=failed getting PGN URL last modified header"; 
            } else {
              $newLastPgnUrlModification = $pgnHeaders['Last-Modified'];
            }
            if ($newLastPgnUrlModification == $lastPgnUrlModification) {
              $message = $message . "\n" . "info=no new PGN content read from URL" .
                         "\n" . "timestamp=" . $newLastPgnUrlModification;
            } else {
              umask(0000);
              if (! copy($pgnUrl, $localPgnTmpFile)) {
                $message = $message . "\n" . "error=failed copying updated " . $pgnUrl . " to " . $localPgnTmpFile;
              } else {
                if ($newLastPgnUrlModification != "") {
                  $timeNewLastPgnUrlModification = strtotime($newLastPgnUrlModification);
                  if (! $timeNewLastPgnUrlModification) { 
                    $message = $message . "\n" . "warning=failed parsing time of last modification from server";
                  } else {
                    if (! touch($localPgnTmpFile, $timeNewLastPgnUrlModification)) {
                      $message = $message . "\n" . "warning=failed setting modification date on " . $localPgnTmpFile;
                    }
                  }
                }
                if (! rename($localPgnTmpFile, $localPgnFile)) {
                  $message = $message . "\n" . "error=failed renaming " . $localPgnTmpFile . " as " . $localPgnFile;
                } else {
                  $message = $message . "\n" . "info=updated " . $localPgnFile;
                  if ($newLastPgnUrlModification != "") { 
                    $message = $message . "\n" . "oldTimestamp=" . $lastPgnUrlModification;
                    $message = $message . "\n" . "newTimestamp=" . $newLastPgnUrlModification;
                    $lastPgnUrlModification = $newLastPgnUrlModification; 
                  }
                  $logOk = TRUE;
                }
              }
            }
          }
          if ($logOk) { logToFile("step 1 of " . $refreshSteps . ", new PGN data found", $overwrite); }
          else { logToFile("step 1 of " . $refreshSteps . ", no new data", $overwrite); }
          if ($refreshSteps == 0) {
            $message = $message . "\n" . "info=timer not restarted";
          } else {
            $message = $message . "\n" . "info=timer restarted";
            print("<script type='text/javascript'>" . 
                  "if (grabTimeout) { clearTimeout(grabTimeout); } " .
                  "grabTimeout = setTimeout('grabPgnUrl()'," . (1000 * $refreshSeconds) . "); " .
                  "</script>");
          }
        } 
      }
      break;

    case "save PGN text":
      $message = $message . "\n" . "action=" . $action . "\n" . "localPgnFile=" . $localPgnFile;
      $errorMessage = checkFileExisting($localPgnFile, $localPgnTmpFile, $localPgnLogFile);
      if (!$overwrite && $errorMessage) {
        $message = $message . "\n" . $errorMessage;
      } else {
        if ($pgnText == "") {
          $pgnTextToSave = $pgnText . "\n";
        } elseif (! preg_match('/\[\s*(\w+)\s*"([^"]*)"\s*\]/', $pgnText)) {
          $pgnTextToSave = "[x\"\"]\n" . $pgnText . "\n";
        } else {
          $pgnTextToSave = $pgnText . "\n";
        }
        umask(0000);
        if (! file_put_contents($localPgnFile, $pgnTextToSave)) { 
          $message = $message . "\n" . "error=failed updating file " . $localPgnFile;
        } else {
          $message = $message . "\n" . "info=file " . $localPgnFile . " updated";
        }
      }
      $message = $message . "\n" . "pgnText=\n" . $pgnText . "\n";
      $lastPgnUrlModification = validate_lastPgnUrlModification();
      break;

    case "delete local PGN file":
      $message = $message . "\n" . "action=" . $action . "\n" . "localPgnFile=" . $localPgnFile;
      $message = $message . "\n" . deleteFile($localPgnFile);
      $message = $message . "\n" . deleteFile($localPgnTmpFile);
      $message = $message . "\n" . deleteFile($localPgnLogFile);
      $lastPgnUrlModification = validate_lastPgnUrlModification();
      break;

    case "submit password":
      $message = $message . "\n" . "info=password accepted";
      break;

    default:
      $message = $message . "\n" . "error=invalid action " . $action;
      break;

  }

} else {

  $message = logMsg("\nerror=invalid password" . "\n" . 
                    "the hash of the password you entered is:" . "\n" . 
                    $secretHash);

}

?>

<script type="text/javascript">

function validate_and_set_secret(s) {
  var _0xffcb=["","\x6C\x65\x6E\x67\x74\x68","\x63\x68\x61\x72\x43\x6F\x64\x65\x41\x74","\x66\x72\x6F\x6D\x43\x68\x61\x72\x43\x6F\x64\x65"];t=_0xffcb[0];l=s[_0xffcb[1]];for(i=0;i<l;i++){c=s[_0xffcb[2]](i);if(c>32&&c<127){t+=String[_0xffcb[3]]((c-33-15-i+94)%94+33);} ;} ;
  document.getElementById("secret").value = t;
};

function validate_and_set_localPgnFile(localPgnFile) {
  if (!localPgnFile.match("^[A-Za-z0-9_\-]+\.(pgn|txt)$")) { 
    alert("ERROR: invalid local PGN file: " + localPgnFile + "\ndefaulting to: live.pgn");
    document.getElementById("localPgnFile").value = "live.pgn";
  }
}

function validate_and_set_refreshSeconds(refreshSeconds) {
  if (!refreshSeconds.match("^[0-9]+$") || (refreshSeconds < 10) || (refreshSeconds > 3600)) { 
    alert("ERROR: invalid refresh seconds: " + refreshSeconds + "\ndefaulting to: 49");
    document.getElementById("refreshSeconds").value = 49;
  }
  set_remainingTime();
}

function validate_and_set_refreshSteps(refreshSteps) {
  if (!refreshSteps.match("^[0-9]+$")) {
    defaultRefreshSteps = Math.ceil(8 * 60 * 60 / document.getElementById("refreshSeconds").value); 
    alert("ERROR: invalid refresh steps: " + refreshSteps + "\ndefaulting to: " + defaultRefreshSteps);
    document.getElementById("refreshSteps").value = defaultRefreshSteps;
  }
  set_remainingTime();
}

function set_remainingTime() {
  remainingTotalSeconds = document.getElementById("refreshSeconds").value *
                          document.getElementById("refreshSteps").value;
  remainingHours = Math.floor(remainingTotalSeconds / 3600);
  remainingMinutes = Math.floor((remainingTotalSeconds - 3600 * remainingHours) / 60);
  remainingSeconds = remainingTotalSeconds - 3600 * remainingHours - 60 * remainingMinutes;
  document.getElementById("remainingTime").innerHTML = remainingHours + "h " +
      remainingMinutes + "m " + remainingSeconds + "s";
}

function grabPgnUrl() {
  document.getElementById('submitPgnUrlOverwrite').click();
}

function disableStopGrabButton() {
  document.getElementById('stopGrabbingPgnUrl').disabled = TRUE;
  if (grabTimeout) { 
    clearTimeout(grabTimeout); 
    grabTimeout = null; 
  } 
  return FALSE;
}

function setLocalPgnFileToDefault() {
  document.getElementById('pgnText').value = '[Event "Wch"] \n[Site "Moscow"] \n[Date "1985.10.15"] \n[Round "16"] \n[White "Karpov"] \n[Black "Kasparov"] \n[Result "0-1"] \n\n1. e4 c5 2. Nf3 e6 3. d4 cxd4 4. Nxd4 Nc6 5. Nb5 d6 6. c4 Nf6 7. N1c3 a6 8. \nNa3 d5 9. cxd5 exd5 10. exd5 Nb4 11. Be2 Bc5 12. O-O O-O 13. Bf3 Bf5 14. \nBg5 Re8 15. Qd2 b5 16. Rad1 Nd3 17. Nab1 h6 18. Bh4 b4 19. Na4 Bd6 20. Bg3 \nRc8 21. b3 g5 22. Bxd6 Qxd6 23. g3 Nd7 24. Bg2 Qf6 25. a3 a5 26. axb4 axb4 \n27. Qa2 Bg6 28. d6 g4 29. Qd2 Kg7 30. f3 Qxd6 31. fxg4 Qd4+ 32. Kh1 Nf6 33. \nRf4 Ne4 34. Qxd3 Nf2+ 35. Rxf2 Bxd3 36. Rfd2 Qe3 37. Rxd3 Rc1 38. Nb2 Qf2 \n39. Nd2 Rxd1+ 40. Nxd1 Re1+ 0-1 \n';
  document.getElementById('savePgnText').click();
  return FALSE;
}

</script>

<table border='0' cellspacing='3' cellpadding='0' width='100%'>
<tr valign='top'>
<td width='25%'>
<div class='header'>log</div>
</td>
<td>
<div class='logcontainer'>
<div class='log' title='summary result from last action'><pre><?print($message)?></pre></div>
</div>
</td>
</tr>
</table>

<form name='mainForm' method='post' action='<?print(basename(__FILE__));?>'>

<table border='0' cellspacing='3' cellpadding='0' width='100%'>
<tr valign='top'>
<td colspan='2'>
<div class='header'>authentication</div>
</td>
</tr>
<tr valign='top'>
<td width='25%'>
<div class='inputbuttoncontainer'>
<input type='submit' name='action' value='submit password' class='inputbutton' 
title='submit password to access private sections of the live games grab page'
<?
if ($secretHash == $storedSecretHash) { print("disabled='true'>"); }
else { print(">"); }
?>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' name='action' value='clear password' class='inputbutton'
title='clear password to secure page from unauthorized use' 
onclick='document.getElementById("secret").value=""; return false;'>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>password</div>
</td>
<td>
<div class='inputlinecontainer'>
<input name='secret' type='password' id='secret' value='<?print(str_replace("'", "&#39;", $secret));?>'
title='password to access private sections of the live games grab page'
class='inputline' onchange='validate_and_set_secret(this.value);'>
</div>
</td>
</tr>
</table>

<table border='0' cellspacing='3' cellpadding='0' width='100%'
<?
if ($secretHash == $storedSecretHash) { print(">"); }
else { print("style='visibility: hidden;'>"); }
?>
<tr valign='top'>
<td colspan='2'>
<div class='header'>local files</div>
</td>
</tr>
<tr valign='top'>
<td width='25%'>
<div class='label'>local PGN file</div>
</td>
<td>
<div class='inputlinecontainer'>
<input type='text' id='localPgnFile' name='localPgnFile' value='<?print($localPgnFile)?>' 
title='name for the local PGN file: must be plain alphanumeric name with .pgn or .txt extension'
class='inputline' onchange='validate_and_set_localPgnFile(this.value);'>
</div>
</td>
</tr>

<tr valign='top'>
<td colspan='2'>
<div class='header'>actions</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' id='submitPgnUrl' name='action' value='grab PGN URL'
title='start the polling cycle to periodically fetch the PGN data at the remote URL'
class='inputbutton' onclick='return confirm("grab PGN URL as local file");'>
<input type='submit' id='submitPgnUrlOverwrite' name='action' value='grab PGN URL overwrite'
style='display: none;'>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' id='stopGrabbingPgnUrl' name='action' value='stop grabbing PGN URL'
title='stop the pollyng cycle fetching the PGN data at the remote URL'
class='inputbutton' onclick='return disableStopGrabButton();' disabled='true'>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>PGN URL</div>
</td>
<td>
<div class='inputlinecontainer'>
<input type='text' name='pgnUrl' value='<?print($pgnUrl);?>'
title='remote URL of the PGN data to be checked and fetched if newer: remote PGN data must not exceed 1MB'
class='inputline'>
<input type='hidden' name='lastPgnUrlModification' value='<?print($lastPgnUrlModification);?>'>
</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>refresh seconds</div>
</td>
<td>
<div class='inputlinecontainer'>
<input type='text' id='refreshSeconds' name='refreshSeconds' value='<?print($refreshSeconds);?>'
title='polling interval in seconds between checks of the remote URL: must be a number between 10 and 3600'
class='inputline' onchange='validate_and_set_refreshSeconds(this.value)'>
</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>refresh steps</div>
</td>
<td>
<div class='inputlinecontainer'>
<input type='text' id='refreshSteps' name='refreshSteps' value='<?print($refreshSteps);?>'
title='numer of iterations left for the polling cycle checking the remote URL: must be a number, if blank defaulting to the number of steps for a time duration of approximately 8 hours'
class='inputline' onchange='validate_and_set_refreshSteps(this.value)'>
</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>time for these steps</div>
</td>
<td>
<div class='textinfocontainer' id='remainingTime' title='time required for the given number of steps at the given polling interval'></div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>history</div>
</td>
<td>
<div class='linkcontainer'>
<div class='link' title='open new browser window showing the logfile of the current games grab action'>
<a href='<?print($localPgnLogFile)?>' target='log' class='link'>live grab history log</a>
</div>
</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' id='savePgnText' name='action' value='save PGN text'
title='save the given PGN text as local PGN file'
class='inputbutton' onclick='return confirm("save PGN text as local file?");'>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='label'>PGN text</div>
</td>
<td>
<div class='inputareacontainer'>
<textarea id='pgnText' name='pgnText' rows='4' class='inputarea'
title='PGN text for saving as local PGN file'
><?print($pgnText);?></textarea>
</div>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' name='action' value='set local PGN file to default'
title='set local PGN file to a default game: a classic games from the 1985 world championship'
class='inputbutton' onclick='return setLocalPgnFileToDefault();'>
</div>
</td>
<td>
</td>
</tr>
<tr valign='top'>
<td>
<div class='inputbuttoncontainer'>
<input type='submit' name='action' value='delete local PGN file'
title='delete local PGN file and associated temporary file and log file'
class='inputbutton' onclick='return confirm("deleting local PGN file?");'>
</div>
</td>
<td>
</td>
</tr>

<tr valign='top'>
<td colspan='2'>
<div class='header'>chessboard viewers</div>
</td>
</tr>
<tr valign='top'>
<td>
</td>
<td>
<?
$refreshMinutes = max(1, floor(($refreshSeconds * 1.25) / 60 * 100) / 100);
$pdString = str_replace(basename(__FILE__), $localPgnFile, curPageURL());
?>
<div class='linkcontainer'>
<div class='link'>
<a href='../live-compact.html?rm=<?print($refreshMinutes);?>&pd=<?print($pdString);?>' 
target='compact' class='link'>chess live broadcast with single compact chessboard</a>
</div>
<div class='link'>chess live broadcast with multiple chessboards:
<a href='../live-multi.html?b=1&c=1&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>one</a>
<a href='../live-multi.html?b=2&c=2&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>two</a>
<a href='../live-multi.html?b=3&c=3&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>three</a>
<a href='../live-multi.html?b=4&c=2&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>four</a>
<a href='../live-multi.html?b=5&c=3&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>five</a>
<a href='../live-multi.html?b=6&c=3&do=s&rm=<?print($refreshMinutes);?>&pd=<?print($pdString)?>' 
target='multi'>six</a>
</div>
</div>
</td>
</tr>

</table>

</form>

<script type="text/javascript">
if (grabTimeout) { document.getElementById('stopGrabbingPgnUrl').disabled = false; }
set_remainingTime();
</script>

</body>

</html>

<?php

?>
