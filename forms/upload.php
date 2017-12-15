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
 * Form for the file upload.
 */
?>
<form method="post" action="index.php" enctype="multipart/form-data">
    <div class="container">
    	<div class="row">
    		<div class="col-sm-3">
    			<p>	<?php echo($messages['selectFile']) ?> </p>
    		</div>
    		<div class="col-sm-4">
                <input type="file" name="fileToUpload" id="fileToUpload">
        	</div>
        </div>
        <div class="row">
        	<div class="col-sm-4">
        		<input type="submit" class="btn btn-primary" value="<?php echo($messages['uploadFile']) ?>" name="submit">
        	</div>
        </div>
    </div>
</form>
