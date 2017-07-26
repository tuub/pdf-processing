<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8">
        <title><?php echo($messages['htmlTitle'])?></title>
		
        <base href="<?php echo $configs['baseUrl'] ?>">

        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        
        <!-- additional css -->
        <link rel="stylesheet" href="css/pdf.css">
        
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="images/TU-Logo-favicon-32x32.png">
        
    </head>
	<body>
		<div class="page-header">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <h3>
                            <a href="https://www.tu-berlin.de/" target="_blank">
                                <img src="images/tu_logo_3.png" class="logo"/>
                            </a>
                            <?php echo($messages['headline'])?>
                        </h3>
                    </div>
                    <ul class="nav navbar-nav ">

                        <?php foreach ($messages['navButton'] as $nav) {
                            $navigator = explode(",", $nav);
                        ?>
                            <li class="text-center active">
                                <a href="<?php echo $navigator[1] ?>">
                                    <i class="glyphicon <?php echo $navigator[2] ?>" aria-hidden="true"></i>
                                    <?php echo $navigator[0] ?>
                                </a>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
		</div>
        <div class="top-buffer bottom-buffer">
