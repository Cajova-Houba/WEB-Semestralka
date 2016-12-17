<?php

/*

    Main menu. If the $activeMenuItem is set (1-4), the menu item will be highlited.
    
*/

class MainMenuView {

    // active menu items
    const O_KONFERENCI_ACTIVE = 0;
    const ORGANIZACE_ACTIVE = 1;
    const PRISPEVKY_ACTIVE = 3;

    static function getHTML($activeMenuItem) {

        // set the class of active menu item to 'active'
        $activeMenuItems = array(
                MainMenuView::O_KONFERENCI_ACTIVE => '',
                MainMenuView::ORGANIZACE_ACTIVE => '',
                MainMenuView::PRISPEVKY_ACTIVE => '');
        if(isset($activeMenuItem)) {
            $activeMenuItems[$activeMenuItem] = 'active';
        }

        return "
            <!-- main menu -->
            <div class=\"row row-offcanvas row-offcanvas-left\">
                <div class=\"col-xs-6 col-sm-3 sidebar-offcanvas\" id=\"sidebar\">
                  <div class=\"list-group\">
                        <a href=\"index.php\" class=\"list-group-item ".$activeMenuItems[MainMenuView::O_KONFERENCI_ACTIVE]."\">O konferenci</a>
                        <a href=\"index.php\" class=\"list-group-item ".$activeMenuItems[MainMenuView::ORGANIZACE_ACTIVE]."\">Organizace</a>
                        <a href=\"index.php\" class=\"list-group-item ".$activeMenuItems[MainMenuView::PRISPEVKY_ACTIVE]."\">Příspěvky</a>
                  </div>
                </div><!--/.sidebar-offcanvas-->
            </div>
        ";
    }
}
?>