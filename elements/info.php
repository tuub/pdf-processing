<?php
/**
 * Displays the processing return value.
 */
?>

<div class="container top-buffer">
   
    <div class="row">
        <div class="col-sm-12">
<?php if ($processor->returnOk($processingReturnValue)) { ?>

        <a href="#" class="btn btn-success btn-lg">
          <span class="glyphicon glyphicon-ok-sign"></span>
          <?php echo($messages['okMessage']) ?>
        </a>

<?php } else { ?>

        <a href="#" class="btn btn-danger btn-lg">
          <span class="glyphicon glyphicon-remove-sign"></span> 
          <?php echo($messages['failMessage']) ?>
        </a>

<?php } ?>
    	</div>
    </div>
    
    <div class="row top-buffer">
        <div class="col-sm-12">
        	<p><?php echo($messages['returnValueMessage']) ?></p>
    	</div>
    </div>
	<div class="row">
        <div class="col-sm-12">
			<pre><?php echo(htmlentities($processingReturnValue))?></pre>
    	</div>
	</div>
</div> 
