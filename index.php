<?php
/**
 * This file functions as a sort of controller, directing the contents of the interface 
 * based on the values in the global variables. 
 * 
 */

    include("init.php");
    include("functions.php");
    include("header.php");

    // If there is a file upload, save it and set the session variables
    if (!empty($_FILES)) {
        if (empty($_FILES["fileToUpload"]["name"])) {
            $_SESSION['infoMessage'] = $messages['fileNotChosen'];
        } else {
            $newFileName =  $processor->renameFile(basename($_FILES["fileToUpload"]["name"]), ".pdf");
            
            if (!$processor->saveFile($_FILES["fileToUpload"], $newFileName)) {
                $_SESSION['errorMessage'] = $messages['fileNotSaved'];
            } 
        }
    }
    
    
    // Check that the uploaded file exists
    if (!empty($_SESSION['targetFile']) && !file_exists($_SESSION['targetFile'])) {
        $_SESSION['errorMessage'] = $messages['fileNotFound'];
    }
    
    include("alerts.php");
    
    // If there is no target file, show upload form
    if (empty($_SESSION['targetFile']) || !file_exists($_SESSION['targetFile'])) {
        include("forms/upload.php");
        
    } else {
        if (!empty($_POST['validate'])) {
            $processor->executePdfProcessing($args, $_SESSION['targetFile']);
        }
        include("forms/processing.php");
    }
    
    include("footer.php");
    
?>
