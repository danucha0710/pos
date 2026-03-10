<?php 
$menu = "sale";
include("header.php");

// รับค่าพารามิเตอร์
$rawBarcode = !empty($_GET['p_barCode']) ? mysqli_real_escape_string($condb, $_GET['p_barCode']) : '';
$act        = !empty($_GET['act']) ? $_GET['act'] : '';

// กำหนด keyword สำหรับค้นหา:
// - กรณี act=add หรือ act=remove ให้ "ไม่ใช้" p_barCode มากรองรายการสินค้า (ให้แสดงสินค้าทั้งหมด)
// - กรณีปกติ (ไม่มี act หรือ act อื่น) ใช้ p_barCode เป็นคำค้นหาได้ตามเดิม
if ($act === 'add' || $act === 'remove') {
  $keyword = '';
} else {
  $keyword = $rawBarcode;
}

// สร้างเงื่อนไข WHERE สำหรับค้นหา
$whereClause = "WHERE 1";
if ($keyword !== '') {
  $like = "%$keyword%";
  $whereClause .= " AND (p_barCode LIKE '$like' OR p_name LIKE '$like')";
}

$query_product = "SELECT * FROM tbl_product $whereClause" or die("Error : ".mysqli_error($condb));
$rs_product = mysqli_query($condb, $query_product);

$query=mysqli_query($condb,"SELECT COUNT(p_barCode) FROM `tbl_product` $whereClause");
$row = mysqli_fetch_row($query);
$rows = $row[0];

// ถ้ามีการพิมพ์ค้นหา ให้ดึง "สินค้าที่ตรงเงื่อนไขทั้งหมด" ไม่มีแบ่งหน้า
if ($keyword !== '') {
  $limit = '';           // ไม่ใช้ LIMIT
  $paginationCtrls = ''; // ไม่ต้องแสดงปุ่มเปลี่ยนหน้า
} else {
  $page_rows = 6;  //จำนวนข้อมูลที่ต้องการให้แสดงใน 1 หน้า 
  $last = ceil($rows/$page_rows);
  if($last < 1){
    $last = 1;
  }
  $pagenum = 1;
  if(isset($_GET['pn'])){
    $pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
  }
  if ($pagenum < 1) {
    $pagenum = 1;
  }
  else if ($pagenum > $last) {
    $pagenum = $last;
  }

  $limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;
  $paginationCtrls = '';
  if($last != 1){
    if ($pagenum > 1) {
      $previous = $pagenum - 1;
      $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'" class="btn btn-info">ก่อนหน้า</a> &nbsp; ';

      for($i = $pagenum-4; $i < $pagenum; $i++){
        if($i > 0){
          $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="btn btn-primary">'.$i.'</a> &nbsp; ';
        }
      }
    }
    //$paginationCtrls .= ''.$pagenum.' &nbsp; ';
    $paginationCtrls .= '<a href=""class="btn btn-danger">'.$pagenum.'</a> &nbsp; ';

    for($i = $pagenum+1; $i <= $last; $i++){
      $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'" class="btn btn-primary">'.$i.'</a> &nbsp; ';
      if($i >= $pagenum+4){
        break;
      }
    }

    if($pagenum != $last) {
      $next = $pagenum + 1;
      $paginationCtrls .= ' &nbsp;<a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'" class="btn btn-info">ถัดไป</a> ';
    }
  }
}

$nquery=mysqli_query($condb,"SELECT * from  tbl_product $whereClause ORDER BY p_id DESC $limit");
?>

<?php 
include "../barcode/src/BarcodeGenerator.php";
include "../barcode/src/BarcodeGeneratorHTML.php";

function barcode($code){
  $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
  $border = 1.2;//กำหนดความหน้าของเส้น Barcode
  $height = 30;//กำหนดความสูงของ Barcode
  return $generator->getBarcode($code , $generator::TYPE_CODE_128,$border,$height);
}
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>ขายสินค้า</h1>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">รายการสินค้า</h3>
        </div>
        <br>
        <div class="card-body">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-7">
                <form action="list_l.php" method="GET">
                  <div class="input-group">
                    <input 
                      type="text" 
                      name="p_barCode" 
                      class="form-control" 
                      id="scan_barcode"
                      placeholder="Scan Barcode หรือ พิมพ์รหัส/ชื่อสินค้า"
                      autofocus
                    >
                  </div>
                </form>
                <br>
                <?php if ($row > 0) { ?> 
                <div class="row">
                  <?php while($rs_prd = mysqli_fetch_array($nquery)){ ?> 
                  <div 
                    class="col-md-4 product-item" 
                    data-barcode="<?php echo htmlspecialchars($rs_prd['p_barCode'], ENT_QUOTES, 'UTF-8'); ?>" 
                    data-name="<?php echo htmlspecialchars($rs_prd['p_name'], ENT_QUOTES, 'UTF-8'); ?>"
                  > <!-- กำหนดขนาดของช่องแสดงสินค้า -->
                    <div class="card">
                      <img width="100%" src="../p_img/<?php echo $rs_prd['p_img'] ;?>" class="card-img-top">
                      <div class="card-body">
                        <p class="card-text">
                          <?php
                            // ตัดวันที่ (รูปแบบ 09/03/2569 เป็นต้นไป) ออกจากชื่อสินค้า ถ้ามี
                            $p_name_display = preg_replace('/\s*\d{1,2}\/\d{1,2}\/\d{2,4}.*/u', '', $rs_prd['p_name']);
                            echo $p_name_display;
                          ?> <br>
                          <?php echo number_format($rs_prd['p_price'], 2); ?> บาท
                        </p>
                        <?php if($rs_prd['p_qty'] > 0){ ?>
                          <center>     
                          <?php echo barcode($rs_prd['p_barCode']); ?>
                          <?php echo $rs_prd['p_barCode']; ?>
                          <br>
                          <a href="list_l.php?p_barCode=<?php echo $rs_prd['p_barCode'];?>&act=add" class="btn btn-success"><i class="fa fa-shopping-cart"></i> หยิบลงตระกร้า</a>
                          </center>
                        <?php } else { ?>
                          <button class="btn btn-danger" disabled> สินค้าหมด </button>
                        <?php } ?>
                      </div>
                    </div>           
                  </div>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              <div class="col-md-5">
                <?php include('cart_a_2.php');?>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <center>
            <div id="pagination_controls">
              <?php echo $paginationCtrls; ?>
            </div>
          </center>     
        </div>
      </div>
    </section>
    <!-- /.content -->
    
<?php include('footer.php'); ?>
  
<script>
  // Live Search จากช่อง Scan Barcode (ค้นหารหัส หรือ ชื่อสินค้า ในหน้าปัจจุบัน)
  (function() {
    const scanInput = document.getElementById('scan_barcode') || document.querySelector('input[name="p_barCode"]');
    const items = document.querySelectorAll('.product-item');

    if (!scanInput || !items.length) {
      return;
    }

    function filterProducts() {
      const q = scanInput.value.trim().toLowerCase();

      items.forEach(function(item) {
        const code = (item.getAttribute('data-barcode') || '').toLowerCase();
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        const matched = !q || code.indexOf(q) !== -1 || name.indexOf(q) !== -1;
        item.style.display = matched ? '' : 'none';
      });
    }

    // เมื่อกด Enter หลังยิงบาร์โค้ด ให้เพิ่มสินค้าลงตะกร้าอัตโนมัติ
    scanInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        const code = scanInput.value.trim();
        if (code) {
          // ไปที่ URL เดิมแบบเดิมที่ปุ่ม "หยิบลงตระกร้า" ใช้
          window.location.href = 'list_l.php?p_barCode=' + encodeURIComponent(code) + '&act=add';
        }
      }
    });

    // ทำงานทันทีเมื่อพิมพ์หรือยิงบาร์โค้ด (live search)
    scanInput.addEventListener('input', filterProducts);
  })();
</script>

</body>
</html>