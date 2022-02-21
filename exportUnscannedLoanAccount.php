<?php
require "connection.php";

    $ulaFrom = $_POST['ulaFrom'];
    $ulaTo = $_POST['ulaTo'];
    $param = array($ulaFrom,$ulaTo,$ulaFrom,$ulaTo,$ulaFrom,$ulaTo,$ulaFrom,$ulaTo);

header('Content-Type: text/csv; charset-`utf-8');
header('Content-Disposition: attachment; filename=LoanDelay_'.$Branch.'_'.$ulaFrom.'-'.$ulaTo.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('UNSCANNED LOAN ACCOUNT','CUTOFFDATE','FROM:'.$ulaFrom,'TO:'.$ulaTo));
fputcsv($output, array('BranchNo','ClientID','AccountNumber','AccountName','GrantedDateTime','MaturityDateTime','PaymentStatus'));
$sql = "SELECT	lle.BranchNo,
            		lle.ClientID,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.AccountName,
            		CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) AS GrantedDateTime,
            		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) AS MaturityDateTime,
            		lle.PaymentStatus
            FROM $DB1.dbo.loanLedger lle
            WHERE lle.ApprovalDateTime BETWEEN (?) AND (?)
            AND lle.AccountStatus = 'Released'
            AND lle.AccountNumber NOT IN (
            							SELECT DISTINCT dms.AccountNumber
            							FROM $DB1.dbo.ddmsFilename dms
            							WHERE dms.AccountType = 'Loan Account'
            							)
        UNION ALL
        SELECT	lle.BranchNo,
            		lle.ClientID,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.AccountName,
            		CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) AS GrantedDateTime,
            		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) AS MaturityDateTime,
            		lle.PaymentStatus
            FROM $DB2.dbo.loanLedger lle
            WHERE lle.ApprovalDateTime BETWEEN (?) AND (?)
            AND lle.AccountStatus = 'Released'
            AND lle.AccountNumber NOT IN (
            							SELECT DISTINCT dms.AccountNumber
            							FROM $DB2.dbo.ddmsFilename dms
            							WHERE dms.AccountType = 'Loan Account'
            							)
        UNION ALL
        SELECT	lle.BranchNo,
            		lle.ClientID,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.AccountName,
            		CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) AS GrantedDateTime,
            		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) AS MaturityDateTime,
            		lle.PaymentStatus
            FROM $DB3.dbo.loanLedger lle
            WHERE lle.ApprovalDateTime BETWEEN (?) AND (?)
            AND lle.AccountStatus = 'Released'
            AND lle.AccountNumber NOT IN (
            							SELECT DISTINCT dms.AccountNumber
            							FROM $DB3.dbo.ddmsFilename dms
            							WHERE dms.AccountType = 'Loan Account'
            							)
        UNION ALL
        SELECT	lle.BranchNo,
            		lle.ClientID,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.AccountName,
            		CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) AS GrantedDateTime,
            		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) AS MaturityDateTime,
            		lle.PaymentStatus
            FROM $DB4.dbo.loanLedger lle
            WHERE lle.ApprovalDateTime BETWEEN (?) AND (?)
            AND lle.AccountStatus = 'Released'
            AND lle.AccountNumber NOT IN (
            							SELECT DISTINCT dms.AccountNumber
            							FROM $DB4.dbo.ddmsFilename dms
            							WHERE dms.AccountType = 'Loan Account'
            							)
            ORDER BY BranchNo,GrantedDateTime DESC";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
