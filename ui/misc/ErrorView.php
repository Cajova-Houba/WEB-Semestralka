<?php
/*
 * If an error occurs, this script will display appropriate panel.
 */

require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class ErrorView {

    static function getHTML($errMsg) {
        return "
            <div class=\"alert alert-danger\">
                ".escapechars($errMsg)."
            </div>
        ";
    }
}
?>
