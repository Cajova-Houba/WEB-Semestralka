<?php

/**
 * Template for articles page.
 */
require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');
require_once('misc/MainMenuView.php');
require_once('misc/NavbarView.php');
require_once('misc/LogoView.php');
require_once('misc/InfoView.php');
require_once ('StandardPageView.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class ArticlesPageView extends StandardPageView {

    /**
     * $data["articles"] with published articles is expected.
     * each $data["articles"] should containt ["authors"] and ["article"]
     */
    protected static  function getContent($data) {
        $articlesStr = "";
        $articles = $data["articles"];
        foreach ($articles as $article) {
            $authors = $article["authors"];
            $a = $article["article"];

            $articlesStr = $articlesStr."
                    <div class=\"panel panel-default\">
                        <div class=\"panel-body\">
                            <h4><a href=\"index.php?web=article&aId=".$a->getId()."\">".escapechars($a->getTitle())."</a></h4>
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

        $content = "
            <div class=\"col-xs-12 col-sm-9\">
			<h1>Publikované příspěvky</h1>
			    ".$articlesStr."
		    </div>
        ";

        return $content;
    }

}