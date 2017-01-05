<?php 
/**
 * Form for logout
 */
?>
<form method="post" action="index.php" enctype="multipart/form-data">
    <div class="container">
        <div class="row top-buffer">
        	<div class="col-sm-4">
        		<input type="submit" class="btn btn-primary" value="<?php echo($messages['logout']) ?>" name="logout">
        	</div>
        </div>
    </div>
</form>
