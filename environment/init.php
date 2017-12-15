<?php
/**
 * (c) 2017 Technische Universität Berlin
 *
 * This software is licensed under GNU General Public License version 3 or later.
 *
 * For the full copyright and license information, 
 * please see https://www.gnu.org/licenses/gpl-3.0.html or read 
 * the LICENSE.txt file that was distributed with this source code.
 */
?>
<?php
/** 
 * Session start, messages load, class initialization 
 */

session_start();
header('Content-Type: text/html; charset=utf-8');

if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    $_SESSION['lang'] = $lang;
} else if (isset($_SESSION['lang'])){
    $lang = $_SESSION['lang'];
} else {
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

if ($lang == 'de') {
    $messages = parse_ini_file("ini/messages_de.ini");
} else {
    $messages = parse_ini_file("ini/messages_en.ini");
}

$configs = parse_ini_file("ini/config.ini");
$xmpConfigs = parse_ini_file("ini/xmp_fragments.ini");

if (!$configs) {
    error_log('The configuration file ini/config.ini could not be loaded!');
}

// Class initialization
include_once("classes/PdfProcessing.php");
$processor = new PdfProcessing($configs, $messages);

include_once("classes/XmpCreator.php");
$xmpCreator = new XmpCreator($xmpConfigs);

include_once("classes/SessionControl.php");
$sessionControl = new SessionControl();
