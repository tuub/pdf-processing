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
 * Prints a static help page.
 */

include("environment/init.php");
include("elements/header.php");

?>
<div class="container helppage">
    <div class="row">
        <div class="col-sm-9">

            <h3><?php echo($messages['helpHeadline']) ?></h3>
            <p><?php echo($messages['helpIntro']) ?></p>
            <p><?php echo($messages['helpIntro1']) ?></p>
            <p><?php echo($messages['helpIntro2']) ?></p>
            <image src="images/help_upload.png"/>
            <div class="alert alert-warning"><?php echo($messages['helpIntroAlert']) ?></div>

            <h4><?php echo($messages['helpPdfaLevelsHeadline']) ?></h4>
            <p><?php echo($messages['helpPdfaLevelsIntro']) ?></p>


            <h5><?php echo($messages['helpPdfaLevelsSubheader1']) ?></h5>
            <p><?php echo($messages['helpPdfaLevelsSubsection1']) ?></p>

            <h5><?php echo($messages['helpPdfaLevelsSubheader2']) ?></h5>
            <p><?php echo($messages['helpPdfaLevelsSubsection2']) ?></p>
            <p><a href="<?php echo($messages['helpPdfaLevelsLink']) ?>" target="_blank"><?php echo($messages['helpPdfaLevelsLinkAnchor']) ?></a></p>

            <h4><?php echo($messages['helpValidationHeadline']) ?></h4>
            <p><?php echo($messages['helpValidationIntro']) ?></p>
            <image src="images/help_validation.png"/>
            <p><?php echo($messages['helpValidationSuccess']) ?></p>
            <image src="images/help_validation_success.png"/>
            <p><?php echo($messages['helpValidationFailure']) ?></p>
            <image src="images/help_validation_failure.png"/>


            <h4><?php echo($messages['helpConversionHeadline']) ?></h4>
            <p><?php echo($messages['helpConversionIntro']) ?></p>
            <div class="alert alert-warning"><?php echo($messages['helpConversionAlert']) ?></div>
            <image src="images/help_conversion.png"/>

            <h5><?php echo($messages['helpConversionSubheader1']) ?></h5>
            <p><?php echo($messages['helpConversionSubsection1']) ?></p>
            <p><?php echo($messages['helpConversionSubsection1b']) ?></p>

            <h5><?php echo($messages['helpConversionSubheader2']) ?></h5>
            <p><?php echo($messages['helpConversionSubsection2']) ?></p>

            <p><?php echo($messages['helpConversionSubsection2b']) ?></p>
            <image src="images/help_conversion_success.png"/>

        </div>
    </div>

</div>
<?php
include("elements/footer.php");

