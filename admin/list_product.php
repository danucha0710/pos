<?php 
$menu = "product";
include("header.php");
?>

<?php 
// ดึงข้อมูลสินค้า พร้อมข้อมูลการเพิ่มสต๊อกครั้งล่าสุด (จำนวนและวันที่)
// ปรับ query เพื่อลดจำนวน subquery ต่อแถว และให้ทำงานเร็วขึ้น
$query_product = "
  SELECT 
    p.*,
    t.t_name, 
    b.b_name,
    s_last.sto_add AS last_add_qty,
    s_last.sto_date_add AS last_add_date
  FROM tbl_product AS p 
  INNER JOIN tbl_type AS t ON p.t_id = t.t_id
  LEFT JOIN tbl_brand AS b ON p.b_id = b.b_id
  LEFT JOIN (
    SELECT s1.p_barCode, s1.sto_add, s1.sto_date_add
    FROM tbl_add_stocks s1
    INNER JOIN (
      SELECT p_barCode, MAX(sto_date_add) AS max_date
      FROM tbl_add_stocks
      GROUP BY p_barCode
    ) s2
      ON s1.p_barCode = s2.p_barCode
     AND s1.sto_date_add = s2.max_date
  ) AS s_last
    ON s_last.p_barCode = p.p_barCode
" or die("Error : ".mysqli_error($condb));

$rs_product = mysqli_query($condb, $query_product);

// แปลงผลลัพธ์เป็น array เพื่อใช้ทั้งในตารางและ JavaScript (live search)
$products = [];
while ($row = mysqli_fetch_assoc($rs_product)) {
  $products[] = $row;
}

$query_type = "SELECT * FROM tbl_type " or die("Error : ".mysqli_error($condb));
$rs_type = mysqli_query($condb, $query_type);
$query_brand = "SELECT * FROM tbl_brand " or die("Error : ".mysqli_error($condb));
$rs_brand = mysqli_query($condb, $query_brand);
?>

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <h1>Product</h1>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="card card-gray">
        <div class="card-header ">
          <h3 class="card-title">รายการสินค้า</h3>
          <div align="right">
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addStockModal">
              <i class="fa fa-plus"></i> เพิ่มสินค้าเข้าคลัง
            </button>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fa fa-plus"></i> เพิ่มสินค้า
            </button>
          </div>
        </div>
        <br>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table id="tableSearch" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr class="danger">
                    <th width="5%"><center>ลำดับ</center></th>
                    <th width="10%"><center>รหัสบาร์โค้ด</center></th>
                    <th width="10%"><center>ประเภทสินค้า</center></th>
                    <th width="10%"><center>แบรนด์สินค้า</center></th>
                    <th width="25%"><center>ชื่อสินค้า</center></th>
                    <th width="10%"><center>ราคาขาย</center></th>
                    <th width="8%"><center>จำนวน</center></th>
                    <th width="8%"><center>จำนวนที่เพิ่มล่าสุด</center></th>
                    <th width="14%"><center>วันที่เพิ่มล่าสุด</center></th>
                    <th width="10%"><center>แก้ไข</center></th>
                    <th width="10%"><center>ลบ</center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($products as $row_product) { ?>
                  <tr>
                    <td align="center"><?php echo @$l+=1; ?></td>
                    <td><?php echo $row_product['p_barCode']; ?></td>
                    <td><?php echo $row_product['t_name']; ?></td>
                    <td><?php echo $row_product['b_name']; ?></td>
                    <td><?php echo $row_product['p_name']; ?></td>
                    <td align="right"><?php echo $row_product['p_price']; ?></td>
                    <td align="center"><?php echo $row_product['p_qty']; ?></td>
                    <td align="center">
                      <?php 
                        if (!is_null($row_product['last_add_qty'])) {
                          echo $row_product['last_add_qty'];
                        } else {
                          echo "-";
                        }
                      ?>
                    </td>
                    <td align="center">
                      <?php 
                        if (!is_null($row_product['last_add_date'])) {
                          echo date('d/m/Y H:i:s', strtotime($row_product['last_add_date']));
                        } else {
                          echo "-";
                        }
                      ?>
                    </td>
                    <td align="center"><a href="product_edit.php?p_id=<?php echo $row_product['p_id']; ?>" class="btn btn-warning"><i class="fas fa-pencil-alt"></i> edit</a></td>
                    <td align="center"><a href="product_db.php?p_id=<?php echo $row_product['p_id']; ?>" class="del-btn btn btn-danger"><i class="fas fas fa-trash"></i> del</a></td>
                  </tr>
                  <?php @$total+=$row_product['p_qty']; } ?>
                </tbody>
              </table>

              <?php if(isset($_GET['d'])){ ?>
              <div class="flash-data" data-flashdata="<?php echo $_GET['d'];?>"></div>
              <?php } ?>

              <script>
              $('.del-btn').on('click',function(e){
                e.preventDefault();
                const href = $(this).attr('href') 
                Swal.fire({
                  title: 'ต้องการลบข้อมูลใช่ไหม ?',
                  //text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it.'
                }).then((result) => {
                  if (result.value) {
                    document.location.href = href;    
                  }
                })
              })

              const flashdata = $('.flash-data').data('flashdata')
              if(flashdata){
                swal.fire({
                  type : 'success',
                  title : 'ลบข้อมูลเรียบร้อยแล้ว',
                  //text : 'Record has been deleted',
                  icon: 'success'
                })
              }
              </script>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->

    <!-- Modal เพิ่มสินค้าเข้าคลัง (เลือกจากสินค้าเดิม) -->
    <div class="modal fade" id="addStockModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="quick_add_stock.php" method="POST">
          <div class="modal-content">
            <div class="modal-header bg-gray">
              <h5 class="modal-title" id="addStockModalLabel">เพิ่มสินค้าเข้าคลัง</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row mb-3">
                <label for="stock_search" class="col-sm-3 col-form-label">ค้นหาสินค้า</label>
                <div class="col-sm-9">
                  <input 
                    type="text" 
                    class="form-control" 
                    id="stock_search" 
                    placeholder="สแกนบาร์โค้ด หรือ พิมพ์ชื่อสินค้า"
                    autocomplete="off"
                    list="productSearchList"
                  >
                  <datalist id="productSearchList">
                    <?php foreach ($products as $p) { ?>
                      <option value="<?php echo $p['p_barCode']; ?>">
                        <?php echo $p['p_barCode'] . ' - ' . $p['p_name']; ?>
                      </option>
                    <?php } ?>
                  </datalist>
                </div>
              </div>

              <div class="row mb-3">
                <label for="add_p_barCode" class="col-sm-3 col-form-label">รหัสสินค้า</label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" id="add_p_barCode" name="p_barCode" readonly>
                </div>
                <label for="add_p_qty_current" class="col-sm-2 col-form-label">จำนวนล่าสุด</label>
                <div class="col-sm-3">
                  <input type="number" class="form-control" id="add_p_qty_current" readonly>
                </div>
              </div>

              <div class="row mb-3">
                <label for="add_p_name" class="col-sm-3 col-form-label">ชื่อสินค้า</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="add_p_name" readonly>
                </div>
              </div>

              <div class="row mb-3">
                <label for="add_qty" class="col-sm-3 col-form-label">จำนวนที่ต้องการเพิ่ม</label>
                <div class="col-sm-3">
                  <input 
                    type="number" 
                    class="form-control" 
                    id="add_qty" 
                    name="add_qty" 
                    min="0" 
                    step="1"
                    value="0"
                    required
                  >
                </div>
                <div class="col-sm-2 d-flex align-items-end">
                  <button type="button" class="btn btn-primary w-100" id="btn_add_to_list">
                    <i class="fa fa-plus"></i> เพิ่ม
                  </button>
                </div>
              </div>

              <hr>

              <div class="row mb-2">
                <div class="col-md-12">
                  <h6>รายการที่ต้องการเพิ่มเข้าคลัง</h6>
                  <table class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                      <tr class="danger">
                        <th width="15%"><center>รหัสสินค้า</center></th>
                        <th width="45%"><center>ชื่อสินค้า</center></th>
                        <th width="15%"><center>จำนวนคงคลัง</center></th>
                        <th width="15%"><center>จำนวนที่เพิ่ม</center></th>
                        <th width="10%"><center>ลบ</center></th>
                      </tr>
                    </thead>
                    <tbody id="addStockItemsBody">
                      <!-- แสดงรายการสินค้าที่จะเพิ่มเข้าคลังที่นี่ -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form action="product_db.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="product" value="add">
          <div class="modal-content">
            <div class="modal-header bg-gray">
              <h5 class="modal-title" id="exampleModalLabel">เพิ่มสินค้า</h5>
              <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <label for="p_barCode" class="col-sm-2 col-form-label">รหัสบาร์โค้ด</label>
                <div class="col-sm-10">
                  <input name="p_barCode" type="text" class="form-control" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="t_id" class="col-sm-2 col-form-label">ประเภทสินค้า</label>
                <div class="col-sm-10">
                  <select name="t_id" class="form-control" required>
                    <option value="">--เลือกประเภทสินค้า--</option>
                    <?php foreach ($rs_type as $rst) { ?>
                    <option value="<?php echo $rst['t_id'];?>"><?php echo $rst['t_name'];?></option>
                    <?php } ?> 
                  </select>   
                </div>
              </div>
              <div class="row mt-3">
                <label for="b_id" class="col-sm-2 col-form-label">แบรนด์สินค้า</label>
                <div class="col-sm-10">
                  <select name="b_id" class="form-control" required>
                    <option value="">--เลือกแบรนด์สินค้า--</option>
                    <?php foreach ($rs_brand as $rsb) { ?> 
                    <option value="<?php echo $rsb['b_id'];?>"><?php echo $rsb['b_name']; ?></option>
                    <?php } ?> 
                  </select>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_name" class="col-sm-2 col-form-label">ชื่อสินค้า</label>
                <div class="col-sm-10">
                  <input name="p_name" type="text" class="form-control" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_detail" class="col-sm-2 col-form-label">รายละเอียด</label>
                <div class="col-sm-10">
                  <textarea name="p_detail" class="form-control" row="3"></textarea>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_cost" class="col-sm-2 col-form-label">ราคาต้นทุน</label>
                <div class="col-sm-10">
                  <input name="p_cost" type="number" min="0" step="0.01" class="form-control" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_price" class="col-sm-2 col-form-label">ราคาขาย</label>
                <div class="col-sm-10">
                  <input name="p_price" type="number" min="0" step="0.01" class="form-control" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_qty" class="col-sm-2 col-form-label">จำนวน</label>
                <div class="col-sm-10">
                  <input name="p_qty" type="number" min="0" step="1" class="form-control" required>
                </div>
              </div>
              <div class="row mt-3">
                <label for="p_img" class="col-sm-2 col-form-label">รูปสินค้า</label>
                <div class="col-sm-10">  
                  <input class="form-control" type="file" name="p_img" id="p_img">
                </div>
              </div>     
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> ยืนยัน</button>
            </div>
          </div>
        </form>
      </div>
    </div> 
  
<?php include('footer.php'); ?>

<script>
  // เตรียมข้อมูลสินค้าให้ JavaScript ใช้สำหรับ live search
  const productsData = <?php echo json_encode(
    array_map(function($p) {
      return [
        'p_barCode' => $p['p_barCode'],
        'p_name' => $p['p_name'],
        'p_qty' => (int)$p['p_qty'],
      ];
    }, $products),
    JSON_UNESCAPED_UNICODE
  ); ?>;

  const stockSearchInput = document.getElementById('stock_search');
  const addBarcodeInput = document.getElementById('add_p_barCode');
  const addNameInput = document.getElementById('add_p_name');
  const addQtyCurrentInput = document.getElementById('add_p_qty_current');
  const addQtyInput = document.getElementById('add_qty');
  const addStockModal = document.getElementById('addStockModal');
  const addStockForm = addStockModal ? addStockModal.querySelector('form') : null;
  const addStockItemsBody = document.getElementById('addStockItemsBody');
  const btnAddToList = document.getElementById('btn_add_to_list');

  function clearAddStockFields() {
    addBarcodeInput.value = '';
    addNameInput.value = '';
    addQtyCurrentInput.value = '';
    addQtyInput.value = 0;
    if (addStockItemsBody) {
      addStockItemsBody.innerHTML = '';
    }
  }

  function findProduct(query) {
    if (!query) return null;
    const q = query.toString().toLowerCase();
    // ตรงกับบาร์โค้ดก่อน
    let product = productsData.find(p => p.p_barCode.toLowerCase() === q);
    if (product) return product;
    // ถ้าไม่เจอ ลองค้นหาจากชื่อสินค้า
    product = productsData.find(p => p.p_name.toLowerCase().indexOf(q) !== -1);
    return product || null;
  }

  if (stockSearchInput) {
    // กันไม่ให้กด Enter ในช่องค้นหาแล้วฟอร์ม submit ทันที (รองรับเครื่องยิงบาร์โค้ด)
    stockSearchInput.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
      }
    });

    stockSearchInput.addEventListener('input', function(e) {
      const q = e.target.value;
      const product = findProduct(q);
      if (product) {
        addBarcodeInput.value = product.p_barCode;
        addNameInput.value = product.p_name;
        addQtyCurrentInput.value = product.p_qty;
      }
    });
  }

  // เพิ่มรายการลงตารางด้านล่าง (ยังไม่บันทึกเข้าฐานข้อมูล)
  function addCurrentSelectionToList() {
    if (!addStockItemsBody) return;

    const code = (addBarcodeInput.value || '').trim();
    const name = (addNameInput.value || '').trim();
    const qtyCurrent = addQtyCurrentInput.value || '0';
    const qtyAdd = parseInt(addQtyInput.value || '0', 10);

    if (!code || !name) {
      alert('กรุณาเลือกสินค้าจากช่องค้นหาก่อน');
      stockSearchInput.focus();
      return;
    }
    if (isNaN(qtyAdd) || qtyAdd <= 0) {
      alert('กรุณาระบุ "จำนวนที่ต้องการเพิ่ม" มากกว่า 0');
      addQtyInput.focus();
      return;
    }

    // ถ้ามีรายการของบาร์โค้ดนี้อยู่แล้ว ให้รวมจำนวนแทนการสร้างแถวใหม่
    const existingRow = addStockItemsBody.querySelector('tr[data-barcode="' + code + '"]');
    if (existingRow) {
      const qtyAddCell = existingRow.querySelector('.cell-add-qty');
      const hiddenQtyInput = existingRow.querySelector('input[name^="items"][name$="[add_qty]"]');
      const oldQty = parseInt(qtyAddCell.textContent || '0', 10);
      const newQty = oldQty + qtyAdd;
      qtyAddCell.textContent = newQty;
      if (hiddenQtyInput) hiddenQtyInput.value = newQty;
    } else {
      const rowId = 'row_' + Date.now() + '_' + Math.floor(Math.random()*1000);
      const tr = document.createElement('tr');
      tr.setAttribute('data-barcode', code);
      tr.id = rowId;
      tr.innerHTML = `
        <td align="center">${code}</td>
        <td>${name}</td>
        <td align="center">${qtyCurrent}</td>
        <td align="center" class="cell-add-qty">${qtyAdd}</td>
        <td align="center">
          <button type="button" class="btn btn-danger btn-sm btn-remove-item">
            <i class="fas fa-trash"></i> ลบ
          </button>
        </td>
        <input type="hidden" name="items[${rowId}][p_barCode]" value="${code}">
        <input type="hidden" name="items[${rowId}][add_qty]" value="${qtyAdd}">
      `;
      addStockItemsBody.appendChild(tr);
    }

    // เคลียร์ข้อมูล เตรียมยิงบาร์โค้ดหรือเลือกสินค้าใหม่
    addQtyInput.value = 0;
    addBarcodeInput.value = '';
    addNameInput.value = '';
    addQtyCurrentInput.value = '';
    stockSearchInput.value = '';
    stockSearchInput.focus();
  }

  if (btnAddToList) {
    btnAddToList.addEventListener('click', addCurrentSelectionToList);
  }

  // ลบรายการออกจากตาราง
  if (addStockItemsBody) {
    addStockItemsBody.addEventListener('click', function(e) {
      if (e.target.closest('.btn-remove-item')) {
        const tr = e.target.closest('tr');
        if (tr) {
          tr.remove();
        }
      }
    });
  }

  // ตรวจสอบว่ามีรายการอย่างน้อย 1 รายการก่อนส่งฟอร์ม
  if (addStockForm) {
    addStockForm.addEventListener('submit', function(e) {
      if (!addStockItemsBody || addStockItemsBody.querySelectorAll('tr').length === 0) {
        e.preventDefault();
        alert('กรุณาเพิ่มรายการสินค้าเข้าคลังอย่างน้อย 1 รายการ ก่อนกด \"ยืนยัน\"');
        return;
      }
    });
  }

  if (addStockModal) {
    addStockModal.addEventListener('shown.bs.modal', function () {
      clearAddStockFields();
      if (stockSearchInput) {
        stockSearchInput.focus();
      }
    });
  }
</script>

</body>
</html>