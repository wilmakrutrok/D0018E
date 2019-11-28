<?php 
logged_in();
template_header('product');


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
    
    //Find Cart ID for the user
    $query_getCartID = "SELECT idcart from carttouser where iduser='".$uid['iduser']."' ";
    $result = mysqli_query($conn, $query_getCartID);
    $cartid = mysqli_fetch_array($result);
    
    //Adding the product to the cart that belongs to the user, if product already in cart update the amount
    $add_product_query="INSERT INTO cartproducts (idcart, idproduct, amount, price)
                                VALUES ('".$cartid['idcart']."', '".$_POST["idproduct"]."', '".$_POST["amount"]."', '".$product['price']."')
                                ON DUPLICATE KEY UPDATE amount = amount + '".$_POST["amount"]."'";
    $result_add = mysqli_query($conn,$add_product_query);
}


if (isset($_POST["send_review"])) {
    //Check if rate and comment given
    if(isset($_POST["rate"]) && $_POST["review"] != ""){
    $query_adduser = "INSERT INTO review (idproduct, grade, comment) VALUES ('".$product['idproduct']."', '".$_POST["rate"]."', '".$_POST["review"]."')";
    $result_add = mysqli_query($conn,$query_adduser);
    }
    else{
        echo "No rate or no comment given";
    }
}
?>

<div class="product">
	<img src="<?php echo $product["image"]?>">
    <form method="post">
        <h1><?php echo $product["name"]?></h1>
        <!-- <div class="rate">
            <input type="radio" id="five_star" name="rate" value="5">
            <label for="star5">★</label>
            <input type="radio" id="four_star" name="rate" value="4">
            <label for="star4">★</label>
            <input type="radio" id="three_star" name="rate" value="3">
            <label for="star3">★</label>
            <input type="radio" id="two_star" name="rate" value="2">
            <label for="star2">★</label>
            <input type="radio" id="one_star" name="rate" value="1">
            <label for="star1">★</label>
 		</div> <br><br><br>-->

        <?php echo $product["price"]." :-"?><br>
          <select name="amount">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
          <br><br>       
        <input type="submit" name="add" value="Add to cart"><br>
        <?php echo $product["description"]?><br>
        <input type="hidden" name="iduser" value="<?php echo $uid["iduser"]?>">
    	<input type="hidden" name="idproduct" value="<?php echo $product["idproduct"]?>">
    	<input type="hidden" name="price" value="<?php echo $product["price"]?>">
    </form>
</div>
<br><br>
<div class="review">
	<h2>Reviews</h2>
	<ul>
    <?php 
        
    $query_reviews = "SELECT comment, grade FROM review WHERE idproduct = '".$product["idproduct"]."'";
    	$result_reviews = $conn->query($query_reviews);
    	
    	if ($result_reviews->num_rows > 0) {

    	    while($review = $result_reviews->fetch_assoc()) {
                ?>
                <li>
                    Name: <?php echo $uname?><br>
    	        	Rate: <?php echo $review["grade"]?><br>
    	        	Comment: <?php echo $review["comment"]?>
    	        </li>
  			<?php 
    	    }
    	    $conn->close();
    	}
        ?>
        </ul>
    <h3>Leave a review</h3>
    	<form method="post">
    	<div class="rate">
    		<input type="radio" id="star5" name="rate" value="5">
            <label for="star5">★</label>
            <input type="radio" id="star4" name="rate" value="4">
            <label for="star4">★</label>
            <input type="radio" id="star3" name="rate" value="3">
            <label for="star3">★</label>
            <input type="radio" id="star2" name="rate" value="2">
            <label for="star2">★</label>
            <input type="radio" id="star1" name="rate" value="1">
            <label for="star1">★</label>
        </div><br><br>
    		<textarea name="review" rows="5" cols="40" placeholder="What did you think about the product?"></textarea><br>
            <input type="submit" value="Send" name="send_review">
        </form>
</div>
<?php template_footer();?>
<style>

.rate {
    float: left;
}
.rate input {
    position:fixed;
    opacity:0

}
.rate label {
    float:right;
    font-size:25px;
    color:#ccc;
}

.rate input:checked ~ label {
    color: #ffc700;    
}

.rate:not(:checked) label:hover,
.rate:not(:checked) label:hover ~ label {
    color: #ffd645;  
}
</style>