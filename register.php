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
        #username_msg, #password_msg {
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
    <!-- Jāievieš paroles apstiprināšana to ievadot otrreiz -->
    <div id="register">Reģistrēties</div>
    <div id="msg"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $( document ).ready(function() {
            $('#register').click(myFunction)

            function myFunction(){
                var username = $('#username').val()
                var password = $('#password').val()

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
            }
            
        });
    </script>
</body>
</html>