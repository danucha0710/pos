<?php 
$menu = "type";
include("header.php");

$t_id = $_GET['t_id'];
$query_type = "SELECT * FROM tbl_type WHERE t_id = $t_id"  or die("Error : ".mysqli_error($condb));
$rs_type = mysqli_query($condb, $query_type);
$row=mysqli_fetch_array($rs_type);
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Type</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">แก้ไขประเภทสินค้า</h3>  
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <form action="type_db.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="type" value="edit">
                <input type="hidden" name="t_id" value="<?php echo $row['t_id']; ?>">
                <div class="row">
                  <label for="t_name" class="col-sm-2 col-form-label">ชื่อประเภทสินค้า</label>
                  <div class="col-sm-10">
                    <input type="text" name="t_name" class="form-control" id="t_name" value="<?php echo $row['t_name'];?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <button type="submit" class="btn btn-danger btn-block">แก้ไข</button>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="card-footer">
        </div>             
      </div>
    </section>
    <!-- /.content -->

<?php include('footer.php'); ?>
  
</body>
</html>