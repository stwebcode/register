<?php
require_once 'config.php';
if(!isset($_SESSION['user_id'])):
    header('Location: login.php'); 
else: ?>
<?php include_once "header.php"; ?>

<?php include_once "footer.php"; ?>
<?php endif; ?>