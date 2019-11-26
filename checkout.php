<!DOCTYPE html>
<?php 
logged_in();
template_header('checkout');
template_footer();
?>
<html>

<!--
    	<head>
    		<title>The store</title>
    		<link rel="stylesheet" href="style.css">
    	</head>
        <body>
        	<header>
            	<ul>
            		<li><a href="index.php">Home</a></li>
            		<li><a href="index.php?page=products">Products</a></li>
            		<li style="margin-left: 25%;"><a href="index.php" style="color: black;">The store</a></li>
            		<li style="float: right"><a href="index.php?page=checkout">Checkout</a></li>
            	</ul>
            </header>
            <main>

            </main>
            <footer>
        		<p>Contact information</p>
			<li style="margin-right: 100%;"><a ><input type="submit" value="pay" class="btn"></a></li>
			
			     
        	</footer>
        </body>
-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  font-family: Arial;
  font-size: 17px;
  padding: 8px;
}

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text] {
  width: 100%;
  margin-bottom: 20px;
  padding: 12px;
  border: 1px solid #ccc;
  border-radius: 3px;
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn {

  background-color: #45a049;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}


@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
</head>
<body>



        <!--</label>

      </form>
    </div>
  </div>
-->

  <div class="col-25">
    <div class="container">
      <h4>Cart <span class="price" style="color:black">
        <i class="fa fa-shopping-cart"></i> 
          <b>4</b></span>
      </h4>


  



<?php
      /*$query_producttable = "SELECT name, description, price, inventory, idproduct FROM products";
    	$result_producttable = $conn->query($query_producttable);
    	$total = 0;
    	if ($result_producttable->num_rows > 0) {
    	    while($product = $result_producttable->fetch_assoc()) {
                ?>
                
    	        <form method="post">
			$price = $product["price"];
    	        	<?php echo $product["name"]?>    	        	
			<?php echo $product["price"]." :-"?><br>
			$total = $price + $total
    	        </form>
    	      
  			<?php 
    	    }
    	    $conn->close();
    	}
      */

      //Hämtar först ut användarens id
      $uname = $_SESSION['uname'];
      $query_getuid= "select iduser from users where username='".$uname."'";
      $result_uid = mysqli_query($conn, $query_getuid);
      $uid = mysqli_fetch_array($result_uid);

      //Här hämtar jag ut alla produkter som finns i kundkorgen. Priset borde hämtas från cartproductstabellen men den är null där så hämtar från products direkt för nu
      $query_cart = "SELECT  products.name, products.price, cartproducts.amount FROM carttouser 
      INNER JOIN cartproducts ON carttouser.idcart = cartproducts.idcart 
      INNER JOIN products ON cartproducts.idproduct = products.idproduct
      WHERE carttouser.iduser = '".$uid['iduser']."'";
      $result_cart = $conn->query($query_cart);
      //Här skrivs allting från korgen ut
      if($result_cart->num_rows > 0){
        while($cart = $result_cart->fetch_assoc()){
          ?>
          <p><?php echo $cart["name"]?><span class="price"><?php echo $cart["price"] ?></span></p>
          <?php
        }
         $conn->close();
      }
    
?><!--
      <p><a href="#">Product 3</a> <span class="price">$8</span></p>
      <p><a href="#">Product 4</a> <span class="price">$2</span></p>
      <hr>
      <p>Total <span class="price" style="color:black"><b><?php $total?></b></span></p>
    --->
    </div>
  </div>


</body>
</html>