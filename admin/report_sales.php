<?php 
$menu = "report_d";
include("header.php");

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// กำหนดช่วงวันที่/เดือน/ปี เหมือนกับหน้า report_top10_product.php
if (!empty($_POST['date_s']) AND !empty($_POST['date_e'])) {
  $date_s = $_POST['date_s'] . " 00:00:00";
  $date_e = $_POST['date_e'] . " 23:59:59";
  $dateWhere = " tbl_order.order_date BETWEEN '$date_s' AND '$date_e'";
}
elseif (!empty($_POST['month'])) {
  $month = $_POST['month'];
  $year = date('Y');
  $dateWhere = " tbl_order.order_date LIKE '".$year."-".$month."%'";
}
elseif (!empty($_POST['year'])) {
  $year = $_POST['year'];
  $dateWhere = " tbl_order.order_date LIKE '".$year."%'";
}
else{
  // ค่าเริ่มต้น: แสดงเฉพาะ "วันที่ปัจจุบัน"
  $today = date('Y-m-d');
  $dateWhere = " DATE(tbl_order.order_date) = '".$today."'";
}

$query_my_order = "SELECT DATE_FORMAT(order_date, '%d-%m-%Y') AS datesave, SUM(pay_amount) AS ptotal, 0 AS cast, 0 AS trnfer, 0 AS credit
FROM tbl_order WHERE $dateWhere
GROUP BY DATE_FORMAT(order_date, '%d-%m-%Y') DESC
ORDER BY DATE_FORMAT(order_date, '%Y-%m-%d') DESC" or die("Error : ".mysqli_error($condb));
$rs_my_order = mysqli_query($condb, $query_my_order);
$rs_my_order_Array = array();
while($fetch0 = mysqli_fetch_array($rs_my_order, MYSQLI_NUM)) {
  array_push($rs_my_order_Array, $fetch0);
}
$rs_my_order_count = count($rs_my_order_Array);
//echo ($query_my_order);//test query
//exit();

// Add Cash
$query_my_order1 = "SELECT DATE_FORMAT(order_date, '%d-%m-%Y') AS datesave, SUM(pay_amount) AS ptotal
FROM tbl_order WHERE $dateWhere AND order_status = 1
GROUP BY DATE_FORMAT(order_date, '%d-%m-%Y') DESC
ORDER BY DATE_FORMAT(order_date, '%Y-%m-%d') DESC" or die("Error : ".mysqli_error($condb));
$rs_my_order1 = mysqli_query($condb, $query_my_order1);
$rs_my_order1_Array = array();
while($fetch1 = mysqli_fetch_array($rs_my_order1, MYSQLI_NUM)) {
  array_push($rs_my_order1_Array, $fetch1);
}
// Add Cash
$rs_my_order1_count = count($rs_my_order1_Array);
for($row1 = 0; $row1 < $rs_my_order1_count; $row1++) {
  for($row2 = 0; $row2 < $rs_my_order_count; $row2++) {
    if($rs_my_order_Array[$row2][0] == $rs_my_order1_Array[$row1][0]) {
      $rs_my_order_Array[$row2][2] += $rs_my_order1_Array[$row1][1];
      break;
    }
  }
}

// Tranfer
$query_my_order2 = "SELECT DATE_FORMAT(order_date, '%d-%m-%Y') AS datesave, SUM(pay_amount) AS ptotal
FROM tbl_order WHERE $dateWhere AND order_status = 2
GROUP BY DATE_FORMAT(order_date, '%d-%m-%Y') DESC
ORDER BY DATE_FORMAT(order_date, '%Y-%m-%d') DESC" or die("Error : ".mysqli_error($condb));
$rs_my_order2 = mysqli_query($condb, $query_my_order2);
$rs_my_order2_Array = array();
while($fetch2 = mysqli_fetch_array($rs_my_order2, MYSQLI_NUM)) {
  array_push($rs_my_order2_Array, $fetch2);
}
// Add Tranfer
$rs_my_order2_count = count($rs_my_order2_Array);
for($row1 = 0; $row1 < $rs_my_order2_count; $row1++) {
  for($row2 = 0; $row2 < $rs_my_order_count; $row2++) {
    if($rs_my_order_Array[$row2][0] == $rs_my_order2_Array[$row1][0]) {
      $rs_my_order_Array[$row2][3] += $rs_my_order2_Array[$row1][1];
      break;
    }
  }
}

// Credit
$query_my_order3 = "SELECT DATE_FORMAT(order_date, '%d-%m-%Y') AS datesave, SUM(pay_amount) AS ptotal
FROM tbl_order WHERE $dateWhere AND order_status = 3
GROUP BY DATE_FORMAT(order_date, '%d-%m-%Y') DESC
ORDER BY DATE_FORMAT(order_date, '%Y-%m-%d') DESC" or die("Error : ".mysqli_error($condb));
$rs_my_order3 = mysqli_query($condb, $query_my_order3);
$rs_my_order3_Array = array();
while($fetch3 = mysqli_fetch_array($rs_my_order3, MYSQLI_NUM)) {
  array_push($rs_my_order3_Array, $fetch3);
}
// Add Credit
$rs_my_order3_count = count($rs_my_order3_Array);
for($row1 = 0; $row1 < $rs_my_order3_count; $row1++) {
  for($row2 = 0; $row2 < $rs_my_order_count; $row2++) {
    if($rs_my_order_Array[$row2][0] == $rs_my_order3_Array[$row1][0]) {
      $rs_my_order_Array[$row2][4] += $rs_my_order3_Array[$row1][1];
      break;
    }
  }
}

?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Dashboard</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">Report Day</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา</button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div id="container"></div>
              <br>
              <table class="table table-hover table-striped" id="datatable">
                <thead>
                  <tr>
                    <th width="15%">วัน/เดือน/ปี</th>
                    <th width="25%">จำนวนยอดขายทั้งหมด</th>
                    <th width="20%">เงินสด</th> 
                    <th width="20%">โอนเงิน</th> 
                    <th width="20%">สินเชื่อ</th>          
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $counts = count($rs_my_order_Array);
                    for($col=0; $col<$counts; $col++){ 
                  ?>
                  <tr>
                    <td align="left"><?php echo $rs_my_order_Array[$col][0]; ?></td>
                    <td align="left"><?php echo $rs_my_order_Array[$col][1]; ?></td>
                    <td align="left"><?php echo $rs_my_order_Array[$col][2]; ?></td>
                    <td align="left"><?php echo $rs_my_order_Array[$col][3]; ?></td>
                    <td align="left"><?php echo $rs_my_order_Array[$col][4]; ?></td>
                  </tr>
                  <?php } ?> 
                </tbody>
              </table>
            </div>   
          </div>   
        </div>
      </div>        
    </section>
    <!-- /.content -->

    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="report_sales.php" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header bg-gray">
              <h5 class="modal-title" id="exampleModalLabel">ค้นหาตามช่วงเวลา</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <h6>ค้นหาแบบช่วงวัน</h6>
              </div>  
              <div class="row mt-3">
                <div class="col-md-6">
                  <label for="date_s">วันที่เริ่มต้น</label>
                  <input type="date" class="form-control" id="date_s" name="date_s">
                </div>
                <div class="col-md-6">
                  <label for="date_e">วันที่สิ้นสุด</label>
                  <input type="date" class="form-control" id="date_e" name="date_e">
                </div>
              </div> 
              <div class="row mt-3">
                <h6>ค้นหาแบบเลือกเดือน</h6>
              </div>  
              <div class="row mt-3">
                <div class="col-md-6">
                  <select class="form-control" name="month" id="month">
                    <option value="">--เลือกเดือน--</option>
                    <option value="01">มกราคม</option>
                    <option value="02">กุมภาพันธ์</option>
                    <option value="03">มีนาคม</option>
                    <option value="04">เมษายน</option>
                    <option value="05">พฤษภาคม</option>
                    <option value="06">มิถุนายน</option>
                    <option value="07">กรกฎาคม</option>
                    <option value="08">สิงหาคม</option>
                    <option value="09">กันยายน</option>
                    <option value="10">ตุลาคม</option>
                    <option value="11">พฤศจิกายน</option>
                    <option value="12">ธันวาคม</option>
                  </select>
                </div>
              </div>
              <div class="row mt-3">
                <h6>ค้นหาแบบเลือกปี</h6>
              </div>  
              <div class="row mt-3">
                <div class="col-md-6">
                  <?php $now_year = date('Y'); ?>
                  <select class="form-control" name="year" id="year">
                    <option value="">--เลือกปี--</option>
                    <option value="<?php echo $now_year; ?>"><?php echo $now_year; ?></option>
                    <option value="<?php echo $now_year-1; ?>"><?php echo $now_year-1; ?></option>
                    <option value="<?php echo $now_year-2; ?>"><?php echo $now_year-2; ?></option>
                    <option value="<?php echo $now_year-3; ?>"><?php echo $now_year-3; ?></option>
                    <option value="<?php echo $now_year-4; ?>"><?php echo $now_year-4; ?></option>
                  </select>
                </div>
              </div>            
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
          </div>
        </form>
      </div>
    </div> 

<?php 
  mysqli_close($condb);
  include('footer.php'); 
?>

<script>
  Highcharts.chart('container', {
    data: {
      table: 'datatable'
    },
    chart: {
      type: 'column'
    },
    title: {
      text: 'รายงานยอดขาย'
    },
    xAxis: {
      type: 'category'
    },
    yAxis: {
      allowDecimals: false,
      title: {
        text: 'จำนวนเงิน (บาท)'
      }
    },
    tooltip: {
      formatter: function () {
        return '<b>' + this.series.name + '</b><br/>' + this.point.y + ' ' + this.point.name.toLowerCase();
      }
    }
  });
</script>
  
</body>
</html>
