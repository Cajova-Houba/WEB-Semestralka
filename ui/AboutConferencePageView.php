<?php
require_once ('StandardPageView.php');

/**
 * Template for the page about the organization of the conference.
 */
class AboutConferencePageView extends StandardPageView {

    static function getHTML($title, $menuActive, $navbar, $data) {
        return parent::getHTML($title, $menuActive, $navbar, $data);
    }

    protected static function getContent($data) {
        return "
            <div class=\"col-xs-12 col-sm-9\">
                <h1>Organizační detaily</h1>
                <p>
                    Konference je veřejná a každý se může zůčastnit - pokud ještě nemáte účet, registrujte se <a href=\"register.php\">zde</a>.
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
        ";
    }

}