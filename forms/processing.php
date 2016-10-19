<?php 
/**
 * Form for the pdf processing settings.
 */
?>
<form method="post">
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<p><?php echo($messages['uploadedFile'] . ' <strong>' . $_SESSION['originalFileName'] . '</strong>')  ?></p>
			</div>
		</div>
		
		<div class="row top-buffer">
			<div class="col-sm-3"><?php echo($messages['pdfaProcessMessage']) ?></div>
			<div class="col-sm-2">
<?php
    createSelectBox('pdfa_process_type', $messages['pdfaProcessType']);
?>							
			</div>
			<div class="col-sm-2">
<?php
    createSelectBox('pdfa_level', $messages['pdfaLevel']);
?>							
			</div>
			<div class="col-sm-2">
<?php
    createSelectBox('pdfa_modus', $messages['pdfaModus']);
?>							
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-primary" name="pdfa_process"
						value="<?php echo($messages['processButton'])?>">
			</div>
		</div>

		<div class="row top-buffer">
			<div class="col-sm-3"><?php echo($messages['pdfProfileProcessMessage']) ?></div>
			<div class="col-sm-6">
<?php
    createSelectBox('pdf_profile', $processor->getPdfProfiles());
?>							
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-primary" name="profile_process"
						value="<?php echo($messages['processButton'])?>">
			</div>
		</div>
		
		<div class="row top-buffer">
			<div class="col-sm-3"><?php echo($messages['pdfFreeProcessMessage']) ?></div>
			<div class="col-sm-6">
				<input type="text" class="form-control" id="pdf_args">
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-primary" name="free_process"
						value="<?php echo($messages['processButton'])?>">
			</div>
		</div>

		<div class="row top-buffer">
			<div class="col-sm-9">
				<p><strong><?php echo($messages['deleteMessage']) ?></strong></p>
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn-primary" name="delete_file"
						value="<?php echo($messages['deleteButton'])?>">
			</div>
		</div>
		
	</div>
</form>
