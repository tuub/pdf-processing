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
 * The main entry building the page together.
 *
 */

    include("environment/init.php");
    include("environment/functions.php");

    // The handler manages the data fromn the http request
    include("environment/handler.php");

    include("elements/header.php");
    include("elements/alerts.php");

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

    include("elements/footer.php");
