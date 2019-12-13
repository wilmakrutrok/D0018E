<?php
admin_verify();
template_header('reviews');

//Delete choosen review
if(isset($_POST["delete_review"])){
    $stmt = $conn->prepare("DELETE FROM review
            WHERE idreview = ?");
    $stmt->bind_param('i',$_POST['idreview']);
    $stmt->execute();
}
?>

<?php admin_menu(); ?>

<div class="review">
<br>
    	<ul>
        <?php 
            $query_reviews = $conn->prepare("SELECT review.grade, review.idreview, review.comment, products.name FROM review INNER JOIN products ON review.idproduct = products.idproduct");
            $query_reviews->execute();
        	$result_reviews = $query_reviews->get_result();
        	
            //Print out all reviews
        	if ($result_reviews->num_rows > 0) {
    
        	    while($review = $result_reviews->fetch_assoc()) {
                    ?>
                    <li>
                    	Product: <?php echo $review["name"]?><br>
        	        	Rate: <?php echo $review["grade"]?><br>
        	        	Comment: <?php echo $review["comment"]?><br>
        	        	<form method="post">
        	        	<input type="hidden" value="<?php echo $review["idreview"]?>" name="idreview">
        	        	<input type="submit" value="Delete" name="delete_review">
        	        	</form>
        	        </li>
      			<?php 
        	    }
    
        	}
            ?>
            </ul>
</div>

<?php template_footer();?>
