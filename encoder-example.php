<?php

/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2010 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

include "pgn-encoder.php";

$pgnText = $_REQUEST["pgnText"];
if (!$pgnText) { $pgnText = $_REQUEST["pgnTextbox"]; }
if (!$pgnText) { $pgnText = $_REQUEST["pt"]; }

if ($pgnText) {
  $pgnTextbox = $pgnText = str_replace("\\\"", "\"", $pgnText);

  $pgnText = preg_replace("/\[/", "\n\n[", $pgnText);
  $pgnText = preg_replace("/\]/", "]\n\n", $pgnText);
  $pgnText = preg_replace("/([012\*])(\s*)(\[)/", "$1\n\n$3", $pgnText);
  $pgnText = preg_replace("/\]\s*\[/", "]\n[", $pgnText);
  $pgnText = preg_replace("/^\s*\[/", "[", $pgnText);
  $pgnText = preg_replace("/\n[\s*\n]+/", "\n\n", $pgnText);
} else { 
  $pgnTextbox = $pgnText = <<<END

[White "?"]
[Black "?"]
[Result "?"]
[Date "?"]
[Event "?"]
[Site "?"]
[Round "?"]

{please enter your PGN games in the textbox and then click the button}

END;
}
$pgnEncoded = EncodePGN($pgnText);

$thisScript = $_SERVER['SCRIPT_NAME'];

print <<<END

<html>

<head>

<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1"> 

<title>pgn4web PGN encoder/decoder php example</title> 

<link rel="shortcut icon" href="pawn.ico" />

</head>

<body style="font-family: sans-serif;">

<h1>pgn4web PGN encoder/decoder php example</h1>

<center>

<iframe src="board.html?am=l&d=1000&ss=26&ps=d&pf=d&lcs=YeiP&dcs=Qcij&bbcs=D91v&hm=n&hcs=Udiz&bd=s&cbcs=YeiP&ctcs=\$\$\$\$&hd=j&md=j&tm=13&fhcs=\$\$\$\$&fhs=80p&fmcs=\$\$\$\$&fccs=v71\$&hmcs=Qcij&fms=80p&fcs=m&cd=i&bcs=____&fp=13&hl=t&fh=b&fw=p&pe=$pgnEncoded" 
 height="312" width="900" frameborder="0" scrolling="no" marginheight="0" marginwidth="0">
your web browser and/or your host do not support iframes as required to display the chessboard
</iframe>

<form action="$thisScript" method="POST">
<input type="submit" style="width:900px;" value="pgn4web PGN encoder/decoder php example"> 
<textarea id="pgnTextbox" name="pgnTextbox" style="height:300px; width:900px;">$pgnTextbox</textarea>
</form>

</center>

</body>

</html>

END;

?>
