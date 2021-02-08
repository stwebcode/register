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
    <style>
        #username_msg, #password_msg, #verify_password_msg {
            color: red;
        }
        #msg {
            color: green;
        }
        #register {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <input type="text" id="username" placeholder="Lietotājvārds" autocomplete="off"><span id="username_msg"></span><br>
    <input type="text" id="password" placeholder="Parole" autocomplete="off"><span id="password_msg"></span><br>
    <!-- Paroles apstiprināšana to ievadot otrreiz -->
    <input type="text" id="verify_password" placeholder="Apstipriniet paroli" autocomplete="off"><span id="verify_password_msg"></span><br>
    <div id="register">Reģistrēties</div>
    <div id="msg"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#register').click(myFunction)

            function myFunction(){
                var username = $('#username').val()
                var password = $('#password').val()
                var verify_password = $('#verify_password').val()

                if(verify_password === password) { //Te Jānis, ideja tāda, ka vispirms frontendā pārbauda vai paroles ir vienādas, ja ir tad tikai pēc tam sūta datus uz serveri. Ja abos paroļu laukos ir tukšums, tad serveris pārbauda paroles garumu
                    $('#verify_password_msg').text('')
                    $.ajax({
                        url: 'server.php',
                        method: 'post',
                        dataType: 'json',
                        data: {
                            action: 'insert_user',
                            username: username,
                            password: password
                        },
                        success: function(data){
                            $('#username_msg').text('')
                            $('#password_msg').text('')
                            if(data.username_error){
                                $('#username_msg').text(data.username_msg)
                            }
                            if(data.password_error){
                                $('#password_msg').text(data.password_msg)
                            }
                            if(data.success){
                                $('#msg').text(data.msg)
                            }
                            
                        }
                    })
                } else if (verify_password != password) {
                    $('#verify_password_msg').text('Paroles nesakrīt')
                    if(username.length > 4) { //Šeit noņem error messages, ja paroles nesakrīt, bet username vai parole ir garāka par 4 simboliem, jo savādāk errori nepazūd, jo uz serveri datus sūta tikai tad, ja paroles sakrīt. Error par to, ka tāds username jau pastāv, parādās tikai tad, ja vēlreiz nospiež reģistrēties, jo to pārbauda serveris.
                        $('#username_msg').text('')
                    }
                    if(password.length > 4) {
                        $('#password_msg').text('')
                    }
                }
            }
            
        });
    </script>
</body>
</html>