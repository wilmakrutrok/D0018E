<?php 
    //session_start();
    $servername = "utbweb.its.ltu.se";
    $user = "980728";
    $password = "123wow234";
    
    $conn = new mysqli($servername, $user, $password);
        if($conn->connect_error){
            die("Connection failed: ". $conn->connect_error);
        }
    
    $db_name = "db980728";
    $db = mysqli_select_db($conn, $db_name);     
?>