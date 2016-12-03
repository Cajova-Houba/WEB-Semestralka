<?php
$host = 'localhost';
$dbname = 'kiv-web';

/* persistent connection, don't forget to close it! */
$dbh = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $pass, array(
    PDO::ATTR_PERSISTENT => true
));
?>
