<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>BPCC POS System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="../assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- DataTables -->
  <link rel="stylesheet" href="../assets/plugins/datatables/css/dataTables.bootstrap5.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Kanit:400" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="../assets/plugins/bootstrap-5/bootstrap.min.css"> 
  <!-- Highcharts.com -->
  <script src="../assets/plugins/chart/highcharts.js"></script>
  <script src="../assets/plugins/chart/data.js"></script>
  <!-- <script src="../assets/plugins/chart/exporting.js"></script> -->
  <script src="../assets/plugins/chart/accessibility.js"></script>
  
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      font-size: 14px;
      background-color: #f4f6f9;
    }

    .page-header-title {
      font-size: 1.6rem;
      font-weight: 600;
      color: #343a40;
    }

    .filter-bar {
      background: #ffffff;
      border-radius: 0.5rem;
      padding: 0.75rem 1rem;
      box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }

    .product-card {
      transition: transform 0.15s ease, box-shadow 0.15s ease;
      border-radius: 0.75rem;
      overflow: hidden;
      border: 1px solid #e9ecef;
    }

    .product-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }

    .product-image-wrapper {
      background: #f8f9fa;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 120px;
    }

    .product-name {
      font-size: 0.9rem;
      font-weight: 500;
      color: #343a40;
      min-height: 40px;
    }

    .product-price {
      font-weight: 600;
      color: #0d6efd;
      font-size: 0.9rem;
    }

    .barcode-wrapper {
      background: #ffffff;
      border-radius: 0.5rem;
      padding: 0.25rem 0.35rem;
      border: 1px dashed #dee2e6;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .barcode-inner {
      width: 100%;
      text-align: center;
    }

    .stock-badge {
      font-size: 0.75rem;
    }

    .empty-state {
      padding: 3rem 1rem;
      text-align: center;
      color: #6c757d;
    }

    @media print{
      .btn,
      .filter-bar,
      #pagination_controls,
      .main-header,
      .main-sidebar {
         display: none !important; 
      }
      body {
        background-color: #ffffff;
      }
      /* พยายามบังคับให้สีของบาร์โค้ดถูกพิมพ์ออกมาด้วย */
      .barcode-wrapper div,
      .barcode-wrapper span {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
      }
    }
  </style>
</head>

<?php
//error_reporting(error_reporting() & ~E_NOTICE);
//session_start(); 
include('../condb.php')
?>

<body>

<?php 

// echo "<pre>";
// print_r($_SESSION);
// print_r($_GET);
// echo "</pre>";
//exit();

// รับค่าตัวกรอง
$t_id = !empty($_GET['t_id']) ? (int)$_GET['t_id'] : 0;
$keyword = !empty($_GET['q']) ? mysqli_real_escape_string($condb, $_GET['q']) : '';

// สร้างเงื่อนไข WHERE ตามประเภทสินค้าและคำค้นหา
$whereClause = "WHERE 1";
if ($t_id > 0) {
  $whereClause .= " AND t_id = $t_id";
}
if ($keyword !== '') {
  $like = "%$keyword%";
  $whereClause .= " AND (p_barCode LIKE '$like' OR p_name LIKE '$like')";
}

// นับจำนวนสินค้า
$query=mysqli_query($condb,"SELECT COUNT(p_barCode) FROM `tbl_product` $whereClause");
$row = mysqli_fetch_row($query);

$rows = (int)$row[0];
$page_rows = 24;  //จำนวนข้อมูลที่ต้องการให้แสดงในหนึ่งหน้า
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
$nquery=mysqli_query($condb,"SELECT * FROM tbl_product $whereClause $limit");

$paginationCtrls = '';
if($last != 1){
if ($pagenum > 1) {
  $previous = $pagenum - 1;
  $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$previous.'&t_id='.$t_id.'" class="btn btn-info">Previous</a> &nbsp; ';
  for($i = $pagenum-4; $i < $pagenum; $i++){
    if($i > 0){
      $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&t_id='.$t_id.'" class="btn btn-primary">'.$i.'</a> &nbsp; ';
    }
  }
}

//$paginationCtrls .= ''.$pagenum.' &nbsp; ';

$paginationCtrls .= '<a href=""class="btn btn-danger">'.$pagenum.'</a> &nbsp; ';

//t_id=1

for($i = $pagenum+1; $i <= $last; $i++){
  $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?pn='.$i.'&t_id='.$t_id.'" class="btn btn-primary">'.$i.'</a> &nbsp; ';
  if($i >= $pagenum+4){
    break;
  }
}

if ($pagenum != $last) {
  $next = $pagenum + 1;
  $paginationCtrls .= ' &nbsp;<a href="'.$_SERVER['PHP_SELF'].'?pn='.$next.'&t_id='.$t_id.'" class="btn btn-info">Next</a> ';
  }
}

include "../barcode/src/BarcodeGenerator.php";
include "../barcode/src/BarcodeGeneratorHTML.php";

function barcode($code){
  // ใช้ HTML generator และจัดให้อยู่กึ่งกลาง รองรับทั้งแสดงผลและพิมพ์
  $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
  // เพิ่มความหนาและความสูงของบาร์โค้ดให้ใหญ่และอ่านง่าย
  $border = 2.0; // ความหนาเส้นบาร์โค้ด (scale)
  $height = 50;  // ความสูงบาร์โค้ด (px)
  $html = $generator->getBarcode($code , $generator::TYPE_CODE_128,$border,$height);
  return '<div class="barcode-inner">'.$html.'</div>';
}
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h1 class="page-header-title mb-0">รายการบาร์โค้ดสินค้า</h1>
            <small class="text-muted">ดูและพิมพ์บาร์โค้ดของสินค้าในระบบ</small>
          </div>
          <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
              <i class="fas fa-print me-1"></i> พิมพ์บาร์โค้ด
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header border-0 pb-0">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">รายการบาร์โค้ด</h3>
          </div>
        </div>
        <div class="card-body pt-3">
          <div class="filter-bar mb-3">
            <div class="row g-2 align-items-end">
              <?php 
                $query_t = "SELECT * FROM tbl_type" or die("Error:".mysqli_error($condb));
                $rs_t= mysqli_query($condb, $query_t);
              ?>
              <form action="list_barcode.php" method="GET" class="row g-2 align-items-end">
                <div class="col-md-4 col-lg-3">
                  <label for="t_id" class="form-label mb-1">ค้นหาตามประเภทสินค้า</label>
                  <select name="t_id" id="t_id" class="form-select form-select-sm">
                    <option value="">-- สินค้าทั้งหมด --</option>
                    <?php foreach ($rs_t as $rs_t ) { ?>
                      <option value="<?php echo $rs_t['t_id']; ?>" <?php echo ($t_id == $rs_t['t_id'] ? 'selected' : ''); ?>>
                        <?php echo $rs_t['t_name']; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-md-4 col-lg-4">
                  <label for="q" class="form-label mb-1">รหัสสินค้า หรือ ชื่อสินค้า</label>
                  <input 
                    type="text" 
                    class="form-control form-control-sm" 
                    id="q" 
                    name="q" 
                    placeholder="เช่น 8850... หรือ ชื่อสินค้า"
                    value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
                  >
                </div>
                <div class="col-md-2 col-lg-2">
                  <button type="submit" class="btn btn-primary btn-sm w-100 mt-3 mt-md-0">
                    <i class="fa fa-search me-1"></i> ค้นหา
                  </button>
                </div>
                <div class="col-md-2 col-lg-2">
                  <a href="list_barcode.php" class="btn btn-outline-secondary btn-sm w-100 mt-3 mt-md-0">
                    ล้างตัวกรอง
                  </a>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <?php if ($rows > 0) { ?> 
                <div class="row g-3" id="barcodeGrid">
                  <?php while($rs_prd = mysqli_fetch_array($nquery)){ ?> 
                    <div 
                      class="col-6 col-sm-4 col-md-3 col-lg-2 barcode-item"
                      data-barcode="<?php echo htmlspecialchars($rs_prd['p_barCode'], ENT_QUOTES, 'UTF-8'); ?>"
                      data-name="<?php echo htmlspecialchars($rs_prd['p_name'], ENT_QUOTES, 'UTF-8'); ?>"
                    >
                      <div class="card product-card h-100">
                        <div class="card-body p-2">
                          <p class="product-name mb-1">
                            <?php echo $rs_prd['p_name']; ?>
                          </p>
                          <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="product-price"><?php echo number_format($rs_prd['p_price'], 2); ?> บาท</span>
                            <span class="badge bg-<?php echo ($rs_prd['p_qty'] > 0 ? 'success' : 'secondary'); ?> stock-badge">
                              คงเหลือ <?php echo (int)$rs_prd['p_qty']; ?>
                            </span>
                          </div>
                          <div class="barcode-wrapper text-center">
                            <div class="mb-1">
                              <?php echo barcode($rs_prd['p_barCode']); ?>
                            </div>
                            <small class="text-muted"><?php echo $rs_prd['p_barCode']; ?></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php } ?>          
                </div>
              <?php } else { ?>
                <div class="empty-state">
                  <i class="fas fa-barcode fa-3x mb-3 text-muted"></i>
                  <h5 class="mb-1">ยังไม่มีข้อมูลบาร์โค้ดสำหรับประเภทนี้</h5>
                  <p class="mb-0">ลองเลือกประเภทอื่น หรือเพิ่มสินค้าใหม่ในระบบก่อน</p>
                </div>
              <?php } ?>  
            </div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <div class="d-flex justify-content-center">
            <div id="pagination_controls">
              <?php echo $paginationCtrls; ?>
            </div>
          </div>     
        </div>
      </div>
    </section>
    <!-- /.content -->

<script>
  // Live search สำหรับรหัสสินค้า / ชื่อสินค้า ภายในหน้าปัจจุบัน
  (function() {
    const searchInput = document.getElementById('q');
    const items = document.querySelectorAll('.barcode-item');

    if (!searchInput || !items.length) {
      return;
    }

    function filterBarcodes() {
      const q = searchInput.value.trim().toLowerCase();

      items.forEach(function(item) {
        const code = (item.getAttribute('data-barcode') || '').toLowerCase();
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        const matched = !q || code.indexOf(q) !== -1 || name.indexOf(q) !== -1;
        item.style.display = matched ? '' : 'none';
      });
    }

    // ทำงานทันทีเมื่อพิมพ์ (live search)
    searchInput.addEventListener('input', filterBarcodes);

    // ทำงานครั้งแรกเผื่อมีค่า q จากการโหลดด้วย GET
    filterBarcodes();
  })();
</script>

</body>
</html>