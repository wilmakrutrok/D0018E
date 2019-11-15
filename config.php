<?php 
    $servername = "utbweb.its.ltu.se";
    $user = "980728";
    $password = "123wow234";
    
    $conn = new mysqli($servername, $user, $password);
        if($conn->connect_error){
            die("Connection failed: ". $conn->connect_error);
        }
    
    $db_name = "db980728";
    $db = mysqli_select_db($conn, $db_name);     

    function template_header($title){
        
   echo '
    <!DOCTYPE html>
    	<head>
    		<title>The store</title>
    		<link rel="stylesheet" href="style.css">
    	</head>
        <body>
        	<header>
            	<ul>
            		<li><a href="index.php">Home</a></li>
            		<li><a href="index.php?page=products">Products</a></li>
            		<li style="margin-left: 25%;"><a href="index.php" style="color: black;">The store</a></li>
            		<li style="float: right"><a href="index.php?page=checkout">Checkout</a></li>
            	</ul>
            </header>
            <main>
        ';
    }
    function template_footer(){
        echo ' 
            </main>
            <footer>
        		<p>Contact information</p>
        	</footer>
        </body>
    </html>
    ';
    }
?>