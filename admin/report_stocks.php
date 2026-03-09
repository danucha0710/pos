<?php 
$menu = "report_stocks";
include("header.php");

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// กำหนดช่วงวันที่/เดือน/ปี เหมือนกับหน้า report_sales.php
if (!empty($_POST['date_s']) AND !empty($_POST['date_e'])) {
  $date_s = $_POST['date_s'] . " 00:00:00";
  $date_e = $_POST['date_e'] . " 23:59:59";
  $dateWhere = " tbl_add_stocks.sto_date_add BETWEEN '$date_s' AND '$date_e'";
}
elseif (!empty($_POST['month'])) {
  $month = $_POST['month'];
  $year = date('Y');
  $dateWhere = " tbl_add_stocks.sto_date_add LIKE '".$year."-".$month."%'";
}
elseif (!empty($_POST['year'])) {
  $year = $_POST['year'];
  $dateWhere = " tbl_add_stocks.sto_date_add LIKE '".$year."%'";
}
else{
  // ค่าเริ่มต้น: แสดงเฉพาะ "ปีปัจจุบัน"
  $year = date('Y');
  $dateWhere = " tbl_add_stocks.sto_date_add LIKE '".$year."%'";
}

// หมายเหตุ: รายงานนี้สรุปคลังสินค้าปัจจุบันจาก tbl_product ทั้งตาราง
// เงื่อนไขวันที่ ($dateWhere) จะใช้กับ export_stocks.php หรือขยายในอนาคต
$query_order = "SELECT
  tbl_product.p_name AS Product_Name,
  tbl_product.p_qty AS Stocks, 
  tbl_product.p_price AS Price,
  tbl_product.p_cost AS Cost,
  SUM(tbl_product.p_qty * tbl_product.p_cost) AS Total_Cost
FROM tbl_product
GROUP BY tbl_product.p_name" or die("Error : ".mysqli_error($condb));
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
          <h3 class="card-title">Report Stocks</h3>
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
                    <th width="10%">คงเหลือ</th>
                    <th width="10%">ราคา</th>  
                    <th width="10%">ต้นทุน</th> 
                    <th width="10%">รวมต้นทุน</th>          
                  </tr>
                </thead>
                <tbody>
                <?php
                $total_cost = 0;
                while ($row = mysqli_fetch_assoc($rs_order)) {
                  $total_cost += $row['Total_Cost'];  // รวมราคาต้นทุนแต่ละรายการ
                  echo "<tr>";
                  echo "<td align='left'>{$row['Product_Name']}</td>";
                  echo "<td align='left'>{$row['Stocks']}</td>";
                  echo "<td align='left'>{$row['Price']}</td>";
                  echo "<td align='left'>{$row['Cost']}</td>";
                  echo "<td align='left'>{$row['Total_Cost']}</td>"; 
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
                    <td align="right"><b>รวมต้นทุนทั้งสิ้น:</b></td>
                    <td align="left"><b><?php echo number_format($total_cost, 2)." บาท"; ?></b></td>
                  </tr>
                </tbody>
              </table>
            </div>   
          </div>
          <div class="text-left"> 
            <button class="print-button btn btn-success" onclick="window.print()">
              <i class="fas fa-print"></i> พิมพ์เอกสาร
            </button>
            <a href="export_stocks.php?date_s=<?php echo substr($date_s, 0, 10); ?>&date_e=<?php echo substr($date_e, 0, 10); ?>" class="btn btn-primary">
              <i class="fas fa-file-excel"></i> Export File
            </a>
          </div>
        </div> 
      </div>    
    </section>
    <!-- /.content -->

    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="report_stocks.php" method="POST" enctype="multipart/form-data">
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
  include('footer.php'); 
?>
</body>
</html>
