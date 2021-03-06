<?php

/*
 * This page will display all reviewed articles together with their reviews.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/UserDao.php');
require_once('core/code/dao/ReviewDao.php');
require_once('core/code/dao/ArticleDao.php');
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
if($user == null || !$user->isAdmin()) {
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
include('ui/NavbarView.php');
?>

<div class="container">

    <?php
    include ('ui/LogoView.php');
    ?>

    <div class="col-xs-12 col-sm-9">
        <h1>Hodnocené články</h1>
            <b>Legenda:</b>
            <ul>
                <li>c1 = Obsah</li>
                <li>c2 = Forma</li>
                <li>c3 = Originalita</li>
                <li>c4 = Osobní názor</li>
            </ul>
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="2">Článek</th>
                        <th rowspan="2">Autor</th>
                        <th colspan="4">1. recenzent</th>
                        <th colspan="4">2. recenzent</th>
                        <th colspan="4">3. recenzent</th>
                        <th></th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>c1</th>
                        <th>c2</th>
                        <th>c3</th>
                        <th>c4</th>

                        <th>c1</th>
                        <th>c2</th>
                        <th>c3</th>
                        <th>c4</th>

                        <th>c1</th>
                        <th>c2</th>
                        <th>c3</th>
                        <th>c4</th>

                        <th></th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        $reviewedArticles = $reviewDao->getAllReviewedArticles();

                        foreach ($reviewedArticles as $reviewedArticle) {
                            $article = $reviewedArticle["article"];
                            $authors = $articleDao->getAuthorsForArticle($article->getId());

                            $reviewResult1 = isset($reviewedArticle["reviewResult1"]) ? $reviewedArticle["reviewResult1"] : ReviewResult::newResult('-','-','-','-');
                            $reviewResult2 = isset($reviewedArticle["reviewResult2"]) ? $reviewedArticle["reviewResult2"] : ReviewResult::newResult('-','-','-','-');
                            $reviewResult3 = isset($reviewedArticle["reviewResult3"]) ? $reviewedArticle["reviewResult3"] : ReviewResult::newResult('-','-','-','-');
                            $authorsStr = authorsToString($authors);

                            //if all reviews are not set, disable publish and reject buttons
                            $allSet = true;
                            for ($i = 1; $i <= 3; $i++) {
                                $allSet = $allSet & isset($reviewedArticle["reviewResult".$i]);
                            }
                            $disabled = $allSet ? "" : "disabled";
                    ?>
                    <tr>
                        <form id="rev_form" action="core/code/publish_article.php" method="post">
                            <input type="hidden" name="article_id" value="<?php echo escapechars($article->getId());?>">
                            <td><?php echo escapechars($article->getTitle());?></td>
                            <td><?php echo escapechars($authorsStr);?></td>

                            <td><?php echo escapechars($reviewResult1->getCrit1());?></td>
                            <td><?php echo escapechars($reviewResult1->getCrit2());?></td>
                            <td><?php echo escapechars($reviewResult1->getCrit3());?></td>
                            <td><?php echo escapechars($reviewResult1->getCrit4());?></td>

                            <td><?php echo escapechars($reviewResult2->getCrit1());?></td>
                            <td><?php echo escapechars($reviewResult2->getCrit2());?></td>
                            <td><?php echo escapechars($reviewResult2->getCrit3());?></td>
                            <td><?php echo escapechars($reviewResult2->getCrit4());?></td>

                            <td><?php echo escapechars($reviewResult3->getCrit1());?></td>
                            <td><?php echo escapechars($reviewResult3->getCrit2());?></td>
                            <td><?php echo escapechars($reviewResult3->getCrit3());?></td>
                            <td><?php echo escapechars($reviewResult3->getCrit4());?></td>

                            <td>
                                <button class="btn btn-primary" type="submit" name="action" value="publish" <?php echo $disabled?>>Publish it!</button>
                            </td>
                            <td>
                                <button class="btn btn-danger" type="submit" name="action" value="reject" <?php echo $disabled?>>Reject it!</button>
                            </td>
                        </form>
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table>
    </div>

    <?php
    include('ui/MainMenuView.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>