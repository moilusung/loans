<?php
require "connection.php";

    $cutoff = $_POST['datecollection'];
    $param = array($cutoff,$cutoff,$cutoff,$cutoff,$cutoff,$cutoff,$cutoff,$cutoff);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=LoanCollection-'.$Branch.'-'.$cutoff.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN COLLECTION','','CUTOFF DATE:'.$cutoff));
fputcsv($output, array('BranchNo','ClientID','AccountNumber','AccountName','LoanNo','PrincipalBalance','MissedPaymantDays','Amortization','PrincipalDue','InterestDue','CompleteAddress','ResidenceTelNo','MobileNo','EmailAddress'));
$sql = "SELECT llg.BranchNo,
               llg.clientID,
               CAST(LEFT(llg.AccountNumber, LEN(llg.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(llg.AccountNumber, LEN(llg.AccountNumber) - 9)AS Varchar) AS AccountNumber,
               llg.AccountName,
               llg.LoanNo,
               llg.PrincipalBalance,
               due.MissedPaymentDays,
               due.Amortizations,
               due.PrincipalDue,
               due.InterestDue,
    		       cad.CompleteAddress,
            CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
            ELSE ccl.ResidenceTelNo END ResidenceTelNo,
            CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
            ELSE ccl.MobileNo END MobileNo,
            CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
            ELSE ccl.EmailAddress END EmailAddress
        FROM $DB1.dbo.loanLedger llg
        INNER JOIN (
        	SELECT lam.AccountNumber,
        	DATEDIFF(DAY,MIN(lam.ScheduleDateTime),?) AS MissedPaymentDays,
        	COUNT(*) AS Amortizations,
        	SUM(lam.PrincipalBalance) AS PrincipalDue,
        	SUM(lam.InterestBalance) AS InterestDue
        	FROM $DB1.dbo.loanLedger llg
        	INNER JOIN $DB1.dbo.loanAmortization lam ON (lam.AccountNumber = llg.AccountNumber)
        	WHERE (llg.AccountStatus = 'Released')
        	AND (llg.PrincipalBalance > 0)
        	AND (llg.LoanStatusID <> 5)
        	AND (DATEDIFF(DAY,lam.ScheduleDateTime,?) >= 0)
        	AND (lam.TotalAmortBalance > 0)
        	GROUP BY lam.AccountNumber
        	  )due ON (due.AccountNumber = llg.AccountNumber)
		LEFT JOIN $DB1.dbo.cifClient ccl ON llg.ClientID = ccl.ClientID
		LEFT JOIN $DB1.dbo.cifAddress cad ON cad.ClientID = llg.ClientID AND cad.AddressTypeID = 1
        INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = llg.LoanStatusID
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc WHERE cfc.ContactTypeID = 1) cfc1 ON cfc1.ClientID = llg.ClientID  AND cfc1.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc WHERE cfc.ContactTypeID = 3 ) cfc2 ON cfc2.ClientID = llg.ClientID AND cfc2.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc WHERE cfc.ContactTypeID = 7) cfc3 ON cfc3.ClientID = llg.ClientID AND cfc3.RowID = 1
        UNION ALL
        SELECT llg.BranchNo,
               llg.clientID,
               CAST(LEFT(llg.AccountNumber, LEN(llg.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(llg.AccountNumber, LEN(llg.AccountNumber) - 9)AS Varchar) AS AccountNumber,
               llg.AccountName,
               llg.LoanNo,
               llg.PrincipalBalance,
               due.MissedPaymentDays,
               due.Amortizations,
               due.PrincipalDue,
               due.InterestDue,
    		       cad.CompleteAddress,
              CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
              ELSE ccl.ResidenceTelNo END ResidenceTelNo,
              CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
              ELSE ccl.MobileNo END MobileNo,
              CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
              ELSE ccl.EmailAddress END EmailAddress
          FROM $DB2.dbo.loanLedger llg
          INNER JOIN (
          	SELECT lam.AccountNumber,
          	DATEDIFF(DAY,MIN(lam.ScheduleDateTime),?) AS MissedPaymentDays,
          	COUNT(*) AS Amortizations,
          	SUM(lam.PrincipalBalance) AS PrincipalDue,
          	SUM(lam.InterestBalance) AS InterestDue
          	FROM $DB2.dbo.loanLedger llg
          	INNER JOIN $DB2.dbo.loanAmortization lam ON (lam.AccountNumber = llg.AccountNumber)
          	WHERE (llg.AccountStatus = 'Released')
          	AND (llg.PrincipalBalance > 0)
          	AND (llg.LoanStatusID <> 5)
          	AND (DATEDIFF(DAY,lam.ScheduleDateTime,?) >= 0)
          	AND (lam.TotalAmortBalance > 0)
          	GROUP BY lam.AccountNumber
          	  )due ON (due.AccountNumber = llg.AccountNumber)
  		LEFT JOIN $DB2.dbo.cifClient ccl ON llg.ClientID = ccl.ClientID
  		LEFT JOIN $DB2.dbo.cifAddress cad ON cad.ClientID = llg.ClientID AND cad.AddressTypeID = 1
          INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = llg.LoanStatusID
          LEFT JOIN (
            SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
            ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
            FROM $DB2.dbo.cifContact cfc WHERE cfc.ContactTypeID = 1) cfc1 ON cfc1.ClientID = llg.ClientID  AND cfc1.RowID = 1
          LEFT JOIN (
            SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
            ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
            FROM $DB2.dbo.cifContact cfc WHERE cfc.ContactTypeID = 3 ) cfc2 ON cfc2.ClientID = llg.ClientID AND cfc2.RowID = 1
          LEFT JOIN (
            SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
            ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
            FROM $DB2.dbo.cifContact cfc WHERE cfc.ContactTypeID = 7) cfc3 ON cfc3.ClientID = llg.ClientID AND cfc3.RowID = 1
        UNION ALL
        SELECT llg.BranchNo,
               llg.clientID,
               CAST(LEFT(llg.AccountNumber, LEN(llg.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(llg.AccountNumber, LEN(llg.AccountNumber) - 9)AS Varchar) AS AccountNumber,
               llg.AccountName,
               llg.LoanNo,
               llg.PrincipalBalance,
               due.MissedPaymentDays,
               due.Amortizations,
               due.PrincipalDue,
               due.InterestDue,
    		       cad.CompleteAddress,
              CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
              ELSE ccl.ResidenceTelNo END ResidenceTelNo,
              CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
              ELSE ccl.MobileNo END MobileNo,
              CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
              ELSE ccl.EmailAddress END EmailAddress
              FROM $DB3.dbo.loanLedger llg
              INNER JOIN (
              	SELECT lam.AccountNumber,
              	DATEDIFF(DAY,MIN(lam.ScheduleDateTime),?) AS MissedPaymentDays,
              	COUNT(*) AS Amortizations,
              	SUM(lam.PrincipalBalance) AS PrincipalDue,
              	SUM(lam.InterestBalance) AS InterestDue
              	FROM $DB3.dbo.loanLedger llg
              	INNER JOIN $DB3.dbo.loanAmortization lam ON (lam.AccountNumber = llg.AccountNumber)
              	WHERE (llg.AccountStatus = 'Released')
              	AND (llg.PrincipalBalance > 0)
              	AND (llg.LoanStatusID <> 5)
              	AND (DATEDIFF(DAY,lam.ScheduleDateTime,?) >= 0)
              	AND (lam.TotalAmortBalance > 0)
              	GROUP BY lam.AccountNumber
              	  )due ON (due.AccountNumber = llg.AccountNumber)
      		LEFT JOIN $DB3.dbo.cifClient ccl ON llg.ClientID = ccl.ClientID
      		LEFT JOIN $DB3.dbo.cifAddress cad ON cad.ClientID = llg.ClientID AND cad.AddressTypeID = 1
              INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = llg.LoanStatusID
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB3.dbo.cifContact cfc WHERE cfc.ContactTypeID = 1) cfc1 ON cfc1.ClientID = llg.ClientID  AND cfc1.RowID = 1
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB3.dbo.cifContact cfc WHERE cfc.ContactTypeID = 3 ) cfc2 ON cfc2.ClientID = llg.ClientID AND cfc2.RowID = 1
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB3.dbo.cifContact cfc WHERE cfc.ContactTypeID = 7) cfc3 ON cfc3.ClientID = llg.ClientID AND cfc3.RowID = 1
        UNION ALL
        SELECT llg.BranchNo,
               llg.clientID,
               CAST(LEFT(llg.AccountNumber, LEN(llg.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(llg.AccountNumber, LEN(llg.AccountNumber) - 9)AS Varchar) AS AccountNumber,
               llg.AccountName,
               llg.LoanNo,
               llg.PrincipalBalance,
               due.MissedPaymentDays,
               due.Amortizations,
               due.PrincipalDue,
               due.InterestDue,
    		       cad.CompleteAddress,
              CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
              ELSE ccl.ResidenceTelNo END ResidenceTelNo,
              CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
              ELSE ccl.MobileNo END MobileNo,
              CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
              ELSE ccl.EmailAddress END EmailAddress
              FROM $DB4.dbo.loanLedger llg
              INNER JOIN (
                SELECT lam.AccountNumber,
                DATEDIFF(DAY,MIN(lam.ScheduleDateTime),?) AS MissedPaymentDays,
                COUNT(*) AS Amortizations,
                SUM(lam.PrincipalBalance) AS PrincipalDue,
                SUM(lam.InterestBalance) AS InterestDue
                FROM $DB4.dbo.loanLedger llg
                INNER JOIN $DB4.dbo.loanAmortization lam ON (lam.AccountNumber = llg.AccountNumber)
                WHERE (llg.AccountStatus = 'Released')
                AND (llg.PrincipalBalance > 0)
                AND (llg.LoanStatusID <> 5)
                AND (DATEDIFF(DAY,lam.ScheduleDateTime,?) >= 0)
                AND (lam.TotalAmortBalance > 0)
                GROUP BY lam.AccountNumber
                  )due ON (due.AccountNumber = llg.AccountNumber)
          LEFT JOIN $DB4.dbo.cifClient ccl ON llg.ClientID = ccl.ClientID
          LEFT JOIN $DB4.dbo.cifAddress cad ON cad.ClientID = llg.ClientID AND cad.AddressTypeID = 1
              INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = llg.LoanStatusID
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB4.dbo.cifContact cfc WHERE cfc.ContactTypeID = 1) cfc1 ON cfc1.ClientID = llg.ClientID  AND cfc1.RowID = 1
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB4.dbo.cifContact cfc WHERE cfc.ContactTypeID = 3 ) cfc2 ON cfc2.ClientID = llg.ClientID AND cfc2.RowID = 1
              LEFT JOIN (
                SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
                ROW_NUMBER() OVER (PARTITION BY cfc.ClientID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
                FROM $DB4.dbo.cifContact cfc WHERE cfc.ContactTypeID = 7) cfc3 ON cfc3.ClientID = llg.ClientID AND cfc3.RowID = 1
        ORDER BY llg.BranchNo,due.MissedPaymentDays";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
