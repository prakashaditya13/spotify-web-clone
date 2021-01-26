<?php 

function formatPassword($inputText){
    $inputText = strip_tags($inputText);
    return $inputText;
}
function formatUsername($inputText){
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ","",$inputText);
        return $inputText;
}

function formatFormString($inputText){
    $inputText = strip_tags(str_replace(" ","",$inputText));
    $inputText = ucfirst(strtolower($inputText));
    return $inputText;
}

if(isset($_POST['registerButton'])){
    // register was pressed
    $username = formatUsername($_POST['Username']);
    $firstName = formatFormString($_POST['firstName']);
    $lastName = formatFormString($_POST['lastName']);
    $email = formatFormString($_POST['email']);
    $confirmEmail = formatFormString($_POST['confirmEmail']);
    $password = formatPassword($_POST['Password']);
    $confirmPassword = formatPassword($_POST['confirmPassword']);
    
}


?>