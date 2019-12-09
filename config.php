<?php 
    $servername = "localhost";
    $user = "root";
    $password = "";
    
    $conn = new mysqli($servername, $user, $password);
        if($conn->connect_error){
            die("Connection failed: ". $conn->connect_error);
        }
    
    $db_name = "d0018e";
    $db = mysqli_select_db($conn, $db_name);     

    function logged_in(){
        if($_SESSION['loggedin'] != true){
            header('Location: index.php');
        }
    }
    function admin_verify(){
        if($_SESSION['admin'] != true){
            header('Location: index.php');
        }
    }
    
    function template_header($title){
        /*
         if(isset($_POST['submit'])){
         $_SESSION['loggedin'] = false;
         header('Location: index.php');
         }
         */
        echo '
    <!DOCTYPE html>
    	<head>
    		<title>The store</title>
    		<link rel="stylesheet" href="style.css">
            <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    	</head>
        <body>
        	<header>
            <div class="nav_bar">
            	<ul>
                    <li><a href="index.php?page=home">Home</a></li>
            		<li><a href="index.php?page=products">Products</a></li>
            	    <li class="header_center"><a href="index.php?page=home">The store</a></li>
                    <li class="header_right"><a href="index.php?page=logout">Log out</a></li>
            		<li class="header_right"><a class="header_right" href="index.php?page=checkout">Checkout</a></li>
                    <li class="header_right"><a class="header_right" href="index.php?page=user">User</a></li>
            	</ul>
            </div>
            </header>
            <main>
        ';
    }
    function template_footer(){
        echo '
            </main>
            <footer>
                <ul>
                    <li><p>Contact information</p></li>
                    <li class="footer_admin"><a href="index.php?page=admin">Admin</a></li>
                </ul>
        	</footer>
        </body>
    </html>
    ';
    }
    
    //Special header for login and signup to have an opacity and no working links
    function template_header_login($title){
        echo '
    <!DOCTYPE html>
    	<head>
    		<title>The store</title>
    		<link rel="stylesheet" href="style.css">
    	</head>
        <body>
        	<header style="opacity: 0.3">
            	<ul>
            		<li><a>Home</a></li>
            		<li><a>Products</a></li>
            		<li class="header_center"><a>The store</a></li>
            		<li class="header_right"><a>Log out</a></li>
            		<li class="header_right"><a class="header_right">Checkout</a></li>
                    <li class="header_right"><a class="header_right">User</a></li>
            	</ul>
            </header>
            <main>
            <div id="background" style="opacity: 0.3">
                <h1>The store</h1>
            </div>
        ';
    }
    
    function admin_menu(){
    echo '
    <h1 style="text-align: center; margin-top: 100px;">Admin page</h1>
    <ul style="width: 410px; margin: auto;">
        <li style="text-align: center"><a href="index.php?page=admin">Home</a></li>
        <li style="text-align: center"><a href="index.php?page=adminNewProduct">Add product</a></li>
        <li style="text-align: center"><a href="index.php?page=adminEditProduct">Edit product</a></li>
        <li style="text-align: center"><a href="index.php?page=adminDeleteReview">Reviews</a></li>
    </ul><br><br>';
    }
    ?>