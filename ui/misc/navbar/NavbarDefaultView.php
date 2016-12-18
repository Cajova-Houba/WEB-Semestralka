<?php
/*
    
    A navbar for non-logged user.

*/

class NavbarDefaultView {

    static function getHTML() {
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
                            <a href=\"index.php?web=login\">
                                Login
                            </a>
                        </span>
                    </div>
                    <div class=\"collapse navbar-collapse\" id=\"bs-example-navbar-collapse-1\">
                        <ul class=\"nav navbar-nav\">
                            <li><a href=\"register.php\">Nový uživatel</a></li>
                        </ul>
                    </div>
              </div><!-- /.container-fluid -->
            </nav>
        ";
    }
}
?>