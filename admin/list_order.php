<?php
// กำหนดจำนวนรายการสูงสุด (ยังไม่ใช้ LIMIT แต่เผื่อในอนาคต)
$max = 500;

// อ่านค่าช่วงวันที่จาก POST (มาจาก Modal ใน index.php)
if (!empty($_POST['date_s']) && !empty($_POST['date_e'])) {
    $date_s = $_POST['date_s'] . " 00:00:00";
    $date_e = $_POST['date_e'] . " 23:59:59";
    $where_clause = " WHERE o.order_date BETWEEN '$date_s' AND '$date_e'";
} else {
    // ถ้าไม่เลือกช่วงวันที่ ให้ใช้ "วันนี้" เป็นค่าเริ่มต้น
    $where_clause = " WHERE DATE(o.order_date) = CURDATE()";
}

$order_by_clause = " ORDER BY o.order_id DESC";
$limit_clause = ""; // ยังไม่ใช้ LIMIT

// สร้างคำสั่ง SQL หลัก
$query_my_order = "SELECT o.*, m.mem_name 
                   FROM tbl_order AS o
                   INNER JOIN tbl_member AS m ON o.mem_id = m.mem_id"
                   . $where_clause 
                   . $order_by_clause 
                   . $limit_clause
                   or die("Error : " . mysqli_error($condb));

$rs_my_order = mysqli_query($condb, $query_my_order);
//echo ($query_my_order);//test query
?>

<div class="form-group mb-3 d-flex justify-content-end">
    <span class="ms-2 text-muted">
        <?php 
          if (!empty($_POST['date_s']) && !empty($_POST['date_e'])) {
            echo "แสดงตามช่วงวันที่ที่เลือก";
          } else {
            echo "แสดงรายการของวันนี้ (หากต้องการช่วงอื่น ให้ใช้ปุ่มค้นหาตามช่วงเวลา)";
          }
        ?>
    </span>
</div>

<table id="tableSearch" class="table table-bordered table-hover table-striped">
  <thead>
    <tr class="danger">
      <th width="5%"><center>ลำดับ</center></th>
      <th width="20%"><center>พนักงานขาย</center></th>
      <th width="20%"><center>จำนวนเงิน</center></th>
      <th width="20%"><center>สถานะ</center></th>
      <th width="20%"><center>วันที่ขาย</center></th>
      <th width="15%"><center>การจัดการ</center></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rs_my_order as $rs_order) { ?> 
    <tr>
      <td align="center"><?php echo $rs_order['order_id']; ?></td>
      <td align="left"><?php echo $rs_order['mem_name']; ?></td>
      <td align="right"><?php echo $rs_order['pay_amount']; ?></td>
      <td align="left"><?php $st= $rs_order['order_status']; include('mystatus.php'); ?></td>
      <td align="left"><?php echo date('d/m/Y H:i:s', strtotime($rs_order['order_date'])); ?></td>
      <td align="center"><a href="index.php?order_id=<?php echo $rs_order['order_id']; ?>&act=view" target="_blank" class="btn btn-success btn-xs"><i class="nav-icon fas fa-clipboard-list"></i> เปิดดูรายการ</a></td>
    </tr>
    <?php } ?>
  </tbody>
</table>