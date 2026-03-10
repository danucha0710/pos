<?php 
$menu = "sale";
include("header.php");

error_reporting(error_reporting() & ~E_NOTICE);
session_start();
$mem_id=$_SESSION['mem_id'];
// echo'<pre>';
// print_r($_SESSION);
//print_r($_POST);
//echo "</pre>";
// exit();

// ดึงหมายเลขพร้อมเพย์จากตารางตั้งค่า
$promptpay_no = '';
$promptpay_qr_url = '';

$sql_settings = "SELECT promptpay_no FROM tbl_settings LIMIT 1";
$result_settings = mysqli_query($condb, $sql_settings);
if ($result_settings && mysqli_num_rows($result_settings) > 0) {
  $row_settings = mysqli_fetch_assoc($result_settings);
  $promptpay_no = $row_settings['promptpay_no'];
}
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>รายการสินค้าที่สั่งซื้อทั้งหมด</h1>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">ยืนยันการสั่งซื้อ</h3>
        </div>
        <br>
        <div class="card-body">
          <div class="col-md-12">
            <div class="container">
              <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                  <form id="frmcart" name="frmcart" method="post" action="saveorder_a.php">
                    <table border="0" align="center" class="table table-hover table-bordered table-striped">
                      <tr>
                        <td width="1%" align="center">ลำดับ</td>
                        <td width="7%" align="center">รูปสินค้า</td>
                        <td width="40%" align="center">รายการสินค้า</td>
                        <td width="10%" align="center">ราคา</td>
                        <td width="10%" align="center">จำนวน</td>
                        <td width="15%" align="center">ราคารวม</td>
                      </tr>
                      <?php
                      $total=0;
                      $i = 0;
                      if(!empty($_SESSION['cart'])){
                        foreach($_SESSION['cart'] as $p_barCode=>$qty){
                          $sql = "SELECT * FROM tbl_product where p_barCode=$p_barCode";
                          $query = mysqli_query($condb, $sql);
                          $row = mysqli_fetch_array($query);
                          $sum = $row['p_price'] * $qty;//เอาราคาสินค้ามา * จำนวนในตระกร้า
                          $total += $sum; //ราคารวม ทั้ง ตระกร้า
						  $pqty = $row['p_qty'];//ประกาศตัวแปรจำนวนสินค้าใน stock
                          echo "<tr>";
                          echo  "<td align='center'>" . (++$i) . "</td>";
                          echo  "<td align='center'>" . "<img src='../p_img/" . $row['p_img'] . "' width='100%'>" . "</td>";
                          $p_name_display = preg_replace('/\s*\d{1,2}\/\d{1,2}\/\d{2,4}.*/u', '', $row["p_name"]);
                          echo  "<td align='left'>" . $p_name_display . "<br><b>" . "สต๊อก ".$row['p_qty'] . " รายการ" . "</b></td>";
                          echo  "<td align='right'>" . number_format($row["p_price"],2) . "</td>";
                          echo  "<td align='center'>" . $qty . "</td>";
                          echo  "<td align='right'>" . number_format($sum,2) . "</td>";
                          echo "</tr>";
                          //remove product
                        }
                        echo "<tr>";
                        echo 		"<td colspan='4' align='right'><b>ราคารวม</b></td>";
  	                    echo 		"<td colspan='2' align='left'" . "<b>" . number_format($total, 2) . "</b></td>";
                        echo "</tr>";
                        // สร้าง URL สำหรับ QR พร้อมเพย์ (ใช้ยอดรวมทั้งหมด)
                        if (!empty($promptpay_no)) {
                          $pay_amount_raw = number_format($total, 2, '.', '');
                          $promptpay_qr_url = "https://promptpay.io/".urlencode($promptpay_no)."/".$pay_amount_raw.".png";
                        }
                      }
                      ?>
                    </table>

                    <?php if($mem_id != ''){ ?>
                      <div class="row">
                        <label for="order_status" class="col-sm-3 col-form-label">รูปแบบการชำระเงิน</label>
                        <div class="col-sm-5">
                          <select class="form-control" name="order_status" id="order_status" onchange = "show(this.value)" required>
                            <option value="">--เลือกรูปแบบการชำระเงิน--</option>
                            <option value="1">ชำระเงินสด</option>
                            <option value="2">โอนเงินผ่านบัญชีธนาคาร</option>
                            <option value="3">สินเชื่อ (หักเงินเดือน)</option>
                          </select>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <label style="display: none" class="col-sm-3 col-form-label" id="select_name_label">ชื่อสมาชิก</label>
                        <div class="col-sm-5">
                          <select style="display: none" class="form-select" name="select_name" id="select_name">
                            <?php
                              $query_mem = "SELECT mem_name, mem_phone FROM tbl_member WHERE ref_l_id=4";
                              $rs_mem = mysqli_query($condb, $query_mem); 
                            ?>
                            <option value="">ค้นหาสมาชิก (พิมพ์ชื่อหรือเบอร์โทร)</option>
                            <?php foreach ($rs_mem as $row_mem) { ?>
                            <option value="<?php echo $row_mem['mem_phone'];?>">
                              <?php echo $row_mem['mem_name'] . ' (' . $row_mem['mem_phone'] . ')';?>
                            </option>
                            <?php } ?> 
                          </select>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <label class="col-sm-3 col-form-label">ยอดเงินที่ต้องชำระ</label>
                        <div class="col-sm-5">
                          <input type="number" id="pay_amount" name="pay_amount" class="form-control" value="<?php echo number_format($total, 2 ,'.', ''); ?>" readonly>
                        </div>
                      </div>
                      <div class="row mt-3" id="pay_row2">
                        <label class="col-sm-3 col-form-label">ยอดเงินที่รับชำระ</label>
                        <div class="col-sm-5">
                          <input type="number" step="0.01" min="0" id="pay_amount2" name="pay_amount2" class="form-control" required>
                        </div>
                      </div>
                      <div class="row mt-3" id="qr_row" style="display: none;">
                        <div class="col-sm-9 offset-sm-1 text-center">
                          <?php if (!empty($promptpay_qr_url)) { ?>
                            <img 
                              id="promptpay_qr" 
                              src="<?php echo $promptpay_qr_url; ?>" 
                              alt="PromptPay QR"
                              class="img-fluid border rounded"
                              style="max-width: 220px;"
                            >
                            <small class="form-text text-muted d-block mt-2">
                              สแกน QR Code เพื่อชำระเงินผ่านพร้อมเพย์
                            </small>
                          <?php } else { ?>
                            <div class="alert alert-warning mb-0">
                              ยังไม่ได้ตั้งค่าหมายเลขพร้อมเพย์ในหน้า Setting
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="row mt-3">
                        <label class="col-sm-3 col-form-label">หมายเลขโทรศัพท์ ลูกค้า</label>
                        <div class="col-sm-5">
                          <input type="tel" name="mem_phone" placeholder="เบอร์โทร หรือ รหัสนักศึกษา" class="form-control">
                        </div>
                      </div>
                      <div class="row mt-3">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-5">
                          <input type="hidden" name="mem_id" value="<?php echo $mem_id; ?>">
                          <button type="submit" class="btn  btn-primary btn-block" >ยืนยันการสั่งซื้อ</button>
                        </div>
                      </div>
                    <?php }else{ ?>
                      <a href="../index.php" class="btn btn-success" onclick="window.print()">กรุณาเข้าสู่ระบบ</a>
                    <?php } ?>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>                    
    </section>
    <!-- /.content -->

<?php include('footer.php'); ?>
  
<script>
  // เคลียร์และสลับฟิลด์ตามรูปแบบการชำระเงิน
  const selectNameLabel = document.getElementById('select_name_label');
  const selectName = document.getElementById('select_name');
  const memPhoneInput = document.querySelector('input[name="mem_phone"]');
  const payAmountInput = document.getElementById('pay_amount');
  const payAmount2Input = document.getElementById('pay_amount2');
  const payRow2 = document.getElementById('pay_row2');
  const qrRow = document.getElementById('qr_row');
  let selectNameWrapper = null;

  function show(value) {
    // ล้างค่าก่อนทุกครั้งที่เปลี่ยนรูปแบบการชำระเงิน
    if (selectName) selectName.value = '';
    if (memPhoneInput) memPhoneInput.value = '';

    if (value == 3) {
      // กรณีสินเชื่อ: ใช้เลือกจากรายชื่อสมาชิก
      if (selectNameLabel) selectNameLabel.style.display = "inline";
      if (selectName) {
        // เรียกใช้ dselect แค่ครั้งเดียว
        if (!selectName.dataset.dselectInitialized) {
          dselect(selectName, { search: true });
          selectName.dataset.dselectInitialized = "true";
          // หา wrapper ที่ dselect สร้างขึ้นมา
          selectNameWrapper = selectName.closest('.dselect-wrapper') || selectName.parentElement;
        }
        // แสดง wrapper ของ dselect (ถ้ามี)
        if (!selectNameWrapper) {
          selectNameWrapper = selectName.closest('.dselect-wrapper') || selectName.parentElement;
        }
        if (selectNameWrapper) {
          selectNameWrapper.style.display = "block";
        } else {
          selectName.style.display = "block";
        }
      }

      // ไม่ต้องกรอกเบอร์โทรเอง และยอดรับชำระ = ยอดที่ต้องชำระ
      if (memPhoneInput) memPhoneInput.readOnly = true;
      if (payAmountInput && payAmount2Input) {
        const payAmount = parseFloat(payAmountInput.value) || 0;
        payAmount2Input.value = payAmount.toFixed(2);
        payAmount2Input.required = false;
      }
      if (payRow2) payRow2.style.display = "none";
      if (qrRow) qrRow.style.display = "none";
    } else if (value == 2) {
      // โอนเงินผ่านบัญชีธนาคาร: แสดง QR พร้อมเพย์, ไม่ใช้รายชื่อสมาชิก
      if (selectNameLabel) selectNameLabel.style.display = "none";
      if (!selectNameWrapper && selectName) {
        selectNameWrapper = selectName.closest('.dselect-wrapper') || selectName.parentElement;
      }
      if (selectNameWrapper) {
        selectNameWrapper.style.display = "none";
      } else if (selectName) {
        selectName.style.display = "none";
      }
      if (memPhoneInput) memPhoneInput.readOnly = false;
      // ยอดรับชำระ = ยอดที่ต้องชำระ และซ่อนช่องกรอก
      if (payAmountInput && payAmount2Input) {
        const payAmount = parseFloat(payAmountInput.value) || 0;
        payAmount2Input.value = payAmount.toFixed(2);
        payAmount2Input.required = false;
      }
      if (payRow2) payRow2.style.display = "none";
      if (qrRow) qrRow.style.display = "block";
    } else {
      // กรณีอื่น ๆ: ซ่อนรายชื่อสมาชิก และ QR ให้กรอกเบอร์โทรได้ตามปกติ
      if (selectNameLabel) selectNameLabel.style.display = "none";
      if (!selectNameWrapper && selectName) {
        selectNameWrapper = selectName.closest('.dselect-wrapper') || selectName.parentElement;
      }
      if (selectNameWrapper) {
        selectNameWrapper.style.display = "none";
      } else if (selectName) {
        selectName.style.display = "none";
      }
      if (memPhoneInput) memPhoneInput.readOnly = false;
      // เงินสด: แสดงช่องยอดที่รับชำระให้กรอกเอง
      if (payAmount2Input) {
        payAmount2Input.value = '';
        payAmount2Input.required = true;
      }
      if (payRow2) payRow2.style.display = "flex";
      if (qrRow) qrRow.style.display = "none";
    }
  }
</script>

<script>
  document.getElementById("frmcart").addEventListener("submit", function (event) {
    const payAmount = parseFloat(document.getElementById("pay_amount").value);
    const payAmount2 = parseFloat(document.getElementById("pay_amount2").value);
	const orderStatus = parseFloat(document.getElementById("order_status").value);

	if (orderStatus != 3) {
		if (payAmount2 < payAmount) {
		  event.preventDefault(); // ยกเลิกการส่งฟอร์ม
		  alert("ยอดเงินที่รับชำระ น้อยกว่า ยอดเงินที่ต้องชำระ");
		} 
	}
  });
</script>

</body>
</html>