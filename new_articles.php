<?php
/*
 * New articles will be displayed here and admin will assign reviewers to those articles.
 */
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/UserDao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/dao/ArticleDao.php');


$articleDao = new ArticleDao();
$userDao = new UserDao();
$user = null;
$login = new Login();
// is user logged in and is admin
if (!$login->isUserLogged()){
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if(!$user->isAdmin()) {
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
        <h1>Nové články</h1>
        <?php
            $articles = $articleDao->getNewArticles();
            $reviewers = $userDao->getReviewers();
            $reviewersOptTags = "";
            //prepare the <option> tags with reviewers
            $sel = true;
            foreach ($reviewers as $reviewer) {
                $reviewersOptTags = $reviewersOptTags."<option value=\"".$reviewer->getId()."\" ";

                // the first one will be selected.
                if ($sel) {
                    $reviewersOptTags = $reviewersOptTags." selected=\"selected\"";
                    $sel = false;
                }
                $reviewersOptTags = $reviewersOptTags.">".htmlspecialchars($reviewer->getUsername())."</option>\n";
            }

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

                    <!-- form to choose reviewers in panel body -->
                    <div class="panel-body">
                        <form method="post" action="core/code/assign_to_reviewers.php" >
                            <input type="hidden" name="article_id" value="<?php echo $article->getId()?>">
                            <table>
                                <thead>
                                    <tr>
                                        <th>1. recenzent</th>
                                        <th>2. recenzent</th>
                                        <th>3. recenzent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <select class="form-control" name="r1">
                                                <?php echo $reviewersOptTags ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="r2">
                                                <?php echo $reviewersOptTags ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control" name="r3">
                                                <?php echo $reviewersOptTags ?>
                                            </select>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Přiděl k recenzi</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                    <div class="panel-footer" style="overflow:hidden">
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
    include('ui/MainMenuView.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>