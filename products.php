<?php 
template_header('products');
template_footer();
?>

	<h1>Products</h1>
    <div class="container">
    <?php 
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
     