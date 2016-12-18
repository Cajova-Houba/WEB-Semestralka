<?php

/**
 * Template for article info for author.
 */
class ArticleInfoForAuthorView {

    /**
     * $data["article"] and $data["review"]
     */
    static function getHTML($data) {
        $article = $data["article"];

        $content = "";
        if($article->getState() === ArticleState::CREATED) {
            $content = "<span class=\"label label-info\">Vytvořený</span>";
        } else if ($article->getState() === ArticleState::TO_BE_REVIEWED || $article->getState() === ArticleState::REVIEWED) {
            $review = $data["review"];
            $content = "
            <span class=\"label label-warning\">Recenzovaný</span>
            <table class=\"table\">
                <thead>
                <tr>
                    <th></th>
                    <th>c1</th>
                    <th>c2</th>
                    <th>c3</th>
                    <th>c4</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>recenzent 1</th>";

            // add review results for reviewers
            foreach ($review[1] as $revRes) {
                $content = $content."<td>".escapechars($revRes)."</td>";
            }
            $content = $content.
                "</tr>

                <tr>
                    <th>recenzent 2</th>";
            foreach ($review[2] as $revRes) {
                $content = $content."<td>".escapechars($revRes)."</td>";
            }
            $content = $content.
                "</tr>

                <tr>
                    <th>recenzent 3</th>";
            foreach ($review[3] as $revRes) {
                $content = $content."<td>".escapechars($revRes)."</td>";
            }
            $content = $content.
                "</tr>
                </tbody>
        </table>";
        } else if ($article->isPublished()) {
            $content = $content."<span class=\"label label-success\">Publikovaný</span>";
        } else if ($article->getState() === ArticleState::REJECTED) {
            $content = $content."<span class=\"label label-danger\">Zamítnutý</span>";
        }

        return $content;
    }

}