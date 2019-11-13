<?php 
include "config.php";
?>


<!DOCTYPE html>
	<head>
		<title>The store</title>
		<link rel="stylesheet" href="style.css">
	</head>
    <body>
    	<header>
        	<ul>
        		<li><a href="index.php">Home</a></li>
        		<li><a href="products.php">Products</a></li>
        		<li style="margin-left: 33%;"><a href="index.php" style="color: black;">The store</a></li>
        		<li style="float: right"><a href="checkout.php">Checkout</a></li>
        	</ul>
        </header>
        <main>
        <div id="background">
            <h1>The store</h1>
        </div>
        <div class="container">
        	<?php 
        	session_start();
        	$uname = $_SESSION['uname'];
        	
        	$query_getuid= "select iduser from Users where username='".$uname."'";
        	$result_uid = mysqli_query($conn, $query_getuid);
        	$uid = mysqli_fetch_array($result_uid);
        	
        	if(isset($_POST['add'])){
        	    $query_add = "INSERT INTO Cart (iduser, idproduct, numberof) VALUES ('".$uid['iduser']."', '".$_POST["idproduct"]."', '1')";
        	    $result_add = mysqli_query($conn,$query_add);
        	}
        	
        	$query_producttable = "SELECT name, description, price, inventory, idproduct FROM Products";
        	$result_producttable = $conn->query($query_producttable);
        	
        	if ($result_producttable->num_rows > 0) {
        	    // output data of each row
        	    while($product = $result_producttable->fetch_assoc()) {
        	        ?>
        	        <form method="post">
        	        	<?php echo $product["name"]?>
        	        	<?php echo $product["price"]?>
        	        	<?php echo $product["description"]?>
        	        	<input type="hidden" name="iduser" value="<?php echo $uid['iduser']?>">
						<input type="hidden" name="idproduct" value="<?php echo $product["idproduct"]?>">
        	        	<input type="submit" name="add" value="Add to cart">
        	        </form>
        	    <?php 
        	    }
        	    $conn->close();
        	}
        	    ?>  
        </div>  
        </main>
        <footer>
    		<p>Contact information</p>
    	</footer>
    </body>
</html>