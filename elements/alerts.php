<?php
/**
 * Displays info and error messages.
 */

if (!empty($errorMessage)) {
?>
<div class="container">
	<div class="alert alert-danger"><?php echo($errorMessage) ?></div>
</div>

<?php
}
    
if (!empty($infoMessage)) {
?>
<div class="container">
	<div class="alert alert-info"><?php echo($infoMessage) ?></div>
</div>

<?php
}
?>