<?php
	error_reporting( error_reporting() & ~E_NOTICE );
session_start();

// อ่านค่าจาก $_GET แบบปลอดภัย ป้องกัน Undefined array key ใน PHP 8.2
$p_barCode = isset($_GET['p_barCode']) ? mysqli_real_escape_string($condb, $_GET['p_barCode']) : '';
$act = isset($_GET['act']) ? mysqli_real_escape_string($condb, $_GET['act']) : '';

// เพิ่มสินค้าเข้าตะกร้า
if($act === 'add' && !empty($p_barCode)){ //เช็คว่า act=='add' และ p_barCode ไม่ใช่ค่าว่างให้ทำเงื่อนไข
		if(isset($_SESSION['cart'][$p_barCode])) {//ถ้าเจอ p_barCode ในตระกร้า 
			$_SESSION['cart'][$p_barCode]++;//ให้เพิ่มทีละ 1 
		}
		else {//ถ้าไม่เจอให้สินค้าที่ส่งมานั้น  
			$_SESSION['cart'][$p_barCode]=1;//ให้สินค้านั้นเท่ากับ 1
		}
	}
	// session_destroy();
	// header("location: cart.php");

	// echo'<pre>';
	// print_r($_SESSION);
	// echo'</pre>';
	// exit();

	// ยกเลิกการสั่งซื้อ
	if($act === 'remove' && !empty($p_barCode)){  //ยกเลิกการสั่งซื้อ
		unset($_SESSION['cart'][$p_barCode]);
	}
	// อัปเดตจำนวนในตะกร้า (ไม่พึ่ง query string, แค่เช็ค POST ก็พอ)
	if(!empty($_POST['amount']) && is_array($_POST['amount'])){
		foreach($_POST['amount'] as $p_barCode => $amount){
			$_SESSION['cart'][$p_barCode] = (int)$amount;
		}
	}
?>

<form id="frmcart" name="frmcart" method="post" action="">
	<h4>รายการสั่งซื้อ</h4>
	<br>
  	<table border="0" align="center" class="table table-hover table-bordered table-striped">
    	<tr>
      		<td width="1%" ></td>
      		<td width="15%" >สินค้า</td>
      		<td width="5%" >ราคา</td>
      		<td width="5%" >จำนวน</td>
      		<td width="5%" >ราคารวม</td>
      		<td width="3%" ></td>
    	</tr>
<?php
$total=0;
$eror_count=0;
$no = 0;
if(!empty($_SESSION['cart'])){
	foreach($_SESSION['cart'] as $p_barCode=>$qty){
		$sql = "SELECT * FROM `tbl_product` WHERE p_barCode=$p_barCode";
		$query = mysqli_query($condb, $sql);
		@$rowcount = mysqli_num_rows($query);
		if($rowcount > 0) {
			$row = mysqli_fetch_array($query);
			$sum = $row['p_price'] * $qty;//เอาราคาสินค้ามา * จำนวนในตระกร้า
			$total += $sum; //ราคารวม ทั้ง ตระกร้า
			echo "<tr>";
			echo 	"<td align='center'>".(++$no)."</td>";
			$displayName = preg_replace('/\s*\d{1,2}\/\d{1,2}\/\d{2,4}.*/u', '', $row["p_name"]);
			echo 	"<td align='left'>".$displayName."<br><b>"."สต๊อก ".$row['p_qty']."</b></td>";
			echo 	"<td align='right'>" .number_format($row["p_price"],2) . "</td>";
			echo 	"<td align='right'>"; 
			$pqty = $row['p_qty']; //ประกาศตัวแปรจำนวนสินค้าใน stock
			echo 	"<input type='number' name='amount[$p_barCode]' value='$qty' size='2' class='form-control' min='0' max='$pqty' onchange=\"document.getElementById('frmcart').submit();\"/></td>";
			echo 	"<td align='right'>".number_format($sum,2)."</td>";
			//remove product
			echo 	"<td align='center'><a href='list_l.php?p_barCode=$p_barCode&act=remove' class='btn btn-danger btn-xs'>ลบ</a></td>";
			echo "</tr>";
			if(number_format($row['p_qty']) < $qty){
				$eror_count++;
			}
		}
		else{
			echo "<tr>";
			echo 	"<td align='center'>".(++$no)."</td>";
			echo 	"<td align='left' colspan='3'><b>ไม่มีสินค้าในฐานข้อมูล</b><br>รหัสบาร์โค้ด ".$p_barCode."</td>";
?>

			 		<td align='center'><a href='add_product.php' onclick="window.open('/pos/admin/add_product.php?barCode=<?php echo $p_barCode ?>', 'newwindow', 'width=600,height=400'); return false;" class='btn btn-warning btn-xs'>เพิ่ม</a></td>
<?php
			echo 	"<td align='center'><a href='list_l.php?p_barCode=$p_barCode&act=remove' class='btn btn-danger btn-xs'>ลบ</a></td>";
			echo "</tr>";
		}
	}
	echo 	"<tr>";
  	echo 		"<td colspan='4' align='right' bgcolor='#CEE7FF'><b>ราคารวม</b></td>";
  	echo 		"<td colspan='2' align='left' bgcolor='#CEE7FF'>"."<b>".number_format($total, 2)."</b></td>";
	echo 	"</tr>";
}
?>
	</table>
	<p align="right">
		<input type="hidden" name="t_id" value="<?php echo $t_id;?>">
		<input type="hidden" name="b_id" value="<?php echo $b_id;?>">
<?php
	if($eror_count > 0) {	
?>
		<input type="submit" value="สินค้าในสต๊อกไม่เพียงพอต่อคำสั่งซื้อ" class="btn btn-danger">
<?php
	}
	else {
?>
		<input type="button" name="Submit2" value="ทำรายการต่อไป" onclick="window.location='confirm_a.php';" class="btn btn-primary">
<?php
	}
?>

	</p>
</form>