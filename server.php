<?php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

// Abstrakta klase, kas simulē Enum objektu priekš error tipiem, kas ir attiecināmi JS definētajiem error tipiem
abstract Class ErrorType{

    // Error tips lietotājvārdu kļūdām
    const USERNAME = "username_error";

    // Error tips paroļu kļūdām
    const PASSWORD = "password_error";
    
    // Error tips nenoteiktām kļūdām
    const NONE = "none";
}

function out($message, bool $is_error=false, string $type=ErrorType::NONE){

    /*
                            out() - izvades funkcija

    Šo funkciju jālieto gadījumos ja serveris izvada informāciju klientam.
    Katru reizi kad kaut kas tiek izvadīts ar šo funkciju, skripts apstājas, tādā
    veidā samazinot if/else statement lietošanu.

    @author: CracX
    @example: out("Invalid username", $is_error=true, ErrorType::USERNAME);

    */

    switch($is_error){
        
        // Ja izvade ir kļūda
        case true:

            // Sūtam jebkādu error kodu lai frontend AJAX funkcija nofeilo
            http_response_code(404);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "error" => true,
                "message" => $message,
                "type" => $type
            );
            break;
        
        // Ja izvade ir parasts ziņojums
        case false:

            // Sūtam success kodu
            http_response_code(200);

            // izveidojam ziņojumu attiecīgā formātā
            $out = array(
                "success" => true,
                "message" => $message
            );
            break;

        default:
            
            // Noklusējuma rezultātā izvadām parastu ziņojumu
            http_response_code(200);
            $out = array(
                "success" => true,
                "message" => $message
            );
    }

    // Liekam skriptam "nomirt" un neturpināt tālāk, izvadot ziņojumu
    die(json_encode($out, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

if($_POST['action'] == "insert_user"){
    $errors = false;
  
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_ARGON2I);

    $output = [];

    if(strlen($username) < 5){
        out("Lietotājvārdam jābūt vismaz 5 simbolus garam", $is_error=true, $type=ErrorType::USERNAME);
    }

    //Ja ievadīts lietotājvārds, kurš garāks par 4 simboliem, tad pārbauda vai tāds jau eksistē DB
    $is_available = "SELECT * FROM users WHERE username = '$username'";
    if($result = mysqli_query($con, $is_available)){
        if(mysqli_num_rows($result) == 1){
            out("Lietotājvārds jau ir aizņemts", $is_error=true, ErrorType::USERNAME);
        }
    }

    if(strlen($password) < 5){
        out("Parolei jābūt vismaz 5 simbolus garai", $is_error=true, $type=ErrorType::PASSWORD);
    }

    //Ja nav eroru, tad reģistrē
    $insert_query = "INSERT INTO users (username, password, joined) VALUES ('$username', '$hash', NOW())";
    $result = mysqli_query($con, $insert_query);
    if($result){
        out("Lietotājs veiksmīgi reģistrēts");
    }
}
?>