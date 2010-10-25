/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2010 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

var pgn4web_version = '2.12+';

var pgn4web_project_url = 'http://pgn4web.casaschi.net';
var pgn4web_project_author = 'Paolo Casaschi';
// pgn4web_project_email could be preassigned in pgn4web-server-config.js
var pgn4web_project_email;
if (pgn4web_project_email === undefined) { pgn4web_project_email = 'pgn4web@casaschi.net'; }

var helpWin=null;
function displayHelp(section){
  if (!section) { section = "top"; }
  if (helpWin && !helpWin.closed) { helpWin.close(); }
  helpWin = window.open(detectHelpLocation() + "?" + 
   (Math.floor(900 * Math.random()) + 100) + "#" + section, "pgn4web_help",
   "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if ((helpWin !== null) && (window.focus)) { helpWin.window.focus(); }
}


// custom functions executed at the given moments
// to be redefined in the HTML AFTER loading pgn4web.js

function customFunctionOnPgnTextLoad() {}
function customFunctionOnPgnGameLoad() {}
function customFunctionOnMove() {}
function customFunctionOnAlert(msg) {}


window.onload = start_pgn4web;

document.onkeydown = handlekey;

function start_pgn4web() {
  // keep startup logs at first run
  // reset alert log when reloading start_pgn4web
  if (alertFirstResetLoadingPgn) { alertFirstResetLoadingPgn = false; }
  else { resetAlert(); }
  InitImages(); 
  createBoard();
  if (LiveBroadcastDelay > 0) { restartLiveBroadcastTimeout(); }
}

var alertLog;
var alertLast;
var alertNum;
var alertNumSinceReset;
var fatalErrorNumSinceReset;
var alertPromptInterval = null;
var alertPromptOn = false;
var alertFirstResetLoadingPgn = true;

resetAlert();

function resetAlert() {
  alertLog = new Array(5);
  alertLast = alertLog.length - 1;
  alertNum = alertNumSinceReset = fatalErrorNumSinceReset = 0;
  stopAlertPrompt();
  if (!alertFirstResetLoadingPgn) {
    boardShortcut(debugShortcutSquare, "pgn4web v" + pgn4web_version + " debug info");
  }
}

function myAlert(msg, fatalError) {
  alertNum++;
  alertNumSinceReset++;
  if (fatalError) { fatalErrorNumSinceReset++; }
  alertLast = (alertLast + 1) % alertLog.length;
  alertLog[alertLast] = msg;
  boardShortcut(debugShortcutSquare, 
    "pgn4web v" + pgn4web_version + " debug info, " + alertNum + " alert" + (alertNum > 1 ? "s" : "")); 

  if ((LiveBroadcastDelay === 0) || (LiveBroadcastAlert === true)) {
    startAlertPrompt();
  }
  customFunctionOnAlert(msg);
}

function startAlertPrompt() {
  if (alertPromptOn) { return; } // dont start flashing twice
  if (alertPromptInterval) { clearTimeout(alertPromptInterval); }
  alertPromptInterval = setTimeout("alertPromptTick(true);", 500);
}

function stopAlertPrompt() {
  if (alertPromptInterval) { 
    clearTimeout(alertPromptInterval); 
    alertPromptInterval = null;
  }
  if (alertPromptOn) { alertPromptTick(false); }
}

function alertPromptTick(restart) {
  if (alertPromptInterval) { 
    clearTimeout(alertPromptInterval); 
    alertPromptInterval = null;
  }
  theObject = document.getElementById('tcol0trow0');
  if(theObject) {
    if (alertPromptOn) {
      if ((highlightOption) && 
        ((lastColFromHighlighted === 0 && lastRowFromHighlighted === 7) || 
        (lastColToHighlighted === 0 && lastRowToHighlighted === 7))) {
          theObject.className = 'highlightWhiteSquare';
        } else { theObject.className = 'whiteSquare'; }
    } else { theObject.className = 'blackSquare'; }

    alertPromptOn = !alertPromptOn;
    if (alertPromptOn) { alertPromptDelay = 500; }
    else { alertPromptDelay = 3000; }
  } else { alertPromptDelay = 1500; } // for alerts before the baord is drawn
  if (restart) { alertPromptInterval = setTimeout("alertPromptTick(true);", alertPromptDelay); }
}


function stopKeyProp(e) {
  e.cancelBubble = true;
  if (e.stopPropagation) { e.stopPropagation(); }
  if (e.preventDefault) { e.preventDefault(); }
  return false;
}

// for onFocus and onBlur actions on textboxes, allowing text typing
var shortcutKeysWereEnabled = false;
function disableShortcutKeysAndStoreStatus() {
  if ((shortcutKeysWereEnabled = shortcutKeysEnabled) === true) {
    SetShortcutKeysEnabled(false);
  }
}

function restoreShortcutKeysStatus() {
  if (shortcutKeysWereEnabled === true) { SetShortcutKeysEnabled(true); }
  shortcutKeysWereEnabled = false;
}

function customShortcutKey_Shift_0() {}
function customShortcutKey_Shift_1() {}
function customShortcutKey_Shift_2() {}
function customShortcutKey_Shift_3() {}
function customShortcutKey_Shift_4() {}
function customShortcutKey_Shift_5() {}
function customShortcutKey_Shift_6() {}
function customShortcutKey_Shift_7() {}
function customShortcutKey_Shift_8() {}
function customShortcutKey_Shift_9() {}

var shortcutKeysEnabled = false;
function handlekey(e) { 
  var keycode;

  if (!e) { e = window.event; }

  keycode = e.keyCode;

  if (e.altKey || e.ctrlKey || e.metaKey) { return true; }

  // escape always enabled: show help and toggle enabling shortcut keys
  if ((keycode != 27) && (shortcutKeysEnabled === false)) { return true; }

  switch(keycode) {

    case  8: // backspace
    case  9: // tab
    case 16: // shift
    case 17: // ctrl
    case 18: // alt
    case 32: // space
    case 33: // page-up
    case 34: // page-down
    case 35: // end
    case 36: // home
    case 45: // insert
    case 46: // delete
    case 92: // super
    case 93: // menu
      return true;

    case 27: // escape
      if (e.shiftKey) { interactivelyToggleShortcutKeys(); }
      else { displayHelp(); }
      return stopKeyProp(e);

    case 90: // z
      if (e.shiftKey) { window.open(pgn4web_project_url); }
      else { displayDebugInfo(); }
      return stopKeyProp(e);

    case 37: // left-arrow  
    case 74: // j
      MoveBackward(1);
      return stopKeyProp(e);

    case 38: // up-arrow
    case 72: // h
      GoToMove(StartPly);
      return stopKeyProp(e);

    case 39: // right-arrow
    case 75: // k
      MoveForward(1);
      return stopKeyProp(e);

    case 40: // down-arrow
    case 76: // l
      GoToMove(StartPly + PlyNumber);
      return stopKeyProp(e);

    case 85: // u
      MoveToPrevComment();
      return stopKeyProp(e);

    case 73: // i
      MoveToNextComment();
      return stopKeyProp(e);

    case 83: // s
      searchPgnGamePrompt();
      return stopKeyProp(e);

    case 13: // enter
      if (e.shiftKey) { searchPgnGame(lastSearchPgnExpression, true); }
      else { searchPgnGame(lastSearchPgnExpression); }
      return stopKeyProp(e);

    case 65: // a
      MoveForward(1);
      SetAutoPlay(true);
      return stopKeyProp(e);

    case 48: // 0
      if (e.shiftKey) { customShortcutKey_Shift_0(); }
      else { SetAutoPlay(false); }
      return stopKeyProp(e);

    case 49: // 1
      if (e.shiftKey) { customShortcutKey_Shift_1(); }
      else { SetAutoplayDelayAndStart( 1*1000); }
      return stopKeyProp(e);

    case 50: // 2
      if (e.shiftKey) { customShortcutKey_Shift_2(); }
      else { SetAutoplayDelayAndStart( 2*1000); }
      return stopKeyProp(e);

    case 51: // 3
      if (e.shiftKey) { customShortcutKey_Shift_3(); }
      else { SetAutoplayDelayAndStart( 3*1000); }
      return stopKeyProp(e);

    case 52: // 4
      if (e.shiftKey) { customShortcutKey_Shift_4(); }
      else { SetAutoplayDelayAndStart( 4*1000); }
      return stopKeyProp(e);

    case 53: // 5
      if (e.shiftKey) { customShortcutKey_Shift_5(); }
      else { SetAutoplayDelayAndStart( 5*1000); }
      return stopKeyProp(e);

    case 54: // 6
      if (e.shiftKey) { customShortcutKey_Shift_6(); }
      else { SetAutoplayDelayAndStart( 6*1000); }
      return stopKeyProp(e);

    case 55: // 7
      if (e.shiftKey) { customShortcutKey_Shift_7(); }
      else { SetAutoplayDelayAndStart( 7*1000); }
      return stopKeyProp(e);

    case 56: // 8
      if (e.shiftKey) { customShortcutKey_Shift_8(); }
      else { SetAutoplayDelayAndStart( 8*1000); }
      return stopKeyProp(e);

    case 57: // 9
      if (e.shiftKey) { customShortcutKey_Shift_9(); }
      else { SetAutoplayDelayAndStart( 9*1000); }
      return stopKeyProp(e);

    case 81: // q
      SetAutoplayDelayAndStart(10*1000);
      return stopKeyProp(e);

    case 87: // w
      SetAutoplayDelayAndStart(20*1000);
      return stopKeyProp(e);

    case 69: // e
      SetAutoplayDelayAndStart(30*1000);
      return stopKeyProp(e);

    case 82: // r
      pauseLiveBroadcast();
      return stopKeyProp(e);

    case 84: // t
      refreshPgnSource();
      return stopKeyProp(e);

    case 89: // y
      resumeLiveBroadcast();
      return stopKeyProp(e);

    case 70: // f
      FlipBoard();
      return stopKeyProp(e);

    case 71: // g
      SetHighlight(!highlightOption);
      return stopKeyProp(e);

    case 68: // d
      if (IsRotated) { FlipBoard(); }
      return stopKeyProp(e);

    case 88: // x
      if (numberOfGames > 1) {
        Init(Math.floor(Math.random()*numberOfGames));
        GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber + 1)));
      }
      return stopKeyProp(e);

    case 67: // c
      if (numberOfGames > 1) { Init(Math.floor(Math.random()*numberOfGames)); }
      return stopKeyProp(e);

    case 86: // v
      if (numberOfGames > 1) { Init(0); }
      return stopKeyProp(e);

    case 66: // b
      Init(currentGame - 1);
      return stopKeyProp(e);

    case 78: // n
      Init(currentGame + 1);
      return stopKeyProp(e);

    case 77: // m
      if (numberOfGames > 1) { Init(numberOfGames - 1); }
      return stopKeyProp(e);

    case 79: // o
      SetCommentsOnSeparateLines(!commentsOnSeparateLines);
      oldPly = CurrentPly;
      Init();
      GoToMove(oldPly);
      return stopKeyProp(e);

    case 80: // p
      SetCommentsIntoMoveText(!commentsIntoMoveText);
      oldPly = CurrentPly;
      Init();
      GoToMove(oldPly);
      return stopKeyProp(e);

    default:
      return true;
  }
  return true;
}

boardOnClick = new Array(8);
boardTitle = new Array(8);
for (col=0; col<8; col++) {
  boardOnClick[col] = new Array(8);
  boardTitle[col] = new Array(8);
  for (row=0; row<8; row++) {
    boardTitle[col][row] = "";
    boardOnClick[col][row] = function(){};
  }
}

function boardShortcut(square, title, functionPointer) {
  if (square.charCodeAt === null) { return; }
  var col = square.charCodeAt(0) - 65; // 65="A"
  if ((col < 0) || (col > 7)) { return; }
  var row = 56 - square.charCodeAt(1); // 56="8"
  if ((row < 0) || (row > 7)) { return; }
  boardTitle[col][row] = title;
  if (functionPointer) { boardOnClick[col][row] = functionPointer; }
  if (theObject = document.getElementById('link_tcol' + col + 'trow' + row)) {
    if (IsRotated) { square = String.fromCharCode(72-col,49+row); }
    if (boardTitle[col][row] !== '') { squareTitle = square + ': ' + boardTitle[col][row]; }
    else { squareTitle = square; } 
    theObject.title = squareTitle;
  }
}

// PLEASE NOTE: 'boardShortcut' ALWAYS ASSUMES 'square' WITH WHITE ON BOTTOM

debugShortcutSquare = "A8";
// A8
boardShortcut("A8", "pgn4web v" + pgn4web_version + " debug info", function(){ displayDebugInfo(); });
// B8
boardShortcut("B8", "show this position FEN string", function(){ displayFenData(); });
// C8
boardShortcut("C8", "show this game PGN source data", function(){ displayPgnData(false); });
// D8
boardShortcut("D8", "show full PGN source data", function(){ displayPgnData(true); });
// E8
boardShortcut("E8", "search help", function(){ displayHelp("search"); });
// F8
boardShortcut("F8", "shortcut keys help", function(){ displayHelp("keys"); });
// G8
boardShortcut("G8", "shortcut squares help", function(){ displayHelp("squares"); });
// H8
boardShortcut("H8", "pgn4web help", function(){ displayHelp(); });
// A7
boardShortcut("A7", "pgn4web website", function(){ window.open(pgn4web_project_url); });
// B7
boardShortcut("B7", "toggle show comments in game text", function(){ SetCommentsIntoMoveText(!commentsIntoMoveText); oldPly = CurrentPly; Init(); GoToMove(oldPly); });
// C7
boardShortcut("C7", "toggle show comments on separate lines in game text", function(){ SetCommentsOnSeparateLines(!commentsOnSeparateLines); oldPly = CurrentPly; Init(); GoToMove(oldPly); });
// D7
boardShortcut("D7", "toggle highlight last move", function(){ SetHighlight(!highlightOption); });
// E7
boardShortcut("E7", "flip board", function(){ FlipBoard(); });
// F7
boardShortcut("F7", "show white on bottom", function(){ if (IsRotated) { FlipBoard(); } });
// G7
boardShortcut("G7", "toggle autoplay next game", function(){ SetAutoplayNextGame(!autoplayNextGame); });
// H7
boardShortcut("H7", "toggle enabling shortcut keys", function(){ interactivelyToggleShortcutKeys(); });
// A6
boardShortcut("A6", "pause live broadcast automatic refresh", function(){ pauseLiveBroadcast(); });
// B6
boardShortcut("B6", "restart live broadcast automatic refresh", function(){ restartLiveBroadcast(); });
// C6
boardShortcut("C6", "search previous finished game", function(){ searchPgnGame('\\[\\s*Result\\s*"(?!\\*"\\s*\\])', true); });
// D6
boardShortcut("D6", "search previous unfinished game", function(){ searchPgnGame('\\[\\s*Result\\s*"\\*"\\s*\\]', true); });
// E6
boardShortcut("E6", "search next unfinished game", function(){  searchPgnGame('\\[\\s*Result\\s*"\\*"\\s*\\]', false); });
// F6
boardShortcut("F6", "search next finished game", function(){ searchPgnGame('\\[\\s*Result\\s*"(?!\\*"\\s*\\])', false); });
// G6
boardShortcut("G6", "", function(){});
// H6
boardShortcut("H6", "force games refresh during live broadcast", function(){ refreshPgnSource(); });
// A5
boardShortcut("A5", "repeat last search backward", function(){ searchPgnGame(lastSearchPgnExpression, true); });
// B5
boardShortcut("B5", "search prompt", function(){ searchPgnGamePrompt(); });
// C5
boardShortcut("C5", "repeat last search", function(){ searchPgnGame(lastSearchPgnExpression); });
// D5
boardShortcut("D5", "search previous win result", function(){ searchPgnGame('\\[\\s*Result\\s*"(1-0|0-1)"\\s*\\]', true); });
// E5
boardShortcut("E5", "search next win result", function(){ searchPgnGame('\\[\\s*Result\\s*"(1-0|0-1)"\\s*\\]', false); });
// F5
boardShortcut("F5", "", function(){});
// G5
boardShortcut("G5", "", function(){});
// H5
boardShortcut("H5", "", function(){});
// A4
boardShortcut("A4", "search previous event", function(){ searchPgnGame('\\[\\s*Event\\s*"(?!' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\])', true); });
// B4
boardShortcut("B4", "search previous round of same event", function(){ searchPgnGame('\\[\\s*Event\\s*"' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\].*\\[\\s*Round\\s*"(?!' + fixRegExp(gameRound[currentGame]) + '"\\s*\\])|\\[\\s*Event\\s*"' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\].*\\[\\s*Round\\s*"(?!' + fixRegExp(gameRound[currentGame]) + '"\\s*\\])', true); });
// C4
boardShortcut("C4", "search previous game of same black player", function(){ searchPgnGame('\\[\\s*Black\\s*"' + fixRegExp(gameBlack[currentGame]) + '"\\s*\\]', true); });
// D4
boardShortcut("D4", "search previous game of same white player", function(){ searchPgnGame('\\[\\s*White\\s*"' + fixRegExp(gameWhite[currentGame]) + '"\\s*\\]', true); });
// E4
boardShortcut("E4", "search next game of same white player", function(){ searchPgnGame('\\[\\s*White\\s*"' + fixRegExp(gameWhite[currentGame]) + '"\\s*\\]', false); });
// F4
boardShortcut("F4", "search next game of same black player", function(){  searchPgnGame('\\[\\s*Black\\s*"' + fixRegExp(gameBlack[currentGame]) + '"\\s*\\]', false); });
// G4
boardShortcut("G4", "search next round of same event", function(){ searchPgnGame('\\[\\s*Event\\s*"' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\].*\\[\\s*Round\\s*"(?!' + fixRegExp(gameRound[currentGame]) + '"\\s*\\])|\\[\\s*Event\\s*"' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\].*\\[\\s*Round\\s*"(?!' + fixRegExp(gameRound[currentGame]) + '"\\s*\\])', false); });
// H4
boardShortcut("H4", "search next event", function(){ searchPgnGame('\\[\\s*Event\\s*"(?!' + fixRegExp(gameEvent[currentGame]) + '"\\s*\\])', false); });
// A3
boardShortcut("A3", "load first game", function(){ if (numberOfGames > 1) { Init(0); } });
// B3
boardShortcut("B3", "junp to previous games decile", function(){ if (currentGame > 0) { calculateDeciles(); for(ii=(deciles.length-2); ii>=0; ii--) { if (currentGame > deciles[ii]) { Init(deciles[ii]); break; } } } });
// C3
boardShortcut("C3", "load previous game", function(){ Init(currentGame - 1); });
// D3
boardShortcut("D3", "load random game", function(){ if (numberOfGames > 1) { Init(Math.floor(Math.random()*numberOfGames)); } });
// E3
boardShortcut("E3", "load random game at random position", function(){ Init(Math.floor(Math.random()*numberOfGames)); GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber + 1))); });
// F3
boardShortcut("F3", "load next game", function(){ Init(currentGame + 1); });
// G3
boardShortcut("G3", "jump to next games decile", function(){ if (currentGame < numberOfGames - 1) { calculateDeciles(); for(ii=1; ii<deciles.length; ii++) { if (currentGame < deciles[ii]) { Init(deciles[ii]); break; } } } });
// H3
boardShortcut("H3", "load last game", function(){ if (numberOfGames > 1) { Init(numberOfGames - 1); } });
// A2
boardShortcut("A2", "stop autoplay", function(){ SetAutoPlay(false); });
// B2
boardShortcut("B2", "toggle autoplay", function(){ SwitchAutoPlay(); });
// C2
boardShortcut("C2", "autoplay 1 second", function(){ SetAutoplayDelayAndStart( 1*1000); });
// D2
boardShortcut("D2", "autoplay 2 seconds", function(){ SetAutoplayDelayAndStart( 2*1000); });
// E2
boardShortcut("E2", "autoplay 3 seconds", function(){ SetAutoplayDelayAndStart( 3*1000); });
// F2
boardShortcut("F2", "autoplay 5 seconds", function(){ SetAutoplayDelayAndStart( 5*1000); });
// G2
boardShortcut("G2", "autoplay 10 seconds", function(){ SetAutoplayDelayAndStart(10*1000); });
// H2
boardShortcut("H2", "autoplay 30 seconds", function(){ SetAutoplayDelayAndStart(30*1000); });
// A1
boardShortcut("A1", "go to game start", function(){ GoToMove(StartPly); });
// B1
boardShortcut("B1", "go to previous comment", function(){ MoveToPrevComment(); });
// C1
boardShortcut("C1", "move 6 half-moves backward", function(){ MoveBackward(6); });
// D1
boardShortcut("D1", "move backward", function(){ MoveBackward(1); });
// E1
boardShortcut("E1", "move forward", function(){ MoveForward(1); });
// F1
boardShortcut("F1", "move 6 half-moves forward", function(){ MoveForward(6); });
// G1
boardShortcut("G1", "go to next comment", function(){ MoveToNextComment(); });
// H1
boardShortcut("H1", "go to game end", function(){ GoToMove(StartPly + PlyNumber); });


var deciles = new Array(11);
function calculateDeciles() {
  for (ii=0; ii<deciles.length; ii++) { 
    deciles[ii] = Math.round((numberOfGames - 1) * ii / (deciles.length - 1));
  }
}

function detectJavascriptLocation() {
  jspath = "";
  var e = document.getElementsByTagName('script');
  for(var i=0; i<e.length; i++) {
    if ((e[i].src) && (e[i].src.match(/(pgn4web|pgn4web-compacted)\.js/))) {
      jspath = e[i].src; 
    }
  }
  return jspath;
}

function detectHelpLocation() {
  return detectJavascriptLocation().replace(/(pgn4web|pgn4web-compacted)\.js/, "help.html"); 
}

function detectBaseLocation() {
  base = "";
  var e = document.getElementsByTagName('base');
  for(var i=0; i<e.length; i++) {
    if (e[i].href) { base = e[i].href; }
  }
  return base;
}


debugWin = null;
function displayDebugInfo() {
  stopAlertPrompt();
  debugInfo = 'pgn4web: version=' + pgn4web_version + ' homepage=' + pgn4web_project_url + '\n\n' +
    'HTMLURL: length=' + location.href.length + ' url=' +
    (location.href.length < 100 ? location.href : (location.href.substring(0,99) + '...')) + '\n' +
    'BASEURL: url=' + detectBaseLocation() + '\n' +
    'JSURL: url=' + detectJavascriptLocation() + '\n\n' +
    'PGNURL: url=' + pgnUrl + '\n' +
    'PGNTEXT: length=';
  if (document.getElementById("pgnText") !== null) { 
    debugInfo += document.getElementById("pgnText").tagName.toLowerCase() == "textarea" ?
      document.getElementById("pgnText").value.length :
      document.getElementById("pgnText").innerHTML.length +
      ' container=' + document.getElementById("pgnText").tagName.toLowerCase();
    // pgn4web up to 1.77 used <span> for pgnText
  }
  debugInfo += '\n\n' +
    'GAMES: current=' + (currentGame+1) + ' number=' + numberOfGames + '\n' +
    'PLY: start=' + StartPly + ' current=' + CurrentPly + ' number=' + PlyNumber + '\n' +
    'AUTOPLAY: ' + (isAutoPlayOn ? 'delay=' + Delay + 'ms' + ' autoplaynext=' + autoplayNextGame : 'off') +
    '\n\n' +
    'LIVEBROADCAST: ' + (LiveBroadcastDelay > 0 ? 'ticker=' + LiveBroadcastTicker + ' delay=' + LiveBroadcastDelay + 'm' + ' started=' + LiveBroadcastStarted + ' ended=' + LiveBroadcastEnded + ' paused=' + LiveBroadcastPaused + ' demo=' + LiveBroadcastDemo + ' alert=' + LiveBroadcastAlert + '\n' + 'refreshed: ' + LiveBroadcastLastRefreshedLocal + '\n' + 'received: ' + LiveBroadcastLastReceivedLocal + '\n' + 'modified (server time): ' + LiveBroadcastLastModified_ServerTime() : 'off') + 
    '\n\n' +
    'ALERTLOG: fatalnew=' + fatalErrorNumSinceReset + ' new=' + alertNumSinceReset + 
    ' shown=' + Math.min(alertNum, alertLog.length) + ' total=' + alertNum + '\n--';
  if (alertNum > 0) {
    for (ii = 0; ii<alertLog.length; ii++) {
      if (alertLog[(alertNum - 1 - ii) % alertLog.length] === undefined) { break; }
      else { debugInfo += "\n" + alertLog[(alertNum - 1 - ii) % alertLog.length] + "\n--"; }
    }
  }
  if (confirm(debugInfo + '\n\nclick OK to show this debug info in a browser window for cut and paste')) {
    if (debugWin && !debugWin.closed) { debugWin.close(); }
    debugWin = window.open("", "debug_data",
      "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
    if (debugWin !== null) {
      text = "<html><head><title>pgn4web debug info</title>" +
        "<link rel='shortcut icon' href='pawn.ico' /></head>" +
        "<body>\n<pre>\n" + debugInfo + "\n</pre>\n</body></html>";
      debugWin.document.open("text/html", "replace");
      debugWin.document.write(text);
      debugWin.document.close();
      if (window.focus) { debugWin.window.focus(); }
    }
  }
  alertNumSinceReset = fatalErrorNumSinceReset = 0;
}

pgnWin = null;
function displayPgnData(allGames) {
  if (allGames === null) { allGames = true; }
  if (pgnWin && !pgnWin.closed) { pgnWin.close(); }
  pgnWin = window.open("", "pgn_data",
    "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if (pgnWin !== null) {
    text = "<html><head><title>pgn4web PGN source</title>" + 
      "<link rel='shortcut icon' href='pawn.ico' /></head><body>\n<pre>\n";
    if (allGames) { for (ii = 0; ii < numberOfGames; ++ii) { text += pgnGame[ii]; } }
    else { text += pgnGame[currentGame]; }
    text += "\n</pre>\n</body></html>";
    pgnWin.document.open("text/html", "replace");
    pgnWin.document.write(text);
    pgnWin.document.close();
    if (window.focus) { pgnWin.window.focus(); }
  }
}

function CurrentFEN() {
  currentFEN = "";

  emptyCounterFen = 0;
  for (row=7; row>=0; row--) {
    for (col=0; col<=7; col++) {
      if (Board[col][row] === 0) { emptyCounterFen++; }
      else {
        if (emptyCounterFen > 0) {
          currentFEN += "" + emptyCounterFen;
          emptyCounterFen = 0;
        }
        if (Board[col][row] > 0) { currentFEN += FenPieceName.toUpperCase().charAt(Board[col][row]-1); }
        else if (Board[col][row] < 0) { currentFEN += FenPieceName.toLowerCase().charAt(-Board[col][row]-1); }
      }
    }
    if (emptyCounterFen > 0) {
      currentFEN += "" + emptyCounterFen;
      emptyCounterFen = 0;
    }
    if (row>0) { currentFEN += "/"; }
  }
 
  // active color
  currentFEN += CurrentPly%2 === 0 ? " w" : " b";

  // castling availability
  CastlingShortFEN = new Array(2);
  CastlingShortFEN[0] = CastlingShort[0];
  CastlingShortFEN[1] = CastlingShort[1];
  CastlingLongFEN = new Array(2);
  CastlingLongFEN[0] = CastlingLong[0];
  CastlingLongFEN[1] = CastlingLong[1];
  for (var thisPly = StartPly; thisPly < CurrentPly; thisPly++) {
    SideToMoveFEN = thisPly%2;
    BackrowSideToMoveFEN = SideToMoveFEN * 7;
    if (HistType[0][thisPly] == 1) { 
      CastlingShortFEN[SideToMoveFEN] = CastlingLongFEN[SideToMoveFEN] = -1;
    }
    if ((HistCol[0][thisPly] == CastlingShortFEN[SideToMoveFEN]) && 
      (HistRow[0][thisPly] == BackrowSideToMoveFEN)) {
      CastlingShortFEN[SideToMoveFEN] = -1;
    }
    if ((HistCol[0][thisPly] == CastlingLongFEN[SideToMoveFEN]) && 
      (HistRow[0][thisPly] == BackrowSideToMoveFEN)) {
      CastlingLongFEN[SideToMoveFEN] = -1;
    }
  }

  CastlingFEN = "";
  if (SquareOnBoard(CastlingShortFEN[0], 0)) {
    for (ii = 7; ii > CastlingShortFEN[0]; ii--) { if (Board[ii][0] == 3) { break; } }
    if (ii == CastlingShortFEN[0]) { CastlingFEN += FenPieceName.toUpperCase().charAt(0); } 
    else { CastlingFEN += columnsLetters.toUpperCase().charAt(CastlingShortFEN[0]); } 
  }
  if (SquareOnBoard(CastlingLongFEN[0], 0)) {
    for (ii = 0; ii < CastlingLongFEN[0]; ii++) { if (Board[ii][0] == 3) { break; } }
    if (ii == CastlingLongFEN[0]) { CastlingFEN += FenPieceName.toUpperCase().charAt(1); }
    else { CastlingFEN += columnsLetters.toUpperCase().charAt(CastlingLongFEN[0]); }
  }
  if (SquareOnBoard(CastlingShortFEN[1], 7)) {
    for (ii = 7; ii > CastlingShortFEN[1]; ii--) { if (Board[ii][7] == -3) { break; } }
    if (ii == CastlingShortFEN[1]) { CastlingFEN += FenPieceName.toLowerCase().charAt(0); }
    else { CastlingFEN += columnsLetters.toLowerCase().charAt(CastlingShortFEN[1]); }
  }
  if (SquareOnBoard(CastlingLongFEN[1], 7)) {
    for (ii = 0; ii < CastlingLongFEN[1]; ii++) { if (Board[ii][7] == -3) { break; } }
    if (ii == CastlingLongFEN[1]) { CastlingFEN += FenPieceName.toLowerCase().charAt(1); }
    else { CastlingFEN += columnsLetters.toLowerCase().charAt(CastlingLongFEN[1]); }
  }
  if (CastlingFEN === "") { CastlingFEN = "-"; }
  currentFEN += " " + CastlingFEN;
 
  // en-passant square
  if (HistEnPassant[CurrentPly-1]) {
    currentFEN += " " + String.fromCharCode(HistEnPassantCol[CurrentPly-1] + 97);
    currentFEN += CurrentPly%2 === 0 ? "6" : "3";
  } else { currentFEN += " -"; }

  // halfmove clock
  HalfMoveClock = InitialHalfMoveClock;  
  for (thisPly = StartPly; thisPly < CurrentPly; thisPly++) {
    if ((HistType[0][thisPly] == 6) || (HistPieceId[1][thisPly] >= 16)) { HalfMoveClock = 0; }
    else { HalfMoveClock++; } 
  }
  currentFEN += " " + HalfMoveClock;

  // fullmove number
  currentFEN += " " + (Math.floor(CurrentPly/2)+1);

  return currentFEN;
}

fenWin = null;
function displayFenData() {
  if (fenWin && !fenWin.closed) { fenWin.close(); }

  currentFEN = CurrentFEN();

  currentMovesString = "";
  lastLineStart = 0;
  for(var thisPly = CurrentPly; thisPly <= StartPly + PlyNumber; thisPly++) {
    addToMovesString = "";
    if (thisPly == StartPly + PlyNumber) {
      if ((gameResult[currentGame]) && (gameResult[currentGame] != "*")) {
        addToMovesString = gameResult[currentGame];
      }
    } else {
      if ((thisPly%2) === 0) { addToMovesString = (Math.floor(thisPly/2)+1) + ". "; }
      else if (thisPly == CurrentPly) {
        addToMovesString = (Math.floor(thisPly/2)+1) + "... ";
      }
      addToMovesString += Moves[thisPly];
    }
    if (currentMovesString.length + addToMovesString.length + 1 > lastLineStart + 80) {
      lastLineStart = currentMovesString.length;
      currentMovesString += "\n" + addToMovesString;
    } else {
      if (currentMovesString.length > 0) { currentMovesString += " "; }
      currentMovesString += addToMovesString;
    }
  }

  fenWin = window.open("", "fen_data", 
    "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if (fenWin !== null) {
    text = "<html>" +
      "<head><title>pgn4web FEN string</title><link rel='shortcut icon' href='pawn.ico' /></head>" +
      "<body>\n<b><pre>\n\n" + currentFEN + "\n\n</pre></b>\n<hr>\n<pre>\n\n" +
      "[Event \"" + (gameEvent[currentGame] ? gameEvent[currentGame] : "?") + "\"]\n" +
      "[Site \"" + (gameSite[currentGame] ? gameSite[currentGame] : "?") + "\"]\n" +
      "[Date \"" + (gameDate[currentGame] ? gameDate[currentGame] : "????.??.??") + "\"]\n" +
      "[Round \"" + (gameRound[currentGame] ? gameRound[currentGame] : "?") + "\"]\n" +
      "[White \"" + (gameWhite[currentGame] ? gameWhite[currentGame] : "?") + "\"]\n" +
      "[Black \"" + (gameBlack[currentGame] ? gameBlack[currentGame] : "?") + "\"]\n" +
      "[Result \"" + (gameResult[currentGame] ? gameResult[currentGame] : "*") + "\"]\n";
    if (currentFEN != FenStringStart) { 
      text += "[SetUp \"1\"]\n" + "[FEN \"" + CurrentFEN() + "\"]\n";
    }
    if (gameVariant[currentGame] !== "") { text += "[Variant \"" + gameVariant[currentGame] + "\"]\n"; }
    text += "\n" + currentMovesString + "\n</pre>\n</body></html>";
    fenWin.document.open("text/html", "replace");
    fenWin.document.write(text);
    fenWin.document.close();
    if (window.focus) { fenWin.window.focus(); }
  }
}


var pgnGame = new Array();
var numberOfGames = -1; 
var currentGame   = -1;

var firstStart = true;

var gameDate = new Array();
var gameWhite = new Array();
var gameBlack = new Array();
var gameEvent = new Array();
var gameSite = new Array();
var gameRound = new Array();
var gameResult = new Array();
var gameSetUp = new Array();
var gameFEN = new Array();
var gameInitialWhiteClock = new Array();
var gameInitialBlackClock = new Array();
var gameVariant = new Array();

var oldAnchor = -1;

var isAutoPlayOn = false;
var AutoPlayInterval = null;
var Delay = 1000; // milliseconds
var autostartAutoplay = false;
var autoplayNextGame = false;

var initialGame = 1;
var initialHalfmove = 0;
var alwaysInitialHalfmove = false;

var LiveBroadcastInterval = null;
var LiveBroadcastDelay = 0; // minutes
var LiveBroadcastAlert = false;
var LiveBroadcastDemo = false;
var LiveBroadcastStarted = false;
var LiveBroadcastEnded = false;
var LiveBroadcastPaused = false;
var LiveBroadcastTicker = 0;
var LiveBroadcastStatusString = "";
var LiveBroadcastLastModified = new Date(0); // default to epoch start
var LiveBroadcastLastModifiedHeader = LiveBroadcastLastModified.toUTCString();
var LiveBroadcastLastReceivedLocal = 'unavailable';
var LiveBroadcastLastRefreshedLocal = 'unavailable';
var LiveBroadcastPlaceholderEvent = 'pgn4web live broadcast';
var LiveBroadcastPlaceholderPgn = '[Event "' + LiveBroadcastPlaceholderEvent + '"]';
var gameDemoMaxPly = new Array();
var gameDemoLength = new Array();

var MaxMove = 500;

var castleRook = -1;
var mvCapture =  0;
var mvIsCastling =  0;
var mvIsPromotion =  0;
var mvFromCol = -1;
var mvFromRow = -1;
var mvToCol = -1;
var mvToRow = -1;
var mvPiece = -1;
var mvPieceId = -1;
var mvPieceOnTo = -1;
var mvCaptured = -1;
var mvCapturedId = -1;

Board = new Array(8);
for(i=0; i<8; ++i) { Board[i] = new Array(8); }

// HistCol and HistRow contain move history up to the last replayed ply
// HistCol[0] and HistRow[0] contain the "square from" (0..7, 0..7 from square a1)
// HistCol[1] and HistRow[1] contain castling and capture info
// HistCol[2] and HistRow[2] contain the "square to" (0..7, 0..7 from square a1)

HistCol = new Array(3);
HistRow = new Array(3);
HistPieceId = new Array(2);
HistType = new Array(2);

PieceCol = new Array(2);
PieceRow = new Array(2);
PieceType = new Array(2);
PieceMoveCounter = new Array(2);

for(i=0; i<2; ++i){
  PieceCol[i] = new Array(16);
  PieceRow[i] = new Array(16);
  PieceType[i] = new Array(16);
  PieceMoveCounter[i] = new Array(16);
  HistType[i] = new Array(MaxMove);
  HistPieceId[i] = new Array(MaxMove);
}

for(i=0; i<3; ++i){
  HistCol[i] = new Array(MaxMove);
  HistRow[i] = new Array(MaxMove);
}

HistEnPassant = new Array(MaxMove);
HistEnPassant[0] = false;
HistEnPassantCol = new Array(MaxMove);
HistEnPassantCol[0] = -1;

var FenPieceName = "KQRBNP";
var PieceCode = new Array(); // IE needs an array to work with [index]
for (i=0; i<6; i++) { PieceCode[i] = FenPieceName.charAt(i); }
var FenStringStart = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
var columnsLetters = "ABCDEFGH";

startingSquareSize = -1;
startingImageSize = -1;

PiecePicture = new Array(2);
for(i=0; i<2; ++i) { PiecePicture[i] = new Array(6); }

var ImagePath = '';                                                 
var ImagePathOld = null;
var imageType = 'png';
var defaultImagesSize = 40;

var highlightOption = true;

var commentsIntoMoveText = true;
var commentsOnSeparateLines = false;

var pgnUrl = '';

CastlingLong  = new Array(2);
CastlingShort = new Array(2);
Moves = new Array(MaxMove);
MoveComments = new Array(MaxMove);
pgn4webMoveComments = new Array(MaxMove);

var MoveColor;
var MoveCount;
var PlyNumber;
var StartPly;
var CurrentPly;

var IsRotated = false;

var pgnHeaderTagRegExp       = /\[\s*(\w+)\s*"([^"]*)"\s*\]/; 
var pgnHeaderTagRegExpGlobal = /\[\s*(\w+)\s*"([^"]*)"\s*\]/g;
var dummyPgnHeader = '[x""]';
var emptyPgnHeader = '[Event ""]\n[Site ""]\n[Date ""]\n[Round ""]\n[White ""]\n[Black ""]\n[Result ""]\n\n';
var templatePgnHeader = '[Event "?"]\n[Site "?"]\n[Date "?"]\n[Round "?"]\n[White "?"]\n[Black "?"]\n[Result "?"]\n';
var alertPgnHeader = '[Event ""]\n[Site ""]\n[Date ""]\n[Round ""]\n[White ""]\n[Black ""]\n[Result ""]\n\n{error: click on the top left chessboard square for debug info}';

var gameSelectorHead = ' ...';
var gameSelectorMono = true;
var gameSelectorNum = false;
var gameSelectorNumLenght = 0;
var gameSelectorChEvent = 0;
var gameSelectorChSite = 0;
var gameSelectorChRound = 0;
var gameSelectorChWhite = 15;
var gameSelectorChBlack = 15;
var gameSelectorChResult = 0;
var gameSelectorChDate = 10;

function CheckLegality(what, plyCount) {
  var retVal;
  var start;
  var end;
  var isCheck;

  // castling move?
  if (what == 'O-O') {
    if (!CheckLegalityOO()) { return false; }
    start = PieceCol[MoveColor][0];
    end   = 6;
    while (start < end) {
      isCheck = IsCheck(start, MoveColor*7, MoveColor);
      if (isCheck) { return false; }
      ++start;
    }
    StoreMove(plyCount);
    return true;
  } else if (what == 'O-O-O') {
    if (!CheckLegalityOOO()) { return false; }
    start = PieceCol[MoveColor][0];
    end   = 2;
    while (start > end) {
      isCheck = IsCheck(start, MoveColor*7, MoveColor);
      if (isCheck) { return false; }
      --start;
    }
    StoreMove(plyCount);
    return true;
  } 
  
  // not a capture => square must be empty
  // capture => "square to" occupied by opposite color piece (except en-passant)
  // "square to" moved piece different from piece => pawn promotion 
  if (!mvCapture) {
    if (Board[mvToCol][mvToRow] !== 0) { return false; }
  }
  if ((mvCapture) && (Color(Board[mvToCol][mvToRow]) != 1-MoveColor)) {
    if ((mvPiece != 6) || (!HistEnPassant[plyCount-1]) || (HistEnPassantCol[plyCount-1] != mvToCol) ||
	(mvToRow != 5-3*MoveColor)) { return false; }
  }
  if (mvIsPromotion) {
    if (mvPiece     != 6) { return false; }
    if (mvPieceOnTo >= 6) { return false; }
    if (mvToRow     != 7*(1-MoveColor)) { return false; }
  }
  
  // piece move => loop over same type pieces: which could move there?
  var pieceId;
  for (pieceId = 0; pieceId < 16; ++pieceId) {
     if (PieceType[MoveColor][pieceId] == mvPiece) {
      if (mvPiece == 1) { retVal = CheckLegalityKing(pieceId); }
      else if (mvPiece == 2) { retVal = CheckLegalityQueen(pieceId); }
      else if (mvPiece == 3) { retVal = CheckLegalityRook(pieceId); }
      else if (mvPiece == 4) { retVal = CheckLegalityBishop(pieceId); }
      else if (mvPiece == 5) { retVal = CheckLegalityKnight(pieceId); }
      else if (mvPiece == 6) { retVal = CheckLegalityPawn(pieceId); }
      if (retVal) {
	mvPieceId = pieceId;
        // board updated: king check?
        StoreMove(plyCount);
        isCheck = IsCheck(PieceCol[MoveColor][0], PieceRow[MoveColor][0], MoveColor);
	if (!isCheck) { return true; }
	else { UndoMove(plyCount); }
      }
    }
  }
  return false;
}

function CheckLegalityKing(thisKing) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisKing])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisKing])) { return false; }
  if (Math.abs(PieceCol[MoveColor][thisKing]-mvToCol) > 1) { return false; }
  if (Math.abs(PieceRow[MoveColor][thisKing]-mvToRow) > 1) { return false; }
  return true;
}

function CheckLegalityQueen(thisQueen) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisQueen])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisQueen])) { return false; }
  if (((PieceCol[MoveColor][thisQueen]-mvToCol) *
    (PieceRow[MoveColor][thisQueen]-mvToRow) !== 0) &&
    (Math.abs(PieceCol[MoveColor][thisQueen]-mvToCol) !=
    Math.abs(PieceRow[MoveColor][thisQueen]-mvToRow)))
  { return false; }
  var clearWay = CheckClearWay(thisQueen);
  if (!clearWay) { return false; }
  return true;
}

function CheckLegalityRook(thisRook) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisRook])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisRook])) { return false; }
  if ((PieceCol[MoveColor][thisRook]-mvToCol) *
    (PieceRow[MoveColor][thisRook]-mvToRow) !== 0)
  { return false; }
  var clearWay = CheckClearWay(thisRook);
  if (!clearWay) { return false; }
  return true;
}

function CheckLegalityBishop(thisBishop) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisBishop])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisBishop])) { return false; }
  if (Math.abs(PieceCol[MoveColor][thisBishop]-mvToCol) !=
    Math.abs(PieceRow[MoveColor][thisBishop]-mvToRow))
  { return false; }
  var clearWay = CheckClearWay(thisBishop);
  if (!clearWay) { return false; }
  return true;
}

function CheckLegalityKnight(thisKnight) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisKnight])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisKnight])) { return false; }
  if (Math.abs(PieceCol[MoveColor][thisKnight]-mvToCol) *
    Math.abs(PieceRow[MoveColor][thisKnight]-mvToRow) != 2)
  { return false; }
  return true;
}

function CheckLegalityPawn(thisPawn) {
  if ((mvFromCol >= 0) && (mvFromCol != PieceCol[MoveColor][thisPawn])) { return false; }
  if ((mvFromRow >= 0) && (mvFromRow != PieceRow[MoveColor][thisPawn])) { return false; }
  if (Math.abs(PieceCol[MoveColor][thisPawn]-mvToCol) != mvCapture) { return false; }
  if (mvCapture) {
    if (PieceRow[MoveColor][thisPawn]-mvToRow != 2*MoveColor-1) { return false; }
  } else {
    if (PieceRow[MoveColor][thisPawn]-mvToRow == 4*MoveColor-2){
      if (PieceRow[MoveColor][thisPawn] != 1+5*MoveColor) { return false; }
      if (Board[mvToCol][mvToRow+2*MoveColor-1] !== 0) { return false; }
    } else {
      if (PieceRow[MoveColor][thisPawn]-mvToRow != 2*MoveColor-1) { return false; }
    }
  }
  return true;
}

function CheckLegalityOO() {
  if (CastlingShort[MoveColor] < 0) { return false; }
  if (PieceMoveCounter[MoveColor][0] > 0) { return false; }
  
  // which rook is castling
  var legal = false;
  var thisRook = 0;
  while (thisRook < 16) {
    if ((PieceCol[MoveColor][thisRook] == CastlingShort[MoveColor]) &&
      (PieceCol[MoveColor][thisRook] > PieceCol[MoveColor][0]) &&
      (PieceRow[MoveColor][thisRook] == MoveColor*7) &&
      (PieceType[MoveColor][thisRook] == 3)) {
      legal = true;
      break;
    }
    ++thisRook;
  }
  if (!legal) { return false; }
  if (PieceMoveCounter[MoveColor][thisRook] > 0) { return false; }
  
  // check no piece between king and rook
  // clear king/rook squares for Chess960
  Board[PieceCol[MoveColor][0]][MoveColor*7] = 0;
  Board[PieceCol[MoveColor][thisRook]][MoveColor*7] = 0;
  var col = PieceCol[MoveColor][thisRook];
  if (col < 6) { col = 6; }
  while ((col > PieceCol[MoveColor][0]) || (col >= 5)) {
    if (Board[col][MoveColor*7] !== 0) { return false; }
    --col;
  }
  castleRook = thisRook;
  return true;
}

function CheckLegalityOOO() {
  if (CastlingLong[MoveColor] < 0) { return false; }
  if (PieceMoveCounter[MoveColor][0] > 0) { return false; }

  // which rook is castling
  var legal = false;
  var thisRook = 0;
  while (thisRook < 16) {
    if ((PieceCol[MoveColor][thisRook] == CastlingLong[MoveColor]) &&
      (PieceCol[MoveColor][thisRook] < PieceCol[MoveColor][0]) &&
      (PieceRow[MoveColor][thisRook] == MoveColor*7) &&
      (PieceType[MoveColor][thisRook] == 3)) {
      legal = true;
      break;
    }
    ++thisRook;
  }
  if (!legal) { return false; }
  if (PieceMoveCounter[MoveColor][thisRook] > 0) { return false; }

  // check no piece between king and rook
  // clear king/rook squares for Chess960
  Board[PieceCol[MoveColor][0]][MoveColor*7] = 0;
  Board[PieceCol[MoveColor][thisRook]][MoveColor*7] = 0;
  var col = PieceCol[MoveColor][thisRook];
  if (col > 2) { col = 2; }
  while ((col < PieceCol[MoveColor][0]) || (col <= 3)) {
   if (Board[col][MoveColor*7] !== 0) { return false; }
    ++col;
  }
  castleRook = thisRook;
  return true;
}

function CheckClearWay(thisPiece) {
  var stepCol = sign(mvToCol-PieceCol[MoveColor][thisPiece]);
  var stepRow = sign(mvToRow-PieceRow[MoveColor][thisPiece]);
  var startCol = PieceCol[MoveColor][thisPiece]+stepCol;
  var startRow = PieceRow[MoveColor][thisPiece]+stepRow;
  while ((startCol != mvToCol) || (startRow != mvToRow)) {
    if (Board[startCol][startRow] !== 0) { return false; }
    startCol += stepCol;
    startRow += stepRow;
  }
  return true;
}

function ClearMove(move) {
  var ss = move.length;
  var cc = -1;
  var ii = 0;
  var mm = "";
  while(ii < ss){
    cc = move.charCodeAt(ii);
    if ((cc == 45) || ((cc >= 48) && (cc <= 57)) || (cc == 61) ||
//        (cc == 35) || (cc == 43) || // patch this to pass through '+' and '#' signs
	((cc >= 65) && (cc <= 90)) || ((cc >=97) && (cc <= 122))) {
	  mm += move.charAt(ii);
    }
    ++ii;
  }
  if (mm.match('^[Oo0]-?[Oo0]-?[Oo0]$')) { return 'O-O-O'; }
  if (mm.match('^[Oo0]-?[Oo0]$')) { return 'O-O'; }
  return mm;
}

function GoToMove(thisMove) {
  var diff = thisMove - CurrentPly;
  if (diff > 0) { MoveForward(diff); }
  else { MoveBackward(-diff); }
}

function SetShortcutKeysEnabled(onOff) {
  shortcutKeysEnabled = onOff;
}

function interactivelyToggleShortcutKeys() {
  if (confirm("Shortcut keys currently " + (shortcutKeysEnabled ? "enabled" : "disabled") + ".\nToggle shortcut keys to " + (shortcutKeysEnabled ? "DISABLED" : "ENABLED") + "?")) {
    SetShortcutKeysEnabled(!shortcutKeysEnabled);
  }
}

function SetCommentsIntoMoveText(onOff) {
  commentsIntoMoveText = onOff;
}

function SetCommentsOnSeparateLines(onOff) { 
  commentsOnSeparateLines = onOff;
}

function SetAutostartAutoplay(onOff) {
  autostartAutoplay = onOff;
}

function SetAutoplayNextGame(onOff) {
  autoplayNextGame = onOff;
}

function SetInitialHalfmove(number_or_string, always) {
  if (always === true) { alwaysInitialHalfmove = true; }
  initialHalfmove = number_or_string;
  if (initialHalfmove == "start") { return; }
  if (initialHalfmove == "end") { return; }
  if (initialHalfmove == "random") { return; }
  if (initialHalfmove == "comment") { return; }
  if ((initialHalfmove = parseInt(initialHalfmove,10)) == NaN) { initialHalfmove = 0; }
}

function SetInitialGame(number_or_string) {
  if (number_or_string) { initialGame = number_or_string; }
}

// clock value detection:
// a) check DGT sequence [%clk 01:02] 
// b) check for nn:nn:nn and nn.nn.nn at the comment start 
  
function clockFromComment(comment) {
  var clock = "";
  if ((DGTclock = comment.match(/\[%clk\s*(.*?)\]/)) !== null) { clock = DGTclock[1]; }
  else { if (!(clock = comment.match(/^\s*[0-9:\.]+/))) {clock = ""; } }
  return clock;
}


function HighlightLastMove() {
  var anchorName;

  // remove highlighting from old anchor
  if (oldAnchor >= 0){
    anchorName = 'Mv'+oldAnchor;
    theAnchor = document.getElementById(anchorName);
    if (theAnchor !== null) { theAnchor.className = 'move'; }
  }

  // find halfmove to be highlighted, negative for starting position (nothing to highlight)
  var showThisMove = CurrentPly - 1;
  if (showThisMove > StartPly + PlyNumber) { showThisMove = StartPly + PlyNumber; }

  var theShowCommentTextObject = document.getElementById("GameLastComment");
  if (theShowCommentTextObject !== null) {
    if (MoveComments[showThisMove+1] !== undefined) {
      // remove PGN extension tags
      thisComment = MoveComments[showThisMove+1].replace(/\[%[^\]]*\]\s*/g,''); // trailing spaces also removed
      // remove spaces only comments
      thisComment = thisComment.replace(/^\s+$/,'');
    } else { thisComment = ''; }
    theShowCommentTextObject.innerHTML = thisComment;
    theShowCommentTextObject.className = 'GameLastComment';
  }
  
  // show side to move
  text = (showThisMove+1)%2 === 0 ? 'white' : 'black';
 
  if (theObject = document.getElementById("GameSideToMove"))
  { theObject.innerHTML = text; }

  // show clock if any
  if ((showThisMove+1)%2 == 1) { // white has just moved
    lastMoverClockObject = document.getElementById("GameWhiteClock");
    initialLastMoverClock = gameInitialWhiteClock[currentGame];
    beforeLastMoverClockObject = document.getElementById("GameBlackClock"); 
    initialBeforeLastMoverClock = gameInitialBlackClock[currentGame];
  } else {
    lastMoverClockObject = document.getElementById("GameBlackClock");
    initialLastMoverClock = gameInitialBlackClock[currentGame];
    beforeLastMoverClockObject = document.getElementById("GameWhiteClock"); 
    initialBeforeLastMoverClock = gameInitialWhiteClock[currentGame];
  }

  if (lastMoverClockObject !== null) {
    lastMoverClockObject.innerHTML = showThisMove+1 > StartPly ?
      clockFromComment(MoveComments[showThisMove+1]) : initialLastMoverClock;
  }
  if (beforeLastMoverClockObject !== null) {
    beforeLastMoverClockObject.innerHTML = showThisMove+1 > StartPly+1 ?
      clockFromComment(MoveComments[showThisMove]) : initialLastMoverClock;
  }

  // show next move
  var theShowMoveTextObject = document.getElementById("GameNextMove");
  if (theShowMoveTextObject !== null) {
    if (showThisMove + 1 >= StartPly + PlyNumber) {
      text = gameResult[currentGame];
    } else {
      text = (Math.floor((showThisMove+1)/2) + 1) + 
        ((showThisMove+1) % 2 === 0 ? '. ' : '... ') + Moves[showThisMove+1];
    }
    theShowMoveTextObject.innerHTML = text; 
    theShowMoveTextObject.style.whiteSpace = 'nowrap';
  }

  // show last move
  theShowMoveTextObject = document.getElementById("GameLastMove");
  if (theShowMoveTextObject !== null) {
    if ((showThisMove >= StartPly) && Moves[showThisMove]) {
      text = (Math.floor(showThisMove/2) + 1) + 
       (showThisMove % 2 === 0 ? '. ' : '... ') + Moves[showThisMove];
    } else { text = ''; }
    theShowMoveTextObject.innerHTML = text; 
    theShowMoveTextObject.style.whiteSpace = 'nowrap';
  }

  if (showThisMove >= (StartPly-1)) {
    anchorName = 'Mv' + (showThisMove + 1);
    theAnchor = document.getElementById(anchorName);
    if (theAnchor !== null) { theAnchor.className = 'move moveOn'; }
    oldAnchor = showThisMove + 1;

    if (highlightOption) {
      if (showThisMove < StartPly) {
        highlightColFrom = highlightRowFrom = -1;
        highlightColTo   = highlightRowTo   = -1;
      } else {
        highlightColFrom = HistCol[0][showThisMove] === undefined ? -1 : HistCol[0][showThisMove];
        highlightRowFrom = HistRow[0][showThisMove] === undefined ? -1 : HistRow[0][showThisMove];
        highlightColTo   = HistCol[2][showThisMove] === undefined ? -1 : HistCol[2][showThisMove];
        highlightRowTo   = HistRow[2][showThisMove] === undefined ? -1 : HistRow[2][showThisMove];
      }
      highlightMove(highlightColFrom, highlightRowFrom, highlightColTo, highlightRowTo);
    }
  }
}

function SetHighlightOption(on) {
  highlightOption = on;
}

function SetHighlight(on) {
  SetHighlightOption(on);
  if (on) { HighlightLastMove(); }
  else { highlightMove(-1, -1, -1, -1); }
}

var lastColFromHighlighted = -1;
var lastRowFromHighlighted = -1;
var lastColToHighlighted = -1;
var lastRowToHighlighted = -1;
function highlightMove(colFrom, rowFrom, colTo, rowTo) {
  highlightSquare(lastColFromHighlighted, lastRowFromHighlighted, false);
  highlightSquare(lastColToHighlighted, lastRowToHighlighted, false);
  if ( highlightSquare(colFrom, rowFrom, true) ) {
    lastColFromHighlighted = colFrom;
    lastRowFromHighlighted = rowFrom;
  } else { lastColFromHighlighted = lastRowFromHighlighted = -1; }
  if ( highlightSquare(colTo, rowTo, true) ) {
    lastColToHighlighted = colTo;
    lastRowToHighlighted = rowTo;
  } else { lastColToHighlighted = lastRowToHighlighted = -1; }
}

function highlightSquare(col, row, on) {
  if ((col === undefined) || (row === undefined)) { return false; }
  if (! SquareOnBoard(col, row)) { return false; }
  // locates coordinates on HTML table
  if (IsRotated) { trow = row; tcol = 7 - col; }
  else { trow = 7 - row; tcol = col; }
  if (!(theObject = document.getElementById('tcol' + tcol + 'trow' + trow))) { return false; }
  if (on) { theObject.className = (trow+tcol)%2 === 0 ? "highlightWhiteSquare" : "highlightBlackSquare"; }
  else { theObject.className = (trow+tcol)%2 === 0 ? "whiteSquare" : "blackSquare"; }
  return true;
}

function fixCommonPgnMistakes(text) {
  text = text.replace(/\u00BD/g,"1/2"); // replace "half fraction" char with "1/2"
  text = text.replace(/[\u2010-\u2015]/g,"-"); // replace "hyphens" chars with "-"
  text = text.replace(/\u2024/g,"."); // replace "one dot leader" char with "."
  text = text.replace(/[\u2025-\u2026]/g,"..."); // replace "two dot leader" and "ellipsis" chars with "..."
  return text;
}

function pgnGameFromPgnText(pgnText) {

  pgnText = fixCommonPgnMistakes(pgnText);

  // replace < and > with html entities: avoid html injection from PGN data
  pgnText = pgnText.replace(/</g, "&lt;");
  pgnText = pgnText.replace(/>/g, "&gt;");

  lines = pgnText.split("\n");
  inGameHeader = false;
  inGameBody = false;
  gameIndex = -1;
  pgnGame.length = 0;
  for(ii in lines) {

    // PGN standard: lines starting with % must be ignored
    if(lines[ii].charAt(0) == '%') { continue; }

    if(pgnHeaderTagRegExp.test(lines[ii]) === true) {
      if(!inGameHeader) {
        gameIndex++;
        pgnGame[gameIndex] = '';
      }
      inGameHeader = true;
      inGameBody = false;
    } else {
      if(inGameHeader) {
        inGameHeader = false;
        inGameBody = true;
      }
    }
    lines[ii] = lines[ii].replace(/^\s*/,"");
    lines[ii] = lines[ii].replace(/\s*$/,"");
    if (gameIndex >= 0) { pgnGame[gameIndex] += lines[ii] + ' \n'; } 
  }

  numberOfGames = pgnGame.length;

  return (gameIndex >= 0);
}

var LOAD_PGN_FROM_PGN_URL_FAIL = 0;
var LOAD_PGN_FROM_PGN_URL_OK = 1;
var LOAD_PGN_FROM_PGN_URL_UNMODIFIED = 2;
function loadPgnFromPgnUrl(pgnUrl){
  
  LiveBroadcastLastRefreshedLocal = (new Date()).toLocaleString();

  var http_request = false;
    if (window.XMLHttpRequest) { // not IE
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
        http_request.overrideMimeType('text/plain');
      }
    } else if (window.ActiveXObject) { // IE
      try { http_request = new ActiveXObject("Msxml2.XMLHTTP"); }
      catch (e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch (e) { }
      }
    }
  if (!http_request) {
    myAlert('error: XMLHttpRequest failed for PGN URL\n' + pgnUrl, true);
    return LOAD_PGN_FROM_PGN_URL_FAIL; 
  }

  try {
    // anti-caching #1: add random parameter
    urlRandomizer = (LiveBroadcastDelay > 0) ? "?nocahce=" + Math.random() : "";
    http_request.open("GET", pgnUrl + urlRandomizer, false);
    // anti-caching #2: add header option
    if (LiveBroadcastDelay > 0) {
      http_request.setRequestHeader( "If-Modified-Since", LiveBroadcastLastModifiedHeader );
    }
    http_request.send(null);
  } catch(e) {
    myAlert('error: request failed for PGN URL\n' + pgnUrl, true);
    return LOAD_PGN_FROM_PGN_URL_FAIL;
  }

  if ( (http_request.readyState == 4) && 
    ((http_request.status == 200) || (http_request.status === 0) || (http_request.status == 304)) ) {

    if (http_request.status == 304) {
      if (LiveBroadcastDelay > 0) { return LOAD_PGN_FROM_PGN_URL_UNMODIFIED; }
      else { 
        myAlert('error: unexpected unmodified PGN URL when not in live mode');
        return LOAD_PGN_FROM_PGN_URL_FAIL;
      }

// dirty hack for Opera's failure reporting 304 status
    } else if (window.opera && (! http_request.responseText) && (http_request.status === 0)) {
      http_request.abort(); 
      return LOAD_PGN_FROM_PGN_URL_UNMODIFIED;
// end of dirty hack

    } else if (! pgnGameFromPgnText(http_request.responseText)) {
      myAlert('error: no games found in PGN file\n' + pgnUrl, true);
      return LOAD_PGN_FROM_PGN_URL_FAIL;
    } else {
      if (LiveBroadcastDelay > 0) {
        LiveBroadcastLastModifiedHeader = http_request.getResponseHeader("Last-Modified");
        if (LiveBroadcastLastModifiedHeader) { 
          LiveBroadcastLastModified = new Date(LiveBroadcastLastModifiedHeader); 
          LiveBroadcastLastReceivedLocal = (new Date()).toLocaleString();
        }
        else { LiveBroadcastLastModified_Reset(); }
      }
    }
  } else { 
    myAlert('error: failed reading PGN from URL\n' + pgnUrl, true);
    return LOAD_PGN_FROM_PGN_URL_FAIL;
  }

  return LOAD_PGN_FROM_PGN_URL_OK;
}

function SetPgnUrl(url) {
  pgnUrl = url;
}


function LiveBroadcastLastModified_Reset() {
  LiveBroadcastLastModified = new Date(0);
  LiveBroadcastLastModifiedHeader = LiveBroadcastLastModified.toUTCString();
}

function LiveBroadcastLastReceivedLocal_Reset() {
  LiveBroadcastLastReceivedLocal = 'unavailable';
}

function LiveBroadcastLastModified_ServerTime() {
  return LiveBroadcastLastModified.getTime() === 0 ? 'unavailable' : LiveBroadcastLastModifiedHeader; 
}

function pauseLiveBroadcast() {
  if (LiveBroadcastDelay === 0) { return; }
  LiveBroadcastPaused = true;
  clearTimeout(LiveBroadcastInterval);
  LiveBroadcastInterval = null;
}

function restartLiveBroadcast() {
  if (LiveBroadcastDelay === 0) { return; }
  LiveBroadcastPaused = false;
  refreshPgnSource();
}

function checkLiveBroadcastStatus() {

  if (LiveBroadcastDelay === 0) { 
    LiveBroadcastEnded = false;
    LiveBroadcastStatusString = "";
    return; 
  }

  // broadcast started yet?
  // check for fake LiveBroadcastPlaceholderPgn game when no PGN file is found
  if ((LiveBroadcastStarted === false) || ((pgnGame === undefined) ||
    ((numberOfGames == 1) && (gameEvent[0] == LiveBroadcastPlaceholderEvent)))) {
    LiveBroadcastEnded = false;
    LiveBroadcastStatusString = "live broadcast yet to start";
  } else {
    // broadcast started with good PGN
    liveGamesRunning = 0;
    for (ii=0; ii<numberOfGames; ii++) {
      if (gameResult[ii].indexOf('*') >= 0) { liveGamesRunning++; }
    }
    LiveBroadcastEnded = (liveGamesRunning === 0);

    LiveBroadcastStatusString = LiveBroadcastEnded ? "live broadcast ended" :
      "live games: " + liveGamesRunning + " &nbsp; finished: " + (numberOfGames - liveGamesRunning);
  }

  if (theObject = document.getElementById("GameLiveStatus"))
  { theObject.innerHTML = LiveBroadcastStatusString; }

  if (theObject = document.getElementById("GameLiveLastRefreshed"))
  { theObject.innerHTML = LiveBroadcastLastRefreshedLocal; }
  if (theObject = document.getElementById("GameLiveLastReceived"))
  { theObject.innerHTML = LiveBroadcastLastReceivedLocal; }
  if (theObject = document.getElementById("GameLiveLastModifiedServer"))
  { theObject.innerHTML = LiveBroadcastLastModified_ServerTime(); }
}

function restartLiveBroadcastTimeout() {
  if (LiveBroadcastDelay === 0) { return; }
  if (LiveBroadcastInterval) { clearTimeout(LiveBroadcastInterval); LiveBroadcastInterval = null; }
  checkLiveBroadcastStatus();
  needRestart = (!LiveBroadcastEnded);
  if ((needRestart === true) && (!LiveBroadcastPaused)) {
    LiveBroadcastInterval = setTimeout("refreshPgnSource()", LiveBroadcastDelay * 60000);
  }
  LiveBroadcastTicker++;
}

var LiveBroadcastFoundOldGame = false;
var LiveBroadcastGameLoadFailures = 0;
var LiveBroadcastGameLoadFailuresThreshold = 5;
function refreshPgnSource() {
  if (LiveBroadcastDelay === 0) { return; }
  if (LiveBroadcastInterval) { clearTimeout(LiveBroadcastInterval); LiveBroadcastInterval = null; }
  if (LiveBroadcastDemo) {
    addedPly = 0;
    for(ii=0;ii<numberOfGames;ii++) {
      rnd = Math.random();
      if      (rnd <= 0.05) { newPly = 3; } //  5% of times add 3 ply
      else if (rnd <= 0.20) { newPly = 2; } // 15% of times add 2 ply
      else if (rnd <= 0.60) { newPly = 1; } // 40% of times add 1 ply
      else                  { newPly = 0; } // 40% of times add 0 ply
      if (gameDemoMaxPly[ii] <= gameDemoLength[ii]) { 
        gameDemoMaxPly[ii] += newPly;
        addedPly += newPly;
      }
    }    
    if (addedPly > 0) { LiveBroadcastLastReceivedLocal = (new Date()).toLocaleString(); }
  }

  loadPgnFromPgnUrlResult = loadPgnFromPgnUrl(pgnUrl);
  if (LiveBroadcastDemo && (loadPgnFromPgnUrlResult == LOAD_PGN_FROM_PGN_URL_UNMODIFIED)) {
    loadPgnFromPgnUrlResult = LOAD_PGN_FROM_PGN_URL_OK;
  }

  switch ( loadPgnFromPgnUrlResult ) {
  
    case LOAD_PGN_FROM_PGN_URL_FAIL:
      LiveBroadcastGameLoadFailures++;
      if (LiveBroadcastGameLoadFailures >= LiveBroadcastGameLoadFailuresThreshold) {
        LiveBroadcastStarted = false;
        pgnGameFromPgnText(LiveBroadcastPlaceholderPgn);
        LiveBroadcastLastModified_Reset();
        LiveBroadcastLastReceivedLocal_Reset();
        initialGame = 1;
        firstStart = true;
        textSelectOptions = '';
        LoadGameHeaders();
        Init();
        checkLiveBroadcastStatus();
        customFunctionOnPgnTextLoad();
      } else { checkLiveBroadcastStatus(); }
      break;

    case LOAD_PGN_FROM_PGN_URL_OK:
      LiveBroadcastGameLoadFailures = 0;
      LiveBroadcastStarted = true;

      oldGameWhite = gameWhite[currentGame];
      oldGameBlack = gameBlack[currentGame];
      oldGameEvent = gameEvent[currentGame];
      oldGameRound = gameRound[currentGame];
      oldGameSite  = gameSite[currentGame];
      oldGameDate  = gameDate[currentGame];

      initialGame = currentGame + 1;
      firstStart = true;
      textSelectOptions = '';

      oldCurrentPly = CurrentPly != StartPly + PlyNumber ? CurrentPly : -1;

      oldAutoplay = isAutoPlayOn;
      if (isAutoPlayOn) { SetAutoPlay(false); }

      LoadGameHeaders();
      LiveBroadcastFoundOldGame = false;
      for (ii=0; ii<numberOfGames; ii++) {
        LiveBroadcastFoundOldGame = 
          (gameWhite[ii]==oldGameWhite) && (gameBlack[ii]==oldGameBlack) && 
          (gameEvent[ii]==oldGameEvent) && (gameRound[ii]==oldGameRound) &&
          (gameSite[ii] ==oldGameSite ) && (gameDate[ii] ==oldGameDate );
        if (LiveBroadcastFoundOldGame) { break; }
      }
      if (LiveBroadcastFoundOldGame) { initialGame = ii + 1; }

      if (LiveBroadcastFoundOldGame && (oldCurrentPly >= 0)) { 
        oldInitialHalfmove = initialHalfmove; 
        initialHalfmove = oldCurrentPly;
      }
  
      Init();

      if (LiveBroadcastFoundOldGame && (oldCurrentPly >= 0)) { 
        initialHalfmove = oldInitialHalfmove; 
      } 
  
      checkLiveBroadcastStatus();
      customFunctionOnPgnTextLoad();

      if (LiveBroadcastFoundOldGame && oldAutoplay) { SetAutoPlay(true); }

      break;

    case LOAD_PGN_FROM_PGN_URL_UNMODIFIED: 
      LiveBroadcastGameLoadFailures = 0;
      checkLiveBroadcastStatus();
      break;

    default:
      break;

  }

  restartLiveBroadcastTimeout();
}


function createBoard(){

  if (theObject = document.getElementById("GameBoard")) {
    theObject.innerHTML = '<DIV STYLE="font-size: small; font-family: sans-serif; ' +
      'padding: 10px; text-align: center;">' + 
      '...loading PGN data<br />please wait...</DIV>';
  }

  if (pgnUrl) {
    switch (loadPgnFromPgnUrl(pgnUrl)) {
      case LOAD_PGN_FROM_PGN_URL_OK:
        if (LiveBroadcastDelay > 0) { LiveBroadcastStarted = true; }
        Init();
        if (LiveBroadcastDelay > 0) { checkLiveBroadcastStatus(); }
        customFunctionOnPgnTextLoad();
        return;

      case LOAD_PGN_FROM_PGN_URL_FAIL:
        if (LiveBroadcastDelay === 0) {
          pgnGameFromPgnText(alertPgnHeader);
          Init();
          customFunctionOnPgnTextLoad();
          myAlert('error: failed loading games from PGN URL\n' + pgnUrl, true);
        } else { // live broadcast: wait for live show start
          LiveBroadcastStarted = false;
          LiveBroadcastLastModified_Reset();
          LiveBroadcastLastReceivedLocal_Reset();
          pgnGameFromPgnText(LiveBroadcastPlaceholderPgn); 
          Init();
	  checkLiveBroadcastStatus();
          customFunctionOnPgnTextLoad();
        }
        return;

      case LOAD_PGN_FROM_PGN_URL_UNMODIFIED:
        if (LiveBroadcastDelay > 0) { checkLiveBroadcastStatus(); }
        return;
     
      default:
        return;
 
    }
  } else if ( document.getElementById("pgnText") ) {
    if (document.getElementById("pgnText").tagName.toLowerCase() == "textarea") {
      tmpText = document.getElementById("pgnText").value;
    } else { // compatibility with pgn4web up to 1.77: <span> used for pgnText
      tmpText = document.getElementById("pgnText").innerHTML;
      // fixes browser issue removing \n from innerHTML
      if (tmpText.indexOf('\n') < 0) { tmpText = tmpText.replace(/((\[[^\[\]]*\]\s*)+)/g, "\n$1\n"); }
      // fixes browser issue replacing quotes with &quot; e.g. blackberry
      if (tmpText.indexOf('"') < 0) { tmpText = tmpText.replace(/(&quot;)/g, '"'); }
    }

    // no html header => add emptyPgnHeader
    if (pgnHeaderTagRegExp.test(tmpText) === false) { tmpText = emptyPgnHeader + tmpText; }

    if ( pgnGameFromPgnText(tmpText) ) {
      Init(); 
      customFunctionOnPgnTextLoad();
    } else {
      pgnGameFromPgnText(alertPgnHeader);
      Init();
      customFunctionOnPgnTextLoad();
      myAlert('error: no games found in PGN text', true);
    }   
    return;
  } else {
    pgnGameFromPgnText(alertPgnHeader);
    Init();
    customFunctionOnPgnTextLoad();
    myAlert('error: missing PGN URL location or pgnText in the HTML file', true);
    return;
  }
}

function setCurrentGameFromInitialGame() {
  switch (initialGame) {
    case "first":
      currentGame = 0;
      break;
    case "last":
      currentGame = numberOfGames - 1;
      break;
    case "random":
      currentGame = Math.floor(Math.random()*numberOfGames);
      break;
    default:
      if (isNaN(parseInt(initialGame,10))) { 
        currentGame = gameNumberSearchPgn(initialGame);
        if (!currentGame) { currentGame = 0; }
      } else {
        initialGame = parseInt(initialGame,10);
        initialGame = initialGame < 0 ? -Math.floor(-initialGame) : Math.floor(initialGame);
        if (initialGame < -numberOfGames) { currentGame = 0; }
        else if (initialGame < 0) { currentGame = numberOfGames + initialGame; }
        else if (initialGame === 0) { currentGame = Math.floor(Math.random()*numberOfGames); }
        else if (initialGame <= numberOfGames) { currentGame = (initialGame - 1); } 
        else { currentGame = numberOfGames - 1; }
      }
      break;
  }
}

function GoToInitialHalfmove() {
  switch (initialHalfmove) {
    case "start":
      GoToMove(0);
      break;
    case "end":
      GoToMove(StartPly + PlyNumber);
      break;
    case "random":
      GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber)));
      break;
    case "comment":
      GoToMove(0);
      MoveToNextComment();
      break;
    default:
      if (isNaN(initialHalfmove)) { initialHalfmove = 0; }
      initialHalfmove = parseInt(initialHalfmove,10);
      initialHalfmove = initialHalfmove < 0 ? -Math.floor(-initialHalfmove) : Math.floor(initialHalfmove);
      if (initialHalfmove < -3) { initialHalfmove = 0; }
      if (initialHalfmove == -3) { GoToMove(StartPly + PlyNumber); }
      else if (initialHalfmove == -2) { GoToMove(0); MoveToNextComment(); }
      else if (initialHalfmove == -1) { GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber))); }
      else { GoToMove(Math.floor(initialHalfmove)); }
      break;
  }
}

function Init(nextGame){

  if (nextGame !== undefined) {
    if ((! isNaN(nextGame)) && (nextGame >= 0) && (nextGame < numberOfGames)) {
      currentGame = parseInt(nextGame,10);
    } else { return; }
  }

  if (isAutoPlayOn) { SetAutoPlay(false); }

  InitImages();
  if (firstStart) {
    LoadGameHeaders();
    setCurrentGameFromInitialGame();
  }

  if ((gameSetUp[currentGame] !== undefined) && (gameSetUp[currentGame] != "1")) { InitFEN(); }
  else { InitFEN(gameFEN[currentGame]); }
  
  OpenGame(currentGame);
  
  RefreshBoard();
  CurrentPly = StartPly;
  HighlightLastMove(); 
  if (firstStart || alwaysInitialHalfmove) { GoToInitialHalfmove(); }
  else { customFunctionOnMove(); }
  // customFunctionOnMove here for consistency: null move starting new game

  if ((firstStart) && (autostartAutoplay)) { SetAutoPlay(true); }

  customFunctionOnPgnGameLoad();

  firstStart = false;
}


function InitFEN(startingFEN) {
  FenString = startingFEN !== undefined ? startingFEN : FenStringStart;
  
  // board reset
  var ii, jj;
  for (ii = 0; ii < 8; ++ii) {
    for (jj = 0; jj < 8; ++jj) {
      Board[ii][jj] = 0;
    }
  }

  // initial position
  var color, pawn;
  StartPly  = 0;
  MoveCount = StartPly;
  MoveColor = StartPly % 2;
  StartMove = 0;

  var newEnPassant = false;
  var newEnPassantCol;
  CastlingLong[0] = CastlingLong[1] = 0;
  CastlingShort[0] = CastlingShort[1] = 7;
  InitialHalfMoveClock = 0;

  if (FenString == FenStringStart) {
    for (color = 0; color < 2; ++color) {
      PieceType[color][0] = 1; // King
      PieceCol[color][0]  = 4;
      PieceType[color][1] = 2; // Queen
      PieceCol[color][1]  = 3;
      PieceType[color][6] = 3; // Rooks
      PieceType[color][7] = 3;
      PieceCol[color][6]  = 0;
      PieceCol[color][7]  = 7;
      PieceType[color][4] = 4; // Bishops
      PieceType[color][5] = 4;
      PieceCol[color][4]  = 2;
      PieceCol[color][5]  = 5;
      PieceType[color][2] = 5; // Knights
      PieceType[color][3] = 5;
      PieceCol[color][2]  = 1;
      PieceCol[color][3]  = 6;
      for (pawn = 0; pawn < 8; ++pawn) {
	PieceType[color][pawn+8] = 6;
	PieceCol[color][pawn+8]  = pawn;
      }
      for (ii = 0; ii < 16; ++ii) {
	PieceMoveCounter[color][ii] = 0;
	PieceRow[color][ii] = (1-color) * Math.floor(ii/8) + color * (7-Math.floor(ii/8));
      }
      for (ii = 0; ii < 16; ii++) {
        var col = PieceCol[color][ii];
        var row = PieceRow[color][ii];
        Board[col][row] = (1-2*color)*PieceType[color][ii];
      }
    }
  } else {
    var cc, kk, ll, nn, mm;
    for (ii = 0; ii < 2; ii++) {
      for (jj = 0; jj < 16; jj++) {
        PieceType[ii][jj] = -1;
        PieceCol[ii][jj] = 0;
        PieceRow[ii][jj] = 0;
        PieceMoveCounter[ii][jj] = 0;
      }
    }

    ii = 0; jj = 7; ll = 0; nn = 1; mm = 1; cc = FenString.charAt(ll++);
    while (cc != " ") {
      if (cc == "/") {
        if (ii != 8) {
          myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }
        ii = 0;
        jj--;
      }
      if (ii == 8) {
        myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
        InitFEN();
        return;
      }
      if (!isNaN(cc)) {
        ii += parseInt(cc,10);
        if ((ii < 0) || (ii > 8)) {
          myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }
      }
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(0)) {
        if (PieceType[0][0] != -1) {
          myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }     
        PieceType[0][0] = 1;
        PieceCol[0][0] = ii;
        PieceRow[0][0] = jj;
        ii++;
      }
      if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(0)) {
        if (PieceType[1][0] != -1) {
          myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }  
        PieceType[1][0] = 1;
        PieceCol[1][0] = ii;
        PieceRow[1][0] = jj;
        ii++;
      }
      for (kk = 1; kk < 6; kk++) {
        if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(kk)) {
          if (nn == 16) {
            myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
            InitFEN();
            return;
          }          
          PieceType[0][nn] = kk+1;
          PieceCol[0][nn] = ii;
          PieceRow[0][nn] = jj;
          nn++;
          ii++;
        }
        if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(kk)) {
          if (mm==16) {
            myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
            InitFEN();
            return;
          }  
          PieceType[1][mm] = kk+1;
          PieceCol[1][mm] = ii;
          PieceRow[1][mm] = jj;
          mm++;
          ii++;
        }
      }
      cc = ll < FenString.length ? FenString.charAt(ll++) : " ";
    }
    if ((ii != 8) || (jj !== 0)) {
      myAlert("error: invalid FEN ("+ll+") in game "+(currentGame+1)+"\n"+FenString, true);
      InitFEN();
      return;
    }
    if ((PieceType[0][0] == -1) || (PieceType[1][0] == -1)) {
      myAlert("error: invalid FEN missing King in game "+(currentGame+1)+"\n"+FenString, true);
      InitFEN();
      return;
    }
    if (ll == FenString.length) {
      FenString += " w ";
      FenString += FenPieceName.toUpperCase().charAt(0);
      FenString += FenPieceName.toUpperCase().charAt(1);
      FenString += FenPieceName.toLowerCase().charAt(0);
      FenString += FenPieceName.toLowerCase().charAt(1);      
      FenString += " - 0 1";
      ll++;
    }
    cc = FenString.charAt(ll++);
    if ((cc == "w") || (cc == "b")) {
      if (cc == "b") { 
        StartMove=1;
        StartPly += 1;
        MoveColor = 1;
      }
    } else {
      myAlert("error: invalid FEN ("+ll+") invalid active color in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }

    // set board
    for (color = 0; color < 2; ++color) {
      for (ii = 0; ii < 16; ii++) {
        if (PieceType[color][ii] != -1) {
   	  col = PieceCol[color][ii];
	  row = PieceRow[color][ii];
	  Board[col][row] = (1-2*color)*(PieceType[color][ii]);
	}
      }
    }
          
    ll++;
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN ("+ll+") missing castling availability in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    CastlingShort[0] = CastlingLong[0] = CastlingShort[1] = CastlingLong[1] = -1;
    cc = FenString.charAt(ll++);
    while (cc!=" ") {
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(0)) {
        for (CastlingShort[0] = 7; CastlingShort[0] >= 0; CastlingShort[0]--) {
          if (Board[CastlingShort[0]][0] == 3) { break; }
        }
        if (CastlingShort[0] < 0) {
          myAlert("error: invalid FEN ("+ll+") missing Rook at castling column " + cc, true);
          CastlingShort[0] = -1;
        }
      }
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(1)) {
        for (CastlingLong[0] = 0; CastlingLong[0] <= 7; CastlingLong[0]++) {
          if (Board[CastlingLong[0]][0] == 3) { break; }
        }
        if (CastlingLong[0] > 7) {
          myAlert("error: invalid FEN ("+ll+") missing Rook at castling column " + cc, true);
          CastlingLong[0] = -1;
        }
      }
      if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(0)) {
        for (CastlingShort[1] = 7; CastlingShort[1] >= 0; CastlingShort[1]--) {
          if (Board[CastlingShort[1]][7] == -3) { break; }
        }
        if (CastlingShort[1] < 0) {
          myAlert("error: invalid FEN ("+ll+") missing Rook at castling column " + cc, true);
          CastlingShort[1] = -1;
        }
      }
      if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(1)) {
        for (CastlingLong[1] = 0; CastlingLong[1] <= 7; CastlingLong[1]++) {
          if (Board[CastlingLong[1]][7] == -3) { break; }
        }
        if (CastlingLong[1] > 7) {
          myAlert("error: invalid FEN ("+ll+") missing Rook at castling column " + cc, true);
          CastlingLong[1] = -1;
        }
      }
      castlingRookCol = columnsLetters.toUpperCase().indexOf(cc);
      if (castlingRookCol >= 0) { color = 0; }
      else { 
        castlingRookCol = columnsLetters.toLowerCase().indexOf(cc);
        if (castlingRookCol >= 0) { color = 1; }
      }
      if (castlingRookCol >= 0) {
        if (Board[castlingRookCol][color*7] == (1-2*color) * 3) {
          if (castlingRookCol > PieceCol[color][0]) { CastlingShort[color] = castlingRookCol; }
          if (castlingRookCol < PieceCol[color][0]) { CastlingLong[color] = castlingRookCol; }
        } else {
          myAlert("error: invalid FEN ("+ll+") missing Rook at castling column " + cc, true);
        }
      }
      cc = ll<FenString.length ? FenString.charAt(ll++) : " ";
    }

    if (ll >= FenString.length) {
      myAlert("error: invalid FEN ("+ll+") missing en passant target square in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    cc = FenString.charAt(ll++);
    while (cc != " ") {
      if ((cc.charCodeAt(0)-97 >= 0) && (cc.charCodeAt(0)-97 <= 7)) {
        newEnPassant = true;
        newEnPassantCol = cc.charCodeAt(0)-97; 
      }
      cc = ll<FenString.length ? FenString.charAt(ll++) : " ";
    }
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN ("+ll+") missing halfmove clock in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    InitialHalfMoveClock = 0;
    cc = FenString.charAt(ll++);
    while (cc != " ") {
      if (isNaN(cc)) {
        myAlert("error: invalid FEN ("+ll+") invalid halfmove clock in game "+(currentGame+1)+"\n"+FenString, true);
        return;
      }
      InitialHalfMoveClock=InitialHalfMoveClock*10+parseInt(cc,10);
      cc = ll<FenString.length ? FenString.charAt(ll++) : " ";
    }
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN ("+ll+") missing fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    cc = FenString.substring(ll++);
    if (isNaN(cc)) {
      myAlert("error: invalid FEN ("+ll+") invalid fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    if (cc <= 0) {
      myAlert("error: invalid FEN ("+ll+") invalid fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    StartPly += 2*(parseInt(cc,10)-1);

    HistEnPassant[StartPly-1] = newEnPassant;
    HistEnPassantCol[StartPly-1] = newEnPassantCol;
  }
}

function SetImageType(extension) {
  imageType = extension;
}

function InitImages() {
  if (ImagePathOld === ImagePath) { return; }

  if ((ImagePath.length > 0) && (ImagePath[ImagePath.length-1] != '/')) {
    ImagePath += '/';
  }

  ClearImg = new Image();
  ClearImg.src = ImagePath+'clear.'+imageType;

  var color;
  ColorName = new Array ("w", "b");
  for (color = 0; color < 2; ++color) {
    PiecePicture[color][1] = new Image();
    PiecePicture[color][1].src = ImagePath + ColorName[color] + 'k.'+imageType;
    PiecePicture[color][2] = new Image();
    PiecePicture[color][2].src = ImagePath + ColorName[color] + 'q.'+imageType;
    PiecePicture[color][3] = new Image();
    PiecePicture[color][3].src = ImagePath + ColorName[color] + 'r.'+imageType;
    PiecePicture[color][4] = new Image();
    PiecePicture[color][4].src = ImagePath + ColorName[color] + 'b.'+imageType;
    PiecePicture[color][5] = new Image();
    PiecePicture[color][5].src = ImagePath + ColorName[color] + 'n.'+imageType;
    PiecePicture[color][6] = new Image();
    PiecePicture[color][6].src = ImagePath + ColorName[color] + 'p.'+imageType;
  }
  ImagePathOld = ImagePath;
}


function IsCheck(col, row, color) {
  var ii, jj;
  var sign = 2*color-1; // white or black

  // other king giving check?
  if ((Math.abs(PieceCol[1-color][0]-col) <= 1) &&
      (Math.abs(PieceRow[1-color][0]-row) <= 1)) { return true; }

  // knight giving check?
  for (ii = -2; ii <= 2; ii += 4) {
    for(jj = -1; jj <= 1; jj += 2) {
      if (SquareOnBoard(col+ii, row+jj)) {
	if (Board[col+ii][row+jj] == sign*5) { return true; }
      }
      if (SquareOnBoard(col+jj, row+ii)) {
	if (Board[col+jj][row+ii] == sign*5) { return true; }
      }
    }
  }

  // pawn giving check?
  for (ii = -1; ii <= 1; ii += 2) {
    if (SquareOnBoard(col+ii, row-sign)) {
      if (Board[col+ii][row-sign] == sign*6) { return true; }
    }
  }

  // queens, rooks and bishops?
  for (ii = -1; ii <= 1; ++ii) {
    for (jj = -1; jj <= 1; ++jj) {
      if ((ii !== 0) || (jj !== 0)) {
	var checkCol  = col+ii;
	var checkRow  = row+jj;
	var thisPiece = 0;

	while (SquareOnBoard(checkCol, checkRow) && (thisPiece === 0)) {
	  thisPiece = Board[checkCol][checkRow];
	  if (thisPiece === 0){
	    checkCol += ii;
	    checkRow += jj;
	  } else {
	    if (thisPiece  == sign*2) { return true; }
	    if ((thisPiece == sign*3) && ((ii === 0) || (jj === 0))) { return true; }
	    if ((thisPiece == sign*4) && ((ii !== 0) && (jj !== 0))) { return true; }
	  }
	}
      }
    }
  }
  return false;
}


function fixRegExp(exp) {
  return exp.replace(/([\[\]\(\)\{\}\.\*\+\^\$\|\?\\])/g, "\\$1");
}

function LoadGameHeaders(){
  var ii;

  gameEvent.length = gameSite.length = gameRound.length = gameDate.length = 0;
  gameWhite.length = gameBlack.length = gameResult.length = 0;
  gameSetUp.length = gameFEN.length = 0;
  gameInitialWhiteClock.length = gameInitialBlackClock.length = 0;
  gameVariant.length = 0;

  pgnHeaderTagRegExpGlobal.exec(""); // coping with IE bug when reloading PGN e.g. inputform.html
  for (ii = 0; ii < numberOfGames; ++ii) {
    var ss = pgnGame[ii];
    var parse;
    gameEvent[ii] = gameSite[ii] = gameRound[ii] = gameDate[ii] = "";
    gameWhite[ii] = gameBlack[ii] = gameResult[ii] = "";
    gameInitialWhiteClock[ii] = gameInitialBlackClock[ii] = "";
    gameVariant[ii] = "";
    while ((parse = pgnHeaderTagRegExpGlobal.exec(ss)) !== null) {
      if      (parse[1] == 'Event')      { gameEvent[ii]  = parse[2]; }
      else if (parse[1] == 'Site')       { gameSite[ii]   = parse[2]; }
      else if (parse[1] == 'Round')      { gameRound[ii]  = parse[2]; }
      else if (parse[1] == 'Date')       { gameDate[ii]   = parse[2]; }
      else if (parse[1] == 'White')      { gameWhite[ii]  = parse[2]; }
      else if (parse[1] == 'Black')      { gameBlack[ii]  = parse[2]; }
      else if (parse[1] == 'Result')     { gameResult[ii] = parse[2]; }
      else if (parse[1] == 'SetUp')      { gameSetUp[ii]  = parse[2]; }
      else if (parse[1] == 'FEN')        { gameFEN[ii]    = parse[2]; }
      else if (parse[1] == 'WhiteClock') { gameInitialWhiteClock[ii] = parse[2]; }
      else if (parse[1] == 'BlackClock') { gameInitialBlackClock[ii] = parse[2]; }
      else if (parse[1] == 'Variant')    { gameVariant[ii] = parse[2]; }
    }
  }
  if ((LiveBroadcastDemo) && (numberOfGames > 0)) {
    for (ii = 0; ii < numberOfGames; ++ii) {
       if (gameDemoLength[ii] === undefined) {
         InitFEN(gameFEN[ii]);
         ParsePGNGameString(pgnGame[ii]);
         gameDemoLength[ii] = PlyNumber;
       }
       if (gameDemoMaxPly[ii] === undefined) { gameDemoMaxPly[ii] = 0; }
       if (gameDemoMaxPly[ii] <= gameDemoLength[ii]) { gameResult[ii] = '*'; }
    }
  }
  return;
}


function MoveBackward(diff) {

  // CurrentPly counts from 1, starting position 0
  var goFromPly  = CurrentPly - 1;
  var goToPly    = goFromPly  - diff;
  if (goToPly < StartPly) { goToPly = StartPly-1; }

  // reconstruct old position ply by ply
  for(var thisPly = goFromPly; thisPly > goToPly; --thisPly) {
    CurrentPly--;
    MoveColor = 1-MoveColor;

    // moved piece back to original square
    var chgPiece = HistPieceId[0][thisPly];
    Board[PieceCol[MoveColor][chgPiece]][PieceRow[MoveColor][chgPiece]] = 0;

    Board[HistCol[0][thisPly]][HistRow[0][thisPly]] = HistType[0][thisPly] * (1-2*MoveColor);
    PieceType[MoveColor][chgPiece] = HistType[0][thisPly];
    PieceCol[MoveColor][chgPiece] = HistCol[0][thisPly];
    PieceRow[MoveColor][chgPiece] = HistRow[0][thisPly];
    PieceMoveCounter[MoveColor][chgPiece]--;

    // castling: rook back to original square
    chgPiece = HistPieceId[1][thisPly];
    if ((chgPiece >= 0) && (chgPiece < 16)) {
      Board[PieceCol[MoveColor][chgPiece]][PieceRow[MoveColor][chgPiece]] = 0;
      Board[HistCol[1][thisPly]][HistRow[1][thisPly]] = HistType[1][thisPly] * (1-2*MoveColor);
      PieceType[MoveColor][chgPiece] = HistType[1][thisPly];
      PieceCol[MoveColor][chgPiece] = HistCol[1][thisPly];
      PieceRow[MoveColor][chgPiece] = HistRow[1][thisPly];
      PieceMoveCounter[MoveColor][chgPiece]--;
    } 

    // capture: captured piece back to original square
    chgPiece -= 16;
    if ((chgPiece >= 0) && (chgPiece < 16)) {
      Board[PieceCol[1-MoveColor][chgPiece]][PieceRow[1-MoveColor][chgPiece]] = 0;
      Board[HistCol[1][thisPly]][HistRow[1][thisPly]] = HistType[1][thisPly] * (2*MoveColor-1);
      PieceType[1-MoveColor][chgPiece] = HistType[1][thisPly];
      PieceCol[1-MoveColor][chgPiece] = HistCol[1][thisPly];
      PieceRow[1-MoveColor][chgPiece] = HistRow[1][thisPly];
      PieceMoveCounter[1-MoveColor][chgPiece]--;
    } 
  }

  // old position reconstructed: refresh board
  RefreshBoard();
  HighlightLastMove(); 

  // autoplay: restart timeout
  if (AutoPlayInterval) { clearTimeout(AutoPlayInterval); AutoPlayInterval = null; }
  if (isAutoPlayOn) {
    if(goToPly >= StartPly) { AutoPlayInterval=setTimeout("MoveBackward(1)", Delay); }
    else { SetAutoPlay(false); }
  } 
  customFunctionOnMove();
}

function MoveForward(diff) {

  // CurrentPly counts from 1, starting position 0
  goToPly = CurrentPly + parseInt(diff,10);

  if (goToPly > (StartPly + PlyNumber)) { goToPly = StartPly + PlyNumber; }

  // reach to selected move checking legality
  parse = false;
  for(var thisPly = CurrentPly; thisPly < goToPly; ++thisPly) {
    var move = Moves[thisPly];
    if (! (parse = ParseMove(move, thisPly))) {
      text = (Math.floor(thisPly / 2) + 1) + ((thisPly % 2) === 0 ? '. ' : '... ');
      myAlert('error: invalid ply ' + text + move + ' in game ' + (currentGame+1), true);
      break;
    }
    MoveColor = 1-MoveColor; 
  }

  // new position: refresh board and update ply count
  CurrentPly = thisPly;
  RefreshBoard();
  HighlightLastMove(); 

  // autoplay: restart timeout
  if (AutoPlayInterval) { clearTimeout(AutoPlayInterval); AutoPlayInterval = null; }
  if (!parse) { SetAutoPlay(false); } 
  else if (thisPly == goToPly) {
    if (isAutoPlayOn) {
      if (goToPly < StartPly + PlyNumber) {
        AutoPlayInterval=setTimeout("MoveForward(1)", Delay);
      } else {
        if (autoplayNextGame) { AutoPlayInterval=setTimeout("AutoplayNextGame()", Delay); }
        else { SetAutoPlay(false); }
      }
    }
  }
  customFunctionOnMove();
}

function AutoplayNextGame() {
  if (fatalErrorNumSinceReset === 0) {
    if (numberOfGames > 0) {
      Init((currentGame + 1) % numberOfGames);
      if ((numberOfGames > 1) || (PlyNumber > 0)) {
        SetAutoPlay(true);
        return;
      }
    }
  }
  SetAutoPlay(false);
}

function MoveToNextComment() {
  for(ii=CurrentPly+1; ii<=StartPly+PlyNumber; ii++) {
    if (MoveComments[ii] !== '') { GoToMove(ii); break; }
  }
}

function MoveToPrevComment() {
  for(ii=(CurrentPly-1); ii>=0; ii--) {
    if (MoveComments[ii] !== '') { GoToMove(ii); break; }
  }
}


function OpenGame(gameId) {
  ParsePGNGameString(pgnGame[gameId]);
  currentGame = gameId;
 
  if (LiveBroadcastDemo) {
    if (gameDemoMaxPly[gameId] <= PlyNumber) { PlyNumber = gameDemoMaxPly[gameId]; }
  }
 
  PrintHTML();
}

function ParsePGNGameString(gameString) {

  var ss = gameString;
  // remove PGN tags and spaces at the end 
  ss = ss.replace(pgnHeaderTagRegExpGlobal, ''); 
  ss = ss.replace(/^\s/, '');
  ss = ss.replace(/\s$/, '');
  
  PlyNumber = 0;
  for (ii=0; ii<StartPly; ii++) { Moves[ii]=''; }
  MoveComments[StartPly+PlyNumber]='';

  for (start=0; start<ss.length; start++) {
  
    switch (ss.charAt(start)) {

      case ' ':
      case '\b':
      case '\f':
      case '\n':
      case '\r':
      case '\t':
        break;

      case '$':
        commentStart = start;
        commentEnd = commentStart + 1;
        while ('0123456789'.indexOf(ss.charAt(commentEnd)) >= 0) {
          commentEnd++;
          if (commentEnd == ss.length) { break; }
        }
        if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
        MoveComments[StartPly+PlyNumber] += ss.substring(commentStart, commentEnd);
        start = commentEnd;
        break;
      
      case '!':
      case '?':
        commentStart = start;
        commentEnd = commentStart + (((ss.charAt(start+1) == '?') || (ss.charAt(start+1) == '!')) ? 2 : 1);
        if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
        MoveComments[StartPly+PlyNumber] += ss.substring(commentStart, commentEnd);
        start = commentEnd;
        break;

      case '{':
        commentStart = start+1;
        commentEnd = ss.indexOf('}',start+1);
        if (commentEnd > 0){
          if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
          MoveComments[StartPly+PlyNumber] += ss.substring(commentStart, commentEnd); 
          start = commentEnd;
        }else{
          myAlert('error: missing end comment char } while parsing game ' + (currentGame+1), true);
          return;
        }
        break;

      case '%':
        // % must be first char of the line
        if ((start > 0) && (ss.charAt(start-1) != '\n')) { break; }
        commentStart = start+1;
        commentEnd = ss.indexOf('\n',start+1);
        if (commentEnd < 0) { commentEnd = ss.length; }
        // dont store % lines as comments
        // if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
        // MoveComments[StartPly+PlyNumber] += ss.substring(commentStart, commentEnd); 
        start = commentEnd;
        break;

      case ';':
        commentStart = start+1;
        commentEnd = ss.indexOf('\n',start+1);
        if (commentEnd < 0) { commentEnd = ss.length; }
        if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
        MoveComments[StartPly+PlyNumber] += ss.substring(commentStart, commentEnd); 
        start = commentEnd;
        break;

      case '(':
        openVariation = 1;
        variationStart = start;
        variationEnd = start+1;
        while ((openVariation > 0) && (variationEnd<ss.length)) {
          nextOpen = ss.indexOf('(', variationEnd);
          nextClosed = ss.indexOf(')', variationEnd);
          if (nextClosed < 0) {
            myAlert('error: missing end variation char ) while parsing game ' + (currentGame+1), true);
            return;
          }
          if ((nextOpen >= 0) && (nextOpen < nextClosed)) {
            openVariation++;
            variationEnd = nextOpen+1;
          } else {
            openVariation--;
            variationEnd = nextClosed+1;
          }
        }
        if (MoveComments[StartPly+PlyNumber].length>0) { MoveComments[StartPly+PlyNumber] += ' '; }
        MoveComments[StartPly+PlyNumber] += ss.substring(variationStart, variationEnd+1); 
        start = variationEnd;
        break;

      default:
        
        searchThis = '1-0';
        if (ss.indexOf(searchThis,start)==start) {
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '0-1';
        if (ss.indexOf(searchThis,start)==start) {
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '1/2-1/2';
        if (ss.indexOf(searchThis,start)==start) {
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '*';
        if (ss.indexOf(searchThis,start)==start) {
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        moveCount = Math.floor((StartPly+PlyNumber)/2)+1;
        searchThis = moveCount.toString()+'.';
        if(ss.indexOf(searchThis,start)==start) {
          start += searchThis.length;
          while ((ss.charAt(start) == '.') || (ss.charAt(start) == ' ') || (ss.charAt(start) == '\n') || (ss.charAt(start) == '\r')){start++;}
	}

        end = ss.indexOf(' ',start);
        end2 = ss.indexOf('$',start); if ((end2 > 0) && (end2 < end)) { end = end2; }
        end2 = ss.indexOf('{',start); if ((end2 > 0) && (end2 < end)) { end = end2; } 
        end2 = ss.indexOf(';',start); if ((end2 > 0) && (end2 < end)) { end = end2; }
        end2 = ss.indexOf('(',start); if ((end2 > 0) && (end2 < end)) { end = end2; } 
        end2 = ss.indexOf('!',start); if ((end2 > 0) && (end2 < end)) { end = end2; }
        end2 = ss.indexOf('?',start); if ((end2 > 0) && (end2 < end)) { end = end2; }
        if (end < 0) { end = ss.length; }
        move = ss.substring(start,end);
        Moves[StartPly+PlyNumber] = ClearMove(move);
        if (ss.charAt(end) == ' ') { start = end; } 
        else { start = end - 1; }
        if (Moves[StartPly+PlyNumber] !== "") { // to cope with misformed PGN data
          PlyNumber++;
          MoveComments[StartPly+PlyNumber]='';
        }
        break;
    }
  }
  for (ii=StartPly; ii<=StartPly+PlyNumber; ii++) {
    MoveComments[ii] = MoveComments[ii].replace(/\s+/g, " ");
    pgn4webCommentTmp = MoveComments[ii].match(/\[%pgn4web\s*(.*?)\]/);
    pgn4webMoveComments[ii] = pgn4webCommentTmp ? pgn4webCommentTmp[1] : "";
    MoveComments[ii] = translateNAGs(MoveComments[ii]);
    MoveComments[ii] = MoveComments[ii].replace(/\s+$/g, '');
  }
}

var NAG = new Array();
NAG[0] = '';       
NAG[1] = '!';  // 'good move'        
NAG[2] = '?';  // 'bad move'        
NAG[3] = '!!'; // 'very good move'       
NAG[4] = '??'; // 'very bad move'       
NAG[5] = '!?'; // 'speculative move'        
NAG[6] = '?!'; // 'questionable move'        
NAG[7] = 'forced move';
NAG[8] = 'singular move';
NAG[9] = 'worst move';
NAG[10] = 'drawish position';
NAG[11] = 'equal chances, quiet position';
NAG[12] = 'equal chances, active position';
NAG[13] = 'unclear position';
NAG[14] = 'White has a slight advantage';
NAG[15] = 'Black has a slight advantage';
NAG[16] = 'White has a moderate advantage';
NAG[17] = 'Black has a moderate advantage';
NAG[18] = 'White has a decisive advantage';
NAG[19] = 'Black has a decisive advantage';
NAG[20] = 'White has a crushing advantage';
NAG[21] = 'Black has a crushing advantage';
NAG[22] = 'White is in zugzwang';
NAG[23] = 'Black is in zugzwang';
NAG[24] = 'White has a slight space advantage';
NAG[25] = 'Black has a slight space advantage';
NAG[26] = 'White has a moderate space advantage';
NAG[27] = 'Black has a moderate space advantage';
NAG[28] = 'White has a decisive space advantage';
NAG[29] = 'Black has a decisive space advantage';
NAG[30] = 'White has a slight time (development) advantage';
NAG[31] = 'Black has a slight time (development) advantage';
NAG[32] = 'White has a moderate time (development) advantage';
NAG[33] = 'Black has a moderate time (development) advantage';
NAG[34] = 'White has a decisive time (development) advantage';
NAG[35] = 'Black has a decisive time (development) advantage';
NAG[36] = 'White has the initiative';
NAG[37] = 'Black has the initiative';
NAG[38] = 'White has a lasting initiative';
NAG[39] = 'Black has a lasting initiative';
NAG[40] = 'White has the attack';
NAG[41] = 'Black has the attack';
NAG[42] = 'White has insufficient compensation for material deficit';
NAG[43] = 'Black has insufficient compensation for material deficit';
NAG[44] = 'White has sufficient compensation for material deficit';
NAG[45] = 'Black has sufficient compensation for material deficit';
NAG[46] = 'White has more than adequate compensation for material deficit';
NAG[47] = 'Black has more than adequate compensation for material deficit';
NAG[48] = 'White has a slight center control advantage';
NAG[49] = 'Black has a slight center control advantage';
NAG[50] = 'White has a moderate center control advantage';
NAG[51] = 'Black has a moderate center control advantage';
NAG[52] = 'White has a decisive center control advantage';
NAG[53] = 'Black has a decisive center control advantage';
NAG[54] = 'White has a slight kingside control advantage';
NAG[55] = 'Black has a slight kingside control advantage';
NAG[56] = 'White has a moderate kingside control advantage';
NAG[57] = 'Black has a moderate kingside control advantage';
NAG[58] = 'White has a decisive kingside control advantage';
NAG[59] = 'Black has a decisive kingside control advantage';
NAG[60] = 'White has a slight queenside control advantage';
NAG[61] = 'Black has a slight queenside control advantage';
NAG[62] = 'White has a moderate queenside control advantage';
NAG[63] = 'Black has a moderate queenside control advantage';
NAG[64] = 'White has a decisive queenside control advantage';
NAG[65] = 'Black has a decisive queenside control advantage';
NAG[66] = 'White has a vulnerable first rank';
NAG[67] = 'Black has a vulnerable first rank';
NAG[68] = 'White has a well protected first rank';
NAG[69] = 'Black has a well protected first rank';
NAG[70] = 'White has a poorly protected king';
NAG[71] = 'Black has a poorly protected king';
NAG[72] = 'White has a well protected king';
NAG[73] = 'Black has a well protected king';
NAG[74] = 'White has a poorly placed king';
NAG[75] = 'Black has a poorly placed king';
NAG[76] = 'White has a well placed king';
NAG[77] = 'Black has a well placed king';
NAG[78] = 'White has a very weak pawn structure';
NAG[79] = 'Black has a very weak pawn structure';
NAG[80] = 'White has a moderately weak pawn structure';
NAG[81] = 'Black has a moderately weak pawn structure';
NAG[82] = 'White has a moderately strong pawn structure';
NAG[83] = 'Black has a moderately strong pawn structure';
NAG[84] = 'White has a very strong pawn structure';
NAG[85] = 'Black has a very strong pawn structure';
NAG[86] = 'White has poor knight placement';
NAG[87] = 'Black has poor knight placement';
NAG[88] = 'White has good knight placement';
NAG[89] = 'Black has good knight placement';
NAG[90] = 'White has poor bishop placement';
NAG[91] = 'Black has poor bishop placement';
NAG[92] = 'White has good bishop placement';
NAG[93] = 'Black has good bishop placement';
NAG[84] = 'White has poor rook placement';
NAG[85] = 'Black has poor rook placement';
NAG[86] = 'White has good rook placement';
NAG[87] = 'Black has good rook placement';
NAG[98] = 'White has poor queen placement';
NAG[99] = 'Black has poor queen placement';
NAG[100] = 'White has good queen placement';
NAG[101] = 'Black has good queen placement';
NAG[102] = 'White has poor piece coordination';
NAG[103] = 'Black has poor piece coordination';
NAG[104] = 'White has good piece coordination';
NAG[105] = 'Black has good piece coordination';
NAG[106] = 'White has played the opening very poorly';
NAG[107] = 'Black has played the opening very poorly';
NAG[108] = 'White has played the opening poorly';
NAG[109] = 'Black has played the opening poorly';
NAG[110] = 'White has played the opening well';
NAG[111] = 'Black has played the opening well';
NAG[112] = 'White has played the opening very well';
NAG[113] = 'Black has played the opening very well';
NAG[114] = 'White has played the middlegame very poorly';
NAG[115] = 'Black has played the middlegame very poorly';
NAG[116] = 'White has played the middlegame poorly';
NAG[117] = 'Black has played the middlegame poorly';
NAG[118] = 'White has played the middlegame well';
NAG[119] = 'Black has played the middlegame well';
NAG[120] = 'White has played the middlegame very well';
NAG[121] = 'Black has played the middlegame very well';
NAG[122] = 'White has played the ending very poorly';
NAG[123] = 'Black has played the ending very poorly';
NAG[124] = 'White has played the ending poorly';
NAG[125] = 'Black has played the ending poorly';
NAG[126] = 'White has played the ending well';
NAG[127] = 'Black has played the ending well';
NAG[128] = 'White has played the ending very well';
NAG[129] = 'Black has played the ending very well';
NAG[130] = 'White has slight counterplay';
NAG[131] = 'Black has slight counterplay';
NAG[132] = 'White has moderate counterplay';
NAG[133] = 'Black has moderate counterplay';
NAG[134] = 'White has decisive counterplay';
NAG[135] = 'Black has decisive counterplay';
NAG[136] = 'White has moderate time control pressure';
NAG[137] = 'Black has moderate time control pressure';
NAG[138] = 'White has severe time control pressure';
NAG[139] = 'Black has severe time control pressure';

function translateNAGs(comment) {
  var jj, ii = 0;
  numString = "01234567890";
  while ((ii = comment.indexOf('$', ii)) >= 0) {
    jj=ii+1;
    while(('0123456789'.indexOf(comment.charAt(jj)) >= 0) && (jj<comment.length)) { 
      jj++; 
      if (jj == comment.length) { break; }
    }
    nag = parseInt(comment.substring(ii+1,jj),10);
    if ((nag !== undefined) && (NAG[nag] !== undefined)) {
      comment = comment.replace(comment.substring(ii,jj), '<SPAN CLASS="nag">' + NAG[nag] + '</SPAN>');
    }
    ii++;  
  }
  return comment;
}

function ParseMove(move, plyCount) {
//  move = move.replace(/[\+#]/g, ""); // patch this to pass through '+' and '#' signs
  var ii, ll;
  var remainder;
  var toRowMarker = -1;

  castleRook = -1;
  mvIsCastling =  0;
  mvIsPromotion =  0;
  mvCapture =  0;
  mvFromCol = -1;
  mvFromRow = -1;
  mvToCol = -1;
  mvToRow = -1;
  mvPiece = -1;
  mvPieceId = -1;
  mvPieceOnTo = -1;
  mvCaptured = -1;
  mvCapturedId = -1;

  // get destination column/row remembering what's left e.g. Rdxc3 exf8=Q+
  ii = 1;
  while(ii < move.length) {
    if (!isNaN(move.charAt(ii))) {
      mvToCol = move.charCodeAt(ii-1) - 97;
      mvToRow = move.charAt(ii)       -  1;
      remainder = move.substring(0, ii-1);
      toRowMarker = ii;
    }
    ++ii;
  }

  // final square did not make sense: maybe a castle?
  if ((mvToCol < 0) || (mvToCol > 7) || (mvToRow < 0) || (mvToRow > 7)) {
    if ((move.indexOf('O') >= 0) || (move.indexOf('o') >= 0) || (move.indexOf('0') >= 0)) {
      // long castling first: looking for o-o will get o-o-o too
      if (move.match('^[Oo0]-?[Oo0]-?[Oo0]$') !== null) {
	mvIsCastling = 1;
        mvPiece = 1;
        mvPieceId = 0;
        mvPieceOnTo = 1;
        mvFromCol = 4;
        mvToCol = 2;
        mvFromRow = 7*MoveColor;
        mvToRow = 7*MoveColor;
        return CheckLegality('O-O-O', plyCount);
      }
      if (move.match('^[Oo0]-?[Oo0]$') !== null) {
        mvIsCastling = 1;
        mvPiece = 1;
        mvPieceId = 0;
        mvPieceOnTo = 1;
	mvFromCol = 4;
	mvToCol = 6;
        mvFromRow = 7*MoveColor;
        mvToRow = 7*MoveColor;
	return CheckLegality('O-O', plyCount);
      }
      return false;
    } else { return false; }
  }

  // get piece and origin square: mark captures ('x' is there)
  ll = remainder.length;
  if (ll > 3) { return false; }
  mvPiece = -1; // make sure mvPiece is properly assigned later
  if (ll === 0) { mvPiece = 6; }
  else {
    for(ii = 1; ii < 6; ++ii) { if (remainder.charAt(0) == PieceCode[ii-1]) { mvPiece = ii; } }
    if (mvPiece == -1) { if (columnsLetters.toLowerCase().indexOf(remainder.charAt(0)) >= 0) { mvPiece = 6; } }
    if (mvPiece == -1) { return false; }
    if (remainder.charAt(ll-1) == 'x') { mvCapture = 1; }
    if (isNaN(move.charAt(ll-1-mvCapture))) {
      mvFromCol = move.charCodeAt(ll-1-mvCapture) - 97;
      if ((mvFromCol < 0) || (mvFromCol > 7)) { mvFromCol = -1; }
    } else {
      mvFromRow = move.charAt(ll-1-mvCapture) - 1;
      if ((mvFromRow < 0) || (mvFromRow > 7)) { mvFromRow = -1; }
    }
    
    if ( (ll > 1) && (!mvCapture) && (mvFromCol == -1) && (mvFromRow == -1) ) { return false; }
    if ( (mvPiece == 6) && (!mvCapture) && (mvFromCol == -1) && (mvFromRow == -1) ) { return false; }
    if ( (mvPiece == 6) && (mvFromCol == mvToCol) ) { return false; }
  }

  mvPieceOnTo = mvPiece;
  // "square to" occupied: capture (note en-passant case)
  if (Board[mvToCol][mvToRow] !== 0) { mvCapture = 1; }
  else {
    if ((mvPiece == 6) && (HistEnPassant[plyCount-1]) && 
        (mvToCol == HistEnPassantCol[plyCount-1]) &&
	(mvToRow == 5-3*MoveColor)) {
      mvCapture = 1;
    }
  }

  // move contains '=' or char after destination row: might be a promotion
  ii = move.indexOf('=');
  if (ii < 0) { ii = toRowMarker; }
  if ((ii > 0) && (ii < move.length-1)) {
    if (mvPiece == 6) {
      var newPiece = move.charAt(ii+1);
      if (newPiece == PieceCode[1]) { mvPieceOnTo = 2; }
      else if (newPiece == PieceCode[2]) { mvPieceOnTo = 3; }
      else if (newPiece == PieceCode[3]) { mvPieceOnTo = 4; }
      else if (newPiece == PieceCode[4]) { mvPieceOnTo = 5; }
      mvIsPromotion = 1;
    }
  }

  // which piece was captured: if nothing found must be en-passant
  if (mvCapture) {
    mvCapturedId = 15;
    while((mvCapturedId >= 0) && (mvCaptured < 0)) {
      if ((PieceType[1-MoveColor][mvCapturedId] >  0) &&
	(PieceCol[1-MoveColor][mvCapturedId] == mvToCol) &&
	(PieceRow[1-MoveColor][mvCapturedId] == mvToRow)) {
	mvCaptured = PieceType[1-MoveColor][mvCapturedId];
      } else { --mvCapturedId; }
    }
    if ((mvPiece == 6) && (mvCapturedId < 1) && (HistEnPassant[plyCount-1])) {
      mvCapturedId = 15;
      while((mvCapturedId >= 0) && (mvCaptured < 0)){
        if ((PieceType[1-MoveColor][mvCapturedId] == 6) &&
	  (PieceCol[1-MoveColor][mvCapturedId] == mvToCol) &&
	  (PieceRow[1-MoveColor][mvCapturedId] == 4-MoveColor)) {
	  mvCaptured = PieceType[1-MoveColor][mvCapturedId];
	} else { --mvCapturedId; }
      }
    }
  }

  // check move legality
  if (! CheckLegality(PieceCode[mvPiece-1], plyCount)) { return false; }

  // pawn moved => check if en-passant possible
  HistEnPassant[plyCount]    = false;
  HistEnPassantCol[plyCount] = -1;
  if (mvPiece == 6) {
     if (Math.abs(HistRow[0][plyCount]-mvToRow) == 2) {
       HistEnPassant[plyCount]    = true;
       HistEnPassantCol[plyCount] = mvToCol;
     }
  }
  return true;
}

function SetGameSelectorOptions(head, num, chEvent, chSite, chRound, chWhite, chBlack, chResult, chDate) {
  if (head !== null) { gameSelectorHead = head; }
  if (num !== null) { gameSelectorNum = num; }
  if (chEvent !== null)  { gameSelectorChEvent  = chEvent  > 32 ? 32 : chEvent;  }
  if (chSite !== null)   { gameSelectorChSite   = chSite   > 32 ? 32 : chSite;   }
  if (chRound !== null)  { gameSelectorChRound  = chRound  > 32 ? 32 : chRound;  }
  if (chWhite !== null)  { gameSelectorChWhite  = chWhite  > 32 ? 32 : chWhite;  } 
  if (chBlack !== null)  { gameSelectorChBlack  = chBlack  > 32 ? 32 : chBlack;  }
  if (chResult !== null) { gameSelectorChResult = chResult > 32 ? 32 : chResult; }
  if (chDate !== null)   { gameSelectorChDate   = chDate   > 32 ? 32 : chDate;   } 
}

var clickedSquareInterval = null;
function clickedSquare(ii, jj) {
  if (clickedSquareInterval) { return; } // dont trigger effect twice
  squareId = 'tcol' + jj + 'trow' + ii;
  theObject = document.getElementById(squareId);
  originalClass = theObject.className;
  newClass = (ii+jj)%2 === 0 ? "blackSquare" : "whiteSquare";
  theObject.className = newClass;
  clickedSquareInterval = setTimeout("reset_after_click(" + ii + "," + jj + ",'" + originalClass + "','" + newClass + "')", 66);
}

function reset_after_click (ii, jj, originalClass, newClass) {
  if (theObject = document.getElementById('tcol' + jj + 'trow' + ii)) {
    // square class changed again by pgn4web already: dont touch it anymore e.g. autoplay
    if (theObject.className == newClass) { theObject.className = originalClass; }
    clickedSquareInterval = null;
  }
}


var lastSearchPgnExpression = "";
function gameNumberSearchPgn(searchExpression, backward) {
  lastSearchPgnExpression = searchExpression;
  if (searchExpression === "") { return false; }
  // replace newline with spaces so that we can use regexp "." on whole game
  newlinesRegExp = new RegExp("[\n\r]", "gm");
  searchExpressionRegExp = new RegExp(searchExpression, "im");
  // at start currentGame might still be -1
  currentGameSearch = (currentGame < 0) || (currentGame >= numberOfGames) ? 0 : currentGame;
  delta = backward ? -1 : +1;
  for (checkGame = (currentGameSearch + delta + numberOfGames) % numberOfGames; 
       checkGame != currentGameSearch; 
       checkGame = (checkGame + delta + numberOfGames) % numberOfGames) { 
    if (pgnGame[checkGame].replace(newlinesRegExp, " ").match(searchExpressionRegExp)) {
      return checkGame;
    }
  }
  return false;
}

function searchPgnGame(searchExpression, backward) {
  lastSearchPgnExpression = searchExpression;
  if (theObject = document.getElementById('searchPgnExpression')) 
  { theObject.value = searchExpression; }
  if ((searchExpression === "") || (! searchExpression)) { return; }
  if (numberOfGames < 2) { return; }
  checkGame = gameNumberSearchPgn(searchExpression, backward);
  if ((checkGame !== false) && (checkGame != currentGame)) { Init(checkGame); }
}

function searchPgnGamePrompt() {
  if (numberOfGames < 2) { 
    alert("info: search prompt disabled with less than 2 games"); 
    return;
  }
  searchExpression = prompt("Please enter search pattern for PGN games:", lastSearchPgnExpression);
  if (searchExpression) { searchPgnGame(searchExpression); }
}

function searchPgnGameForm() {
  if (theObject = document.getElementById('searchPgnExpression')) 
  { searchPgnGame(document.getElementById('searchPgnExpression').value); }
}


var tableSize = 0;
function PrintHTML() {
  var ii, jj;
  var text;

  // 8x8 table chessboard

  if (theObject = document.getElementById("GameBoard")) {
    text = '<TABLE CLASS="boardTable" ID="boardTable" CELLSPACING=0 CELLPADDING=0';
    text += (tableSize > 0) ? ' STYLE="width: ' + tableSize + 'px; height: ' + tableSize + 'px;">' : '>';
    for (ii = 0; ii < 8; ++ii) {
      text += '<TR>';
      for (jj = 0; jj < 8; ++jj) {
        squareId = 'tcol' + jj + 'trow' + ii;
        imageId = 'img_' + squareId;
        linkId = 'link_' + squareId;
        text += (ii+jj)%2 === 0 ? 
          '<TD CLASS="whiteSquare" ID="' + squareId + '" BGCOLOR="#FFFFFF"' :
          '<TD CLASS="blackSquare" ID="' + squareId + '" BGCOLOR="#D3D3D3"';
        text += ' ALIGN="center" VALIGN="middle" ONCLICK="clickedSquare(' + ii + ',' + jj + ')">';
        squareCoord = IsRotated ? String.fromCharCode(72-jj,49+ii) : String.fromCharCode(jj+65,56-ii);
        squareTitle = squareCoord;
        if (boardTitle[jj][ii] !== '') { squareTitle += ': ' + boardTitle[jj][ii]; }
        text += '<A HREF="javascript:boardOnClick[' + jj + '][' + ii + ']()" ' +
          'ID="' + linkId + '" TITLE="' + squareTitle + '" ' + 
          'STYLE="text-decoration: none; outline: none;" ' +
          'ONFOCUS="this.blur()">' + 
          '<IMG CLASS="pieceImage" ID="' + imageId + '" ' + 
          ' SRC="'+ ClearImg.src +'" BORDER=0></A></TD>';
      }
      text += '</TR>';
    }
    text += '</TABLE>';

    theObject.innerHTML = text;
  }

  if (theObject = document.getElementById("boardTable")) {
    tableSize = theObject.offsetWidth;
    if (tableSize > 0) { // coping with browser always returning 0 to offsetWidth
      theObject.style.height = tableSize + "px";
    }
  }

  // control buttons

  if (theObject = document.getElementById("GameButtons")) {
    numberOfButtons = 5;
    spaceSize = 3;
    buttonSize = (tableSize - spaceSize*(numberOfButtons - 1)) / numberOfButtons;
    text = '<FORM NAME="GameButtonsForm" STYLE="display:inline;">' +
      '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>' + 
      '<TR><TD>' +
      '<INPUT ID="startButton" TYPE="BUTTON" VALUE="&lt;&lt;" STYLE="';
    if (buttonSize > 0) { text += 'width: ' + buttonSize + 'px;'; }
    text += '"; CLASS="buttonControl" TITLE="go to game start" ' +
      ' ID="btnGoToStart" onClick="javascript:GoToMove(StartPly)" ONFOCUS="this.blur()">' +
      '</TD>' +
      '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
      '</TD><TD>' +
      '<INPUT ID="backButton" TYPE="BUTTON" VALUE="&lt;" STYLE="';
    if (buttonSize > 0) { text += 'width: ' + buttonSize + 'px;'; }
    text += '"; CLASS="buttonControl" TITLE="move backward" ' +
      ' ID="btnMoveBackward1" onClick="javascript:MoveBackward(1)" ONFOCUS="this.blur()">' +
      '</TD>' +
      '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
      '</TD><TD>';
    text += '<INPUT ID="autoplayButton" TYPE="BUTTON" VALUE=' +
      (isAutoPlayOn ? "=" : "+") + ' STYLE="';
    if (buttonSize > 0) { text += 'width: ' + buttonSize + 'px;'; }
    text += isAutoPlayOn ?
      '"; CLASS="buttonControlStop" TITLE="toggle autoplay (stop)" ' :
      '"; CLASS="buttonControlPlay" TITLE="toggle autoplay (start)" ';
    text += ' ID="btnPlay" NAME="AutoPlay" onClick="javascript:SwitchAutoPlay()" ONFOCUS="this.blur()">' +
      '</TD>' +
      '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
      '</TD><TD>' +
      '<INPUT ID="forwardButton" TYPE="BUTTON" VALUE="&gt;" STYLE="';
    if (buttonSize > 0) { text += 'width: ' + buttonSize + 'px;'; }
    text += '"; CLASS="buttonControl" TITLE="move forward" ' +
      ' ID="btnMoveForward1" onClick="javascript:MoveForward(1)" ONFOCUS="this.blur()">' +
      '</TD>' +
      '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
      '</TD><TD>' +
      '<INPUT ID="endButton" TYPE="BUTTON" VALUE="&gt;&gt;" STYLE="';
    if (buttonSize > 0) { text += 'width: ' + buttonSize + 'px;'; }
    text += '"; CLASS="buttonControl" TITLE="go to game end" ' +
      ' ID="btnGoToEnd" onClick="javascript:GoToMove(StartPly + PlyNumber)" ONFOCUS="this.blur()">' +
      '</TD></TR></TABLE></FORM>';

    theObject.innerHTML = text;
  }
  
  // game selector

  if (theObject = document.getElementById("GameSelector")) {
    if (firstStart) { textSelectOptions=''; }
    if (numberOfGames < 2) {
      // theObject.innerHTML = ''; // replaced with code below to cope with IE bug
      while (theObject.firstChild) { theObject.removeChild(theObject.firstChild); }
      textSelectOptions = '';
    } else {
      if(textSelectOptions === '') {
        if (gameSelectorNum) { gameSelectorNumLenght = Math.floor(Math.log(numberOfGames)/Math.log(10)) + 1; }
        text = '<FORM NAME="GameSel" STYLE="display:inline;"> ' +
          '<SELECT ID="GameSelSelect" NAME="GameSelSelect" STYLE="';
        if (tableSize > 0) { text += 'width: ' + tableSize + 'px; '; }
        text += 'font-family: monospace;" CLASS="selectControl" TITLE="Select a game" ' +
          'ONCHANGE="this.blur(); if(this.value >= 0) { Init(this.value); this.value = -1; }" ' +
          'ONFOCUS="disableShortcutKeysAndStoreStatus();" ONBLUR="restoreShortcutKeysStatus();" ' +
          '> ' +
          '<OPTION value=-1>';

        blanks = ''; for (ii=0; ii<32; ii++) { blanks += ' '; }
        if (gameSelectorNum) { 
          gameSelectorHeadDisplay = blanks.substring(0, gameSelectorNumLenght) + '  ' + gameSelectorHead; 
        } else { 
          gameSelectorHeadDisplay = gameSelectorHead; 
        }
        // replace spaces with &nbsp; 
        text += gameSelectorHeadDisplay.replace(/ /g, '&nbsp;'); 

        for (ii=0; ii<numberOfGames; ii++){
          textSelectOptions += '<OPTION value=' + ii + '>';
          textSO = '';
          if (gameSelectorNum) {
            numText = ' ' + (ii+1);
            textSO += blanks.substr(0, gameSelectorNumLenght - (numText.length - 1)) +
              numText + ' ';
          }
          if (gameSelectorChEvent > 0) {
            textSO += ' ' + gameEvent[ii].substring(0, gameSelectorChEvent) + 
              blanks.substr(0, gameSelectorChEvent - gameEvent[ii].length) + ' ';
          }
          if (gameSelectorChSite > 0) {
            textSO += ' ' + gameSite[ii].substring(0, gameSelectorChSite) +
              blanks.substr(0, gameSelectorChSite - gameSite[ii].length) + ' ';
          }
          if (gameSelectorChRound > 0) {
            textSO += ' ' + blanks.substr(0, gameSelectorChRound - gameRound[ii].length) +
              gameRound[ii].substring(0, gameSelectorChRound) + ' ';
          }
          if (gameSelectorChWhite > 0) {
            textSO += ' ' + gameWhite[ii].substring(0, gameSelectorChWhite) +
              blanks.substr(0, gameSelectorChWhite - gameWhite[ii].length) + ' ';
          }
          if (gameSelectorChBlack > 0) {
            textSO += ' ' + gameBlack[ii].substring(0, gameSelectorChBlack) +
              blanks.substr(0, gameSelectorChBlack - gameBlack[ii].length) + ' ';
          }
          if (gameSelectorChResult > 0) {
            textSO += ' ' + gameResult[ii].substring(0, gameSelectorChResult) +
              blanks.substr(0, gameSelectorChResult - gameResult[ii].length) + ' ';
          }
          if (gameSelectorChDate > 0) {
            textSO += ' ' + gameDate[ii].substring(0, gameSelectorChDate) +
              blanks.substr(0, gameSelectorChDate - gameDate[ii].length) + ' ';
          }
          // replace spaces with &nbsp; 
          textSelectOptions += textSO.replace(/ /g, '&nbsp;');
        }
        text += textSelectOptions + '</SELECT></FORM>';
        theObject.innerHTML = text; 
      }
    }
  }

  // game event

  if (theObject = document.getElementById("GameEvent")) 
  { theObject.innerHTML = gameEvent[currentGame]; }

  // game round

  if (theObject = document.getElementById("GameRound")) 
  { theObject.innerHTML = gameRound[currentGame]; }

  // game site

  if (theObject = document.getElementById("GameSite")) 
  { theObject.innerHTML = gameSite[currentGame]; }

  // game date

  if (theObject = document.getElementById("GameDate")) { 
    theObject.innerHTML = gameDate[currentGame]; 
    theObject.style.whiteSpace = "nowrap";
  }

  // game white

  if (theObject = document.getElementById("GameWhite"))
  { theObject.innerHTML = gameWhite[currentGame]; }

  // game black

  if (theObject = document.getElementById("GameBlack"))
  { theObject.innerHTML = gameBlack[currentGame]; }

  // game result

  if (theObject = document.getElementById("GameResult")) {
    theObject.innerHTML = gameResult[currentGame]; 
    theObject.style.whiteSpace = "nowrap";
  } 
  
  // game text

  if (theObject = document.getElementById("GameText")) {
    text = '<SPAN ID="ShowPgnText">';
    for (ii = StartPly; ii < StartPly+PlyNumber; ++ii) {
      printedComment = false;
      // remove PGN extension tags
      thisComment = MoveComments[ii].replace(/\[%[^\]]*?\]\s*/g,''); // note trailing spaces also removed
      // remove spaces only comments
      thisComment = thisComment.replace(/^\s+$/,'');
      if (commentsIntoMoveText && (thisComment !== '')) {
        if (commentsOnSeparateLines && (ii > StartPly)) { 
          text += '<DIV CLASS="comment" STYLE="line-height: 33%;">&nbsp;</DIV>';
        }
        text += '<SPAN CLASS="comment">' + thisComment + '</SPAN><SPAN CLASS="move"> </SPAN>';
        if (commentsOnSeparateLines) { 
          text += '<DIV CLASS="comment" STYLE="line-height: 33%;">&nbsp;</DIV>';
        }
        printedComment = true;
      }
      var moveCount = Math.floor(ii/2)+1;
      text += '<SPAN STYLE="white-space: nowrap;">';
      if (ii%2 === 0){
        text += '<SPAN CLASS="move">' + moveCount + '.&nbsp;</SPAN>';
      } else {
        if ((printedComment) || (ii == StartPly)) { text += '<SPAN CLASS="move">' + moveCount + '...&nbsp;</SPAN>'; }
      }
      jj = ii+1;
      text += '<A HREF="javascript:GoToMove(' + jj + ')" CLASS="move" ID="Mv' + jj + 
        '" ONFOCUS="this.blur()">' + Moves[ii] + '</A></SPAN>' +
        '<SPAN CLASS="move"> </SPAN>';
    }
    // remove PGN extension tags and trailing spaces
    thisComment = MoveComments[StartPly+PlyNumber].replace(/\[%.*?\]\s*/g,'');
    // remove spaces only comments
    thisComment = thisComment.replace(/^\s+$/,'');
    if (commentsIntoMoveText && (thisComment !== '')) {
      if (commentsOnSeparateLines) { text += '<DIV CLASS="comment" STYLE="line-height: 33%;">&nbsp;</DIV>'; }
      text += '<SPAN CLASS="comment">' + thisComment + '</SPAN><SPAN CLASS="move"> </SPAN>';
    }
    text += '</SPAN>';

    theObject.innerHTML = text;
  }

  // game searchbox

  if ((theObject = document.getElementById("GameSearch")) && firstStart) {
    if (numberOfGames < 2) {
      // theObject.innerHTML = ''; // replaced with code below to cope with IE bug
      while (theObject.firstChild) { theObject.removeChild(theObject.firstChild); }
    } else {
      text = '<FORM ID="searchPgnForm" STYLE="display: inline;" ' +
        'ACTION="javascript:searchPgnGameForm();">';
      text += '<INPUT ID="searchPgnButton" CLASS="searchPgnButton" STYLE="display: inline; ';
      if (tableSize > 0) { text += 'width: ' + (tableSize/4) + 'px; '; }
      text += '" TITLE="find games matching the search string (or regular expression)" ' +
        'TYPE="submit" VALUE="?">' +
        '<INPUT ID="searchPgnExpression" CLASS="searchPgnExpression" ' +
        'TITLE="find games matching the search string (or regular expression)" ' + 
        'TYPE="input" VALUE="" STYLE="display: inline; ';
      if (tableSize > 0) { text += 'width: ' + (3*tableSize/4) + 'px; '; }
      text += '" ONFOCUS="disableShortcutKeysAndStoreStatus();" ONBLUR="restoreShortcutKeysStatus();">'; 
      text += '</FORM>';
      theObject.innerHTML = text;
      theObject = document.getElementById('searchPgnExpression');
      if (theObject) { theObject.value = lastSearchPgnExpression; }
    }
  }
}


function FlipBoard() {
  tmpHighlightOption = highlightOption;
  if (tmpHighlightOption) { SetHighlight(false); }
  IsRotated = !IsRotated;
  PrintHTML();
  RefreshBoard();
  if (tmpHighlightOption) { SetHighlight(true); }
}

function RefreshBoard() {

  // display all empty squares
  var col, row, square;
  for (col = 0; col < 8;++col) {
    for (row = 0; row < 8; ++row) {
      if (Board[col][row] === 0) { SetImage(col, row, ClearImg.src); }
    }
  }

  // display pieces
  var color, ii;
  for (color = 0; color < 2; ++color) {
    for (ii = 0; ii < 16; ++ii) {
      if (PieceType[color][ii] > 0) {
        SetImage(PieceCol[color][ii], PieceRow[color][ii], PiecePicture[color][PieceType[color][ii]].src);
      }
    }
  }
}

function SetAutoPlay(vv) {
  isAutoPlayOn = vv;
  // clear timeout
  if (AutoPlayInterval) { clearTimeout(AutoPlayInterval); AutoPlayInterval = null; }
  // timeout on: move forward and change button label
  if (isAutoPlayOn){
    if (document.GameButtonsForm) {
      if (document.GameButtonsForm.AutoPlay) {
        document.GameButtonsForm.AutoPlay.value = "=";
        document.GameButtonsForm.AutoPlay.title = "toggle autoplay (stop)";
        document.GameButtonsForm.AutoPlay.className = "buttonControlStop";
      }
    }
    if (CurrentPly < StartPly+PlyNumber) { AutoPlayInterval=setTimeout("MoveForward(1)", Delay); }
    else {
      if (autoplayNextGame) { AutoPlayInterval=setTimeout("AutoplayNextGame()", Delay); }
      else { SetAutoPlay(false); }
    }
  } else { 
    if (document.GameButtonsForm) {
      if (document.GameButtonsForm.AutoPlay) {
        document.GameButtonsForm.AutoPlay.value = "+";
        document.GameButtonsForm.AutoPlay.title = "toggle autoplay (start)";
        document.GameButtonsForm.AutoPlay.className = "buttonControlPlay";
      }
    }
  }
}

function SetAutoplayDelay(vv) {
  Delay = vv;
}

function SetAutoplayDelayAndStart(vv) {
  MoveForward(1);
  SetAutoplayDelay(vv);
  SetAutoPlay(true);
}

function SetLiveBroadcast(delay, alertFlag, demoFlag) {
  LiveBroadcastDelay = delay; // delay = 0 => no live broadcast
  LiveBroadcastAlert = (alertFlag === true); // display myAlerts during live broadcast?
  LiveBroadcastDemo = (demoFlag === true);
}

function SetImage(col, row, image) {
  if (IsRotated) { trow = row; tcol = 7 - col; }
  else { trow = 7 - row; tcol = col; }
  if (theObject = document.getElementById('img_' + 'tcol' + tcol + 'trow' + trow)) {
    if (theObject.src != image) { theObject.src = image; }
  }
}

function SetImagePath(path) {
  ImagePath = path;
}

function SwitchAutoPlay() {
  if (isAutoPlayOn) { SetAutoPlay(false); }
  else {
    MoveForward(1);
    SetAutoPlay(true);
  }
}

function StoreMove(thisPly) {

  // "square from" history
  HistPieceId[0][thisPly] = mvPieceId;
  HistCol[0][thisPly] = PieceCol[MoveColor][mvPieceId];
  HistRow[0][thisPly] = PieceRow[MoveColor][mvPieceId];
  HistType[0][thisPly] = PieceType[MoveColor][mvPieceId];

  // "square to" history
  HistCol[2][thisPly] = mvToCol;
  HistRow[2][thisPly] = mvToRow;

  if (mvIsCastling) {
    HistPieceId[1][thisPly] = castleRook;
    HistCol[1][thisPly] = PieceCol[MoveColor][castleRook];
    HistRow[1][thisPly] = PieceRow[MoveColor][castleRook];
    HistType[1][thisPly] = PieceType[MoveColor][castleRook];
  } else if (mvCapturedId >= 0) {
    HistPieceId[1][thisPly] = mvCapturedId+16;
    HistCol[1][thisPly] = PieceCol[1-MoveColor][mvCapturedId];
    HistRow[1][thisPly] = PieceRow[1-MoveColor][mvCapturedId];
    HistType[1][thisPly] = PieceType[1-MoveColor][mvCapturedId];
  } else {
    HistPieceId[1][thisPly] = -1;
  }

  // update "square from" and captured square (not necessarily "square to" e.g. en-passant)
  Board[PieceCol[MoveColor][mvPieceId]][PieceRow[MoveColor][mvPieceId]] = 0;

  // mark the captured piece
  if (mvCapturedId >= 0) {
     PieceType[1-MoveColor][mvCapturedId] = -1;
     PieceMoveCounter[1-MoveColor][mvCapturedId]++;
     Board[PieceCol[1-MoveColor][mvCapturedId]][PieceRow[1-MoveColor][mvCapturedId]] = 0;
  }

  // update piece arrays: a promotion would change piece type
  PieceType[MoveColor][mvPieceId] = mvPieceOnTo;
  PieceMoveCounter[MoveColor][mvPieceId]++;
  PieceCol[MoveColor][mvPieceId] = mvToCol;
  PieceRow[MoveColor][mvPieceId] = mvToRow;
  if (mvIsCastling) {
    PieceMoveCounter[MoveColor][castleRook]++;
    PieceCol[MoveColor][castleRook] = mvToCol == 2 ? 3 : 5;
    PieceRow[MoveColor][castleRook] = mvToRow;
  }

  // update board
  Board[mvToCol][mvToRow] = PieceType[MoveColor][mvPieceId]*(1-2*MoveColor);
  if (mvIsCastling) {
    Board[PieceCol[MoveColor][castleRook]][PieceRow[MoveColor][castleRook]] =
      PieceType[MoveColor][castleRook]*(1-2*MoveColor);
  }
  return;
}

function UndoMove(thisPly) {

  // bring moved piece back
  Board[mvToCol][mvToRow] = 0;
  Board[HistCol[0][thisPly]][HistRow[0][thisPly]] =
    HistType[0][thisPly]*(1-2*MoveColor);

  PieceCol[MoveColor][mvPieceId] = HistCol[0][thisPly];
  PieceRow[MoveColor][mvPieceId] = HistRow[0][thisPly];
  PieceType[MoveColor][mvPieceId] = HistType[0][thisPly];
  PieceMoveCounter[MoveColor][mvPieceId]--;

  // capture/castle: bring captured/rook back
  if (mvCapturedId >= 0) {
     PieceType[1-MoveColor][mvCapturedId] = mvCapturedId;
     PieceCol[1-MoveColor][mvCapturedId] = HistCol[1][thisPly];
     PieceRow[1-MoveColor][mvCapturedId] = HistRow[1][thisPly];
     PieceCol[1-MoveColor][mvCapturedId] = HistCol[1][thisPly];
  } else if (mvIsCastling) {
     PieceCol[MoveColor][castleRook] = HistCol[1][thisPly];
     PieceRow[MoveColor][castleRook] = HistRow[1][thisPly];
     PieceMoveCounter[MoveColor][castleRook]--;
  }
}

function Color(nn) {
  if (nn < 0) { return 1; }
  if (nn > 0) { return 0; }
  return 2;
}

function sign(nn) {
  if (nn > 0) { return  1; }
  if (nn < 0) { return -1; }
  return 0;
}

function SquareOnBoard(col, row) {
  return col >= 0 && col <= 7 && row >= 0 && row <= 7;
}

