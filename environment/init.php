<?php
/** 
 * Session start, messages load, class initialization 
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

$messages = parse_ini_file("ini/messages.ini");
$configs = parse_ini_file("ini/config.ini");

if (!$configs) {
    error_log('The configuration file ini/config.ini could not be loaded!');
}

// Class initialization
include_once("classes/PdfProcessing.php");
$processor = new PdfProcessing($configs);

?>