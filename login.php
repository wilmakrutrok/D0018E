<?php
template_header_login('login');
if(isset($_POST['submit'])){
    $uname = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    
    //check if username and password is entered and searches
    //for a hit on the username.If there is a hit the password
    //is checked against the hashed password in the database.
    if ($uname != "" && $password != ""){
        $sql_query = "select count(*) as cntUser from users where username='".$uname."'";
        $result = mysqli_query($conn,$sql_query);
        $row = mysqli_fetch_array($result);
        $count = $row['cntUser'];
        if($count > 0){
            $sql_query = "select password, role from users where username='".$uname."'";
            $result = mysqli_query($conn,$sql_query);
            $row = mysqli_fetch_array($result);
            $hashed_pwd = $row['password'];
            $user_role = $row['role'];
            if (password_verify($password, $hashed_pwd)){
                //Get user id
                $query_getUid = "select iduser from users where username='".$uname."'";
                $result_getUid = mysqli_query($conn,$query_getUid);
                $uid = mysqli_fetch_array($result_getUid);
                $iduser = $uid['iduser'];
                $_SESSION['iduser'] = $iduser;
                
                $_SESSION['uname'] = $uname;
                if($row['role']=='user'){
                    $_SESSION['loggedin'] = true;
                    header('Location: index.php?page=home');
                }
                else if ($row['role']=='admin'){
                    $_SESSION['admin']=true;
                    $_SESSION['loggedin'] = true;
                    header('Location: index.php?page=admin');
                }
            }else{
                echo "Invalid Password";
            }
        }else{
            echo "Invalid Username";
        }
    }
    else {
        echo "No username or password given";
    }
}

?>

    <div class="login">
    	<h1>Log in</h1>
    	<form action="" method="post">
            Username:<br>
            <input type="text" name="username" id="login_uname" placeholder="Enter Username"><br>
            Password:<br>
            <input type="password" name="password" id="login_pwd" placeholder="Enter Password"><br><br>
            <input type="submit" value="Login" name="submit">
        </form>
        <p>Don't have an account? <a href="index.php?page=signup" style="color:gray;">Sign up now.</a></p>
    </div>
<?php template_footer();?>