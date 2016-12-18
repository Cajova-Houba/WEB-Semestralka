<?php

require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');
require_once('misc/ErrorView.php');

/**
 * A template for the login page.
 */
class LoginPageView
{
    static function getHTML($errMessage) {
        $head = HeaderView::getHTML('Přihlášení');
        $footer = FooterView::getHTML();
        $errPanel = $errMessage == null ? "" : ErrorView::getHTML($errMessage);
        $content = "
            <div class=\"row\">
               <div class=\"col-xs-6 col-md-4\"></div>
               <div class=\"col-xs-6 col-md-4\">
                <h1>Login</h1>
                <form action=\"core/code/login.php\" method=\"post\">
                    <div class=\"form-group\">
                        <label for=\"username\">Username: </label>
                        <input type=\"text\" id=\"username\" name=\"username\" class=\"form-control\">
                        <label for=\"password\">Heslo: </label>
                        <input type=\"password\" id=\"password\" name=\"password\" class=\"form-control\">
                    </div>    
                    
                    <div class=\"btn-group\">
                        <a href=\"index.php\" role=\"button\" class=\"btn btn-link\">Cancel</a>
                        <button type=\"submit\" class=\"btn btn-primary\">Login</button>
                    </div>
                </form>
                   ".$errPanel."
               </div>
            </div>
        ";
        return $head.$content.$footer;
    }
}