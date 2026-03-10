<?php
error_reporting(error_reporting() & ~E_NOTICE);
session_start();

// อนุญาตเฉพาะแอดมินระดับหลัก (m_level = 0)
if (!isset($_SESSION['ref_l_id']) || $_SESSION['ref_l_id'] != 0) {
  Header("Location: ../index.php");
  exit();
}

include('../condb.php');

$setting_id   = isset($_POST['setting_id']) ? (int)$_POST['setting_id'] : 0;
$org_name     = mysqli_real_escape_string($condb, $_POST['org_name']);
$org_address  = mysqli_real_escape_string($condb, $_POST['org_address']);
$tax_id       = mysqli_real_escape_string($condb, $_POST['tax_id']);
$promptpay_no = mysqli_real_escape_string($condb, $_POST['promptpay_no']);
$current_logo = mysqli_real_escape_string($condb, $_POST['current_logo']);

// จัดการอัปโหลดโลโก้ (เก็บไฟล์ในโฟลเดอร์ mem_img เหมือนรูปสมาชิก)
$date1   = date("Ymd_His");
$numrand = mt_rand();
$upload  = $_FILES['org_logo']['name'];

if ($upload != '') {
  $path = "../mem_img/";
  $type = strrchr($upload, ".");
  $newname = $numrand . $date1 . $type;
  $path_copy = $path . $newname;
  move_uploaded_file($_FILES['org_logo']['tmp_name'], $path_copy);
} else {
  $newname = $current_logo; // ใช้รูปเดิม
}

if ($setting_id > 0) {
  // อัปเดตแถวเดิม
  $sql = "
    UPDATE tbl_settings SET
      org_name     = '$org_name',
      org_logo     = '$newname',
      org_address  = '$org_address',
      tax_id       = '$tax_id',
      promptpay_no = '$promptpay_no'
    WHERE id = $setting_id
  ";
} else {
  // เพิ่มแถวใหม่ (มีได้แค่หนึ่งแถว)
  $sql = "
    INSERT INTO tbl_settings(org_name, org_logo, org_address, tax_id, promptpay_no)
    VALUES('$org_name', '$newname', '$org_address', '$tax_id', '$promptpay_no')
  ";
}

$result = mysqli_query($condb, $sql);

if ($result) {
  mysqli_close($condb);
  Header("Location: settings.php?save=success");
  exit();
} else {
  mysqli_close($condb);
  Header("Location: settings.php?save=error");
  exit();
}

