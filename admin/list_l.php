<?php 
$menu = "sale";
include("header.php");

$query_product = "SELECT * FROM tbl_product " or die("Error : ".mysqli_error($condb));
$rs_product = mysqli_query($condb, $query_product);

$query=mysqli_query($condb,"SELECT COUNT(p_barCode) FROM `tbl_product`");
$row = mysqli_fetch_row($query);
$rows = $row[0];
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
$nquery=mysqli_query($condb,"SELECT * from  tbl_product ORDER BY p_id DESC $limit");
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
                    <input type="text" name="p_barCode" class="form-control" placeholder="Scan Barcode" autofocus>
                  </div>
                </form>
                <br>
                <?php if ($row > 0) { ?> 
                <div class="row">
                  <?php while($rs_prd = mysqli_fetch_array($nquery)){ ?> 
                  <div class="col-md-4"> <!-- กำหนดขนาดของช่องแสดงสินค้า -->
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
  
</body>
</html>