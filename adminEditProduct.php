<?php
admin_verify();
template_header('editProduct');

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
?>

<?php admin_menu(); ?>

<div class="products">
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
    
<?php template_footer();?>