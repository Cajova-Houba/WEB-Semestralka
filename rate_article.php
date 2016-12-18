<?php
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
if($user == null || !$user->isReviewer()) {
    redirHome();
}

if(!isset($_GET["review"])) {
    redirHome();
}
$revId = htmlspecialchars($_GET["review"]);
$review = $reviewDao->get($revId);
if($review == null || $review->getReviewerId() !== $user->getId()) {
    redirHome();
}
$article = $articleDao->get($review->getArticleId());
if($article == null) {
    redirHome();
}

$authors = $articleDao->getAuthorsForArticle($article->getId());
$authorsStr = authorsToString($authors);

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
			<h1>Hodnocení článku</h1>
			
			<div class="panel panel-default">
			    <div class="panel-heading">
			        <?php
                        echo htmlspecialchars($article->getTitle());
                    ?>
			    </div>
			    
			    <div class="panel-body">
			        <?php
                        echo htmlspecialchars($article->getContent());
                    ?>
			    </div>
			    
			    <div class="panel-footer" style="overflow:hidden">
                    <div style="float:left">
                        Authors: <?php echo escapechars($authorsStr);?>
                    </div>
                    <div class="text-right">
                        <?php
                            echo htmlspecialchars($article->getCreated());
                        ?>
                    </div>
			    </div>
			</div>
			
			<div class="col-xs-6 col-md-4">
                <form method="post" action="core/code/rate_article.php" class="form-horizontal">
                    <h3>Hodnocení:</h3>
                    <div class="form-group">
                        <label for="c1" class="col-sm-6 control-label">Obsah: </label>
                        <div class="col-sm-6">
                            <input type="number" min="0" max="10" class="form-control" id="c1" name="c1" value="5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="c2" class="col-sm-6 control-label">Forma: </label>
                        <div class="col-sm-6">
                            <input type="number" min="0" max="10" class="form-control" id="c2" name="c2" value="5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="c3" class="col-sm-6 control-label">Originalita: </label>
                        <div class="col-sm-6">
                            <input type="number" min="0" max="10" class="form-control" id="c3" name="c3" value="5">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="c4" class="col-sm-6 control-label">Osobní názor: </label>
                        <div class="col-sm-6">
                            <input type="number" min="0" max="10" class="form-control" id="c4" name="c4" value="5">
                        </div>
                    </div>
                    
                    <input type="hidden" name="review_id" value="<?php echo $revId?>">

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Uložit hodnocení</button>
                    </div>
                </form>
                <?php
                if(isset($_GET["err"])) {
                    $err = escapechars($_GET["err"]);
                }
                include ('ui/ErrorView.php');
                ?>
			</div>


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