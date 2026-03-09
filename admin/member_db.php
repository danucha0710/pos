<?php 
include('../condb.php');
// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";
// $member = $_POST['member'];
// echo $member;
// exit();

// ตรวจสอบว่าเป็นการเพิ่ม/แก้ไข หรือการลบสมาชิก
$member = isset($_POST['member']) ? $_POST['member'] : '';

if ($member == "add"){
	$ref_l_id = mysqli_real_escape_string($condb,$_POST["ref_l_id"]);
	$mem_name = mysqli_real_escape_string($condb,$_POST["mem_name"]);
	$mem_username = mysqli_real_escape_string($condb,$_POST["mem_username"]);
	$mem_password = mysqli_real_escape_string($condb,(sha1($_POST["mem_password"])));
	$mem_phone = mysqli_real_escape_string($condb,$_POST["mem_phone"]);

	$date1 = date("Ymd_His");
	$numrand = (mt_rand());
	$mem_img = (isset($_POST['mem_img']) ? $_POST['mem_img'] : '');
	$upload=$_FILES['mem_img']['name'];
	if($upload !=''){ 
		$path="../mem_img/";
		$type = strrchr($_FILES['mem_img']['name'],".");
		$newname =$numrand.$date1.$type;
		$path_copy=$path.$newname;
		// $path_link="../mem_img/".$newname;
		move_uploaded_file($_FILES['mem_img']['tmp_name'],$path_copy);  
	}
	else{
		$newname='';
	}

	// ตรวจสอบไม่ให้ Username หรือ เบอร์โทร ซ้ำ
	$check = "SELECT mem_username, mem_phone FROM tbl_member WHERE mem_username = '$mem_username' OR mem_phone = '$mem_phone'";
	$result1 = mysqli_query($condb, $check) or die(mysqli_error($condb));
	$num = mysqli_num_rows($result1);

	if($num > 0){
		mysqli_close($condb);
		echo "<script>";
		// ถ้า Username หรือ เบอร์โทร ซ้ำ จะไม่ให้เพิ่มข้อมูลใหม่
		echo "window.location = 'list_mem.php?mem_add_error=mem_add_error'; ";
		echo "</script>";
		exit();
	}
	else{
		$sql = "INSERT INTO tbl_member(
		ref_l_id,
		mem_name,
		mem_username,
		mem_password,
		mem_phone,
		mem_img
		)
		VALUES(
		'$ref_l_id',
		'$mem_name',
		'$mem_username',
		'$mem_password',
		'$mem_phone',
		'$newname'
		)";
		$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");
	}
	//exit();

	if(isset($result) && $result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('เพิ่มข้อมูลเรียบร้อย');";
		echo "window.location = 'list_mem.php?mem_add=mem_add'; ";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'list_mem.php?mem_add_error=mem_add_error'; ";
		echo "</script>";
	}
}
elseif ($member == "edit"){
	// 	echo "<pre>";
	// print_r($_POST);
	// print_r($_FILES);
	// echo "</pre>";
	// $member = $_POST['member'];
	// echo $member;
	// exit();

	$mem_id = mysqli_real_escape_string($condb,$_POST["mem_id"]);
	$ref_l_id = mysqli_real_escape_string($condb,$_POST["ref_l_id"]);	
	$mem_name = mysqli_real_escape_string($condb,$_POST["mem_name"]);
	$mem_username = mysqli_real_escape_string($condb,$_POST["mem_username"]);
	$mem_password = mysqli_real_escape_string($condb,(sha1($_POST["mem_password"])));
	$mem_phone = mysqli_real_escape_string($condb,$_POST["mem_phone"]);
	$mem_img2 = mysqli_real_escape_string($condb,$_POST['mem_img2']);

	// เก็บเบอร์โทรเดิมไว้สำหรับอัปเดตคำสั่งซื้อย้อนหลัง
	$sql_old = "SELECT mem_phone FROM tbl_member WHERE mem_id=$mem_id";
	$result_old = mysqli_query($condb, $sql_old);
	$old_mem_phone = '';
	if ($result_old) {
		$row_old = mysqli_fetch_assoc($result_old);
		if ($row_old) {
			$old_mem_phone = $row_old['mem_phone'];
		}
	}

	$date1 = date("Ymd_His");
	$numrand = (mt_rand());
	$mem_img = (isset($_POST['mem_img']) ? $_POST['mem_img'] : '');
	$upload=$_FILES['mem_img']['name'];
	if($upload !=''){ 
		$path="../mem_img/";
		$type = strrchr($_FILES['mem_img']['name'],".");
		$newname =$numrand.$date1.$type;
		$path_copy=$path.$newname;
		// $path_link="mem_img/".$newname;
		move_uploaded_file($_FILES['mem_img']['tmp_name'],$path_copy);  
	}
	else{
		$newname=$mem_img2;
	}

	// ตรวจสอบไม่ให้ Username หรือ เบอร์โทร ซ้ำกับสมาชิกคนอื่น
	$check_edit = "SELECT mem_id FROM tbl_member WHERE (mem_username='$mem_username' OR mem_phone='$mem_phone') AND mem_id<>$mem_id";
	$result_check_edit = mysqli_query($condb, $check_edit) or die(mysqli_error($condb));
	if (mysqli_num_rows($result_check_edit) > 0) {
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'mem_edit.php?mem_id=$mem_id&&mem_edit_error=mem_edit_error'; ";
		echo "</script>";
		exit();
	}

	$sql = "UPDATE tbl_member SET 
	ref_l_id='$ref_l_id',
	mem_name='$mem_name',	
	mem_username='$mem_username',
	mem_password='$mem_password',
	mem_phone='$mem_phone',
	mem_img='$newname'
	WHERE mem_id=$mem_id";

	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");

	// ถ้าแก้ไขเบอร์โทร ให้ไปอัปเดตเบอร์ในตารางคำสั่งซื้อให้ตรงกัน
	if ($result && $old_mem_phone != '' && $old_mem_phone != $mem_phone) {
		$update_order_phone = "UPDATE tbl_order SET mem_phone='$mem_phone' WHERE mem_phone='$old_mem_phone'";
		mysqli_query($condb, $update_order_phone);
	}

	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('แก้ไขข้อมูลเรียบร้อย');";
		echo "window.location = 'mem_edit.php?mem_id=$mem_id&&mem_edit=mem_edit'; ";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'mem_edit.php?mem_id=$mem_id&&mem_edit_error=mem_edit_error'; ";
		echo "</script>";
	}
}elseif($member == "edit_profile"){	
	$mem_id = mysqli_real_escape_string($condb,$_POST["mem_id"]);
	$ref_l_id = mysqli_real_escape_string($condb,$_POST["ref_l_id"]);
	$mem_name = mysqli_real_escape_string($condb,$_POST["mem_name"]);
	$mem_username = mysqli_real_escape_string($condb,$_POST["mem_username"]);
	$mem_password = mysqli_real_escape_string($condb,(sha1($_POST["mem_password"])));
	$mem_phone = mysqli_real_escape_string($condb,$_POST["mem_phone"]);
	$mem_img2 = mysqli_real_escape_string($condb,$_POST['mem_img2']);

	// เก็บเบอร์โทรเดิมไว้สำหรับอัปเดตคำสั่งซื้อย้อนหลัง
	$sql_old = "SELECT mem_phone FROM tbl_member WHERE mem_id=$mem_id";
	$result_old = mysqli_query($condb, $sql_old);
	$old_mem_phone = '';
	if ($result_old) {
		$row_old = mysqli_fetch_assoc($result_old);
		if ($row_old) {
			$old_mem_phone = $row_old['mem_phone'];
		}
	}

	$date1 = date("Ymd_His");
	$numrand = (mt_rand());
	$mem_img = (isset($_POST['mem_img']) ? $_POST['mem_img'] : '');
	$upload=$_FILES['mem_img']['name'];
	if($upload !=''){ 
		$path="../mem_img/";
		$type = strrchr($_FILES['mem_img']['name'],".");
		$newname =$numrand.$date1.$type;
		$path_copy=$path.$newname;
		// $path_link="mem_img/".$newname;
		move_uploaded_file($_FILES['mem_img']['tmp_name'],$path_copy);  
	}
	else{
		$newname=$mem_img2;
	}

	// ตรวจสอบไม่ให้ Username หรือ เบอร์โทร ซ้ำกับสมาชิกคนอื่น
	$check_edit = "SELECT mem_id FROM tbl_member WHERE (mem_username='$mem_username' OR mem_phone='$mem_phone') AND mem_id<>$mem_id";
	$result_check_edit = mysqli_query($condb, $check_edit) or die(mysqli_error($condb));
	if (mysqli_num_rows($result_check_edit) > 0) {
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'edit_profile.php?mem_id=$mem_id&&mem_editp_error=mem_editp_error'; ";
		echo "</script>";
		exit();
	}

	$sql = "UPDATE tbl_member SET 
	ref_l_id='$ref_l_id',
	mem_name='$mem_name',
	mem_username='$mem_username',
	mem_password='$mem_password',
	mem_phone='$mem_phone',
	mem_img='$newname'
	WHERE mem_id=$mem_id" ;

	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");

	// ถ้าแก้ไขเบอร์โทร ให้ไปอัปเดตเบอร์ในตารางคำสั่งซื้อให้ตรงกัน
	if ($result && $old_mem_phone != '' && $old_mem_phone != $mem_phone) {
		$update_order_phone = "UPDATE tbl_order SET mem_phone='$mem_phone' WHERE mem_phone='$old_mem_phone'";
		mysqli_query($condb, $update_order_phone);
	}
	
	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('แก้ไขข้อมูลเรียบร้อย');";
		echo "window.location = 'edit_profile.php?mem_id=$mem_id&&mem_editp=mem_editp'; ";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'edit_profile.php?mem_id=$mem_id&&mem_editp_error=mem_editp_error'; ";
		echo "</script>";
	}
}
else{
	// ลบสมาชิก (รับค่าทาง GET)
	if(isset($_GET["mem_id"]) && is_numeric($_GET["mem_id"])) {
		$mem_id  = mysqli_real_escape_string($condb, $_GET["mem_id"]);
		$sql = "DELETE FROM tbl_member WHERE mem_id=$mem_id";
		$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb));	
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'list_mem.php?mem_del=mem_del'; ";
		echo "</script>";
	} else {
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'list_mem.php?mem_del_error=mem_del_error'; ";
		echo "</script>";
	}
}
?>