<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Loan Report</title>
    <?php include('template/header.php');
?>
</style>
<style media="screen">
  p{
    margin-bottom: 1px;
    font-size: 14px;
    font-family: calibri;
    font-weight: bold;
  }
</style>
  </head>
  <body>
    <div class="container">
      <div class="" style="height:5px;"></div>
        <div class="">
          <div class="row">
              <div class="col-md-4">
                <label class="text-primary" for="" style="margin:0px;font-size:14px">Export to Excel Report</label>
              </div>
              <div class="col-md-8 text-right">
                <label for="" class=" text-danger" style="margin:0px;font-size:12px">Consolidated LIS is 1(ONE) day delay from the production server. </label>
              </div>
            </div>
            <hr style="margin-top:0px;margin-bottom:5px">
        </div>

        <?php
            require "connection.php";

            if(isset($_POST['btnSearch'])){
                $acc1 = $_POST['acc1'];
                $params = array($acc1,$acc1,$acc1,$acc1);
                  $sql="SELECT lle.AccountNumber,
                          	   lle.AccountName,
                          	   cad.CompleteAddress,
                          	   lle.LoanNo,
                          	   lle.OriginalPrincipal,
                          	   lst.SecurityType,
                          	   lra.InterestDesc,
                          	   lsc.ScheduleDescription,
                               lpa.ParameterDesc,
                          	   lpa.GracePeriodNumberOfDays,
                          	   FORMAT(lle.GrantedDateTime,'MM-dd-yyyy') GrantedDate,
                          	   FORMAT(lle.MaturityDateTime,'MM-dd-yyyy') MaturityDate,
                          	   lle.GrandTotalAmort,
                          	   lle.PrincipalBalance,
                          	   ls.StatusDescription
                          FROM $DB1.dbo.loanLedger lle
                          LEFT JOIN $DB1.dbo.cifAddress cad ON cad.ClientID = lle.ClientID AND cad.AddressTypeID = 1
                          LEFT JOIN $DB1.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
                          LEFT JOIN $DB1.dbo.loanSecurityType lst ON lst.SecurityTypeID = lle.SecurityTypeID
                          LEFT JOIN $DB1.dbo.loanStatus ls ON ls.StatusID = lle.LoanStatusID
                          LEFT JOIN $DB1.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
                          LEFT JOIN $DB1.dbo.loanParameter lpa ON lpa.ParameterID = lle.ParameterID
                          WHERE lle.AccountName+' - '+lle.loanNo = ?
                          UNION
                          SELECT lle.AccountNumber,
                            	   lle.AccountName,
                            	   cad.CompleteAddress,
                            	   lle.LoanNo,
                            	   lle.OriginalPrincipal,
                            	   lst.SecurityType,
                            	   lra.InterestDesc,
                            	   lsc.ScheduleDescription,
                                 lpa.ParameterDesc,
                            	   lpa.GracePeriodNumberOfDays,
                            	   FORMAT(lle.GrantedDateTime,'MM-dd-yyyy') GrantedDate,
                            	   FORMAT(lle.MaturityDateTime,'MM-dd-yyyy') MaturityDate,
                            	   lle.GrandTotalAmort,
                            	   lle.PrincipalBalance,
                            	   ls.StatusDescription
                            FROM $DB2.dbo.loanLedger lle
                            LEFT JOIN $DB2.dbo.cifAddress cad ON cad.ClientID = lle.ClientID AND cad.AddressTypeID = 1
                            LEFT JOIN $DB2.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
                            LEFT JOIN $DB2.dbo.loanSecurityType lst ON lst.SecurityTypeID = lle.SecurityTypeID
                            LEFT JOIN $DB2.dbo.loanStatus ls ON ls.StatusID = lle.LoanStatusID
                            LEFT JOIN $DB2.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
                            LEFT JOIN $DB2.dbo.loanParameter lpa ON lpa.ParameterID = lle.ParameterID
                            WHERE lle.AccountName+' - '+lle.loanNo = ?
                            UNION
                            SELECT lle.AccountNumber,
                              	   lle.AccountName,
                              	   cad.CompleteAddress,
                              	   lle.LoanNo,
                              	   lle.OriginalPrincipal,
                              	   lst.SecurityType,
                              	   lra.InterestDesc,
                              	   lsc.ScheduleDescription,
                                   lpa.ParameterDesc,
                              	   lpa.GracePeriodNumberOfDays,
                              	   FORMAT(lle.GrantedDateTime,'MM-dd-yyyy') GrantedDate,
                              	   FORMAT(lle.MaturityDateTime,'MM-dd-yyyy') MaturityDate,
                              	   lle.GrandTotalAmort,
                              	   lle.PrincipalBalance,
                              	   ls.StatusDescription
                              FROM $DB3.dbo.loanLedger lle
                              LEFT JOIN $DB3.dbo.cifAddress cad ON cad.ClientID = lle.ClientID AND cad.AddressTypeID = 1
                              LEFT JOIN $DB3.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
                              LEFT JOIN $DB3.dbo.loanSecurityType lst ON lst.SecurityTypeID = lle.SecurityTypeID
                              LEFT JOIN $DB3.dbo.loanStatus ls ON ls.StatusID = lle.LoanStatusID
                              LEFT JOIN $DB3.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
                              LEFT JOIN $DB3.dbo.loanParameter lpa ON lpa.ParameterID = lle.ParameterID
                              WHERE lle.AccountName+' - '+lle.loanNo = ?
                              UNION
                              SELECT lle.AccountNumber,
                                	   lle.AccountName,
                                	   cad.CompleteAddress,
                                	   lle.LoanNo,
                                	   lle.OriginalPrincipal,
                                	   lst.SecurityType,
                                	   lra.InterestDesc,
                                	   lsc.ScheduleDescription,
                                     lpa.ParameterDesc,
                                	   lpa.GracePeriodNumberOfDays,
                                	   FORMAT(lle.GrantedDateTime,'MM-dd-yyyy') GrantedDate,
                                	   FORMAT(lle.MaturityDateTime,'MM-dd-yyyy') MaturityDate,
                                	   lle.GrandTotalAmort,
                                	   lle.PrincipalBalance,
                                	   ls.StatusDescription
                                FROM $DB4.dbo.loanLedger lle
                                LEFT JOIN $DB4.dbo.cifAddress cad ON cad.ClientID = lle.ClientID AND cad.AddressTypeID = 1
                                LEFT JOIN $DB4.dbo.loanRate lra ON lra.InterestRateID = lle.InterestRateID
                                LEFT JOIN $DB4.dbo.loanSecurityType lst ON lst.SecurityTypeID = lle.SecurityTypeID
                                LEFT JOIN $DB4.dbo.loanStatus ls ON ls.StatusID = lle.LoanStatusID
                                LEFT JOIN $DB4.dbo.loanSchedule lsc ON lsc.ScheduleID = lle.AmortizationScheduleID
                                LEFT JOIN $DB4.dbo.loanParameter lpa ON lpa.ParameterID = lle.ParameterID
                                WHERE lle.AccountName+' - '+lle.loanNo = ?";
                  $stmt = sqlsrv_query( $conn, $sql, $params);
                  if( $stmt === false ) {
                       die( print_r( sqlsrv_errors(), true));
                  }
                  $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
                  if ( $row[0] == NULL) {
                    echo '<script>alert("Incorrect AccountNumber")</script>';
                    echo "<meta http-equiv='refresh' content='0'>";
                  }

                }
         ?>
         <div class="container">
         </div>
         <div class="row">
           <div class="col">
              <?php include('accountnumberhistory.php'); ?>
          </div>
          </div>
          <div class="" style="height:20px"></div>
         <div class="row">
            <div class="col-md-5">
              <div class="row">
                <div class="col-4 text-right"><p class="text-sm" for="">Account Number:</p></div>
                <div class="col"><input readonly id="accountnumber"  type="text" class="form-control form-control-sm input-sm" required name="accnum" value="<?php if (isset($_POST['btnSearch'])) echo $row['0'] ?>"></div>
              </div>
              <div class="row">
                <div class="col-4 text-right"><p class="text-sm" for="">Account Name:</p></div>
                <div class="col"><input readonly  type="text" class="form-control form-control-sm input-sm" name="accname" required value="<?php if (isset($_POST['btnSearch'])) echo $row['1'] ?>"></div>
              </div>
              <div class="row">
                <div class="col-4 text-right"><p class="text-sm">Loan No:</p></div>
                <div class="col"><input readonly type="text" class="form-control form-control-sm input-sm" name="loannum" required value="<?php if (isset($_POST['btnSearch'])) echo $row['3'] ?>"></div>
              </div>
            </div>
            <div class="col-md-5">
              <form class="" action="exportloanamortization.php" method="post">
              <div class="row ">
                <input type="hidden" name="accnum" required value="<?php echo $row['0'];  ?>">
                <input type="hidden" name="accname" value="<?php echo $row['1'] ; ?>">
                <input type="hidden" name="loannum" value="<?php echo $row['3'];  ?>">
                <div class="col"><input style="margin-top:10px"  type="Submit" class="form-control form-control-sm input-sm btn-info" name="setupdate" value="Loan Amortization" ></div>
                <div class="col-6 text-left"><label class="text-sm"></label></div>
              </div>
              </form>
              <form class="" action="exportloantransaction.php" method="post">
              <div class="row ">
                <input type="hidden" name="accnum" value="<?php echo $row['0'];  ?>">
                <input type="hidden" name="accname" value="<?php echo $row['1'] ; ?>">
                <input type="hidden" name="loannum" value="<?php echo $row['3'];  ?>">
                <div class="col"><input style="margin-top:10px"   type="submit" id="setupdate" class="form-control form-control-sm input-sm btn-success" name="setupdate" value="Loan Transaction" ></div>
                <div class="col-6 text-right"><label class="text-sm"></label></div>
              </div>
            </form>
            </div>
         </div>
        <hr style="margin:1">

        <hr style="margin:1">
        <div class="row">
          <div class="col">
              <div class="">
                <form action="exportactiveaccount.php" method="post">
                <p >R001.Active account </p>
                <p style="margin-bottom:1px;font-size:12px">Setup date:</p>
                <input class="form-control input-sm" type="date" name="dateactiveaccount" value="<?php echo $today ?>" style="margin-bottom:5px">
                <div class="text-right">
                  <input type="Submit" class="btn btn-primary input-sm" name="btnactiveaccount" value="Export">
                </div>
                <hr >
              </form>
              </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportcollection.php" method="post">
              <p >R002.Loan Collection </p>
              <p style="margin-bottom:1px;font-size:12px">Setup date:</p>
              <input class="form-control input-sm" type="date" name="datecollection" value="<?php echo $today ?>" min="<?php echo date('Y-m-d'); ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="" value="Export">
              </div>
              </form>
              <hr >
            </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportpaidloan.php" method="post">
              <p >R003.Paid Loan </p>
              <p style="margin-bottom:1px;font-size:12px">Setup date:</p>
              <input class="form-control input-sm text-Dark" type="date" name="datepaidloans" value="<?php echo $today ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnpaidloans" value="Export">
              </div>
            </form>
              <hr >
            </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportloandelay.php" method="post">
              <p >R004.Loan Delay </p>
              <p style="margin-bottom:1px;font-size:12px">Date:</p>
              <input class="form-control input-sm" readonly type="date" name="dateloandelay" value="<?php echo $today ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnloandelay" value="Export">
              </div>
              <hr >
            </form>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="">
              <form action="exportLoanPortfolio.php" method="post">
              <p >R005.Loan Portfolio  </p>
              <p style="margin-bottom:1px;font-size:12px">MonthEnd Date:</p>
              <input required class="form-control input-sm" type="month" name="dateLoanPortfolio" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnLoanportfolio" value="Export">
              </div>
              <hr >
            </form>
            </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportAccountGranted.php" method="post">
              <p >R006.Account Granted </p>
              <p style="margin-bottom:1px;font-size:12px">FROM:</p>
              <input class="form-control input-sm" type="date" name="AccountGrantedFrom" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <p style="margin-bottom:1px;font-size:12px">To:</p>
              <input class="form-control input-sm" type="date" name="AccountGrantedTo" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnAccountGranted" value="Export">
              </div>
              <hr >
            </form>
            </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportUnscannedLoanAccount.php" method="post">
              <p >R007.Unscanned Loan Account </p>
              <p style="margin-bottom:1px;font-size:12px">FROM:</p>
              <input class="form-control input-sm" type="date" name="ulaFrom" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <p style="margin-bottom:1px;font-size:12px">To:</p>
              <input class="form-control input-sm" type="date" name="ulaTo" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnULA" value="Export">
              </div>
              <hr >
            </form>
            </div>
          </div>
          <div class="col">
            <div class="">
              <form action="exportTopBorrower.php" method="post">
              <p >R008.Top Borrowers</p>
              <div class="row">
                <div class="col-3">
                  <p style="margin-bottom:1px;font-size:12px">Top#:</p>
                  <input class="form-control input-sm" required type="number" name="TopNumber" value="<?php echo $today; ?>" style="margin-bottom:5px;">
                </div>
                <div class="col-5">
                  <p style="font-size:12px"><input type="radio" required name="sort" value="orig" style="margin-bottom:0px;margin-top:10%;height:12px;"> Loan Amount</p>
                  <p style="font-size:12px"><input type="radio" required name="sort" value="Prin" style="margin-bottom:0px;height:12px;"> Prin. Balance</p>
                </div>
                <div class="col">
                  <p style="font-size:12px"><input type="radio" required name="Top" value="Bankwide" style="margin-bottom:0px;margin-top:10%;height:12px;"> Bankwide</p>
                  <p style="font-size:12px"><input type="radio" required name="Top" value="Branch" style="margin-bottom:0px;height:12px;"> Branch</p>
                </div>
              </div>
              <p style="margin-bottom:1px;font-size:12px">To:</p>
              <input class="form-control input-sm" type="date" name="TopBorrowerDate" value="<?php echo $today; ?>" style="margin-bottom:5px">
              <div class="text-right">
                <input type="Submit" class="btn btn-primary input-sm" name="btnTB" value="Export">
              </div>
              <hr >
            </form>
            </div>
          </div>
        </div>
   </div>


  </body>
  <?php

  include('template/footer.php')
    ?>
</html>
