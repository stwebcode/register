<?php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');


function out(string $message, bool $is_error=false, string $type="default"){

    /*
                        out() - izvades funkcija

    Šo funkciju jālieto gadījumos ja serveris izvada informāciju klientam.
    Katru reizi kad kaut kas tiek izvadīts ar šo funkciju, skripts apstājas, tādā
    veidā samazinot if/else statement lietošanu.

    @author: CracX

    */

    switch($is_error){
        case true:
            $out = array(
                "error" => true,
                "message" => $message,
                "type" => $type
            );
            break;
        
        case false:
            $out = array(
                "success" => true,
                "message" => $message
            );
            break;

        default:
            $out = array(
                "success" => true,
                "message" => $message
            );
    }

    die(json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

if($_POST['action'] == "insert_user"){
    $errors = false;
  
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_ARGON2I);

    $output = [];

    if(strlen($username) < 5){
        out("Lietotājvārdam jābūt vismaz 5 simbolus garam", $is_error=true, $type="username_error");
    }

    //Ja ievadīts lietotājvārds, kurš garāks par 4 simboliem, tad pārbauda vai tāds jau eksistē DB
    $is_available = "SELECT * FROM users WHERE username = '$username'";
    if($result = mysqli_query($con, $is_available)){
        if(mysqli_num_rows($result) == 1){
            $errors = true;
            $output["username_error"] = true;
            $output["username_msg"] = "Lietotājvārds jau ir aizņemts";
        }
    }

    if(strlen($password) < 5){
        out("Parolei jābūt vismaz 5 simbolus garai", $is_error=true, $type="password_error");
    }

    //Ja nav eroru, tad reģistrē
    $insert_query = "INSERT INTO users (username, password, joined) VALUES ('$username', '$hash', NOW())";
    $result = mysqli_query($con, $insert_query);
    if($result){
        out("Lietotājs veiksmīgi reģistrēts");
    }
}
?>