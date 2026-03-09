<?php 
if($st==1){
	echo '<font color="green">';
	echo 'ชำระเงินสด';
	echo '</font>';
}
elseif ($st==2) {
	echo '<font color="blue">';
	echo 'โอนเงินผ่านบัญชีธนาคาร';
	echo '</font>';
}
elseif ($st==3) {
	echo '<font color="red">';
	echo 'สินเชื่อ(หักเงินเดือน)';
	echo '</font>';
}
?>