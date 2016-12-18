<?php

require_once('misc/MainMenuView.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');
require_once ('StandardPageView.php');

/**
 * Template for displaying the article.
 */
class DisplayArticleView extends StandardPageView
{
    /**
     * $data["article"], $data["attachments"], $data["authors"], $data["showEdit"] is expected
     */
    protected static  function getContent($data) {
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

        return $content;
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
                        <input type=\"hidden\" name=\"aid\" value=\"".escapechars($article->getId())."\">
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