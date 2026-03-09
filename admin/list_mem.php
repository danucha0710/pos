<?php
session_start();
$menu = "member";
include("header.php");
if($_SESSION["ref_l_id"] == 0) {
  $query_member = "SELECT * FROM tbl_member" or die("Error : ".mysqli_error($condb));
}
else {
  $query_member = "SELECT * FROM tbl_member WHERE ref_l_id > 2" or die("Error : ".mysqli_error($condb));
}
$rs_member = mysqli_query($condb, $query_member);
//echo ($query_level);//test query
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Member</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header">
          <h3 class="card-title">รายการสมาชิก</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i> เพิ่มข้อมูล สมาชิก</button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table id="tableSearch" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr class="danger">
                    <th width="1%"><center>ลำดับ</center></th>
                    <th width="20%"><center>ชื่อ-นามสกุล</center></th>
                    <th width="10%"><center>เบอร์โทร</center></th>
                    <th width="10%"><center>สถานะ</center></th>
                    <th width="10%"><center>แต้ม</center></th>
                    <th width="5%"><center>แก้ไข</center></th>
                    <th width="5%"><center>ลบ</center></th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($rs_member as $row_member) { ?>
                  <tr>
                    <td align="center"><?php echo $row_member['mem_id']; ?></td>
                    <td><?php echo $row_member['mem_name']; ?></td>
                    <td><?php echo $row_member['mem_phone']; ?></td>
                    <td><?php include('mem_status.php'); ?></td>
                    <td align="right"><?php echo $row_member['mem_point']; ?></td>
                    <td align="center"><a href="mem_edit.php?mem_id=<?php echo $row_member['mem_id']; ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> edit</a></td>
                    <td align="center"><a href="member_db.php?mem_id=<?php echo $row_member['mem_id']; ?>" class="del-btn btn btn-danger"><i class="fas fas fa-trash"></i> del</a></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>

              <?php if(isset($_GET['d'])){ ?>
              <div class="flash-data" data-flashdata="<?php echo $_GET['d'];?>"></div>
              <?php } ?>
              
              <script>
                $('.del-btn').on('click',function(e){
                  e.preventDefault();
                  const href = $(this).attr('href') 
                  Swal.fire({
                    title: 'ต้องการลบข้อมูลใช่ไหม ?',
                    //text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it.'
                  }).then((result) => {
                    if (result.value) {
                      document.location.href = href;    
                    }
                  })
                })

                const flashdata = $('.flash-data').data('flashdata')
                if(flashdata){
                  swal.fire({
                    type : 'success',
                    title : 'ลบข้อมูลเรียบร้อยแล้ว',
                    //text : 'Record has been deleted',
                    icon: 'success'
                  })
                }
              </script>
                    
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->

    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form action="member_db.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="member" value="add">
            <div class="modal-header">
              <h5 class="modal-title">เพิ่มข้อมูล สมาชิก</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <label for="ref_l_id" class="col-sm-2 col-form-label">ระดับการใช้งาน</label>
                <div class="col-sm-10">
                <?php if($_SESSION["ref_l_id"] == 0) { ?>
                  <select class="form-select" name="ref_l_id" id="ref_l_id" required>
                    <option value="">-- เลือกประเภท --</option>
                    <option value="1">ผู้ดูแลระบบ(Admin)</option>
                    <option value="2">พนักงาน</option>
                    <option value="3">ลูกค้าทั่วไป</option>
                    <option value="4">สมาชิก</option>
                  </select>
                <?php } else { ?>
                  <select class="form-select" name="ref_l_id" id="ref_l_id" required>
                    <option value="">-- เลือกประเภท --</option>
                    <option value="3">ลูกค้าทั่วไป</option>
                    <option value="4">สมาชิก</option>
                  </select>
                <?php } ?>
                </div>
              </div>
              <div class="row mt-3">
                <label for="mem_name" class="col-sm-2 col-form-label">ชื่อ-นามสกุล</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="mem_name" id="mem_name" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="mem_username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="mem_username" id="mem_username" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="mem_password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" name="mem_password" id="mem_password" placeholder="ใส่รหัสผ่านก่อนกดบันทึก" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="mem_phone" class="col-sm-2 col-form-label">หมายเลขโทรศัพท์</label>
                <div class="col-sm-10">
                  <input type="text" name="mem_phone" class="form-control" id="mem_phone" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="" class="col-sm-2 col-form-label">รูปภาพ</label>
                <div class="col-sm-10">
                  <input class="form-control" type="file" name="mem_img" id="mem_img">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
          </form>
        </div>
      </div>
    </div> 

<?php include('footer.php'); ?>
  
</body>
</html>

