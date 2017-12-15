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
 * Input fields for additional metadata.
 */
?>
<div class="row top-buffer">
	<div class="col-sm-9 text-info"><?php echo($messages['pdfaMetadataMessage']) ?></div>
</div>
<?php foreach ($configs['metadataField'] as $field) { ?>    
    <div class="row top-buffer">
    	<div class="col-sm-3">
    		<p><?php echo($messages[$field . 'Label']) ?></p>
    	</div>
    	<div class="col-sm-5">
    		<input name="<?php echo($field) ?>" type="text" class="form-control" />
    	</div>
    </div>
<?php } ?>
    