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
  echo "Needs to be run using bash and requires either curl or wget"
  echo "Logs to 'localPgnFile'.log"
  echo
  exit
fi

if [ "$(basename $SHELL)" != "bash" ]
then
	echo "ERROR: $(basename $0) should be run with bash"
	exit
fi

print_log() {
	if [ -n "$1" ]
	then
		log="$(date) $(basename $0) ($$) LOG: $1"
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
tmpLocalPgnFile=$localPgnFile.$RANDOM.pgn

logFile=$localPgnFile.log
if [ -e "$logFile" ] || [ -h "$logFile" ]
then
	print_error  "logFile $logFile exists"
	print_error "delete the file or choose another localPgnFile name and restart"
	exit
fi
print_log
print_log "pgn4web $(basename $0) logfile"
print_log

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
	if [ -z "$(which wget)" ]
	then
		print_error "missing both curl and wget"
		exit
	else
		grabCmdLine="wget -qrO $tmpLocalPgnFile $remotePgnUrl"
	fi
else 
	grabCmdLine="curl -so $tmpLocalPgnFile --url $remotePgnUrl"
fi

print_log "remoteUrl: $remotePgnUrl"
print_log "localPgnFile: $localPgnFile"
print_log "refreshSeconds: $refreshSeconds"
print_log "timeoutHours: $timeoutHours"
print_log 

step=0
while [ $step -le $timeoutSteps ] 
do
	print_log "step $step of $timeoutSteps"
	$grabCmdLine 
	if [ -e "$tmpLocalPgnFile" ]
	then
		mv "$tmpLocalPgnFile" "$localPgnFile"
	fi
	step=$(($step +1))
	sleep $refreshSeconds
done

print_log "done"

