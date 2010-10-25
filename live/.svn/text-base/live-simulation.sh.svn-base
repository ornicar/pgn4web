#!/bin/bash

#  pgn4web javascript chessboard
#  copyright (C) 2009, 2010 Paolo Casaschi
#  see README file and http://pgn4web.casaschi.net
#  for credits, license and more details

# bash script to create a pgn file over time, same as a live broadcast
# more realistic than simulating the live broadcast within pgn4web

if [ "$1" == "--help" ]
then
	echo
	echo "$(basename $0)"
	echo
	echo "Shell script to create a pgn file over time, same as a live broadcast"
	echo "and more realistic than simulating the live broadcast within pgn4web"
	echo
	echo "Needs to be run using bash"
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

pgn_file=live.pgn
pgn_file_tmp=live-tmp.pgn
delay=17

# dont touch after this line

umask 0000
if [ $? -ne 0 ]
then
        echo "ERROR: $(basename $0) failed setting umask 0000"
        exit
fi 

game1_header="[Event \"Tilburg Fontys\"]\n[Site \"Tilburg\"]\n[Date \"1998.10.24\"]\n[Round \"2\"]\n[White \"Anand, Viswanathan\"]\n[Black \"Kramnik, Vladimir\"]\n[WhiteClock \"2:00:00\"]\n[BlackClock \"2:00:00\"]"
game1_header_live="$game1_header\n[Result \"*\"]\n"
game1_header_end="$game1_header\n[Result \"1-0\"]\n"

game1_moves[0]="1.e4 {1:59:59} e5 {1:58:58}"
game1_moves[1]=" 2.Nf3 {1:57:57} Nf6 {1:56:56} 3.Nxe5 {1:55:55}"
game1_moves[2]="d6 {1:54:54}"
game1_moves[3]="4.Nf3 {1:53:53} Nxe4 {1:52:52}"
game1_moves[4]="5.d4 {1:51:51} d5 {1:50:50} 6.Bd3 {1:49:49}"
game1_moves[5]="Nc6 {1:48:48} 7.O-O {1:47:47}"
game1_moves[6]="Be7 {1:46:46} 8.Re1 {1:45:45}"
game1_moves[7]="Bg4 {1:44:44} 9.c3 {1:43:43} f5 {1:42:42}"
game1_moves[8]=""
game1_moves[9]="10.Qb3 {1:41:41} O-O {1:40:40} 11.Nbd2 {1:39:39}"
game1_moves[11]="Na5 {1:38:38}"
game1_moves[12]="12.Qa4 {1:37:37} Nc6 {1:36:36} 13.Bb5 {1:35:35}"
game1_moves[13]="Nxd2 {1:34:34} 14.Nxd2 {1:33:33} Qd6 {1:32:32}"
game1_moves[14]="15.h3 {1:31:31} Bh5 {1:30:30}"
game1_moves[15]=""
game1_moves[16]="16.Nb3 {1:29:29} Bh4 {1:28:28}"
game1_moves[17]="17.Nc5 {1:27:27}"
game1_moves[18]="Bxf2+ {1:26:26}"
game1_moves[19]="18.Kxf2 {1:25:25} Qh2 {1:24:24} 19.Bxc6 {1:23:23}"
game1_moves[20]="bxc6 {1:22:22} 20.Qxc6 {1:21:21} f4 {1:20:20}"
game1_moves[21]="21.Qxd5+ {1:19:19}"
game1_moves[22]="Kh8 {1:18:18} 22.Qxh5 {1:17:17}"
game1_moves[23]="f3 {1:16:16}"
game1_moves[24]="23.Qxf3 {1:15:15} Rxf3+ {1:14:14}"
game1_moves[25]="24.Kxf3 {1:13:13} Rf8+ {1:12:12} 25.Ke2 {1:11:11}"
game1_moves[26]="Qxg2+ {1:10:10} 26.Kd3 {1:09:09}"
game1_moves[27]="Qxh3+ {1:08:08} 27.Kc2 {1:07:07} Qg2+ {1:06:06}"
game1_moves[28]="28.Bd2 {1:05:05} Qg6+ {1:04:04}"
game1_moves[29]="29.Re4 {1:03:03} h5 {1:02:02} 30.Re1 {1:01:01}"
game1_moves[30]="Re8 {1:00:00} 31.Kc1 {59:59} Rxe4 {58:58}"
game1_moves[31]="Nxe4 {57:57} h4 {56:56} 33.Ng5 {55:55}"
game1_moves[32]="Qh5 {54:54} 34.Re3 {53:53} Kg8 {52:52}"
game1_moves[33]="35.c4 {51:51}"

game2_header="[Event \"Tilburg Fontys\"]\n[Site \"Tilburg\"]\n[Date \"1998.10.24\"]\n[Round \"2\"]\n[White \"Lautier, Joel\"]\n[Black \"Van Wely, Loek\"]\n[WhiteClock \"2:00:00\"]\n[BlackClock \"2:00:00\"]"
game2_header_live="$game2_header\n[Result \"*\"]\n"
game2_header_end="$game2_header\n[Result \"1/2-1/2\"]\n"

game2_moves[0]="1.d4 {[%clk 1:59:59]} Nf6 {[%clk 1:59:58]} 2.c4 {[%clk 1:58:57]}"
game2_moves[1]="c5 {[%clk 1:58:56]} 3.d5 {[%clk 1:57:55]}"
game2_moves[2]="b5 {[%clk 1:57:54]}"
game2_moves[3]="4.Nf3 {[%clk 1:56:53]}"
game2_moves[4]="Bb7 {[%clk 1:56:52]}"
game2_moves[5]="5.a4 {[%clk 1:55:51]}"
game2_moves[6]="Qa5+ {[%clk 1:55:50]}"
game2_moves[7]="6.Bd2 {[%clk 1:54:49]}"
game2_moves[8]="b4 {[%clk 1:54:48]}"
game2_moves[9]="7.Bg5 {[%clk 1:53:47]} d6 {[%clk 1:53:46]}"
game2_moves[10]=""
game2_moves[11]="8.Nbd2 {[%clk 1:52:45]}"
game2_moves[12]="Nbd7 {[%clk 1:52:44]}"
game2_moves[13]="9.h3 {[%clk 1:51:43]} g6 {[%clk 1:51:42]}"
game2_moves[14]="10.e4 {[%clk 1:50:41]} Bg7 {[%clk 1:50:40]} 11.Bd3 {[%clk 1:49:39]}"
game2_moves[15]="O-O {[%clk 1:49:38]} 12.O-O {[%clk 1:48:37]}"
game2_moves[16]="Rae8 {[%clk 1:48:36]}"
game2_moves[17]=""
game2_moves[18]="13.Re1 {[%clk 1:47:35]} e5 {[%clk 1:47:34]}"
game2_moves[19]="14.Nf1 {[%clk 1:46:33]}"
game2_moves[20]="Nh5 {[%clk 1:46:32]} 15.g3 {[%clk 1:45:31]}"
game2_moves[21]="Bc8 {[%clk 1:45:30]}"
game2_moves[22]=""
game2_moves[23]="16.Kh2 {[%clk 1:44:29]} Kh8 {[%clk 1:44:28]}"
game2_moves[24]="17.b3 {[%clk 1:43:27]}"
game2_moves[25]="Qc7 {[%clk 1:43:26]}"
game2_moves[26]=""
game2_moves[27]="18.Ra2 {[%clk 1:42:25]}"
game2_moves[28]="Ndf6 {[%clk 1:42:24]}"
game2_moves[29]="19.Ng1 {[%clk 1:41:23]}"
game2_moves[30]=""
game2_moves[31]="Ng8 {[%clk 1:41:22]}"
game2_moves[32]=""
game2_moves[33]="20.Bc1 {[%clk 1:40:21]}"

steps=33

if [ -e "$pgn_file" ]
then
	echo "ERROR: $(basename $0): $pgn_file exists"
        echo "Delete the file or choose another filename and restart $(basename $0)"
        exit
fi

echo Generating PGN file $pgn_file simulating live game broadcast

echo > $pgn_file_tmp
echo -e $game1_header_live >> $pgn_file_tmp
echo >> $pgn_file_tmp
echo -e $game2_header_live >> $pgn_file_tmp
mv $pgn_file_tmp $pgn_file
sleep $delay

upto=0;
while [ $upto -le $steps ]
do
	echo " step $upto of $steps"
	echo > $pgn_file_tmp

	echo -e $game1_header_live >> $pgn_file_tmp
	move=0
	while [ $move -le $upto ]
	do
		echo ${game1_moves[$move]} >> $pgn_file_tmp
		move=$(($move + 1))
	done

	echo >> $pgn_file_tmp

	echo -e $game2_header_live >> $pgn_file_tmp
	move=0
	while [ $move -le $upto ]
	do
		echo ${game2_moves[$move]} >> $pgn_file_tmp
		move=$(($move + 1))
	done

	mv $pgn_file_tmp $pgn_file
	sleep $delay

	upto=$(($upto + 1))
done

echo > $pgn_file_tmp
echo -e $game1_header_end >> $pgn_file_tmp
move=0
while [ $move -le $upto ]
do
	echo ${game1_moves[$move]} >> $pgn_file_tmp
	move=$(($move + 1))
done
echo >> $pgn_file_tmp
echo -e $game2_header_end >> $pgn_file_tmp
move=0
while [ $move -le $upto ]
do
	echo ${game2_moves[$move]} >> $pgn_file_tmp
	move=$(($move + 1))
done
mv $pgn_file_tmp $pgn_file
echo done with games... waiting for a while before deleting $pgn_file

sleep 3600
rm $pgn_file


