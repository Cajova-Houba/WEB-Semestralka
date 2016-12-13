<?php
require_once('vendor/autoload.php');
require_once ('core/code/utils.php');
use Tracy\Debugger;

Debugger::enable();

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Registrace nového uživatele</title>
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
            <h1>Nový uživatel</h1>
            <form action="core/code/register.php" method="post" id="reg_form">
                <div class="form-group">
                    <label for="first_name">Jméno: </label>
                    <input type="text" id="first_name" class="form-control" name="first_name">
                    <label for="last_name">Příjmení: </label>
                    <input type="text" id="last_name" class="form-control" name="last_name">
                    <label for="username">Nickaname: </label>
                    <input type="text" id="username" class="form-control" name="username">
                    <label for="password">Heslo: </label>
                    <input type="password" id="password" class="form-control" name="password">
                    <label for="password_check">Ověření hesla: </label>
                    <input type="password" id="password_check" class="form-control" name="password_check">
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-link"><a href="index.php">Cancel</a></button>
                    <button type="submit" class="btn btn-primary">Registrovat</button>
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

    <?php
    include ('ui/footer.php');
    ?>



    <script type="text/javascript">
        var formValidator = new Validator("reg_form");
        formValidator.addValidation("first_name", "req", "Vaše jméno nesmí být prázdné");
        formValidator.addValidation("first_name", "maxlen=40", "Jméno může být dlouhé maximálně 40 znaků.");
        
        formValidator.addValidation("last_name", "req", "Vaše příjmení nesmí být prázdné");
        formValidator.addValidation("last_name", "maxlen=40", "Příjmení může být dlouhé maximálně 40 znaků.");
        
        formValidator.addValidation("username", "req", "Váš nickname nesmí být prázdný");
        formValidator.addValidation("username", "maxlen=40", "Nickname může být dlouhé maximálně 40 znaků.");
        
        formValidator.addValidation("password", "req", "Vaše heslo nesmí být prázdné");
        formValidator.addValidation("password", "maxlen=20", "Vaše heslo může být dlouhé maximálně 20 znaků.");
        
        formValidator.addValidation("password_check", "req", "Potvrzení hesla nesmí být prázdné");
        formValidator.addValidation("password_check", "eqelmnt=password", "Potvrzení hesla se neshoduje s původním heslem.");
    </script>
</div>


</body>
</html>