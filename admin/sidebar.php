<?php 
$query_ptype = "SELECT t.t_id, t.t_name, COUNT(p.t_id) as ttotal
FROM tbl_type as t left JOIN tbl_product as  p ON t.t_id=p.t_id 
GROUP BY t.t_id ORDER BY t.t_name ASC" or die("Error:".mysqli_error($condb));
$rs_ptype = mysqli_query($condb, $query_ptype);
$query_t = "SELECT * FROM tbl_type 
  -- LEFT JOIN tbl_product ON tbl_type.t_id = tbl_product.t_id
  -- GROUP BY tbl_type.t_id";
$rs_t= mysqli_query($condb, $query_t);
?>

<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-gray elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link bg-gray">
      <img src="../bpcc_logo.png"
        alt="BPCC Logo"
        class="brand-image img-circle elevation-3"
        style="opacity: .8">
      <span class="brand-text font-weight-light">BPCC | POS System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="../mem_img/<?php echo $_SESSION['mem_img'];?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="edit_profile.php" target="" class="d-block"> <?php echo $_SESSION['mem_name'];?> | แก้ไขข้อมูลส่วนตัว</a>
        </div>
      </div>

      <?php if ($m_level == 0) { ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <!-- nav-compact -->
        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
          <li class="nav-header">เมนูสำหรับการขาย</li>
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($menu=="index"){echo "active";} ?> ">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>รายการขาย </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_l.php" class="nav-link <?php if($menu=="sale"){echo "active";} ?> ">
              <i class="nav-icon fa fa-shopping-cart "></i>
              <p>ขายสินค้า </p>
            </a>
          </li>
          
        </ul>
        <hr>

        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
          <li class="nav-header">ตั้งค่าข้อมูลระบบ</li>
          <li class="nav-item">
            <a href="list_mem.php" class="nav-link <?php if($menu=="member"){echo "active";} ?> ">
              <i class="nav-icon fa fa-users"></i>
              <p>สมาชิก</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_type.php" class="nav-link <?php if($menu=="type"){echo "active";} ?> ">
              <i class="nav-icon fa fa-copy"></i>
              <p>ประเภทสินค้า</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_brand.php" class="nav-link <?php if($menu=="brand"){echo "active";} ?> ">
              <i class="nav-icon fa fa-box"></i>
              <p>แบรนด์สินค้า</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_product.php" class="nav-link <?php if($menu=="product"){echo "active";} ?> ">
              <i class="nav-icon fa fa-box-open"></i>
              <p>สินค้า</p>
            </a>
          </li>
        </ul>
        <hr>

        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">Dashboard</li>
          <li class="nav-item">
            <a href="report_sales.php" class="nav-link <?php if($menu=="report_d"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-bar text-white"></i>
              <p>ยอดขาย</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_stocks.php" class="nav-link <?php if($menu=="report_stocks"){echo "active";} ?> ">
              <i class="nav-icon fas fa-tasks text-warning"></i>
              <p>สรุปยอดสินค้าคงเหลือ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_profit.php" class="nav-link <?php if($menu=="report_profit"){echo "active";} ?> ">
              <i class="nav-icon fas fa-tasks text-success"></i>
              <p>สรุปกำไรสุทธิ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_top10_product.php" class="nav-link <?php if($menu=="report_top10_product"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-line text-primary"></i>
              <p>สินค้าขายดี</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_credit.php" class="nav-link <?php if($menu=="report_credit"){echo "active";} ?> ">
              <i class="nav-icon fas fa-money-check text-info"></i>
              <p>สินเชื่อ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_purchase.php" class="nav-link <?php if($menu=="report_purchase"){echo "active";} ?> ">
              <i class="nav-icon fas fa-wallet text-pink"></i>
              <p>ยอดซื้อทั้งหมด</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_add_stocks.php" class="nav-link <?php if($menu=="list_add_stocks"){echo "active";} ?> ">
              <i class="nav-icon fas fa-dolly-flatbed text-white"></i>
              <p>รายการเพิ่มสินค้าเข้าคลัง</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_barcode.php" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-qrcode text-red"></i>
              <p>รายการบาร์โค้ด</p>
            </a>
          </li>
          <li class="nav-header"></li>
          <li class="nav-item">
            <a href="../logout.php" class="nav-link text-danger">
              <i class="nav-icon fas fa-power-off"></i>
              <p>ออกจากระบบ</p>
            </a>
          </li>
        </ul>
      </nav>

      <?php } elseif ($m_level == 1) { ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
          <li class="nav-header">ตั้งค่าข้อมูลระบบ</li>
          <li class="nav-item">
            <a href="list_type.php" class="nav-link <?php if($menu=="type"){echo "active";} ?> ">
              <i class="nav-icon fa fa-copy"></i>
              <p>ประเภทสินค้า</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_brand.php" class="nav-link <?php if($menu=="brand"){echo "active";} ?> ">
              <i class="nav-icon fa fa-box"></i>
              <p>แบรนด์สินค้า</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_product.php" class="nav-link <?php if($menu=="product"){echo "active";} ?> ">
              <i class="nav-icon fa fa-box-open"></i>
              <p>สินค้า</p>
            </a>
          </li>
        </ul>
        <hr>

        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">Dashboard</li>
          <li class="nav-item">
            <a href="report_sales.php" class="nav-link <?php if($menu=="report_d"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-bar text-white"></i>
              <p>ยอดขาย</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_all.php" class="nav-link <?php if($menu=="report_all"){echo "active";} ?> ">
              <i class="nav-icon fas fa-tasks text-success"></i>
              <p>สรุปยอดขาย</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_top10_product.php" class="nav-link <?php if($menu=="report_top10_product"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-line text-primary"></i>
              <p>สินค้าขายดี</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_credit.php" class="nav-link <?php if($menu=="report_credit"){echo "active";} ?> ">
              <i class="nav-icon fas fa-money-check text-info"></i>
              <p>สินเชื่อ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_purchase.php" class="nav-link <?php if($menu=="report_purchase"){echo "active";} ?> ">
              <i class="nav-icon fas fa-wallet text-white"></i>
              <p>ยอดซื้อทั้งหมด</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_add_stocks.php" class="nav-link <?php if($menu=="list_add_stocks"){echo "active";} ?> ">
              <i class="nav-icon fas fa-dolly-flatbed text-white"></i>
              <p>รายการเพิ่มสินค้าเข้าคลัง</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_barcode.php" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-qrcode text-white"></i>
              <p>รายการบาร์โค้ด</p>
            </a>
          </li>
          <li class="nav-header"></li>
          <li class="nav-item">
            <a href="../logout.php" class="nav-link text-danger">
              <i class="nav-icon fas fa-power-off"></i>
              <p>ออกจากระบบ</p>
            </a>
          </li>
        </ul>
      </nav>

      <?php } elseif ($m_level == 2) { ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
        <!-- nav-compact -->
        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
          <li class="nav-header">เมนูสำหรับการขาย</li>
          <li class="nav-item">
            <a href="index.php" class="nav-link <?php if($menu=="index"){echo "active";} ?> ">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>รายการขาย </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_l.php" class="nav-link <?php if($menu=="sale"){echo "active";} ?> ">
              <i class="nav-icon fa fa-shopping-cart "></i>
              <p>ขายสินค้า </p>
            </a>
          </li>
          
        </ul>
        <hr>

        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
          <li class="nav-header">ตั้งค่าข้อมูลระบบ</li>

        </ul>
        <hr>

        <ul class="nav nav-pills nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">Dashboard</li>
          <li class="nav-item">
            <a href="report_sales.php" class="nav-link <?php if($menu=="report_d"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-bar text-white"></i>
              <p>ยอดขาย</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_all.php" class="nav-link <?php if($menu=="report_all"){echo "active";} ?> ">
              <i class="nav-icon fas fa-tasks text-success"></i>
              <p>สรุปยอดขาย</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_top10_product.php" class="nav-link <?php if($menu=="report_top10_product"){echo "active";} ?> ">
              <i class="nav-icon fas fa-chart-line text-primary"></i>
              <p>สินค้าขายดี</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_credit.php" class="nav-link <?php if($menu=="report_credit"){echo "active";} ?> ">
              <i class="nav-icon fas fa-money-check text-info"></i>
              <p>สินเชื่อ</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="report_purchase.php" class="nav-link <?php if($menu=="report_purchase"){echo "active";} ?> ">
              <i class="nav-icon fas fa-wallet text-white"></i>
              <p>ยอดซื้อทั้งหมด</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_add_stocks.php" class="nav-link <?php if($menu=="list_add_stocks"){echo "active";} ?> ">
              <i class="nav-icon fas fa-dolly-flatbed text-white"></i>
              <p>รายการเพิ่มสินค้าเข้าคลัง</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="list_barcode.php" class="nav-link" target="_blank">
              <i class="nav-icon fas fa-qrcode text-white"></i>
              <p>รายการบาร์โค้ด</p>
            </a>
          </li>
          <li class="nav-header"></li>
          <li class="nav-item">
            <a href="../logout.php" class="nav-link text-danger">
              <i class="nav-icon fas fa-power-off"></i>
              <p>ออกจากระบบ</p>
            </a>
          </li>
        </ul>
      </nav>

      <?php } else { ?>
        Header("Location: ../index.php");
      <?php } ?>
      
    </div>
    <!-- /.sidebar -->
  </aside>