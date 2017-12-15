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
 * Displays info and error messages.
 */

if (!empty($errorMessage)) {
?>
<div class="container">
	<div class="alert alert-danger"><?php echo($errorMessage) ?></div>
</div>

<?php
}
    
if (!empty($infoMessage)) {
?>
<div class="container">
	<div class="alert alert-info"><?php echo($infoMessage) ?></div>
</div>

<?php
}
