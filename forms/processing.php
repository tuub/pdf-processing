<?php 
/**
 * Form for the pdf processing settings.
 */
?>
<form method="post" action="index.php">
	<div class="container">
		<div class="row">
			<div class="col-sm-9">
				<p><?php echo($messages['uploadedFile'] . ' <strong>' . $_SESSION['originalFileName'] . '</strong>')  ?></p>
			</div>
		</div>
		
		<div class="row top-buffer">
			<div class="col-sm-3"><?php echo($messages['pdfaValidateMessage']) ?></div>
			<div class="col-sm-3">
<?php
    createSelectBox('pdfa_level', $messages['pdfaLevel']);
?>							
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-success" name="pdfa_validate"
						value="<?php echo($messages['validateButton'])?>">
			</div>
		</div>

		<div class="row top-buffer">
			<div class="col-sm-3"><?php echo($messages['pdfaConvertMessage']) ?></div>
			<div class="col-sm-1">
<?php
    createSelectBox('pdfa_convlevel', $messages['pdfaLevel']);
?>							
			</div>
			<div class="col-sm-2">
<?php
    createSelectBox('pdfa_mode', $messages['pdfaModus']);
?>							
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-info" name="pdfa_convert"
						value="<?php echo($messages['convertButton'])?>">
			</div>
		</div>

<?php include 'elements/metadata.php'; ?>

		<div class="row top-buffer">
			<div class="col-sm-6">
				<p><strong><?php echo($messages['deleteMessage']) ?></strong></p>
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-primary" name="delete_file"
						value="<?php echo($messages['deleteButton'])?>">
			</div>
		</div>

	</div>
</form>
