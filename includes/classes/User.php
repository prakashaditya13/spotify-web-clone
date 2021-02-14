<?php
class User
{
    private $conn;
    private $username;
    public function __construct($conn, $username)
    {   
        $this->conn = $conn;
        $this->username = $username;
    }
    public function getUsername(){
        return $this->username;
    }
}


?>