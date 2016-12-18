<?php

/**
 * Page header.
 */

require_once ($_SERVER['DOCUMENT_ROOT'].'/kiv-web/core/code/utils.php');

class HeaderView
{

    static function getHTML($title) {
        return "
            <!doctype html>
            <html lang=\"en\">
            <head>
                <meta charset=\"utf-8\">
                <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
                <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
            
                <title>".escapechars($title)."</title>
                <meta name=\"description\" content=\"Semestrální práce z předmětu KIV/WEB\">
                <meta name=\"author\" content=\"Zdenek Vales\">
            
                <link rel=\"stylesheet\" href=\"ui/css/style.css\">
            
                <!-- bootstrap -->
                <link rel=\"stylesheet\" href=\"ui/bootstrap/css/bootstrap.min.css\">
            
            
                <!--[if lt IE 9]>
                <script src=\"https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js\"></script>
                <![endif]-->
                
                <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                <script src=\"https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js\"></script>
                <script src=\"ui/js/scripts.js\"></script>
                <script src=\"ui/bootstrap/js/bootstrap.min.js\"></script>
            </head>
            <body>
        ";
    }
}