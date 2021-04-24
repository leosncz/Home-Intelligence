#!/usr/bin/python3

from vosk import Model, KaldiRecognizer
import os

if not os.path.exists("../../model-fr"):
    print ("Please download the model from https://github.com/alphacep/kaldi-android-demo/releases and unpack as 'model-en' in the current folder.")
    exit (1)

import pyaudio

p = pyaudio.PyAudio()
stream = p.open(format=pyaudio.paInt16, channels=1, rate=16000, input=True, frames_per_buffer=8000)
stream.start_stream()

model = Model("../../model-fr")
rec = KaldiRecognizer(model, 16000)
can = 0

while True:
    data = stream.read(2000,exception_on_overflow = False)
    if len(data) == 0:
        break
    if rec.AcceptWaveform(data):
        test = rec.Result()
        if '"result"' in test:
         can = 0
    else:
        print(rec.PartialResult())
        can = 1
print(rec.FinalResult())
