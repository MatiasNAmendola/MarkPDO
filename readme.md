#MarkPDO
A simple wrapper for interacting with MySQL databases using PHP Data Objects.

##Usage
Include MarkPDO.class.php on whatever page you need to use it on.

1. Instantiate 

    // Use connection info defined in MarkPDO.class.php
    $dbh = new MarkPDO();

    // Use your own connection info,
    $dbh = new MarkPDO($host, $dbname, $user, $password);
