<?php 
include("includes/includeFiles.php");

if(isset($_GET['term'])){
    $term = urldecode($_GET['term']);
}else{
    $term = "";
}
?>

<div class="searchContainer">
    <h4>Search for artists, albums and songs</h4>
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="start typing..." onfocus="this.value = this.value">
</div>

<script>
    $(".searchInput").focus()
    $(function(){
        $(".searchInput").keyup(function(){
            clearTimeout(timer)

            timer = setTimeout(function(){
                var val = $(".searchInput").val()
                openPage("search.php?term="+val)
            },1000)
        })
    })

</script>

<?php if($term=="") exit(); ?>
<div class="trackListContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="trackList">
        <?php
        $songQuery = mysqli_query($conn, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");
        if(mysqli_num_rows($songQuery) == 0){
            echo "<span class='noResults'>No Songs Found ".$term."</span>";
        }

        $songIdArray = array();
        $i = 1;
        while($row = mysqli_fetch_array($songQuery)){
            if($i>15){
                break;
            }
            array_push($songIdArray, $row['id']);
            $albumSong = new Song($conn, $row['id']);
            $albumArtist = $albumSong->getArtist();

            echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"".$albumSong->getId()."\", tempPlaylist, true)'>
                        <span class='trackNumber'>$i</span>       
                    </div>

                    <div class='trackInfo'>
                        <span class='trackName'>".$albumSong->getTitle()."</span>
                        <span class='artistName'>".$albumArtist->getName()."</span>
                    </div>

                    <div class='trackOptions'>
                        <input type='hidden' class='songId' value='".$albumSong->getId()."'>
                        <img class='optionButton' src='assets/images/icons/more.png' onclick='showOptionMenu(this)'>
                    </div>

                    <div class='trackDuration'>
                        <span class='duration'>".$albumSong->getDuration()."</span>
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


<div class="artistContainer borderBottom">
    <h2>ARTISTS</h2>
    <?php
    
    $artistQuery = mysqli_query($conn, "SELECT id FROM artist WHERE name LIKE '$term%' LIMIT 10");
    if(mysqli_num_rows($artistQuery) == 0){
        echo "<span class='noResults'>No Artists Found ".$term."</span>";
    }
    while($row = mysqli_fetch_array(($artistQuery))){
        $artistFound = new Artist($conn, $row['id']);
        echo "<div class='searchResultRow'>
            <div class='artistName'>
                <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=".$artistFound->getId()."\")'>".$artistFound->getName()."</span>
            </div> 
        </div>";
    }
    ?>
</div>


<div class="gridViewContainer">
    <h2>ALBUMS</h2>
    <?php 
        $albumQuery = mysqli_query($conn, "SELECT * FROM album WHERE title LIKE '$term%' LIMIT 10");
        if(mysqli_num_rows($albumQuery) == 0){
            echo "<span class='noResults'>No Albums Found ".$term."</span>";
        }
        while($row = mysqli_fetch_array($albumQuery)){
            echo "<div class='gridViewItem'>
            <span role='link' tabindex='0' onclick='openPage(\"album.php?id=".$row['id']."\")'>
                <img src='".$row['albumArtwork']."'>
                <div class='gridViewInfo'>"
                    .$row['title'].
                "</div>
            </span>
            </div>";
        }
    ?>
</div>

<nav class="optionMenu">
    <input type="hidden" class="songId">
    <?php echo PLaylist::getPlaylistDropdown($conn, $userLoggedIn->getUsername()); ?>
</nav>