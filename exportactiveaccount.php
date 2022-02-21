<?php
require "connection.php";

    $cutoff = $_POST['dateactiveaccount'];
    $param = array($cutoff,$cutoff,$cutoff,$cutoff);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=ActiveAccount-'.$Branch.'-'.$cutoff.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN ACTIVE ACCOUNT','','CUTOFF DATE:'.$cutoff));
fputcsv($output, array('BranchNo','AccountNumber','LoanNo','ClientID','AccountName','GrantedDateTime','OriginalPrincipal','PrincipalBalance','MaturityDateTime','ScheduleDescription','Type','SecurityType','GracePeriodDaysPDAmort','LoanStatusID','StatusDescription'));
$sql = "SELECT
          	gbr.BranchNo,
          	CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
          	lle.LoanNo,
          	lle.ClientID,
          	lle.AccountName,
          	CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) as GrantedDateTime,
          	lle.OriginalPrincipal,
          	ltr.PrincipalBalance,
          	CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDateTime,
          	lsc.ScheduleDescription,
          	lst.Type,
          	lst.SecurityType,
          	lpa.GracePeriodDaysPDAmort,
          	ltr.LoanStatusID,
          	lsa.StatusDescription
          FROM $DB1.dbo.loanLedger lle
          INNER JOIN (
          		SELECT ltr.TransactionID,ltr.TransactionNumber,lle.ClientID,ltr.AccountNumber,ltr.TransactionDateTime,
          				ltr.LoanStatusID,ltr.TransactionType,ltr.PrincipalBalance,ltr.CICAmortDateTime,
          				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
          			FROM $DB1.dbo.loanLedger lle
          			INNER JOIN $DB1.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
          			WHERE (lle.AccountStatus = 'Released')
          				AND (DATEDIFF(DAY,ltr.TransactionDateTime, ?) >= 0)
          		) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
          INNER JOIN $DB1.dbo.genBranch gbr ON (gbr.BranchNo = lle.BranchNo)
          INNER JOIN $DB1.dbo.loanSchedule lsc ON (lsc.ScheduleID = lle.AmortizationScheduleID)
          INNER JOIN $DB1.dbo.loanSecurityType lst ON (lst.SecurityTypeID = lle.SecurityTypeID)
          INNER JOIN $DB1.dbo.loanParameter lpa ON (lpa.ParameterID = lle.ParameterID)
          INNER JOIN $DB1.dbo.loanStatus lsa ON (lsa.StatusID = ltr.LoanStatusID)
          WHERE (lle.AccountStatus = 'Released')
          	AND (ltr.PrincipalBalance > 0)
          	AND (ltr.LoanStatusID <> 5)
          UNION ALL
          SELECT
            	gbr.BranchNo,
            	CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
            	lle.LoanNo,
            	lle.ClientID,
            	lle.AccountName,
            	CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) as GrantedDateTime,
            	lle.OriginalPrincipal,
            	ltr.PrincipalBalance,
            	CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDateTime,
            	lsc.ScheduleDescription,
            	lst.Type,
            	lst.SecurityType,
            	lpa.GracePeriodDaysPDAmort,
            	ltr.LoanStatusID,
            	lsa.StatusDescription
            FROM $DB2.dbo.loanLedger lle
            INNER JOIN (
            		SELECT ltr.TransactionID,ltr.TransactionNumber,lle.ClientID,ltr.AccountNumber,ltr.TransactionDateTime,
            				ltr.LoanStatusID,ltr.TransactionType,ltr.PrincipalBalance,ltr.CICAmortDateTime,
            				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
            			FROM $DB2.dbo.loanLedger lle
            			INNER JOIN $DB2.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
            			WHERE (lle.AccountStatus = 'Released')
            				AND (DATEDIFF(DAY,ltr.TransactionDateTime, ?) >= 0)
            		) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
            INNER JOIN $DB2.dbo.genBranch gbr ON (gbr.BranchNo = lle.BranchNo)
            INNER JOIN $DB2.dbo.loanSchedule lsc ON (lsc.ScheduleID = lle.AmortizationScheduleID)
            INNER JOIN $DB2.dbo.loanSecurityType lst ON (lst.SecurityTypeID = lle.SecurityTypeID)
            INNER JOIN $DB2.dbo.loanParameter lpa ON (lpa.ParameterID = lle.ParameterID)
            INNER JOIN $DB2.dbo.loanStatus lsa ON (lsa.StatusID = ltr.LoanStatusID)
            WHERE (lle.AccountStatus = 'Released')
            	AND (ltr.PrincipalBalance > 0)
            	AND (ltr.LoanStatusID <> 5)
            UNION ALL
            SELECT
            	gbr.BranchNo,
            	CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
            	lle.LoanNo,
            	lle.ClientID,
            	lle.AccountName,
            	CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) as GrantedDateTime,
            	lle.OriginalPrincipal,
            	ltr.PrincipalBalance,
            	CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDateTime,
            	lsc.ScheduleDescription,
            	lst.Type,
            	lst.SecurityType,
            	lpa.GracePeriodDaysPDAmort,
            	ltr.LoanStatusID,
            	lsa.StatusDescription
            FROM $DB3.dbo.loanLedger lle
            INNER JOIN (
            		SELECT ltr.TransactionID,ltr.TransactionNumber,lle.ClientID,ltr.AccountNumber,ltr.TransactionDateTime,
            				ltr.LoanStatusID,ltr.TransactionType,ltr.PrincipalBalance,ltr.CICAmortDateTime,
            				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
            			FROM $DB3.dbo.loanLedger lle
            			INNER JOIN $DB3.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
            			WHERE (lle.AccountStatus = 'Released')
            				AND (DATEDIFF(DAY,ltr.TransactionDateTime, ?) >= 0)
            		) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
            INNER JOIN $DB3.dbo.genBranch gbr ON (gbr.BranchNo = lle.BranchNo)
            INNER JOIN $DB3.dbo.loanSchedule lsc ON (lsc.ScheduleID = lle.AmortizationScheduleID)
            INNER JOIN $DB3.dbo.loanSecurityType lst ON (lst.SecurityTypeID = lle.SecurityTypeID)
            INNER JOIN $DB3.dbo.loanParameter lpa ON (lpa.ParameterID = lle.ParameterID)
            INNER JOIN $DB3.dbo.loanStatus lsa ON (lsa.StatusID = ltr.LoanStatusID)
            WHERE (lle.AccountStatus = 'Released')
            	AND (ltr.PrincipalBalance > 0)
            	AND (ltr.LoanStatusID <> 5)
              UNION ALL
              SELECT
                gbr.BranchNo,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                lle.LoanNo,
                lle.ClientID,
                lle.AccountName,
                CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) as GrantedDateTime,
                lle.OriginalPrincipal,
                ltr.PrincipalBalance,
                CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDateTime,
                lsc.ScheduleDescription,
                lst.Type,
                lst.SecurityType,
                lpa.GracePeriodDaysPDAmort,
                ltr.LoanStatusID,
                lsa.StatusDescription
              FROM $DB4.dbo.loanLedger lle
              INNER JOIN (
                  SELECT ltr.TransactionID,ltr.TransactionNumber,lle.ClientID,ltr.AccountNumber,ltr.TransactionDateTime,
                      ltr.LoanStatusID,ltr.TransactionType,ltr.PrincipalBalance,ltr.CICAmortDateTime,
                      ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
                    FROM $DB4.dbo.loanLedger lle
                    INNER JOIN $DB4.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
                    WHERE (lle.AccountStatus = 'Released')
                      AND (DATEDIFF(DAY,ltr.TransactionDateTime, ?) >= 0)
                  ) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
              INNER JOIN $DB4.dbo.genBranch gbr ON (gbr.BranchNo = lle.BranchNo)
              INNER JOIN $DB4.dbo.loanSchedule lsc ON (lsc.ScheduleID = lle.AmortizationScheduleID)
              INNER JOIN $DB4.dbo.loanSecurityType lst ON (lst.SecurityTypeID = lle.SecurityTypeID)
              INNER JOIN $DB4.dbo.loanParameter lpa ON (lpa.ParameterID = lle.ParameterID)
              INNER JOIN $DB4.dbo.loanStatus lsa ON (lsa.StatusID = ltr.LoanStatusID)
              WHERE (lle.AccountStatus = 'Released')
                AND (ltr.PrincipalBalance > 0)
                AND (ltr.LoanStatusID <> 5)
            ORDER BY BranchNo,GrantedDateTime DESC;";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
