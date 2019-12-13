<?php 
session_start();
include "config.php";

//Check if user has logged in to give access to web shop else direct to login page
if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
} else {
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'login';
}

include $page . '.php';
?>
