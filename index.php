<?php
include 'top.php';
?>

<center>
<div class="card" style="width: 70%;">
  <h5 class="card-header">A votre service</h5>
  <div class="card-body">

    <h5 class="card-title">Interaction vocale (nécessite microphone)</h5>
    <p class="card-text">Quelque chose à me dire ?</p>
    <button id="recordButton" class="btn btn-primary">Je vous écoute</button>
    <button class="btn btn-primary" id="pauseButton" disabled>Pause</button>
    <button class="btn btn-primary" id="stopButton" disabled>Stop</button>
</br></br>
    <p class="font-weight-light" id="reply"></p>
</br>
    <h5 class="card-title">Interaction textuelle</h5>
    <p class="card-text">Vous préférez me l'écrire ?</p>
    <input class="form-control" type="text" id="thetext"  placeholder="Dites-moi tout"></br>
    <button id="sendButton" class="btn btn-primary">Je vous lis</button>
</br></br>
    <p class="font-weight-light" id="reply2"></p>
  </div>
</div>
</center>

<script>
URL = window.URL || window.webkitURL;
var gumStream;
//stream from getUserMedia() 
var rec;
//Recorder.js object 
var input;
//MediaStreamAudioSourceNode we'll be recording 
// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext = new AudioContext;
//new audio context to help us record 
var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");
var sendButton = document.getElementById("sendButton");
//add events to those 3 buttons 
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);
sendButton.addEventListener("click", textSend);

function textSend()
{
    var url = "api/interaction.php?text=" + encodeURI(document.getElementById("thetext").value.toLowerCase());
    var xhr = new XMLHttpRequest();
    xhr.onload = function(e) {
        if (this.readyState === 4) {
            document.getElementById("reply2").innerHTML = e.target.responseText;
        }
    };
    //var fd = new FormData();
    //fd.append("audio_data", blob, filename);
    xhr.open("GET", url, true);
    xhr.send(null);

}

function startRecording() { 
console.log("recordButton clicked"); 
var constraints = {
    audio: true,
    video: false
} 
/* Disable the record button until we get a success or fail from getUserMedia() */

recordButton.disabled = true;
stopButton.disabled = false;
pauseButton.disabled = false

/* We're using the standard promise based getUserMedia()

https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia */

navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
    console.log("getUserMedia() success, stream created, initializing Recorder.js ..."); 
    /* assign to gumStream for later use */
    gumStream = stream;
    /* use the stream */
    input = audioContext.createMediaStreamSource(stream);
    /* Create the Recorder object and configure to record mono sound (1 channel) Recording 2 channels will double the file size */
    rec = new Recorder(input, {
        numChannels: 1
    }) 
    //start the recording process 
    rec.record()
    console.log("Recording started");
}).catch(function(err) {
    //enable the record button if getUserMedia() fails 
    recordButton.disabled = false;
    stopButton.disabled = true;
    pauseButton.disabled = true
});
}


function pauseRecording() {
    console.log("pauseButton clicked rec.recording=", rec.recording);
    if (rec.recording) {
        //pause 
        rec.stop();
        pauseButton.innerHTML = "Resume";
    } else {
        //resume 
        rec.record()
        pauseButton.innerHTML = "Pause";
    }
}
function stopRecording() {
    console.log("stopButton clicked");
    //disable the stop button, enable the record too allow for new recordings 
    stopButton.disabled = true;
    recordButton.disabled = false;
    pauseButton.disabled = true;
    //reset button just in case the recording is stopped while paused 
    pauseButton.innerHTML = "Pause";
    //tell the recorder to stop the recording 
    rec.stop(); //stop microphone access 
    gumStream.getAudioTracks()[0].stop();
    //create the wav blob and pass it on to createDownloadLink 
    rec.exportWAV(createDownloadLink);
}


function createDownloadLink(blob) {
    var url = URL.createObjectURL(blob);
    var au = document.createElement('audio');
    var li = document.createElement('li');
    var link = document.createElement('a');
    //add controls to the <audio> element 
    au.controls = true;
    au.src = url;
    //link the a element to the blob 
    link.href = url;
    link.download = new Date().toISOString() + '.wav';
    link.innerHTML = link.download;
    //add the new audio and a elements to the li element 
    li.appendChild(au);
    li.appendChild(link);
   
var filename = new Date().toISOString();
    var xhr = new XMLHttpRequest();
    xhr.onload = function(e) {
        if (this.readyState === 4) {
            document.getElementById("reply").innerHTML = e.target.responseText;
        }
    };
    var fd = new FormData();
    fd.append("audio_data", blob, filename);
    xhr.open("POST", "api/interaction.php", true);
    xhr.send(fd);

}

</script>


  </body>
</html>
