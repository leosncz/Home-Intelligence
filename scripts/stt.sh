./convert.sh > /dev/null 2>&1 
PYTHONIOENCODING=utf-8 python3 stt.py test.wav > stt_output 2>&1 
tail stt_output | grep '"text" : ' | cut -c13- > stt_output2 2>&1
sed '$ s/.$//' stt_output2 

rm -f stt_output2
rm -f stt_output
rm -f test.wav
