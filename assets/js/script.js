var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex;
var repeat = false
var shuffle = false
var userLoggedIn
var timer;

function openPage(url){
    if(timer!=null){
        clearTimeout(timer)
    }

    if(url.indexOf("?")=== -1){
        url = url + "?"
    }
    var encodedUrl = encodeURI(url+"&userLoggedIn="+userLoggedIn)
    $("#mainContent").load(encodedUrl)
    $("body").scrollTop(0)
    history.pushState(null, null, url)
}

function createPlaylist(){
    console.log(userLoggedIn)
    var popup = prompt("Please enter the name of your playlist")

    if(popup!=null){
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done(function(error){
            if(error!=""){
                alert(error)
                return
            }
            openPage('yourMusic.php')
        })
    }
}

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

function playFirstSong(){
    setTrack(tempPlaylist[0], tempPlaylist, true)
}

class Audio {
    constructor() {
        this.CurrentPlaying;
        this.audio = document.createElement('audio');

        this.audio.addEventListener("ended", function(){
            nextSong()
        })
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