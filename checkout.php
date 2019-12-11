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
$query_cart = $conn->prepare("
  SELECT  products.description, products.image, products.idproduct, products.name, products.price, products.inventory, carttouser.idcart, cartproducts.amount
  FROM carttouser
  INNER JOIN cartproducts
    ON carttouser.idcart = cartproducts.idcart
  INNER JOIN products
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = ?");
$query_cart->bind_param('i', $iduser);
$query_cart->execute();
$result_cart = $query_cart->get_result();

//Enters when pay button is pressed
if(isset($_POST['pay_button'])){
    $get_inventory = $conn->prepare("
  SELECT  products.inventory, cartproducts.amount
  FROM carttouser
  INNER JOIN cartproducts
    ON carttouser.idcart = cartproducts.idcart
  INNER JOIN products
    ON cartproducts.idproduct = products.idproduct
  WHERE carttouser.iduser = ?");
    $get_inventory->bind_param('i', $iduser);
    $get_inventory->execute();
    $result_get_inventory = $get_inventory->get_result();
    $result_inventory = mysqli_fetch_all($result_get_inventory, MYSQLI_ASSOC);
    $inventory = array_column($result_inventory, "inventory");
    $amount = array_column($result_inventory, "amount");
    //Check if enough in inventory to buy
    if($inventory >= $amount) {
        $conn->begin_transaction();
        //$conn->autocommit(false);
        //Create a new order. IDorder is incremented automatically
        //so each new order is unique
        $todaysdate = date("Y-m-d");
        $query_create_order = $conn->prepare("INSERT INTO orders(iduser, totalprice, date) VALUES(?, ?, ?)");
        $query_create_order->bind_param('ids',$iduser, $_POST['totalprice'], $todaysdate);
        $query_create_order->execute();
        //Use the generated IDorder to update orderproducts
        $order_id = $conn->insert_id;
        //Copying all info from a users cart in to orders
        $stmt =$conn->prepare("INSERT INTO orderproducts
  SELECT orders.idorder, cartproducts.idproduct, cartproducts.amount, cartproducts.price, products.name
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
        //Changing the inventory of the selected products.
        //Reusing the query_cart statement
        $query_cart->execute();
        $result_products_in_cart = $query_cart->get_result();
        if($result_products_in_cart->num_rows > 0){
            $change_inventory = $conn->prepare("
      UPDATE products
      SET inventory = inventory - ?
      WHERE idproduct = ?");
            while($cart = $result_products_in_cart->fetch_assoc()){
                $change_inventory -> bind_param('ii', $cart['amount'], $cart["idproduct"]);
                $change_inventory -> execute();
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
        header('Location: index.php?page=confirmation&orderid='.$order_id.'');
    } else {
        echo "Product not in inventory";
    }
}

if(isset($_POST['change'])){
    //Check so enough is in inventory
    if($_POST["amount"] <= $_POST['inventory']){
        //Adding the product to the cart that belongs to the user, if product already in cart update the amount
        $add_product_query= $conn->prepare("UPDATE cartproducts
                            SET amount = ?
                            WHERE idcart = ? AND idproduct = ?");
        $add_product_query->bind_param('iii',$_POST["amount"], $_POST['idcart'], $_POST["idproduct"]);
        $add_product_query->execute();
        //$result_add = mysqli_query($conn,$add_product_query);
        header('Location: index.php?page=checkout');
    }else{
        echo "Not enough in inventory";
    }
}
if(isset($_POST['remove'])){
    //Adding the product to the cart that belongs to the user, if product already in cart update the amount
    $delete_product_query= $conn->prepare("DELETE FROM cartproducts
                            WHERE idcart = ? AND idproduct = ?");
    $delete_product_query->bind_param('ii', $_POST['idcart'], $_POST["idproduct"]);
    $delete_product_query->execute();
    // $result_delete = mysqli_query($conn,$delete_product_query);
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
    <!-- If the user has items in the cart then they are displayed-->
    <?php
    if($result_cart->num_rows > 0){ ?>
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
        //if($result_cart->num_rows > 0){
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
          <!-----Stops printing out items in cart------>
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
           
    </table>
    <?php $conn->close(); }
  //If user has no items in cart this text is displayed
  else{ ?> <h4>You have no items in your cart</h4> 
  <?php 
  } ?>
</div>
</div>
  </div>
<?php template_footer();?>