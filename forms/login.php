<?php 
/**
 * Form for login
 */
?>
<form method="post" action="index.php" enctype="multipart/form-data">
    <div class="container">
    	<div class="row">
    		<div class="col-sm-6">
    			<p>	<?php echo($messages['account']) ?> </p>
    		</div>
        </div>
        <div class="row">
        	<div class="col-sm-3">
 			<input type="text" required="required" name="account" maxlength="30" autofocus="autofocus" class="form-control" />
        	</div>
        </div>
    	<div class="row top-buffer ">
    		<div class="col-sm-6">
    			<p>	<?php echo($messages['password']) ?> </p>
    		</div>
        </div>
        <div class="row">
        	<div class="col-sm-3">
                 <input type="password" required="required" name="password" maxlength="20" class="form-control"/>
        	</div>
        </div>
        <div class="row top-buffer ">
        	<div class="col-sm-4">
        		<input type="submit" class="btn btn-primary" value="<?php echo($messages['login']) ?>" name="login">
        	</div>
        </div>
    </div>
</form>
