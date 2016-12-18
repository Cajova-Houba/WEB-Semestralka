<?php

require_once('misc/MainMenuView.php');
require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

/**
 * Template for manage users page.
 */
class ManageUsersPageView extends StandardPageView {

    /**
     * $data["firstName"], $data["lastName"], $data["users"] and $data["roles"] are expected.
     */
    static function getHTML($navbar, $data) {
        return parent::getHTML("Uživatelé", MainMenuView::NOTHING_ACTIVE, $navbar, $data);
    }

    protected static  function getContent($data) {
        $users = $data["users"];
        $roles = $data["roles"];
        $usersStr = "";


        // rows with users and interaction buttons
        foreach ($users as $user) {

            $userEnableDisable = "";
            if ($user->isEnabled()) {
                $userEnableDisable = "<td><button class=\"btn btn-warning\" name=\"action\" value=\"disable\">Zakázat účet</button></td>";
            } else {
                $userEnableDisable = "<td><button class=\"btn btn-warning\" name=\"action\" value=\"enable\">Povolit účet</button></td>";
            }

            $usersStr = $usersStr."
                <tr>
                    <form id=\"rev_form\" action=\"core/code/modify_user.php\" method=\"post\">
                        <input type=\"hidden\" name=\"user_id\" value=\"".escapechars($user->getId())."\">

                        <td>".escapechars($user->getId())."</td>
                        <td>".escapechars($user->getUsername())."</td>
                        <td>".escapechars($user->getFirstName())."</td>
                        <td>".escapechars($user->getLastName())."</td>
                        <td>
                            <select name=\"role_id\" class=\"form-control\">
            ";
            /* print all roles and mark the user's role as selected */
            $rolesStr = "";
            foreach ($roles as $role) {
                if ($role->getId() == $user->getRoleId()) {
                    $rolesStr = $rolesStr.
                        "<option selected=\"selected\" value=\"".escapechars($role->getId())."\">".escapechars($role->getName())."</option>";
                } else {
                    $rolesStr = $rolesStr."<option value=\"".escapechars($role->getId())."\">".escapechars($role->getName())."</option>";
                }

            }
            $usersStr = $usersStr.$rolesStr."
                            </select>
                        </td>
                        <td><button class=\"btn btn-primary\" name=\"action\" value=\"update\">Update</button></td>
                        ".$userEnableDisable."
                        <td><button class=\"btn btn-danger\"  name=\"action\" value=\"delete\">Smazat účet</button></td>
                    </form>
                </tr>
            ";
        }

        // content of the whole page
        $content = "
            <div class=\"col-xs-12 col-sm-9\">
                <h1>Uživatelé</h1>
                <table class=\"table\">
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
                        ".$usersStr."
                    </tbody>
                </table>
            </div>
        ";

        return $content;
    }
}