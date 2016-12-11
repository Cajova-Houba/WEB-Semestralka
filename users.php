<?php

/*
 * This page will display all reviewed articles together with their reviews.
 */

require_once('vendor/autoload.php');
use Tracy\Debugger;

Debugger::enable();
require_once('core/code/dao/user_dao.php');
require_once('core/code/dao/role_dao.php');
require_once('core/code/classes/Login.class.php');
require_once('core/code/utils.php');

// user must be logged in and must be reviewer
// also the review id must be set and the review object must be
// assigned to logged user
$userDao = new UserDao();
$roleDao = new RoleDao();
$user = null;
$login = new Login();
if(!$login->isUserLogged()) {
    redirHome();
}
$user = $userDao->getUserByUsername($login->getUsername());
if($user == null || !$user->isAdmin()) {
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
include('ui/navbar.php');
?>

<div class="container">

    <?php
    include ('ui/logo.php');
    ?>

    <div class="col-xs-12 col-sm-9">
        <h1>Uživatelé</h1>
        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Username</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Role</th>
            </tr>
            </thead>

            <tbody>
            <?php
                $users = $userDao->getAll();
                $roles = $roleDao->getAll();
                foreach ($users as $user) {
                ?>
                <tr>
                    <form id="rev_form" action="core/code/modify_user.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo escapechars($user->getId());?>">

                        <td><?php echo escapechars($user->getId());?></td>
                        <td><?php echo escapechars($user->getUsername());?></td>
                        <td><?php echo escapechars($user->getFirstName());?></td>
                        <td><?php echo escapechars($user->getLastName());?></td>
                        <td>
                            <select name="role_id" class="form-control">
                                <?php
                                /* print all roles and mark the user's role as selected */
                                foreach ($roles as $role) {
                                    if ($role->getId() == $user->getRoleId()) {
                                        ?>
                                        <option selected="selected" value="<?php echo escapechars($role->getId())?>">
                                        <?php
                                    } else {
                                        ?>
                                        <option value="<?php echo escapechars($role->getId())?>">
                                        <?php
                                    }

                                    echo escapechars($role->getName());
                                    ?>
                                    </option>
                                    <?php
                                }
                                ?>
                                <?php echo escapechars($user->getRoleId());?>
                            </select>
                        </td>
                        <td><button class="btn btn-primary" name="action" value="update">Update</button></td>
                        <?php
                            if ($user->isEnabled()) {
                        ?>
                            <td><button class="btn btn-warning" name="action" value="disable">Zakázat účet</button></td>
                        <?php
                            } else {
                        ?>
                            <td><button class="btn btn-warning" name="action" value="enable">Povolit účet</button></td>
                        <?php
                            }
                        ?>
                        <td><button class="btn btn-danger"  name="action" value="delete">Smazat účet</button></td>
                    </form>
                </tr>
                <?php
                }
            ?>
            </tbody>
        </table>
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