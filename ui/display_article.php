<?php

require_once ('misc/header.php');
require_once ('misc/footer.php');
require_once ('misc/main_menu.php');
require_once ('misc/navbar.php');
require_once ('misc/logo.php');
require_once ('misc/info_panel.php');

/**
 * Template for displaying the article.
 */
class DisplayArticleView
{
    /**
     * $data["article"], $data["attachments"], $data["authors"], $data["showEdit"] is expected
     */
    static function getHTML($navbar, $data) {
        $head = HeaderView::getHTML('O konferenci');
        $mainMenu = MainMenuView::getHTML(MainMenuView::O_KONFERENCI_ACTIVE);
        $footer = FooterView::getHTML();
        $navbar = NavbarView::getHTML($navbar, $data);
        $logo = LogoView::getHTML();

        $article = $data["article"];
        $attachments = $data["attachments"];
        $authors = $data["authors"];
        $showEdit = $data["showEdit"];

        $attachmentsStr = self::getAttachmentsStr($attachments);

        $showEditStr = self::getShowEditStr($showEdit, $article);


        $content = "
            <div class=\"col-xs-12 col-sm-9\">
                <h1>".escapechars($article->getTitle())."</h1>

                <p>
                    ".escapechars($article->getContent())."
                </p>
                
                ".$attachmentsStr."
     

                <div style=\"overflow:hidden;\">
                    <div style=\"float:left;\">
                        Autoři: ".escapechars($authors)."
                    </div>
                    <div class=\"text-right\">Vytvořeno: ".formatDate($article->getCreated())."</div>
                </div>
                
                ".$showEditStr."
            </div>
        ";

        return $head.$navbar.$logo.$content.$mainMenu.$footer;
    }



    private static function getAttachmentsStr($attachments) {
        if(sizeof($attachments) > 0) {
            return
                "Příloha: <a href=\"index.php?web=download&fid=".escapechars($attachments[0]->getId())."\">
                    <button class=\"btn btn-primary\"> Stáhnout</button></a>";
        } else {
            return "";
        }
    }

    private static function getShowEditStr($showEdit, $article) {
        if($showEdit) {
            $disabled = $article->isPublished() ? "disabled" : "";
            return "
                <form method=\"post\" action=\"core/code/edit_article.php\">
                    <div class=\"btn-group\">
                        <input type=\"hidden\" name=\"aid\" value=\"<?php echo escapechars($article->getId()) ?>\">
                        <button type=\"submit\" class=\"btn btn-danger\" name=\"action\"
                                value=\"delete\" ".$disabled.">Smazat
                        </button>
                        <button type=\"submit\" class=\"btn btn-warning\" name=\"action\"
                                value=\"edit\" ".$disabled.">Upravit
                        </button>
                    </div>
                </form>
            ";
        } else {
            return "";
        }
    }
}