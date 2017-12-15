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
 * Handles the download of the processed file. 
 */    

include("environment/init.php");

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
