<?php 
include "config.php";
session_start();
if(isset($_POST['submit'])){
    $uname = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    if ($uname != "" && $password != ""){

        $sql_query = "select count(*) as cntUser from Users where username='".$uname."' and password='".$password."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);

        $count = $row['cntUser'];

        if($count > 0){
            $_SESSION['uname'] = $uname;
            header('Location: index.php');
        }else{
            echo "Invalid username and password";
        }

    }
	else {
		echo "No username or password given";
	}
}
?>

<!DOCTYPE html>
	<head>
		<title>The store</title>
		<link rel="stylesheet" href="style.css">
	</head>
    <body>
        <div style="opacity: 0.1;">
        	<header>
            	<ul>
            		<li><a>Home</a></li>
            		<li><a>Products</a></li>
            		<li style="margin-left: 26%">The store</li>
            		<li style="float: right"><a>Checkout</a></li>
            	</ul>
            </header>
            <main>
                <div id="background">
            		<h1>The store</h1>
        		</div>
            </main>
            <footer>
        		<p>Contact information</p>
        	</footer>
        </div>
        <div class="login">
        	<h1>Log in</h1>
        	<form action="" method="post">
                Username:<br>
                <input type="text" name="username" id="login_uname" placeholder="Enter Username"><br>
                Password:<br>
                <input type="text" name="password" id="login_pwd" placeholder="Enter Password"><br><br>
                <input type="submit" value="Login" name="submit">
            </form>
            <p>Don't have an account? <a style="color:gray;">Sign up now.</a></p>
        </div>
    </body>
</html>