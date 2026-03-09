<?php 
  $menu = "";
  include("header.php");

  $mem_id = $_SESSION['mem_id'];
  $query_member = "SELECT * FROM tbl_member WHERE mem_id = $mem_id" or die("Error : ".mysqli_error($condb));
  $rs_member = mysqli_query($condb, $query_member);
  $row=mysqli_fetch_array($rs_member);
  //echo $row['mem_name'];
  //echo ($query_member);//test query
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Profile</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">แก้ไขข้อมูลส่วนตัว</h3>
        </div>
        <br>
        <div class="card-body">
          <div class="row"> 
            <div class="col-md-8">
              <form action="member_db.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="member" value="edit_profile">
                <input type="hidden" name="mem_id" value="<?php echo $row['mem_id'];?>">
                <input type="hidden" name="ref_l_id" value="<?php echo $row['ref_l_id'];?>">
                <div class="row">
                  <label for="mem_name" class="col-sm-3 col-form-label">ชื่อ</label>
                  <div class="col-sm-9">
                    <input type="text" name="mem_name" class="form-control" id="mem_name"value="<?php echo $row['mem_name'];?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="mem_phone" class="col-sm-3 col-form-label">หมายเลขโทรศัพท์</label>
                  <div class="col-sm-9">
                    <input type="text" name="mem_phone" class="form-control" id="mem_phone" value="<?php echo $row['mem_phone'];?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="mem_username" class="col-sm-3 col-form-label">Username</label>
                  <div class="col-sm-9">
                    <input type="text" name="mem_username" class="form-control" id="mem_username" value="<?php echo $row['mem_username'];?>" readonly>
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="mem_password" class="col-sm-3 col-form-label">Password</label>
                  <div class="col-sm-9">
                    <input type="text" name="mem_password" class="form-control" id="mem_password" placeholder="ใส่รหัสผ่านก่อนกดบันทึก" required>
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="" class="col-sm-3 col-form-label">รูปภาพ</label>
                  <div class="col-sm-9">  
                    <img src="../mem_img/<?php echo $row['mem_img'];?>" width="150px">
                    <input type="hidden" name="mem_img2" value="<?php echo $row['mem_img'];?>">
                  </div>
                </div>
                <div class="row mt-3">
                  <label for="" class="col-sm-3 col-form-label">เปลี่ยนรูปภาพ</label>
                  <div class="col-sm-9">         
                  <input class="form-control" type="file" name="mem_img" id="mem_img">
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