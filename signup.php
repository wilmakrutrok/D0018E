<?php 
template_header_login('signup');
template_footer();

if(isset($_POST['submit'])){
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
            $sql_query2="INSERT INTO users (username, password)
                        VALUES ('".$uname."', '".$password."')";
            $newuser_add = mysqli_query($conn,$sql_query2);
            header('Location: index.php?page=home');
          }
    }else {
		echo "No username or password given ";
	}
}
?>

    <div class="login">
    	<h1>Sign up</h1>
    	<form action="" method="post">
            Username:<br>
            <input type="text" name="username" id="signup_uname" placeholder="Enter Username"><br>
            Password:<br>
            <input type="text" name="password" id="signup_pwd" placeholder="Enter Password"><br>
            Confirm Password:<br>
            <input type="text" name="password2" id="signup_pwd2" placeholder="Confirm Password"><br><br>
            <input type="submit" value="Sign up" name="submit">
        </form>
        <p>Already have an account? <a href="index.php?page=login" style="color:gray;">Login now.</a></p>
    </div>
       