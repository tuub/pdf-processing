<?php
/**
 * Input fields for additional metadata.
 */
?>
<a href="#metadata" class="btn btn-info" data-toggle="collapse"><?php echo($messages['metadataButton']) ?></a>
<div id="metadata" class="collapse">
    
<?php foreach ($configs['metadataField'] as $field) { ?>    
    <div class="row">
    	<div class="col-sm-3">
    		<p><?php echo($messages[$field . 'Label']) ?></p>
    	</div>
    	<div class="col-sm-8">
    		<input name="<?php echo($field) ?>" type="text" class="form-control" />
    	</div>
    </div>
<?php } ?>

</div>
    