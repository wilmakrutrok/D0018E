<?php 
logged_in();
template_header('product');


if (isset($_GET['id'])) {

    $stmt = $conn->prepare("SELECT * FROM products WHERE idproduct = ?");
    $stmt -> bind_param('i', $_GET['id']);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $product = mysqli_fetch_array($result);

    if (!$product) {
        die ('Product does not exist');
    }
} else {
    die ('Product does not exist');
}

$uname = $_SESSION['uname'];

//find user ID
/*$query_getuid= "select iduser from users where username='".$uname."'";
$result_uid = mysqli_query($conn, $query_getuid);
$uid = mysqli_fetch_array($result_uid);*/

//Get user ID
$iduser = $_SESSION['iduser'];

if(isset($_POST['add'])){
    //Chech so enough in inventory
    if($_POST["amount"] <= $product['inventory']){
        //Search if user has a cart. If no is found one is created.
        $count_query = $conn->prepare("select count(*) as cntUser from carttouser where iduser= ?");
        $count_query -> bind_param('i', $iduser);
        $count_query -> execute();
        $result = $count_query -> get_result();
        //$row = mysqli_fetch_array($result);
        $row = $result -> fetch_assoc();
        $count = $row['cntUser'];
        
        if($count == 0){
            $query_adduser = $conn -> prepare("INSERT INTO carttouser (iduser) VALUES (?)");
            $query_adduser -> bind_param('i', $iduser);
            $query_adduser -> execute();
            $result_add = $query_adduser -> get_result();
        }
        
        //Find Cart ID for the user
        $query_getCartID = $conn -> prepare("SELECT idcart from carttouser where iduser= ?");
        $query_getCartID -> bind_param('i', $iduser);
        $query_getCartID -> execute();
        $result = $query_getCartID -> get_result();
        //$cartid = mysqli_fetch_array($result);
        $cartid = $result -> fetch_assoc();
        //Adding the product to the cart that belongs to the user, if product already in cart update the amount
        $add_product_query = $conn -> prepare("INSERT INTO cartproducts (idcart, idproduct, amount, price)
                                    VALUES (?, ?, ?, ?)
                                    ON DUPLICATE KEY UPDATE amount = 
                                    IF((amount + ?) <= ?, amount + ?, amount)");
        $add_product_query -> bind_param('iiidiii', $cartid['idcart'],  $_POST["idproduct"], $_POST["amount"], $product['price'], $_POST["amount"], $product['inventory'], $_POST["amount"]);
        $add_product_query -> execute();
        $result_add = $add_product_query ->get_result();
    }else{
        echo "Not enough in inventory";
    }
}

if (isset($_POST["send_review"])) {
    //Check if rate and comment given
    if(isset($_POST["rate"]) && $_POST["review"] != ""){
        $query_adduser = $conn -> prepare("INSERT INTO review (idproduct, grade, comment) VALUES (?, ?, ?)");
        $query_adduser -> bind_param('iis', $product['idproduct'], $_POST["rate"], $_POST["review"]);
        $query_adduser -> execute();
        $result_add = $query_adduser -> get_result();
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

        <?php echo $product["price"]." :-"?><br>
          <select name="amount">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>
          <p>Inventory: <?php echo $product['inventory']?>
          <br><br>       
        <input type="submit" name="add" value="Add to cart"><br>
        <?php echo $product["description"]?><br>
        
    	<input type="hidden" name="idproduct" value="<?php echo $product["idproduct"]?>">
    	<input type="hidden" name="price" value="<?php echo $product["price"]?>">
    </form>
</div>
<br><br>
<div class="review">
	<h2>Reviews</h2>
	<ul>
    <?php 
        
    $query_reviews = $conn -> prepare("SELECT comment, grade FROM review WHERE idproduct = ?");
    $query_reviews -> bind_param('i', $product["idproduct"]);
    $query_reviews -> execute();
    	$result_reviews = $query_reviews -> get_result();
    	
    	if ($result_reviews->num_rows > 0) {
    	    while($review = $result_reviews->fetch_assoc()) {
                ?>
                <li>
    	        	Rate: <?php echo $review["grade"]?><br>
    	        	Comment: <?php echo $review["comment"]?>
    	        </li>
  			<?php 
    	    }
    	    $conn->close();
    	}
        ?>
        </ul>
        <br><br><br>
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