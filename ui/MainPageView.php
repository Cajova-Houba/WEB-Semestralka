<?php
/**
 * A template for main page
 */

require_once('misc/HeaderView.php');
require_once('misc/FooterView.php');
require_once('misc/MainMenuView.php');
require_once('misc/NavbarView.php');
require_once('misc/LogoView.php');
require_once('misc/InfoView.php');

class MainPageView {

    static function getHTML($info, $navbar, $data) {
        $head = HeaderView::getHTML('O konferenci');
        $mainMenu = MainMenuView::getHTML(MainMenuView::O_KONFERENCI_ACTIVE);
        $footer = FooterView::getHTML();
        $navbar = NavbarView::getHTML($navbar, $data);
        $logo = LogoView::getHTML();
        $infoPan = $info == null ? '' : InfoView::getHTML($info);
        $content = "
            <div class=\"col-xs-12 col-sm-9\">
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
        ";

        return $head.$navbar.$infoPan.$logo.$content.$mainMenu.$footer;
    }
}
?>