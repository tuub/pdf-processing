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
 * Displays the processing return value.
 */
?>

<div class="container top-buffer">
   
    <div class="row">
        <div class="col-sm-12">
<?php if ($processor->returnOk($processingReturnValue)) { ?>

        <a href="#" class="btn btn-success btn-lg">
          <span class="glyphicon glyphicon-ok-sign"></span>
          <?php echo($messages['okMessage']) ?>
        </a>

<?php
        // If there is a processed file, offer it to download
        if (!empty($_SESSION['processedFile']) && file_exists($_SESSION['processedFile'])) {
            include("elements/download.php");
        }

    } else { ?>

        <a href="#" class="btn btn-danger btn-lg">
          <span class="glyphicon glyphicon-remove-sign"></span> 
          <?php echo($messages['failMessage']) ?>
        </a>

<?php } ?>
    	</div>
    </div>

    <div class="row top-buffer">
        <div class="col-sm-12">
        	<p><?php echo($messages['returnValueMessage']) ?></p>
    	</div>
    </div>
	<div class="row">
        <div class="col-sm-12">
			<pre><?php echo(htmlentities($processingReturnValue))?></pre>
    	</div>
	</div>
</div> 
