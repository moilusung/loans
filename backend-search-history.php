<?php

include('connection.php');

if(isset($_REQUEST["term"])) {

    $sql = "SELECT AccountNumber,AccountName,LoanNo
            FROM $DB1.dbo.Loanledger WHERE (AccountName LIKE ?)
            UNION ALL
            SELECT AccountNumber,AccountName,LoanNo
            FROM $DB2.dbo.Loanledger WHERE (AccountName LIKE ?)
            UNION ALL
            SELECT AccountNumber,AccountName,LoanNo
            FROM $DB3.dbo.Loanledger WHERE (AccountName LIKE ?)
            UNION ALL
            SELECT AccountNumber,AccountName,LoanNo
            FROM $DB4.dbo.Loanledger WHERE (AccountName LIKE ?)";
    $param_term = array($_REQUEST["term"] . '%',$_REQUEST["term"] . '%',$_REQUEST["term"] . '%',$_REQUEST["term"] . '%');
    $stmt = sqlsrv_query($conn, $sql, $param_term);

    if($stmt) {
        while($row = sqlsrv_fetch_array($stmt)){
                    echo "<p>" . $row[1] .' - '. $row[2] . "</p>";
        }

        sqlsrv_free_stmt($stmt);

    } else {
        echo "<p>No matches found</p>";
    }

}

sqlsrv_close($conn);
?>
