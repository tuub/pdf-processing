<?php
/**
 * Displays the processing return value.
 */
?>

<div class="container top-buffer">
    <div class="row">
        <div class="col-sm-12">
        	<p><?php echo($messages['argsLabel']) ?></p>
    	</div>
    </div>
    <div class="row">
        <div class="col-sm-12">
			<pre><?php echo(htmlentities($args))?></pre>
    	</div>
    </div>
    <div class="row">
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
