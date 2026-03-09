<?php
include('../condb.php');

// รับค่าจากฟอร์ม (หลายรายการ)
$items = isset($_POST['items']) && is_array($_POST['items']) ? $_POST['items'] : [];

if (empty($items)) {
  mysqli_close($condb);
  echo "<script type='text/javascript'>";
  echo "alert('ไม่พบรายการสินค้าเข้าคลัง กรุณาเพิ่มรายการก่อนกดยืนยัน');";
  echo "window.location = 'list_product.php';";
  echo "</script>";
  exit();
}

$now = date("Y-m-d H:i:s");

foreach ($items as $item) {
  $p_barCode = isset($item['p_barCode']) ? mysqli_real_escape_string($condb, $item['p_barCode']) : '';
  $add_qty = isset($item['add_qty']) ? (int)$item['add_qty'] : 0;

  if ($p_barCode === '' || $add_qty < 1) {
    continue; // ข้ามรายการที่ไม่สมบูรณ์
  }

  // ดึงข้อมูลสินค้าปัจจุบัน
  $sql_product = "SELECT p_name, p_price, p_qty FROM tbl_product WHERE p_barCode = '$p_barCode' LIMIT 1";
  $rs_product = mysqli_query($condb, $sql_product) or die("Error : ".mysqli_error($condb));
  $row = mysqli_fetch_assoc($rs_product);

  if (!$row) {
    continue; // ถ้าไม่พบสินค้า ให้ข้ามรายการนี้
  }

  $p_name = $row['p_name'];
  $p_price = $row['p_price'];
  $old_qty = (int)$row['p_qty'];
  $new_qty = $old_qty + $add_qty;

  // บันทึกประวัติการเพิ่มสต๊อก
  $sql_log = "INSERT INTO tbl_add_stocks(sto_id, p_barCode, p_name, p_price, p_qty, sto_add, sto_date_add)
  VALUES(
    '',
    '$p_barCode',
    '$p_name',
    '$p_price',
    '$old_qty',
    '$add_qty',
    '$now'
  )";
  $result_log = mysqli_query($condb, $sql_log) or die("Error in query: $sql_log " . mysqli_error($condb). "<br>$sql_log");

  // อัปเดตจำนวนสินค้าในตารางหลัก
  $sql_update = "UPDATE tbl_product SET p_qty = '$new_qty' WHERE p_barCode = '$p_barCode'";
  $result_update = mysqli_query($condb, $sql_update) or die("Error in query: $sql_update " . mysqli_error($condb). "<br>$sql_update");
}

mysqli_close($condb);

echo "<script type='text/javascript'>";
echo "window.location = 'list_product.php?add_stock=success';";
echo "</script>";

?>

