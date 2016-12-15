<?php

/*
 * Articles created by author will be displayed here.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/user_dao.php');
require_once('core/code/dao/article_dao.php');
require_once('core/code/dao/review_dao.php');
require_once('core/code/classes/Login.class.php');
require_once ('core/code/utils.php');


// user must be logged in and must be author
$userDao = new UserDao();
$articleDao = new ArticleDao();
$reviewDao = new ReviewDao();
$user = null;
$login = new Login();
if($login->isUserLogged()) {
    $user = $userDao->getUserByUsername($login->getUsername());
} else {
    redirHome();
}

if(!$user->isAuthor()) {
    redirHome();
}
?>
<!doctype html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Konference</title>
    <meta name="description" content="Semestrální práce z předmětu KIV/WEB">
    <meta name="author" content="Zdenek Vales">

    <link rel="stylesheet" href="ui/css/style.css">

    <!-- bootstrap -->
    <link rel="stylesheet" href="ui/bootstrap/css/bootstrap.min.css">


    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>

<body>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="ui/js/scripts.js"></script>
<script src="ui/bootstrap/js/bootstrap.min.js"></script>

<?php
/* this script will choose the correct navbar */
include('ui/navbar.php');
?>
<div class="container">

    <div class="row col-xs-12">
        FAKT VELKÝ LOGO
    </div>

    <div class="col-xs-12 col-sm-9">
        <h1>Moje články</h1>
        <?php
        /* list all published articles and their authors */
        $articles = $articleDao->getArticlesForAuthor($user->getId());
        foreach($articles as $article) {
            $authors = $articleDao->getAuthorsForArticle($article->getId());
//                    var_dump($authors);
            $authorsStr = "";
            foreach($authors as $author) {
                $authorsStr = $authorsStr.$author->getUsername()."; ";
            }

            // trim the last ';'
            $authorsStr = rtrim($authorsStr, "; ");

            //prepare the review object if needed
            if($article->getState() === ArticleState::TO_BE_REVIEWED || $article->getState() === ArticleState::REVIEWED) {
                $reviewResults = $reviewDao->getReviewsForArticle($article->getId());

                // initialize the review object with empty values
                $review = array(1 => array('-','-','-','-'),
                                2 => array('-','-','-','-'),
                                3 => array('-','-','-','-'));

                // fill the array with actuall review results
                for ($i = 0; $i < sizeof($reviewResults); $i++) {
                    if(!$reviewResults[$i]->isReviewed()) {
                        continue;
                    }

                    $revRes = $reviewDao->getReviewResult($reviewResults[$i]->getReviewResultId());
                    $review[$i+1]  = array(
                            $revRes->getCrit1(),
                            $revRes->getCrit2(),
                            $revRes->getCrit3(),
                            $revRes->getCrit4()
                    );
                }
            }
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>
                        <a href="display_article.php?aId=<?php echo escapechars($article->getId());?>">
                            <?php echo escapechars($article->getTitle()); ?>
                        </a>
                    </h4>
                </div>

                <div class="panel-body">
                    <?php
                        include('ui/article_info_author.php');
                    ?>
                </div>

                <div class="panel-footer" style="overflow:hidden;">
                    <div style="float:left;">
                        Autoři: <?php
                        echo escapechars($authorsStr); ?>
                    </div>
                    <div class="text-right">Vytvořeno: <?php echo formatDate($article->getCreated()); ?></div>
                </div>
            </div>
            <?php
        } /*foreach article end*/
        ?>
    </div>

    <?php
    $activeMenuItem = 3;
    include('ui/main_menu.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>