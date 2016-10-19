<?php
/**
 * This file handles the request input and by need executes processing.  
 * 
 */

    // If there is a file upload, save it and set the session variables
    if (!empty($_FILES)) {
        if (empty($_FILES["fileToUpload"]["name"])) {
            $infoMessage = $messages['fileNotChosen'];
        } else {
            $newFileName =  $processor->renameFile(basename($_FILES["fileToUpload"]["name"]), ".pdf");
            
            if (!$processor->saveFile($_FILES["fileToUpload"], $newFileName)) {
                $errorMessage = $messages['fileNotSaved'];
            } 
        }
    }
    
    // Check that the uploaded file exists
    if (!empty($_SESSION['targetFile']) && !file_exists($_SESSION['targetFile'])) {
        $errorMessage = $messages['fileNotFound'];
    }
    
    // If a process button was pushed, perform processing 
    if (!empty($_POST['pdfa_process'])) {
        $args = $processor->createPdfaArgs($_POST['pdfa_process_type'], $_POST['pdfa_level'], $_POST['pdfa_mode'], '.pdf');
        $processingReturnValue = $processor->executePdfProcessing($args);

    } elseif (!empty($_POST['profile_process'])) {
        $args = $processor->createPdfProfileArgs($_POST['pdf_profile'], '.pdf');
        $processingReturnValue = $processor->executePdfProcessing($args);
    }
    
    
    if (!empty($_POST['delete_file'])) {
        $processor->clearSession();
        $infoMessage = $messages['fileDeletedMessage'];
    }
?>
