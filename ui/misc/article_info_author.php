<?php
/*
 * Info about article - displayed in authors view.
 * $article and $review objects are expected.
 *
 * $review should be an array so it's possible to get values by $review[1][1] = c2 of the 1st review, $review[1][0] = c1 of the 1st review
 */
if(!isset($article)) {
    exit();
}

if($article->getState() === ArticleState::CREATED) {

?>
    <span class="label label-info">Vytvořený</span>
<?php
} else if ($article->getState() === ArticleState::TO_BE_REVIEWED || $article->getState() === ArticleState::REVIEWED) {
?>
    <span class="label label-warning">Recenzovaný</span>
    <table class="table">
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
            <th>recenzent 1</th>
            <?php
                foreach ($review[1] as $revRes) {
                    echo "<td>".escapechars($revRes)."</td>";
                }
            ?>
        </tr>

        <tr>
            <th>recenzent 2</th>
            <?php
            foreach ($review[2] as $revRes) {
                echo "<td>".escapechars($revRes)."</td>";
            }
            ?>
        </tr>

        <tr>
            <th>recenzent 3</th>
            <?php
            foreach ($review[3] as $revRes) {
                echo "<td>".escapechars($revRes)."</td>";
            }
            ?>
        </tr>
        </tbody>
</table>
<?php
} else if ($article->isPublished()) {
?>
    <span class="label label-success">Publikovaný</span>
<?php
} else if ($article->getState() === ArticleState::REJECTED) {
?>
    <span class="label label-danger">Zamítnutý</span>
<?php
}
?>
