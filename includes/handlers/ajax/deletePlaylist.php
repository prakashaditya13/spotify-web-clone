<?php
include("../../config.php");

if(isset($_POST['playlistId'])){
    $playlistId = $_POST['playlistId'];
    $playlistQuery = mysqli_query($conn, "DELETE FROM playlist WHERE id='$playlistId'");
    $songQuery = mysqli_query($conn, "DELETE FROM playlistsong WHERE playlistId='$playlistId'");
}else{
    echo "Playlist ID is found";
}


?>