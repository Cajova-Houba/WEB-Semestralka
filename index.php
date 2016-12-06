<!doctype html>

<?php
require_once('core/code/user_dao.php');
require_once('core/code/classes/Login.class.php');
require_once('vendor/autoload.php');


// is user logged in
$user = null;
$login = new Login();
if($login->isUserLoged()) {
    $user = getUserByUsername($login->getUsername());
}
?>

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
        if($login->isUserLoged()) {
            if($user->isAdmin()) {
            /*USER ID ADMIN*/
    ?>
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
                            </a> (administrátor)
                        </span>
                    </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                  <ul class="nav navbar-nav">
<!--                    <li class="active"><a href="#">Uživatelé <span class="sr-only">(current)</span></a></li>-->
                    <li><a href="#">Uživatelé</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Články <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="#">Nové články</a></li>
                        <li><a href="#">Recenzované články</a></li>
                      </ul>
                    </li>
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
	<?php
            } else if ($user->isReviewer()){
            /* USER IS REVIEWER */
    ?>
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
                    <li><a href="#">Nové články</a></li>
                    <li><a href="#">Hodnocené články</a></li>
                  </ul>
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
    <?php
            } else {
                /* USER IS JUST A NORMAL USER */
    ?>
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
                </div><!-- /.navbar-collapse -->
              </div><!-- /.container-fluid -->
            </nav>
    <?php
            }
        } else {
            /* NO USER LOGGED IN */
    ?>
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
                        <a href="login.php">
                            Login
                        </a>
                    </span>
                </div>
          </div><!-- /.container-fluid -->
        </nav>
	<?php
       }
    ?>
	<div class="container">
		
		<div class="row col-xs-12">
			FAKT VELKÝ LOGO
		</div>
		
		<div class="col-xs-12 col-sm-9">
			<h1>Konference KIV/WEB</h1>
			<p>Tady jednou bude semestrální práce z KIV/WEB.</p>
			
			<h2>Test php
			    <p>
			        <?php echo phpversion();?>
			     </p>
			</h2>
		</div>
		
		<!-- main menu -->
		<div class="row row-offcanvas row-offcanvas-left">
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
			  <div class="list-group">
				<a href="#" class="list-group-item active">O konferenci</a>
				<a href="#" class="list-group-item">Témata konference</a>
				<a href="#" class="list-group-item">Organizace</a>
				<a href="#" class="list-group-item">Příspěvky</a>
			  </div>
			</div><!--/.sidebar-offcanvas-->
		</div>
		
		<footer>
			<p>&copy; 2016 Zdeněk Valeš</p>
		</footer>
	</div>
    
</body>
</html>