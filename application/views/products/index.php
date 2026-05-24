<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-boxes"></i> إدارة المنتجات</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i> إضافة منتج جديد
        </button>
    </div>
    
    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="بحث بالكود أو الاسم...">
                </div>
                <div class="col-md-4">
                    <select id="categoryFilter" class="form-control">
                        <option value="">جميع التصنيفات</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button id="searchBtn" class="btn btn-primary w-100">بحث</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الكود</th>
                            <th>الاسم</th>
                            <th>التصنيف</th>
                            <th>سعر البيع</th>
                            <th>سعر الشراء</th>
                            <th>كمية التنبيه</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <tr>
                            <td colspan="9" class="text-center">جاري التحميل...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div id="pagination" class="d-flex justify-content-center mt-3"></div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> إضافة منتج جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>كود المنتج *</label>
                            <input type="text" name="code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>اسم المنتج *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>التصنيف</label>
                            <select name="category_id" class="form-control">
                                <option value="">بدون تصنيف</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>سعر البيع *</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>سعر الشراء</label>
                            <input type="number" name="cost" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>كمية التنبيه</label>
                            <input type="number" name="alert_quantity" class="form-control" value="5">
                        </div>
                        <div class="col-12 mb-3">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="saveProductBtn" class="btn btn-primary">حفظ المنتج</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> تعديل المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" name="product_id" id="edit_product_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>كود المنتج *</label>
                            <input type="text" name="code" id="edit_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>اسم المنتج *</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>التصنيف</label>
                            <select name="category_id" id="edit_category_id" class="form-control">
                                <option value="">بدون تصنيف</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat->id ?>"><?= $cat->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>سعر البيع *</label>
                            <input type="number" name="price" id="edit_price" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>سعر الشراء</label>
                            <input type="number" name="cost" id="edit_cost" class="form-control" step="0.01">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>كمية التنبيه</label>
                            <input type="number" name="alert_quantity" id="edit_alert_quantity" class="form-control">
                        </div>
                        <div class="col-12 mb-3">
                            <label>الوصف</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="updateProductBtn" class="btn btn-primary">تحديث المنتج</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPage = 1;

$(document).ready(function() {
    loadProducts();
    
    // Search button click
    $('#searchBtn').click(function() {
        currentPage = 1;
        loadProducts();
    });
    
    // Enter key search
    $('#searchInput').keypress(function(e) {
        if(e.which == 13) {
            currentPage = 1;
            loadProducts();
        }
    });
    
    // Save product
    $('#saveProductBtn').click(function() {
        $.ajax({
            url: '<?= base_url("products/add") ?>',
            type: 'POST',
            data: $('#addProductForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#addProductModal').modal('hide');
                    $('#addProductForm')[0].reset();
                    loadProducts();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    });
    
    // Update product
    $('#updateProductBtn').click(function() {
        let id = $('#edit_product_id').val();
        $.ajax({
            url: '<?= base_url("products/edit/") ?>' + id,
            type: 'POST',
            data: $('#editProductForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#editProductModal').modal('hide');
                    loadProducts();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    });
});

function loadProducts() {
    let search = $('#searchInput').val();
    let category_id = $('#categoryFilter').val();
    
    $.ajax({
        url: '<?= base_url("products/ajax_list") ?>',
        type: 'GET',
        data: {
            search: search,
            category_id: category_id,
            page: currentPage
        },
        dataType: 'json',
        success: function(response) {
            displayProducts(response.products);
            displayPagination(response);
        }
    });
}

function displayProducts(products) {
    let html = '';
    
    if(products.length === 0) {
        html = '<tr><td colspan="9" class="text-center">لا توجد منتجات</td></tr>';
    } else {
        products.forEach(function(product, index) {
            let statusBadge = product.is_active == 1 ? 
                '<span class="badge bg-success">نشط</span>' : 
                '<span class="badge bg-danger">معطل</span>';
            
            html += `
                <tr>
                    <td>${((currentPage-1)*10) + index + 1}</td>
                    <td><strong>${product.code}</strong></td>
                    <td>${product.name}</td>
                    <td>${product.category_name || '-'}</td>
                    <td>${parseFloat(product.price).toFixed(2)}</td>
                    <td>${parseFloat(product.cost).toFixed(2)}</td>
                    <td>${product.alert_quantity}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editProduct(${product.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
    }
    
    $('#productsTableBody').html(html);
}

function displayPagination(data) {
    let html = '<nav><ul class="pagination">';
    
    if(data.current_page > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${data.current_page - 1})">السابق</a></li>`;
    }
    
    for(let i = 1; i <= data.last_page; i++) {
        if(i <= 5 || i > data.last_page - 2 || Math.abs(i - data.current_page) <= 1) {
            html += `<li class="page-item ${i == data.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                     </li>`;
        } else if(i == 6 && data.current_page > 4) {
            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
        }
    }
    
    if(data.current_page < data.last_page) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${data.current_page + 1})">التالي</a></li>`;
    }
    
    html += '</ul></nav>';
    $('#pagination').html(html);
}

function changePage(page) {
    currentPage = page;
    loadProducts();
}

function editProduct(id) {
    $.ajax({
        url: '<?= base_url("products/get_product/") ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(product) {
            $('#edit_product_id').val(product.id);
            $('#edit_code').val(product.code);
            $('#edit_name').val(product.name);
            $('#edit_category_id').val(product.category_id);
            $('#edit_price').val(product.price);
            $('#edit_cost').val(product.cost);
            $('#edit_alert_quantity').val(product.alert_quantity);
            $('#edit_description').val(product.description);
            $('#editProductModal').modal('show');
        }
    });
}

function deleteProduct(id) {
    if(confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
        $.ajax({
            url: '<?= base_url("products/delete/") ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    loadProducts();
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        });
    }
}
</script>