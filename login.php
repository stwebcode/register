<?php
require_once 'config.php';
if(isset($_SESSION['user']['user']))
{
	header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="croppie.js"></script>
    <link rel="stylesheet" href="croppie.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body style="background-color: rgb(17, 17, 17);">

    <div class="login-form">

        <div class="basic-data login-box">
            <h1>Autorizācija</h1>
            <input type="text" id="username" placeholder="Lietotājvārds" autocomplete="off">
            <span id="username_msg"></span>
            <input type="password" id="password" placeholder="Parole" autocomplete="off">
            <span id="password_msg"></span>
            <div id="login">Pieslēgties</div>
            <div id="status_msg"></div>
            <div id="msg"></div>
        </div> 
    </div>


    <script>
    // Šeit tiek definēti Error tipi t.i. vietas, kur parādās errori
    const ErrorType = {

        // Error tips lietotājvārdu kļūdām
        USERNAME: "username",

        // Error tips paroļu kļūdām
        PASSWORD: "password",

        // Error tips status kļūdām
        STATUS: "status",

        // Ziņojuma tips veiksmīgiem ziņojumiem
        SUCCESS: "success"
    }

    // Funckija, kas iztīra error laukus
    const errorClear = () => {
        $('#username_msg').text('')
        $('#password_msg').text('')
        $('#status_msg').text('')
    }
    // Funkcija, kas parāda <message> vietā <type> (ErrorType)
    const errorOut = (type, message) => {

    // Skatoties pēc tipa, izvadam ziņojumu/kļūdu
    switch(type){

        case ErrorType.USERNAME:
            $('#username_msg').text(message)
            break
        
        case ErrorType.PASSWORD:
            $('#password_msg').text(message)
            break

        case ErrorType.STATUS:
            $('#status_msg').text(message)
            break

        case ErrorType.SUCCESS:
            $('#msg').text(message)
            break
        
        default:
            break
        }
    }

    function errorAnim(input) { // input vietā liek attiecīgo input field, piemēram, '#username'
        $(input).addClass('bounce')
        setTimeout(() => {
            $(input).removeClass('bounce')
        }, 1000);
    }

    $( document ).ready(function() {
        $("#username").focus()
        $('#login').click(loginUser)
    });
    function loginUser(){
        var username = $('#username').val()
        var password = $('#password').val()

        // iztīram ziņojumus
        errorClear();

        // Pārbaudam ievadīto lietotājvārdu
        if(username.length < 5) {
            errorAnim('#username')
            errorOut(ErrorType.USERNAME, "Lietotājvārdam jābūt vismaz 5 simbolus garam")
            return
        }

        // Pārbaudam ievadīto paroli
        if(password.length < 5) {
            errorAnim('#password')
            errorOut(ErrorType.PASSWORD, "Parolei jābūt vismaz 5 simbolus garai")
            return
        }

        // Ja neviens no erroriem netika triggerots, sūtam pieprasījumu serverim
        $.post("server.php", {
                action: "login_user",
                username: username,
                password: password

            // Ja serveris atbild ar 200 (Success)
            }, (data) => {
                console.log(data.message)
                errorOut(ErrorType.SUCCESS, data.message)
                window.location.href = "index.php";
                return;

            // Ja serveris atbild ar 404, 500 u.c. (Not found / Failed)
            }).fail((data) => {

                // Skatamies kāda tipa error serveris atsūta, uz to arī reaģējam
                switch(data.responseJSON.type){
                    case "username_error":
                        errorAnim('#username')
                        errorOut(ErrorType.USERNAME, data.responseJSON.message)
                        break
                    
                    case "password_error":
                        errorOut(ErrorType.PASSWORD, data.responseJSON.message)
                        break
                    
                    case "status_error":
                        errorOut(ErrorType.STATUS, data.responseJSON.message)
                        break

                    default:
                        // Ja nav definēts servera errors tad klientam izvadīsies atbildes dump konsolē (response dump)
                        console.log(data)
                        break
                }
                return;
            })
        }//END loginUser()
    </script>
</body>
</html>
