<?php

/*
 * This page will display articles reviewed by a reviewer.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/user_dao.php');
require_once('core/code/dao/review_dao.php');
require_once('core/code/dao/article_dao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/utils.php');


$reviewDao = new ReviewDao();
$articleDao = new ArticleDao();
// user must be logged in and must be reviewer
// also the review id must be set and the review object must be
// assigned to logged user
$userDao = new UserDao();
$user = null;
$login = new Login();
if(!$login->isUserLogged()) {
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if($user == null || !$user->isReviewer()) {
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

    <?php
        include ('ui/logo.php');
    ?>

    <div class="col-xs-12 col-sm-9">
        <h1>Ohodnotil jsem</h1>
        <?php
        $articles = $reviewDao->getReviewedArticles($user->getId());

        // print all articles
        foreach ( $articles as $article ) {
            $title = htmlspecialchars($article->getTitle());
            $created = $article->getCreated();
            $authors = $articleDao->getAuthorsForArticle($article->getId());
            $authorsStr = "";
            foreach($authors as $author) {
                $authorsStr = $authorsStr.$author->getUsername()."; ";
            }

            // trim the last ';'
            $authorsStr = rtrim($authorsStr, "; ");
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo $title ?>
                </div>

                <div class="panel-body" style="overflow:hidden">
                    <div style="float:left">
                        Authors: <?php echo htmlspecialchars($authorsStr); ?>
                    </div>
                    <div class="text-right">
                        <?php echo htmlspecialchars($article->getCreated());?>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>

    <?php
    include('ui/main_menu.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>