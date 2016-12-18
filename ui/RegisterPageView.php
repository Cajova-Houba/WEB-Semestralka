<?php

require_once ($_SERVER["DOCUMENT_ROOT"].'/kiv-web/core/code/utils.php');
require_once ('misc/ErrorView.php');
require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');

/**
 * Register page.
 */
class RegisterPageView
{
    static function getHTML($errorMessage) {

        $head = HeaderView::getHTML('Nový uživatel');
        $footer = FooterView::getHTML();
        $errStr = self::getErrorStr($errorMessage);
        $valid = self::getValidationJs();

        $content = "
            <div class=\"container\">
            <div class=\"row\">
                <div class=\"col-xs-6 col-md-4\"></div> 
                <div class=\"col-xs-6 col-md-4\">
                    <h1>Nový uživatel</h1>
                    <form action=\"core/code/register.php\" method=\"post\" id=\"reg_form\">
                        <div class=\"form-group\">
                            <label for=\"first_name\">Jméno: </label>
                            <input type=\"text\" id=\"first_name\" class=\"form-control\" name=\"first_name\">
                            <label for=\"last_name\">Příjmení: </label>
                            <input type=\"text\" id=\"last_name\" class=\"form-control\" name=\"last_name\">
                            <label for=\"username\">Nickaname: </label>
                            <input type=\"text\" id=\"username\" class=\"form-control\" name=\"username\">
                            <label for=\"password\">Heslo: </label>
                            <input type=\"password\" id=\"password\" class=\"form-control\" name=\"password\">
                            <label for=\"password_check\">Ověření hesla: </label>
                            <input type=\"password\" id=\"password_check\" class=\"form-control\" name=\"password_check\">
                        </div>
        
                        <div class=\"btn-group\">
                            <a href=\"index.php\" role=\"button\" class=\"btn btn-link\">Cancel</a>
                            <button type=\"submit\" class=\"btn btn-primary\">Registrovat</button>
                        </div>
                    </form>
                    ".$errStr.$valid."
                </div>
            </div>
        ";

        return $head.$content.$footer;
    }

    private static function getErrorStr($errorMessage) {
        if ($errorMessage == null) {
            return "";
        } else {
            return ErrorView::getHTML($errorMessage);
        }
    }

    private static function getValidationJs() {
        return "
            <script type=\"text/javascript\">
                var formValidator = new Validator(\"reg_form\");
                formValidator.addValidation(\"first_name\", \"req\", \"Vaše jméno nesmí být prázdné\");
                formValidator.addValidation(\"first_name\", \"maxlen=40\", \"Jméno může být dlouhé maximálně 40 znaků.\");
                
                formValidator.addValidation(\"last_name\", \"req\", \"Vaše příjmení nesmí být prázdné\");
                formValidator.addValidation(\"last_name\", \"maxlen=40\", \"Příjmení může být dlouhé maximálně 40 znaků.\");
                
                formValidator.addValidation(\"username\", \"req\", \"Váš nickname nesmí být prázdný\");
                formValidator.addValidation(\"username\", \"maxlen=40\", \"Nickname může být dlouhé maximálně 40 znaků.\");
                
                formValidator.addValidation(\"password\", \"req\", \"Vaše heslo nesmí být prázdné\");
                formValidator.addValidation(\"password\", \"maxlen=20\", \"Vaše heslo může být dlouhé maximálně 20 znaků.\");
                
                formValidator.addValidation(\"password_check\", \"req\", \"Potvrzení hesla nesmí být prázdné\");
                formValidator.addValidation(\"password_check\", \"eqelmnt=password\", \"Potvrzení hesla se neshoduje s původním heslem.\");
            </script>
        ";
    }
}