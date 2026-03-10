<?php 
$menu = "settings";
include("header.php");

// อนุญาตเฉพาะแอดมินระดับหลัก (m_level = 0)
if ($m_level != 0) {
?>
  <section class="content-header">
    <div class="container-fluid">
      <h1>ไม่มีสิทธิ์เข้าถึงหน้านี้</h1>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <div class="alert alert-danger mt-3">
        เฉพาะผู้ดูแลระบบ (Admin) เท่านั้นที่สามารถจัดการหน้าตั้งค่าได้
      </div>
    </div>
  </section>
<?php
  include('footer.php');
  exit();
}

// โหลดข้อมูล settings (มีแถวเดียว)
$sql_settings = "SELECT * FROM tbl_settings LIMIT 1";
$result_settings = mysqli_query($condb, $sql_settings);
$settings = $result_settings ? mysqli_fetch_assoc($result_settings) : null;

$setting_id    = $settings ? (int)$settings['id'] : 0;
$org_name      = $settings['org_name'] ?? '';
$org_logo      = $settings['org_logo'] ?? '';
$org_address   = $settings['org_address'] ?? '';
$tax_id        = $settings['tax_id'] ?? '';
$promptpay_no  = $settings['promptpay_no'] ?? '';
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>ตั้งค่าข้อมูลหน่วยงาน</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-8">
            <div class="card card-gray">
              <div class="card-header">
                <h3 class="card-title">ข้อมูลหน่วยงาน</h3>
              </div>
              <form action="settings_db.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="setting_id" value="<?php echo $setting_id; ?>">
                <input type="hidden" name="current_logo" value="<?php echo htmlspecialchars($org_logo, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="card-body">
                  <?php if (!empty($_GET['save']) && $_GET['save'] === 'success') { ?>
                    <div class="alert alert-success">
                      บันทึกข้อมูลเรียบร้อยแล้ว
                    </div>
                  <?php } elseif (!empty($_GET['save']) && $_GET['save'] === 'error') { ?>
                    <div class="alert alert-danger">
                      ไม่สามารถบันทึกข้อมูลได้ กรุณาลองใหม่อีกครั้ง
                    </div>
                  <?php } ?>

                  <div class="mb-3 row">
                    <label for="org_name" class="col-sm-3 col-form-label">ชื่อหน่วยงาน</label>
                    <div class="col-sm-9">
                      <input type="text" name="org_name" id="org_name" class="form-control" required
                        value="<?php echo htmlspecialchars($org_name, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label for="org_logo" class="col-sm-3 col-form-label">โลโก้หน่วยงาน</label>
                    <div class="col-sm-9">
                      <input type="file" name="org_logo" id="org_logo" class="form-control">
                      <small class="form-text text-muted">
                        รองรับไฟล์ภาพนามสกุล .jpg, .jpeg, .png
                      </small>
                      <?php if (!empty($org_logo)) { ?>
                        <div class="mt-2">
                          <p class="mb-1">ตัวอย่างโลโก้ปัจจุบัน</p>
                          <img src="../mem_img/<?php echo htmlspecialchars($org_logo, ENT_QUOTES, 'UTF-8'); ?>" 
                               alt="Organization Logo" 
                               style="max-height: 80px;">
                        </div>
                      <?php } ?>
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label for="org_address" class="col-sm-3 col-form-label">ที่อยู่หน่วยงาน</label>
                    <div class="col-sm-9">
                      <textarea name="org_address" id="org_address" rows="3" class="form-control"><?php 
                        echo htmlspecialchars($org_address, ENT_QUOTES, 'UTF-8'); 
                      ?></textarea>
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label for="tax_id" class="col-sm-3 col-form-label">เลขผู้เสียภาษี</label>
                    <div class="col-sm-9">
                      <input type="text" name="tax_id" id="tax_id" class="form-control"
                        value="<?php echo htmlspecialchars($tax_id, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                  </div>

                  <div class="mb-3 row">
                    <label for="promptpay_no" class="col-sm-3 col-form-label">หมายเลขพร้อมเพย์</label>
                    <div class="col-sm-9">
                      <input type="text" name="promptpay_no" id="promptpay_no" class="form-control"
                        value="<?php echo htmlspecialchars($promptpay_no, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> บันทึกข้อมูล
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

<?php include('footer.php'); ?>

  </body>
</html>

