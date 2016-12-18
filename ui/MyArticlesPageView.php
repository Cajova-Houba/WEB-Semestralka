<?php

require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');
require_once ('misc/ArticleInfoForAuthorView.php');
require_once ('StandardPageView.php');

/**
 * Template for page which will display author's own articles.
 */
class MyArticlesPageView extends StandardPageView {

    /**
     * $data["articles"] is expected
     *
     * for each $data["articles"]
     *      ["article"], ["authors"] and ["review"] is expected
     *
     */
    protected static function getContent($data) {
        $articles = $data["articles"];
        $content = "
            <div class=\"col-xs-12 col-sm-9\">
                <h1>Moje články</h1>
                ";

        foreach ($articles as $article) {
            $a = $article["article"];
            $authors = $article["authors"];
            $content = $content."
            <div class=\"panel panel-default\">
                <div class=\"panel-heading\">
                    <h4>
                        <a href=\"display_article.php?aId=".escapechars($a->getId())."\">
                            ".escapechars($a->getTitle())."
                        </a>
                    </h4>
                </div>

                <div class=\"panel-body\">
                    ".ArticleInfoForAuthorView::getHTML($article)."
                </div>

                <div class=\"panel-footer\" style=\"overflow:hidden;\">
                    <div style=\"float:left;\">
                        Autoři: ".escapechars($authors)."
                    </div>
                    <div class=\"text-right\">Vytvořeno: ".formatDate($a->getCreated())."</div>
                </div>
            </div>
        ";
        }

        $content = $content."</div>";

        return $content;
    }

}