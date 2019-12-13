<?php
//Change session for user and admin so they can't reach pages anymore without loggin in
$_SESSION['loggedin'] = false;
$_SESSION['admin'] = false;
header('Location: index.php');
?>
