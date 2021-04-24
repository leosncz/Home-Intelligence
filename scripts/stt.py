#!/usr/bin/python3

from vosk import Model, KaldiRecognizer
import sys
import os
import wave

if not os.path.exists("model-fr"):
    print ("Please download the model from https://github.com/alphacep/kaldi-android-demo/releases and unpack as 'model-en' in the current folder.")
    exit (1)

wf = wave.open(sys.argv[1], "rb")
if wf.getnchannels() != 1 or wf.getsampwidth() != 2 or wf.getcomptype() != "NONE":
    print ("Audio file must be WAV format mono PCM.")
    exit (1)

model = Model("model-fr")
rec = KaldiRecognizer(model, wf.getframerate())

while True:
    data = wf.readframes(1000)
    if len(data) == 0:
        break
    if rec.AcceptWaveform(data):
        print("")
    else:
        print("")

text = rec.FinalResult()
assert isinstance(text, str) # or str on Python 3
print(text)

