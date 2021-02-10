<?php 

class Album{
    private $conn;
    private $id;
    private $title;
    private $artistId;
    private $genre;
    private $artworkPath;

    public function __construct($conn, $id){
        $this->conn = $conn;
        $this->id = $id;
        $Query = mysqli_query($this->conn, "SELECT * FROM album WHERE id='$this->id'");
        $result = mysqli_fetch_array($Query);
        $this->title = $result['title'];
        $this->artistId = $result['artist'];
        $this->genre = $result['genre'];
        $this->artworkPath = $result['albumArtwork'];
    }

    public function getTitle(){
        return $this->title;
    }
    public function getArtist(){
        return new Artist($this->conn, $this->artistId);
    }
    public function getGenreId(){
        return $this->genre;
    }
    public function getArtworkPath(){
        return $this->artworkPath;
    }

    public function getNumberOfSongs(){
        $query = mysqli_query($this->conn, "SELECT id FROM songs WHERE album='$this->id'");
        return mysqli_num_rows($query);
    }

    public function getSongIds(){
        $query = mysqli_query($this->conn, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC");
        $array = array();
        while($row = mysqli_fetch_array($query)){
            array_push($array, $row['id']);
        }
        return $array;
    }
}


?>