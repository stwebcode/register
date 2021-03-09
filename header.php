<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="calendar/evo-calendar.css" />
    <link rel="stylesheet" href="calendar/evo-calendar.midnight-blue.css" />
    <link rel="stylesheet" href="index.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>TODO: title</title>
    <script>
        window.onscroll = function(){
            if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
                $("#header nav").css("height","50px");
                $("#header li a").css("padding","10px 6px");
            } else {
                $("#header nav").css("height","90px");
                $("#header li a").css("padding","20px 10px");
            }
        }
    </script>
</head>
<body>
<div id="header">
    <nav>
        <div id="logo-container"><a href="index.php"><img id="logo" src="images/logo.png"></a></div>
        <ul>
            <li><a href="index.php">Sākums</a></li>
            
        </ul>
        <?php
        echo "
        <div id=\"header-user\">
            <img src='images/30x30/{$_SESSION['user']['image']}' alt=''>
            <div>{$_SESSION['user']['firstname']} {$_SESSION['user']['lastname']}</div>
            <a href='logout.php'>Izlogoties</a>
        </div>";
        ?>
    </nav>
</div>
<main>