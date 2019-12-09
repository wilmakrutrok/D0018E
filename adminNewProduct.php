<?php
admin_verify();
template_header('newProduct');

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
}
?>

<?php admin_menu(); ?>

<div class="container" style="margin-bottom: 600px">
    <div class="div_cart_content">
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
</div>

<?php template_footer();?>