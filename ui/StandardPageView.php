<?php
require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');
require_once('misc/MainMenuView.php');
require_once('misc/NavbarView.php');
require_once('misc/LogoView.php');

/**
 * A template for standard page.
 *
 * Contains header, footer, logo, main menu and abstract method for obtaining the page content.
 *
 */
abstract class StandardPageView {

    static function getHTML($title, $menuActive, $navbar, $data) {
        $header = HeaderView::getHTML($title);
        $footer = FooterView::getHTML();
        $mainMenu = MainMenuView::getHTML($menuActive);
        $navbar = NavbarView::getHTML($navbar, $data);
        $logo = LogoView::getHTML();
        $content =  static::getContent($data);

        return $header.$navbar.$logo.$content.$mainMenu.$footer;
    }

    /**
     * Returns the page's conent
     */
    protected static abstract function getContent($data);

}