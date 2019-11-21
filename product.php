<?php 
logged_in();
template_header('product');
template_footer();

if (isset($_GET['id'])) {

    $stmt = "SELECT * FROM products WHERE idproduct = '".$_GET['id']."'";
    $result = mysqli_query($conn, $stmt);
    $product = mysqli_fetch_array($result);

    if (!$product) {
        die ('Product does not exist');
    }
} else {
    die ('Product does not exist');
}

$uname = $_SESSION['uname'];

//find user ID
$query_getuid= "select iduser from users where username='".$uname."'";
$result_uid = mysqli_query($conn, $query_getuid);
$uid = mysqli_fetch_array($result_uid);

if(isset($_POST['add'])){
    
    //Search if user has a cart. If no is found one is created.
    $count_query = "select count(*) as cntUser from carttouser where iduser='".$uid['iduser']."'";
    $result = mysqli_query($conn, $count_query);
    $row = mysqli_fetch_array($result);
    $count = $row['cntUser'];
    
    if($count == 0){
        $query_adduser = "INSERT INTO carttouser (iduser) VALUES ('".$uid['iduser']."')";
        $result_add = mysqli_query($conn,$query_adduser);
    }
    
    //Fin Cart ID for the user
    $query_getCartID = "SELECT idcart from carttouser where iduser='".$uid['iduser']."' ";
    $result = mysqli_query($conn, $query_getCartID);
    $cartid = mysqli_fetch_array($result);
    
    //Adding the product to the cart that belongs to the user, if product already in cart update the amount
    $add_product_query="INSERT INTO cartproducts (idcart, idproduct, amount)
                                VALUES ('".$cartid['idcart']."', '".$_POST["idproduct"]."', '1')
                                ON DUPLICATE KEY UPDATE amount = amount + 1";
    $result_add = mysqli_query($conn,$add_product_query);
}
?>

<div class="product">
    <form method="post">
        <h1><?php echo $product["name"]?></h1><br>
        <?php echo $product["price"]." :-"?><br>
        <input type="submit" name="add" value="Add to cart"><br>
        <?php echo $product["description"]?><br>
        <input type="hidden" name="iduser" value="<?php echo $uid["iduser"]?>">
    	<input type="hidden" name="idproduct" value="<?php echo $product["idproduct"]?>">
    </form>
</div>