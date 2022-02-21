<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard</title>

    <style media="screen">
      #div div {
        position: relative;
        height: 250px;
        font-family: arial;
      }
    #div table {
        font-family: arial;
        font-size: 15px;
        text-align: center;
         font-weight: bold;
      }
      #div span {
          font-family: arial;
          font-size: 14px;
        }
      .carousel {
        height: 80%;
      }
      .center {
        padding: 40px 0;
      text-align: center;
      }
      .center1 {
        padding: 20px 0;
      text-align: center;
      }

    </style>
  </head>
  <?php include('Template/header.php'); ?>
  <body class=" bg-light">
    <div class="container">
        <div id='div'  class="row" style="margin-top:10px">
            <div class="col-md-4 bg-light border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-warning rounded-top text-center" style="height:10%;padding:0" >
                <span class="text-dark"  style="">Specific Allowance for Credit Loses</span>
              </div>
              <div class="table-responsive bg-light rounded-bottom" style="height:90%;padding:0px" >
                <table class="table table-sm text-center text-dark" style="font-family:arial">
                  <thead>
                    <tr>
                      <td>Branch</td>
                      <td>Date</td>
                      <td>Amount</td>
                    </tr>
                  </thead>
                  <?php
                    require 'connection.php';
                    $sql = "SELECT acl.BranchNo,
                            	CONVERT(varchar,FORMAT(acl.ReportDateTime,'yyyy-MM-dd')) as ReportDate,
                            SUM(acl.acl)
                     as acl FROM(
                              		SELECT	lle.BranchNo,
                              				lin.ReportDateTime,
                              				lle.AccountNumber,
                              				lin.MissedPaymentDays,
                              				CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
                              				CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) *  (lse.C_UnclassifiedRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_DoubtfulRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateB/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateB/100))
                              				ELSE 0
                              				END
                              				ELSE ((lin.PrincipalBalance - lin.EndingDiscount) *  (5.0/100))
                              				END  'acl'
                              		FROM $DB1.dbo.loanLedger lle
                              		INNER JOIN $DB1.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                              		INNER JOIN $DB1.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                              		INNER JOIN $DB1.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                              		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB4.dbo.LoanIncome)
                              		AND lin.PrincipalBalance > 0
                              		AND lle.AccountStatus = 'Released'
                              		AND lle.LoanStatusID <> 5
                              		UNION ALL
                              		SELECT	lle.BranchNo,
                              				lin.ReportDateTime,
                              				lle.AccountNumber,
                              				lin.MissedPaymentDays,
                              				CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
                              				CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) *  (lse.C_UnclassifiedRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_DoubtfulRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateB/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateB/100))
                              				ELSE 0
                              				END
                              				ELSE ((lin.PrincipalBalance - lin.EndingDiscount) *  (5.0/100))
                              				END  'acl'
                              		FROM $DB2.dbo.loanLedger lle
                              		INNER JOIN $DB2.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                              		INNER JOIN $DB2.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                              		INNER JOIN $DB2.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                              		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB4.dbo.LoanIncome)
                              		AND lin.PrincipalBalance > 0
                              		AND lle.AccountStatus = 'Released'
                              		AND lle.LoanStatusID <> 5
                              		UNION ALL
                              		SELECT	lle.BranchNo,
                              				lin.ReportDateTime,
                              				lle.AccountNumber,
                              				lin.MissedPaymentDays,
                              				CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
                              				CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) *  (lse.C_UnclassifiedRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_DoubtfulRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateB/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateB/100))
                              				ELSE 0
                              				END
                              				ELSE ((lin.PrincipalBalance - lin.EndingDiscount) *  (5.0/100))
                              				END  'acl'
                              		FROM $DB3.dbo.loanLedger lle
                              		INNER JOIN $DB3.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                              		INNER JOIN $DB3.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                              		INNER JOIN $DB3.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                              		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB4.dbo.LoanIncome)
                              		AND lin.PrincipalBalance > 0
                              		AND lle.AccountStatus = 'Released'
                              		AND lle.LoanStatusID <> 5
                              		UNION ALL
                              		SELECT	lle.BranchNo,
                              				lin.ReportDateTime,
                              				lle.AccountNumber,
                              				lin.MissedPaymentDays,
                              				CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
                              				CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) *  (lse.C_UnclassifiedRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_DoubtfulRate/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateA/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_SubStandardRateB/100))
                              				WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
                              				THEN ((lin.PrincipalBalance - lin.EndingDiscount) * (lse.C_LossRateB/100))
                              				ELSE 0
                              				END
                              				ELSE ((lin.PrincipalBalance - lin.EndingDiscount) *  (5.0/100))
                              				END  'acl'
                              		FROM $DB4.dbo.loanLedger lle
                              		INNER JOIN $DB4.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                              		INNER JOIN $DB4.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                              		INNER JOIN $DB4.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                              		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB4.dbo.LoanIncome)
                              		AND lin.PrincipalBalance > 0
                              		AND lle.AccountStatus = 'Released'
                              		AND lle.LoanStatusID <> 5
                              ) AS ACL
                              GROUP BY ACL.BranchNo,ACL.ReportDateTime
                              ORDER BY ACL.BranchNo";
                        $stmt = sqlsrv_query( $conn, $sql);
                        while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                        {
                   ?>
                  <tbody>
                      <tr>
                        <td><?php echo $row['BranchNo']; ?></td>
                        <td><?php echo $row['ReportDate']; ?></td>
                        <td><?php echo number_format($row['acl'], 2, '.', ','); ?></td>
                      </tr>
                    <?php
                        $data[] = $row['acl'];
                        }
                        $a=$data;

                  ?>
                    <tr style="height:10px">
                      <td colspan="2">Total</td>
                      <td><?php echo number_format(array_sum($a), 2, '.', ','); ?></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!--  -->
            <div class="col-md-4 bg-white border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-primary rounded-top text-center" style="height:10%;padding:0" >
                <span class="text-white"  style="">Specific General Loan Loss Provision</span>
              </div>
              <div class="table-responsive bg-light rounded-bottom" style="height:90%;padding:0px" >
                <table class="table table-sm text-dark  text-center" style="">
                  <thead>
                    <tr>
                      <td>Branch</td>
                      <td>Date</td>
                      <td>Amount</td>
                    </tr>
                  </thead>
                  <?php
                    require 'connection.php';
                    $sql = "SELECT gllp.BranchNo,
                                CONVERT(varchar,FORMAT(gllp.ReportDateTime,'yyyy-MM-dd')) as ReportDate,
                                SUM(gllp.gllp) as gllp
                              FROM(
                            		SELECT	lle.BranchNo,
                            				lin.ReportDateTime,
                            				lle.AccountNumber,
                            				lin.MissedPaymentDays,
                            		CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
                                      THEN
                                  		CASE WHEN lle.LoanTypeID = 3
                                  		THEN
                                  			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
                                  		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
                                  		END
                                      ELSE 0 END 'gllp'
                            		FROM $DB1.dbo.loanLedger lle
                            		INNER JOIN $DB1.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                            		INNER JOIN $DB1.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                            		INNER JOIN $DB1.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                            		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB1.dbo.LoanIncome)
                            		AND lin.PrincipalBalance > 0
                            		AND lle.AccountStatus = 'Released'
                            		AND lle.LoanStatusID <> 5
                            		UNION ALL
                            		SELECT	lle.BranchNo,
                            				lin.ReportDateTime,
                            				lle.AccountNumber,
                            				lin.MissedPaymentDays,
                            				CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
                                      THEN
                                  		CASE WHEN lle.LoanTypeID = 3
                                  		THEN
                                  			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
                                  		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
                                  		END
                                      ELSE 0 END 'gllp'
                            		FROM $DB2.dbo.loanLedger lle
                            		INNER JOIN $DB2.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                            		INNER JOIN $DB2.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                            		INNER JOIN $DB2.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                            		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB2.dbo.LoanIncome)
                            		AND lin.PrincipalBalance > 0
                            		AND lle.AccountStatus = 'Released'
                            		AND lle.LoanStatusID <> 5
                            		UNION ALL
                            		SELECT	lle.BranchNo,
                            				lin.ReportDateTime,
                            				lle.AccountNumber,
                            				lin.MissedPaymentDays,
                            				CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
                                      THEN
                                  		CASE WHEN lle.LoanTypeID = 3
                                  		THEN
                                  			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
                                  		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
                                  		END
                                      ELSE 0 END 'gllp'
                            		FROM $DB3.dbo.loanLedger lle
                            		INNER JOIN $DB3.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                            		INNER JOIN $DB3.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                            		INNER JOIN $DB3.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                            		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB3.dbo.LoanIncome)
                            		AND lin.PrincipalBalance > 0
                            		AND lle.AccountStatus = 'Released'
                            		AND lle.LoanStatusID <> 5
                            		UNION ALL
                            		SELECT	lle.BranchNo,
                            				lin.ReportDateTime,
                            				lle.AccountNumber,
                            				lin.MissedPaymentDays,
                            				CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
                                      THEN
                                  		CASE WHEN lle.LoanTypeID = 3
                                  		THEN
                                  			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
                                  		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
                                  		END
                                      ELSE 0 END 'gllp'
                            		FROM $DB4.dbo.loanLedger lle
                            		INNER JOIN $DB4.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
                            		INNER JOIN $DB4.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
                            		INNER JOIN $DB4.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
                            		WHERE lin.ReportDateTime = (SELECT MAX(ReportDateTime) FROM $DB4.dbo.LoanIncome)
                            		AND lin.PrincipalBalance > 0
                            		AND lle.AccountStatus = 'Released'
                            		AND lle.LoanStatusID <> 5
                            ) AS gllp
                            GROUP BY gllp.BranchNo,gllp.ReportDateTime
                            ORDER BY gllp.BranchNo";
                        $stmt = sqlsrv_query( $conn, $sql);
                        while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                        {
                   ?>
                  <tbody>
                      <tr>
                        <td><?php echo $row['BranchNo']; ?></td>
                        <td><?php echo $row['ReportDate']; ?></td>
                        <td><?php echo number_format($row['gllp'], 2, '.', ','); ?></td>
                      </tr>
                    <?php
                        $data1[] = $row['gllp'];
                        }
                        $a1=$data1;

                  ?>
                    <tr>
                      <td colspan="2">Total</td>
                      <td><?php echo number_format(array_sum($a1), 2, '.', ','); ?></td>
                    </tr>
                  </tbody>

                </table>
              </div>
            </div>
            <!--  -->
            <div class="col-md-4 bg-white border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-light rounded-bottom" style="height:100%;padding:0" >
                  <?php
                    require 'connection.php';
                    $sql = "SELECT
                    tb.RowNum,
                    MAX(tb.AccountName) accountname,
                    SUM(tb.AmountApproved) approved,
                    SUM(tb.PrincipalBalance) balance,
                    MAX(tb.StatusDescription) loanstatus
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
                            b.orig
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
                        SELECT TOP 20 c.ClientID,c.orig,ROW_NUMBER() OVER(ORDER BY c.orig DESC) AS RowNum
                        FROM(
                            SELECT e.ClientID,SUM(e.AmountApproved) orig
                            FROM(
                            SELECT CASE WHEN d.AccountName like '%SYSTEMS PLUS%'
                                    THEN '10000776' ELSE d.ClientID END ClientID,
                                    d.AmountApproved FROM (
                                SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime
                                FROM $DB1.dbo.loanLedger lle
                                WHERE lle.PrincipalBalance > 0
                                AND lle.AccountStatus = 'Released'
                                AND lle.LoanStatusID <> 5
                                UNION ALL
                                SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime
                                FROM $DB2.dbo.loanLedger lle
                                WHERE lle.PrincipalBalance > 0
                                AND lle.AccountStatus = 'Released'
                                AND lle.LoanStatusID <> 5
                                UNION ALL
                                SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime
                                FROM $DB3.dbo.loanLedger lle
                                WHERE lle.PrincipalBalance > 0
                                AND lle.AccountStatus = 'Released'
                                AND lle.LoanStatusID <> 5
                                UNION ALL
                                SELECT lle.ClientID,lle.AccountName,lle.AmountApproved,lle.GrantedDateTime
                                FROM $DB4.dbo.loanLedger lle
                                WHERE lle.PrincipalBalance > 0
                                AND lle.AccountStatus = 'Released'
                                AND lle.LoanStatusID <> 5
                                ) as d
                            )as e
                            GROUP BY e.ClientID
                            )as c
                            ORDER BY c.orig DESC
                        ) b ON a.GeneratedClientID = b.ClientID
                        ) as tb
                        GROUP BY RowNum
                        ORDER BY approved DESC";
                        $stmt = sqlsrv_query( $conn, $sql);
                   ?>

                <div id="carouselExampleControls" class="carousel slide" data-ride="carousel" >
                    <div class="carousel-inner">
                      <div class="carousel-item active">
                        <div class="bg-white rounded-top" style="height:10%"></div>
                          <div class=" bg-light center" style="height:75%;text-align:center" >
                            <h2 class="text-primary" style="text-align:center;font-family:arial;font-weight: bold;">TOP <br> BORROWER</h2>
                          </div>
                        <div class="bg-white rounded-bottom" style="height:10%"></div>
                      </div>

                      <?php
                      $stmt = sqlsrv_query( $conn, $sql);

                      while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                      {
                       ?>
                       <div class="carousel-item">
                         <div class="bg-white rounded-top" style="height:10%"></div>
                           <div class="center1 bg-light" style="height:75%;text-align:center" >
                             <h3 class="text-primary" style="text-align:center;font-family:arial;font-weight: 900;"><?php echo $row['RowNum']; ?></h3>
                             <h6 class="text-dark" style="text-align:center;font-family:arial;font-weight: bold;"><?php echo $row['accountname']; ?></h6>
                             <h6 class="text-danger" style="text-align:center;font-family:arial;font-weight: bold;"><?php echo 'PHP '.number_format($row['approved'], 2, '.', ','); ?></h6>
                             <h6 class="text-dark" style="text-align:center;font-family:arial;font-weight: bold;"><?php echo $row['loanstatus']; ?></h6>
                           </div>
                         <div class="bg-white rounded-bottom" style="height:10%"></div>
                        </div>
                        <?php
                            }
                       ?>

                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="sr-only">Next</span>
                    </a>
                  </div>
                </div>
            </div>
            <!--  -->
            <div class="col-md-4 bg-white border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-danger rounded-top text-center" style="height:10%;padding:0" >
                <span class="text-white" style="">Negative File Information System Reported</span>
              </div>
              <div class="table-responsive  bg-light rounded-bottom" style="height:90%;padding:0px" >
                <table class="table table-sm text-center text-dark" style="">
                  <thead>
                    <tr>
                      <td>Branch</td>
                      <td>PD/NPL</td>
                      <td>WO</td>
                      <td>IL</td>
                      <td>FC</td>
                    </tr>
                  </thead>
                  <?php
                    require 'connection.php';
                    $sql = "SELECT a.ReportingBranch,a.pastdue,b.wo,c.il,d.fc FROM(
                          	SELECT pd.ReportingBranch,COUNT(pd.LoanReferenceNo) as pastdue
                          	FROM nfispastdueindiv pd
                          	GROUP BY pd.ReportingBranch
                          	) as a
                          INNER JOIN (
                          SELECT * FROM(
                          	SELECT wo.ReportingBranch,COUNT(wo.LoanReferenceNo) as wo
                          	FROM nfiswrittenoffindiv wo
                          	GROUP BY wo.ReportingBranch
                          	) as b
                          ) b on b.ReportingBranch = a.ReportingBranch
                          INNER JOIN (
                          SELECT * FROM(
                          	SELECT il.ReportingBranch,COUNT(il.LoanReferenceNo) as il
                          	FROM nfisILindiv il
                          	GROUP BY il.ReportingBranch
                          	) as c
                          ) c on c.ReportingBranch = a.ReportingBranch
                          INNER JOIN (
                          SELECT * FROM(
                          	SELECT fc.ReportingBranch,COUNT(fc.LoanReferenceNo) as fc
                          	FROM nfisForeclosedindiv fc
                          	GROUP BY fc.ReportingBranch
                          	) as d
                          ) d on d.ReportingBranch = a.ReportingBranch";
                        $stmt = sqlsrv_query( $conn, $sql);
                        while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                        {
                   ?>
                  <tbody>
                      <tr>
                        <td><?php echo $row['ReportingBranch'];?></td>
                        <td><?php echo number_format($row['pastdue']);?></td>
                        <td><?php echo number_format($row['wo']);?></td>
                        <td><?php echo number_format($row['il']);?></td>
                        <td><?php echo number_format($row['fc']);?></td>
                      </tr>
                    <?php
                        $pd[] = $row['pastdue'];
                        $wo[] = $row['wo'];
                        $il[] = $row['il'];
                        $fc[] = $row['fc'];
                        }
                        $pd=$pd;
                        $wo=$wo;
                        $il=$il;
                        $fc=$fc;

                  ?>
                    <tr>
                      <td>Total</td>
                      <td><?php echo number_format(array_sum($pd)); ?></td>
                      <td><?php echo number_format(array_sum($wo)); ?></td>
                      <td><?php echo number_format(array_sum($il)); ?></td>
                      <td><?php echo number_format(array_sum($fc)); ?></td>
                    </tr>
                  </tbody>

                </table>
              </div>
            </div>
            <!--  -->
            <div class="col-md-4 bg-white border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-info rounded-top text-center text-white" style="height:10%;padding:0" >
                <span style="">Loan Status</span>
              </div>
              <div class="table-responsive  bg-light rounded-bottom" style="height:90%;padding:0px" >
                <table class="table table-sm " style="">
                  <thead>
                    <tr  class="bg-light">
                      <td>Branch</td>
                      <td>CUR</td>
                      <td>PD</td>
                      <td>NPL</td>
                      <td>WO</td>
                      <td>IL</td>
                      <td>FC</td>
                    </tr>
                  </thead>
                  <?php
                    require 'connection.php';
                    $sql = "SELECT lle.BranchNo,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 1
                      			AND a.PrincipalBalance >0
                      			AND a.AccountStatus = 'Released' ) as cur,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 2
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as pd,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 3
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as npl,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID BETWEEN 3 AND 4
                      			AND a.PrincipalBalance = 1
                      			AND a.AccountStatus = 'Released' ) as wo,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 4
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as il,
                      			(SELECT COUNT(*) FROM $DB1.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 5
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as fc
                      	FROM $DB1.dbo.loanledger lle
                      	GROUP BY lle.BranchNo
                      	UNION ALL
                      		SELECT lle.BranchNo,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 1
                      			AND a.PrincipalBalance >0
                      			AND a.AccountStatus = 'Released' ) as cur,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 2
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as pd,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 3
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as npl,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID BETWEEN 3 AND 4
                      			AND a.PrincipalBalance = 1
                      			AND a.AccountStatus = 'Released' ) as wo,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 4
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as il,
                      			(SELECT COUNT(*) FROM $DB2.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 5
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as fc
                      	FROM $DB2.dbo.loanledger lle
                      	GROUP BY lle.BranchNo
                      		UNION ALL
                      		SELECT lle.BranchNo,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 1
                      			AND a.PrincipalBalance >0
                      			AND a.AccountStatus = 'Released' ) as cur,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 2
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as pd,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 3
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as npl,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID BETWEEN 3 AND 4
                      			AND a.PrincipalBalance = 1
                      			AND a.AccountStatus = 'Released' ) as wo,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 4
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as il,
                      			(SELECT COUNT(*) FROM $DB3.dbo.loanLedger a
                      			WHERE a.LoanStatusID = 5
                      			AND a.PrincipalBalance >1
                      			AND a.AccountStatus = 'Released' ) as fc
                      	FROM $DB3.dbo.loanledger lle
                      	GROUP BY lle.BranchNo
                      	UNION ALL
                      	SELECT lle.BranchNo,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID = 1
                      		AND a.PrincipalBalance >0
                      		AND a.AccountStatus = 'Released' ) as cur,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID = 2
                      		AND a.PrincipalBalance >1
                      		AND a.AccountStatus = 'Released' ) as pd,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID = 3
                      		AND a.PrincipalBalance >1
                      		AND a.AccountStatus = 'Released' ) as npl,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID BETWEEN 3 AND 4
                      		AND a.PrincipalBalance = 1
                      		AND a.AccountStatus = 'Released' ) as wo,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID = 4
                      		AND a.PrincipalBalance >1
                      		AND a.AccountStatus = 'Released' ) as il,
                      		(SELECT COUNT(*) FROM $DB4.dbo.loanLedger a
                      		WHERE a.LoanStatusID = 5
                      		AND a.PrincipalBalance >1
                      		AND a.AccountStatus = 'Released' ) as fc
                      FROM $DB4.dbo.loanledger lle
                      GROUP BY lle.BranchNo";
                        $stmt = sqlsrv_query( $conn, $sql);
                        while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                        {
                   ?>
                  <tbody>
                      <tr  class="bg-light">
                        <td><?php echo $row['BranchNo'];?></td>
                        <td><?php echo number_format($row['cur']);?></td>
                        <td><?php echo number_format($row['pd']);?></td>
                        <td><?php echo number_format($row['npl']);?></td>
                        <td><?php echo number_format($row['wo']);?></td>
                        <td><?php echo number_format($row['il']);?></td>
                        <td><?php echo number_format($row['fc']);?></td>
                      </tr>
                    <?php
                        $cur[] = $row['cur'];
                        $pd[] = $row['pd'];
                        $npl[] = $row['npl'];
                        $wo[] = $row['wo'];
                        $il[] = $row['il'];
                        $fc[] = $row['fc'];
                        }
                        $cur=$cur;
                        $pd=$pd;
                        $npl=$npl;
                        $wo=$wo;
                        $il=$il;
                        $fc=$fc;

                  ?>
                    <tr  class="bg-light">
                      <td>Total</td>
                      <td><?php echo number_format(array_sum($cur));?></td>
                      <td><?php echo number_format(array_sum($pd));?></td>
                      <td><?php echo number_format(array_sum($npl));?></td>
                      <td><?php echo number_format(array_sum($wo));?></td>
                      <td><?php echo number_format(array_sum($il));?></td>
                      <td><?php echo number_format(array_sum($fc));?></td>
                    </tr>
                  </tbody>

                </table>
              </div>
            </div>
            <!--  -->
            <div class="col-md-4 bg-white border shadow-sm p-1 mb-1 bg-white rounded">
              <div class=" bg-success rounded-top text-center" style="height:10%;padding:0" >
                <span class="text-white" style="">Unscanned Document</span>
              </div>
              <div class="table-responsive  bg-light rounded-bottom" style="height:90%;padding:0px" >
                <table class="table table-sm text-dark" style="">
                  <thead>
                    <tr>
                      <td>Branch</td>
                      <td>No. of Unscanned</td>
                    </tr>
                  </thead>
                  <?php
                    require 'connection.php';
                    $date = $today;
                    $param = array($date,$date,$date,$date);
                    $sql = "SELECT lle.BranchNo,COUNT(lle.AccountNumber) Unscanned
                            FROM $DB1.dbo.loanledger lle
                            WHERE lle.GrantedDateTime BETWEEN '2021-01-01' AND ?
                            AND lle.AccountStatus = 'Released'
                            AND lle.AccountNumber
                            NOT IN(
                            		SELECT DISTINCT dms.AccountNumber
                            		FROM $DB1.dbo.ddmsFilename dms
                            		WHERE dms.AccountType = 'Loan Account'
                            	   )
                            GROUP BY lle.BranchNo
                            UNION ALL
                            SELECT lle.BranchNo,COUNT(lle.AccountNumber) Unscanned
                            FROM $DB2.dbo.loanledger lle
                            WHERE lle.GrantedDateTime BETWEEN '2021-01-01' AND ?
                            AND lle.AccountStatus = 'Released'
                            AND lle.AccountNumber
                            NOT IN(
                            		SELECT DISTINCT dms.AccountNumber
                            		FROM $DB2.dbo.ddmsFilename dms
                            		WHERE dms.AccountType = 'Loan Account'
                            	   )
                            GROUP BY lle.BranchNo
                            UNION ALL
                            SELECT lle.BranchNo,COUNT(lle.AccountNumber) Unscanned
                            FROM $DB3.dbo.loanledger lle
                            WHERE lle.GrantedDateTime BETWEEN '2021-01-01' AND ?
                            AND lle.AccountStatus = 'Released'
                            AND lle.AccountNumber
                            NOT IN(
                            		SELECT DISTINCT dms.AccountNumber
                            		FROM $DB3.dbo.ddmsFilename dms
                            		WHERE dms.AccountType = 'Loan Account'
                            	   )
                            GROUP BY lle.BranchNo
                            UNION ALL
                            SELECT lle.BranchNo,COUNT(lle.AccountNumber) Unscanned
                            FROM $DB4.dbo.loanledger lle
                            WHERE lle.GrantedDateTime BETWEEN '2021-01-01' AND ?
                            AND lle.AccountStatus = 'Released'
                            AND lle.AccountNumber
                            NOT IN(
                            		SELECT DISTINCT dms.AccountNumber
                            		FROM $DB4.dbo.ddmsFilename dms
                            		WHERE dms.AccountType = 'Loan Account'
                            	   )
                            GROUP BY lle.BranchNo";
                        $stmt = sqlsrv_query( $conn, $sql,$param);
                        while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
                        {
                   ?>
                  <tbody>
                      <tr>
                        <td><?php echo $row['BranchNo']; ?></td>
                        <td><?php echo number_format($row['Unscanned']); ?></td>
                      </tr>
                    <?php
                        $Unscanned[] = $row['Unscanned'];
                        }
                        $Unscanned=$Unscanned;

                  ?>
                    <tr>
                      <td>Total</td>
                      <td><?php echo number_format(array_sum($Unscanned)); ?></td>
                    </tr>
                  </tbody>

                </table>
              </div>
            </div>
            <!--  -->
        </div>
    </div>
  </body>
  <?php include('Template/footer.php') ?>
</html>
