<?php
// กำหนดให้ PHP ไม่ต้องจำกัดเวลาในการทำงานและหน่วยความจำ (หากข้อมูลเยอะมาก)
set_time_limit(0);
ini_set('memory_limit', '1024M'); 

// 1. เชื่อมต่อฐานข้อมูล (สมมติว่าไฟล์นี้มีการเชื่อมต่อฐานข้อมูลเหมือน header.php)
// **คุณอาจต้อง include ไฟล์เชื่อมต่อฐานข้อมูลที่นี่**
// เช่น: include("connect_db.php");
require_once '../condb.php'; // เปลี่ยนเป็นชื่อไฟล์เชื่อมต่อฐานข้อมูลของคุณ

// 2. รับค่าช่วงวันที่จาก URL (GET request)
if (isset($_GET['date_s']) && isset($_GET['date_e'])) {
    $date_s = $_GET['date_s'] . " 00:00:00";
    $date_e = $_GET['date_e'] . " 23:59:59";
} else {
    // กำหนดค่าเริ่มต้นถ้าไม่มีการส่งค่ามา (อาจดึงข้อมูลวันนี้)
    $date_s = date("Y-m-d") . " 00:00:00";
    $date_e = date("Y-m-d") . " 23:59:59";
}

// 3. กำหนด Header เพื่อบังคับดาวน์โหลดเป็นไฟล์ Excel/CSV
header("Content-Type: text/csv; charset=utf-8");
header("Content-Disposition: attachment; filename=stock_report_" . date('Ymd_His') . ".csv");

// 4. สร้างไฟล์ CSV Output (เขียนไปยัง Output Buffer)
$output = fopen("php://output", "w");

// สำหรับการใช้งานภาษาไทยใน Excel (โดยเฉพาะเวอร์ชันเก่า) อาจต้องเพิ่ม BOM
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); 

// 5. กำหนดหัวตาราง (Headers)
fputcsv($output, array('รายการ', 'คงเหลือ', 'ราคา', 'ต้นทุน', 'รวมต้นทุน'));

// 6. สร้างคำสั่ง SQL 
// Note: เนื่องจากรายงานสต็อกไม่ได้ผูกกับช่วงเวลาในตาราง tbl_product โดยตรง
// แต่โค้ดเดิมไม่ได้ใช้ WHERE clause เลย ดังนั้นเราจะใช้ Query เดิม
$query_export = "SELECT
    tbl_product.p_name,
    tbl_product.p_qty, 
    tbl_product.p_price,
    tbl_product.p_cost,
    (tbl_product.p_qty * tbl_product.p_cost) AS Total_Cost
FROM tbl_product
GROUP BY tbl_product.p_name
ORDER BY tbl_product.p_name ASC"; // เปลี่ยนเป็น ORDER BY ที่เหมาะสม

$rs_export = mysqli_query($condb, $query_export) or die("Error : " . mysqli_error($condb));

// 7. Loop เพื่อดึงข้อมูลและใส่ลงใน CSV
while ($row = mysqli_fetch_assoc($rs_export)) {
    // เขียนข้อมูลแต่ละแถวลงในไฟล์ CSV
    fputcsv($output, $row);
}

// 8. ปิดไฟล์และยกเลิกการเชื่อมต่อ DB
fclose($output);
mysqli_close($condb);
exit();
?>