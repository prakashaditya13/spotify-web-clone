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
        currentPlaylist = <?php echo $jsonArray; ?>;
        audioElement = new Audio();
        setTrack(currentPlaylist[0], currentPlaylist, false);
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

    function nextSong(){
        if(repeat===true){
            audioElement.setTime(0)
        }

        if(currentIndex === currentPlaylist.length-1){
            currentIndex = 0
        }else{
            currentIndex++
        }

        var trackToPlay = currentPlaylist[currentIndex]
        setTrack(trackToPlay, currentPlaylist, true)
    }

    function setTrack(trackId, newPlaylist, play) {

        $.post("includes/handlers/ajax/getSongJson.php", {
            songId: trackId
        }, function(data) {
            currentIndex = currentPlaylist.indexOf(trackId)
            var track = JSON.parse(data)
            $(".trackName span marquee").text(track.title)

            $.post("includes/handlers/ajax/getArtistJson.php", {
                artistId: track.artist
            }, function(data) {
                var artist = JSON.parse(data)
                $(".artistName span").text(artist.name)
            })

            $.post("includes/handlers/ajax/getAlbumJson.php", {
                albumId: track.album
            }, function(data) {
                var album = JSON.parse(data)
                $(".albumLink img").attr("src", album.albumArtwork)
            })
            audioElement.setTrack(track)
            playSong()
        });

        if (play) {
            playSong()
        }
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
                        <span class="x">
                            <marquee behavior="" direction=""></marquee>
                        </span>
                    </span>
                    <span class="artistName">
                        <span class="x"></span>
                    </span>
                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="shuffle music">
                        <img src="./assets/images/icons/shuffle.png" alt="shuffle">
                    </button>
                    <button class="controlButton previous" title="Previous music">
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
                    <button class="controlButton repeat" title="Repeat music">
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
                <button class="controlButton volume" title="Volume Button">
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