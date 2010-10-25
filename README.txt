#
#  pgn4web javascript chessboard
#  copyright (C) 2009, 2010 Paolo Casaschi
#  see README file and http://pgn4web.casaschi.net
#  for credits, license and more details
#

PGN4WEB
display chess games on web pages using javascript

ABOUT

PGN4WEB is a javascript tool showing chess PGN games as a graphical
chessboard in websites and blogs. 

It comes as software package you can to your website or blogsite, but also
provides a very simple board generator web tool helping you adding chess 
games to your website or blog without any html/javascript coding from your 
side.
 
It has been designed to make it easier adding chess games to your web 
pages and blogs, without the need of much knowledge of HTML or any 
other technicality. It also has been integrated with many popular web
software platforms and services.

***
*** THIS README FILE IS A SUMMARY OF THE PGN4WEB DOCUMENTATION
*** MORE EXTENSIVE AND UPDATED DOCUMENTATION IS AVAILABLE
*** FROM THE PGN4WEB SUPPORT WIKI REACHABLE FROM http://pgn4web.casaschi.net
*** PLEASE RELY ON THE SUPPORT WIKI RATHER THAN THIS README ONLY
***

Project homepage: http://pgn4web.casaschi.net (including downloads and wiki)
Contact email: pgn4web@casaschi.net

Features:
- display chess games form a PGN file on a dynamic chessboard on your
  webpage or blog
- shortcut keys for navigating through the game, for selecting the
  game from the file and much more; also uses chessboard squares as 
  input buttons (hit escape for help)
- fully customizable display. Each item (board, button bar, game
  selection menu, PGN header info, game text, game comment and more)
  can be displayed (or hidden) at pleasure in your html file
- support different chess bitmaps (even custom bitmaps) and different
  chessboard sizes
- provides a very simple board generator web tool helping you adding chess 
  games to your website or blog without any html/javascript coding (see 
  http://pgn4web-board-generator.casaschi.net
- integrated with popular blog platforms (such as blogger and wordpress), 
  portal platforms (such as joomla, drupal and wikimedia), forum platforms
  (such as phpBB, simplemachines and vbulletin) and hosting services (such 
  as google sites)
- allows for live broadcast of games

Limitations:
- variations are displayed as comment, it's not possible to display
  in the chessboard positions from variations
- only one chessboard for html file (use frames if you need to display
  more in the same view)

Bugs: 
- if square brackets are present in the comments (or the game text) in a
pattern similar to the PGN header pattern '[tag "value"]' then the game 
parsing might break. If your PGN file is not displayed properly, please 
check for characters "[" and "]" in the comments and try removing them. 
Please email me for review at pgn4web@casaschi.net any PGN file that 
pgn4web fails parsing correctly.  

Enjoy!


DEBUGGING

Errors alert messages are logged by pgn4web, such as failure to load PGN
data, incorrect PGN games or incorrect FEN strings. 
When an error is encountered, the top left chessboard square will flash
to signal the exception. 
The error alert log can be reviewed clicking on the same top left 
chessboard square.


HOW TO USE pgn4we.js

add a SCRIPT instance at the top of your HTML file:

  <script src="pgn4web.js" type="text/javascript"></script>

The PGN input can be specified either as URL within another SCRIPT instance 
with at least the call to 

  SetPgnUrl("http://yoursite/yourpath/yourfile.pgn")

and optionally any of the other calls listed below.  

Or the PGN file can be pasted in the body of the HTML file 
within a hidden FORM/TEXTAREA statement with the ID pgnText:

  <!-- paste your PGN below and make sure you dont specify an external source with SetPgnUrl() -->
  <form style="display: none;"><textarea style="display: none;" id="pgnText">

  ... your PGN text ...

  </textarea></form>
  <!-- paste your PGN above and make sure you dont specify an external source with SetPgnUrl() -->

Example:

  <script type="text/javascript>

    SetPgnUrl("http://yoursite/yourpath/yourfile.pgn"); // if set, this has precedence over the inline PGN in the HTML file
    SetImagePath(""); // use "" path if images are in the same folder as this javascript file
    SetImageType("png");
    SetHighlightOption(true); // true or false
    SetGameSelectorOptions(" ...", false, 0, 0, 0, 15, 15, 0, 10); // (head, num, chEvent, chSite, chRound, chWhite, chBlack, chResult, chDate);
    SetCommentsIntoMoveText(false);
    SetCommentsOnSeparateLines(false);
    SetAutoplayDelay(1000); // milliseconds
    SetAutostartAutoplay(false);
    SetAutoplayNextGame(false); // if set, move to the next game at the end of the current game during autoplay
    SetInitialGame(1); // number of game to be shown at load, from 1 (default); values (keep the quotes) of "first", "last", "random" are accepted; other string values assumed as PGN search string
    SetInitialHalfmove(0,false); // halfmove number to be shown at load, 0 (default) for start position; values (keep the quotes) of "start", "end", "random" and "comment" (go to first comment) are also accepted. Second parameter if true applies the setting to every selected game instead of startup only
    SetShortcutKeysEnabled(false);

    SetLiveBroadcast(0.25, true, true); // set live broadcast; parameters are delay (refresh delay in minutes, 0 means no broadcast, default 0) alertFlag (if true, displays debug error messages, default false) demoFlag (if true starts broadcast demo mode, default false)

  </script>
 
Then the script will automagically add content into your HTML file 
to any <div> or <span> containers with the following IDs:

  <div id="GameSelector"></div>
  <div id="GameSearch"></div>
  <div id="GameLastMove"></div>
  <div id="GameNextMove"></div>
  <div id="GameSideToMove"></div>
  <div id="GameLastComment"></div>
  <div id="GameBoard"></div>
  <div id="GameButtons"></div>
  <div id="GameEvent"></div>
  <div id="GameRound"></div>
  <div id="GameSite"></div>
  <div id="GameDate"></div>
  <div id="GameWhite"></div>
  <div id="GameBlack"></div>
  <div id="GameResult"></div>
  <div id="GameText"></div>

  <div id="GameWhiteClock"></div>
  <div id="GameBlackClock"></div>
  <div id="GameLiveStatus"></div>
  <div id="GameLiveLastModified"></div>

The file template.css shows a list of customization style options.
For better chessboard display, it is recommended to explicitly enforce 
chessboard square sizes using the ".whiteSquare" and ".blackSquare" CSS 
classes, such as:
  .whiteSquare, .blackSquare { width:40px; height:40px; }

See template.html file for an example.
See *mini.html* for an example of embedding the PGN content into the HTML file.
See http://pgn4web-demo.casaschi.net usage example, including a live broadcast
demo.
See http://pgn4web-blog.casaschi for a usage example within a blog using the
iframe html tag. 


CHESS FIGURINE DISPLAY OF MOVES

pgn4web allows displaying chess moves text using the supplied figurine fonts: 
'pgn4web ChessSansAlpha', 'pgn4web ChessSansMerida', 'pgn4web ChessSansPiratf',
'pgn4web ChessSansUscf' and 'pgn4web ChessSansUsual'. These fonts are based on
the Liberation Sans font, see credits section for more details.
To enable figurine display of chess moves text, make sure you include the
fonts/pgn4web-fonts.css file into your HTML file:
  <link href="fonts/pgn4web-fonts.css" type="text/css" rel="stylesheet" /> 
or into your CSS file:
  @import url("fonts/pgn4web-fonts.css");
Then set the font-family for the .move class to the chess font of your choice.
For example in your CSS file:
  .move {
    font-family: 'pgn4web ChessSansPiratf', 'pgn4web Liberation Sans', sans-serif;
  }
See the template.html and template.css files for an example.


THE BOARD GENERATOR WEB TOOL

The board widget allows showing games and positions in web pages and blogs, 
without any html coding for each game, where the chessboard widget is created 
using a given HTML code within the web page or blog. 

Just go to the board widget generator site on 
  http://pgn4web-board-generator.casaschi.net
the enter your PGN games and configure the options. The tool will 
automatically generate some HTML code that you can cut and paste in your web 
page or your blog.


THE LIVE BROADCAST OF GAMES

By setting the SetLiveBroadcast(delay, alertFlag, demoFlag) option in the 
HTML file, pgn4web will periodically refresh the PGN file, showing the live 
progress of the games. PGN files produced by the DGT chessboards are supported.

SetLiveBroadcast(delay, alertFlag, demoFlag) parameters:
 - delay = refresh interval in minutes, decimals allowed (default 1)
 - alertFlag = if set true, shows alert debug messages (default false)
 - demoFlag = if set true, sets live demo mode (default false)

The bash shell script live-grab.sh, executed on your server allows for grabbing
the updated game source from anywhere on the Internet to your server.
The live broadcast stops refreshing once all games are ended.

If your live PGN contains clock info as comments after each game such as
{1:59:59}, the clock information is displayed in the following sections:

  <div id="GameWhiteClock"></div>
  <div id="GameBlackClock"></div>

Clock information provided by the DGT chessboards (like {[%clk 1:59:59]}) is
also supported.

The status of the live broadcast is displayed in the following sections: 

  <div id="GameLiveStatus"></div>
  <div id="GameLiveLastRefreshed"></div>
  <div id="GameLiveLastReceived"></div>
  <div id="GameLiveLastModifiedServer"></div>

Clicking on the H6 square will force a games refresh.
Clicking on the A6/B6 squares will pause/restart the automatic games refresh.

The file live-template.html shows a very basic example.

A demo facility is available to test the live broadcast functionality.
If the demo flag is set in SetLiveBroadcast() and a set of full games is 
provided, the tool will simulate a slow progress of the game. Set the
proper flag in live-template.html for an example. Please note, even during
a demo, the PGN file is actually refreshed from the server for a more
accurate testing.
Alternatively, for a more realistic simulation, the bash shell script 
live-simulation.sh slowly updates the live.pgn file, simulating a real event.

The easiest way to setup a live broadcast is to use the live-compact.html file.
The HTML file accepts these parameters:
 - pgnFile = PGN file to load (default live.pgn)
 - initialGame = initial game, a number or first, last, random (default 1)
 - refreshMinutes = refresh interval in minutes, decimals allowed (default 1)
 - demo = if set true, sets live demo mode (default false)
For instance, make sure that the file myGames.pgn on your server is periodically
refreshed with the live games, then add the following iframe to your page:
<iframe frameborder=0 width=480 height=360 
        src=live-compact.html?pgnFile=myGames.pgn>
</iframe>
Of course live-compact.html can be edited to customize colors, layout and every
detail.

http://pgn4web-live.casaschi.net will occasionally broadcast live major chess
events.


CUSTOMIZATION FUNCTIONS

The following functions, if defined in the HTML file after loading pgn4web.js,
allow for execution of custom commands at given points:
- customFunctionOnPgnTextLoad(): when loading a new PGN file
- customFunctionOnPgnGameLoad(): when loading a new game
- customFunctionOnMove(): when a  move is made
- customFunctionOnAlert(message_string): when an error alert is raised
Please note the order these functions are executed; for example, when loading 
a new PGN file at the end of the first game, first customFunctionOnMove() is 
executed, then (when the game has been loaded and the move positioning 
completed) customFunctionOnPgnGameLoad() is executed and finally (when the
selected game is fully loaded) customFunctionOnPgnTextLoad() is executed.

See twic765.html or live.html for examples.

The following functions, if defined in the HTML file after loading pgn4web.js,
allow for execution of custom commands when shift + a number key is pressed:
- customShortcutKey_Shift_0()
- customShortcutKey_Shift_1()
...
- customShortcutKey_Shift_9()


SHORTCUT KEYS AND TEXT FORMS

When the HTML page contains the following script command

  SetShortcutKeysEnabled(true);

then all keystrokes for that active page are captured and processed by pgn4web; 
this allows for instance to browse the game using the arrow keys. If no other 
precautions are taken, this has also the undesirable side effect of capturing 
keystrokes intended by the user for typing in text forms when present in the 
same page: this makes the text forms unusable.

In order to have fully functional text forms in pgn4web pages, the following 
"onFocus" and "onBlur" actions should be added to the textarea forms:

  <textarea onFocus="disableShortcutKeysAndStoreStatus();" 
  onBlur="restoreShortcutKeysStatus();"></textarea> 

See the inputform.html HTML file for an example.


TECHNICAL NOTES ABOUT WEB BROWSERS

pgn4web is developed and tested with recent versions of a variety of 
browsers (Arora, Blackberry browser, Chrome, Epiphany, Firefox, Internet 
Explorer, Opera, Safari) on a variety of personal computer platforms 
(Linux/Debian, MacOS, Windows) and some smartphone/pda platform (Android, 
Blackberry, Apple iOS for iPhone/iPad/iPod).
Not every browser version (please upgrade to a recent release) has been 
tested and not every combination of browser/platform has been validated. 
If you have any issue with using pgn4web on your platform, please email 
pgn4web@casaschi.net 

Note about Google Chrome: you might experience problems when testing HTML
pages from your local computer while developing your site. This is a
security limitation of the browser with respect to loading local files. 
The limitation can be bypassed by starting Google Chrome with the command 
line switch '--allow-file-access-from-files'. Browsing pgn4web websites
with Google Chrome should work properly.

Note about Internet Explorer v7 and above: under some circumstances you might
experience problems when testing HTML pages from your local computer while 
developing your site. If this happens to you, read notes at 
http://code.google.com/p/pgn4web/issues/detail?id=23 


PGN STANDARD SUPPORT

pgn4web mostly supports the PGN standard for chess games notation (see 
http://www.tim-mann.org/Standard). Notable exceptions and limitations:

- variations are not parsed as such, but stored as comments; support for 
browsing variations is planned for a future release
- if square brackets are present in the comments (or the game text) in a
pattern similar to the PGN header pattern '[tag "value"]' then the game 
parsing might break. If your PGN file is not displayed properly, please
check for characters "[" and "]" in the comments and try removing them.
- only pieces initials in the English language are supported, the use of
alternative languages as specified by the PGN standard is not supported
(pgn4web can however display chess moves text using figurine notation, so
the language issue should not be much of a problem, just make sure your
chess software produces PGN data with English pieces initials).

pgn4web also follows a set of proposed extensions to the PGN standard
(see http://www.enpassant.dk/chess/palview/enhancedpgn.htm), more 
specifically:

- understands the [%clk 1:59:58] tag in the PGN comment section as the 
  clock time after each move
- understands the PGN tags [WhiteClock "2:00:00"] and 
  [BlackClock "2:00:00"] as the clock times at the beginning of the game
- defines the [%pgn4web internal comment] tag in the PGN comment section 
and stores the internal comment value for internal use. 

Please email me for review any PGN file that pgn4web fails parsing correctly. 

CHESS960 SUPPORT

pgn4web supports Chess960 (a.k.a. Fischer random chess) and understands both 
the X-FEN and the Shredder-FEN extensions to the FEN notation.

JAVASCRIPT CODING

As of pgn4web version 1.72, the pgn4web.js code is checked with the lint
tool (see online version at http://www.javascriptlint.com/online_lint.php).
Plase note that warnings "lint warning: increment (++) and decrement (--) 
operators used as part of greater statement" are ignored.
Lint validation should allow for easy compression of the javascript code,
for instance using http://javascriptcompressor.com/ 
Although a compression beyond 50% can be achieved, only the uncompressed
version is distributed, but if you want to use a compressed version on your
site, the pgn4web.js code should support it.


CREDITS AND LICENSE

javascript modifications of Paolo Casaschi (pgn4web@casaschi.net) on code 
from the http://ficsgames.com database, in turn likely based on code from the 
LT PGN viewer at http://www.lutanho.net/pgn/pgnviewer.html

PNG images from http://ixian.com/chess/jin-piece-sets (creative commons 
attribution-share alike 3.0 unported license)

The figurine fonts are derived from the Liberation Sans font (released under
GNU General Public License, see https://fedorahosted.org/liberation-fonts/)
with the addition of chess pieces from freeware fonts: the alpha2 font (Peter
Strickland), the good companion font (David L. Brown), the merida font (Armando
H. Marroquin), the pirate font (Klaus Wolf) and the chess usual font (Armando
H. Marroquin). The original chess fonts and more details are available at 
http://www.enpassant.dk/chess/fonteng.htm
 
Some of the PGN files for the examples are coming from "The Week in Chess" at 
http://www.chesscenter.com/twic/twic.html (files wch08ak.pgn and twic765.pgn) 
and from the scid project at http://scid.sourceforge.net (file tactics.pgn).

The jscolor javascript code is  maintained by Honza Odvarko 
(http://odvarko.cz/) and released under the GNU Lesser General Public License 
(http://www.gnu.org/copyleft/lesser.html)
See http://jscolor.com/

The above items remains subject to their original licenses (if any).

Remaining pgn4web code is copyright (C) 2010 Paolo Casaschi

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.

See license-gpl-2.0.txt license file.

You are free to use pgn4web in your website or blog; you are not required to
acknowledge the pgn4web project, but if you want to do so the following line
might be used:
javascript chess viewer courtesy of <a href=http://pgn4web.casaschi.net>pgn4web</a>

You are also encouraged to notify pgn4web@casaschi.net that you are using 
pgn4web.

END

