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
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Kanit:400" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/plugins/bootstrap-5/bootstrap.min.css"> 

  <style>
    body {
      font-family: 'Kanit', sans-serif;
      font-size: 14px;
    }
  </style>

  <style type="text/css">
  @media print{
    .btn{
       display: none; 
    }
  }
</style>
</head>

<?php
error_reporting(error_reporting() & ~E_NOTICE);
session_start(); 
//print_r($_SESSION);
$m_level = $_SESSION['ref_l_id'];
if($m_level != 1 AND $m_level != 2){
  Header("Location: ../index.php");
}
include('../condb.php');
$barCode =  $_GET['barCode'];
$query_type = "SELECT * FROM tbl_type " or die("Error : ".mysqli_error($condb));
$rs_type = mysqli_query($condb, $query_type);
$query_brand = "SELECT * FROM tbl_brand " or die("Error : ".mysqli_error($condb));
$rs_brand = mysqli_query($condb, $query_brand);
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed text-sm">
  <form action="add_product_db.php" method="POST" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row">
          <label for="p_barCode" class="col-sm-3 col-form-label">รหัสบาร์โค้ด</label>
          <div class="col-sm-9">
            <input name="p_barCode" type="text" class="form-control" value="<?php echo $barCode; ?>" readonly>
          </div>
        </div>
        <div class="row mt-1">
          <label for="t_id" class="col-sm-3 col-form-label">ประเภทสินค้า</label>
          <div class="col-sm-9">
            <select name="t_id" class="form-control" required>
              <option value="">--เลือกประเภทสินค้า--</option>
              <?php foreach ($rs_type as $rst) { ?>
              <option value="<?php echo $rst['t_id'];?>"><?php echo $rst['t_name'];?></option>
              <?php } ?> 
            </select>   
          </div>
        </div>
        <div class="row mt-1">
          <label for="b_id" class="col-sm-3 col-form-label">แบรนด์สินค้า</label>
          <div class="col-sm-9">
            <select name="b_id" class="form-control" required>
              <option value="">--เลือกแบรนด์สินค้า--</option>
              <?php foreach ($rs_brand as $rsb) { ?> 
              <option value="<?php echo $rsb['b_id'];?>"><?php echo $rsb['b_name']; ?></option>
              <?php } ?> 
            </select>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_name" class="col-sm-3 col-form-label">ชื่อสินค้า</label>
          <div class="col-sm-9">
            <input name="p_name" type="text" class="form-control" required>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_detail" class="col-sm-3 col-form-label">รายละเอียด</label>
          <div class="col-sm-9">
            <textarea name="p_detail" class="form-control" row mt-1="3"></textarea>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_cost" class="col-sm-3 col-form-label">ราคาต้นทุน</label>
          <div class="col-sm-9">
            <input name="p_cost" type="number" min="0" step="0.01" class="form-control" required>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_price" class="col-sm-3 col-form-label">ราคาขาย</label>
          <div class="col-sm-9">
            <input name="p_price" type="number" min="0" step="0.01" class="form-control" required>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_qty" class="col-sm-3 col-form-label">จำนวน</label>
          <div class="col-sm-9">
            <input name="p_qty" type="number" min="0" step="1" class="form-control" required>
          </div>
        </div>
        <div class="row mt-1">
          <label for="p_img" class="col-sm-3 col-form-label">รูปสินค้า</label>
          <div class="col-sm-9">  
            <input class="form-control" type="file" name="p_img" id="p_img">
          </div>
        </div>     
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
      </div>
    </div>
  </form>
</body>
</html>