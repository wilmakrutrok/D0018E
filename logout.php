<?php
    $_SESSION['loggedin'] = false;
    $_SESSION['admin'] = false;
    header('Location: index.php');
?>