<?php
    if (isset($_GET['Date'], $_GET['Time'], $_GET['Location'])) {
        $now = "{$_GET['Date']} {$_GET['Time']}";
        $loc = $_GET['Location'];

        print("Logging: $now @ $loc\n");

        unset($_GET['Date']);
        unset($_GET['Time']);
        unset($_GET['Location']);

        require('website.mysql.inc');
        $mysqli = new mysqli($mysql_host, $mysql_username, $mysql_passwd, $mysql_dbname);
        foreach ($_GET as $key => $value) {
            $mysqli->query("INSERT INTO datalogger VALUES ('$now', '$loc', '$key', '$value');");
        }
        $mysqli->close();
     } else {
        print("Invalid log request!");
    }
?>
