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
header('Content-Disposition: attachment; filename=LoanAmort_'.$Branch.'_'.$LoanNo.'_'.$today.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN AMORTIZATION HISTORY','','AccountNumber:',"'".$accountnumber,'AccountName:',$accountname,'LoanNo:',$LoanNo));
fputcsv($output, array('ScheduleDateTime','PrincipalOriginal','PrincipalBalance','PrincipalPaid','InterestOriginal','InterestBalance','InterestPaid','PenaltyOriginal','PenaltyBalance','PenaltyPaid','PenaltyWaived','PenaltyDaysEarned','SCOriginal','SCBalance','SCPaid','SCWaived','UpdatedDateTime'));
$sql = "SELECT CONVERT(varchar,FORMAT(lam.ScheduleDateTime,'yyyy-MM-dd')) as ScheduleDateTime,
            		lam.PrincipalOriginal,
            		lam.PrincipalBalance,
            		lam.PrincipalPaid,
            		lam.InterestOriginal,
            		lam.InterestBalance,
            		lam.InterestPaid,
            		lam.PenaltyOriginal,
            		lam.PenaltyBalance,
            		lam.PenaltyPaid,
            		lam.PenaltyWaived,
            		lam.PenaltyDaysEarned,
            		lam.SCOriginal,
            		lam.SCBalance,
            		lam.SCPaid,
            		lam.SCWaived,
                CONVERT(varchar,FORMAT(lam.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
            FROM $DB1.dbo.loanAmortization lam
            WHERE AccountNumber = ?
        UNION ALL
        SELECT CONVERT(varchar,FORMAT(lam.ScheduleDateTime,'yyyy-MM-dd')) as ScheduleDateTime,
            		lam.PrincipalOriginal,
            		lam.PrincipalBalance,
            		lam.PrincipalPaid,
            		lam.InterestOriginal,
            		lam.InterestBalance,
            		lam.InterestPaid,
            		lam.PenaltyOriginal,
            		lam.PenaltyBalance,
            		lam.PenaltyPaid,
            		lam.PenaltyWaived,
            		lam.PenaltyDaysEarned,
            		lam.SCOriginal,
            		lam.SCBalance,
            		lam.SCPaid,
            		lam.SCWaived,
                CONVERT(varchar,FORMAT(lam.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
            FROM $DB2.dbo.loanAmortization lam
            WHERE AccountNumber = ?
            UNION ALL
        SELECT CONVERT(varchar,FORMAT(lam.ScheduleDateTime,'yyyy-MM-dd')) as ScheduleDateTime,
            		lam.PrincipalOriginal,
            		lam.PrincipalBalance,
            		lam.PrincipalPaid,
            		lam.InterestOriginal,
            		lam.InterestBalance,
            		lam.InterestPaid,
            		lam.PenaltyOriginal,
            		lam.PenaltyBalance,
            		lam.PenaltyPaid,
            		lam.PenaltyWaived,
            		lam.PenaltyDaysEarned,
            		lam.SCOriginal,
            		lam.SCBalance,
            		lam.SCPaid,
            		lam.SCWaived,
                CONVERT(varchar,FORMAT(lam.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
            FROM $DB3.dbo.loanAmortization lam
            WHERE AccountNumber = ?
          UNION ALL
        SELECT CONVERT(varchar,FORMAT(lam.ScheduleDateTime,'yyyy-MM-dd')) as ScheduleDateTime,
            		lam.PrincipalOriginal,
            		lam.PrincipalBalance,
            		lam.PrincipalPaid,
            		lam.InterestOriginal,
            		lam.InterestBalance,
            		lam.InterestPaid,
            		lam.PenaltyOriginal,
            		lam.PenaltyBalance,
            		lam.PenaltyPaid,
            		lam.PenaltyWaived,
            		lam.PenaltyDaysEarned,
            		lam.SCOriginal,
            		lam.SCBalance,
            		lam.SCPaid,
            		lam.SCWaived,
                CONVERT(varchar,FORMAT(lam.UpdatedDateTime,'yyyy-MM-dd')) as UpdatedDateTime
            FROM $DB4.dbo.loanAmortization lam
            WHERE AccountNumber = ?
            ORDER BY ScheduleDateTime";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
  fclose($output);


  ?>
