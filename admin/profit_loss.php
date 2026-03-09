<?php 
$menu = "profit_loss";
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
  $dateText = " AND tbl_order.order_date LIKE '".date("Y-m")."%"."'";
}

$query_my_order = "SELECT tbl_product.p_name, SUM(tbl_order_detail.p_c_qty) AS sumqty
FROM `tbl_order_detail`
INNER JOIN tbl_product ON ( tbl_order_detail.p_barCode = tbl_product.p_barCode)
INNER JOIN tbl_order ON ( tbl_order_detail.order_id = tbl_order.order_id $dateText)
GROUP BY tbl_product.p_name
ORDER By SUM(tbl_order_detail.p_c_qty) DESC
LIMIT 10;" or die("Error : ".mysqli_error($condb));
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
          <h3 class="card-title">Profit/Loss</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา</button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <center><h5>กำไร/ขาดทุน</h5></center>
              <br>
              <table class="table table-hover" id="datatable" >
                <thead>
                  <tr>
                    <th>ชื่อสินค้า</th>
                    <th>จำนวนยอดขาย</th>
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

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
        <form action="profit_loss.php" method="POST" enctype="multipart/form-data">
          <div class="modal-content">
            <div class="modal-header bg-dark">
              <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
              <h5 class="modal-title">ค้นหาตามช่วงเวลา</h5>
            </div>
            <div class="modal-body">
              <div class="form-row">
                <h6>ค้นหาแบบช่วงเวลา</h6>
              </div>  
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="admin_name">วันที่เริ่มต้น</label>
                  <input type="date" class="form-control" id="date_s" name="date_s" placeholder="" value="">
                </div>
                <div class="form-group col-md-6">
                  <label for="admin_username">วันที่สิ้นสุด</label>
                  <input type="date" class="form-control" id="date_e" name="date_e" placeholder="" value="">
                </div>
              </div> 
              <div class="form-row">
                <h6>ค้นหาแบบเลือกเดือน</h6>
              </div>  
              <div class="form-row">
                <div class="form-group col-md-6">
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
              <div class="form-row">
                <h6>ค้นหาแบบเลือกปี</h6>
              </div>  
              <div class="form-row">
                <div class="form-group col-md-6">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
          </div>
        </form>
      </div>
    </div> 

 
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<?php 
  mysqli_close($condb);
  include('footer.php'); 
?>

<script>
  $(function (){
    $(".datatable").DataTable();
    // $('#example2').DataTable({
    //   "paging": true,
    //   "lengthChange": false,
    //   "searching": false,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": false,
    // });
  });
</script>
  
</body>
</html>