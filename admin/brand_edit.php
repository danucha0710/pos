<?php 
$menu = "brand";
include("header.php");

$b_id = $_GET['b_id'];
$query_type = "SELECT * FROM tbl_brand WHERE b_id = $b_id" or die("Error : ".mysqli_error($condb));
$rs_type = mysqli_query($condb, $query_type);
$row=mysqli_fetch_array($rs_type);
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Brand</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">แก้ไข แบรนด์สินค้า</h3> 
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <form action="brand_db.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="brand" value="edit">
                <input type="hidden" name="b_id" value="<?php echo $row['b_id'];?>">
                <div class="row">
                  <label for="b_name" class="col-sm-2 col-form-label">ชื่อแบรนด์สินค้า</label>
                  <div class="col-sm-10">
                    <input type="text" name="b_name" class="form-control" id="b_name" value="<?php echo $row['b_name'];?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <button type="submit" class="btn btn-danger btn-block">แก้ไข</button>
                </div>
              </form>
            </div>
          </div>
        </div>     
      </div>
    </section>
    <!-- /.content -->
 
<?php include('footer.php'); ?>
  
</body>
</html>