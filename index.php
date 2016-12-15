<?php
require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once ('core/code/utils.php');
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
<?php
if(isset($_GET["info"])) {
    $info = $_GET["info"];
}
include('ui/info_panel.php');
?>

<div class="container">

    <?php
        include('ui/logo.php');
    ?>

    <div class="col-xs-12 col-sm-9">
        <h1>Konference New World Technologies</h1>
        <p>
            Vítejte na webu konference NWT. Na letošním ročníku budou představy poslední novinky a trendy v informačních technologiích.
            Jedním z nejočekávanějších bude nový open source operační systém dosud neznámého amerického studenta Gilla Batese. Oproti tomu
            předvede novou verzi svého operačního systému i počítačový gigant Linux Corp v čele s Tinusem Lorvaldsem.
        </p>

        <p>
            Mimo to se dočkáme i několika technických novinek, například nová verze diskety s pamětí 1 GB, HDD speciálně navržený pro swapování, který
            nahrazuje příliš pomalé RAM.
        </p>

        <h2>Hlavní organizátoři konference:</h2>
        <ul>
            <li>Zdeněk Valeš - zdenek.vales@kiv-web.cz</li>
            <li>ZČU/KIV - zastupce.kiv@kiv-web.cz</li>
        </ul>

        <h2>Hlavní sponzoři konference:</h2>
        <ul>
            <li>Linux Corp</li>
            <li>MBI</li>
            <li>Západočeská Univerzita v Plzni</li>
        </ul>
    </div>

    <?php
    $activeMenuItem = 0;
    include('ui/main_menu.php');
    ?>

    <footer>
        <p>&copy; 2016 Zdeněk Valeš</p>
    </footer>
</div>

</body>
</html>