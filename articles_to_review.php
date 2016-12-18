<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/UserDao.php');
require_once('core/code/dao/ReviewDao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/utils.php');


$reviewDao = new ReviewDao();
// user must be logged in and must be reviewer
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
        include('ui/NavbarView.php');
    ?>
    
    	
    <div class="container">
		
		<div class="row col-xs-12">
			FAKT VELKÝ LOGO
		</div>
		
		<div class="col-xs-12 col-sm-9">
			<h1>Nové články k hodnocení</h1>
			<?php
                /* list all new articles for reviewer */
                $articles = $reviewDao->getArticlesToReview($user->getId());
//                var_dump($articles);
                foreach($articles as $article) {
                    $title = $article["article"]->getTitle();
                    
                    $rId = htmlspecialchars($article["review"]->getId());
                    
                    // get the name of the admin which has assigned the article for reviewer
                    $assignedBy = "Assigned by: ";
                    $assigner = $userDao->get($article["review"]->getAssignedById());
                    if($assigner == null) {
                        $assignedBy = $assignedBy."-";
                    } else {
                        $assignedBy = $assignedBy.$assigner->getUsername();
                    }
            ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>
                            <?php 
                                echo "<a href=\"rate_article.php?review=".$rId."\">";
                                echo htmlspecialchars($title); 
                                echo "</a>";
                            ?>
                            </h4>
                        </div>

                        <div class="panel-footer">
                            <div class="text-left"><?php echo htmlspecialchars($assignedBy);
                                ?></div>
                        </div>
                    </div>
            <?php
                } /*foreach article end*/
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