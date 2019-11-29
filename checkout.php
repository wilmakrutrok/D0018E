<?php 
  logged_in();
  template_header('checkout');
  
  //Hämtar först ut användarens id
  $uname = $_SESSION['uname'];
  $query_getuid= "select iduser from users where username='".$uname."'";
  $result_uid = mysqli_query($conn, $query_getuid);
  $uid = mysqli_fetch_array($result_uid);
  //Här hämtar jag ut alla produkter som finns i kundkorgen. 
  //Priset borde hämtas från cartproductstabellen men den är 
  //null där så hämtar från products direkt för nu
  $query_cart = "
  SELECT  products.idproduct, products.name, products.price, products.inventory, carttouser.idcart, cartproducts.amount 
  FROM carttouser 
  INNER JOIN cartproducts 
    ON carttouser.idcart = cartproducts.idcart 
  INNER JOIN products 
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = '".$uid['iduser']."'";
  $result_cart = $conn->query($query_cart);
  //Enters when pay button is pressed
  if(isset($_POST['pay_button'])){  
  //Create a new order. IDorder is incremented automatically 
  //so each new order is unique
  $query_create_order = "INSERT INTO orders(iduser)
  VALUES('".$uid['iduser']."')";
  mysqli_query($conn, $query_create_order);
  //Use the generated IDorder to update orderproducts
  $order_id = $conn->insert_id;
  //Inserting products from cart to orders
  $stmt =$conn->prepare("INSERT INTO orderproducts
  SELECT orders.idorder, cartproducts.idproduct, cartproducts.amount, cartproducts.price
  FROM orders 
  INNER JOIN carttouser
  on orders.iduser = carttouser.iduser
  INNER JOIN cartproducts 
    ON carttouser.idcart = cartproducts.idcart 
  INNER JOIN products 
    ON cartproducts.idproduct = products.idproduct
  WHERE orders.idorder = ?");
  $stmt->bind_param('i', $order_id);
  $stmt->execute();
  
  //Change inventory
  $query_products_in_cart = "
  SELECT  products.idproduct, cartproducts.amount
  FROM carttouser
  INNER JOIN cartproducts
    ON carttouser.idcart = cartproducts.idcart
  INNER JOIN products
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = '".$uid['iduser']."'";
  $result_products_in_cart = $conn->query($query_products_in_cart);
  if($result_products_in_cart->num_rows > 0){
      while($cart = $result_products_in_cart->fetch_assoc()){
          $change_inventory = "
              UPDATE products
              SET inventory = inventory - '".$cart['amount']."'
              WHERE idproduct = '".$cart["idproduct"]."'";
          mysqli_query($conn, $change_inventory);
      }
  }
  
  //Emptying all the products in the cart
  $delete_cart = "
  DELETE  cartproducts 
  FROM cartproducts 
  INNER JOIN carttouser 
  ON carttouser.idcart = cartproducts.idcart 
  WHERE carttouser.iduser = '".$uid['iduser']."'";
  mysqli_query($conn, $delete_cart);
  //Sends the user back to checkout page
  header('Location: index.php?page=checkout');
  
}

if(isset($_POST['change'])){
    //Chech so enough in inventory
    if($_POST["amount"] <= $_POST['inventory']){
        //Adding the product to the cart that belongs to the user, if product already in cart update the amount
        $add_product_query="UPDATE cartproducts
                            SET amount = '".$_POST["amount"]."' 
                            WHERE idcart = '".$_POST['idcart']."' AND idproduct = '".$_POST["idproduct"]."'";
        $result_add = mysqli_query($conn,$add_product_query);
        header('Location: index.php?page=checkout');
    }else{
        echo "Not enough in inventory";
    }
}

if(isset($_POST['remove'])){
        //Adding the product to the cart that belongs to the user, if product already in cart update the amount
        $delete_product_query="DELETE FROM cartproducts
                            WHERE idcart = '".$_POST['idcart']."' AND idproduct = '".$_POST["idproduct"]."'";
        $result_delete = mysqli_query($conn,$delete_product_query);
        header('Location: index.php?page=checkout');
}
?>
<!--<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
-->

  <div class="container">
    <table>
      <th>
     <tr>
       <td><h4><i class="fa fa-shopping-cart"></i> Products: </h4></span></td>
        <td><h4>Price: </h4></td>
        <td><h4>Quantity: </h4></td>
        <td><h4>Total: </h4></td>
     </tr>
   </th>
    <tbody>
      <?php
        //Everything from the cart is displayed in different cells a new row for every product.
      if($result_cart->num_rows > 0){
        $total_price = 0;
        while($cart = $result_cart->fetch_assoc()){
      ?>
      <tr>
        <td><?php echo $cart["name"]?></td>
        <td><?php echo $cart["price"];?></td>
        <!--
          Ändra button så att det är input och kan ändra kvantitet i sin kundkorg
        -->
        <td><?php echo $cart["amount"]?>
        <form method="post" style="float:right">
        	<select name="amount">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
         	</select>
         	<input type="hidden" name="idcart" value="<?php echo $cart["idcart"]?>">
         	<input type="hidden" name="idproduct" value="<?php echo $cart["idproduct"]?>">
         	<input type="hidden" name="inventory" value="<?php echo $cart["inventory"]?>">
         	<input type="submit" name="change" value="Change">
         </form>
         </td>
        <td class="total"><?php $total_product = $cart["price"] * $cart["amount"]; 
              $total_price = $total_price + $total_product;
              echo $total_product ?>
            <form method="post" style="float: right">
                <input type="hidden" name="idcart" value="<?php echo $cart["idcart"]?>">
             	<input type="hidden" name="idproduct" value="<?php echo $cart["idproduct"]?>">
          		<input type="submit" name="remove" value="Remove">
          	</form>
        </td>
        <?php } ?>
        <!-----While-loop Ends------>
         </tr>
         <tr>
          <td class="total"><h4>Total Price:</h4> </td>
          <td class = "total"><?php  echo $total_price ?></td>
         </tr>
         <tr><td>
         <form method="post">
          <input type="submit" name="pay_button" value="Pay"></button></form></td>
         </tr>
    </tbody>
         <?php } $conn->close(); ?> 
  </table>
  </div>
  </div>
<?php template_footer();?>