#!/bin/bash

#  pgn4web javascript chessboard
#  copyright (C) 2009, 2010 Paolo Casaschi
#  see README file and http://pgn4web.casaschi.net
#  for credits, license and more details

if [ "$1" == "--help" ]
then
	echo
	echo "$(basename $0)"
	echo 
	echo "Shell script to check status of live-grab.sh processes"
	echo
	echo "Note: note it assumes that live-grab.sh is always starter from its own"
	echo "directory so that the logFile path (if any) is relative to that directory"
	echo
	echo "Needs to be run using bash and requires awk"
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

if [ -z "$(which awk)" ]
then
	echo "ERROR: missing awk"
fi

pgn4web_scan=$(ps -U $USER -w -o pid,command | awk 'BEGIN {c=0} $3=="live-grab.sh" {printf("pgn4web_pid[%d]=\"%s\";pgn4web_log[%d]=\"%s\".log;",c,$1,c,$5); c++}')

eval $pgn4web_scan

length=${#pgn4web_pid[@]}
if [ $length -gt 0 ]
then
	echo pgn4web live-grab.sh processes: $length 
fi

pgn4web_dir=$(dirname $0)

for ((i=0; i<length; i++))
do
	if [ "${pgn4web_log[i]}" == ".log" ]
	then
		pgn4web_log[i]="live.pgn.log"
	fi

	if [ -n "$pgn4web_dir" ]
	then
		if [[ ${pgn4web_log[i]} != /* ]]
		then
			pgn4web_log[$i]=$pgn4web_dir"/"${pgn4web_log[i]}
		fi
	fi

	if [ -f "${pgn4web_log[$i]}" ]
	then
		pgn4web_steps[i]=$(cat ${pgn4web_log[$i]} | awk 'END { printf("%4d of %4d", $8, $10) }')
	else
		pgn4web_steps[i]="unavaiable  "
	fi
	echo "  pid: ${pgn4web_pid[$i]}  steps: ${pgn4web_steps[$i]}  log: ${pgn4web_log[$i]}"
done

