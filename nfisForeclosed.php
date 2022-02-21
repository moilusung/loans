<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>NFIS Monitoring</title>
    <script src="resources/jquery/3.5.1/jquery.min.js"></script>
  </headr >

  <?php
  include('template/header.php');

  $timezone = "Asia/Colombo";
  date_default_timezone_set($timezone);
  $today = date("Y-m-d");


  function input($name, $value, $type=null) {
    return '<input type="' . (is_null($type) ? 'text' : $type) . '" name="' . $name . '" value="' . $value . '">';
  }

  require "connection.php";


  ?>

  <body>
    <div class="container ">
      <div class="" style="height:10px;"></div>
      <div class="text-center">
        <div class="row">
            <div class="col-md-4 text-left">
              <label class="text-primary" for="" style="margin:0px;font-size:14px">NFIS Monitoring</label>
            </div>
            <div class="col-md-8 text-right">
              <label for="" class=" text-danger" style="margin:0px;font-size:12px"> Consolidated LIS is 1(ONE) day delay from the production server. </label>
            </div>
          </div>

      <hr style="margin-top:0px;margin-bottom:5px">
      </div>
      <!--navbar-->
      <nav class="navbar navbar-expand-md navbar-light bg-light">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item ">
            <a class="nav-link" href="nfispastdue.php">PastDue</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="nfiswriteoff.php">Write Off</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="nfislitigation.php">Items In Litigation</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link text-dark" href="nfisForeclosed.php"><b>Foreclosed <span class="sr-only">(current)</span></b></a>
          </li>
        </ul>
      </div>
    </nav>
    <!--navbar-->
    <div class="row">
    <div class="col-7">
      <label for="" class="text-left text-danger" style="font-size:12px"> All accounts refected herein were previously reported to NFIS and are now either updated or settled.
    </div>
    <div class="col-5 text-right">
      <font size="1px">FILE:&nbsp<a href="file/updates.xls" target="_blank">UPDATE</a>|<a href="file/amend.xls" target="_blank">AMEND</a>|<a href="file/deletion.xls" target="_blank">DELETION</a></font>
    </div>
  </div>
<form>
      <table class="t-table table-hover table-bordered table-sm" style="table-layout: fixed;font-weight: bold;"  >
      <thead>
        <tr style="line-height: 8px" class=" text-dark text-center">
          <td class="table-success text-dark" colspan="7"><font size="1px"><b>NFIS DIRECTIVES</b></font></td>
          <td class="table-primary text-dark" colspan="5"><font size="1px"><b>Cyber Production Status</b></font></td>
        </tr>
      <tr style="line-height: 8px" class=" text-dark text-center">
        <td class="table-success text-dark" width="5%"  class=""><font size="1px">ClientID</font></td>
        <td class="table-success text-dark" width="10%" class=""><font size="1px">LoanReferenceNo</font></td>
        <td class="table-success text-dark" width="30%" class=""><font size="1px">Name</font></td>
        <td class="table-success text-dark" width="6%" class=""><font size="1px">Birthhdate</font></td>
        <td class="table-success text-dark" width="8%"  class=""><font size="1px">OSBalance</font></td>
        <td class="table-success text-dark" width="7%"  class=""><font size="1px">MaturityDate</font></td>
        <td class="table-success text-dark" width="7%"  class=""><font size="1px">Created_date</font></td>
        <td class="table-primary text-dark" width="6%" class=""><font size="1px">Balance</font></td>
        <td class="table-primary text-dark" width="6%" class=""><font size="1px">LastTransDate</font></td>
        <td class="table-primary text-dark" width="12%" class=""><font size="1px">StatusDescription</font></td>
        <td class="table-primary text-dark" width="6%" class=""><font size="1px">Remarks</font></td>
        <td class="table-primary text-dark" width="8%" class=""><font size="1px">Comment</font></td>
      </tr>
    </thead>
    <tbody>
      <?php

          $param = array($today,$today,$today,$today);
          $sql="SELECT nfis.ReportingBranch,
                        nfis.ClientID,
                        nfis.LoanReferenceNo,
                        nfis.Name,
                        FORMAT(nfis.Birthhdate,'MM-dd-yyyy') Birthhdate,
                        nfis.BKValue,
                        FORMAT(nfis.ForeclosedDate,'MM-dd-yyyy') PastDueDate,
                        lle.PrincipalBalance,
                        lle.InterestBalance,
                        lle.LoanStatusID,
                        lst.StatusDescription,
                        nfis.Action,
                        FORMAT(nfis.created_datetime,'MM-dd-yyyy') created_date,
                        FORMAT(ltr.TransactionDateTime,'MM-dd-yyyy') TransactionDateTime
                    FROM nfisForeclosedindiv nfis
                    INNER JOIN $DB1.dbo.loanLedger lle ON lle.AccountNumber = nfis.LoanReferenceNo
                    INNER JOIN $DB1.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
                    INNER JOIN
                    (
                    SELECT ltr.TransactionDateTime,
                        ltr.AccountNumber,
                        ltr.TransactionNumber,
                        ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                    FROM $DB1.dbo.loanTransaction ltr
                    WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 1
                    AND ltr.TransactionType <> 'Transfer'
                    ) ltr ON ltr.AccountNumber = nfis.LoanReferenceNo AND ltr.RowNum = 1
                    WHERE lle.PrincipalBalance = 0
                    OR lle.LoanStatusID = 1
                    UNION
                    SELECT nfis.ReportingBranch,
                        nfis.ClientID,
                        nfis.LoanReferenceNo,
                        nfis.Name,
                        FORMAT(nfis.Birthhdate,'MM-dd-yyyy') Birthhdate,
                        nfis.BKValue,
                        FORMAT(nfis.ForeclosedDate,'MM-dd-yyyy') PastDueDate,
                        lle.PrincipalBalance,
                        lle.InterestBalance,
                        lle.LoanStatusID,
                        lst.StatusDescription,
                        nfis.Action,
                        FORMAT(nfis.created_datetime,'MM-dd-yyyy') created_date,
                        FORMAT(ltr.TransactionDateTime,'MM-dd-yyyy') TransactionDateTime
                    FROM nfisForeclosedindiv nfis
                    INNER JOIN $DB2.dbo.loanLedger lle ON lle.AccountNumber = nfis.LoanReferenceNo
                    INNER JOIN $DB2.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
                    INNER JOIN
                    (
                    SELECT ltr.TransactionDateTime,
                        ltr.AccountNumber,
                        ltr.TransactionNumber,
                        ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                    FROM $DB2.dbo.loanTransaction ltr
                    WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 1
                    AND ltr.TransactionType <> 'Transfer'
                    ) ltr ON ltr.AccountNumber = nfis.LoanReferenceNo AND ltr.RowNum = 1
                    WHERE lle.PrincipalBalance = 0
                    OR lle.LoanStatusID = 1
                    UNION
                    SELECT nfis.ReportingBranch,
                        nfis.ClientID,
                        nfis.LoanReferenceNo,
                        nfis.Name,
                        FORMAT(nfis.Birthhdate,'MM-dd-yyyy') Birthhdate,
                        nfis.BKValue,
                        FORMAT(nfis.ForeclosedDate,'MM-dd-yyyy') PastDueDate,
                        lle.PrincipalBalance,
                        lle.InterestBalance,
                        lle.LoanStatusID,
                        lst.StatusDescription,
                        nfis.Action,
                        FORMAT(nfis.created_datetime,'MM-dd-yyyy') created_date,
                        FORMAT(ltr.TransactionDateTime,'MM-dd-yyyy') TransactionDateTime
                    FROM nfisForeclosedindiv nfis
                    INNER JOIN $DB3.dbo.loanLedger lle ON lle.AccountNumber = nfis.LoanReferenceNo
                    INNER JOIN $DB3.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
                    INNER JOIN
                    (
                    SELECT ltr.TransactionDateTime,
                        ltr.AccountNumber,
                        ltr.TransactionNumber,
                        ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                    FROM $DB3.dbo.loanTransaction ltr
                    WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 1
                    AND ltr.TransactionType <> 'Transfer'
                    ) ltr ON ltr.AccountNumber = nfis.LoanReferenceNo AND ltr.RowNum = 1
                    WHERE lle.PrincipalBalance = 0
                    OR lle.LoanStatusID = 1
                    UNION
                    SELECT nfis.ReportingBranch,
                        nfis.ClientID,
                        nfis.LoanReferenceNo,
                        nfis.Name,
                        FORMAT(nfis.Birthhdate,'MM-dd-yyyy') Birthhdate,
                        nfis.BKValue,
                        FORMAT(nfis.ForeclosedDate,'MM-dd-yyyy') PastDueDate,
                        lle.PrincipalBalance,
                        lle.InterestBalance,
                        lle.LoanStatusID,
                        lst.StatusDescription,
                        nfis.Action,
                        FORMAT(nfis.created_datetime,'MM-dd-yyyy') created_date,
                        FORMAT(ltr.TransactionDateTime,'MM-dd-yyyy') TransactionDateTime
                    FROM nfisForeclosedindiv nfis
                    INNER JOIN $DB4.dbo.loanLedger lle ON lle.AccountNumber = nfis.LoanReferenceNo
                    INNER JOIN $DB4.dbo.loanStatus lst ON lst.StatusID = lle.LoanStatusID
                    INNER JOIN
                    (
                    SELECT ltr.TransactionDateTime,
                        ltr.AccountNumber,
                        ltr.TransactionNumber,
                        ROW_NUMBER() OVER (PARTITION BY ltr.AccountNumber ORDER BY ltr.TransactionNumber DESC ) AS RowNum
                    FROM $DB4.dbo.loanTransaction ltr
                    WHERE DATEDIFF(day,ltr.TransactionDateTime,(?)) >= 1
                    AND ltr.TransactionType <> 'Transfer'
                    ) ltr ON ltr.AccountNumber = nfis.LoanReferenceNo AND ltr.RowNum = 1
                    WHERE lle.PrincipalBalance = 0
                    OR lle.LoanStatusID = 1";
        if (($stmt = sqlsrv_query($conn, $sql,$param)) === false) {
            die(print_r(sqlsrv_errors()));
        } else {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if (isset($row['ClientID']) != NULL){
            echo '<tr  style="line-height: 8px" class="text-dark">';
            echo '<td><font size="1px">'. $row['ClientID'] .'</font></td>';
            echo '<td><font size="1px">'. $row['LoanReferenceNo'] .'</font></td>';
            echo '<td><font size="1px">'. htmlentities($row['Name']) .'</font></td>';
            echo '<td><font size="1px">'. $row['Birthhdate'] .'</font></td>';
            echo '<td><font size="1px">'. number_format($row['OSBalance'],2) .'</font></td>';
            echo '<td><font size="1px">'. $row['MaturityDate'] .'</font></td>';
            echo '<td><font size="1px">'. $row['created_date'] .'</font></td>';
            echo '<td class=""><font size="1px">'. number_format($row['PrincipalBalance']+$row['InterestBalance'],2) .'</font></td>';
            echo '<td class=""><font size="1px">'. $row['TransactionDateTime'] .'</font></td>';
            echo '<td class=""><font size="1px">'. $row['StatusDescription'] .'</font></td>';
            if ($row['PrincipalBalance'] == 0) {
                echo '<td class="text-danger"><font size="1px">CLOSED</font></td>';
                }elseif ($row['LoanStatusID'] == 1) {
                echo '<td class="text-danger"><font size="1px">NO DELAYS</font></td>';
                }else {
                echo '<td class="text-dark"><font size="1px">No Action</font></td>';
                };
            echo '<td class="text-center" style="font-size:10px">';
          if ($row['Action'] != NULL) {
            echo '<select class="" disabled="true">';
            echo '<option value="NULL"' . ($row['Action'] == NULL ? ' selected>' : '>') . 'No Action</option>';
            echo '<option value="Update"' . ($row['Action'] == 'Update' ? ' selected>' : '>') . 'Update</option>';
            echo '<option value="Amend"' . ($row['Action'] == 'Amend' ? ' selected>' : '>') . 'Amend</option>';
            echo '<option value="Deletion"' . ($row['Action'] == 'Deletion' ? ' selected>' : '>') . 'Deletion</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';
          } else {
            echo '<select class="">';
            echo '<option value="NULL"' . ($row['Action'] == NULL ? ' selected>' : '>') . 'No Action</option>';
            echo '<option value="Update"' . ($row['Action'] == 'Update' ? ' selected>' : '>') . 'Update</option>';
            echo '<option value="Amend"' . ($row['Action'] == 'Amend' ? ' selected>' : '>') . 'Amend</option>';
            echo '<option value="Deletion"' . ($row['Action'] == 'Deletion' ? ' selected>' : '>') . 'Deletion</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';
          }
            // echo '<select class="">';

                  }
            }
          }
            ?>
  </tbody>
  </table>
  </form>


    </div>

  </body>

  <script>
  $(document).ready(function(){
      $("select").on("change", function(){
        var statusID = $(this).val();
        var LoanReferenceNo = $(this).parents("tr").find("td:eq(1)").text();
        var r = confirm("Reminder: Please dont forget to do "+ statusID +" in this account to NFIS. Press the button to confirm!");
        if (r == true) {
          $.post("nfispastdueupdate.php", {account: LoanReferenceNo, status: statusID}, function(response){
              alert(response);
          })
      }
      });
  });
  </script>


  <?php

    include('template/footer.php')
    ?>
</html>
