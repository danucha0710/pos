<?php
$menu = "brand";
include("header.php");

$query_brand = "SELECT * FROM tbl_brand ORDER BY b_id DESC" or die("Error : ".mysqli_error($condb));
$rs_brand = mysqli_query($condb, $query_brand);
//echo ($query_level);
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
        <div class="card-header">
          <h3 class="card-title">รายการแบรนด์สินค้า</h3>
          <div align="right">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa fa-plus"></i> เพิ่มข้อมูล แบรนด์สินค้า</button>  
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row"> 
            <div class="col-md-8">
              <table id="tableSearch" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr class="danger">
                    <th width="1%"><center>ลำดับ</center></th>
                    <th width="20%"><center>ชื่อแบรนด์</center></th>
                    <th width="3%"><center>แก้ไข</center></th>
                    <th width="3%"><center>ลบ</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($rs_brand as $rs_brand) { ?>
                  <tr>
                    <td align="center"><?php echo $rs_brand['b_id']; ?></td>
                    <td align="left"><?php echo $rs_brand['b_name']; ?></td>
                    <td align="center"><a href="brand_edit.php?b_id=<?php echo $rs_brand['b_id']; ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> edit</a></td>
                    <td align="center"><a href="brand_db.php?b_id=<?php echo $rs_brand['b_id']; ?>" class="del-btn btn btn-danger"><i class="fas fas fa-trash"></i> del</a></td>
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
        <form action="brand_db.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="brand" value="add">
          <div class="modal-content">
            <div class="modal-header bg-gray">
              <h5 class="modal-title" id="exampleModalLabel">เพิ่มข้อมูล แบรนด์สินค้า</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <label for="b_name" class="col-sm-2 col-form-label">ชื่อประแบรนด์</label>
                <div class="col-sm-10">
                  <input type="text" name="b_name" class="form-control" id="b_name" required>
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