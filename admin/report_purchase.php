<?php 
$menu = "report_purchase";
include("header.php");
?>

<?php 
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//exit();

if (!empty($_POST['date_s']) AND !empty($_POST['date_e'])) {
  $date_s = $_POST['date_s'];
  $date_e = $_POST['date_e'];
  $dateText = " AND tbl_order.order_date BETWEEN '$date_s' AND '$date_e'";
} 
elseif (!empty($_POST['month'])) {
  $month = $_POST['month'];
  $year = date('Y');
  $dateText = " AND tbl_order.order_date LIKE '".$year."-".$month."%'";
} 
elseif (!empty($_POST['year'])) {
  $year = $_POST['year'];
  $dateText = " AND tbl_order.order_date LIKE '".$year."%'";
}
else {
  // ไม่ได้เลือกช่วงวัน/เดือน/ปี ให้แสดงยอดซื้อเฉพาะ "ปีปัจจุบัน"
  $year = date('Y');
  $dateText = " AND tbl_order.order_date LIKE '".$year."%'";
}

$query0 = "SELECT tbl_member.mem_name, 0 AS cast, 0 AS trnfer, 0 AS credit, 0 AS sum FROM `tbl_member`
GROUP BY tbl_member.mem_name" or die("Error : ".mysqli_error($condb));
$result0 = mysqli_query($condb, $query0);
$result0Array = array();
while($fetch0 = mysqli_fetch_array($result0, MYSQLI_NUM)) {
  array_push($result0Array, $fetch0);
}

// เตรียมข้อมูลระดับสมาชิกของแต่ละชื่อสมาชิก
$levelQuery = "SELECT mem_name, ref_l_id FROM tbl_member";
$levelResult = mysqli_query($condb, $levelQuery);
$memberLevels = array();
if ($levelResult) {
  while($levelRow = mysqli_fetch_assoc($levelResult)) {
    $memberLevels[$levelRow['mem_name']] = $levelRow['ref_l_id'];
  }
}

$query1 = "SELECT tbl_member.mem_name, SUM(tbl_order.pay_amount) AS cash FROM `tbl_order`
INNER JOIN tbl_member ON (tbl_member.mem_phone = tbl_order.mem_phone) 
WHERE tbl_order.order_status = 1 $dateText
GROUP BY tbl_member.mem_name" or die("Error : ".mysqli_error($condb));
$result1 = mysqli_query($condb, $query1);
$result1Array = array();
while($fetch1 = mysqli_fetch_array($result1, MYSQLI_NUM)) {
  array_push($result1Array, $fetch1);
}

$query2 = "SELECT tbl_member.mem_name, SUM(tbl_order.pay_amount) AS tranfer FROM `tbl_order`
INNER JOIN tbl_member ON (tbl_member.mem_phone = tbl_order.mem_phone) 
WHERE tbl_order.order_status = 2 $dateText
GROUP BY tbl_member.mem_name" or die("Error : ".mysqli_error($condb));
$result2 = mysqli_query($condb, $query2);
$result2Array = array();
while($fetch2 = mysqli_fetch_array($result2, MYSQLI_NUM)) {
  array_push($result2Array, $fetch2);
}

$query3 = "SELECT tbl_member.mem_name, SUM(tbl_order.pay_amount) AS credit FROM `tbl_order`
INNER JOIN tbl_member ON (tbl_member.mem_phone = tbl_order.mem_phone) 
WHERE tbl_order.order_status = 3 $dateText
GROUP BY tbl_member.mem_name" or die("Error : ".mysqli_error($condb));
$result3 = mysqli_query($condb, $query3);
$result3Array = array();
while($fetch3 = mysqli_fetch_array($result3, MYSQLI_NUM)) {
  array_push($result3Array, $fetch3);
}

$resultArray = $result0Array;
$row_count = count($resultArray);

// Add Cash
$row1_count = count($result1Array);
for($row1 = 0; $row1 < $row1_count; $row1++) {
  for($row2 = 0; $row2 < $row_count; $row2++) {
    if($resultArray[$row2][0] == $result1Array[$row1][0]) {
      $resultArray[$row2][1] += $result1Array[$row1][1];
      break;
    }
  }
}

// Add Tranfer
$row2_count = count($result2Array);
for($row1 = 0; $row1 < $row2_count; $row1++) {
  for($row2 = 0; $row2 < $row_count; $row2++) {
    if($resultArray[$row2][0] == $result2Array[$row1][0]) {
      $resultArray[$row2][2] += $result2Array[$row1][1];
      break;
    }
  }
}

// Add Credit
$row3_count = count($result3Array);
for($row1 = 0; $row1 < $row3_count; $row1++) {
  for($row2 = 0; $row2 < $row_count; $row2++) {
    if($resultArray[$row2][0] == $result3Array[$row1][0]) {
      $resultArray[$row2][3] += $result3Array[$row1][1];
      break;
    }
  }
}

// SUM Array
$row_count = count($resultArray);
for($row1 = 0; $row1 < $row_count; $row1++) {
  $resultArray[$row1][4] = $resultArray[$row1][1]+$resultArray[$row1][2]+$resultArray[$row1][3];
}

// Print Array
/*
for($row1 = 0; $row1 < $row_count; $row1++) {
  for($row2 = 0; $row2 < 5; $row2++) {
    echo $resultArray[$row1][$row2]." ";
  }
  echo "<br>";
}
*/
//exit();
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
          <h3 class="card-title">รายการยอดซื้อรายบุคคล</h3>
          <div align="right">
            <button 
              type="button" 
              class="btn btn-light" 
              style="margin-right:6px;" 
              onclick="window.print();"
            >
              <i class="fa fa-print"></i> พิมพ์
            </button>
            <button 
              type="button" 
              class="btn btn-light" 
              data-bs-toggle="modal" 
              data-bs-target="#exampleModal"
            >
              <i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา
            </button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-hover table-striped" id="tableSearch">
                <thead>
                  <tr>
                    <th width="5%">ลำดับ</th>
                    <th width="18%">ชื่อ-นามสกุล</th>
                    <th width="12%">ระดับสมาชิก</th>
                    <th width="15%">ชำระเงินสด (บาท)</th>
                    <th width="18%">โอนผ่านบัญชีธนาคาร (บาท)</th>
                    <th width="18%">สินเชื่อหักเงินเดือน (บาท)</th>
                    <th width="14%">ยอดซื้อทั้งหมด (บาท)</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    $counts = count($resultArray);
                    $no = 0;
                    for($col=0; $col<$counts; $col++){ 
                  ?>
                  <tr>
                    <td align="left"><?php echo $no+=1; ?></td>
                    <td align="left"><?php echo $resultArray[$col][0]; ?></td>
                    <td align="left">
                      <?php
                        $memberName = $resultArray[$col][0];
                        if (isset($memberLevels[$memberName])) {
                          $row_member = array('ref_l_id' => $memberLevels[$memberName]);
                          include 'mem_status.php';
                        } else {
                          echo "-";
                        }
                      ?>
                    </td>
                    <td align="left"><?php echo $resultArray[$col][1]; ?></td>
                    <td align="left"><?php echo $resultArray[$col][2]; ?></td>
                    <td align="left"><?php echo $resultArray[$col][3]; ?></td>
                    <td align="left"><b><?php echo $resultArray[$col][4]; ?><b></td>
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
        <form action="report_purchase.php" method="POST" enctype="multipart/form-data">
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
                  <?php $now_year = date('Y');
                  ?>
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

</body>
</html>