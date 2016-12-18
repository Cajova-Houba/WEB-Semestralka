<?php

require_once ('StandardPageView.php');
require_once ('misc/ErrorView.php');

/**
 * A template for page for editing/adding a new article.
 */
class NewArticlePageView extends StandardPageView {

    static function getHTML($title, $menuActive, $navbar, $data) {
        return parent::getHTML($title, $menuActive, $navbar, $data);
    }

    /**
     * $data["article"] is expected to contain article or null
     * $data["errorMessage"] is expected to contain null or error message
     */
    protected static  function getContent($data) {
        $article = $data["article"];
        $header = "";
        $errorView = $data["errorMessage"] != null ? ErrorView::getHTML($data["errorMessage"]) : "";
        $articleId = "";
        $title = "";
        $content = "";
        if($article == null) {
            $header = "<h1>Nový článek</h1>";
        } else {
            $header = "<h1>Upravit článek</h1>";
            $title = escapechars($article->getTitle());
            $content = escapechars($article->getContent());
            $articleId = "<input type=\"hidden\" name=\"aid\" value=\"".escapechars($article->getId())."\">";
        }
        $content = "
            <div class=\"col-xs-12 col-sm-9\">
                ".$header."
                <form action=\"core/code/new_article.php\" method=\"post\" id=\"art_form\" enctype=\"multipart/form-data\">
                    ".$articleId."
                    <div class=\"form-group\">
                        <label for=\"title\">Název</label>
                        <input type=\"text\" name=\"title\" id=\"title\" class=\"form-control\" value=\"".$title."\">
                    </div>
                    
                    <div class=\"form-group\">
                        <label for=\"content\">Článek:</label>
                        <textarea name=\"content\" id=\"content\" cols=\"30\" rows=\"10\" class=\"form-control\">".$content."</textarea>
                    </div>
                    
                    <div class=\"form-group\">
                        <label for=\"attachment\">Příloha:</label>
                        <input type=\"file\" id=\"attachment\" name=\"attachment\">
                        <p class=\"help-block\">Volitelný</p>
                    </div>
                    
                    <div class=\"btn-group\">
                        <button type=\"submit\" class=\"btn btn-primary\">Uložit</button>
                    </div>
                </form>
                
                ".$errorView."
            </div>
        ";

        return $content;
    }

}