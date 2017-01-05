<?php
/**
 * The main entry building the page together.
 *
 */

    include("environment/init.php");
    include("environment/functions.php");

    //Authentification
    $login->authMe();

    // The handler manages the data fromn the http request
    include("environment/handler.php");

    include("elements/header.php");
    include("elements/alerts.php");

    // we check, if we are logged in
    if (empty($_SESSION['login']) || !$_SESSION['login'] || $login->logoutWanted()) {
        include("forms/login.php");
    } else {

        // If there is no target file, show upload form
        if (empty($_SESSION['uploadFile']) || !file_exists($_SESSION['uploadFile'])) {
            include("forms/upload.php");

        } else {
            include("forms/processing.php");
        }

        // If there are arguments of a processing return value, show them
        if (!empty($processingReturnValue) || !empty($args)) {
            include("elements/info.php");
        }

        // If there is a processed file, offer it to download
        if (!empty($_SESSION['processedFile']) && file_exists($_SESSION['processedFile'])) {
            include("elements/download.php");
        }

        include("forms/logout.php");

    }
    include("elements/footer.php");
