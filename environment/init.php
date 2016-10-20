<?php
/** 
 * Session start, messages load, class initialization 
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

$messages = parse_ini_file("ini/messages.ini");

// Class initialization
include_once("classes/PdfProcessing.php");
$processor = new PdfProcessing();

?>