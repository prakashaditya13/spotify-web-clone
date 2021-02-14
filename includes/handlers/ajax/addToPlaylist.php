<?php 
include("../../config.php");
if(isset($_POST['playlistId']) && isset($_POST['songId'])){
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];
    $orderQuery = mysqli_query($conn, "SELECT MAX(playlistOrder)+1 as playlistOrder FROM playlistsong WHERE playlistId='$playlistId'");
    $row = mysqli_fetch_array($orderQuery);
    $order = $row['playlistOrder'];

    $query = mysqli_query($conn, "INSERT INTO playlistsong VALUES('', '$songId', '$playlistId', '$order')");
}else{
    echo "Playlist ID or Song ID is found";
}

?>