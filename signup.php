<?php 
include "config.php";
if(isset($_POST['submit'])){
    /*$uname = mysqli_real_escape_string($conn,$_POST['username']);
    echo $uname;
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $password2 = mysqli_real_escape_string($conn,$_POST['password2']);*/
    $uname=$_POST['username'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];
    if ($password != $password2){
        echo"Password does not match";
    }elseif ($uname != "" && $password != "") {
         $sql_query = "select count(*) as cntUser
          from users where username='".$uname."'";
          $result = mysqli_query($conn,$sql_query);
          $row = mysqli_fetch_array($result);
          $count = $row['cntUser'];
          if($count > 0){
            echo'Username already exists.';
          }
          else{
            $sql_query="INSERT INTO users (username, password)
                        VALUES ('".$uname."', '".$password."')";
            $newuser_add = mysqli_query($conn,$sql_query);
            header('Location: index.php');
          }

    }else {
		echo "No username or password given ";
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
        	<h1>Sign up</h1>
        	<form action="" method="post">
                Username:<br>
                <input type="text" name="username" id="signup_uname" placeholder="Enter Username"><br>
                Password:<br>
                <input type="text" name="password" id="signup_pwd" placeholder="Enter Password"><br>
                Confirm Password:<br>
                <input type="text" name="password2" id="signup_pwd2" placeholder="Confirm Password"><br><br>
                <input type="submit" value="Signup" name="submit">
            </form>
            <p>Already have an account? <a style="color:gray;">Login now.</a></p>
        </div>
    </body>
</html>