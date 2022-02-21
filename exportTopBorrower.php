<?php
require "connection.php";

    $cutoff = $_POST['TopBorrowerDate'];

    $Top = $_POST['Top'];
    $sort = $_POST['sort'];

    $TopNumber = $_POST['TopNumber'];

    if ($sort == 'orig') {
      $sort1 = 'Loan Amount';
    }else{
      $sort1 = 'Principal Balance';
      }

// echo $TopNumber.$cutoff.$cutoff


header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=TOPBorrower-'.$Top.'-'.$Branch.'-'.$cutoff.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('TOP '.$TopNumber.' '.$Top.' Borrower','','CUTOFF DATE:',$cutoff,'SORT BY:',$sort1));

if ($Top == 'Bankwide') {
fputcsv($output, array('TOP#','BranchNo','GeneratedClientID','ClientID','AccountNumber','LoanNo','AccountName','GrantedDateTime','MaturityDateTime','AmountApproved','PrincipalBalance','StatusDescription','SecurityTypeDescription','GLGroupDesc'));

      $param = array($cutoff,$cutoff);
      $sql = "
      SELECT	tb.RowNum,
      tb.BranchNo,
      tb.GeneratedClientID,
      tb.ClientID,
      tb.AccountNumber,
      tb.LoanNo,
      tb.AccountName,
      tb.GrantedDateTime,
      tb.MaturityDateTime,
      tb.AmountApproved,
      tb.PrincipalBalance,
      tb.StatusDescription,
      tb.SecurityTypeDescription,
      tb.GLGroupDesc
      FROM (
      SELECT	b.RowNum,
        a.BranchNo,
        a.GeneratedClientID,
        a.ClientID,
        CAST(LEFT(a.AccountNumber, LEN(a.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(a.AccountNumber, LEN(a.AccountNumber) - 9)AS Varchar) AS AccountNumber,
        a.LoanNo,
        a.AccountName,
        CONVERT(varchar,FORMAT(a.GrantedDateTime,'yyyy-MM-dd')) GrantedDateTime,
        CONVERT(varchar,FORMAT(a.MaturityDateTime,'yyyy-MM-dd')) MaturityDateTime,
        a.AmountApproved,
        a.PrincipalBalance,
        a.StatusDescription,
        a.SecurityTypeDescription,
        a.GLGroupDesc,
        b.orig,
        b.prin
      FROM (
        SELECT  lle.BranchNo,
            CASE WHEN lle.AccountName like '%SYSTEMS PLUS%'
              THEN '10000776' ELSE lle.ClientID END GeneratedClientID,
            lle.ClientID,
            lle.AccountNumber,
            lle.LoanNo,
            lle.AccountName,
            lle.GrantedDateTime,
            lle.MaturityDateTime,
            lle.AmountApproved,
            lle.PrincipalBalance,
            lst.StatusDescription,
            lse.SecurityTypeDescription,
            lgl.GLGroupDesc
        FROM $DB1.dbo.loanLedger lle
        INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN $DB1.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
        INNER JOIN $DB1.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        WHERE lle.PrincipalBalance > 0
        AND lle.AccountStatus = 'Released'
        AND lle.LoanStatusID <> 5
        UNION ALL
        SELECT  lle.BranchNo,
            CASE WHEN  lle.AccountName like '%SYSTEMS PLUS%'
              THEN '10000776' ELSE lle.ClientID END GeneratedClientID,
            lle.ClientID,
            lle.AccountNumber,
            lle.LoanNo,
            lle.AccountName,
            lle.GrantedDateTime,
            lle.MaturityDateTime,
            lle.AmountApproved,
            lle.PrincipalBalance,
            lst.StatusDescription,
            lse.SecurityTypeDescription,
            lgl.GLGroupDesc
        FROM $DB2.dbo.loanLedger lle
        INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN $DB2.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
        INNER JOIN $DB2.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        WHERE lle.PrincipalBalance > 0
        AND lle.AccountStatus = 'Released'
        AND lle.LoanStatusID <> 5
        UNION ALL
        SELECT  lle.BranchNo,
            CASE WHEN lle.AccountName like '%SYSTEMS PLUS%'
              THEN '10000776' ELSE lle.ClientID END GeneratedClientID,
            lle.ClientID,
            lle.AccountNumber,
            lle.LoanNo,
            lle.AccountName,
            lle.GrantedDateTime,
            lle.MaturityDateTime,
            lle.AmountApproved,
            lle.PrincipalBalance,
            lst.StatusDescription,
            lse.SecurityTypeDescription,
            lgl.GLGroupDesc
        FROM $DB3.dbo.loanLedger lle
        INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN $DB3.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
        INNER JOIN $DB3.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        WHERE lle.PrincipalBalance > 0
        AND lle.AccountStatus = 'Released'
        AND lle.LoanStatusID <> 5
        UNION ALL
        SELECT  lle.BranchNo,
            CASE WHEN lle.AccountName like '%SYSTEMS PLUS%'
              THEN '10000776' ELSE lle.ClientID END GeneratedClientID,
            lle.ClientID,
            lle.AccountNumber,
            lle.LoanNo,
            lle.AccountName,
            lle.GrantedDateTime,
            lle.MaturityDateTime,
            lle.AmountApproved,
            lle.PrincipalBalance,
            lst.StatusDescription,
            lse.SecurityTypeDescription,
            lgl.GLGroupDesc
        FROM $DB4.dbo.loanLedger lle
        INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
        INNER JOIN $DB4.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
        INNER JOIN $DB4.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
        WHERE lle.PrincipalBalance > 0
        AND lle.AccountStatus = 'Released'
        AND lle.LoanStatusID <> 5
      ) as a
      INNER JOIN(
      SELECT TOP ".$TopNumber." c.ClientID,c.orig,c.prin,ROW_NUMBER() OVER(ORDER BY ".$sort." DESC) AS RowNum
      FROM(
        SELECT e.ClientID,SUM(e.AmountApproved) orig,SUM(e.PrincipalBalance) prin
        FROM(
          SELECT CASE WHEN d.AccountName like '%SYSTEMS PLUS%'
                THEN '10000776' ELSE d.ClientID END ClientID,
                d.AmountApproved,d.PrincipalBalance FROM (
              SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime,lle.PrincipalBalance
              FROM $DB1.dbo.loanLedger lle
              WHERE lle.PrincipalBalance > 0
              AND lle.AccountStatus = 'Released'
              AND lle.LoanStatusID <> 5
              UNION ALL
              SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime,lle.PrincipalBalance
              FROM $DB2.dbo.loanLedger lle
              WHERE lle.PrincipalBalance > 0
              AND lle.AccountStatus = 'Released'
              AND lle.LoanStatusID <> 5
              UNION ALL
              SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime,lle.PrincipalBalance
              FROM $DB3.dbo.loanLedger lle
              WHERE lle.PrincipalBalance > 0
              AND lle.AccountStatus = 'Released'
              AND lle.LoanStatusID <> 5
              UNION ALL
              SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime,lle.PrincipalBalance
              FROM $DB4.dbo.loanLedger lle
              WHERE lle.PrincipalBalance > 0
              AND lle.AccountStatus = 'Released'
              AND lle.LoanStatusID <> 5
              ) as d
              WHERE DATEDIFF(DAY,d.GrantedDateTime, ? ) >= 0
          )as e
          GROUP BY e.ClientID
        )as c
        ORDER BY c.orig DESC
      ) b ON a.GeneratedClientID = b.ClientID
      WHERE DATEDIFF(DAY,a.GrantedDateTime, ? ) >= 0
      ) as tb
      ORDER BY tb.RowNum,".$sort." DESC,tb.AmountApproved DESC";

}else {
  fputcsv($output, array('TOP#','BranchNo','ClientID','AccountNumber','LoanNo','AccountName','GrantedDateTime','MaturityDateTime','AmountApproved','PrincipalBalance','StatusDescription','SecurityTypeDescription','GLGroupDesc'));

      $param = array($cutoff,$cutoff,$cutoff,$cutoff);
      $sql = "
      SELECT  a.RowNum,
  		lle.BranchNo,
  		lle.ClientID,
  		CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
  		lle.LoanNo,
  		lle.AccountName,
  		CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) GrantedDateTime,
  		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) MaturityDateTime,
  		lle.AmountApproved,
  		lle.PrincipalBalance,
  		lst.StatusDescription,
  		lse.SecurityTypeDescription,
  		lgl.GLGroupDesc
  FROM $DB1.dbo.loanLedger lle
  INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
  INNER JOIN $DB1.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
  INNER JOIN $DB1.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
  INNER JOIN (
  		SELECT TOP ".$TopNumber." a.ClientID,a.orig,a.prin,ROW_NUMBER() OVER(ORDER BY ".$sort." DESC) AS RowNum
  			FROM(
  				SELECT lle.ClientID,SUM(lle.AmountApproved) orig,SUM(lle.PrincipalBalance) prin
  				FROM $DB1.dbo.loanLedger lle
  				WHERE lle.PrincipalBalance > 0
  				AND lle.AccountStatus = 'Released'
  				AND lle.LoanStatusID <> 5
  				AND DATEDIFF(DAY,lle.GrantedDateTime, ? ) >= 0
  				GROUP BY lle.ClientID
  				) as a
  				ORDER BY ".$sort." DESC
  		) a ON lle.ClientID = a.ClientID
  WHERE lle.PrincipalBalance > 0
  AND lle.AccountStatus = 'Released'
  AND lle.LoanStatusID <> 5
  UNION ALL
  SELECT  a.RowNum,
  		lle.BranchNo,
  		lle.ClientID,
  		CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
  		lle.LoanNo,
  		lle.AccountName,
      CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) GrantedDateTime,
  		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) MaturityDateTime,
  		lle.AmountApproved,
  		lle.PrincipalBalance,
  		lst.StatusDescription,
  		lse.SecurityTypeDescription,
  		lgl.GLGroupDesc
  FROM $DB2.dbo.loanLedger lle
  INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
  INNER JOIN $DB2.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
  INNER JOIN $DB2.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
  INNER JOIN (
  		SELECT TOP ".$TopNumber." a.ClientID,a.orig,a.prin,ROW_NUMBER() OVER(ORDER BY ".$sort." DESC) AS RowNum
  			FROM(
  				SELECT lle.ClientID,SUM(lle.AmountApproved) orig,SUM(lle.PrincipalBalance) prin
  				FROM $DB2.dbo.loanLedger lle
  				WHERE lle.PrincipalBalance > 0
  				AND lle.AccountStatus = 'Released'
  				AND lle.LoanStatusID <> 5
  				AND DATEDIFF(DAY,lle.GrantedDateTime, ? ) >= 0
  				GROUP BY lle.ClientID
  				) as a
  				ORDER BY ".$sort." DESC
  		) a ON lle.ClientID = a.ClientID
  WHERE lle.PrincipalBalance > 0
  AND lle.AccountStatus = 'Released'
  AND lle.LoanStatusID <> 5
  UNION ALL
  SELECT  a.RowNum,
  		lle.BranchNo,
  		lle.ClientID,
  		CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
  		lle.LoanNo,
  		lle.AccountName,
      CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) GrantedDateTime,
  		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) MaturityDateTime,
  		lle.AmountApproved,
  		lle.PrincipalBalance,
  		lst.StatusDescription,
  		lse.SecurityTypeDescription,
  		lgl.GLGroupDesc
  FROM $DB3.dbo.loanLedger lle
  INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
  INNER JOIN $DB3.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
  INNER JOIN $DB3.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
  INNER JOIN (
  		SELECT TOP ".$TopNumber." a.ClientID,a.orig,a.prin,ROW_NUMBER() OVER(ORDER BY ".$sort." DESC) AS RowNum
  			FROM(
  				SELECT lle.ClientID,SUM(lle.AmountApproved) orig,SUM(lle.PrincipalBalance) prin
  				FROM $DB3.dbo.loanLedger lle
  				WHERE lle.PrincipalBalance > 0
  				AND lle.AccountStatus = 'Released'
  				AND lle.LoanStatusID <> 5
  				AND DATEDIFF(DAY,lle.GrantedDateTime, ? ) >= 0
  				GROUP BY lle.ClientID
  				) as a
  				ORDER BY ".$sort." DESC
  		) a ON lle.ClientID = a.ClientID
  WHERE lle.PrincipalBalance > 0
  AND lle.AccountStatus = 'Released'
  AND lle.LoanStatusID <> 5
  UNION ALL
  SELECT  a.RowNum,
  		lle.BranchNo,
  		lle.ClientID,
  		CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber,
  		lle.LoanNo,
  		lle.AccountName,
      CONVERT(varchar,FORMAT(lle.GrantedDateTime,'yyyy-MM-dd')) GrantedDateTime,
  		CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) MaturityDateTime,
  		lle.AmountApproved,
  		lle.PrincipalBalance,
  		lst.StatusDescription,
  		lse.SecurityTypeDescription,
  		lgl.GLGroupDesc
  FROM $DB4.dbo.loanLedger lle
  INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
  INNER JOIN $DB4.dbo.loanSecurityType lse ON lse.SecurityTypeID = lle.SecurityTypeID
  INNER JOIN $DB4.dbo.loanGLGroup lgl ON lgl.GLGroupID = lle.GLGroupID
  INNER JOIN (
  		SELECT TOP ".$TopNumber." a.ClientID,a.orig,a.prin,ROW_NUMBER() OVER(ORDER BY ".$sort." DESC) AS RowNum
  			FROM(
  				SELECT lle.ClientID,SUM(lle.AmountApproved) orig,SUM(lle.PrincipalBalance) prin
  				FROM $DB4.dbo.loanLedger lle
  				WHERE lle.PrincipalBalance > 0
  				AND lle.AccountStatus = 'Released'
  				AND lle.LoanStatusID <> 5
  				AND DATEDIFF(DAY,lle.GrantedDateTime, ? ) >= 0
  				GROUP BY lle.ClientID
  				) as a
  				ORDER BY ".$sort." DESC
  		) a ON lle.ClientID = a.ClientID
  ORDER BY  BranchNo,RowNum,AmountApproved DESC";
}
    $stmt = sqlsrv_query( $conn, $sql, $param);
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
