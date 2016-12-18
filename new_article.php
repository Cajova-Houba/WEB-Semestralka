<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/UserDao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/utils.php');
require_once('core/code/dao/ArticleDao.php');


// is user logged in
$user = null;
$userDao = new UserDao();
$login = new Login();
$articleDao = new ArticleDao();
// user has to be an author to access this page
if($login->isUserLogged()) {
    $user = $userDao->getUserByUsername($login->getUsername());
}   

if($user == null || !$user->isAuthor()) {
    redirHome();
}

//if the aid is specified, author can edit his articles
$article = null;
if(isset($_GET["aid"])) {
    $aid = escapechars($_GET["aid"]);

    // if the user isn't author, or the article has been already published
    // editing is not possible
    if($articleDao->isAuthor($aid, $user->getId()) && !$articleDao->isPublished($aid)) {
        $article = $articleDao->get($aid);
    }
}


?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Nový článek</title>
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
      
    <!--
    
    Container
    -->
	<div class="container">
		
		<div class="row col-xs-12">
			FAKT VELKÝ LOGO
		</div>
		
		<div class="col-xs-12 col-sm-9">
            <?php
                if($article == null) {
                    echo "<h1>Nový článek</h1>";
                    $title = "";
                    $content = "";
                } else {
                    echo "<h1>Upravit článek</h1>";
                    $title = escapechars($article->getTitle());
                    $content = escapechars($article->getContent());
                }
            ?>
			<form action="core/code/new_article.php" method="post" id="art_form" enctype="multipart/form-data">
                <?php
                    if($article != null) {
                ?>
                        <input type="hidden" name="aid" value="<?php echo escapechars($article->getId())?>">
                <?php
                    }
                ?>
			    <div class="form-group">
			        <label for="title">Název</label>
			        <input type="text" name="title" id="title" class="form-control" value="<?php echo $title?>">
			    </div>
			    
                <div class="form-group">
			        <label for="content">Článek:</label>
			        <textarea name="content" id="content" cols="30" rows="10" class="form-control"><?php echo $content ?></textarea>
                </div>
			    
                <div class="form-group">
			        <label for="attachment">Příloha:</label>
			        <input type="file" id="attachment" name="attachment">
			        <p class="help-block">Volitelný</p>
			    </div>
			    
			    <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Uložit</button>
                </div>
			</form>
            
            <?php 
                if(isset($_GET["err"])) {
                    $err = escapechars($_GET["err"]);
                    include ('ui/ErrorView.php');
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