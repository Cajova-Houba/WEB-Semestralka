<?php
    require_once ('core/code/utils.php');
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Přihlášení</title>
    <meta name="description" content="Semestrální práce z předmětu KIV/WEB">
    <meta name="author" content="Zdenek Vales">
  
    <link rel="stylesheet" href="ui/css/style.css">
	
	<!-- bootstrap -->
	<link rel="stylesheet" href="ui/bootstrap/css/bootstrap.min.css">
	
	<!-- js validator -->
	<script src="ui/js/gen_validatorv4.js" type="text/javascript"></script>
	
  
    <!--[if lt IE 9]>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
       <div class="col-xs-6 col-md-4"></div>
       <div class="col-xs-6 col-md-4">
        <h1>Login</h1>
        <form action="core/code/login.php" method="post">
            <div class="form-group">
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" class="form-control">
                <label for="password">Heslo: </label>
                <input type="password" id="password" name="password" class="form-control">
            </div>    
            
            <div class="btn-group">
                <a href="index.php" role="button" class="btn btn-link">Cancel</a>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
           <?php
           if(isset($_GET["err"])) {
               $err = escapechars($_GET["err"]);
           }
           include ('ui/error_panel.php');
           ?>
       </div>
    </div>


    <div class="row"></div>
    <?php
        include ('ui/footer.php');
    ?>
</div>


</body>
</html>