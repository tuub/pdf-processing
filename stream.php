<?php
/**
 * Handles the download of the processed file. 
 */    

include("environment/init.php");

// Do nothing is user not logged in.
$login->authMe();
if (empty($_SESSION['login']) || !$_SESSION['login']) {
    exit;
}

if (empty($_SESSION['processedFile']) || !file_exists($_SESSION['processedFile'])) {
    $errorMessage = $messages['downloadFileNotExists'];
    unset($_SESSION['processedFile']);
    include("index.php");
} else {
    if (empty($_SESSION['processedDisplayName'])) {
        $_SESSION['processedDisplayName'] = 'processedFile.pdf';
    }

    $processor->downloadFile($_SESSION['processedFile'], 'application/pdf', $_SESSION['processedDisplayName']);
    exit;
}
