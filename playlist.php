<?php include('includes/includeFiles.php'); 

if(isset($_GET['id'])){
    $playlistId = $_GET['id'];
}else{
    header('Location: index.php');
}

$playlist = new PLaylist($conn,$playlistId);
$owner = new User($conn, $playlist->getOwner());
?>

<div class="entityInfo">
    <div class="leftSection">
        <img src="assets/images/icons/playlist.png" class="playlistimage">
    </div>
    <div class="rightSection">
        <h2><?php echo $playlist->getName(); ?></h2>
        <p>By <?php echo $playlist->getOwner(); ?></p>
        <span><?php echo $playlist->getNumberOfSongs(); ?> Songs</span>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">Delete Playlist</button>
    </div>
</div>

<div class="trackListContainer">
    <ul class="trackList">
        <?php
        
        $songIdArray = $playlist->getSongIds();
        $i = 1;
        foreach($songIdArray as $songId){
            $playlistSong = new Song($conn, $songId);
            $songArtist = $playlistSong->getArtist();

            echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"".$playlistSong->getId()."\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>       
                    </div>

                    <div class='trackInfo'>
                        <span class='trackName'>".$playlistSong->getTitle()."</span>
                        <span class='artistName'>".$songArtist->getName()."</span>
                    </div>

                    <div class='trackOptions'>
                        <img class='optionButton' src='assets/images/icons/more.png'>
                    </div>

                    <div class='trackDuration'>
                        <span class='duration'>".$playlistSong->getDuration()."</span>
                    </div>
                </li>";
        $i++;
        }
        
        ?>

    <script>
        var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
        tempPlaylist = JSON.parse(tempSongIds)
    </script>


    </ul>
</div>


