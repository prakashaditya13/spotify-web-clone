<?php 
include("includes/includeFiles.php");
?>


<div class="playlistContainer">
    <div class="gridViewContainer">
        <h2>PLAYLISTS</h2>
        <div class="buttonItems">
            <button class="button green" onclick="createPlaylist()">
                New Playlist
            </button>
        </div>

        <?php 
        $username = $userLoggedIn->getUsername();
        $playlistQuery = mysqli_query($conn, "SELECT * FROM playlist WHERE owner='$username'");
        if(mysqli_num_rows($playlistQuery) == 0){
            echo "<span class='noResults'>No playlists Found</span>";
        }
        while($row = mysqli_fetch_array($playlistQuery)){
            $playlist = new PLaylist($conn, $row);
            echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=".$playlist->getId()."\")'>
            <div class='playlistImage'>
                <img src='assets/images/icons/playlist.png'>
            </div>
            <div class='gridViewInfo'>"
            .$playlist->getname().
            "</div>
            </div>";
        }
    ?>









    </div>
</div>