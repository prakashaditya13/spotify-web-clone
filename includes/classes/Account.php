<?php

class Account
{
    private $conn;
    private $errorArray;
    public function __construct($conn)
    {   
        $this->conn = $conn;
        $this->errorArray = array();
    }

    public function login($loginUser, $loginPass){
        $Epw = md5($loginPass);
        $query = mysqli_query($this->conn, "SELECT * FROM users WHERE username='$loginUser' AND password='$Epw'");
        if(mysqli_num_rows($query) == 1){
            return true;
        }else{
            array_push($this->errorArray,Constants::$loginError);
            return false;
        }
    }

    public function register($un, $fn, $ln, $em, $em1, $pass, $pass1)
    {
        $this->validateUsername($un);
        $this->validateFirstname($fn);
        $this->validateLastname($ln);
        $this->validateEmails($em, $em1);
        $this->validatePasswords($pass, $pass1);

        if(empty($this->errorArray) == true){
            return $this->insertUserDetails($un, $fn, $ln, $em, $pass);
        }else{
            return false;
        }
    }

    private function insertUserDetails($un, $fn, $ln, $em, $pass){
        $encryptPass = md5($pass);
        $profilePic = "assets/images/profile-pic/DSCF3447-1.jpg";
        $date = date("Y-m-d");
        $result = mysqli_query($this->conn, "INSERT INTO users VALUES ('', '$un', '$fn', '$ln', '$em', '$encryptPass', '$date', '$profilePic')");
        return $result;
    }

    public function getError($error){
        if(!in_array($error, $this->errorArray)){
            $error = "";
        }
        return "<span class='errorMessage'>$error</span>";
    }


    private function validateUsername($un)
    {
        if(strlen($un) > 25 || strlen($un) < 5){
            array_push($this->errorArray,Constants::$userName);
            return;
        }
        $checkUsernameQuery = mysqli_query($this->conn, "SELECT username FROM users WHERE username='$un'"); 
        if(mysqli_num_rows($checkUsernameQuery) != 0){
            array_push($this->errorArray,Constants::$checkUsernameResult);
            return;
        }
    }

    private function validateFirstname($fn)
    {
        if(strlen($fn) > 25 || strlen($fn) < 2){
            array_push($this->errorArray,Constants::$firstName);
            return;
        }
    }

    private function validateLastname($ln)
    {
        if(strlen($ln) > 25 || strlen($ln) < 2){
            array_push($this->errorArray,Constants::$lastname);
            return;
        }
    }

    private function validateEmails($em, $em2)
    {
        if($em != $em2){
            array_push($this->errorArray,Constants::$email1);
            return;
        }
        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$email2);
            return;
        }
        $checkEmailQuery = mysqli_query($this->conn, "SELECT email FROM users WHERE email='$em'"); 
        if(mysqli_num_rows($checkEmailQuery) != 0){
            array_push($this->errorArray,Constants::$checkEmailResult);
            return;
        }
    }

    private function validatePasswords($pass, $pass1)
    {
        if($pass != $pass1){
            array_push($this->errorArray,Constants::$pass1);
            return;
        }
        if(preg_match('/[^A-Za-z0-9]/', $pass)){
            array_push($this->errorArray,Constants::$pass2);
            return;
        }
        if(strlen($pass) > 30 || strlen($pass) < 8){
            array_push($this->errorArray,Constants::$pass3);
            return;
        }
    }
}
