<?php 
include('../condb.php');
// echo "<pre>";
// print_r($_POST);
// print_r($_FILES);
// echo "</pre>";
// exit();

@$type = $_POST['type'];
if ($type == "add") {
	$t_name = mysqli_real_escape_string($condb,$_POST["t_name"]);
	$sql = "INSERT INTO tbl_type(t_name)VALUES('$t_name')";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");
	//exit();
	//mysqli_close($condb);

	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('เพิ่มข้อมูลเรียบร้อย');";
		echo "window.location = 'list_type.php?type_add=type_add';";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'list_type.php?type_add_error=type_add_error';";
		echo "</script>";
	}
}
elseif ($type == "edit") {
	// echo "<pre>";
	// print_r($_POST);
	// print_r($_FILES);
	// echo "</pre>";
	//  exit();
	$t_id = mysqli_real_escape_string($condb,$_POST["t_id"]);
	$t_name = mysqli_real_escape_string($condb,$_POST["t_name"]);
	$sql = "UPDATE tbl_type SET t_name='$t_name' WHERE t_id=$t_id";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb). "<br>$sql");
	
	if($result){
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		//echo "alert('แก้ไขข้อมูลเรียบร้อย');";
		echo "window.location = 'type_edit.php?t_id=$t_id&&type_edit=type_edit';";
		echo "</script>";
	}
	else{
		mysqli_close($condb);
		echo "<script type='text/javascript'>";
		echo "window.location = 'type_edit.php?t_id=$t_id&&type_edit_error=type_edit_error';";
		echo "</script>";
	}
}
else{
	$t_id  = mysqli_real_escape_string($condb,$_GET["t_id"]);
	$sql = "DELETE FROM tbl_type WHERE t_id=$t_id";
	$result = mysqli_query($condb, $sql) or die ("Error in query: $sql " . mysqli_error($condb));	
	mysqli_close($condb);
	echo "<script type='text/javascript'>";
	echo "window.location = 'list_type.php?type_del=type_del'; ";
	echo "</script>";	
}
?>