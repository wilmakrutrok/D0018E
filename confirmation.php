<?php
logged_in();
template_header('checkout');

//Hämtar först ut användarens id
$uname = $_SESSION['uname'];
$iduser = $_SESSION['iduser'];
    $stmt = $conn->prepare("SELECT * FROM orders WHERE idorder = ?");
    $stmt -> bind_param('i', $_GET['order_id']);
    $stmt -> execute();
    $result = $stmt -> get_result();
   // echo $_GET['order_id'];
    /*
    if (!$_GET['order_id']) {
        die ('Order does not exist');
    } 
    else {
      die ('Product does not exist');
    }
    */
?>
  <div class="container_cart" style="margin-bottom:200px">
    <div class="div_cart_content">
      <div class="checkout_page">
        <div class="checkout_page_center">
      <h4>Thank You For Your Order </h4>
    </div>
    </div>
    <div>
      <ul>
        <li class = order_info>Order ID</li>
        <li class = order_info>Date </li>
      </ul>
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
        <tr>
          <td>
          </td>
          <td>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  </div>
</div> 
<?php template_footer();?>