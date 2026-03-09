<?php 
include('../condb.php');
// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";
// exit();

@$product = $_POST['product'];
if (!empty($_POST['p_barCode'])) {
	$p_barCode = mysqli_real_escape_string($condb,$_POST["p_barCode"]);
	$t_id = mysqli_real_escape_string($condb,$_POST["t_id"]);
	$b_id = mysqli_real_escape_string($condb,$_POST["b_id"]);
	$p_name = mysqli_real_escape_string($condb,$_POST["p_name"]);
	$p_detail = mysqli_real_escape_string($condb,$_POST["p_detail"]);
	$p_cost = mysqli_real_escape_string($condb,$_POST["p_cost"]);
	$p_price = mysqli_real_escape_string($condb,$_POST["p_price"]);
	$p_qty = mysqli_real_escape_string($condb,$_POST["p_qty"]);

	$date1 = date("Ymd_His");
	$numrand = (mt_rand());
	$p_img = (isset($_POST['p_img']) ? $_POST['p_img'] : '');
	$upload=$_FILES['p_img']['name'];
	if($upload !='') { 
		$path="../p_img/";
		$type = strrchr($_FILES['p_img']['name'],".");
		$newname =$numrand.$date1.$type;
		$path_copy=$path.$newname;
		// $path_link="../p_img/".$newname;
		move_uploaded_file($_FILES['p_img']['tmp_name'],$path_copy);  
	}
	else{
		$newname='';
	}

	$sql = "INSERT INTO tbl_product(p_barCode, t_id, b_id, p_name, p_detail, p_cost, p_price, p_qty, p_img) VALUES(
	'$p_barCode',
	'$t_id',
	'$b_id',
	'$p_name',
	'$p_detail',
	'$p_cost',
	'$p_price',
	'$p_qty',
	'$newname')";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");	
	//exit();

	if($result){ 
		mysqli_close($condb);
?>

		<script type='text/javascript'>
			alert('เพิ่มสินค้าเรียบร้อยแล้ว\nกรุณากดรีโหลดหน้าเว็บไซต์อีกครั้ง');
			window.open('','_self',''); 
			window.close();
		</script>
<?php	}
	else{
		mysqli_close($condb);
?>
		<script type='text/javascript'>
			alert('ไม่สามารถเพิ่มสินค้าได้\nกรุณากดรีโหลดหน้าเว็บไซต์อีกครั้ง');
			window.close();
		</script>
<?php
	}
}
?>