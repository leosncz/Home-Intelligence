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
    data = stream.read(2000, exception_on_overflow = False)
    if len(data) == 0:
        break
    if rec.AcceptWaveform(data):
        test = rec.Result()
        if '"result"' in test:
         can = 0
    else:
        test = rec.PartialResult()
        if "ordinateur" in test and can == 0:
         os.system("cd /var/www/html/scripts/permanent && play beep_hw.wav && cd .. && rec test_tmp.wav trim 0 3 && cd /var/www/html/scripts/permanent && play beep_hw.wav && cd .. && ./convert.sh && ./stt.sh > permanent_result && cd permanent/permanent_v1/ && ./send.sh")
         can = 1
print(rec.FinalResult())
