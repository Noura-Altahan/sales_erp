<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4><i class="fas fa-file-invoice"></i> إنشاء فاتورة جديدة</h4>
                </div>
                <div class="card-body">
                    
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>رقم الفاتورة</label>
                            <input type="text" id="invoice_no" class="form-control" value="<?= $invoice_no ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>التاريخ</label>
                            <input type="text" class="form-control" value="<?= date('Y-m-d H:i:s') ?>" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>العميل *</label>
                            <select id="customer_id" class="form-control">
                                <option value="">اختر عميل</option>
                                <?php foreach($customers as $c): ?>
                                    <option value="<?= $c->id ?>"><?= $c->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>المستودع *</label>
                            <select id="warehouse_id" class="form-control">
                                <option value="">اختر مستودع</option>
                                <?php foreach($warehouses as $w): ?>
                                    <option value="<?= $w->id ?>"><?= $w->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label>البحث عن منتج</label>
                            <input type="text" id="search_product" class="form-control" placeholder="اكتب كود أو اسم المنتج...">
                            <div id="search_results" class="list-group mt-2" style="display: none;"></div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>كود المنتج</th>
                                    <th>اسم المنتج</th>
                                    <th>السعر</th>
                                    <th>الكمية</th>
                                    <th>الإجمالي</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="invoice_items">
                                <tr><td colspan="7" class="text-center">لم تتم إضافة أي منتجات بعد</td><tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5" class="text-end">المجموع الفرعي:</th>
                                    <th id="subtotal_display">0.00</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th colspan="5" class="text-end">الخصم (%):</th>
                                    <th>
                                        <div class="input-group">
                                            <input type="number" id="discount_percent" class="form-control" value="0" step="1" min="0" max="100">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </th>
                                    <th></th>
                                </tr>
                                <tr class="table-primary">
                                    <th colspan="5" class="text-end">الإجمالي النهائي:</th>
                                    <th id="total_display">0.00</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button id="save_invoice_btn" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> حفظ الفاتورة
                        </button>
                        <a href="<?= base_url('invoices') ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> إلغاء
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let items = [];
let productSearchTimeout;

$(document).ready(function() {
    
    $('#search_product').on('keyup', function() {
        clearTimeout(productSearchTimeout);
        let search = $(this).val();
        let warehouse_id = $('#warehouse_id').val();
        
        if(search.length < 2 || !warehouse_id) {
            $('#search_results').hide();
            return;
        }
        
        productSearchTimeout = setTimeout(function() {
            $.ajax({
                url: '<?= base_url("invoices/search_products") ?>',
                type: 'POST',
                data: {
                    search: search,
                    warehouse_id: warehouse_id
                },
                dataType: 'json',
                success: function(products) {
                    displaySearchResults(products);
                }
            });
        }, 500);
    });
    
    $('#discount_percent').on('keyup change', function() {
        calculateTotals();
    });
    
    $('#save_invoice_btn').click(function() {
        let customer_id = $('#customer_id').val();
        let warehouse_id = $('#warehouse_id').val();
        
        if(!customer_id) {
            alert('الرجاء اختيار العميل');
            return;
        }
        if(!warehouse_id) {
            alert('الرجاء اختيار المستودع');
            return;
        }
        if(items.length === 0) {
            alert('الرجاء إضافة منتجات إلى الفاتورة');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("invoices/save_invoice") ?>',
            type: 'POST',
            data: {
                customer_id: customer_id,
                warehouse_id: warehouse_id,
                invoice_no: $('#invoice_no').val(),
                items: JSON.stringify(items),
                discount_percent: $('#discount_percent').val()
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    alert('تم حفظ الفاتورة بنجاح!');
                    window.location.href = '<?= base_url("invoices") ?>';
                } else {
                    alert(response.message);
                }
            }
        });
    });
});

function displaySearchResults(products) {
    let html = '';
    if(products.length === 0) {
        html = '<div class="list-group-item text-danger">لا توجد منتجات متاحة</div>';
    } else {
        products.forEach(function(product) {
            html += `
                <a href="#" class="list-group-item list-group-item-action" onclick="addProduct(${product.id}, '${product.code}', '${product.name}', ${product.price}, ${product.stock}); return false;">
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${product.code}</strong> - ${product.name}
                        </div>
                        <div>
                            السعر: ${parseFloat(product.price).toFixed(2)} 
                            | المخزون: ${product.stock}
                        </div>
                    </div>
                </a>
            `;
        });
    }
    $('#search_results').html(html).show();
}

function addProduct(id, code, name, price, stock) {
    let existingIndex = items.findIndex(item => item.product_id == id);
    if(existingIndex !== -1) {
        alert('المنتج موجود بالفعل في الفاتورة');
        return;
    }
    
    items.push({
        product_id: id,
        code: code,
        name: name,
        price: price,
        quantity: 1,
        stock: stock
    });
    
    refreshItemsTable();
    $('#search_product').val('');
    $('#search_results').hide();
    calculateTotals();
}

function refreshItemsTable() {
    let html = '';
    
    if(items.length === 0) {
        html = '<tr><td colspan="7" class="text-center">لم تتم إضافة أي منتجات بعد</td><tr>';
    } else {
        items.forEach(function(item, index) {
            let total = item.price * item.quantity;
            html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.code}</td>
                    <td>${item.name}</td>
                    <td>${parseFloat(item.price).toFixed(2)}</td>
                    <td>
                        <div class="input-group" style="width: 120px;">
                            <button class="btn btn-sm btn-danger" onclick="updateQuantity(${index}, -1)">-</button>
                            <input type="number" class="form-control text-center quantity-input" value="${item.quantity}" min="1" max="${item.stock}" onchange="setQuantity(${index}, this.value)">
                            <button class="btn btn-sm btn-success" onclick="updateQuantity(${index}, 1)">+</button>
                        </div>
                    </td>
                    <td>${total.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="removeProduct(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#invoice_items').html(html);
}

function updateQuantity(index, delta) {
    let newQuantity = items[index].quantity + delta;
    if(newQuantity >= 1 && newQuantity <= items[index].stock) {
        items[index].quantity = newQuantity;
        refreshItemsTable();
        calculateTotals();
    }
}

function setQuantity(index, value) {
    let newQuantity = parseInt(value);
    if(newQuantity >= 1 && newQuantity <= items[index].stock) {
        items[index].quantity = newQuantity;
        refreshItemsTable();
        calculateTotals();
    }
}

function removeProduct(index) {
    items.splice(index, 1);
    refreshItemsTable();
    calculateTotals();
}

function calculateTotals() {
    let subtotal = 0;
    items.forEach(function(item) {
        subtotal += item.price * item.quantity;
    });
    
    let discountPercent = parseFloat($('#discount_percent').val()) || 0;
    let discountAmount = (subtotal * discountPercent) / 100;
    let total = subtotal - discountAmount;
    
    $('#subtotal_display').text(subtotal.toFixed(2));
    $('#total_display').text(total.toFixed(2));
}

$(document).click(function(e) {
    if(!$(e.target).closest('#search_product, #search_results').length) {
        $('#search_results').hide();
    }
});
</script>

<style>
#search_results {
    position: absolute;
    z-index: 1000;
    width: calc(100% - 16px);
    max-height: 300px;
    overflow-y: auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.quantity-input {
    width: 60px;
    text-align: center;
}
</style>