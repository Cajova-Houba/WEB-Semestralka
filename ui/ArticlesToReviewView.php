<?php

require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');
require_once('misc/MainMenuView.php');
require_once('misc/NavbarView.php');
require_once('misc/LogoView.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

/**
 * Template for articles to be reviewed by a reviewer.
 */
class ArticlesToReviewView {

    static function getHTML($navbar, $data) {
        $header = HeaderView::getHTML("Uživatelé");
        $footer = FooterView::getHTML();
        $mainMenu = MainMenuView::getHTML(MainMenuView::NOTHING_ACTIVE);
        $navbar = NavbarView::getHTML($navbar, $data);
        $logo = LogoView::getHTML();

        $reviews = $data["reviews"];
        $revStr = "";
        foreach ($reviews as $review) {
            $title = $review["article"]->getTitle();
            $rId = $review["review"];
            $assigner = $review["assigner"];

            $revStr = $revStr."
                <div class=\"panel panel-default\">
                    <div class=\"panel-body\">
                        <h4>
                            <a href=\"index.php?web=rate&review=".$rId."\">".escapechars($title)."</a>
                        </h4>
                    </div>

                    <div class=\"panel-footer\">
                        <div class=\"text-left\">
                            ".escapechars($assigner)."
                        </div>
                    </div>
                </div>
            ";
        }

        $content = "
            <div class=\"col-xs-12 col-sm-9\">
                <h1>Nové články k hodnocení</h1>
                ".$revStr."
            </div>
        ";

        return $header.$navbar.$logo.$content.$mainMenu.$footer;
    }
}