<div class="container-fluid">
    <h2 class="mb-4"><i class="fas fa-boxes"></i> إدارة المخزون</h2>
    
    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <label>المنتج</label>
                    <select id="productFilter" class="form-control">
                        <option value="">جميع المنتجات</option>
                        <?php foreach($products as $p): ?>
                            <option value="<?= $p->id ?>"><?= $p->code ?> - <?= $p->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label>المستودع</label>
                    <select id="warehouseFilter" class="form-control">
                        <option value="">جميع المستودعات</option>
                        <?php foreach($warehouses as $w): ?>
                            <option value="<?= $w->id ?>"><?= $w->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button id="searchBtn" class="btn btn-primary form-control">بحث</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>كود المنتج</th>
                            <th>اسم المنتج</th>
                            <th>المستودع</th>
                            <th>الكمية الحالية</th>
                            <th>كمية التنبيه</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="stockTableBody">
                        <tr><td colspan="8" class="text-center">جاري التحميل...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Update Stock Modal -->
<div class="modal fade" id="updateStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> تحديث المخزون</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="updateStockForm">
                    <input type="hidden" name="product_id" id="stock_product_id">
                    <input type="hidden" name="warehouse_id" id="stock_warehouse_id">
                    
                    <div class="mb-3">
                        <label>المنتج</label>
                        <input type="text" id="stock_product_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>المستودع</label>
                        <input type="text" id="stock_warehouse_name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>الكمية الحالية</label>
                        <input type="number" id="current_quantity" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>نوع العملية</label>
                        <select name="operation" id="operation" class="form-control" required>
                            <option value="add">➕ إضافة كميه</option>
                            <option value="subtract">➖ خصم كميه</option>
                            <option value="set">📝 تعيين كميه جديدة</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>الكمية</label>
                        <input type="number" name="quantity" id="update_quantity" class="form-control" required min="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="updateStockBtn" class="btn btn-primary">تحديث المخزون</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

$(document).ready(function() {
    loadStock();
    
    $('#searchBtn').click(function() {
        loadStock();
    });
    
    $('#updateStockBtn').click(function() {
        let operation = $('#operation').val();
        let quantity = parseInt($('#update_quantity').val());
        let currentQty = parseInt($('#current_quantity').val());
        
        if(operation == 'subtract' && quantity > currentQty) {
            alert('لا يمكن خصم كميه أكبر من المتاحة!');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("stock/update_stock") ?>',
            type: 'POST',
            data: $('#updateStockForm').serialize(),
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if(response.success) {
                    $('#updateStockModal').modal('hide');
                    loadStock();
                }
            }
        });
    });
});

function loadStock() {
    let product_id = $('#productFilter').val();
    let warehouse_id = $('#warehouseFilter').val();
    
    $.ajax({
        url: '<?= base_url("stock/ajax_stock_list") ?>',
        type: 'GET',
        data: {
            product_id: product_id,
            warehouse_id: warehouse_id
        },
        dataType: 'json',
        success: function(stock) {
            displayStock(stock);
        }
    });
}

function displayStock(stock) {
    let html = '';
    
    if(stock.length === 0) {
        html = '<tr><td colspan="8" class="text-center">لا توجد بيانات</td></tr>';
    } else {
        stock.forEach(function(item, index) {
            let statusBadge = '';
            if(item.quantity <= item.alert_quantity) {
                statusBadge = '<span class="badge bg-danger">منخفض</span>';
            } else {
                statusBadge = '<span class="badge bg-success">جيد</span>';
            }
            
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${item.code}</strong></td>
                    <td>${item.product_name}</td>
                    <td>${item.warehouse_name}</td>
                    <td class="text-center">
                        <span class="badge bg-primary fs-6">${item.quantity}</span>
                    </td>
                    <td>${item.alert_quantity}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="openUpdateModal(${item.product_id}, ${item.warehouse_id}, '${item.product_name}', '${item.warehouse_name}', ${item.quantity})">
                            <i class="fas fa-edit"></i> تحديث
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#stockTableBody').html(html);
}

function openUpdateModal(product_id, warehouse_id, product_name, warehouse_name, quantity) {
    $('#stock_product_id').val(product_id);
    $('#stock_warehouse_id').val(warehouse_id);
    $('#stock_product_name').val(product_name);
    $('#stock_warehouse_name').val(warehouse_name);
    $('#current_quantity').val(quantity);
    $('#update_quantity').val('');
    $('#operation').val('add');
    $('#updateStockModal').modal('show');
}
</script>