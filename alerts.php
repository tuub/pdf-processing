<?php
/**
 * Display of info and error messages.
 */

if (!empty($_SESSION['errorMessage'])) {
?>
<div class="alert alert-info">
  <?php echo($_SESSION['errorMessage']) ?>
</div>           
                
<?php
    $_SESSION['errorMessage'] = NULL;
}
    
if (!empty($_SESSION['infoMessage'])) {
?>
<div class="alert alert-danger">
  <?php echo($_SESSION['infoMessage']) ?>
</div>           

<?php
    $_SESSION['infoMessage'] = NULL;
}
?>