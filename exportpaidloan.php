<?php
require "connection.php";

    $cutoff = $_POST['datepaidloans'];
    $param = array($cutoff,$cutoff,$cutoff,$cutoff);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=PaidLoans-'.$Branch.'-'.$cutoff.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('PAID LOAN','','CUTOFF DATE:'.$cutoff));
fputcsv($output, array('BranchNo','AccountNumber','ClientName','CompleteAddress','ResidenceTelNo','MobileNo','EmailAddress','LastTransactionDateTime','TotalPayment','StatusDescription'));
$sql = "SELECT  ccl.BranchNo,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                ccl.ClientName,
                cad.CompleteAddress,
                CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
                ELSE ccl.ResidenceTelNo END ResidenceTelNo,
                CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
                ELSE ccl.MobileNo END MobileNo,
                CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
                ELSE ccl.EmailAddress END,
                CONVERT(varchar,FORMAT(lle.TransactionDateTime,'yyyy-MM-dd')) as LastTransactionDateTime,
                lle.TotalPayment,
                lst.StatusDescription
        FROM $DB1.dbo.cifClient ccl
        INNER JOIN (
          SELECT lle.ClientID, lle.AccountNumber,ltr.TransactionDateTime,ltr.LoanStatusID,ltr.TotalPayment,
          ROW_NUMBER() OVER (PARTITION BY ltr.ClientID ORDER BY ltr.TransactionDateTime DESC) AS RowID
          FROM $DB1.dbo.loanLedger lle
          INNER JOIN
          (
            SELECT	ltr.TransactionID,ltr.TransactionNumber,
            lle.ClientID,ltr.AccountNumber,
            ltr.TransactionDateTime,ltr.LoanStatusID,
            ltr.PrincipalBalance,ltr.TotalPayment,
            ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
            FROM $DB1.dbo.loanLedger lle
            INNER JOIN $DB1.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
            WHERE (lle.AccountStatus = 'Released')
            AND (DATEDIFF(DAY,ltr.TransactionDateTime,?) >= 0)
          ) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
          WHERE (ltr.PrincipalBalance = 0)
        ) lle ON (ccl.ClientID = lle.ClientID) AND (lle.RowID = 1)
        LEFT JOIN $DB1.dbo.cifAddress cad ON cad.ClientID = ccl.ClientID AND cad.AddressTypeID = 1
        INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc) cfc1 ON cfc1.ClientID = ccl.ClientID AND cfc1.ContactTypeID = 1 AND cfc1.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc) cfc2 ON cfc2.ClientID = ccl.ClientID AND cfc2.ContactTypeID = 3 AND cfc2.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB1.dbo.cifContact cfc) cfc3 ON cfc3.ClientID = ccl.ClientID AND cfc3.ContactTypeID = 7 AND cfc3.RowID = 1
        UNION ALL
        SELECT  ccl.BranchNo,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                ccl.ClientName,
                cad.CompleteAddress,
                CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
                ELSE ccl.ResidenceTelNo END ResidenceTelNo,
                CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
                ELSE ccl.MobileNo END MobileNo,
                CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
                ELSE ccl.EmailAddress END,
                CONVERT(varchar,FORMAT(lle.TransactionDateTime,'yyyy-MM-dd')) as LastTransactionDateTime,
                lle.TotalPayment,
                lst.StatusDescription
        FROM $DB2.dbo.cifClient ccl
        INNER JOIN (
          SELECT lle.ClientID, lle.AccountNumber,ltr.TransactionDateTime,ltr.LoanStatusID,ltr.TotalPayment,
          ROW_NUMBER() OVER (PARTITION BY ltr.ClientID ORDER BY ltr.TransactionDateTime DESC) AS RowID
          FROM $DB2.dbo.loanLedger lle
          INNER JOIN
          (
            SELECT	ltr.TransactionID,ltr.TransactionNumber,
            lle.ClientID,ltr.AccountNumber,
            ltr.TransactionDateTime,ltr.LoanStatusID,
            ltr.PrincipalBalance,ltr.TotalPayment,
            ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
            FROM $DB2.dbo.loanLedger lle
            INNER JOIN $DB2.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
            WHERE (lle.AccountStatus = 'Released')
            AND (DATEDIFF(DAY,ltr.TransactionDateTime,?) >= 0)
          ) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
          WHERE (ltr.PrincipalBalance = 0)
        ) lle ON (ccl.ClientID = lle.ClientID) AND (lle.RowID = 1)
        LEFT JOIN $DB2.dbo.cifAddress cad ON cad.ClientID = ccl.ClientID AND cad.AddressTypeID = 1
        INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB2.dbo.cifContact cfc) cfc1 ON cfc1.ClientID = ccl.ClientID AND cfc1.ContactTypeID = 1 AND cfc1.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB2.dbo.cifContact cfc) cfc2 ON cfc2.ClientID = ccl.ClientID AND cfc2.ContactTypeID = 3 AND cfc2.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB2.dbo.cifContact cfc) cfc3 ON cfc3.ClientID = ccl.ClientID AND cfc3.ContactTypeID = 7 AND cfc3.RowID = 1
        UNION ALL
        SELECT  ccl.BranchNo,
                CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
                ccl.ClientName,
                cad.CompleteAddress,
                CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
                ELSE ccl.ResidenceTelNo END ResidenceTelNo,
                CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
                ELSE ccl.MobileNo END MobileNo,
                CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
                ELSE ccl.EmailAddress END,
                CONVERT(varchar,FORMAT(lle.TransactionDateTime,'yyyy-MM-dd')) as LastTransactionDateTime,
                lle.TotalPayment,
                lst.StatusDescription
        FROM $DB3.dbo.cifClient ccl
        INNER JOIN (
          SELECT lle.ClientID, lle.AccountNumber,ltr.TransactionDateTime,ltr.LoanStatusID,ltr.TotalPayment,
          ROW_NUMBER() OVER (PARTITION BY ltr.ClientID ORDER BY ltr.TransactionDateTime DESC) AS RowID
          FROM $DB3.dbo.loanLedger lle
          INNER JOIN
          (
            SELECT	ltr.TransactionID,ltr.TransactionNumber,
            lle.ClientID,ltr.AccountNumber,
            ltr.TransactionDateTime,ltr.LoanStatusID,
            ltr.PrincipalBalance,ltr.TotalPayment,
            ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
            FROM $DB3.dbo.loanLedger lle
            INNER JOIN $DB3.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
            WHERE (lle.AccountStatus = 'Released')
            AND (DATEDIFF(DAY,ltr.TransactionDateTime,?) >= 0)
          ) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
          WHERE (ltr.PrincipalBalance = 0)
        ) lle ON (ccl.ClientID = lle.ClientID) AND (lle.RowID = 1)
        LEFT JOIN $DB3.dbo.cifAddress cad ON cad.ClientID = ccl.ClientID AND cad.AddressTypeID = 1
        INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB3.dbo.cifContact cfc) cfc1 ON cfc1.ClientID = ccl.ClientID AND cfc1.ContactTypeID = 1 AND cfc1.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB3.dbo.cifContact cfc) cfc2 ON cfc2.ClientID = ccl.ClientID AND cfc2.ContactTypeID = 3 AND cfc2.RowID = 1
        LEFT JOIN (
          SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
          ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
          FROM $DB3.dbo.cifContact cfc) cfc3 ON cfc3.ClientID = ccl.ClientID AND cfc3.ContactTypeID = 7 AND cfc3.RowID = 1
      UNION ALL
      SELECT  ccl.BranchNo,
              CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
              ccl.ClientName,
              cad.CompleteAddress,
              CASE WHEN cfc1.Value IS NOT NULL THEN cfc1.Value
              ELSE ccl.ResidenceTelNo END ResidenceTelNo,
              CASE WHEN cfc2.Value IS NOT NULL THEN cfc2.Value
              ELSE ccl.MobileNo END MobileNo,
              CASE WHEN cfc3.Value IS NOT NULL THEN cfc3.Value
              ELSE ccl.EmailAddress END,
              CONVERT(varchar,FORMAT(lle.TransactionDateTime,'yyyy-MM-dd')) as LastTransactionDateTime,
              lle.TotalPayment,
              lst.StatusDescription
      FROM $DB4.dbo.cifClient ccl
      INNER JOIN (
        SELECT lle.ClientID, lle.AccountNumber,ltr.TransactionDateTime,ltr.LoanStatusID,ltr.TotalPayment,
        ROW_NUMBER() OVER (PARTITION BY ltr.ClientID ORDER BY ltr.TransactionDateTime DESC) AS RowID
        FROM $DB4.dbo.loanLedger lle
        INNER JOIN
        (
          SELECT	ltr.TransactionID,ltr.TransactionNumber,
          lle.ClientID,ltr.AccountNumber,
          ltr.TransactionDateTime,ltr.LoanStatusID,
          ltr.PrincipalBalance,ltr.TotalPayment,
          ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC) AS RowID
          FROM $DB4.dbo.loanLedger lle
          INNER JOIN $DB4.dbo.loanTransaction ltr ON (ltr.AccountNumber = lle.AccountNumber)
          WHERE (lle.AccountStatus = 'Released')
          AND (DATEDIFF(DAY,ltr.TransactionDateTime,?) >= 0)
        ) ltr ON (ltr.AccountNumber = lle.AccountNumber) AND (ltr.RowID = 1)
        WHERE (ltr.PrincipalBalance = 0)
      ) lle ON (ccl.ClientID = lle.ClientID) AND (lle.RowID = 1)
      LEFT JOIN $DB4.dbo.cifAddress cad ON cad.ClientID = ccl.ClientID AND cad.AddressTypeID = 1
      INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
      LEFT JOIN (
        SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
        ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
        FROM $DB4.dbo.cifContact cfc) cfc1 ON cfc1.ClientID = ccl.ClientID AND cfc1.ContactTypeID = 1 AND cfc1.RowID = 1
      LEFT JOIN (
        SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
        ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
        FROM $DB4.dbo.cifContact cfc) cfc2 ON cfc2.ClientID = ccl.ClientID AND cfc2.ContactTypeID = 3 AND cfc2.RowID = 1
      LEFT JOIN (
        SELECT cfc.ClientID,cfc.ContactTypeID,cfc.value,cfc.UpdatedDateTime,
        ROW_NUMBER() OVER (PARTITION BY cfc.ContactTypeID ORDER BY cfc.UpdatedDateTime DESC) AS RowID
        FROM $DB4.dbo.cifContact cfc) cfc3 ON cfc3.ClientID = ccl.ClientID AND cfc3.ContactTypeID = 7 AND cfc3.RowID = 1
      ORDER BY BranchNo,LastTransactionDateTime DESC";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
