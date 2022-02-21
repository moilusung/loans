<?php
require "connection.php";

    $AccountGrantedFrom = $_POST['AccountGrantedFrom'];
    $AccountGrantedTo = $_POST['AccountGrantedTo'];
    $param = array($AccountGrantedTo,$AccountGrantedTo,$AccountGrantedFrom,$AccountGrantedTo,$AccountGrantedTo,$AccountGrantedFrom,$AccountGrantedTo,$AccountGrantedTo,$AccountGrantedFrom,$AccountGrantedTo,$AccountGrantedTo,$AccountGrantedFrom);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=AccountGranted-'.$Branch.'-'.$AccountGrantedFrom.'-'.$AccountGrantedTo.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('Account Granted','','CUTOFF DATE:','From-'.$AccountGrantedFrom,'To-'.$AccountGrantedTo));
fputcsv($output, array('BranchNo','LoanNo','AccountName','StatusDescription','ApprovalDateTime','MaturityDateTime','AmountApproved','PrincipalBalance','TransactionDateTime'));
$sql = "SELECT lle.BranchNo,
            		lle.LoanNo,
            		lle.AccountName,
            		lst.StatusDescription,
            		CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as ApprovalDateTime,
            		 CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDate,
            		lle.AmountApproved,
            		lin.PrincipalBalance,
            		CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime
            FROM $DB1.dbo.loanledger lle
            INNER JOIN $DB1.dbo.loanIncome lin ON lle.AccountNumber = lin.AccountNumber
            INNER JOIN $DB1.dbo.loanStatus lst ON lin.LoanStatusID = lst.StatusID
            INNER JOIN
            	(
            		SELECT ltr.TransactionDateTime,
            				ltr.AccountNumber,
            				ltr.TransactionNumber,
            				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
            		FROM $DB1.dbo.loanTransaction ltr
            		WHERE DATEDIFF(day,ltr.TransactionDateTime,?) >= 0
            		AND ltr.TransactionType <> 'Transfer'
            	) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
            WHERE lin.ReportDateTime = ?
            AND lin.PrincipalBalance > 0
            AND lin.LoanStatusID <> 5
            AND lle. AccountStatus = 'Released'
            AND DATEDIFF(day,lle.ApprovalDateTime,?) <= 1
          UNION ALL
          SELECT lle.BranchNo,
                  lle.LoanNo,
                  lle.AccountName,
                  lst.StatusDescription,
                  CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as ApprovalDateTime,
                    CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDate,
                  lle.AmountApproved,
                  lin.PrincipalBalance,
                  CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime
              FROM $DB2.dbo.loanledger lle
              INNER JOIN $DB2.dbo.loanIncome lin ON lle.AccountNumber = lin.AccountNumber
              INNER JOIN $DB2.dbo.loanStatus lst ON lin.LoanStatusID = lst.StatusID
              INNER JOIN
                (
                  SELECT ltr.TransactionDateTime,
                      ltr.AccountNumber,
                      ltr.TransactionNumber,
                      ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                  FROM $DB2.dbo.loanTransaction ltr
                  WHERE DATEDIFF(day,ltr.TransactionDateTime,?) >= 0
                  AND ltr.TransactionType <> 'Transfer'
                ) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
              WHERE lin.ReportDateTime = ?
              AND lin.PrincipalBalance > 0
              AND lin.LoanStatusID <> 5
              AND lle. AccountStatus = 'Released'
              AND DATEDIFF(day,lle.ApprovalDateTime,?) <= 1
            UNION ALL
            SELECT lle.BranchNo,
                		lle.LoanNo,
                		lle.AccountName,
                		lst.StatusDescription,
                		CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as ApprovalDateTime,
                		  CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDate,
                		lle.AmountApproved,
                		lin.PrincipalBalance,
                		CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime
                FROM $DB3.dbo.loanledger lle
                INNER JOIN $DB3.dbo.loanIncome lin ON lle.AccountNumber = lin.AccountNumber
                INNER JOIN $DB3.dbo.loanStatus lst ON lin.LoanStatusID = lst.StatusID
                INNER JOIN
                	(
                		SELECT ltr.TransactionDateTime,
                				ltr.AccountNumber,
                				ltr.TransactionNumber,
                				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                		FROM $DB3.dbo.loanTransaction ltr
                		WHERE DATEDIFF(day,ltr.TransactionDateTime,?) >= 0
                		AND ltr.TransactionType <> 'Transfer'
                	) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
                WHERE lin.ReportDateTime = ?
                AND lin.PrincipalBalance > 0
                AND lin.LoanStatusID <> 5
                AND lle. AccountStatus = 'Released'
                AND DATEDIFF(day,lle.ApprovalDateTime,?) <= 1
            UNION ALL
            SELECT lle.BranchNo,
                		lle.LoanNo,
                		lle.AccountName,
                		lst.StatusDescription,
                		CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as ApprovalDateTime,
                		  CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as MaturityDate,
                		lle.AmountApproved,
                		lin.PrincipalBalance,
                		CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as TransactionDateTime
                FROM $DB4.dbo.loanledger lle
                INNER JOIN $DB4.dbo.loanIncome lin ON lle.AccountNumber = lin.AccountNumber
                INNER JOIN $DB4.dbo.loanStatus lst ON lin.LoanStatusID = lst.StatusID
                INNER JOIN
                	(
                		SELECT ltr.TransactionDateTime,
                				ltr.AccountNumber,
                				ltr.TransactionNumber,
                				ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                		FROM $DB4.dbo.loanTransaction ltr
                		WHERE DATEDIFF(day,ltr.TransactionDateTime,?) >= 0
                		AND ltr.TransactionType <> 'Transfer'
                	) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
                WHERE lin.ReportDateTime = ?
                AND lin.PrincipalBalance > 0
                AND lin.LoanStatusID <> 5
                AND lle. AccountStatus = 'Released'
                AND DATEDIFF(day,lle.ApprovalDateTime,?) <= 1
            ORDER BY BranchNo,ApprovalDateTime";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
