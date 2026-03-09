<?php 
$menu = "report_profit";
include("header.php");

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// กำหนดช่วงวันที่/เดือน/ปี เหมือนกับหน้า report_sales.php
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

$query_order = "SELECT
  tbl_product.p_name AS Product_Name,
  SUM(tbl_order_detail.p_c_qty) AS Sales,
  SUM(tbl_order_detail.p_c_qty) * tbl_product.p_price AS Totals,
  SUM(tbl_order_detail.p_c_qty) * (tbl_product.p_price - tbl_product.p_cost) AS Profit
FROM tbl_order_detail
INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id
INNER JOIN tbl_product ON tbl_order_detail.p_barCode = tbl_product.p_barCode
WHERE $dateWhere
GROUP BY tbl_product.p_name
ORDER BY tbl_product.p_name ASC" or die("Error : ".mysqli_error($condb));
$rs_order = mysqli_query($condb, $query_order);
mysqli_close($condb);
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
          <h3 class="card-title">Report Profit</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา</button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div id="container"></div>
              <table id="tableSearch" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th width="45%">รายการ</th>
                    <th width="10%">ขายไป</th> 
                    <th width="10%">ยอดขาย</th> 
                    <th width="10%">กำไร</th>          
                  </tr>
                </thead>
                <tbody>
                <?php
                // กำหนดค่าเริ่มต้นป้องกัน Warning กรณีไม่มีข้อมูล
                $total_totals = 0;
                $total_net_profit = 0;

                while ($row = mysqli_fetch_assoc($rs_order)) {
                  $total_totals += $row['Totals'];       // รวมยอดขายแต่ละรายการ
                  $total_net_profit += $row['Profit'];   // รวมกำไรแต่ละรายการ
                  echo "<tr>";
                  echo "<td align='left'>{$row['Product_Name']}</td>";
                  echo "<td align='left'>{$row['Sales']}</td>";
                  echo "<td align='left'>{$row['Totals']}</td>";
                  echo "<td align='left'>{$row['Profit']}</td>"; 
                  echo "</tr>";
                }
                ?>
                </tbody>
              </table>
            </div>   
          </div> 
          <div class="row">
            <div class="col-md-12 text-center">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td align="right"><b>ยอดขายทั้งหมด:</b></td>
                    <td align="left"><b><?php echo number_format($total_totals, 2)." บาท"; ?></b></td>
                  </tr>
                  <tr>
                    <td align="right"><b>กำไรสุทธิ:</b></td>
                    <td align="left"><b><?php echo number_format($total_net_profit, 2)." บาท"; ?></b></td>
                  </tr>
                </tbody>
              </table>
            </div>   
          </div> 
          <button class="print-button btn btn-success" onclick="print()">พิมพ์เอกสาร</button>
        </div> 
      </div>    
    </section>
    <!-- /.content -->

    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="report_profit.php" method="POST" enctype="multipart/form-data">
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
  include('footer2.php'); 
?>
</body>
</html>
