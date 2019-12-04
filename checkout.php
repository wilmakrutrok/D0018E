<?php
logged_in();
template_header('checkout');

//Hämtar först ut användarens id
$uname = $_SESSION['uname'];
$iduser = $_SESSION['iduser'];

/*$query_getuid= "select iduser from users where username='".$uname."'";
$result_uid = mysqli_query($conn, $query_getuid);
$uid = mysqli_fetch_array($result_uid);*/

//Här hämtar jag ut alla produkter som finns i kundkorgen.
//Priset borde hämtas från cartproductstabellen men den är
//null där så hämtar från products direkt för nu
$query_cart = "
  SELECT  products.description, products.image, products.idproduct, products.name, products.price, products.inventory, carttouser.idcart, cartproducts.amount
  FROM carttouser
  INNER JOIN cartproducts
    ON carttouser.idcart = cartproducts.idcart
  INNER JOIN products
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = '".$iduser."'";
$result_cart = $conn->query($query_cart);
//Enters when pay button is pressed
if(isset($_POST['pay_button'])){
    $conn->autocommit(false);
    //Create a new order. IDorder is incremented automatically
    //so each new order is unique
    $todaysdate = date("Y-m-d");
    $query_create_order = $conn->prepare("INSERT INTO orders(iduser, totalprice, date)
                                        VALUES(?, ?, ?)");
    $query_create_order->bind_param('ids',$iduser, $_POST['totalprice'], $todaysdate);
    $query_create_order->execute();
    
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
  WHERE carttouser.iduser = '".$iduser."'";
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
    $delete_cart = $conn->prepare("
  DELETE  cartproducts
  FROM cartproducts
  INNER JOIN carttouser
  ON carttouser.idcart = cartproducts.idcart
  WHERE carttouser.iduser = ?");
    $delete_cart->bind_param('i',$iduser);
    $delete_cart->execute();
    $conn->commit();
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


  <div class="container_cart" style="margin-bottom:200px">
    <div class="div_cart_content">
      <div class="checkout_page">
        <div class="checkout_page_center">
      <h4>CHECKOUT</h4>
    </div>
    </div>
    <table class="checkout_table">
      <thead class="cart_header">
     <tr>
        <td class="cart_products">Item</td>
        <td>Price</td>
        <td>Quantity</td>
        <td>Total</td>
     </tr>
   </thead>
    <tbody>
      <?php
        //Everything from the cart is displayed in different cells a new row for every product.
      if($result_cart->num_rows > 0){
        $total_price = 0;
        while($cart = $result_cart->fetch_assoc()){
      ?>
      <tr>
        <td class="cart_image"><img class="cart_image2" style="height: 70px" src="<?php echo $cart["image"]?>"></td>
        <td>
            <?php echo $cart["price"]?>
          </td>
          <td>
            <?php echo $cart["amount"]?>
            <form method="post">
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
              <td class="total_row"><h4 class="total_h4"><?php $total_product = $cart["price"] * $cart["amount"];
              $total_price = $total_price + $total_product;
              echo $total_product ?></h4>
        </td>
        <td> <form method="post" style="float: right">
                <input type="hidden" name="idcart" value="<?php echo $cart["idcart"]?>">
              <input type="hidden" name="idproduct" value="<?php echo $cart["idproduct"]?>">
              <input type="submit" name="remove" value="Remove">
            </form></td>
        <?php } ?>
        <!-----While-loop Ends------>
         </tr>
         <tr class="total">
          <td class="total"><h4>Total Price: <?php  echo $total_price ?></h4> </td>
         </tr>
         <tr><td>
         <form method="post">
          <input type="hidden" name="totalprice" value="<?php echo $total_price?>">
          <input type="submit" name="pay_button" value="Pay"></button></form></td>
         </tr>
    </tbody>
         <?php } $conn->close(); ?>
  </table>
</div>
</div>
  </div>
<?php template_footer();?>