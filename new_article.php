<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/user_dao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/utils.php');


// is user logged in
$user = null;
$userDao = new UserDao();
$login = new Login();
// user has to be an author to access this page
if($login->isUserLogged()) {
    $user = $userDao->getUserByUsername($login->getUsername());
}   

if($user == null || !$user->isAuthor()) {
    header('Location: http://localhost/kiv-web/');
    die('http://localhost/kiv-web/');
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
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <span class="navbar-brand">
                    <a href="#">
                        <?php
                            echo htmlspecialchars($user->getFirstName())." ".htmlspecialchars($user->getLastName());
                        ?>
                    </a> (recenzent)
                </span>
            </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
<!--                    <li class="active"><a href="#">Nové články<span class="sr-only">(current)</span></a></li>-->
            <li><a href="#">Nový článek</a></li>
            <li><a href="#">Moje články</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
              <li><a href="logout.php">Logout</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>
      
    <!--
    
    Container
    -->
	<div class="container">
		
		<div class="row col-xs-12">
			FAKT VELKÝ LOGO
		</div>
		
		<div class="col-xs-12 col-sm-9">
			<h1>Nový článek</h1>
			<form action="core/code/new_article.php" method="post" id="art_form">
			    <div class="form-group">
			        <label for="title">Název</label>
			        <input type="text" name="title" id="title" class="form-control">
			    </div>
			    
                <div class="form-group">
			        <label for="content">Článek:</label>
			        <textarea name="content" id="content" cols="30" rows="10" class="form-control"></textarea>
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
                    $openTag = "<br><div class=\"row\"><div class=\"panel panel-danger\"> <div class=\"panel-heading\"> <h3 class=\"panel-title\">Chyba! </h3></div> <div class=\"panel-body\">";
                    $closeTag = "</div></div></div>";
                    $errCode = $_GET["err"];

                    switch($errCode) {
                        case Errors::TITLE_NOT_OK: 
                                    echo $openTag."Název článku je chybný.".$closeTag;
                                    break;

                        case Errors::CONTENT_NOT_OK: 
                                    echo $openTag."Obsah je špatný.".$closeTag;
                                    break;

                        default: echo $openTag."Neznámá chyba.".$closeTag;
                                    break;
                    }
                }
            ?>
		</div>
		
		
		<!-- main menu -->
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
			  <div class="list-group">
				<a href="index.php" class="list-group-item">O konferenci</a>
				<a href="#" class="list-group-item">Témata konference</a>
				<a href="#" class="list-group-item">Organizace</a>
				<a href="articles.php" class="list-group-item">Příspěvky</a>
			  </div>
			</div><!--/.sidebar-offcanvas-->
		</div>
		
		<footer>
			<p>&copy; 2016 Zdeněk Valeš</p>
		</footer>
	</div>
    
</body>
</html>