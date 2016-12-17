<?php
/*
 * In case it's needed to display some info (successful registration, saving a new article...) this script
 * will display the panel with message.
 *
 */
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class InfoView {

    static function getHTML($infoMsg) {
        return "
            <div cla=\"row\">
                <div class=\"alert alert-info\">
                    ".escapechars($infoMsg)."
                </div>
            </div>
        ";
    }

}
?>