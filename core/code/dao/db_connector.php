<?php

/*
 Opens a new connection. Don't forget to close it.
*/
function getConnection() {
    $host = 'localhost';
    $dbname = 'kiv-web';
    $user = "root";
    $pass = "r00t";

    /* persistent connection, don't forget to close it! */
    $dbh = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8', $user, $pass, array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ));
    
    return $dbh;
}
?>
