<?php
/*

    A navbar for reviewer.

*/

require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class NavbarReviewerView {

    /**
     * $data["fisrtName"] and $data["lastName"] is expected.
     */
    static function getHTML($data) {
        return "
            <nav class=\"navbar navbar-default\">
                <div class=\"container-fluid\">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class=\"navbar-header\">
                        <button type=\"button\" class=\"navbar-toggle collapsed\" data-toggle=\"collapse\" data-target=\"#bs-example-navbar-collapse-1\" aria-expanded=\"false\">
                            <span class=\"sr-only\">Toggle navigation</span>
                            <span class=\"icon-bar\"></span>
                            <span class=\"icon-bar\"></span>
                            <span class=\"icon-bar\"></span>
                        </button>
            
                        <span class=\"navbar-brand\">
                            <a href=\"#\">
                                ".escapechars($data["firstName"])." ".escapechars($data["lastName"])."
                            </a> (recenzent)
                        </span>
                    </div>
            
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
                      <ul class=\"nav navbar-nav\">
                        <li><a href=\"index.php?web=reviews\">Nové články</a></li>
                        <li><a href=\"reviewed_articles_r.php\">Hodnocené články</a></li>
                      </ul>
                       <ul class=\"nav navbar-nav navbar-right\">
                          <li><a href=\"index.php?web=logout\">Logout</a></li>
                      </ul>
                    </div>
               </div>
            </nav>
        ";
    }
}
?>
