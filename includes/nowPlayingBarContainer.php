<?php
$songsQuery = mysqli_query($conn, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
$resultArray = array();
while ($row = mysqli_fetch_array($songsQuery)) {
    array_push($resultArray, $row['id']);
}

$jsonArray = json_encode($resultArray);
?>

<script>
    $(document).ready(function() {
        var newPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false);
        $(".volumeBar .progress").css("width", audioElement.audio.volume*50 + "%")

        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e){
            e.preventDefault()
        })


        // controlling playBar with mouse
        $(".playbackBar .progressBar").mousedown(function() {
            mouseDown = true
        })

        $(".playbackBar .progressBar").mousemove(function(e) {
            if (mouseDown) {
                // set the time of depending on mouse position
                timeFromOffset(e, this)
            }
        })

        $(".playbackBar .progressBar").mouseup(function(e) {
            timeFromOffset(e, this)
        })

        // controlling volumeBar with mouse
        $(".volumeBar .progressBar").mousedown(function() {
            mouseDown = true
        })

        $(".volumeBar .progressBar").mousemove(function(e) {
            if (mouseDown) {
                // set the volume of depending on mouse position
                var percentage = e.offsetX / $(this).width()
                if (percentage >= 0 && percentage <= 1) {
                    audioElement.audio.volume = percentage
                }
            }
        })

        $(".volumeBar .progressBar").mouseup(function(e) {
            var percentage = e.offsetX / $(this).width()
            if (percentage >= 0 && percentage <= 1) {
                audioElement.audio.volume = percentage
            }
        })



        $(document).mouseup(function() {
            mouseDown = false;
        })
    });

    function timeFromOffset(mouse, progressBar) {
        var percentage = mouse.offsetX / $(progressBar).width() * 100;
        var seconds = audioElement.audio.duration * (percentage / 100)
        audioElement.setTime(seconds)
    }

    function prevSong(){
        if(audioElement.audio.currentTime>=3 || currentIndex===0){
            audioElement.setTime(0);
        }else{
            currentIndex--;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true)
        }
    }

    function nextSong(){
        if(repeat===true){
            audioElement.setTime(0)
            playSong()
            return;
        }

        if(currentIndex === currentPlaylist.length-1){
            currentIndex = 0
        }else{
            currentIndex++
        }

        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex]
        setTrack(trackToPlay, currentPlaylist, true)
    }

    function repeatSong(){
        repeat = !repeat
        var imageName = repeat ? "repeat-active.png" : "repeat.png"
        $(".controlButton.repeat img").attr("src", "./assets/images/icons/"+imageName)
    }

    function MuteSong(){
        audioElement.audio.muted = !audioElement.audio.muted
        var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png"
        $(".controlButton.volume img").attr("src", "./assets/images/icons/"+imageName)
    }

    function shuffleSong(){
        shuffle = !shuffle
        var imageName = shuffle ? "shuffle-active.png" : "shuffle.png"
        $(".controlButton.shuffle img").attr("src", "./assets/images/icons/"+imageName)

        if(shuffle){
            shuffleArray(shufflePlaylist)
            currentIndex = shufflePlaylist.indexOf(audioElement.CurrentPlaying.id)
        }else{
            currentIndex = currentPlaylist.indexOf(audioElement.CurrentPlaying.id)
        }

    }

    function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
    return a;
}

    function setTrack(trackId, newPlaylist, play) {

        if(newPlaylist != currentPlaylist){
            currentPlaylist = newPlaylist
            shufflePlaylist = currentPlaylist.slice()
            shuffleArray(shufflePlaylist)
        }
        if(shuffle){
            currentIndex = shufflePlaylist.indexOf(trackId)
        }else{
            currentIndex = currentPlaylist.indexOf(trackId)
        } 
        pauseSong()
        $.post("includes/handlers/ajax/getSongJson.php", {
            songId: trackId
        }, function(data) {
            var track = JSON.parse(data)
            $(".trackName span marquee").text(track.title)

            $.post("includes/handlers/ajax/getArtistJson.php", {
                artistId: track.artist
            }, function(data) {
                var artist = JSON.parse(data)
                $(".artistName span").text(artist.name)
                $(".artistName span").attr("onclick", "openPage('artist.php?id="+artist.id+"')")
            })

            $.post("includes/handlers/ajax/getAlbumJson.php", {
                albumId: track.album
            }, function(data) {
                var album = JSON.parse(data)
                $(".albumLink img").attr("src", album.albumArtwork)
                $(".albumLink img").attr("onclick", "openPage('album.php?id="+album.id+"')")
                $(".trackName span marquee").attr("onclick", "openPage('album.php?id="+album.id+"')")
            })
            audioElement.setTrack(track)
            if (play) {
                playSong()
        }
        });

        
    }

    function playSong() {

        if (audioElement.audio.currentTime == 0) {
            $.post("includes/handlers/ajax/updatePlays.php", {
                songsId: audioElement.CurrentPlaying.id
            }, function(data) {
                var album = JSON.parse(data)
            })
        }
        $(".controlButton.play").hide()
        $(".controlButton.pause").show()
        audioElement.play()
    }

    function pauseSong() {
        $(".controlButton.play").show()
        $(".controlButton.pause").hide()
        audioElement.pause()
    }
</script>






<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img src="" alt="songPic" class="albumPic">
                </span>
                <div class="trackInfo">
                    <span class="trackName">
                        <span class="x" role="link" tabindex="0">
                            <marquee behavior="" direction=""></marquee>
                        </span>
                    </span>
                    <span class="artistName">
                        <span class="x" role="link" tabindex="0"></span>
                    </span>
                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="shuffle music" onclick="shuffleSong()">
                        <img src="./assets/images/icons/shuffle.png" alt="shuffle">
                    </button>
                    <button class="controlButton previous" title="Previous music" onclick="prevSong()">
                        <img src="./assets/images/icons/previous.png" alt="previous">
                    </button>
                    <button class="controlButton play" title="Play music" onclick="playSong()">
                        <img src="./assets/images/icons/play.png" alt="play">
                    </button>
                    <button class="controlButton pause" title="Pause music" style="display: none;" onclick="pauseSong()">
                        <img src="./assets/images/icons/pause.png" alt="pause">
                    </button>
                    <button class="controlButton next" title="Next music" onclick="nextSong()">
                        <img src="./assets/images/icons/next.png" alt="next">
                    </button>
                    <button class="controlButton repeat" title="Repeat music" onclick="repeatSong()">
                        <img src="./assets/images/icons/repeat.png" alt="repeat">
                    </button>
                </div>
                <div class="playbackBar">
                    <span class="progressTime currentTime">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarbg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remainingTime">0.00</span>

                </div>
            </div>
        </div>
        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume Button" onclick="MuteSong()">
                    <img src="./assets/images/icons/volume.png" alt="volume">
                </button>
                <div class="progressBar">
                    <div class="progressBarbg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>