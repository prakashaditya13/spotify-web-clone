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


$(document).click(function(click){
    var target = $(click.target)
    if(!target.hasClass("item") && !target.hasClass("optionButton")){
        hideOptionMenu()
    }
})
$(window).scroll(function(){
    hideOptionMenu()
})

$(document).on("change", "select.playlist", function(){
    var playlistId = $(this).val()
    var songId = $(this).prev(".songId").val()
})

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

function deletePlaylist(playlistId){
    var promt = confirm("Are you sure to delete this playlist?");
    if(promt){
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId}).done(function(error){
            if(error!=""){
                alert(error)
                return
            }
            openPage('yourMusic.php')
        })
    }
}

function hideOptionMenu(){
    var menu = $(".optionMenu")
    if(menu.css("display") != "none"){
        menu.css("display", "none")
    }
}

function showOptionMenu(button){
    var menu = $(".optionMenu")
    var menuWidth = menu.width()
    var scrollTop = $(window).scrollTop()
    var elementOffset = $(button).offset().top
    var top = elementOffset - scrollTop
    var left = $(button).position().left
    menu.css({
        "top": top+"px",
        "left": left-menuWidth+"px",
        "display": "inline"
    })
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