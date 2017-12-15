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
 * Side effect functions for use in php elements.
 */

/**
 * Creates a select box. 
 * 
 * @param $id - the id of the select element.
 * @param $messageArray - an array of options.
 */
function createSelectBox($id, $messageArray) 
{
    echo ('<select name="' . $id . '" class="selectpicker">');
    while (list ($key, $val) = each($messageArray)) {
        $explodedVal = explode(',', $val);
        $value = $explodedVal[0];
        
        if (count($explodedVal) > 1) {
            $text = $explodedVal[1];            
        } else {
            $text = $explodedVal[0];
        }

        $selected = "";
        if ($value == $_POST[$id]) {
            $selected = " selected";
        }

        echo ('<option value="' . $value . '"' . $selected . '>' . $text . '</option>');
    }
    echo ('</select>');
}

