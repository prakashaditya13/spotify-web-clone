<?php include('./includes/header.php'); ?>

<h1 class="pageHeading">You Might Also Like</h1>

<div class="gridViewContainer">
    <?php 
        $albumQuery = mysqli_query($conn, "SELECT * FROM album ORDER BY RAND() LIMIT 10");

        while($row = mysqli_fetch_array($albumQuery)){
            echo "<div class='gridViewItem'>
            <img src='".$row['albumArtwork']."'>
            <div class='gridViewInfo'>"
                .$row['title'].
            "</div>
            </div>";
        }
    ?>
</div>

<?php include('./includes/footer.php'); ?>
