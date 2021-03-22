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
    <!-- <script>
        window.onscroll = function(){
            if (document.body.scrollTop > 40 || document.documentElement.scrollTop > 40) {
                $("nav").css("height","50px");
                $(".nav-links li a").css("padding","10px 6px");
            } else {
                $("nav").css("min-height","10vh");
                $(".nav-links li a").css("padding","0");
            }
        }
    </script> -->
</head>
<body>
    <nav>
        <div class="logo">
            <a href="index.php"><img id="logo" src="images/logo.png"></a>
        </div>
        <ul class="nav-links">
            <li>
                <a href="index.php">Sākums</a><!--Uz :hover varētu bounce animation-->
            </li>
            <li>
                <a href="#">Pasākumi</a>
            </li>
            <li>
                <a href="#">Par mums</a>
            </li>
            <li>
                <?php echo "<a href='logout.php' id='logout-btn'>Izlogoties</a>"; ?>
            </li>
            <li id="user-pic">
                <?php echo "<img id='profile-pic' src='images/30x30/{$_SESSION['user']['image']}'>"; ?>
            </li>
            <li>
                <a href="#"><?php echo "{$_SESSION['user']['firstname']} {$_SESSION['user']['lastname']}"; ?></a>
            </li>
        </ul>
        <div class="burger">
            <div class="line1"></div>
            <div class="line2"></div>
            <div class="line3"></div>
        </div>
    </nav>

    <script>
        const burger = document.querySelector('.burger');
        const nav = document.querySelector('.nav-links');
        const navLinks = document.querySelectorAll('.nav-links li');

        //Toggle Nav
        burger.onclick = function(){
            //Slaido navigāciju
            nav.classList.toggle('nav-active');

            //Animate Links
            navLinks.forEach((link, index) => {
                if(link.style.animation){
                    link.style.animation = '';
                }else{
                    link.style.animation = `navLinkFade 0.5s ease forwards ${index / 7 + 0.5}s`;
                    //0.5 aizturi vajag, jo 0.5s ieslaido nav fons
                }
            });

            //Burger animation
            burger.classList.toggle('burgerX');
        }
    </script>
</body>
<main>