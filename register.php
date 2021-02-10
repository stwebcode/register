<?php
require_once 'config.php';
if(isset($_SESSION['user_id']))
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
</head>
<body style="background-color: rgb(17, 17, 17);">
    <div class="register-box">
        <h1>Reģistrācija</h1>
        <input type="text" id="username" placeholder="Lietotājvārds" autocomplete="off"><br>
        <span id="username_msg"></span><br>
        <input type="password" id="password" placeholder="Parole" autocomplete="off"><br>
        <span id="password_msg"></span><br>
        <input type="password" id="verify_password" placeholder="Apstipriniet paroli" autocomplete="off"><br>
        <span id="verify_password_msg"></span><br><br>
        <div id="register">Reģistrēties</div><br><br>
        <div id="msg"></div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <script>

        // Šeit tiek definēti Error tipi t.i. vietas, kur parādās errori
        const ErrorType = {

            // Error tips lietotājvārdu kļūdām
            USERNAME: "username",

            // Error tips paroļu kļūdām
            PASSWORD: "password",

            // Error tips atkārtotas paroles kļūdām
            VER_PASSWORD: "password_verify",

            // Ziņojuma tips veiksmīgiem ziņojumiem
            SUCCESS: "success"
        }

        // Funckija, kas iztīra error laukus
        const errorClear = () => {
            $('#username_msg').text('')
            $('#password_msg').text('')
            $('#verify_password_msg').text('')
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
                
                case ErrorType.VER_PASSWORD:
                    $('#verify_password_msg').text(message)
                    break
                
                case ErrorType.SUCCESS:
                    $('#msg').text(message)
                    break
                
                default:
                    break
            }
        }

        // Sākuma funkcija - tiek palaista lapas ielādes beigās.
        $( document ).ready(function() {
            $("#username").focus() //Kad ielādējas lapa tad var ievadīt username, nav jāspiež uz tā
            $('#register').click(registerUser)
        });

        // Funkcija, kas reģistrē lietotāju
        function registerUser(){
            var username = $('#username').val()
            var password = $('#password').val()
            var verify_password = $('#verify_password').val()

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

            // Pārbaudam abu ievadīto paroļu līdzību
            if (verify_password != password) {
                errorAnim('#verify_password')
                errorOut(ErrorType.VER_PASSWORD, "Paroles nesakrīt")
                return
            }

            // Ja neviens no erroriem netika triggerots, sūtam pieprasījumu serverim
            $.post("server.php", {
                    action: "insert_user",
                    username: username,
                    password: password

                // Ja serveris atbild ar 200 (Success)
                }, (data) => {
                    errorOut(ErrorType.SUCCESS, data.message)
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
                        
                        default:
                            // Ja nav definēts servera errors tad klientam izvadīsies atbildes dump konsolē (response dump)
                            console.log(data)
                            break
                    }
                    return;
                })
            }

            // Funkcija, kas pievieno un noņem klasi 'bounce' (error animāciju)
            function errorAnim(input) { // input vietā liek attiecīgo input field, piemēram, '#username'
                $(input).addClass('bounce')
                setTimeout(() => {
                    $(input).removeClass('bounce')
                }, 1000);
            }

    </script>
</body>
</html>
