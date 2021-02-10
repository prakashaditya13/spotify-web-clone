<?php 

include("../../config.php");

if(isset($_POST['songsId'])){
    $songId = $_POST['songsId'];
    $query = mysqli_query($conn,"UPDATE songs SET plays=plays+1 WHERE id='$songId'");
    $resultArray = mysqli_fetch_array($query);
    echo json_encode($resultArray);
}

?>