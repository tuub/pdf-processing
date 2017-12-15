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
 * Offers the processed file to download.
 */
?>

    <div class="row">
        <div class="col-sm-12">
        	<p><?php echo $messages['downloadLabel'] ?>
                <a href="stream.php" class="btn btn-info btn-lg">
                	<span class="glyphicon glyphicon-download"></span> 
                	<?php echo $_SESSION['processedDisplayName'] ?>
                </a>
			</p> 
    	</div>
    </div>
