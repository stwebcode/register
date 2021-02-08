<?php
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

if($_POST['action'] == "insert_user"){
    $errors = false;
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

    $output = [];

    if(strlen($username) < 4){
        $errors = true;
        $output["username_error"] = true;
        $output["username_msg"] = "Lietotājvārdam jābūt vismaz 5 simbolus garam";
    } else {
        //Ja ievadīts lietotājvārds, kurš garāks par 4 simboliem, tad pārbauda vai tāds jau eksistē DB
        $is_available = "SELECT * FROM users WHERE username = '$username'";
        if($result = mysqli_query($con, $is_available)){
            if(mysqli_num_rows($result) == 1){
                $errors = true;
                $output["username_error"] = true;
                $output["username_msg"] = "Lietotājvārds jau ir aizņemts";
            }
        }
    }


    if(strlen($password) < 4){
        $errors = true;
        $output["password_error"] = true;
        $output["password_msg"] = "Parolei jābūt vismaz 5 simbolus garai";
    }
    if(!empty($output)){
        echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    if(!$errors){//Ja nav eroru, tad reģistrē
        $insert_query = "INSERT INTO users (username, password, joined) VALUES ('$username', '$password', NOW())";
        $result = mysqli_query($con, $insert_query);
        if($result){
            $output = array(
                "success" => true,
                "msg"   => "Lietotājs veiksmīgi reģistrēts"
            );
            echo json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }


    
}
?>