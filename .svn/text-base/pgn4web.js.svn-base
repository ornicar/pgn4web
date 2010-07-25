/*
 *  pgn4web javascript chessboard
 *  copyright (C) 2009, 2010 Paolo Casaschi
 *  see README file and http://pgn4web.casaschi.net
 *  for credits, license and more details
 */

/*
 *  See README.txt file for instructions HOW TO USE pgn4web.js
 *  Alternatively, check the project wiki at http://pgn4web.casaschi.net
 */

var pgn4web_version = '2.04+';

var pgn4web_project_url = 'http://pgn4web.casaschi.net';
var pgn4web_project_author = 'Paolo Casaschi';
//  pgn4web_project_email might have been configured already in pgn4web-server-config.js
var pgn4web_project_email;
if (pgn4web_project_email == undefined) { pgn4web_project_email = 'pgn4web@casaschi.net'; }

var about = '\tpgn4web v' + pgn4web_version + '\n\t' + pgn4web_project_url + '\n';

var helpWin=null;
function displayHelp(section){
  if (!section) { section = "top"; }
  if (helpWin && !helpWin.closed) { helpWin.close(); }
  helpWin = window.open(detectHelpLocation() + "?" + 
            (Math.floor(900 * Math.random()) + 100) + "#" + section, 
            "pgn4web_help", 
            "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if ((helpWin !== null) && (window.focus)) { helpWin.window.focus(); }
}


/*
 * Custom functions executed at the given moments.
 * Here intentionally empty, to be redefined in 
 * the HTML file AFTER loading pgn4web.js
 */

function customFunctionOnPgnTextLoad() {}
function customFunctionOnPgnGameLoad() {}
function customFunctionOnMove()        {}
function customFunctionOnAlert(msg)    {}


window.onload = start_pgn4web;

document.onkeydown = handlekey;

function start_pgn4web() {
  // first time pgn4web is started allow for alert log messages preceding start_pgn4web
  // if start_pgn4web is later reloaded then reset alert log
  if (alertFirstResetLoadingPgn) { alertFirstResetLoadingPgn = false; }
  else { resetAlert(); } 
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
    configBoardShrortcut(debugShortcutSquare, 
                         "pgn4web v" + pgn4web_version + " debug info", 
                         "keep");
  }
}

function myAlert(msg, fatalError) {
  alertNum++;
  alertNumSinceReset++;
  if (fatalError) { fatalErrorNumSinceReset++; }
  alertLast = (alertLast + 1) % alertLog.length;
  alertLog[alertLast] = msg;
  alertPlural = alertNum > 1 ? "s" : "";
  configBoardShrortcut(debugShortcutSquare, 
                       "pgn4web v" + pgn4web_version + " debug info, " + alertNum + " alert" + alertPlural, 
                       "keep"); 

  if ((LiveBroadcastDelay === 0) || (LiveBroadcastAlert === true)) {
    startAlertPrompt();
  }
  customFunctionOnAlert(msg);
}

function startAlertPrompt() {
  if (alertPromptOn) { return; } // if flashing already dont start a new flashing
  if (alertPromptInterval) { clearTimeout(alertPromptInterval); }
  alertPromptInterval = setTimeout("alertPromptTick(true);", 500);
}

function stopAlertPrompt() {
  if (alertPromptInterval) { 
    clearTimeout(alertPromptInterval); 
    alertPromptInterval = null;
  }
  // need to restore the chessboard to the correct colors
  if (alertPromptOn) { alertPromptTick(false); }
}

function alertPromptTick(restart) {
  if (alertPromptInterval) { 
    clearTimeout(alertPromptInterval); 
    alertPromptInterval = null;
  }
  if(document.getElementById('tcol0trow0')) {
    theObject = document.getElementById('tcol0trow0');
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
  } else { alertPromptDelay = 1500; }
  if (restart) { alertPromptInterval = setTimeout("alertPromptTick(true);", alertPromptDelay); }
}


function stopKeyPropagation(e) {
  e.cancelBubble = true;
  if (e.stopPropagation) { e.stopPropagation(); }
  if (e.preventDefault) { e.preventDefault(); }
  return false;
}

// to be used as onFocus and onBlur actions on textboxes, in order to allow typing text
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

  // escape is always enabled to show help and to toggle enabling shortcut keys
  if ((keycode != 27) && (shortcutKeysEnabled === false)) { return true; }

  switch(keycode)
  {
    case  8:  // backspace
    case  9:  // tab
    case 16:  // shift
    case 17:  // ctrl
    case 18:  // alt
    case 32:  // space
    case 33:  // page up
    case 34:  // page down
    case 35:  // end
    case 36:  // home
    case 45:  // insert
    case 46:  // delete
    case 92:  // super
    case 93:  // menu
      return true;
      break;

    case 27: // escape
      if (e.shiftKey) {
        // shift key + escape (27) toogle the usage of shortcut keys 
        interactivelyToggleShortcutKeys(); 
      } else {
        displayHelp();
      }
      return stopKeyPropagation(e);
      break;

    case 90: // z
      if (e.shiftKey) { window.open(pgn4web_project_url); }
      else { displayDebugInfo(); }
      return stopKeyPropagation(e);
      break;

    case 37:  // left arrow  
    case 74:  // j
      MoveBackward(1);
      return stopKeyPropagation(e);
      break;

    case 38:  // up arrow
    case 72:  // h
      GoToMove(StartPly);
      return stopKeyPropagation(e);
      break;

    case 39:  // right arrow
    case 75:  // k
      MoveForward(1);
      return stopKeyPropagation(e);
      break;

    case 40:  // down arrow
    case 76:  // l
      GoToMove(StartPly + PlyNumber);
      return stopKeyPropagation(e);
      break;

    case 85:  // u
      MoveToPrevComment();
      return stopKeyPropagation(e);
      break;

    case 73:  // i
      MoveToNextComment();
      return stopKeyPropagation(e);
      break;

    case 83:  // s
      searchPgnGamePrompt();
      return stopKeyPropagation(e);
      break;

    case 13:  // enter
      searchPgnGame(lastSearchPgnExpression);
      return stopKeyPropagation(e);
      break;

    case 65:  // a
      MoveForward(1);
      SetAutoPlay(true);
      return stopKeyPropagation(e);
      break;

    case 48:  // 0
      if (e.shiftKey) { customShortcutKey_Shift_0(); }
      else {
        SetAutoPlay(false); 
      }
      return stopKeyPropagation(e);
      break;

    case 49:  // 1
      if (e.shiftKey) { customShortcutKey_Shift_1(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 1*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 50:  // 2
      if (e.shiftKey) { customShortcutKey_Shift_2(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 2*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 51:  // 3
      if (e.shiftKey) { customShortcutKey_Shift_3(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 3*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 52:  // 4
      if (e.shiftKey) { customShortcutKey_Shift_4(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 4*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 53:  // 5
      if (e.shiftKey) { customShortcutKey_Shift_5(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 5*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 54:  // 6
      if (e.shiftKey) { customShortcutKey_Shift_6(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 6*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 55:  // 7
      if (e.shiftKey) { customShortcutKey_Shift_7(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 7*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 56:  // 8
      if (e.shiftKey) { customShortcutKey_Shift_8(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 8*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 57:  // 9
      if (e.shiftKey) { customShortcutKey_Shift_9(); }
      else {
        MoveForward(1);
        SetAutoplayDelay( 9*1000);
        SetAutoPlay(true);
      }
      return stopKeyPropagation(e);
      break;

    case 81:  // q
      MoveForward(1);
      SetAutoplayDelay(10*1000);
      SetAutoPlay(true);
      return stopKeyPropagation(e);
      break;

    case 87:  // w
      MoveForward(1);
      SetAutoplayDelay(20*1000);
      SetAutoPlay(true);
      return stopKeyPropagation(e);
      break;

    case 69:  // e
      MoveForward(1);
      SetAutoplayDelay(30*1000);
      SetAutoPlay(true);
      return stopKeyPropagation(e);
      break;

    case 82:  // r
      pauseLiveBroadcast();
      return stopKeyPropagation(e);
      break;

    case 84:  // t
      refreshPgnSource();
      return stopKeyPropagation(e);
      break;

    case 89:  // y
      resumeLiveBroadcast();
      return stopKeyPropagation(e);
      break;

    case 70:  // f
      FlipBoard();
      return stopKeyPropagation(e);
      break;

    case 71:  // g
      SetHighlight(!highlightOption);
      return stopKeyPropagation(e);
      break;

    case 68:  // d
      if (IsRotated) { FlipBoard(); }
      return stopKeyPropagation(e);
      break;

    case 88: // x
      if (numberOfGames > 1) {
        currentGame = Math.floor(Math.random()*numberOfGames);
        Init();
        GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber + 1)));
      }
      return stopKeyPropagation(e);
      break;

    case 67: // c
      if (numberOfGames > 1) {
        currentGame = Math.floor(Math.random()*numberOfGames);
        Init();
      }
      return stopKeyPropagation(e);
      break;

    case 86:  // v
      if (numberOfGames > 1) {
	currentGame = 0;
        Init();
      }
      return stopKeyPropagation(e);
      break;

    case 66:  // b
      if (currentGame > 0) {
        currentGame--;
        Init();
      }
      return stopKeyPropagation(e);
      break;

    case 78:  // n
      if (numberOfGames > currentGame + 1) {
        currentGame++;
        Init();
      }
      return stopKeyPropagation(e);
      break;

    case 77:  // m
      if (numberOfGames > 1) {
        currentGame = numberOfGames - 1;
        Init();
      }
      return stopKeyPropagation(e);
      break;

    case 79:  // o
      SetCommentsOnSeparateLines(!commentsOnSeparateLines);
      Init();
      return stopKeyPropagation(e);
      break;

    case 80:  // p
      SetCommentsIntoMoveText(!commentsIntoMoveText);
      Init();
      return stopKeyPropagation(e);
      break;

    default:
      return true;
      break;
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

function configBoardShrortcut(square, title, functionPointer) {
  if (square.charCodeAt === null) { return; }
  var col = square.charCodeAt(0) - 65; // 65 is "A"
  if ((col < 0) || (col > 7)) { return; }
  var row = 56 - square.charCodeAt(1); // 56 is "8"
  if ((row < 0) || (row > 7)) { return; }
  boardTitle[col][row] = title;
  if (functionPointer != "keep") { boardOnClick[col][row] = functionPointer; }
  theObject = document.getElementById('link_tcol' + col + 'trow' + row);
  if (theObject) {
    if (IsRotated) { square = String.fromCharCode(72-col,49+row); }
    if (boardTitle[col][row] !== '') { squareTitle = square + ': ' + boardTitle[col][row]; }
    else { squareTitle = square; } 
    theObject.title = squareTitle;
  }
}

// PLEASE NOTE: the 'square' parameter of 'configBoardShrortcut' is ALWAYS ASSUMING WHITE ON BOTTOM

debugShortcutSquare = "A8";
// A8
configBoardShrortcut("A8", "pgn4web v" + pgn4web_version + " debug info", function(){ displayDebugInfo(); });
// B8
configBoardShrortcut("B8", "show this position FEN string", function(){ displayFenData(); });
// C8
configBoardShrortcut("C8", "show this game PGN source data", function(){ displayPgnData(false); });
// D8
configBoardShrortcut("D8", "show full PGN source data", function(){ displayPgnData(true); });
// E8
configBoardShrortcut("E8", "search help", function(){ displayHelp("search"); });
// F8
configBoardShrortcut("F8", "shortcut keys help", function(){ displayHelp("keys"); });
// G8
configBoardShrortcut("G8", "shortcut squares help", function(){ displayHelp("squares"); });
// H8
configBoardShrortcut("H8", "pgn4web help", function(){ displayHelp(); });
// A7
configBoardShrortcut("A7", "pgn4web website", function(){ window.open(pgn4web_project_url); });
// B7
configBoardShrortcut("B7", "toggle show comments in game text", function(){ SetCommentsIntoMoveText(!commentsIntoMoveText); thisPly = CurrentPly; Init(); GoToMove(thisPly); });
// C7
configBoardShrortcut("C7", "toggle show comments on separate lines in game text", function(){ SetCommentsOnSeparateLines(!commentsOnSeparateLines); thisPly = CurrentPly; Init(); GoToMove(thisPly); });
// D7
configBoardShrortcut("D7", "toggle highlight last move", function(){ SetHighlight(!highlightOption); });
// E7
configBoardShrortcut("E7", "flip board", function(){ FlipBoard(); });
// F7
configBoardShrortcut("F7", "show white on bottom", function(){ if (IsRotated) { FlipBoard(); } });
// G7
configBoardShrortcut("G7", "toggle autoplay next game", function(){ SetAutoplayNextGame(!autoplayNextGame); });
// H7
configBoardShrortcut("H7", "toggle enabling shortcut keys", function(){ interactivelyToggleShortcutKeys(); });
// A6
configBoardShrortcut("A6", "pause live broadcast automatic refresh", function(){ pauseLiveBroadcast(); });
// B6
configBoardShrortcut("B6", "restart live broadcast automatic refresh", function(){ restartLiveBroadcast(); });
// C6
configBoardShrortcut("C6", "load previous finished game", function(){ for (ii=currentGame-1; ii>=0; ii--) { if ((checkHeaderDefined(gameResult[ii])) && (gameResult[ii]!="*")) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// D6
configBoardShrortcut("D6", "load previous unfinished game", function(){ for (ii=currentGame-1; ii>=0; ii--) { if ((!checkHeaderDefined(gameResult[ii])) || (gameResult[ii]=="*")) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// E6
configBoardShrortcut("E6", "load next unfinished game", function(){ for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((!checkHeaderDefined(gameResult[ii])) || (gameResult[ii]=="*")) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// F6
configBoardShrortcut("F6", "load next finished game", function(){ for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((checkHeaderDefined(gameResult[ii])) && (gameResult[ii]!="*")) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// G6
configBoardShrortcut("G6", "", function(){});
// H6
configBoardShrortcut("H6", "force games refresh during live broadcast", function(){ refreshPgnSource(); });
// A5
configBoardShrortcut("A5", "repeat last search", function(){ searchPgnGame(lastSearchPgnExpression); });
// B5
configBoardShrortcut("B5", "search prompt", function(){ searchPgnGamePrompt(); });
// C5
configBoardShrortcut("C5", "", function(){});
// D5
configBoardShrortcut("D5", "", function(){});
// E5
configBoardShrortcut("E5", "", function(){});
// F5
configBoardShrortcut("F5", "", function(){});
// G5
configBoardShrortcut("G5", "", function(){});
// H5
configBoardShrortcut("H5", "", function(){});
// A4
configBoardShrortcut("A4", "jump to previous event", function(){ if (!checkHeaderDefined(gameEvent[currentGame])) { return; } for (ii=currentGame-1; ii>=0; ii--) { if ((checkHeaderDefined(gameEvent[ii])) && (gameEvent[ii] != gameEvent[currentGame])) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// B4
configBoardShrortcut("B4", "jump to previous round of same event", function(){ if (!checkHeaderDefined(gameRound[currentGame])) { return; } for (ii=currentGame-1; ii>=0; ii--) { if ((checkHeaderDefined(gameRound[ii])) && (gameEvent[ii] == gameEvent[currentGame]) && (gameRound[ii] != gameRound[currentGame])) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// C4
configBoardShrortcut("C4", "load previous game of same black player", function(){ if (!checkHeaderDefined(gameBlack[currentGame])) { return; } for (ii=currentGame-1; ii>=0; ii--) { if ((checkHeaderDefined(gameBlack[ii])) && (gameBlack[ii] == gameBlack[currentGame])) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// D4
configBoardShrortcut("D4", "load previous game of same white player", function(){ if (!checkHeaderDefined(gameWhite[currentGame])) { return; } for (ii=currentGame-1; ii>=0; ii--) { if ((checkHeaderDefined(gameWhite[ii])) && (gameWhite[ii] == gameWhite[currentGame])) { break; } } if (ii>=0) { currentGame = ii; Init();} });
// E4
configBoardShrortcut("E4", "load next game of same white player", function(){ if (!checkHeaderDefined(gameWhite[currentGame])) { return; } for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((checkHeaderDefined(gameWhite[ii])) && (gameWhite[ii] == gameWhite[currentGame])) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// F4
configBoardShrortcut("F4", "load next game of same black player", function(){ if (!checkHeaderDefined(gameBlack[currentGame])) { return; } for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((checkHeaderDefined(gameBlack[ii])) && (gameBlack[ii] == gameBlack[currentGame])) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// G4
configBoardShrortcut("G4", "jump to next round of same event", function(){ if (!checkHeaderDefined(gameRound[currentGame])) { return; } for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((checkHeaderDefined(gameRound[ii])) && (gameEvent[ii] == gameEvent[currentGame]) && (gameRound[ii] != gameRound[currentGame])) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// H4
configBoardShrortcut("H4", "jump to next event", function(){ if (!checkHeaderDefined(gameEvent[currentGame])) { return; } for (ii=currentGame+1; ii<numberOfGames; ii++) { if ((checkHeaderDefined(gameEvent[ii])) && (gameEvent[ii] != gameEvent[currentGame])) { break; } } if (ii<numberOfGames) { currentGame = ii; Init();} });
// A3
configBoardShrortcut("A3", "load first game", function(){ if (numberOfGames > 1) { currentGame = 0; Init(); } });
// B3
configBoardShrortcut("B3", "jump 50 games backward", function(){ if (currentGame >= 50){ currentGame -= 50; Init(); }else{ if (numberOfGames > 1) { currentGame = 0; Init(); } } });
// C3
configBoardShrortcut("C3", "load previous game", function(){ if (currentGame > 0){ currentGame--; Init(); } });
// D3
configBoardShrortcut("D3", "load random game", function(){ if (numberOfGames > 1) { currentGame = Math.floor(Math.random()*numberOfGames); Init(); } });
// E3
configBoardShrortcut("E3", "load random game at random position", function(){ currentGame = Math.floor(Math.random()*numberOfGames); Init(); GoToMove(StartPly + Math.floor(Math.random()*(StartPly + PlyNumber + 1))); });
// F3
configBoardShrortcut("F3", "load next game", function(){ if (numberOfGames > currentGame + 1){ currentGame++; Init(); } });
// G3
configBoardShrortcut("G3", "jump 50 games forward", function(){ if (numberOfGames > currentGame + 50){ currentGame += 50; Init(); }else{ if (numberOfGames > 1) { currentGame = numberOfGames - 1; Init(); } } });
// H3
configBoardShrortcut("H3", "load last game", function(){ if (numberOfGames > 1) { currentGame = numberOfGames - 1; Init(); } });
// A2
configBoardShrortcut("A2", "stop autoplay", function(){ SetAutoPlay(false); });
// B2
configBoardShrortcut("B2", "toggle autoplay", function(){ SwitchAutoPlay(); });
// C2
configBoardShrortcut("C2", "autoplay 1 second", function(){ MoveForward(1); SetAutoplayDelay( 1*1000); SetAutoPlay(true); });
// D2
configBoardShrortcut("D2", "autoplay 2 seconds", function(){ MoveForward(1); SetAutoplayDelay( 2*1000); SetAutoPlay(true); });
// E2
configBoardShrortcut("E2", "autoplay 3 seconds", function(){ MoveForward(1); SetAutoplayDelay( 3*1000); SetAutoPlay(true); });
// F2
configBoardShrortcut("F2", "autoplay 5 seconds", function(){ MoveForward(1); SetAutoplayDelay( 5*1000); SetAutoPlay(true); });
// G2
configBoardShrortcut("G2", "autoplay 10 seconds", function(){ MoveForward(1); SetAutoplayDelay( 10*1000); SetAutoPlay(true); });
// H2
configBoardShrortcut("H2", "autoplay 30 seconds", function(){ MoveForward(1); SetAutoplayDelay( 30*1000); SetAutoPlay(true); });
// A1
configBoardShrortcut("A1", "go to game start", function(){ GoToMove(StartPly); });
// B1
configBoardShrortcut("B1", "go to previous comment", function(){ MoveToPrevComment(); });
// C1
configBoardShrortcut("C1", "move 6 half-moves backward", function(){ MoveBackward(6); });
// D1
configBoardShrortcut("D1", "move backward", function(){ MoveBackward(1); });
// E1
configBoardShrortcut("E1", "move forward", function(){ MoveForward(1); });
// F1
configBoardShrortcut("F1", "move 6 half-moves forward", function(){ MoveForward(6); });
// G1
configBoardShrortcut("G1", "go to next comment", function(){ MoveToNextComment(); });
// H1
configBoardShrortcut("H1", "go to game end", function(){ GoToMove(StartPly + PlyNumber); });


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
  helpfile = "help.html";
  return detectJavascriptLocation().replace(/(pgn4web|pgn4web-compacted)\.js/, helpfile); 
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
  debugInfo = 'pgn4web: version=' + pgn4web_version + ' homepage=' + pgn4web_project_url + '\n\n';
  debugInfo += 'HTML URL: length=' + location.href.length + ' url=';
  debugInfo += (location.href.length < 100 ? location.href : (location.href.substring(0,99) + '...')) + '\n';
  baseLocation = detectBaseLocation();
  debugInfo += 'BASE URL: url=' + (baseLocation !== '' ? baseLocation : 'none') + '\n';
  debugInfo += 'JS URL: url=' + detectJavascriptLocation() + '\n\n';
  debugInfo += 'PGN URL: url=' + (pgnUrl !== '' ? pgnUrl : 'none') + '\n';
  debugInfo += 'PGN TEXT: length=';
  if (document.getElementById("pgnText") !== null) { 
    debugInfo += document.getElementById("pgnText").tagName.toLowerCase() == "textarea" ?
                 document.getElementById("pgnText").value.length :
                 document.getElementById("pgnText").innerHTML.length +
                 ' container=' + document.getElementById("pgnText").tagName.toLowerCase();
    // backward compatibility with pgn4web older than 1.77 when the <span> technique was used for pgnText
  }
  debugInfo += '\n\n';
  debugInfo += 'GAMES: current=' + (currentGame+1) + ' number=' + numberOfGames + '\n' +
               'PLY: start=' + StartPly + ' current=' + CurrentPly + ' number=' + PlyNumber + '\n';
  debugInfo += 'AUTOPLAY: ' + (isAutoPlayOn ? 'delay=' + Delay + 'ms' + ' autoplaynext=' + autoplayNextGame : 'off');
  debugInfo += '\n\n';
  debugInfo += 'LIVE BROADCAST: ' + (LiveBroadcastDelay > 0 ? 'ticker=' + LiveBroadcastTicker + ' delay=' + LiveBroadcastDelay + 'm' + ' started=' + LiveBroadcastStarted + ' ended=' + LiveBroadcastEnded + ' paused=' + LiveBroadcastPaused + ' demo=' + LiveBroadcastDemo + ' alert=' + LiveBroadcastAlert : 'off'); 
  debugInfo += '\n\n';
  debugInfo += 'ALERT LOG: fatalnew=' + fatalErrorNumSinceReset + ' new=' + alertNumSinceReset + ' shown=' + 
               Math.min(alertNum, alertLog.length) + ' total=' + alertNum + '\n--';
  if (alertNum > 0) {
    for (ii = 0; ii<alertLog.length; ii++) {
      if (alertLog[(alertNum - 1 - ii) % alertLog.length] === undefined) { break; }
      else { debugInfo += "\n" + alertLog[(alertNum - 1 - ii) % alertLog.length] + "\n--"; }
    }
  }
  if (confirm(debugInfo + '\n\nclick OK to show this debug info in a browser window for cut and paste')) {
    if (debugWin && !debugWin.closed) { debugWin.close(); }
    debugWin = window.open("", "debug_data", "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
    if (debugWin !== null) {
       debugWin.document.open("text/html", "replace");
       debugWin.document.write("<html>");
       debugWin.document.write("<head><title>pgn4web debug info</title><link rel='shortcut icon' href='pawn.ico' /></head>");
       debugWin.document.write("<body>\n<pre>\n");
       debugWin.document.write(debugInfo);
       debugWin.document.write("\n</pre>\n</body></html>");
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
  pgnWin = window.open("", "pgn_data", "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if (pgnWin !== null) {
    pgnWin.document.open("text/html", "replace");
    pgnWin.document.write("<html>");
    pgnWin.document.write("<head><title>pgn4web PGN source</title><link rel='shortcut icon' href='pawn.ico' /></head>");
    pgnWin.document.write("<body>\n<pre>\n");
    if (allGames) { for (ii = 0; ii < numberOfGames; ++ii) { pgnWin.document.write(pgnGame[ii]); } }
    else { pgnWin.document.write(pgnGame[currentGame]); }
    pgnWin.document.write("\n</pre>\n</body></html>");
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
 
  // Active color
  currentFEN += CurrentPly%2 === 0 ? " w" : " b";

  // Castling availability (only standard chess supported, not any FischerRandom extensions
  CastlingShortFEN = new Array(2);
  CastlingShortFEN[0] = CastlingShort[0];
  CastlingShortFEN[1] = CastlingShort[1];
  CastlingLongFEN = new Array(2);
  CastlingLongFEN[0] = CastlingLong[0];
  CastlingLongFEN[1] = CastlingLong[1];
  for (thisPly = StartPly; thisPly < CurrentPly; thisPly++) {
    SideToMoveFEN = thisPly%2;
    BackrowSideToMoveFEN = SideToMoveFEN * 7;
    if (HistType[0][thisPly] == 1) { CastlingShortFEN[SideToMoveFEN] = CastlingLongFEN[SideToMoveFEN] = 0; }
    if ((HistCol[0][thisPly] === 7) && (HistRow[0][thisPly] == BackrowSideToMoveFEN)) { CastlingShortFEN[SideToMoveFEN] = 0; }
    if ((HistCol[0][thisPly] === 0) && (HistRow[0][thisPly] == BackrowSideToMoveFEN)) { CastlingLongFEN[SideToMoveFEN] = 0; }
  }

  CastlingFEN = "";
  if (CastlingShortFEN[0] !== 0) { CastlingFEN += FenPieceName.toUpperCase().charAt(0); }
  if (CastlingLongFEN[0] !== 0) { CastlingFEN += FenPieceName.toUpperCase().charAt(1); }
  if (CastlingShortFEN[1] !== 0) { CastlingFEN += FenPieceName.toLowerCase().charAt(0); }
  if (CastlingLongFEN[1] !== 0) { CastlingFEN += FenPieceName.toLowerCase().charAt(1); }
  if (CastlingFEN === "") { CastlingFEN = "-"; }
  currentFEN += " " + CastlingFEN;
 
  // En passant target square
  if (HistEnPassant[CurrentPly-1]) {
    currentFEN += " " + String.fromCharCode(HistEnPassantCol[CurrentPly-1] + 97);
    currentFEN += CurrentPly%2 === 0 ? "6" : "3";
  } else { currentFEN += " -"; }

  // Halfmove clock
  HalfMoveClock = InitialHalfMoveClock;  
  for (thisPly = StartPly; thisPly < CurrentPly; thisPly++) {
    if ((HistType[0][thisPly] == 6) || (HistPieceId[1][thisPly] >= 16)) { HalfMoveClock = 0; }
    else { HalfMoveClock++; } 
  }
  currentFEN += " " + HalfMoveClock;

  // Fullmove number
  currentFEN += " " + (Math.floor(CurrentPly/2)+1);

  return currentFEN;
}

fenWin = null;
function displayFenData() {
  if (fenWin && !fenWin.closed) { fenWin.close(); }

  currentFEN = CurrentFEN();

  currentMovesString = "";
  lastLineStart = 0;
  for(thisPly = CurrentPly; thisPly <= StartPly + PlyNumber; thisPly++) {
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

  fenWin = window.open("", "fen_data", "resizable=yes,scrollbars=yes,toolbar=no,location=no,menubar=no,status=no");
  if (fenWin !== null) {
    fenWin.document.open("text/html", "replace");
    fenWin.document.write("<html>");
    fenWin.document.write("<head><title>pgn4web FEN string</title><link rel='shortcut icon' href='pawn.ico' /></head>");
    fenWin.document.write("<body>\n");
    fenWin.document.write("<b><pre>\n\n" + currentFEN + "\n\n</pre></b>\n<hr>\n");
    fenWin.document.write("<pre>\n\n");
    tmpString = gameEvent[currentGame] ? gameEvent[currentGame] : "?";
    fenWin.document.write("[Event \"" + tmpString + "\"]\n");
    tmpString = gameSite[currentGame] ? gameSite[currentGame] : "?";
    fenWin.document.write("[Site \"" + tmpString + "\"]\n");
    tmpString = gameDate[currentGame] ? gameDate[currentGame] : "????.??.??";
    fenWin.document.write("[Date \"" + tmpString + "\"]\n");
    tmpString = gameRound[currentGame] ? gameRound[currentGame] : "?";
    fenWin.document.write("[Round \"" + tmpString + "\"]\n");
    tmpString = gameWhite[currentGame] ? gameWhite[currentGame] : "?";
    fenWin.document.write("[White \"" + tmpString + "\"]\n");
    tmpString = gameBlack[currentGame] ? gameBlack[currentGame] : "?";
    fenWin.document.write("[Black \"" + tmpString + "\"]\n");
    tmpString = gameResult[currentGame] ? gameResult[currentGame] : "*";
    fenWin.document.write("[Result \"" + tmpString + "\"]\n");
    fenWin.document.write("[SetUp \"1\"]\n");
    fenWin.document.write("[FEN \"" + CurrentFEN() + "\"]\n\n");
    fenWin.document.write(currentMovesString);
    fenWin.document.write("\n</pre>\n</body></html>");
    fenWin.document.close();
    if (window.focus) { fenWin.window.focus(); }
  }
}


var pgnGame = new Array();
var numberOfGames = -1; 
var currentGame   = -1;

var firstStart = true;

/*
 * Global variables holding game tags.
 */
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
var LiveBroadcastPlaceholderEvent = 'pgn4web live broadcast';
var LiveBroadcastPlaceholderPgn = '[Event "' + LiveBroadcastPlaceholderEvent + '"]';
var gameDemoMaxPly = new Array();
var gameDemoLength = new Array();

var MaxMove = 500;

var castleRook    = -1;
var mvCapture     =  0;
var mvIsCastling  =  0;
var mvIsPromotion =  0;
var mvFromCol     = -1;
var mvFromRow     = -1;
var mvToCol       = -1;
var mvToRow       = -1;
var mvPiece       = -1;
var mvPieceId     = -1;
var mvPieceOnTo   = -1;
var mvCaptured    = -1;
var mvCapturedId  = -1;

Board = new Array(8);
for(i=0; i<8; ++i) { Board[i] = new Array(8); }

// HistCol and HistRow contain move history up to the last replayed ply
// HistCol[0] and HistRow[0] contain the from square (0..7, 0..7 from square a1)
// HistCol[1] and HistRow[1] contain castling and capture info
// HistCol[2] and HistRow[2] contain the from square (0..7, 0..7 from square a1)

HistCol          = new Array(3);
HistRow          = new Array(3);
HistPieceId      = new Array(2);
HistType         = new Array(2);

PieceCol         = new Array(2);
PieceRow         = new Array(2);
PieceType        = new Array(2);
PieceMoveCounter = new Array(2);

for(i=0; i<2; ++i){
  PieceCol[i]         = new Array(16);
  PieceRow[i]         = new Array(16);
  PieceType[i]        = new Array(16);
  PieceMoveCounter[i] = new Array(16);
  HistType[i]    = new Array(MaxMove);
  HistPieceId[i] = new Array(MaxMove);
}

for(i=0; i<3; ++i){
  HistCol[i]     = new Array(MaxMove);
  HistRow[i]     = new Array(MaxMove);
}

HistEnPassant =  new Array(MaxMove);
HistEnPassant[0] =  false;
HistEnPassantCol = new Array(MaxMove);
HistEnPassantCol[0] = -1;

startingSquareSize = -1;
startingImageSize = -1;

PiecePicture = new Array(2);
for(i=0; i<2; ++i) { PiecePicture[i] = new Array(6); }

PieceCode    = new Array(6);
PieceCode[0] = "K";
PieceCode[1] = "Q";
PieceCode[2] = "R";
PieceCode[3] = "B";
PieceCode[4] = "N";
PieceCode[5] = "P";

var FenPieceName = "KQRBNP";
var FenStringStart = "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1";
var ImageOffset  = -1; 
                                                
var ImagePath = '';                                                 
var ImagePathOld;
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

ClearImg  = new Image();

DocumentImages = new Array();

var pgnHeaderTagRegExp       = /\[\s*(\w+)\s*"([^"]*)"\s*\]/; 
var pgnHeaderTagRegExpGlobal = /\[\s*(\w+)\s*"([^"]*)"\s*\]/g;
var dummyPgnHeader = '[x""]';
var emptyPgnHeader = '[Event ""]\n[Site ""]\n[Date ""]\n[Round ""]\n[White ""]\n[Black ""]\n[Result ""]\n\n';
var templatePgnHeader = '[Event "?"]\n[Site "?"]\n[Date "?"]\n[Round "?"]\n[White "?"]\n[Black "?"]\n[Result "?"]\n';
var alertPgnHeader = '[Event ""]\n[Site ""]\n[Date ""]\n[Round ""]\n[White ""]\n[Black ""]\n[Result ""]\n\n{error: click on the top left chessboard square for debug info}';

var gameSelectorHead      = ' ...';
var gameSelectorMono      = true;
var gameSelectorNum       = false;
var gameSelectorNumLenght = 0;
var gameSelectorChEvent   = 0;
var gameSelectorChSite    = 0;
var gameSelectorChRound   = 0;
var gameSelectorChWhite   = 15;
var gameSelectorChBlack   = 15;
var gameSelectorChResult  = 0;
var gameSelectorChDate    = 10;

function CheckLegality(what, plyCount) {
  var retVal;
  var start;
  var end;
  var isCheck;

  // Is it a castling move?
  if (what == 'O-O'){
    if (!CheckLegalityOO()) { return false; }
    start = PieceCol[MoveColor][0];
    end   = 6;
    while(start < end){
      isCheck = IsCheck(start, MoveColor*7, MoveColor);
      if (isCheck) { return false; }
      ++start;
    }
    StoreMove(plyCount);
    return true;
  } else if (what == 'O-O-O'){
    if (!CheckLegalityOOO()) { return false; }
    start = PieceCol[MoveColor][0];
    end   = 2;
    while(start > end){
      isCheck = IsCheck(start, MoveColor*7, MoveColor);
      if (isCheck) { return false; }
      --start;
    }
    StoreMove(plyCount);
    return true;
  } 
  
  // Some checks common to all pieces:
  // If it is not a capture the square has to be empty.
  // If it is a capture the TO square has to be occupied by a piece of the
  // opposite color, with the exception of the en-passant capture.
  // If the moved piece and the piece in the TO square are different then 
  // the moved piece has to be a pawn promoting.
  if (!mvCapture){
    if (Board[mvToCol][mvToRow] !== 0) { return false; }
  }
  if ((mvCapture) && (Color(Board[mvToCol][mvToRow]) != 1-MoveColor)){
    if ((mvPiece != 6) || (!HistEnPassant[plyCount-1]) || (HistEnPassantCol[plyCount-1] != mvToCol) ||
	(mvToRow != 5-3*MoveColor)) { return false; }
  }
  if (mvIsPromotion){
    if (mvPiece     != 6)               { return false; }
    if (mvPieceOnTo >= 6)               { return false; }
    if (mvToRow     != 7*(1-MoveColor)) { return false; }
  }
  
  // It is a piece move. Loop over all pieces and find the ones of the same
  // type as the one in the move. For each one of these check if they could 
  // have made the move.
  var pieceId;
  for (pieceId = 0; pieceId < 16; ++pieceId){
     if (PieceType[MoveColor][pieceId] == mvPiece){
      if (mvPiece == 1) { retVal = CheckLegalityKing(pieceId); }
      else if (mvPiece == 2) { retVal = CheckLegalityQueen(pieceId); }
      else if (mvPiece == 3) { retVal = CheckLegalityRook(pieceId); }
      else if (mvPiece == 4) { retVal = CheckLegalityBishop(pieceId); }
      else if (mvPiece == 5) { retVal = CheckLegalityKnight(pieceId); }
      else if (mvPiece == 6) { retVal = CheckLegalityPawn(pieceId); }
      if (retVal) {
	mvPieceId = pieceId;
        // Now that the board is updated check if the king is in check.
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
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisKing])) { return false; }
  if ((mvFromRow > 0) &&
      (mvFromRow != PieceRow[MoveColor][thisKing])) { return false; }

  if (Math.abs(PieceCol[MoveColor][thisKing]-mvToCol) > 1) { return false; }
  if (Math.abs(PieceRow[MoveColor][thisKing]-mvToRow) > 1) { return false; }

  return true;
}

function CheckLegalityQueen(thisQueen) {
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisQueen])) { return false; }
  if ((mvFromRow >= 0) &&
      (mvFromRow != PieceRow[MoveColor][thisQueen])) { return false; }

  if (((PieceCol[MoveColor][thisQueen]-mvToCol)*
       (PieceRow[MoveColor][thisQueen]-mvToRow) !== 0) &&
      (Math.abs(PieceCol[MoveColor][thisQueen]-mvToCol) !=
       Math.abs(PieceRow[MoveColor][thisQueen]-mvToRow))) { return false; }

  var clearWay = CheckClearWay(thisQueen);
  if (!clearWay) { return false; }

  return true;
}

function CheckLegalityRook(thisRook) {
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisRook])) { return false; }
  if ((mvFromRow >= 0) &&
      (mvFromRow != PieceRow[MoveColor][thisRook])) { return false; }

  if ((PieceCol[MoveColor][thisRook]-mvToCol)*
      (PieceRow[MoveColor][thisRook]-mvToRow) !== 0) { return false; }

  var clearWay = CheckClearWay(thisRook);
  if (!clearWay) { return false; }

  return true;
}

function CheckLegalityBishop(thisBishop) {
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisBishop])) { return false; }
  if ((mvFromRow >= 0) &&
      (mvFromRow != PieceRow[MoveColor][thisBishop])) { return false; }

  if (Math.abs(PieceCol[MoveColor][thisBishop]-mvToCol) !=
      Math.abs(PieceRow[MoveColor][thisBishop]-mvToRow)) { return false; }

  var clearWay = CheckClearWay(thisBishop);
  if (!clearWay) { return false; }

  return true;
}

function CheckLegalityKnight(thisKnight) {
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisKnight])) { return false; }
  if ((mvFromRow >= 0) &&
      (mvFromRow != PieceRow[MoveColor][thisKnight])) { return false; }

  if (Math.abs(PieceCol[MoveColor][thisKnight]-mvToCol)*
      Math.abs(PieceRow[MoveColor][thisKnight]-mvToRow) != 2) { return false; }

  return true;
}

function CheckLegalityPawn(thisPawn) {
  if ((mvFromCol >= 0) &&
      (mvFromCol != PieceCol[MoveColor][thisPawn])) { return false; }
  if ((mvFromRow >= 0) &&
      (mvFromRow != PieceRow[MoveColor][thisPawn])) { return false; }

  if (Math.abs(PieceCol[MoveColor][thisPawn]-mvToCol) != mvCapture)
  { return false; }

  if (mvCapture) {
    if (PieceRow[MoveColor][thisPawn]-mvToRow != 2*MoveColor-1) { return false; }
  } else {
    if (PieceRow[MoveColor][thisPawn]-mvToRow == 4*MoveColor-2){
      if (PieceRow[MoveColor][thisPawn] != 1+5*MoveColor) { return false; }
      if (Board[mvToCol][mvToRow+2*MoveColor-1] !== 0)    { return false; }
    } else {
      if (PieceRow[MoveColor][thisPawn]-mvToRow != 2*MoveColor-1) { return false; }
    }
  }
  return true;
}

function CheckLegalityOO() {
  if (CastlingShort[MoveColor] === 0) { return false; }
  if (PieceMoveCounter[MoveColor][0] > 0) { return false; }
  
  // Find which rook was involved in the castling.
  var legal    = false;
  var thisRook = 0;
  while (thisRook < 16) {
    if ((PieceCol[MoveColor][thisRook]  >  PieceCol[MoveColor][0]) &&
	(PieceRow[MoveColor][thisRook]  == MoveColor*7)            &&
        (PieceType[MoveColor][thisRook] == 3)) {
      legal = true;
      break;
    }
    ++thisRook;
  }
  if (!legal) { return false; }
  if (PieceMoveCounter[MoveColor][thisRook] > 0) { return false; }
  
  // Check no piece is between the king and the rook. To make it compatible
  // with fisher-random rules clear the king and rook squares now.
  Board[PieceCol[MoveColor][0]][MoveColor*7]        = 0;
  Board[PieceCol[MoveColor][thisRook]][MoveColor*7] = 0;
  var col = PieceRow[MoveColor][thisRook];
  if (col < 6) { col = 6; }
  while ((col > PieceCol[MoveColor][0]) || (col >= 5)) {
    if (Board[col][MoveColor*7] !== 0) { return false; }
    --col;
  }
  castleRook = thisRook;
  return true;
}

function CheckLegalityOOO() {
  if (CastlingLong[MoveColor] === 0) { return false; }
  if (PieceMoveCounter[MoveColor][0] > 0) { return false; }

  // Find which rook was involved in the castling.
  var legal    = false;
  var thisRook = 0;
  while (thisRook < 16){
    if ((PieceCol[MoveColor][thisRook]  <  PieceCol[MoveColor][0]) &&
	(PieceRow[MoveColor][thisRook]  == MoveColor*7)            &&
        (PieceType[MoveColor][thisRook] == 3)) {
      legal = true;
      break;
    }
    ++thisRook;
  }
  if (!legal) { return false; }
  if (PieceMoveCounter[MoveColor][thisRook] > 0) { return false; }

  // Check no piece is between the king and the rook. To make it compatible
  // with fisher-random rules clear the king and rook squares now.
  Board[PieceCol[MoveColor][0]][MoveColor*7]        = 0;
  Board[PieceCol[MoveColor][thisRook]][MoveColor*7] = 0;
  var col = PieceRow[MoveColor][thisRook];
  if (col > 2) { col = 2; }
  while ((col > PieceCol[MoveColor][0]) || (col <= 3)) {
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

  while ((startCol != mvToCol) || (startRow != mvToRow)){
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
	((cc >= 65) && (cc <= 90)) || ((cc >=97) && (cc <= 122))){
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
  if ((initialHalfmove = parseInt(initialHalfmove, 10)) == NaN) { initialHalfmove = 0; }
}

function SetInitialGame(number_or_string) {
  if (number_or_string) { initialGame = number_or_string; }
}

// the clock value is detected with two options: first the DGT sequence [%clk 01:02] 
// is checked. 
// If this fails, then look for the beginning of the comment for a sequence of numbers 
// and ':' and '.' characters.
  
function clockFromComment(comment) {
  var clock = "";
  if ((DGTclock = comment.match(/\[%clk\s*(.*?)\]/)) !== null) { clock = DGTclock[1]; }
  else { if (!(clock = comment.match(/^\s*[0-9:\.]+/))) {clock = ""; } }
  return clock;
}


function HighlightLastMove() {
  var anchorName;

  // Remove the highlighting from the old anchor if any.
  if (oldAnchor >= 0){
    anchorName = 'Mv'+oldAnchor;
    theAnchor = document.getElementById(anchorName);
    if (theAnchor !== null) { theAnchor.className = 'move'; }
  }

  // Find which move has to be highlighted. If the move number is negative
  // we are at the starting position and nothing is to be highlighted and
  // the header on top of the board is removed.
  var showThisMove = CurrentPly - 1;
  if (showThisMove > StartPly + PlyNumber) { showThisMove = StartPly + PlyNumber; }

  var theShowCommentTextObject = document.getElementById("GameLastComment");
  if (theShowCommentTextObject !== null) {
    if (MoveComments[showThisMove+1] != undefined) {
      // remove PGN extension tags
      thisComment = MoveComments[showThisMove+1].replace(/\[%.*?\]\s*/g,''); // note trailing spaces are removed also
      // remove comments that are all spaces
      if (thisComment.match(/^\s*$/)) { thisComment = ''; }
    } else { thisComment = ''; }
    theShowCommentTextObject.innerHTML = thisComment !== '' ? MoveComments[showThisMove+1] : '-';
    theShowCommentTextObject.className = 'GameLastComment';
  }
  
  // Show the side to move
  text = (showThisMove+1)%2 === 0 ? 'white' : 'black';
 
  theObject = document.getElementById("GameSideToMove");
  if (theObject !== null) { theObject.innerHTML = text; }

  // Show the clock (if suitable info is found in the game comment)
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
                                     clockFromComment(MoveComments[showThisMove+1]) :
                                     initialLastMoverClock;
  }
  if (beforeLastMoverClockObject !== null) {
    beforeLastMoverClockObject.innerHTML = showThisMove+1 > StartPly+1 ?
                                           clockFromComment(MoveComments[showThisMove]) :
                                           initialBeforeLastMoverClock;
  }

  // Show the next move
  var theShowMoveTextObject = document.getElementById("GameNextMove");
  if (theShowMoveTextObject !== null) {
    if (showThisMove+1 >= (StartPly+PlyNumber)) {
      text = gameResult[currentGame];
    } else {
      text = (Math.floor((showThisMove+1)/2) + 1) + 
             ((showThisMove+1) % 2 === 0 ? '. ' : '... ') +
             Moves[showThisMove+1];
    }
    theShowMoveTextObject.innerHTML = text; 
    theShowMoveTextObject.className = 'GameNextMove';
    theShowMoveTextObject.style.whiteSpace = 'nowrap';
  }

  theShowMoveTextObject = document.getElementById("GameLastMove");
  if (theShowMoveTextObject !== null) {
    if (showThisMove < StartPly) {
      text = '-';
    } else {
      text = (Math.floor(showThisMove/2) + 1) + 
             (showThisMove % 2 === 0 ? '. ' : '... ') +
             Moves[showThisMove];
    }
    theShowMoveTextObject.innerHTML = text; 
    theShowMoveTextObject.className = 'GameLastMove';
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
        highlightColFrom = HistCol[0][showThisMove];
        if (highlightColFrom == undefined) { highlightColFrom = -1; }
        highlightRowFrom = HistRow[0][showThisMove];
        if (highlightRowFrom == undefined) { highlightRowFrom = -1; }
        highlightColTo = HistCol[2][showThisMove];
        if (highlightColTo == undefined) { highlightColTo = -1; }
        highlightRowTo = HistRow[2][showThisMove];
        if (highlightRowTo == undefined) { highlightRowTo = -1; }
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

// global vars to remember last highlighted square
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
  } else {
    lastColFromHighlighted = -1;
    lastRowFromHighlighted = -1;
  }

  if ( highlightSquare(colTo, rowTo, true) ) {
    lastColToHighlighted = colTo;
    lastRowToHighlighted = rowTo;
  } else {
    lastColToHighlighted = -1;
    lastRowToHighlighted = -1;
  }
}


function highlightSquare(col, row, on) {
  if ((col == undefined) || (row == undefined)) { return false; }
  if (! SquareOnBoard(col, row)) { return false; }

  // locates coordinates on the HTML table
  if (IsRotated) { trow = row; tcol = 7 - col; }
  else { trow = 7 - row; tcol = col; }

  theObject = document.getElementById('tcol' + tcol + 'trow' + trow);
  if (theObject === null) { return false; }

  if (on) {
    theObject.className = (trow+tcol)%2 === 0 ? "highlightWhiteSquare" : "highlightBlackSquare";
  } else {
    theObject.className = (trow+tcol)%2 === 0 ? "whiteSquare" : "blackSquare";
  }
  return true;
}


function pgnGameFromPgnText(pgnText) {

  // replace < and > with html entities to avoid html injection form the PGN data
  pgnText = pgnText.replace(/</g, "&lt;");
  pgnText = pgnText.replace(/>/g, "&gt;");

  lines = pgnText.split("\n");
  inGameHeader = false;
  inGameBody = false;
  gameIndex = -1;
  pgnGame.length = 0;
  for(ii in lines){

    // according to the PGN standard lines starting with % should be ignored
    if(lines[ii].charAt(0) == '%') { continue; }

    if(pgnHeaderTagRegExp.test(lines[ii]) === true) {
      if(!inGameHeader) {
        gameIndex++;
        pgnGame[gameIndex] = '';
      }
      inGameHeader=true;
      inGameBody=false;
    } else {
      if(inGameHeader) {
        inGameHeader=false;
        inGameBody=true;
      }
    }
    lines[ii] = lines[ii].replace(/^\s*/,"");
    lines[ii] = lines[ii].replace(/\s*$/,"");
    if (gameIndex >= 0) { pgnGame[gameIndex] += lines[ii] + ' \n'; } 
  }

  numberOfGames = pgnGame.length;

  return (gameIndex >= 0);
}


function loadPgnFromPgnUrl(pgnUrl){
  
  var http_request = false;
    if (window.XMLHttpRequest) { // Mozilla, Safari, ...
      http_request = new XMLHttpRequest();
      if (http_request.overrideMimeType) {
        http_request.overrideMimeType('text/xml');
      }
    } else if (window.ActiveXObject) { // IE
      try { http_request = new ActiveXObject("Msxml2.XMLHTTP"); }
      catch (e) {
        try { http_request = new ActiveXObject("Microsoft.XMLHTTP"); }
        catch (e) { }
      }
    }
  if (!http_request){
    myAlert('error: XMLHttpRequest failed for PGN URL\n' + pgnUrl, true);
    return false; 
  }

  try {
    // anti-caching tecnique number 1: add a random parameter to the URL
    if (LiveBroadcastDelay > 0) {
      dd = new Date();
      http_request.open("GET", pgnUrl + "?nocahce=" + Math.random(), false); 
    } else { http_request.open("GET", pgnUrl, false); }
    // anti-caching tecnique number 2: add header option
    if (LiveBroadcastDelay > 0) { 
      http_request.setRequestHeader( "If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT" ); 
    }
    http_request.send(null);
  } catch(e) {
      myAlert('error: request failed for PGN URL\n' + pgnUrl, true);
      return false;
}

  if((http_request.readyState == 4) && ((http_request.status == 200) || (http_request.status === 0))){
    if (! pgnGameFromPgnText(http_request.responseText)) {
      myAlert('error: no games found in PGN file\n' + pgnUrl, true);
      return false;
    }
  }else{ 
    myAlert('error: failed reading PGN from URL\n' + pgnUrl, true);
    return false;
  }

  return true;
}

function SetPgnUrl(url) {
  pgnUrl = url;
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

  // check if broadcast did not start yet
  // check for odd situations where no PGN file is found and fake LiveBroadcastPlaceholderPgn game is injected
  if ((LiveBroadcastStarted === false) || 
      ((pgnGame == undefined) || 
      ((numberOfGames == 1) && (gameEvent[0] == LiveBroadcastPlaceholderEvent)))) {
    LiveBroadcastEnded = false;
    LiveBroadcastStatusString = "live broadcast yet to start";
  } else {
    // broadcast started with a good PGN
    liveGamesRunning = 0;
    for (ii=0; ii<numberOfGames; ii++) {
      if (gameResult[ii].indexOf('*') >= 0) { liveGamesRunning++; }
    }
    LiveBroadcastEnded = (liveGamesRunning === 0);

    LiveBroadcastStatusString = LiveBroadcastEnded ?
                                "live broadcast ended" :
                                "live games: " + liveGamesRunning +
                                " &nbsp; finished: " + (numberOfGames - liveGamesRunning);
  }

  theObject = document.getElementById("GameLiveStatus");
  if (theObject !== null) { theObject.innerHTML = LiveBroadcastStatusString; }
}

function restartLiveBroadcastTimeout() {
  if (LiveBroadcastDelay === 0) { return; }
  if (LiveBroadcastInterval) { clearTimeout(LiveBroadcastInterval); LiveBroadcastInterval = null; }
  checkLiveBroadcastStatus();
  needRestart = (!LiveBroadcastEnded);
  if ((needRestart === true) && (!LiveBroadcastPaused)){
    LiveBroadcastInterval = setTimeout("refreshPgnSource()", LiveBroadcastDelay * 60000);
  }
  LiveBroadcastTicker++;
}

var LiveBroadcastFoundOldGame = false;
function refreshPgnSource() {
  if (LiveBroadcastDelay === 0) { return; }
  if (LiveBroadcastInterval) { clearTimeout(LiveBroadcastInterval); LiveBroadcastInterval = null; }
  if (LiveBroadcastDemo) {
    for(ii=0;ii<numberOfGames;ii++) {
      rnd = Math.random();
      if (rnd <= 0.05)      { gameDemoMaxPly[ii] += 3; } //  5% of times add 3 ply
      else if (rnd <= 0.20) { gameDemoMaxPly[ii] += 2; } // 15% of times add 2 ply
      else if (rnd <= 0.60) { gameDemoMaxPly[ii] += 1; } // 40% of times add 1 ply
    }                                                    // 40% of times add 0 ply
  }

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

  LiveBroadcastStarted = loadPgnFromPgnUrl(pgnUrl);
  if (!LiveBroadcastStarted) { pgnGameFromPgnText(LiveBroadcastPlaceholderPgn); }

  LoadGameHeaders();
  LiveBroadcastFoundOldGame = false;
  for (ii=0; ii<numberOfGames; ii++) {
    LiveBroadcastFoundOldGame = ( (gameWhite[ii]==oldGameWhite) && 
                                  (gameBlack[ii]==oldGameBlack) &&
                                  (gameEvent[ii]==oldGameEvent) && 
                                  (gameRound[ii]==oldGameRound) &&
                                  (gameSite[ii] ==oldGameSite ) && 
                                  (gameDate[ii] ==oldGameDate ) );
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

  restartLiveBroadcastTimeout();

  if (LiveBroadcastFoundOldGame && oldAutoplay) { SetAutoPlay(true); }

}

function createBoard(){

  theObject = document.getElementById("GameBoard");
  if (theObject !== null) {
    theObject.innerHTML = '<DIV STYLE="font-size: small; font-family: sans-serif; ' +
                          'padding: 10px; text-align: center;">' + 
                          '...loading PGN data<br />please wait...</DIV>';
  }

  if (pgnUrl) {
    if ( loadPgnFromPgnUrl(pgnUrl) ) {
      if (LiveBroadcastDelay > 0) { LiveBroadcastStarted = true; }
      Init();
      if (LiveBroadcastDelay > 0) { checkLiveBroadcastStatus(); }
      customFunctionOnPgnTextLoad();
      return;
    } else {
      if (LiveBroadcastDelay === 0) {
        pgnGameFromPgnText(alertPgnHeader);
        Init();
        customFunctionOnPgnTextLoad();
        myAlert('error: failed loading games from PGN URL\n' + pgnUrl, true);
        return;
      } else { // live broadcast case, wait for live show to start
        LiveBroadcastStarted = false;
        pgnGameFromPgnText(LiveBroadcastPlaceholderPgn); 
        Init();
	checkLiveBroadcastStatus();
        customFunctionOnPgnTextLoad();
        return;
      }
    }
  } else if ( document.getElementById("pgnText") ) {
    if (document.getElementById("pgnText").tagName.toLowerCase() == "textarea") {
      tmpText = document.getElementById("pgnText").value;
    } else { // backward compatibility with pgn4web older than 1.77 when the <span> technique was used for pgnText
      tmpText = document.getElementById("pgnText").innerHTML;
      // fixes issue with some browser removing \n from innerHTML
      if (tmpText.indexOf('\n') < 0) { tmpText = tmpText.replace(/((\[[^\[\]]*\]\s*)+)/g, "\n$1\n"); }
      // fixes issue with some browser replacing quotes with &quot; such as the blackberry browser
      if (tmpText.indexOf('"') < 0) { tmpText = tmpText.replace(/(&quot;)/g, '"'); }
    }

    // if no html header is present, add emptyPgnHeader at the top
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


function Init(){

  if (isAutoPlayOn) { SetAutoPlay(false); }
  InitImages();
  if (firstStart){
    LoadGameHeaders();
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
          if (initialGame < 0) { currentGame = 0; }
          else if (initialGame === 0) { currentGame = Math.floor(Math.random()*numberOfGames); }
          else if (initialGame <= numberOfGames) { currentGame = (initialGame - 1); } 
          else { currentGame = numberOfGames - 1; }
        }
        break;
    }
  }

  if ((gameSetUp[currentGame] != undefined) && (gameSetUp[currentGame] != "1")) { InitFEN(); }
  else { InitFEN(gameFEN[currentGame]); }
  
  OpenGame(currentGame);
  
  // Find the index of the first square image if needed.
  if (ImageOffset < 0) {
    for (ii = 0; ii < document.images.length; ++ii) {
      if (document.images[ii].src == ClearImg.src) {
        ImageOffset = ii;
        break;
      }
    }
  }

  RefreshBoard();
  CurrentPly = StartPly;
  HighlightLastMove(); 
  if (firstStart || alwaysInitialHalfmove) {
    switch (initialHalfmove) {
      case "start":
        GoToMove(0);
        break;
      case "end":
        GoToMove(StartPly+PlyNumber);
        break;
      case "random":
        GoToMove(StartPly + Math.floor(Math.random()*(StartPly+PlyNumber)));
        break;
      case "comment":
        GoToMove(0);
        MoveToNextComment();
        break;
      default:
        if (isNaN(initialHalfmove)) { initialHalfmove = 0; }
        if (initialHalfmove < -3) { initialHalfmove = 0; }
        if (initialHalfmove == -3) { GoToMove(StartPly+PlyNumber); }
        else if (initialHalfmove == -2) { GoToMove(0); MoveToNextComment(); }
        else if (initialHalfmove == -1) { GoToMove(StartPly + Math.floor(Math.random()*(StartPly+PlyNumber))); }
        else { GoToMove(initialHalfmove); }
        break;
    }
  } else {
    // added here customFunctionOnMove for consistency, as a null move starting a new game
    customFunctionOnMove();
  }
  if ((firstStart) && (autostartAutoplay)) { SetAutoPlay(true); }

  customFunctionOnPgnGameLoad();

  firstStart = false;
}


function InitFEN(startingFEN) {
  FenString = startingFEN != undefined ? startingFEN : FenStringStart;
  
  // Reset the board
  var ii, jj;
  for (ii = 0; ii < 8; ++ii) {
    for (jj = 0; jj < 8; ++jj) {
      Board[ii][jj] = 0;
    }
  }

  // Set the initial position. As of now only the normal starting position.
  var color, pawn;
  StartPly  = 0;
  MoveCount = StartPly;
  MoveColor = StartPly % 2;
  StartMove = 0;

  var newEnPassant = false;
  var newEnPassantCol;
  for (ii = 0; ii < 2; ii++) { CastlingLong[ii] = CastlingShort[ii] = 1; }
  InitialHalfMoveClock = 0;

  if (FenString == "rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1") {
    for (color = 0; color < 2; ++color) {
      PieceType[color][0] = 1;  // King
      PieceCol[color][0]  = 4;
      PieceType[color][1] = 2;  // Queen
      PieceCol[color][1]  = 3;
      PieceType[color][6] = 3;  // Rooks
      PieceType[color][7] = 3;
      PieceCol[color][6]  = 0;
      PieceCol[color][7]  = 7;
      PieceType[color][4] = 4;  // Bishops
      PieceType[color][5] = 4;
      PieceCol[color][4]  = 2;
      PieceCol[color][5]  = 5;
      PieceType[color][2] = 5;  // Knights
      PieceType[color][3] = 5;
      PieceCol[color][2]  = 1;
      PieceCol[color][3]  = 6;
      for (pawn = 0; pawn < 8; ++pawn){
	PieceType[color][pawn+8] = 6;
	PieceCol[color][pawn+8]  = pawn;
      }
      for (ii = 0; ii < 16; ++ii){
	PieceMoveCounter[color][ii] = 0;
	PieceRow[color][ii]         = (1-color) * Math.floor(ii/8) +
 	                                 color  * (7-Math.floor(ii/8));
      }
      for (ii = 0; ii < 16; ii++){
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
          myAlert("error: invalid FEN [1] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }
        ii = 0;
        jj--;
      }
      if (ii == 8) {
        myAlert("error: invalid FEN [2] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
        InitFEN();
        return;
      }
      if (!isNaN(cc)) {
        ii += parseInt(cc, 10);
        if ((ii < 0) || (ii > 8)) {
          myAlert("error: invalid FEN [3] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
          InitFEN();
          return;
        }
      }
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(0)) {
        if (PieceType[0][0] != -1) {
          myAlert("error: invalid FEN [4] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
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
          myAlert("error: invalid FEN [5] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
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
            myAlert("error: invalid FEN [6] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
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
            myAlert("error: invalid FEN [7] char "+ll+" '" + cc + "' in game "+(currentGame+1)+"\n"+FenString, true);
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
      myAlert("error: invalid FEN [8] char "+ll+" in game "+(currentGame+1)+"\n"+FenString, true);
      InitFEN();
      return;
    }
    if ((PieceType[0][0] == -1) || (PieceType[1][0] == -1)) {
      myAlert("error: invalid FEN [9]: missing king in game "+(currentGame+1)+"\n"+FenString, true);
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
      myAlert("error: invalid FEN [11]: char "+ll+" '" + cc + "' invalid active color in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }

    ll++;
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN [12]: char "+ll+" missing castling availability in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    CastlingShort[0] = CastlingLong[0] = CastlingShort[1] = CastlingLong[1] = 0;
    cc = FenString.charAt(ll++);
    while (cc!=" ") {
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(0))
      { CastlingShort[0] = 1; }
      if (cc.charCodeAt(0) == FenPieceName.toUpperCase().charCodeAt(1))
      { CastlingLong[0] = 1; }
      if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(0))
      { CastlingShort[1]=1; }
      if (cc.charCodeAt(0) == FenPieceName.toLowerCase().charCodeAt(1))
      { CastlingLong[1] = 1; }
      if ((cc == "E") || (cc == "F") || (cc == "G") || (cc == "H")) //for Chess960
      { CastlingShort[0] = 1; }
      if ((cc == "A") || (cc == "B") || (cc == "C") || (cc == "D"))
      { CastlingLong[0] = 1; }
      if ((cc == "e") || (cc == "f") || (cc == "g") || (cc=="h"))
      { CastlingShort[1] = 1; }
      if ((cc == "a") || (cc == "b") || (cc == "c") || (cc == "d"))
      { CastlingLong[1] = 1; }
      cc = ll<FenString.length ? FenString.charAt(ll++) : " ";
    }

    // Set board
    for (color = 0; color < 2; ++color) {
      for (ii = 0; ii < 16; ii++) {
        if (PieceType[color][ii] != -1) {
   	  col = PieceCol[color][ii];
	  row = PieceRow[color][ii];
	  Board[col][row] = (1-2*color)*(PieceType[color][ii]);
	}
      }
    }
          
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN [13]: char "+ll+" missing en passant target square in game "+(currentGame+1)+"\n"+FenString, true);
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
      myAlert("error: invalid FEN [14]: char "+ll+" missing halfmove clock in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    InitialHalfMoveClock = 0;
    cc = FenString.charAt(ll++);
    while (cc != " ") {
      if (isNaN(cc)) {
        myAlert("error: invalid FEN [15]: char "+ll+" '" + cc + "' invalid halfmove clock in game "+(currentGame+1)+"\n"+FenString, true);
        return;
      }
      InitialHalfMoveClock=InitialHalfMoveClock*10+parseInt(cc, 10);
      cc = ll<FenString.length ? FenString.charAt(ll++) : " ";
    }
    if (ll >= FenString.length) {
      myAlert("error: invalid FEN [16]: char "+ll+" missing fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    cc = FenString.substring(ll++);
    if (isNaN(cc)) {
      myAlert("error: invalid FEN [17]: char "+ll+" '" + cc + "' invalid fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    if (cc <= 0) {
      myAlert("error: invalid FEN [18]: char "+ll+" '" + cc + "' invalid fullmove number in game "+(currentGame+1)+"\n"+FenString, true);
      return;
    }
    StartPly += 2*(parseInt(cc, 10)-1);

    HistEnPassant[StartPly-1] = newEnPassant;
    HistEnPassantCol[StartPly-1] = newEnPassantCol;
  }
}

function SetImageType(extension) {
  imageType = extension;
}


function InitImages() {
  // Reset the array describing what image is in each square.
  DocumentImages.length = 0;
  
  // No need if the directory where we pick images is not changed.
  if (ImagePathOld == ImagePath) { return; }

  /* adds a trailing / to ImagePath if missing and if path not blank */
  if ((ImagePath.length > 0) && (ImagePath[ImagePath.length-1] != '/')) {
    ImagePath += '/';
  }

  // No image.
  ClearImg.src = ImagePath+'clear.'+imageType;

  // Load the images.
  var color;
  ColorName = new Array ("w", "b");
  for (color = 0; color < 2; ++color) {
    PiecePicture[color][1]     = new Image();
    PiecePicture[color][1].src = ImagePath + ColorName[color] + 'k.'+imageType;
    PiecePicture[color][2]     = new Image();
    PiecePicture[color][2].src = ImagePath + ColorName[color] + 'q.'+imageType;
    PiecePicture[color][3]     = new Image();
    PiecePicture[color][3].src = ImagePath + ColorName[color] + 'r.'+imageType;
    PiecePicture[color][4]     = new Image();
    PiecePicture[color][4].src = ImagePath + ColorName[color] + 'b.'+imageType;
    PiecePicture[color][5]     = new Image();
    PiecePicture[color][5].src = ImagePath + ColorName[color] + 'n.'+imageType;
    PiecePicture[color][6]     = new Image();
    PiecePicture[color][6].src = ImagePath + ColorName[color] + 'p.'+imageType;
  }
  ImagePathOld = ImagePath;
}

function IsCheck(col, row, color) {
  var ii, jj;
  var sign = 2*color-1; // white or black

  // Is the other king giving check?
  if ((Math.abs(PieceCol[1-color][0]-col) <= 1) &&
      (Math.abs(PieceRow[1-color][0]-row) <= 1)) { return true; }

  // Any knight giving check?
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

  // Any pawn giving check?
  for (ii = -1; ii <= 1; ii += 2){
    if (SquareOnBoard(col+ii, row-sign)){
      if (Board[col+ii][row-sign] == sign*6) { return true; }
    }
  }

  // Now queens, rooks and bishops.
  for (ii = -1; ii <= 1; ++ii) {
    for (jj = -1; jj <= 1; ++jj) {
      if ((ii !== 0) || (jj !== 0)) {
	var checkCol  = col+ii;
	var checkRow  = row+jj;
	var thisPiece = 0;

	while (SquareOnBoard(checkCol, checkRow) && (thisPiece === 0)){
	  thisPiece = Board[checkCol][checkRow];
	  if (thisPiece === 0){
	    checkCol += ii;
	    checkRow += jj;
	  } else {
	    if (thisPiece  == sign*2)                                { return true; }
	    if ((thisPiece == sign*3) && ((ii === 0) || (jj === 0))) { return true; }
	    if ((thisPiece == sign*4) && ((ii !== 0) && (jj !== 0))) { return true; }
	  }
	}
      }
    }
  }
  return false;
}

function checkHeaderDefined(headerValue) {
  return ((headerValue != undefined) && (headerValue !== "") && (headerValue != " ") && (headerValue != "?"));
}


function LoadGameHeaders(){
  var ii;

  // Initialize the global arrays to the number of games length.
  gameEvent.length = gameSite.length = gameRound.length = gameDate.length = 0;
  gameWhite.length = gameBlack.length = gameResult.length = 0;
  gameSetUp.length = gameFEN.length = 0;
  gameInitialWhiteClock.length = gameInitialBlackClock.length = 0;

  // Read the headers of all games and store them in the global arrays
  pgnHeaderTagRegExpGlobal.exec(""); // to cope with IE bug when reloading a PGN as in inputform.html
  for (ii = 0; ii < numberOfGames; ++ii) {
    var ss = pgnGame[ii];
    var parse;
    gameEvent[ii] = gameSite[ii] = gameRound[ii] = gameDate[ii] = "";
    gameWhite[ii] = gameBlack[ii] = gameResult[ii] = "";
    gameInitialWhiteClock[ii] = gameInitialBlackClock[ii] = "";
    while ((parse = pgnHeaderTagRegExpGlobal.exec(ss)) !== null){
      if       (parse[1] == 'Event')      { gameEvent[ii]  = parse[2]; }
      else if  (parse[1] == 'Site')       { gameSite[ii]   = parse[2]; }
      else if  (parse[1] == 'Round')      { gameRound[ii]  = parse[2]; }
      else if  (parse[1] == 'Date')       { gameDate[ii]   = parse[2]; }
      else if  (parse[1] == 'White')      { gameWhite[ii]  = parse[2]; }
      else if  (parse[1] == 'Black')      { gameBlack[ii]  = parse[2]; }
      else if  (parse[1] == 'Result')     { gameResult[ii] = parse[2]; }
      else if  (parse[1] == 'SetUp')      { gameSetUp[ii]  = parse[2]; }
      else if  (parse[1] == 'FEN')        { gameFEN[ii]    = parse[2]; }
      else if  (parse[1] == 'WhiteClock') { gameInitialWhiteClock[ii] = parse[2]; }
      else if  (parse[1] == 'BlackClock') { gameInitialBlackClock[ii] = parse[2]; }
    }
  }
  if ((LiveBroadcastDemo) && (numberOfGames > 0)) {
    for (ii = 0; ii < numberOfGames; ++ii) {
       if (gameDemoLength[ii] == undefined) {
         InitFEN(gameFEN[ii]);
         ParsePGNGameString(pgnGame[ii]);
         gameDemoLength[ii] = PlyNumber; 
       }
       if (gameDemoMaxPly[ii] == undefined) { gameDemoMaxPly[ii] = 0; }
       if (gameDemoMaxPly[ii] <= gameDemoLength[ii]) { gameResult[ii] = '*'; }
    }
  }

  return;
}


function MoveBackward(diff) {

  // First of all find to which ply we have to go back. Remember that
  // CurrentPly contains the ply number counting from 1.
  var goFromPly  = CurrentPly - 1;
  var goToPly    = goFromPly  - diff;
  if (goToPly < StartPly) { goToPly = StartPly-1; }

  // Loop back to reconstruct the old position one ply at the time.
  var thisPly;
  for(thisPly = goFromPly; thisPly > goToPly; --thisPly) {
    CurrentPly--;
    MoveColor = 1-MoveColor;

    // Reposition the moved piece on the original square.
    var chgPiece = HistPieceId[0][thisPly];
    Board[PieceCol[MoveColor][chgPiece]][PieceRow[MoveColor][chgPiece]] = 0;

    Board[HistCol[0][thisPly]][HistRow[0][thisPly]] = HistType[0][thisPly] * (1-2*MoveColor);
    PieceType[MoveColor][chgPiece] = HistType[0][thisPly];
    PieceCol[MoveColor][chgPiece] = HistCol[0][thisPly];
    PieceRow[MoveColor][chgPiece] = HistRow[0][thisPly];
    PieceMoveCounter[MoveColor][chgPiece]--;

    // If the move was a castling reposition the rook on its original square.
    chgPiece = HistPieceId[1][thisPly];
    if ((chgPiece >= 0) && (chgPiece < 16)) {
       Board[PieceCol[MoveColor][chgPiece]][PieceRow[MoveColor][chgPiece]] = 0;
       Board[HistCol[1][thisPly]][HistRow[1][thisPly]] = HistType[1][thisPly] * (1-2*MoveColor);
       PieceType[MoveColor][chgPiece] = HistType[1][thisPly];
       PieceCol[MoveColor][chgPiece] = HistCol[1][thisPly];
       PieceRow[MoveColor][chgPiece] = HistRow[1][thisPly];
       PieceMoveCounter[MoveColor][chgPiece]--;
    } 

    // For captures, reposition the captured piece on its original square.
    chgPiece -= 16;
    if ((chgPiece >= 0) && (chgPiece < 16)) {
       Board[PieceCol[1-MoveColor][chgPiece]][PieceRow[1-MoveColor][chgPiece]] = 0;
       Board[HistCol[1][thisPly]][HistRow[1][thisPly]] = HistType[1][thisPly]*
	(2*MoveColor-1);
       PieceType[1-MoveColor][chgPiece] = HistType[1][thisPly];
       PieceCol[1-MoveColor][chgPiece] = HistCol[1][thisPly];
       PieceRow[1-MoveColor][chgPiece] = HistRow[1][thisPly];
       PieceMoveCounter[1-MoveColor][chgPiece]--;
    } 
  }

  // With the old position refresh the board and update the ply count on the HTML.
  RefreshBoard();
  HighlightLastMove(); 

  // Set a new timeout if in autoplay mode.
  if (AutoPlayInterval) { clearTimeout(AutoPlayInterval); AutoPlayInterval = null; }
  if (isAutoPlayOn) {
    if(goToPly >= StartPly) { AutoPlayInterval=setTimeout("MoveBackward(1)", Delay); }
    else { SetAutoPlay(false); }
  } 
  customFunctionOnMove();
}


function MoveForward(diff) {

  // First of all find to which ply we have to go back. Remember that
  // CurrentPly contains the ply number counting from 1.
  goToPly = CurrentPly + parseInt(diff, 10);

  if (goToPly > (StartPly+PlyNumber)) { goToPly = StartPly+PlyNumber; }
  var thisPly;

  // Loop over all moves till the selected one is reached. Check that
  // every move is legal and if yes update the board.
  for(thisPly = CurrentPly; thisPly < goToPly; ++thisPly) {
    var move = Moves[thisPly];
    var parse = ParseMove(move, thisPly);
    if (!parse) {
      text = (Math.floor(thisPly / 2) + 1) + ((thisPly % 2) === 0 ? '. ' : '... ');
      myAlert('error: invalid ply ' + text + move + ' in game ' + (currentGame+1), true);
      break;
    }
    MoveColor = 1-MoveColor; 
  }

  // Once the desired position is reached refresh the board and update the 
  // ply count on the HTML.
  CurrentPly = thisPly;
  RefreshBoard();
  HighlightLastMove(); 

  // Set a new timeout if in autoplay mode and if all parsing was successful
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
    if (++currentGame >= numberOfGames) { currentGame = 0; }
    Init();
    if ((numberOfGames > 0) || (PlyNumber > 0)) {
      SetAutoPlay(true);
      return;
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
  // Get rid of the PGN tags and remove the result at the end. 
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
          }else{
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
        if (ss.indexOf(searchThis,start)==start){
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '0-1';
        if (ss.indexOf(searchThis,start)==start){
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '1/2-1/2';
        if (ss.indexOf(searchThis,start)==start){
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        searchThis = '*';
        if (ss.indexOf(searchThis,start)==start){
          start += searchThis.length;
          MoveComments[StartPly+PlyNumber] += ss.substring(start, ss.length);
          start = ss.length;
          break;
        }
        
        moveCount = Math.floor((StartPly+PlyNumber)/2)+1;
        searchThis = moveCount.toString()+'.';
        if(ss.indexOf(searchThis,start)==start){
          start += searchThis.length;
          while ((ss.charAt(start) == '.') || (ss.charAt(start) == ' ')  || (ss.charAt(start) == '\n') || (ss.charAt(start) == '\r')){start++;}
        } else {
          searchThis = moveCount.toString()+String.fromCharCode(8230); // ellipsis ...
          if(ss.indexOf(searchThis,start)==start){
            start += searchThis.length;
            while ((ss.charAt(start) == '.') || (ss.charAt(start) == ' ')  || (ss.charAt(start) == '\n') || (ss.charAt(start) == '\r')){start++;}
          }
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
        if (Moves[StartPly+PlyNumber] !== "") { // takes into account odd cased of misformed PGN data
          PlyNumber++;
          MoveComments[StartPly+PlyNumber]='';
        }
        break;
    }
  }
  for (ii=StartPly; ii<=PlyNumber; ii++) {
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
    nag = parseInt(comment.substring(ii+1,jj), 10);
    if ((nag != undefined) && (NAG[nag] != undefined)) {
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
  // Reset the global move variables.
  castleRook    = -1;
  mvIsCastling  =  0;
  mvIsPromotion =  0;
  mvCapture     =  0;
  mvFromCol     = -1;
  mvFromRow     = -1;
  mvToCol       = -1;
  mvToRow       = -1;
  mvPiece       = -1;
  mvPieceId     = -1;
  mvPieceOnTo   = -1;
  mvCaptured    = -1;
  mvCapturedId  = -1;

  // Given the move as something like Rdxc3 or exf8=Q+ extract the destination
  // column and row and remember whatever is left of the string.
  ii = 1;
  while(ii < move.length) {
    if (!isNaN(move.charAt(ii))) {
      mvToCol     = move.charCodeAt(ii-1) - 97;
      mvToRow     = move.charAt(ii)       -  1;
      remainder   = move.substring(0, ii-1);
      toRowMarker = ii;
    }
    ++ii;
  }

  // The final square did not make sense, maybe it is a castle.
  if ((mvToCol < 0) || (mvToCol > 7) || (mvToRow < 0) || (mvToRow > 7)) {
    if ((move.indexOf('O') >= 0) || (move.indexOf('o') >= 0) || (move.indexOf('0') >= 0)) {
      // Do long castling first since looking for o-o will get it too.
      if (move.match('^[Oo0]-?[Oo0]-?[Oo0]$') !== null) {
	mvIsCastling = 1;
        mvPiece      = 1;
        mvPieceId    = 0;
        mvPieceOnTo  = 1;
        mvFromCol    = 4;
        mvToCol      = 2;
        mvFromRow    = 7*MoveColor;
        mvToRow      = 7*MoveColor;
        return CheckLegality('O-O-O', plyCount);
      }
      if (move.match('^[Oo0]-?[Oo0]$') !== null) {
        mvIsCastling = 1;
        mvPiece      = 1;
        mvPieceId    = 0;
        mvPieceOnTo  = 1;
	mvFromCol    = 4;
	mvToCol      = 6;
        mvFromRow    = 7*MoveColor;
        mvToRow      = 7*MoveColor;
	return CheckLegality('O-O', plyCount);
      }
      return false;
    } else { return false; }
  }

  // Now extract the piece and the origin square. If it is a capture (the 'x'
  // is present) mark the as such.
  ll = remainder.length;
  if (ll > 3) { return false; }
  mvPiece = -1; // make sure mvPiece is assigned to something sensible later
  if (ll === 0) { mvPiece = 6; }
  else {
    for(ii = 1; ii < 6; ++ii) { if (remainder.charAt(0) == PieceCode[ii-1]) { mvPiece = ii; } }
    if (mvPiece == -1) { if ('abcdefgh'.indexOf(remainder.charAt(0)) >= 0) { mvPiece = 6; } }
    if (mvPiece == -1) { return false; }
    if (remainder.charAt(ll-1) == 'x') { mvCapture = 1; }
    if (isNaN(move.charAt(ll-1-mvCapture))){
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
  // If the to square is occupied mark the move as capture. Take care of
  // the special en passant case.
  if (Board[mvToCol][mvToRow] !== 0) { mvCapture = 1; }
  else {
    if ((mvPiece == 6) && (HistEnPassant[plyCount-1]) && 
        (mvToCol == HistEnPassantCol[plyCount-1]) &&
	(mvToRow == 5-3*MoveColor)) {
      mvCapture = 1;
    }
  }

  // Take care of promotions. If there is a '=' in the move or if the
  // destination row is not the last character in the move, then it may be a
  // pawn promotion.
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

  // Find which piece was captured. The first part checks normal captures.
  // If nothing is found then it has to be a pawn making an en-passant
  // capture.
  if (mvCapture) {
    mvCapturedId = 15;
    while((mvCapturedId >= 0) && (mvCaptured < 0)) {
      if ((PieceType[1-MoveColor][mvCapturedId] >  0)       &&
	  (PieceCol[1-MoveColor][mvCapturedId]  == mvToCol) &&
	  (PieceRow[1-MoveColor][mvCapturedId]  == mvToRow)){
	mvCaptured = PieceType[1-MoveColor][mvCapturedId];
      } else { --mvCapturedId; }
    }
    if ((mvPiece == 6) && (mvCapturedId < 1) && (HistEnPassant[plyCount-1])) {
      mvCapturedId = 15;
      while((mvCapturedId >= 0) && (mvCaptured < 0)){
        if ((PieceType[1-MoveColor][mvCapturedId] == 6)       &&
	    (PieceCol[1-MoveColor][mvCapturedId]  == mvToCol) &&
	    (PieceRow[1-MoveColor][mvCapturedId]  == 4-MoveColor)) {
	  mvCaptured = PieceType[1-MoveColor][mvCapturedId];
	} else { --mvCapturedId; }
      }
    }
  }

  // Check the move legality.
  var retVal;
  retVal = CheckLegality(PieceCode[mvPiece-1], plyCount);
  if (!retVal) { return false; }

  // If a pawn was moved check for en-passant capture on next move
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
  if (clickedSquareInterval) { return; } // make sure you dont trigger the effect twice by mistake
  squareId = 'tcol' + jj + 'trow' + ii;
  theObject = document.getElementById(squareId);
  originalClass = theObject.className;
  newClass = (ii+jj)%2 === 0 ? "blackSquare" : "whiteSquare";
  theObject.className = newClass;
  clickedSquareInterval = setTimeout("reset_after_click(" + ii + "," + jj + ",'" + originalClass + "','" + newClass + "')", 66);
}

function reset_after_click (ii, jj, originalClass, newClass) {
  squareId = 'tcol' + jj + 'trow' + ii;
  theObject = document.getElementById(squareId);
  // if the square class has been changed by pgn4web already (due to autoplay for instance) dont touch it anymore
  if (theObject.className == newClass) { theObject.className = originalClass; }
  clickedSquareInterval = null;
}


var lastSearchPgnExpression = "";
function gameNumberSearchPgn(searchExpression) {
  lastSearchPgnExpression = searchExpression;
  if (searchExpression === "") { return false; }
  // when searching we replace newline characters with spaces, 
  // so that we can use the "." special regexp characters on the whole game as a single line
  newlinesRegExp = new RegExp("[\n\r]", "gm");
  searchExpressionRegExp = new RegExp(searchExpression, "im");
  currentGameSearch = (currentGame + 1) % numberOfGames;
  for (checkGame = (currentGameSearch + 1) % numberOfGames; 
       checkGame != currentGameSearch; 
       checkGame = (checkGame + 1) % numberOfGames) { 
    if (pgnGame[checkGame].replace(newlinesRegExp, " ").match(searchExpressionRegExp)) {
      return checkGame;
    }
  }
  return false;
}

function searchPgnGame(searchExpression) {
  lastSearchPgnExpression = searchExpression;
  if ((searchExpression === "") || (! searchExpression)) { return; }
  if (numberOfGames < 2) { return; }
  checkGame = gameNumberSearchPgn(searchExpression);
  if (checkGame != currentGame) {
    currentGame = checkGame;
    Init();
  }
}

function searchPgnGamePrompt() {
  if (numberOfGames < 2) { 
    alert("info: search prompt disabled with less than 2 games"); 
    return;
  }
  searchExpression = prompt("Please enter search pattern for PGN games:", lastSearchPgnExpression);
  if (! searchExpression) { return; }
  theObject = document.getElementById('searchPgnExpression');
  if (theObject) {
    theObject.value = searchExpression;
  }
  searchPgnGame(searchExpression);
}


var tableSize = null;
function PrintHTML() {
  var ii, jj;
  var text;

  // Show the board as a 8x8 table.

  text = '<TABLE CLASS="boardTable" ID="boardTable" CELLSPACING=0 CELLPADDING=0';
  text += ((tableSize !== null) && (tableSize !== 0)) ?
          ' STYLE="width: ' + tableSize + 'px; height: ' + tableSize + 'px;">' :
          '>';
  for (ii = 0; ii < 8; ++ii) {
    text += '<TR>';
    for (jj = 0; jj < 8; ++jj) {
      squareId = 'tcol' + jj + 'trow' + ii;
      imageId = 'img_' + squareId;
      linkId = 'link_' + squareId;
      text += (ii+jj)%2 === 0 ? 
              '<TD CLASS="whiteSquare" ID="' + squareId + '" BGCOLOR="white"' :
              '<TD CLASS="blackSquare" ID="' + squareId + '" BGCOLOR="lightgray"';
      text += ' ALIGN="center" VALIGN="middle" ONCLICK="clickedSquare(' + ii + ',' + jj + ')">';
      squareCoord = IsRotated ? String.fromCharCode(72-jj,49+ii) : String.fromCharCode(jj+65,56-ii);
      squareTitle = squareCoord;
      if (boardTitle[jj][ii] !== '') { squareTitle += ': ' + boardTitle[jj][ii]; }
      text += '<A HREF="javascript:boardOnClick[' + jj + '][' + ii + ']()" ' +
              'ID="' + linkId + '" ' + 
              'TITLE="' + squareTitle + '" ' +
              'STYLE="text-decoration: none; outline: none;" ' +
              'ONFOCUS="this.blur()">' + 
              '<IMG CLASS="pieceImage" ID="' + imageId + '" ' + 
              ' SRC="'+ImagePath+'clear.'+imageType+'" BORDER=0></A></TD>';
    }
    text += '</TR>';
  }
  text += '</TABLE>';

  // Show the HTML for the chessboard

  theObject = document.getElementById("GameBoard");
  if (theObject !== null) { theObject.innerHTML = text; }

  theObject = document.getElementById("boardTable"); 
  if (theObject !== null) {
    tableSize = theObject.offsetWidth;
    if (tableSize > 0) { // check to cope with some browser returning always 0 to offsetWidth
      theObject.style.height = tableSize;
    }
  }

  numberOfButtons = 5;
  spaceSize = 3;
  buttonSize = (tableSize - spaceSize*(numberOfButtons - 1)) / numberOfButtons;
  text =  '<FORM NAME="GameButtonsForm" STYLE="display:inline;">' +
          '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0>' + 
          '<TR>' +
          '<TD>' +
          '<INPUT ID="startButton" TYPE="BUTTON" VALUE="&lt;&lt;" STYLE="';
  if ((buttonSize != undefined) && (buttonSize > 0)) { text += 'width: ' + buttonSize + ';'; }
  text += '"; CLASS="buttonControl" TITLE="go to game start" ' +
          ' ID="btnGoToStart" onClick="javascript:GoToMove(StartPly)" ONFOCUS="this.blur()">' +
          '</TD>' +
          '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
          '</TD>' +
          '<TD>' +
          '<INPUT ID="backButton" TYPE="BUTTON" VALUE="&lt;" STYLE="';
  if ((buttonSize != undefined) && (buttonSize > 0)) { text += 'width: ' + buttonSize + ';'; }
  text += '"; CLASS="buttonControl" TITLE="move backward" ' +
          ' ID="btnMoveBackward1" onClick="javascript:MoveBackward(1)" ONFOCUS="this.blur()">' +
          '</TD>' +
          '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
          '</TD>' +
          '<TD>';
  text += '<INPUT ID="autoplayButton" TYPE="BUTTON" VALUE=';
  text += isAutoPlayOn ? "=" : "+";
  text += ' STYLE="';
  if ((buttonSize != undefined) && (buttonSize > 0)) { text += 'width: ' + buttonSize + ';'; }
  text += isAutoPlayOn ?
          '"; CLASS="buttonControlStop" TITLE="toggle autoplay (stop)" ' :
          '"; CLASS="buttonControlPlay" TITLE="toggle autoplay (start)" ';
  text += ' ID="btnPlay" NAME="AutoPlay" onClick="javascript:SwitchAutoPlay()" ONFOCUS="this.blur()">' +
          '</TD>' +
          '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
          '</TD>' +
          '<TD>' +
          '<INPUT ID="forwardButton" TYPE="BUTTON" VALUE="&gt;" STYLE="';
  if ((buttonSize != undefined) && (buttonSize > 0)) { text += 'width: ' + buttonSize + ';'; }
  text += '"; CLASS="buttonControl" TITLE="move forward" ' +
          ' ID="btnMoveForward1" onClick="javascript:MoveForward(1)" ONFOCUS="this.blur()">' +
          '</TD>' +
          '<TD CLASS="buttonControlSpace" WIDTH="' + spaceSize + '">' +
          '</TD>' +
          '<TD>' +
          '<INPUT ID="endButton" TYPE="BUTTON" VALUE="&gt;&gt;" STYLE="';
  if ((buttonSize != undefined) && (buttonSize > 0)) { text += 'width: ' + buttonSize + ';'; }
  text += '"; CLASS="buttonControl" TITLE="go to game end" ' +
          ' ID="btnGoToEnd" onClick="javascript:GoToMove(StartPly + PlyNumber)" ONFOCUS="this.blur()">' +
          '</TD>' +
          '</TR>' + 
          '</TABLE>' +
          '</FORM>';

  // Show the HTML for the control buttons

  theObject = document.getElementById("GameButtons");
  if (theObject !== null) { theObject.innerHTML = text; }
  
  // Show the HTML for the Game Selector

  if (firstStart) { textSelectOptions=''; }
  theObject = document.getElementById("GameSelector");

  if (theObject !== null) {
    if (numberOfGames < 2) {
      // theObject.innerHTML = ''; // replaced with code below to cope with IE bug
      while (theObject.firstChild) { theObject.removeChild(theObject.firstChild); }
      textSelectOptions = '';
    } else {
      if(textSelectOptions === '') {
        if (gameSelectorNum) { gameSelectorNumLenght = Math.floor(Math.log(numberOfGames)/Math.log(10)) + 1; }
        text = '<FORM NAME="GameSel" STYLE="display:inline;"> ' +
               '<SELECT ID="GameSelSelect" NAME="GameSelSelect" STYLE="';
        if ((tableSize != undefined) && (tableSize > 0)) { text += 'width: ' + tableSize + '; '; }
        text += 'font-family: monospace;" CLASS="selectControl" TITLE="Select a game" ' +
                'ONCHANGE="this.blur(); if(this.value >= 0) {currentGame=parseInt(this.value); ' +
                'document.GameSel.GameSelSelect.value = -1; Init();}" ' +
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
            howManyBlanks = gameSelectorNumLenght - (numText.length - 1);
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += numText + ' ';
          }
          if (gameSelectorChEvent > 0) {
            textSO += ' ' + gameEvent[ii].substring(0, gameSelectorChEvent);
            howManyBlanks = gameSelectorChEvent - gameEvent[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChSite > 0) {
            textSO += ' ' + gameSite[ii].substring(0, gameSelectorChSite);
            howManyBlanks = gameSelectorChSite - gameSite[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChRound > 0) {
            textSO += ' ' + gameRound[ii].substring(0, gameSelectorChRound);
            howManyBlanks = gameSelectorChRound - gameRound[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChWhite > 0) {
            textSO += ' ' + gameWhite[ii].substring(0, gameSelectorChWhite);
            howManyBlanks = gameSelectorChWhite - gameWhite[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChBlack > 0) {
            textSO += ' ' + gameBlack[ii].substring(0, gameSelectorChBlack);
            howManyBlanks = gameSelectorChBlack - gameBlack[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChResult > 0) {
            textSO += ' ' + gameResult[ii].substring(0, gameSelectorChResult);
            howManyBlanks = gameSelectorChResult - gameResult[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          if (gameSelectorChDate > 0) {
            textSO += ' ' + gameDate[ii].substring(0, gameSelectorChDate);
            howManyBlanks = gameSelectorChDate - gameDate[ii].length;
            if (howManyBlanks > 0) { textSO += blanks.substring(0, howManyBlanks); }
            textSO += ' ';
          }
          // replace spaces with &nbsp; 
          textSelectOptions += textSO.replace(/ /g, '&nbsp;');
        }
      text += textSelectOptions + '</SELECT></FORM>';
      theObject.innerHTML = text; 
      }
    }
  }

  // Show the HTML for the Game Event

  theObject = document.getElementById("GameEvent");
  if (theObject !== null) {
    theObject.innerHTML = gameEvent[currentGame];
    // theObject.style.whiteSpace = "nowrap";
  }

  // Show the HTML for the Game Round

  theObject = document.getElementById("GameRound");
  if (theObject !== null) {
    theObject.innerHTML = gameRound[currentGame]; 
    theObject.style.whiteSpace = "nowrap";
  } 

  // Show the HTML for the Game Site

  theObject = document.getElementById("GameSite");
  if (theObject !== null) {
    theObject.innerHTML = gameSite[currentGame]; 
    // theObject.style.whiteSpace = "nowrap";
  } 

  // Show the HTML for the Game Date

  theObject = document.getElementById("GameDate");
  if (theObject !== null) {
    theObject.innerHTML = gameDate[currentGame]; 
    theObject.style.whiteSpace = "nowrap";
  } 

  // Show the HTML for the Game White Player

  theObject = document.getElementById("GameWhite");
  if (theObject !== null) {
    theObject.innerHTML = gameWhite[currentGame]; 
    // theObject.style.whiteSpace = "nowrap";
  } 

  // Show the HTML for the Game Black Player

  theObject = document.getElementById("GameBlack");
  if (theObject !== null) {
    theObject.innerHTML = gameBlack[currentGame]; 
    // theObject.style.whiteSpace = "nowrap";
  } 

  // Show the HTML for the Game Result

  theObject = document.getElementById("GameResult");
  if (theObject !== null) {
    theObject.innerHTML = gameResult[currentGame]; 
    theObject.style.whiteSpace = "nowrap";
  } 
  
  text = '<SPAN ID="ShowPgnText">';
  for (ii = StartPly; ii < StartPly+PlyNumber; ++ii){
    printedComment = false;
    // remove PGN extension tags
    thisComment = MoveComments[ii].replace(/\[%.*?\]\s*/g,''); // note trailing spaces are removed also
    // remove comments that are all spaces
    if (thisComment.match(/^\s*$/)) { thisComment = ''; }
    if (commentsIntoMoveText && (thisComment !== '')){
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
    }else{
      if ((printedComment) || (ii == StartPly)) { text += '<SPAN CLASS="move">' + moveCount + '...&nbsp;</SPAN>'; }
    }
    jj = ii+1;
    text += '<A HREF="javascript:GoToMove(' + jj + ')" CLASS="move" ID="Mv' + jj + 
            '" ONFOCUS="this.blur()">' + Moves[ii] + '</A></SPAN>' +
            '<SPAN CLASS="move"> </SPAN>';
  }
  // remove PGN extension tags
  thisComment = MoveComments[StartPly+PlyNumber].replace(/\[%.*?\]\s*/g,''); // note trailing spaces are removed also
  // remove comments that are all spaces
  if (thisComment.match(/^\s*$/)) { thisComment = ''; }
  if (commentsIntoMoveText && (thisComment !== '')){
    if (commentsOnSeparateLines) { text += '<DIV CLASS="comment" STYLE="line-height: 33%;">&nbsp;</DIV>'; }
    text += '<SPAN CLASS="comment">' + thisComment + '</SPAN><SPAN CLASS="move"> </SPAN>';
  }
  text += '</SPAN>';

  // Show the HTML for the Game Text

  theObject = document.getElementById("GameText");
  if (theObject !== null) { theObject.innerHTML = text; }

  // Show the HTML for the Game Search box

  theObject = document.getElementById("GameSearch");
  if ((firstStart) && (theObject !== null)) {
    if (numberOfGames < 2) {
      // theObject.innerHTML = ''; // replaced with code below to cope with IE bug
      while (theObject.firstChild) { theObject.removeChild(theObject.firstChild); }
    } else {
      text = '<FORM ID="searchPgnForm" STYLE="display: inline;" ' +
             'ACTION="javascript:searchPgnGame(document.getElementById(\'searchPgnExpression\').value);">';
      text += '<INPUT ID="searchPgnButton" CLASS="searchPgnButton" STYLE="display: inline; ';
      if ((tableSize != undefined) && (tableSize > 0)) { text += 'width: ' + tableSize/4 + '; '; }
      text += '" TITLE="find games matching the search string (or regular expression)" ';
      text += 'TYPE="submit" VALUE="?">';
      text += '<INPUT ID="searchPgnExpression" CLASS="searchPgnExpression" ' +
              'TITLE="find games matching the search string (or regular expression)" ' + 
              'TYPE="input" VALUE="' + lastSearchPgnExpression + '" STYLE="display: inline; ';
      if ((tableSize != undefined) && (tableSize > 0)) { text += 'width: ' + 3*tableSize/4 + '; '; }
      text += '" ONFOCUS="disableShortcutKeysAndStoreStatus();" ONBLUR="restoreShortcutKeysStatus();">'; 
      text += '</FORM>';
      theObject.innerHTML = text;
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
  // Check if we need a new set of pieces.
  InitImages();

  // Display all empty squares.
  var col, row, square;
  for (col = 0; col < 8;++col) {
    for (row = 0; row < 8; ++row) {
      if (Board[col][row] === 0) {
        square = IsRotated ? 63-col-(7-row)*8 : col+(7-row)*8;
	SetImage(square, ClearImg.src);
      }
    }
  }

  // Display all pieces.
  var color, ii;
  for (color = 0; color < 2; ++color){
    for (ii = 0; ii < 16; ++ii){
      if (PieceType[color][ii] > 0){
        square = IsRotated ? 
                 63-PieceCol[color][ii] - (7-PieceRow[color][ii])*8 :
                 PieceCol[color][ii] + (7-PieceRow[color][ii])*8;
        SetImage(square, PiecePicture[color][PieceType[color][ii]].src);
      }
    }
  }
}


function SetAutoPlay(vv) {
  isAutoPlayOn = vv;
  // No matter what clear the timeout.
  if (AutoPlayInterval) { clearTimeout(AutoPlayInterval); AutoPlayInterval = null; }
  // If switched on start  moving forward. Also change the button value.
  if (isAutoPlayOn){
    if (document.GameButtonsForm) {
      if (document.GameButtonsForm.AutoPlay){
        document.GameButtonsForm.AutoPlay.value="=";
        document.GameButtonsForm.AutoPlay.title="toggle autoplay (stop)";
        document.GameButtonsForm.AutoPlay.className="buttonControlStop";
      }
    }
    if (CurrentPly < StartPly+PlyNumber) { AutoPlayInterval=setTimeout("MoveForward(1)", Delay); }
    else { if (autoplayNextGame) { AutoPlayInterval=setTimeout("AutoplayNextGame()", Delay); }
           else { SetAutoPlay(false); }
    }
  } else { 
    if (document.GameButtonsForm) {
      if (document.GameButtonsForm.AutoPlay) {
        document.GameButtonsForm.AutoPlay.value="+";
        document.GameButtonsForm.AutoPlay.title="toggle autoplay (start)";
        document.GameButtonsForm.AutoPlay.className="buttonControlPlay";
      }
    }
  }
}


function SetAutoplayDelay(vv) {
  Delay = vv;
}


function SetLiveBroadcast(delay, alertFlag, demoFlag) {
  LiveBroadcastDelay = delay; // delay = 0 means no live broadcast
  LiveBroadcastAlert = (alertFlag === true); // whether to display myAlerts during live broadcast
  LiveBroadcastDemo = (demoFlag === true);
}


function SetImage(square, image) {
  if (DocumentImages[square] == image) { return; }
  document.images[square+ImageOffset].src = image;
  DocumentImages[square]                  = image;   // Store the new image.
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

  // Stores "square from" history information
  HistPieceId[0][thisPly] = mvPieceId;
  HistCol[0][thisPly]     = PieceCol[MoveColor][mvPieceId];
  HistRow[0][thisPly]     = PieceRow[MoveColor][mvPieceId];
  HistType[0][thisPly]    = PieceType[MoveColor][mvPieceId];

  // Stores "square to" history information
  HistCol[2][thisPly] = mvToCol;
  HistRow[2][thisPly] = mvToRow;

  if (mvIsCastling) {
     HistPieceId[1][thisPly] = castleRook;
     HistCol[1][thisPly]     = PieceCol[MoveColor][castleRook];
     HistRow[1][thisPly]     = PieceRow[MoveColor][castleRook];
     HistType[1][thisPly]    = PieceType[MoveColor][castleRook];
  } else if (mvCapturedId >= 0) {
     HistPieceId[1][thisPly] = mvCapturedId+16;
     HistCol[1][thisPly]     = PieceCol[1-MoveColor][mvCapturedId];
     HistRow[1][thisPly]     = PieceRow[1-MoveColor][mvCapturedId];
     HistType[1][thisPly]    = PieceType[1-MoveColor][mvCapturedId];
  } else {
    HistPieceId[1][thisPly] = -1;
  }

  // Update the from square and the captured square. Remember that the
  // captured square is not necessarely the to square because of the en-passant.
  Board[PieceCol[MoveColor][mvPieceId]][PieceRow[MoveColor][mvPieceId]] = 0;

  // Mark the captured piece as such.
  if (mvCapturedId >=0) {
     PieceType[1-MoveColor][mvCapturedId] = -1;
     PieceMoveCounter[1-MoveColor][mvCapturedId]++;
     Board[PieceCol[1-MoveColor][mvCapturedId]][PieceRow[1-MoveColor][mvCapturedId]] = 0;
  }

  // Update the piece arrays. Don't forget to update the type array, since a
  // pawn might have been replaced by a piece in a promotion.
  PieceType[MoveColor][mvPieceId] = mvPieceOnTo;
  PieceMoveCounter[MoveColor][mvPieceId]++;
  PieceCol[MoveColor][mvPieceId]  = mvToCol;
  PieceRow[MoveColor][mvPieceId]  = mvToRow;
  if (mvIsCastling) {
    PieceMoveCounter[MoveColor][castleRook]++;
    PieceCol[MoveColor][castleRook] = mvToCol == 2 ? 3 : 5;
    PieceRow[MoveColor][castleRook] = mvToRow;
  }

  // Update the board.
  Board[mvToCol][mvToRow] = PieceType[MoveColor][mvPieceId]*(1-2*MoveColor);
  if (mvIsCastling){
    Board[PieceCol[MoveColor][castleRook]][PieceRow[MoveColor][castleRook]] =
      PieceType[MoveColor][castleRook]*(1-2*MoveColor);
  }
  return;
}

function UndoMove(thisPly) {

  // Bring the moved piece back.
  Board[mvToCol][mvToRow] = 0;
  Board[HistCol[0][thisPly]][HistRow[0][thisPly]] =
    HistType[0][thisPly]*(1-2*MoveColor);

  PieceCol[MoveColor][mvPieceId]  = HistCol[0][thisPly];
  PieceRow[MoveColor][mvPieceId]  = HistRow[0][thisPly];
  PieceType[MoveColor][mvPieceId] = HistType[0][thisPly];
  PieceMoveCounter[MoveColor][mvPieceId]--;

  // If capture or castle bring the captured piece or the rook back.
  if (mvCapturedId >=0) {
     PieceType[1-MoveColor][mvCapturedId] = mvCapturedId;
     PieceCol[1-MoveColor][mvCapturedId]  = HistCol[1][thisPly];
     PieceRow[1-MoveColor][mvCapturedId]  = HistRow[1][thisPly];
     PieceCol[1-MoveColor][mvCapturedId]  = HistCol[1][thisPly];
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
  if ((col < 0) || (col > 7)) { return false; }
  if ((row < 0) || (row > 7)) { return false; }
  return true;
}

