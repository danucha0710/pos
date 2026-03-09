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
                <div class="col-md-6">
                  <label for="date_s">วันที่เริ่มต้น</label>
                  <input type="date" class="form-control" id="date_s" name="date_s" required>
                </div>
                <div class="col-md-6">
                  <label for="date_e">วันที่สิ้นสุด</label>
                  <input type="date" class="form-control" id="date_e" name="date_e" required>
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