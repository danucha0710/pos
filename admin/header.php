<?php
error_reporting(error_reporting() & ~E_NOTICE);
session_start();
//print_r($_SESSION);
if (isset($_SESSION['ref_l_id'])) {
  $m_level = $_SESSION['ref_l_id'];
  if ($m_level >= 3) {
    Header("Location: user/");
    exit();
  }
} else {
  Header("Location: ../index.php");
  exit();
}

include('../condb.php');
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BPCC POS System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables/css/dataTables.bootstrap5.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Kanit:400" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/plugins/bootstrap-5/bootstrap.min.css"> 
  <!-- Highcharts.com -->
  <script src="../assets/plugins/chart/highcharts.js"></script>
  <script src="../assets/plugins/chart/data.js"></script>
  <!-- <script src="../assets/plugins/chart/exporting.js"></script> -->
  <script src="../assets/plugins/chart/accessibility.js"></script>

  <style>
  @media print {
    .no-print { display: none; }
    body { font-size: 12pt; }
  }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
  <div class="wrapper">
    <?php include ("navbar.php"); ?>
    <?php include ("sidebar.php"); ?>
    <div class="content-wrapper">