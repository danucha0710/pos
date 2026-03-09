<?php 
$menu = "report_all";
include("header.php");

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
if (isset($_POST['date_s']) AND $_POST['date_e']) {
  $date_s = $_POST['date_s']." 00:00:00";
  $date_e = $_POST['date_e']." 23:59:59";
}
else{
  $date_s = date("Y-m-d")." 00:00:00";
  $date_e = date("Y-m-d")." 23:59:59";
}
//echo $date_s;
//echo "<br>";
//echo $date_e;
//exit();
$query_order = "SELECT
  tbl_product.p_name AS Product_Name,
  SUM(tbl_order_detail.p_c_qty) AS Sales,
  tbl_product.p_price AS Price,
  SUM(tbl_order_detail.p_c_qty * tbl_product.p_price) AS Totals,
  tbl_product.p_qty AS Stocks
FROM tbl_order_detail
INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id
INNER JOIN tbl_product ON tbl_order_detail.p_barCode = tbl_product.p_barCode
WHERE tbl_order.order_date BETWEEN '"."$date_s' AND '"."$date_e'"."
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
          <h3 class="card-title">Report Sales & Stocks</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา</button>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <div id="container"></div>
              <table class="table table-hover table-striped" id="datatable">
                <thead>
                  <tr>
                    <th width="45%">รายการ</th>
                    <th width="10%">ขายไป</th> 
                    <th width="10%">ยอดขาย</th> 
                    <th width="10%">คงเหลือ</th>          
                  </tr>
                </thead>
                <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($rs_order)) {
                  echo "<tr>";
                  echo "<td align='left'>{$row['Product_Name']}</td>";
                  echo "<td align='left'>{$row['Sales']}</td>";
                  echo "<td align='left'>{$row['Price']}</td>";
                  echo "<td align='left'>{$row['Stocks']}</td>"; 
                  echo "</tr>";
                }
                ?>
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
        <form action="report_all.php" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header bg-gray">
              <h5 class="modal-title" id="exampleModalLabel">ค้นหาตามช่วงเวลา</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <label for="date_s">วันที่เริ่มต้น</label>
                  <input type="date" class="form-control" id="date_s" name="date_s" required>
                </div>
                <div class="col-md-6">
                  <label for="date_e">วันที่สิ้นสุด</label>
                  <input type="date" class="form-control" id="date_e" name="date_e" required>
                </div>
              </div>      
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
              </div>
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
