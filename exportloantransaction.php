<?php
require "connection.php";

$timezone = "Asia/Colombo";
date_default_timezone_set($timezone);
$today = date("Y-m-d");

    $accountnumber = $_POST['accnum'];
    $accountname = $_POST['accname'];
    $loannum = $_POST['loannum'];
    // $AccountName = $_POST['accname'];
    $LoanNo = $_POST['loannum'];
    $param = array($accountnumber,$accountnumber,$accountnumber,$accountnumber);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=LoanTransaction_'.$Branch.'_'.$LoanNo.'_'.$today.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN TRANSACTION HISTORY','','AccountNumber:',"'".$accountnumber,'AccountName:',$accountname,'LoanNo:',$LoanNo));
fputcsv($output, array('TransactionDateTime','TransactionType','StatusDescription','OriginalPrincipal','PrincipalBalance','PrincipalPayment','OriginalInterest','InterestBalance','InterestPayment','PDPPayment','PDPonAmortPayment','WaivedPDP','WaivedPDPonAmort','PDIPayment','PDIOnAmortPayment','WaivedPDI','WaivedPDIonAmort','UpdatedDateTime'));
$sql = "SELECT CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime,
      		ltr.TransactionType,
      		lst.StatusDescription,
      		ltr.OriginalPrincipal,
      		ltr.PrincipalBalance,
      		ltr.PrincipalPayment,
      		ltr.OriginalInterest,
      		ltr.InterestBalance,
      		ltr.InterestPayment,
      		ltr.PDPPayment,
      		ltr.PDPonAmortPayment,
      		ltr.WaivedPDP,
      		ltr.WaivedPDPonAmort,
      		ltr.PDIPayment,
      		ltr.PDIOnAmortPayment,
      		ltr.WaivedPDI,
      		ltr.WaivedPDIonAmort,
      		CONVERT(varchar,FORMAT(ltr.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
        FROM $DB1.dbo.loanTransaction ltr
        INNER JOIN $DB1.dbo.loanStatus lst ON ltr.LoanStatusID = lst.StatusID
        WHERE ltr.AccountNumber = ?
        UNION ALL
        SELECT CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime,
        		ltr.TransactionType,
        		lst.StatusDescription,
        		ltr.OriginalPrincipal,
        		ltr.PrincipalBalance,
        		ltr.PrincipalPayment,
        		ltr.OriginalInterest,
        		ltr.InterestBalance,
        		ltr.InterestPayment,
        		ltr.PDPPayment,
        		ltr.PDPonAmortPayment,
        		ltr.WaivedPDP,
        		ltr.WaivedPDPonAmort,
        		ltr.PDIPayment,
        		ltr.PDIOnAmortPayment,
        		ltr.WaivedPDI,
        		ltr.WaivedPDIonAmort,
        		CONVERT(varchar,FORMAT(ltr.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
          FROM $DB2.dbo.loanTransaction ltr
          INNER JOIN $DB2.dbo.loanStatus lst ON ltr.LoanStatusID = lst.StatusID
          WHERE ltr.AccountNumber = ?
          UNION ALL
          SELECT CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime,
              ltr.TransactionType,
              lst.StatusDescription,
              ltr.OriginalPrincipal,
              ltr.PrincipalBalance,
              ltr.PrincipalPayment,
              ltr.OriginalInterest,
              ltr.InterestBalance,
              ltr.InterestPayment,
              ltr.PDPPayment,
              ltr.PDPonAmortPayment,
              ltr.WaivedPDP,
              ltr.WaivedPDPonAmort,
              ltr.PDIPayment,
              ltr.PDIOnAmortPayment,
              ltr.WaivedPDI,
              ltr.WaivedPDIonAmort,
              CONVERT(varchar,FORMAT(ltr.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
            FROM $DB3.dbo.loanTransaction ltr
            INNER JOIN $DB3.dbo.loanStatus lst ON ltr.LoanStatusID = lst.StatusID
            WHERE ltr.AccountNumber = ?
            UNION ALL
            SELECT CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime,
                ltr.TransactionType,
                lst.StatusDescription,
                ltr.OriginalPrincipal,
                ltr.PrincipalBalance,
                ltr.PrincipalPayment,
                ltr.OriginalInterest,
                ltr.InterestBalance,
                ltr.InterestPayment,
                ltr.PDPPayment,
                ltr.PDPonAmortPayment,
                ltr.WaivedPDP,
                ltr.WaivedPDPonAmort,
                ltr.PDIPayment,
                ltr.PDIOnAmortPayment,
                ltr.WaivedPDI,
                ltr.WaivedPDIonAmort,
                CONVERT(varchar,FORMAT(ltr.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
              FROM $DB4.dbo.loanTransaction ltr
              INNER JOIN $DB4.dbo.loanStatus lst ON ltr.LoanStatusID = lst.StatusID
              WHERE ltr.AccountNumber = ?
          ORDER BY TransactionDateTime";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
