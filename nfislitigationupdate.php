<?php
require 'connection.php';

$param = [$_POST['status'], $_POST['account']];
$sql = 'UPDATE nfispastdueindiv
              SET [Action] = ?
              WHERE LoanReferenceNo = ?';
if (sqlsrv_query($conn, $sql, $param) === false) {
    die(print_r(sqlsrv_errors()));
} else {
    print_r('Record updated successfully!');
}

sqlsrv_close($conn);

session_start();

// echo print_r($_POST, true);

?>
