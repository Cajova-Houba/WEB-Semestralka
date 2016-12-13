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
    
    <?php
        /* this script will choose the correct navbar */
        include('ui/navbar.php');
    ?>
      
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
                    $err = escapechars($_GET["err"]);
                    include ('ui/error_panel.php');
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