<?php

/**
 * Template for articles page.
 */
require_once ('misc/header.php');
require_once ('misc/footer.php');
require_once ('misc/main_menu.php');
require_once ('misc/navbar.php');
require_once ('misc/logo.php');
require_once ('misc/info_panel.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class ArticlesPageView {

    /**
     * $data["articles"] with published articles is expected.
     * each $data["articles"] should containt ["authors"] and ["article"]
     */
    static function getHTML($info, $navbar, $data) {
        $head = HeaderView::getHTML('Publikované příspěvky');
        $mainMenu = MainMenuView::getHTML(MainMenuView::PRISPEVKY_ACTIVE);
        $footer = FooterView::getHTML();
        $navbar = NavbarView::getHTML($navbar, $data);
        $logo = LogoView::getHTML();
        $infoPan = $info == null ? '' : InfoView::getHTML($info);

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
                            <div class=\"text - right\">Vytvořeno: ".formatDate($a->getCreated())."</div>
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

        return $head.$navbar.$infoPan.$logo.$content.$mainMenu.$footer;
    }

}