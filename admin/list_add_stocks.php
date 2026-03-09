<?php 
$menu = "list_add_stocks";
include("header.php");

//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

// กำหนดช่วงวันที่/เดือน/ปี เหมือนกับหน้า report_sales.php
if (!empty($_POST['date_s']) AND !empty($_POST['date_e'])) {
  $date_s = $_POST['date_s'] . " 00:00:00";
  $date_e = $_POST['date_e'] . " 23:59:59";
  $dateWhere = " sto_date_add BETWEEN '$date_s' AND '$date_e'";
}
elseif (!empty($_POST['month'])) {
  $month = $_POST['month'];
  $year = date('Y');
  $dateWhere = " sto_date_add LIKE '".$year."-".$month."%'";
}
elseif (!empty($_POST['year'])) {
  $year = $_POST['year'];
  $dateWhere = " sto_date_add LIKE '".$year."%'";
}
else{
  // ค่าเริ่มต้น: แสดงเฉพาะ "ปีปัจจุบัน"
  $year = date('Y');
  $dateWhere = " sto_date_add LIKE '".$year."%'";
}

$query_add_stocks = "SELECT * FROM tbl_add_stocks WHERE $dateWhere ORDER BY sto_date_add DESC" or die("Error : ".mysqli_error($condb));
$rs_add_stocks = mysqli_query($condb, $query_add_stocks);
//echo $query_add_stocks;
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>List add stocks</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">รายการเพิ่มสินค้าเข้าคลัง</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา
            </button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table id="tableSearch" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr class="danger">
                    <th width="5%"><center>ลำดับ</center></th>
                    <th width="10%"><center>รหัสบาร์โค้ด</center></th>
                    <th width="25%"><center>ชื่อสินค้า</center></th>
                    <th width="10%"><center>ราคาขาย</center></th>
                    <th width="10%"><center>จำนวนคงคลัง</center></th>
                    <th width="10%"><center>จำนวนที่เพิ่ม</center></th>
                    <th width="15%"><center>วันที่เพิ่ม</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rs_add_stocks as $row_add_stocks) { ?>
                  <tr>
                    <td><?php echo $row_add_stocks['sto_id']; ?></td>
                    <td><?php echo $row_add_stocks['p_barCode']; ?></td>
                    <td><?php echo $row_add_stocks['p_name']; ?></td>
                    <td align="right"><?php echo $row_add_stocks['p_price']; ?></td>
                    <td align="center"><?php echo $row_add_stocks['p_qty']; ?></td>
                    <td align="center"><?php echo $row_add_stocks['sto_add']; ?></td>
                    <td align="center"><?php echo date('d/m/Y H:i:s', strtotime($row_add_stocks['sto_date_add'])); ?></td>
                    
                  </tr>
                  <?php } ?>
                </tbody>
              </table>

              <?php if(isset($_GET['d'])){ ?>
              <div class="flash-data" data-flashdata="<?php echo $_GET['d'];?>"></div>
              <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  
    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="list_add_stocks.php" method="POST" enctype="multipart/form-data">
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
  
<?php include('footer.php'); ?>
  
</body>
</html> 