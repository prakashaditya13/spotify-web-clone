<?php 

class Artist{
    private $conn;
    private $id;

    public function __construct($conn, $id){
        $this->conn = $conn;
        $this->id = $id;
    }
    public function getName(){
        $artistQuery = mysqli_query($this->conn, "SELECT name FROM artist WHERE id='$this->id'");
        $artist = mysqli_fetch_array($artistQuery);
        return $artist['name'];
    }
}


?>