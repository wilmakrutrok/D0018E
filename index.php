<?php 
session_start();
include "config.php";

if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'home';
} else {
    $page = isset($_GET['page']) && file_exists($_GET['page'] . '.php') ? $_GET['page'] : 'login';
}

include $page . '.php';
?>