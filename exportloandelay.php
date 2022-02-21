<?php
require "connection.php";

    $cutoff = $_POST['dateloandelay'];
    $param = array($cutoff,$cutoff,$cutoff,$cutoff);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=LoanDelay_'.$Branch.'_'.$cutoff.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN DELAY','CUTOFF DATE:',$cutoff));
fputcsv($output, array('BranchNo','AccountNumber','LoanNo','AccountName','GLGroupDesc','LoanStatus','MinDelay','MaxDelay'));
$sql = "SELECT  lle.BranchNo,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.loanNo,
                lle.AccountName,
                lgl.GLGroupDesc,
                lst.StatusDescription,
                MIN(lam.DaysDelay) [MinDelay],
                MAX(lam.DaysDelay) MaxDelay
        FROM $DB1.dbo.loanLedger lle
		      INNER JOIN $DB1.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        INNER JOIN $DB1.dbo.LoanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN (SELECT lam.AccountNumber,
                    DATEDIFF(DAY,lam.ScheduleDateTime,ISNULL(lam.PaidDateTime,
            				CASE WHEN (lam.PrincipalBalance + lam.InterestBalance) = 0 THEN lam.UpdatedDateTime
            				ELSE ? END)) DaysDelay
        FROM $DB1.dbo.loanAmortization lam) lam ON lle.AccountNumber = lam.AccountNumber
        WHERE lle.PrincipalBalance > 1 AND lle.LoanStatusID <> 5
        GROUP by lle.BranchNo,lle.AccountNumber,lle.loanNo,lle.AccountName,lgl.GLGroupDesc,lst.StatusDescription
    UNION ALL
        SELECT lle.BranchNo,
                  CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.loanNo,
                lle.AccountName,
                lgl.GLGroupDesc,
                lst.StatusDescription,
                MIN(lam.DaysDelay) [MinDelay],
                MAX(lam.DaysDelay) MaxDelayFROM
        FROM $DB2.dbo.loanLedger lle
		      INNER JOIN $DB2.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        INNER JOIN $DB2.dbo.LoanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN (SELECT lam.AccountNumber,
                    DATEDIFF(DAY,lam.ScheduleDateTime,ISNULL(lam.PaidDateTime,
            				CASE WHEN (lam.PrincipalBalance + lam.InterestBalance) = 0 THEN lam.UpdatedDateTime
            				ELSE ? END)) DaysDelay
        FROM $DB2.dbo.loanAmortization lam) lam ON lle.AccountNumber = lam.AccountNumber
        WHERE lle.PrincipalBalance > 1 AND lle.LoanStatusID <> 5
        GROUP by lle.BranchNo,lle.AccountNumber,lle.loanNo,lle.AccountName,lgl.GLGroupDesc,lst.StatusDescription
    UNION ALL
        SELECT lle.BranchNo,
                  CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.loanNo,
                lle.AccountName,
                lgl.GLGroupDesc,
                lst.StatusDescription,
                MIN(lam.DaysDelay) [MinDelay],
                MAX(lam.DaysDelay) MaxDelay
        FROM $DB3.dbo.loanLedger lle
		      INNER JOIN $DB3.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        INNER JOIN $DB3.dbo.LoanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN (SELECT lam.AccountNumber,
                    DATEDIFF(DAY,lam.ScheduleDateTime,ISNULL(lam.PaidDateTime,
            				CASE WHEN (lam.PrincipalBalance + lam.InterestBalance) = 0 THEN lam.UpdatedDateTime
            				ELSE ? END)) DaysDelay
        FROM $DB3.dbo.loanAmortization lam) lam ON lle.AccountNumber = lam.AccountNumber
        WHERE lle.PrincipalBalance > 1 AND lle.LoanStatusID <> 5
        GROUP by lle.BranchNo,lle.AccountNumber,lle.loanNo,lle.AccountName,lgl.GLGroupDesc,lst.StatusDescription
    UNION ALL
        SELECT lle.BranchNo,
                  CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.loanNo,
                lle.AccountName,
                lgl.GLGroupDesc,
                lst.StatusDescription,
                MIN(lam.DaysDelay) [MinDelay],
                MAX(lam.DaysDelay) MaxDelay
        FROM $DB4.dbo.loanLedger lle
          INNER JOIN $DB4.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        INNER JOIN $DB4.dbo.LoanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN (SELECT lam.AccountNumber,
                    DATEDIFF(DAY,lam.ScheduleDateTime,ISNULL(lam.PaidDateTime,
                    CASE WHEN (lam.PrincipalBalance + lam.InterestBalance) = 0 THEN lam.UpdatedDateTime
                    ELSE ? END)) DaysDelay
        FROM $DB4.dbo.loanAmortization lam) lam ON lle.AccountNumber = lam.AccountNumber
        WHERE lle.PrincipalBalance > 1 AND lle.LoanStatusID <> 5
        GROUP by lle.BranchNo,lle.AccountNumber,lle.loanNo,lle.AccountName,lgl.GLGroupDesc,lst.StatusDescription
        ORDER BY BranchNo,AccountName";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
