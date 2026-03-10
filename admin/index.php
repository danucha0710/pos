<?php 
  $menu = "index";
  include("header.php"); 
?>

    <section class="content-header">
      <div class="container-fluid">
        <h1>Order</h1>
      </div><!-- /.container-fluid -->
    </section>

    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">รายการขายหน้าร้าน</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#searchOrderModal">
              <i class="fa fa-search"></i> ค้นหา ตามช่วงเวลา
            </button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">       
            <div class="col-md-12">
              <?php 
                $act = (isset($_GET['act']) ? $_GET['act'] : '');
                if($act =='view'){
                  include('order_detail.php');
                }
                else{
                  include('list_order.php');
                } 
              ?>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal ค้นหาตามช่วงเวลา เหมือน report_sales.php -->
    <div class="modal fade" id="searchOrderModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="index.php" method="POST" enctype="multipart/form-data">
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