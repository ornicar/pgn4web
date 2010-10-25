#!/bin/bash

#  pgn4web javascript chessboard
#  copyright (C) 2009, 2010 Paolo Casaschi
#  see README file and http://pgn4web.casaschi.net
#  for credits, license and more details

localPgnFile_default=live.pgn
refreshSeconds_default=49
timeoutHours_default=12

if [ -z "$1" ] || [ "$1" == "--help" ]
then
  echo
  echo "$(basename $0) remotePgnUrl localPgnFile refreshSeconds timeoutHours"
  echo 
  echo "Shell script periodically fetching a PGN file for a pgn4web live broadcast."
  echo
  echo "Parameters:"
  echo "  remotePgnUrl: URL to fetch"
  echo "  localPgnFile: local PGN filename (default: $localPgnFile_default)"
  echo "  refreshSeconds: refresh rate in seconds (default: $refreshSeconds_default)"
  echo "  timeoutHours: timeout in hours for stopping the process (default: $timeoutHours_default)"
  echo
  echo "Needs to be run using bash and requires curl"
  echo "Logs to 'localPgnFile'.log"
  echo
  exit
fi

if [ "$1" == "--no-shell-check" ]
then
	shift 1
else
	if [ "$(basename $SHELL)" != "bash" ]
	then
		echo "ERROR: $(basename $0) should be run with bash. Prepend --no-shell-check as first parameters to skip checking the shell type."
		exit
	fi
fi

print_log() {
	if [ -n "$1" ]
	then
		log="$(date '+%b %d %T') $(hostname) $(basename $0) [$$]: $1"
	else
		log=""
	fi
	if [ -n "$logFile" ]
	then
		echo $log >> $logFile
	else
		echo $log
	fi
}

first_print_error="notYet";
print_error() {
        if [ -n "$logFile" ]
        then
		echo $(date) $(basename $0) ERROR: $1 >> $logFile
	fi
        if [ -n "$first_print_error" ]
	then
		first_print_error=
	        echo > /dev/stderr
	fi
	echo $(basename $0) ERROR: $1 > /dev/stderr
}

umask 0000
if [ $? -ne 0]
then
	print_error "failed setting umask 0000"
        exit
fi 

if [ -z "$1" ]
then 
	exit
else
	remotePgnUrl=$1
fi

if [ -z "$2" ]
then
	localPgnFile=$localPgnFile_default
else
	localPgnFile=$2
fi
if [ -e "$localPgnFile" ] || [ -h "$localPgnFile" ]
then
	print_error "localPgnFile $localPgnFile exists"
	print_error "delete the file or choose another filename and restart"
	exit
fi
if [ $(echo "$localPgnFile" | grep "\*") ] 
then
	print_error "localPgnFile should not contain \"*\"" 
	exit
fi
if [ $(echo "$localPgnFile" | grep "\?") ] 
then
	print_error "localPgnFile should not contain \"?\""
	exit
fi
if [ $(echo "$localPgnFile" | grep "\[") ] 
then
	print_error "localPgnFile should not contain \"[\""
	exit
fi
if [ $(echo "$localPgnFile" | grep "\]") ] 
then
	print_error "localPgnFile should not contain \"]\""
	exit
fi
tmpLocalPgnFile=$localPgnFile.tmp

logFile=$localPgnFile.log
if [ -e "$logFile" ] || [ -h "$logFile" ]
then
	print_error  "logFile $logFile exists"
	print_error "delete the file or choose another localPgnFile name and restart"
	exit
fi
print_log "start"

if [ -z "$3" ]
then
	refreshSeconds=$refreshSeconds_default
else
	refreshSeconds=$3
fi

if [ -z "$4" ]
then
	timeoutHours=$timeoutHours_default
else
	timeoutHours=$4
fi
timeoutSteps=$((3600*$timeoutHours/$refreshSeconds))

if [ -z "$(which curl)" ]
then
	print_error "missing curl"
	exit
else 
	grabCmdLine="curl --silent --remote-time --time-cond $tmpLocalPgnFile --output $tmpLocalPgnFile --url $remotePgnUrl"
fi
	# wget alternative to curl, but --timestamp option is not compatible with --output-document
	# grabCmdLine="wget --quiet --output-document=$tmpLocalPgnFile $remotePgnUrl"

print_log "remoteUrl: $remotePgnUrl"
print_log "localPgnFile: $localPgnFile"
print_log "refreshSeconds: $refreshSeconds"
print_log "timeoutHours: $timeoutHours"

step=0
while [ $step -le $timeoutSteps ] 
do
	$grabCmdLine
	cmp -s "$tmpLocalPgnFile" "$localPgnFile"
	if [ $? -ne 0 ]
	then
		cp "$tmpLocalPgnFile" "$localPgnFile"
		print_log "step $step of $timeoutSteps, new PGN data found"
	else
		print_log "step $step of $timeoutSteps, no new data"
	fi
	step=$(($step +1))
	sleep $refreshSeconds
done

