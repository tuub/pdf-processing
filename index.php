<?php
/**
 * This file functions as a sort of controller, directing the contents of the interface 
 * based on the values in the global variables. 
 * 
 */

    include("init.php");
    include("functions.php");
    include("handler.php");
    
    include("elements/header.php");
    include("elements/alerts.php");
    
    // If there is no target file, show upload form
    if (empty($_SESSION['targetFile']) || !file_exists($_SESSION['targetFile'])) {
        include("forms/upload.php");
        
    } else {
        include("forms/processing.php");
    }
    
    // If there is a processing return value, show it 
    if (!empty($processingReturnValue)) {
        include("elements/info.php");
    }
    
    include("elements/footer.php");
    
?>
