<?php
/**
 * Displays the processing return value.
 */
?>

<div class="container top-buffer">
    <div class="row">
        <div class="col-sm-12">
        	<?php echo($messages['argsLabel']) ?>
    	</div>
    </div>
    <div class="row">
        <div class="col-sm-12">
			<pre><?php echo($args)?></pre>
    	</div>
    </div>
    <div class="row">
        <div class="col-sm-12">
        	<?php echo($messages['returnValueMessage']) ?>
    	</div>
    </div>
	<div class="row">
        <div class="col-sm-12">
			<pre><?php echo($processingReturnValue)?></pre>
    	</div>
	</div>
</div> 
