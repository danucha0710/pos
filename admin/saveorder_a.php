<?php
session_start();
include("../condb.php");
error_reporting( error_reporting() & ~E_NOTICE );
 
$mem_id=$_SESSION['mem_id'];
if (@$_SESSION['mem_id'] == ''){
  	session_destroy();
?>
  	<script type="text/javascript">
  		alert('บันทึกข้อมูลไม่สำเร็จ');
  		window.location="index.php";
  	</script>

<?php } 

//echo "<pre>";
//print_r($_SESSION);
//print_r($_POST);
//echo "</pre>";
?>

<!--สร้างตัวแปรสำหรับบันทึกการสั่งซื้อ -->
<?php
	if(empty($_POST["order_status"])) {
		$order_status = 1;
		$mem_phone = "0000000000";
	}
	if(empty($_POST["select_name"])) {
		$select_name = "0000000000";
	}
	else {
		$select_name = $_POST["select_name"];
	}
	$mem_id = $_POST["mem_id"];
	$order_status = $_POST["order_status"];
	$pay_amount = $_POST["pay_amount"];		//ยอดเงินที่ต้องชำระ
	$pay_amount2 = $_POST["pay_amount2"];	//ยอดเงินที่รับชำระ
	$order_date = Date("Y-m-d G:i:s");
	if($order_status == 3) {
		$mem_phone = $select_name;	//เบอร์โทรลูกค้า
		$pay_amount2 = $pay_amount;
	}
	elseif($order_status != 3 and !empty($_POST["mem_phone"])) {
		$mem_phone = $_POST["mem_phone"];	//เบอร์โทรลูกค้า
	}
	elseif($order_status != 3 and empty($_POST["mem_phone"])) {
		$mem_phone = "0000000000";	//เบอร์โทรลูกค้า
	}
	else {
		$mem_phone = "0000000000";
	}

	//บันทึกการสั่งซื้อลงใน order
	mysqli_query($condb, "BEGIN"); 
	$sql1	= "INSERT INTO tbl_order 
	VALUES(NULL, 
	'$mem_id',
	'$mem_phone',
	'$order_status',  
	'$pay_amount', 
	'$pay_amount2',
	'$order_date'
	)";
	$query1	= mysqli_query($condb, $sql1) or die ("Error1 : ".mysqli_error($condb));

	// echo $sql1;
	// echo "<hr/>";
	// exit();

	//ฟังก์ชั่น MAX() จะคืนค่าที่มากที่สุดในคอลัมน์ที่ระบุ
	$sql2 = "SELECT MAX(order_id) as order_id 
	FROM tbl_order 
	WHERE mem_id='$mem_id'";
	$query2	= mysqli_query($condb, $sql2) or die ("Error2 : ".mysqli_error($condb));
	$row = mysqli_fetch_array($query2);
	$order_id = $row["order_id"];

	// echo $order_id;
	// exit();

	//PHP foreach() เป็นคำสั่งเพื่อนำข้อมูลออกมาจากตัวแปรที่เป็นประเภท array โดยสามารถเรียกค่าได้ทั้ง $key และ $value ของ array
	// กำหนดค่าเริ่มต้นให้ $query4 ป้องกัน Warning เมื่อไม่มีสินค้าในตะกร้า
	$query4 = true;
	foreach($_SESSION['cart'] as $p_barCode=>$qty) {
		//query3 เพื่อให้รู้ว่า ใน ตระกร้าสินค้า มีการสั่งซื้อสินค้าอะไรบ้าง เพื่อให้เอาราคาสินค้าต่อหน่วย มาคูณกับ จำนวนสั่งซื้อทั้งหมดและเก็บลงตาราง order_detail
		$sql3	= "SELECT * FROM tbl_product WHERE p_barCode=$p_barCode";
		$query3	= mysqli_query($condb, $sql3) or die ("Error3 : ".mysqli_error($condb));
		$row3	= mysqli_fetch_array($query3);
		$total	= $row3['p_price']*$qty;
		$count=mysqli_num_rows($query3);	//นับว่ามีการqueryได้ไหม

		//echo"<pre>";
		//print_r($row3);
		//echo"</pre>";
		//echo $total;
		//exit();
		
		$sql4	= "INSERT INTO tbl_order_detail 
				   VALUES (NULL,
				   '$order_id', 
				   '$p_barCode', 
				   '$qty', 
				   '$total'
				   )";
		$query4	= mysqli_query($condb, $sql4) or die ("Error4 : ".mysqli_error($condb));

		//ตัดสต๊อก
		for($i=0; $i<$count; $i++){
		  	$have =  $row3['p_qty'];
		  
		  	$stc = $have - $qty;
		  
		  	$sql5 = "UPDATE tbl_product SET p_qty=$stc WHERE  p_barCode=$p_barCode";
		  	$query5 = mysqli_query($condb, $sql5) or die ("Error5 : ".mysqli_error($condb));  
		}
	}

	//เพิ่ม point (เฉพาะกรณีที่มีสมาชิกเบอร์นี้จริง)
	$sql6	= "SELECT mem_point FROM `tbl_member` WHERE mem_phone='$mem_phone'";
	$query6	= mysqli_query($condb, $sql6) or die ("Error6 : ".mysqli_error($condb));
	if ($query6 && mysqli_num_rows($query6) > 0) {
		$row6	= mysqli_fetch_array($query6);
		$point	= $row6['mem_point'] + $pay_amount;
		$sql7	= "UPDATE tbl_member SET mem_point=$point WHERE mem_phone='$mem_phone'";
		$query7	= mysqli_query($condb, $sql7) or die ("Error7 : ".mysqli_error($condb));
	}

	//เงินสินเชื่อ
	if($order_status == 3) {
		$query_mem = "SELECT mem_id FROM `tbl_member` WHERE mem_phone='$mem_phone'";
		$rs_mem = mysqli_query($condb, $query_mem); 
		$rowcount = mysqli_num_rows($rs_mem);
		if($rowcount == 1) {
			$row = mysqli_fetch_array($rs_mem);
			$credit_mem = $row['mem_id'];
			$sql8 = "INSERT INTO `tbl_credit` VALUES (NULL, '$credit_mem', '$pay_amount', '$order_date')";
			$query8	= mysqli_query($condb, $sql8) or die ("Error8 : ".mysqli_error($condb));
			mysqli_free_result($rs_mem);
		}
		//echo $sql8;
		//exit();
	}

	//ถ้าทำงานครบตามเงื่อนไข
	if($query1 && $query4){
		mysqli_query($condb, "COMMIT");	//จะ COMMIT บันทึกสำเร็จคือบันทึก sql1 กับ sql4 แล้ว
		$msg = "บันทึกข้อมูลเรียบร้อยแล้ว ";

		foreach($_SESSION['cart'] as $p_barCode){	//วนซ้ำ $_SESSION['cart'] เพื่อเช็คเตรียมจะunset
			unset($_SESSION['cart']);	//unset($_SESSION['cart'][$p_barCode]);
		}
	}
	else{
		mysqli_query($condb, "ROLLBACK");  
		$msg = "บันทึกข้อมูลไม่สำเร็จ กรุณาติดต่อเจ้าหน้าที่";	
	}
mysqli_close($condb);
//exit();
?>

<script type="text/javascript">
	window.location ='index.php?order_id=<?php echo $order_id; ?>&act=view&&save_ok=save_ok';
</script>