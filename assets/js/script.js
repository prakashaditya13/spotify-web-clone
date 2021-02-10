var currentPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex;
var repeat = false

function formatTime(seconds){
    var time = Math.round(seconds)
    var minutes = Math.floor(time/60)
    var seconds = time-(minutes*60)
    var extraZero = (seconds<10) ? "0" : ""
    return minutes+":"+extraZero+seconds
}

function updateProgressBar(audio){
    $(".progressTime.currentTime").text(formatTime(audio.currentTime))
    $(".progressTime.remainingTime").text(formatTime(audio.duration - audio.currentTime))
    var progress = audio.currentTime/audio.duration*100
    $(".playbackBar .progress").css("width", progress + "%")
}

function updateVolumeBar(audio){
    var volume = audio.volume*100
    $(".volumeBar .progress").css("width", volume + "%")
}

class Audio {
    constructor() {
        this.CurrentPlaying;
        this.audio = document.createElement('audio');
        this.audio.addEventListener("canplay", function(){
            $(".progressTime.remainingTime").text(formatTime(this.duration))
        })
        this.audio.addEventListener("timeupdate", function(){
            if(this.duration){
                updateProgressBar(this)
            }
        })
        this.audio.addEventListener("volumechange", function(){
            updateVolumeBar(this)
        })
    }
    setTrack = function (track) {
        this.CurrentPlaying = track
        this.audio.src = track.path;
    };
    play = function () {
        this.audio.play();
    };
    pause = function () {
        this.audio.pause();
    };
    setTime = function(seconds){
        this.audio.currentTime = seconds
    }
}