<?php
/**
 * This file handles the request input and possibly executes processing.  
 * 
 */

    // If there is a file upload, save it and set the session variables
    if (!empty($_FILES)) {
        if (empty($_FILES['fileToUpload']['name'])) {
            $infoMessage = $messages['fileNotChosen'];
        } elseif (pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION) != 'pdf') {
            $errorMessage = $messages['uploadNoPdf'];
        } else {
            $newFileName =  $processor->renameFile(basename($_FILES['fileToUpload']['name']), '.pdf');
            
            if (!$processor->saveFile($_FILES['fileToUpload'], $newFileName)) {
                $errorMessage = $messages['fileNotSaved'];
            } 
        }
    }
    
    // Check that the uploaded file exists
    if (!empty($_SESSION['uploadFile']) && !file_exists($_SESSION['uploadFile'])) {
        $errorMessage = $messages['fileNotFound'];
        session_unset();
    }
    
    // If a process button was pushed, perform processing 
    if (!empty($_POST['pdfa_process'])) {
        $processor->createAndSaveProcessedFileName('.pdf');
        $metadataArray = $processor->createMetadataArray();
        if (!empty($metadataArray)) {
            $fileContent = $xmpCreator->createXmp($metadataArray);
            $processor->saveXmpFile($fileContent);
        }
        $args = $processor->createPdfaArgs($_POST['pdfa_process_type'], 
            $_POST['pdfa_level'], $_POST['pdfa_mode']);

    } elseif (!empty($_POST['profile_process'])) {
        $processor->createAndSaveProcessedFileName('.pdf');
        $args = $processor->createPdfProfileArgs($_POST['pdf_profile']);
    
    } elseif (!empty($_POST['free_process'])) {
        $processor->createAndSaveProcessedFileName('.pdf');
        $args = $processor->createPdfFreeArgs($_POST['pdf_args']);
        
    } 
    if (!empty($args)) {
        $processingReturnValue = $processor->executePdfProcessing($args);
    }
      
    if (!empty($_POST['delete_file'])) {
        $processor->clearSession();
        $infoMessage = $messages['fileDeletedMessage'];
    }
?>
