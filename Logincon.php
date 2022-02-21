<?php
session_start();
require "connection.php";

$ui = $_POST['ui'];
$pass = $_POST['pass'];

$sql1 = "SELECT Password FROM $DB.dbo.secUser WHERE UserId=$ui AND Status = 'Active'";
$stmt = sqlsrv_query( $conn, $sql1);
if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
$row1 = sqlsrv_fetch_array($stmt);
if($row1 === null){
      ?>
        <script> alert('INCORRECT USER ID')</script>

        <?php
        header('location:login.php');
} else{
	$aas = $row1[0];
	$sql = "EXEC $DB.dbo.gsp_Decrypt2 '$aas','A'";
	$stmt1 = sqlsrv_query( $conn, $sql);
		if( $stmt1 === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
        $row = sqlsrv_fetch_array($stmt1);
          if($row === null){
         header('location:index.php');
	}else{
		$stripped1 = preg_replace('/\s+/', ' ', $pass);
		$c = ' '+$row[0];
		if($c == $stripped1){
      $_SESSION['uid'] = $ui;
			header('location:Dashboard.php');
		}else{
			header('location:index.php');
		}
		}

        }

sqlsrv_free_stmt( $stmt1);
sqlsrv_free_stmt( $stmt);

 ?>
