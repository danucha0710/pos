<?php 
include('../condb.php');
// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";
// exit();

@$brand = $_POST['brand'];
if ($brand == "add") {
	$b_name = mysqli_real_escape_string($condb,$_POST["b_name"]);
	$sql = "INSERT INTO tbl_brand(b_name)VALUES('$b_name')";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");

	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('เพิ่มข้อมูลเรียบร้อย');";
		echo "window.location = 'list_brand.php?brand_add=brand_add'; ";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'list_brand.php?brand_add_error=brand_add_error'; ";
		echo "</script>";
	}
}
elseif($brand == "edit") {
	// echo "<pre>";
	// print_r($_POST);
	// print_r($_FILES);
	// echo "</pre>";
	//  exit();
	$b_id = mysqli_real_escape_string($condb,$_POST["b_id"]);
	$b_name = mysqli_real_escape_string($condb,$_POST["b_name"]);
	$sql = "UPDATE tbl_brand SET b_name='$b_name' WHERE b_id=$b_id" ;
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");

	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('แก้ไขข้อมูลเรียบร้อย');";
		echo "window.location = 'brand_edit.php?b_id=$b_id&&brand_edit=brand_edit'; ";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'brand_edit.php?b_id=$b_id&&brand_edit_error=brand_edit_error'; ";
		echo "</script>";
	}
}
else{
	$b_id  = mysqli_real_escape_string($condb,$_GET["b_id"]);
	$sql = "DELETE FROM tbl_brand WHERE b_id=$b_id";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb));	
	mysqli_close($condb);
	echo "<script type='text/javascript'>";
	echo "window.location = 'list_brand.php?brand_del=brand_del'; ";
	echo "</script>";	
}

?>