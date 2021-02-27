<?php
require_once 'config.php';
if(isset($_SESSION['user_id'])){
    echo "
        <div>{$_SESSION['firstname']} {$_SESSION['lastname']}</div>
        <img src='images/200x200/{$_SESSION['image']}' alt=''>
        <a href='logout.php'>Izlogoties</a>
    ";
} else {
    header('Location: login.php');
}
?>
