<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "ajax");
mysqli_set_charset($con,"utf8");
date_default_timezone_set("Europe/Riga");
?>