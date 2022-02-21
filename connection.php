<?php
    $DB = "Cyber1";
    $DB1 = "Cyber1";
    $DB2 = "Cyber2";
    $DB3 = "Cyber3";
    $DB4 = "Cyber4";
    $Branch = 'Consolidated';
    $serverName     = "10.1.1.5";
    $connectionInfo = array("Database"     => "PAMDB",
                            "UID"          => "cyberuser",
                            "PWD"          => "cyberuser",
                            "CharacterSet" => "UTF-8");
    if (($conn = sqlsrv_connect($serverName, $connectionInfo)) == false) {
        die(print_r(sqlsrv_errors(), true));
    }
 ?>
