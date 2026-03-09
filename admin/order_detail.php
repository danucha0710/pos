<?php
	$order_id = mysqli_real_escape_string($condb, $_GET['order_id']);
	//echo $order_id;
	$sqlpay = "SELECT tbl_order_detail.*,tbl_product.*, tbl_member.mem_name, tbl_order.order_date, tbl_order.order_status, tbl_order.pay_amount2, tbl_order.mem_phone FROM `tbl_order_detail`
	INNER JOIN `tbl_product` ON tbl_order_detail.p_barCode=tbl_product.p_barCode
	INNER JOIN `tbl_order` ON tbl_order_detail.order_id=tbl_order.order_id
	INNER JOIN `tbl_member` ON tbl_order.mem_id=tbl_member.mem_id
	WHERE tbl_order_detail.order_id=$order_id";

	$querypay = mysqli_query($condb, $sqlpay) or die("Error : ".mysqli_error($condb));
	$rowmember=mysqli_fetch_array($querypay);
	$st=$rowmember['order_status'];
?>

<center>
	<h4>รายการสั่งซื้อ</h4><br>
</center>
<h5>
	หมายเลขคำสั่งซื้อ : <?php echo $order_id; ?><br>
	วันที่สั่งซื้อ : <?php echo date('d/m/y',strtotime($rowmember['order_date'])); ?><br>
	ผู้ทำรายการ : <?php echo $rowmember['mem_name']; ?><br>
	รูปแบบชำระเงิน : <?php include('mystatus.php'); ?><br>
	หมายเลขโทรศัพท์ลูกค้า : <?php echo $rowmember['mem_phone']; ?><br>
</h5>

<table border="0" align="center" class="table table-hover table-bordered table-striped mt-3">
	<tr>
  		<td width="5%" align="center">ลำดับ</td>
      	<td width="10%" align="center">บาร์โค้ด</td>
      	<td width="35%" align="center">รายการสินค้า</td>
      	<td width="10%" align="center">ราคา</td>
      	<td width="10%" align="center">จำนวน</td>
      	<td width="15%" align="center">ราคารวม</td>
    </tr>
	<?php
		$total=0;
		foreach($querypay as $rspay){
			$total += $rspay['total']; //ราคารวมทั้งตระกร้า
			echo "<tr>";
			echo 	"<td align='center'>" . @$no+=1 . "</td>";
			echo 	"<td align='center'>" . $rspay["p_barCode"] . "</td>";
			echo 	"<td>" . $rspay["p_name"] . "</td>";
			echo 	"<td align='right'>" .number_format($rspay["p_price"], 2) . "</td>";
			echo 	"<td align='right'>"; 
			echo 	"<input type='number' name='p_c_qty' value='$rspay[p_c_qty]' size='2' class='form-control' disabled></td>";
			echo 	"<td align='right'>".number_format($rspay['total'], 2)."</td>";
		}
		include('../convertnumtothai.php');
	?>

	<tr>
  		<td align='right' colspan="4">
  			<b>ราคารวม (<?php echo Convert($total); ?>)</b><br>
  			<b>ยอดเงินที่รับชำระ (<?php echo Convert($rowmember['pay_amount2']); ?>)</b><br>
  			<?php 
				$pay_amount3 = $rowmember['pay_amount2'] - $total;
				//echo $pay_amount3;
			?>
  			<b>เงินทอน (<?php echo Convert($pay_amount3); ?>)</b>
    	</td>
  		<td align='left' colspan='2'>
  			<b><?php echo number_format($total, 2);?> บาท</b><br>
  			<b><?php echo number_format($rowmember['pay_amount2'], 2);?> บาท</b><br>
  			<b><?php echo number_format($pay_amount3, 2);?> บาท</b>
  		</td>
	</tr>
</table>
<br>
<a href="#" target="" class="btn btn-success" onclick="window.print()">Print</a>