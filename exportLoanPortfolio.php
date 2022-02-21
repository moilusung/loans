<?php
require "connection.php";
    $cutoff = $_POST['dateLoanPortfolio'];

    $CutoffDate = date("Y-M-t", strtotime($cutoff));
   $param = array($CutoffDate,$CutoffDate,$CutoffDate,$CutoffDate,$CutoffDate,$CutoffDate,$CutoffDate,$CutoffDate);

header('Content-Type: text/csv; charset-utf-8');
header('Content-Disposition: attachment; filename=LoanPortfolio-'.$Branch.'-'.$CutoffDate.'.csv');
$output = fopen("php://output","w");
fputcsv($output, array('LOAN PORTFOLIO','','MONTHEND DATE: '.$CutoffDate));
fputcsv($output, array('BranchNo','PolicyNo','AccountName','CompleteAddress','Industry','Loan Type','Date Granted','Principal Amount','Outstaniding Balance','Maturity Date','Mode of Repayment','Source of income','Nominal EIR','EIR','Accrued Interest Receivable','Unamortized Service Charge','Unearned Interest and Discount','AmortizedCost','Specific Allowance for Credit Losses','General Loan Loss Provision','Monthly Amortization','Last Payment Date','PrincipalPayment','InterestPayment','MissedPaymentDays','SecurityTypeDescription','StatusDescription','Classification','ClassificationRate','Compliance to Agri Agra','Restructured','DOSRI','Report Date','BANKCODE','BANKREG','IncomeID','LoanNo','AccountNumber'));
$sql = "SELECT
          lle.BranchNo,
          lle.PolicyNo,
          lle.AccountName,
          cfa.CompleteAddress,
          lgl.GLGroupDesc as Industry,
          lty.LoanTypeDescription as 'Loan Type', -- for modification
          CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as 'Date Granted',
          lle.AmountApproved as 'Principal Amount',
          lin.PrincipalBalance as 'Outstanding Balance',
          CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as 'Maturity Date',
          lsc.ScheduleDescription as 'Mode of Repayment',
          cso.Description as 'Source of income',
          lra.InterestRate as 'Nominal EIR',
          lle.AnnualEIR/100 as EIR,
          '' as 'Accrued Interest Receivable',
          lin.EndingDiscount as 'Unamortized Service Charge',
          0 as 'Unearned Interest and Discount',
          (lin.PrincipalBalance - lin.EndingDiscount) AS AmortizedCost,
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
          END  'Specific Allowance for Credit Losses',
          CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
          THEN
      		CASE WHEN lle.LoanTypeID = 3
      		THEN
      			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
      		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
      		END
          ELSE 0 END 'General Loan Loss Provision',
          lle.TotalAmort as 'Monthly Amortization',
          CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as 'Last Payment Date',
          lin.PrincipalPayment,
          lin.InterestPayment,
          lin.MissedPaymentDays,
          lse.SecurityTypeDescription,
          lst.StatusDescription,
          CASE WHEN lfrp.LoanFRPStatusID != 1 THEN lfrp.Description
          ELSE 'Pass' END as Classification,
          CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
          CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
          THEN lse.C_UnclassifiedRate/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
          THEN  lse.C_SubStandardRateA/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
          THEN lse.C_DoubtfulRate/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
          THEN lse.C_LossRateA/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
          THEN lse.C_SubStandardRateB/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
          THEN lse.C_LossRateB/100
          ELSE
          CASE WHEN  (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
          THEN 0.01
          ELSE 0 END
          END
          ELSE (5.0/100)
          END ClassificationRate,
          '' as 'Compliance to Agri Agra',
          '' as Restructured,
          '' as DOSRI,
          CONVERT(varchar,FORMAT(lin.ReportDateTime,'yyyy-MM-dd')) as 'Report Date',
      		CASE WHEN lle.BranchNo = 1 THEN '02001000000'
        			 WHEN lle.BranchNo = 2 THEN '02001001001'
        			 WHEN lle.BranchNo = 3 THEN '02001001002'
        			 WHEN lle.BranchNo = 4 THEN '02001001003'
      		END as BANKCODE,
      		'3' as BANKREG,
          lin.IncomeID,
          lle.LoanNo,
          CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber
      FROM $DB1.dbo.loanledger lle
      INNER JOIN $DB1.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
      INNER JOIN $DB1.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
      INNER JOIN $DB1.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
      INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = lin.LoanStatusID
      INNER JOIN $DB1.dbo.loanGLGroup lgl ON lle.GLGroupID = lgl.GLGroupID
      INNER JOIN $DB1.dbo.loanType lty ON lle.LoanTypeID = lty.LoanTypeID
      INNER JOIN $DB1.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
      INNER JOIN $DB1.dbo.cifClient ccl ON ccl.ClientID = lle.ClientID
      LEFT JOIN $DB1.dbo.cifSourceOfFund cso ON cso.SourceOfFundID = ccl.SourceOfFundID
      INNER JOIN $DB1.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
      LEFT JOIN
      (SELECT
          ClientID,
          AddressTypeID,
          CompleteAddress,
          ROW_NUMBER() OVER (PARTITION BY ClientID ORDER BY AddressTypeID ASC) AS RowID
      FROM $DB1.dbo.cifAddress
      ) cfa ON cfa.ClientID = lle.ClientID AND cfa.RowID = 1
      INNER JOIN
      (
      SELECT ltr.TransactionDateTime,
          	ltr.AccountNumber,
          	ltr.TransactionNumber,
          	ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
      FROM $DB1.dbo.loanTransaction ltr
      WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 0
      AND ltr.TransactionType <> 'Transfer'
      ) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
      WHERE lin.ReportDateTime = (?)
      AND lin.LoanStatusID <> 5
      AND lle.AccountStatus = 'Released'
      AND lin.PrincipalBalance > 0
UNION ALL
SELECT
      lle.BranchNo,
      lle.PolicyNo,
      lle.AccountName,
      cfa.CompleteAddress,
      lgl.GLGroupDesc as Industry,
      lty.LoanTypeDescription as 'Loan Type', -- for modification
      CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as 'Date Granted',
      lle.AmountApproved as 'Principal Amount',
      lin.PrincipalBalance as 'Outstanding Balance',
      CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as 'Maturity Date',
      lsc.ScheduleDescription as 'Mode of Repayment',
      cso.Description as 'Source of income',
      lra.InterestRate as 'Nominal EIR',
      lle.AnnualEIR/100 as EIR,
      '' as 'Accrued Interest Receivable',
      lin.EndingDiscount as 'Unamortized Service Charge',
      0 as 'Unearned Interest and Discount',
      (lin.PrincipalBalance - lin.EndingDiscount) AS AmortizedCost,
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
      END  'Specific Allowance for Credit Losses',
      CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
      THEN
      CASE WHEN lle.LoanTypeID = 3
      THEN
        ((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
      ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
      END
      ELSE 0 END 'General Loan Loss Provision',
      lle.TotalAmort as 'Monthly Amortization',
      CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as 'Last Payment Date',
      lin.PrincipalPayment,
      lin.InterestPayment,
      lin.MissedPaymentDays,
      lse.SecurityTypeDescription,
      lst.StatusDescription,
      CASE WHEN lfrp.LoanFRPStatusID != 1 THEN lfrp.Description
      ELSE 'Pass' END as Classification,
      CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
      CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
      THEN lse.C_UnclassifiedRate/100
      WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
      THEN  lse.C_SubStandardRateA/100
      WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
      THEN lse.C_DoubtfulRate/100
      WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
      THEN lse.C_LossRateA/100
      WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
      THEN lse.C_SubStandardRateB/100
      WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
      THEN lse.C_LossRateB/100
      ELSE
      CASE WHEN  (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
      THEN 0.01
      ELSE 0 END
      END
      ELSE (5.0/100)
      END ClassificationRate,
      '' as 'Compliance to Agri Agra',
      '' as Restructured,
      '' as DOSRI,
      CONVERT(varchar,FORMAT(lin.ReportDateTime,'yyyy-MM-dd')) as 'Report Date',
      CASE WHEN lle.BranchNo = 1 THEN '02001000000'
           WHEN lle.BranchNo = 2 THEN '02001001001'
           WHEN lle.BranchNo = 3 THEN '02001001002'
           WHEN lle.BranchNo = 4 THEN '02001001003'
      END as BANKCODE,
      '3' as BANKREG,
      lin.IncomeID,
      lle.LoanNo,
      CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber
  FROM $DB2.dbo.loanledger lle
  INNER JOIN $DB2.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
  INNER JOIN $DB2.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
  INNER JOIN $DB2.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
  INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = lin.LoanStatusID
  INNER JOIN $DB2.dbo.loanGLGroup lgl ON lle.GLGroupID = lgl.GLGroupID
  INNER JOIN $DB2.dbo.loanType lty ON lle.LoanTypeID = lty.LoanTypeID
  INNER JOIN $DB2.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
  INNER JOIN $DB2.dbo.cifClient ccl ON ccl.ClientID = lle.ClientID
  LEFT JOIN $DB2.dbo.cifSourceOfFund cso ON cso.SourceOfFundID = ccl.SourceOfFundID
  INNER JOIN $DB2.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
  LEFT JOIN
  (SELECT
      ClientID,
      AddressTypeID,
      CompleteAddress,
      ROW_NUMBER() OVER (PARTITION BY ClientID ORDER BY AddressTypeID ASC) AS RowID
  FROM $DB2.dbo.cifAddress
  ) cfa ON cfa.ClientID = lle.ClientID AND cfa.RowID = 1
  INNER JOIN
  (
  SELECT ltr.TransactionDateTime,
        ltr.AccountNumber,
        ltr.TransactionNumber,
        ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
  FROM $DB2.dbo.loanTransaction ltr
  WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 0
  AND ltr.TransactionType <> 'Transfer'
  ) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
  WHERE lin.ReportDateTime = (?)
  AND lin.LoanStatusID <> 5
  AND lle.AccountStatus = 'Released'
  AND lin.PrincipalBalance > 0
 UNION ALL
 SELECT
       lle.BranchNo,
       lle.PolicyNo,
       lle.AccountName,
       cfa.CompleteAddress,
       lgl.GLGroupDesc as Industry,
       lty.LoanTypeDescription as 'Loan Type', -- for modification
       CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as 'Date Granted',
       lle.AmountApproved as 'Principal Amount',
       lin.PrincipalBalance as 'Outstanding Balance',
       CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as 'Maturity Date',
       lsc.ScheduleDescription as 'Mode of Repayment',
       cso.Description as 'Source of income',
       lra.InterestRate as 'Nominal EIR',
       lle.AnnualEIR/100 as EIR,
       '' as 'Accrued Interest Receivable',
       lin.EndingDiscount as 'Unamortized Service Charge',
       0 as 'Unearned Interest and Discount',
       (lin.PrincipalBalance - lin.EndingDiscount) AS AmortizedCost,
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
       END  'Specific Allowance for Credit Losses',
       CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
       THEN
   		CASE WHEN lle.LoanTypeID = 3
   		THEN
   			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
   		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
   		END
       ELSE 0 END 'General Loan Loss Provision',
       lle.TotalAmort as 'Monthly Amortization',
       CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as 'Last Payment Date',
       lin.PrincipalPayment,
       lin.InterestPayment,
       lin.MissedPaymentDays,
       lse.SecurityTypeDescription,
       lst.StatusDescription,
       CASE WHEN lfrp.LoanFRPStatusID != 1 THEN lfrp.Description
       ELSE 'Pass' END as Classification,
       CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
       CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
       THEN lse.C_UnclassifiedRate/100
       WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
       THEN  lse.C_SubStandardRateA/100
       WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
       THEN lse.C_DoubtfulRate/100
       WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
       THEN lse.C_LossRateA/100
       WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
       THEN lse.C_SubStandardRateB/100
       WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
       THEN lse.C_LossRateB/100
       ELSE
       CASE WHEN  (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
       THEN 0.01
       ELSE 0 END
       END
       ELSE (5.0/100)
       END ClassificationRate,
       '' as 'Compliance to Agri Agra',
       '' as Restructured,
       '' as DOSRI,
       CONVERT(varchar,FORMAT(lin.ReportDateTime,'yyyy-MM-dd')) as 'Report Date',
   		CASE WHEN lle.BranchNo = 1 THEN '02001000000'
     			 WHEN lle.BranchNo = 2 THEN '02001001001'
     			 WHEN lle.BranchNo = 3 THEN '02001001002'
     			 WHEN lle.BranchNo = 4 THEN '02001001003'
   		END as BANKCODE,
   		'3' as BANKREG,
       lin.IncomeID,
       lle.LoanNo,
       CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber
   FROM $DB3.dbo.loanledger lle
   INNER JOIN $DB3.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
   INNER JOIN $DB3.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
   INNER JOIN $DB3.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
   INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = lin.LoanStatusID
   INNER JOIN $DB3.dbo.loanGLGroup lgl ON lle.GLGroupID = lgl.GLGroupID
   INNER JOIN $DB3.dbo.loanType lty ON lle.LoanTypeID = lty.LoanTypeID
   INNER JOIN $DB3.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
   INNER JOIN $DB3.dbo.cifClient ccl ON ccl.ClientID = lle.ClientID
   LEFT JOIN $DB3.dbo.cifSourceOfFund cso ON cso.SourceOfFundID = ccl.SourceOfFundID
   INNER JOIN $DB3.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
   LEFT JOIN
   (SELECT
       ClientID,
       AddressTypeID,
       CompleteAddress,
       ROW_NUMBER() OVER (PARTITION BY ClientID ORDER BY AddressTypeID ASC) AS RowID
   FROM $DB3.dbo.cifAddress
   ) cfa ON cfa.ClientID = lle.ClientID AND cfa.RowID = 1
   INNER JOIN
   (
   SELECT ltr.TransactionDateTime,
       	ltr.AccountNumber,
       	ltr.TransactionNumber,
       	ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
   FROM $DB3.dbo.loanTransaction ltr
   WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 0
   AND ltr.TransactionType <> 'Transfer'
   ) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
   WHERE lin.ReportDateTime = (?)
   AND lin.LoanStatusID <> 5
   AND lle.AccountStatus = 'Released'
   AND lin.PrincipalBalance > 0
  UNION ALL
    SELECT
          lle.BranchNo,
          lle.PolicyNo,
          lle.AccountName,
          cfa.CompleteAddress,
          lgl.GLGroupDesc as Industry,
          lty.LoanTypeDescription as 'Loan Type', -- for modification
          CONVERT(varchar,FORMAT(lle.ApprovalDateTime,'yyyy-MM-dd')) as 'Date Granted',
          lle.AmountApproved as 'Principal Amount',
          lin.PrincipalBalance as 'Outstanding Balance',
          CONVERT(varchar,FORMAT(lle.MaturityDateTime,'yyyy-MM-dd')) as 'Maturity Date',
          lsc.ScheduleDescription as 'Mode of Repayment',
          cso.Description as 'Source of income',
          lra.InterestRate as 'Nominal EIR',
          lle.AnnualEIR/100 as EIR,
          '' as 'Accrued Interest Receivable',
          lin.EndingDiscount as 'Unamortized Service Charge',
          0 as 'Unearned Interest and Discount',
          (lin.PrincipalBalance - lin.EndingDiscount) AS AmortizedCost,
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
          END  'Specific Allowance for Credit Losses',
          CASE WHEN (lin.MissedPaymentDays BETWEEN 0 AND 30) AND (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
          THEN
      		CASE WHEN lle.LoanTypeID = 3
      		THEN
      			((lin.PrincipalBalance - lin.EndingDiscount) * (5.0/100))
      		ELSE ((lin.PrincipalBalance - lin.EndingDiscount) * (1.0/100))
      		END
          ELSE 0 END 'General Loan Loss Provision',
          lle.TotalAmort as 'Monthly Amortization',
          CONVERT(varchar,FORMAT(ltr.TransactionDateTime,'yyyy-MM-dd')) as 'Last Payment Date',
          lin.PrincipalPayment,
          lin.InterestPayment,
          lin.MissedPaymentDays,
          lse.SecurityTypeDescription,
          lst.StatusDescription,
          CASE WHEN lfrp.LoanFRPStatusID != 1 THEN lfrp.Description
          ELSE 'Pass' END as Classification,
          CASE WHEN lfrp.LoanFRPStatusID != 2 THEN -- Especially Mention
          CASE WHEN lin.MissedPaymentDays BETWEEN lse.C_UnclassifiedDaysFrom AND lse.C_UnclassifiedRate
          THEN lse.C_UnclassifiedRate/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromA AND lse.C_SubStandardDaysToA
          THEN  lse.C_SubStandardRateA/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_DoubtfulDaysFrom AND lse.C_DoubtfulDaysTo
          THEN lse.C_DoubtfulRate/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromA AND lse.C_LossDaysToA
          THEN lse.C_LossRateA/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_SubStandardDaysFromB AND lse.C_SubStandardDaysToB
          THEN lse.C_SubStandardRateB/100
          WHEN lin.MissedPaymentDays BETWEEN lse.C_LossDaysFromB AND lse.C_LossDaysToB
          THEN lse.C_LossRateB/100
          ELSE
          CASE WHEN  (lse.SecurityTypeCode != 'LAD') AND (lse.SecurityTypeCode NOT LIKE '%H.O.D.%')
          THEN 0.01
          ELSE 0 END
          END
          ELSE (5.0/100)
          END ClassificationRate,
          '' as 'Compliance to Agri Agra',
          '' as Restructured,
          '' as DOSRI,
          CONVERT(varchar,FORMAT(lin.ReportDateTime,'yyyy-MM-dd')) as 'Report Date',
      		CASE WHEN lle.BranchNo = 1 THEN '02001000000'
        			 WHEN lle.BranchNo = 2 THEN '02001001001'
        			 WHEN lle.BranchNo = 3 THEN '02001001002'
        			 WHEN lle.BranchNo = 4 THEN '02001001003'
      		END as BANKCODE,
      		'3' as BANKREG,
          lin.IncomeID,
          lle.LoanNo,
          CAST(LEFT(lle.AccountNumber, LEN(lle.AccountNumber) - 7) AS VARCHAR) + '-' + CAST(RIGHT(lle.AccountNumber, LEN(lle.AccountNumber) - 9)AS Varchar) AS AccountNumber
      FROM $DB4.dbo.loanledger lle
      INNER JOIN $DB4.dbo.loanIncome lin ON lin.AccountNumber = lle.AccountNumber
      INNER JOIN $DB4.dbo.loanSecurityType lse ON lle.SecurityTypeID = lse.SecurityTypeID
      INNER JOIN $DB4.dbo.loanFRPStatus lfrp ON lfrp.LoanFRPStatusID = lle.LoanFRPStatusID
      INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = lin.LoanStatusID
      INNER JOIN $DB4.dbo.loanGLGroup lgl ON lle.GLGroupID = lgl.GLGroupID
      INNER JOIN $DB4.dbo.loanType lty ON lle.LoanTypeID = lty.LoanTypeID
      INNER JOIN $DB4.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
      INNER JOIN $DB4.dbo.cifClient ccl ON ccl.ClientID = lle.ClientID
      LEFT JOIN $DB4.dbo.cifSourceOfFund cso ON cso.SourceOfFundID = ccl.SourceOfFundID
      INNER JOIN $DB4.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
      LEFT JOIN
      (SELECT
          ClientID,
          AddressTypeID,
          CompleteAddress,
          ROW_NUMBER() OVER (PARTITION BY ClientID ORDER BY AddressTypeID ASC) AS RowID
      FROM $DB4.dbo.cifAddress
      ) cfa ON cfa.ClientID = lle.ClientID AND cfa.RowID = 1
      INNER JOIN
      (
      SELECT ltr.TransactionDateTime,
          	ltr.AccountNumber,
          	ltr.TransactionNumber,
          	ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
      FROM $DB4.dbo.loanTransaction ltr
      WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 0
      AND ltr.TransactionType <> 'Transfer'
      ) ltr ON ltr.AccountNumber = lle.AccountNumber AND ltr.RowNum = 1
      WHERE lin.ReportDateTime = (?)
      AND lin.LoanStatusID <> 5
      AND lle.AccountStatus = 'Released'
      AND lin.PrincipalBalance > 0";
    $stmt = sqlsrv_query( $conn, $sql,$param );
    while( $row= sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) )
    {
     fputcsv($output, $row);
    }
    fclose($output);


  ?>
