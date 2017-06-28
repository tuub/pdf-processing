<?php
/**
 * Offers the processed file to download.
 */
?>

    <div class="row">
        <div class="col-sm-12">
        	<p><?php echo $messages['downloadLabel'] ?>
                <a href="stream.php" class="btn btn-info btn-lg">
                	<span class="glyphicon glyphicon-download"></span> 
                	<?php echo $_SESSION['processedDisplayName'] ?>
                </a>
			</p> 
    	</div>
    </div>
