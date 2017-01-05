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
        $login->clearSession();
    }
    
    // If a process button was pushed, perform processing 
    if (!empty($_POST['pdfa_validate'])) {
        $args = $processor->createPdfaValidateArgs($_POST['pdfa_level']);

    } elseif (!empty($_POST['pdfa_convert'])) {
        $processor->createAndSaveProcessedFileName('.pdf');
        $metadataArray = $processor->createMetadataArray();
        if (!empty($metadataArray)) {
            $fileContent = $xmpCreator->createXmp($metadataArray);
            if (!empty($fileContent)) {
                $processor->saveXmpFile($fileContent);
            }
        }
        $args = $processor->createPdfaArgs( 
            $_POST['pdfa_convlevel'], $_POST['pdfa_mode']);
        
    }
    if (!empty($args)) {
        $processingReturnValue = $processor->executePdfProcessing($args);
        $processingReturnValue = $processor->filterReturnValue($processingReturnValue);
    }
      
    if (!empty($_POST['delete_file'])) {
        $login->clearSession();
        $infoMessage = $messages['fileDeletedMessage'];
    }

