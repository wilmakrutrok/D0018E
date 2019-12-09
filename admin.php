<?php
  admin_verify();
  template_header('admin');
  if(isset($_POST['submit'])){
        $file = $_FILES['image'];
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileSize = $_FILES['image']['size'];
        $fileError = $_FILES['image']['error'];
        $fileType = $_FILES['image']['type'];
        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        if($fileError === 0){
          $fileNameNew = uniqid('',true).".".$fileActualExt;
          $fileDestination ='/'.$fileNameNew;
         move_uploaded_file($fileTmpName, $fileNameNew);
         header("Location: index.php?page=admin");
        }else{
          echo"There is something wrong with the file!";
        }
        $stmt =$conn->prepare("INSERT INTO products(price, inventory, name, description, image) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param('iisss',$_POST['price'], $_POST['inventory'], $_POST['name'], $_POST['description'], $fileNameNew);
        $stmt->execute();
        header('Location: index.php?page=admin');
}/*
if(isset($_POST["edit"]) && $_POST["newinventory"] != ""){
    $stmt = "
            UPDATE products
            SET inventory = '".$_POST['newinventory']."'
            WHERE idproduct = '".$_POST['idproduct']."'";
    $result = mysqli_query($conn, $stmt);
}

if(isset($_POST["edit"]) && $_POST["newname"] != ""){
    $stmt = "
            UPDATE products
            SET name = '".$_POST['newname']."'
            WHERE idproduct = '".$_POST['idproduct']."'";
    $result = mysqli_query($conn, $stmt);
}

if(isset($_POST["edit"]) && $_POST["newprice"] != ""){
    $stmt = "
            UPDATE products
            SET price = '".$_POST['newprice']."'
            WHERE idproduct = '".$_POST['idproduct']."'";
    $result = mysqli_query($conn, $stmt);
}

if(isset($_POST["delete"])){
    $stmt = "DELETE FROM products
            WHERE idproduct = '".$_POST['idproduct']."'";
    $result = mysqli_query($conn, $stmt);
}

if(isset($_POST["delete_review"])){
    $delete_review = "DELETE FROM review
            WHERE idreview = '".$_POST['idreview']."'";
    $review_deleted = mysqli_query($conn, $delete_review);
}*/
if(isset($_POST["edit"]) && $_POST["newinventory"] != ""){
    $stmt = $conn->prepare("
            UPDATE products
            SET inventory = ?
            WHERE idproduct = ?");
    $stmt->bind_param('ii',$_POST['newinventory'],$_POST['idproduct'] );
    $stmt->execute();
}

if(isset($_POST["edit"]) && $_POST["newname"] != ""){
    $stmt =$conn->prepare("
            UPDATE products
            SET name = ?
            WHERE idproduct = ?");
    $stmt->bind_param('si',$_POST['newname'], $_POST['idproduct']);
    $stmt->execute();
}

if(isset($_POST["edit"]) && $_POST["newprice"] != ""){
    $stmt = $conn->prepare("
            UPDATE products
            SET price = ?
            WHERE idproduct = ?");
    $stmt->bind_param('ii',$_POST['newprice'], $_POST['idproduct']);
    $stmt->execute();
}

if(isset($_POST["delete"])){
    $stmt = $conn -> prepare("DELETE FROM products
            WHERE idproduct = ?");
    $stmt->bind_param('i', $_POST['idproduct']);
    $stmt->execute();
}


if(isset($_POST["delete_review"])){
    $stmt = $conn->prepare("DELETE FROM review
            WHERE idreview = ?");
    $stmt->bind_param('i',$_POST['idreview']);
    $stmt->execute();
}
?>
<div class="container" style="margin-bottom: 600px">
    <div class="div_cart_content">
    	<div class="checkout_page">
        	<div class="checkout_page_center">
     			<h4>INSERT NEW PRODUCT</h4>
    		</div>
    	</div>
          <table class="admin_table">
            <form action="" method="post" enctype="multipart/form-data">
            <th>
              <tr>
                <td class="admin_add_image"><h4>Upload Picture</h4></td>
                <td><h4>Product Name</h4></td>
                <td><h4>Set price</h4></td>
                <td><h4>Set inventory</h4></td>
                <td><h4>Description</h4></td>
              </tr>
            </th>
            <tbody>
              <tr>
                <td><input type="file" name="image"></td>
                <td><input type="text" name="name"></td>
                <td><input type="text" name="price"></td>
                <td><input type="text" name="inventory"></td>
                <td><input type="text" name="description"></td>
              </tr>
              <tr>
                <td> <input type="submit" value="Upload Product:" name="submit"></td>
              </tr>
            </tbody>
             </form>
          </table>
	</div>


    <div class="products">
    <h1>Edit product</h1>
    	<ul>
        <?php
            $query_producttable = $conn->prepare("SELECT name, description, price, inventory, idproduct, image FROM products");
            $query_producttable->execute();
            $result_producttable = $query_producttable->get_result();
        	if ($result_producttable->num_rows > 0) {
    
        	    while($product = $result_producttable->fetch_assoc()) {
                    ?>
                    <li>
                    	<form method="post">
            	        	<img src="<?php echo $product["image"]?>"><br>
            	        	<input type="text" name="newname" placeholder="<?php echo $product["name"]?>"><br>
            	        	<input type="text" name="newprice" placeholder="<?php echo $product["price"]." :-"?>"><br>
            	        	<input type="text" name="newinventory" placeholder="<?php echo $product["inventory"]." in stock"?>"><br>
            	        	<input type="hidden" name="idproduct" value="<?php echo $product["idproduct"]?>"><br>
            	        	<input type="submit" value="Edit" name="edit">
            	        	<input type="submit" value="Delete product" name="delete">
        	        	</form>
        	        </li>
      			<?php 
        	    }
    
        	}
            ?>
            </ul>
    </div> 
    
     <div class="review" style="margin-top: 300px">
    	<h2>Reviews</h2>
    	<ul>
        <?php 
            $query_reviews = $conn->prepare("SELECT * FROM review");
            $query_reviews->execute();
        	$result_reviews = $query_reviews->get_result();
        	
        	if ($result_reviews->num_rows > 0) {
    
        	    while($review = $result_reviews->fetch_assoc()) {
                    ?>
                    <li>
        	        	Rate: <?php echo $review["grade"]?><br>
        	        	Comment: <?php echo $review["comment"]?><br>
        	        	<form method="post">
        	        	<input type="hidden" value="<?php echo $review["idreview"]?>" name="idreview">
        	        	<input type="submit" value="Delete" name="delete_review">
        	        	</form>
        	        </li>
      			<?php 
        	    }
    
        	}
            ?>
            </ul>
            
    </div>
</div>
<?php template_footer();?>