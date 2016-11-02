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
        echo ('<option value="' . $value . '">' . $text . '</option>');
    }
    echo ('</select>');
}

