<?php 
$menu = "report_top10_product";
include("header.php");

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
  // ค่าเริ่มต้น: จำกัดเฉพาะปีปัจจุบัน เพื่อลดข้อมูลที่ต้องประมวลผล
  $year = date('Y');
  $dateText = " AND tbl_order.order_date LIKE '".$year."%'";
}

$query_my_order = "SELECT tbl_product.p_name, SUM(tbl_order_detail.p_c_qty) AS sumqty
FROM tbl_order_detail
INNER JOIN tbl_order ON tbl_order_detail.order_id = tbl_order.order_id
INNER JOIN tbl_product ON tbl_order_detail.p_barCode = tbl_product.p_barCode
WHERE 1 $dateText
GROUP BY tbl_product.p_name
ORDER BY SUM(tbl_order_detail.p_c_qty) DESC
LIMIT 10" or die("Error : ".mysqli_error($condb));
$result = mysqli_query($condb, $query_my_order);
//echo ($query_my_order);//test query
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
          <h3 class="card-title">Top 10 Best Seller</h3>
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
              <table class="table table-hover table-striped" id="datatable" >
                <thead>
                  <tr>
                    <th width="30%">ชื่อสินค้า</th>
                    <th width="70%">จำนวนยอดขาย</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($result as $value){ ?>
                  <tr>
                    <td><?php echo $value["p_name"]; ?></td>
                    <td><?php echo $value["sumqty"]; ?></td>
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
        <form action="report_top10_product.php" method="POST" enctype="multipart/form-data">
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
  include('footer2.php'); 
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
      text: 'รายงานสินค้าขายดี'
    },
    xAxis: {
      type: 'category'
    },
    yAxis: {
      allowDecimals: false,
      title: {
        text: 'จำนวนยอดขาย'
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