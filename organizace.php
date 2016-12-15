<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/user_dao.php');
require_once('core/code/classes/Login.class.php');


// is user logged in
$userDao = new UserDao();
$user = null;
$login = new Login();
if ($login->isUserLogged()) {
    $user = $userDao->getUserByUsername($login->getUsername());
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
include('ui/navbar.php');
?>

<div class="container">

    <?php
    include('ui/logo.php');
    ?>

    <div class="col-xs-12 col-sm-9">
        <h1>Organizační detaily</h1>
        <p>
            Konference je veřejná a každý se může zůčastnit - pokud ještě nemáte účet, registrujte se <a href="register.php">zde</a>.
        </p>
        <p>
            Konferenci je možno navštívit v nové budově FAV v areálu Západočeské Univerzity v Plzni na Borech - konferenční sály US 207 a US 217.
            Jednotlivé přednášky a prezentace budou probíhat ve dnech 15. 12. až 21. 12. 2016 v čase od 9:00 do 16:00. V areálu ZČU bude možné navštívit několik
            workshopů organizovaných jak hostujícími firmami, tak samotnou univerzitou.
        </p>


        <h2>Organizátoři konference:</h2>
        <ul>
            <li>Zdeněk Valeš - zdenek.vales@kiv-web.cz</li>
            <li>ZČU/KIV - zastupce.kiv@kiv-web.cz</li>
            <li>CIV - zastupce.civ@kiv-web.cz</li>
            <li>Gill Bates - gill.bates@kiv-web.cz</li>
        </ul>

        <h2>Oficiální kontakt</h2>
        <ul>
            <li>Telefon: +420 123 456 789</li>
            <li>Email: ofic.email@kiv-web.cz</li>
            <li>Email pro dotazy a připomínky: pripominky@kiv-web.cz</li>
        </ul>
    </div>

    <?php
    $activeMenuItem = 2;
    include('ui/main_menu.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>