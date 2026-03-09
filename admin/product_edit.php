<?php 
$menu = "product";
include("header.php");
?>

<?php 
$p_id = $_GET['p_id'];
$query_product = "SELECT * FROM tbl_product LEFT JOIN tbl_type ON tbl_product.t_id = tbl_type.t_id
LEFT JOIN tbl_brand ON tbl_product.b_id = tbl_brand.b_id
WHERE p_id = $p_id" or die("Error : ".mysqli_error($condb));
$rs_product = mysqli_query($condb, $query_product);
$row=mysqli_fetch_array($rs_product);
//echo $row['mem_name'];
//echo ($query_member);//test query

$query_type = "SELECT *FROM tbl_type " or die("Error : ".mysqli_error($condb));
$rs_type = mysqli_query($condb, $query_type);
$query_brand = "SELECT * FROM tbl_brand " or die("Error : ".mysqli_error($condb));
$rs_brand = mysqli_query($condb, $query_brand);
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Product Edit</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">แก้ไขสินค้า</h3>  
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-8">
              <form action="product_db.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="product" value="edit">
                <input type="hidden" name="p_id" value="<?php echo $row['p_id'];?>">
                <input name="file1" type="hidden" id="file1" value="<?php echo $row['p_img']; ?>">
                <div class="row">
                  <label for="p_barCode" class="col-sm-2 col-form-label">รหัสบาร์โค้ด</label>
                  <div class="col-sm-10">
                    <input name="p_barCode" type="text" class="form-control" value="<?php echo $row['p_barCode']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="t_id" class="col-sm-2 col-form-label">ประเภทสินค้า</label>
                  <div class="col-sm-10">
                    <select name="t_id" class="form-control">
                      <option value="<?php echo $row['t_id'] ?>"><?php echo $row['t_name']; ?></option>
                      <option value="">--เลือกประเภทสินค้า--</option>
                      <?php foreach ($rs_type as $rst) { ?>
                      <option value="<?php echo $rst['t_id'];?>"><?php echo $rst['t_name'];?></option>
                      <?php } ?>
                    </select>   
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="b_id" class="col-sm-2 col-form-label">แบรนด์สินค้า</label>
                  <div class="col-sm-10">
                    <select name="b_id" class="form-control">
                      <option value="<?php echo $row['b_id'] ?>"><?php echo $row['b_name']; ?></option>
                      <option value="">--เลือกแบรนด์สินค้า--</option>
                      <?php foreach ($rs_brand as $rsb) { ?>
                      <option value="<?php echo $rsb['b_id'];?>"><?php echo $rsb['b_name']; ?></option>
                      <?php } ?>  
                    </select>   
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_name" class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                  <div class="col-sm-10">
                    <input name="p_name" type="text" class="form-control" value="<?php echo $row['p_name']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_detail" class="col-sm-2 col-form-label">รายละเอียด</label>
                  <div class="col-sm-10">
                    <textarea name="p_detail" class="form-control" row="3"><?php echo $row['p_detail']; ?></textarea>
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_cost" class="col-sm-2 col-form-label">ราคาต้นทุน</label>
                  <div class="col-sm-10">
                    <input name="p_cost" type="number" min="0" step="0.01" class="form-control" value="<?php echo $row['p_cost']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_price" class="col-sm-2 col-form-label">ราคาขาย</label>
                  <div class="col-sm-10">
                    <input name="p_price" type="number" min="0" step="0.01" class="form-control" value="<?php echo $row['p_price']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_qty" class="col-sm-2 col-form-label">จำนวน</label>
                  <div class="col-sm-10">
                    <input name="p_qty" type="number" min="0" step="1" class="form-control" value="<?php echo $row['p_qty']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="mem_img2" class="col-sm-2 col-form-label">รูปสินค้า</label>
                  <div class="col-sm-10">
                    <img src="../p_img/<?php echo $row['p_img'];?>" width="150px">
                    <input type="hidden" name="mem_img2" value="<?php echo $row['p_img']; ?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="p_img" class="col-sm-2 col-form-label">รูปสินค้า</label>
                  <div class="col-sm-10">
                    <input class="form-control" type="file" name="p_img" id="p_img">
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