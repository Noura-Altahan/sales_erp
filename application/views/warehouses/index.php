<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-warehouse"></i> إدارة المستودعات</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWarehouseModal">
            <i class="fas fa-plus"></i> إضافة مستودع جديد
        </button>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المستودع</th>
                            <th>الموقع</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                         </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($warehouses)): ?>
                            <tr>
                                <td colspan="6" class="text-center">لا توجد مستودعات</td>
                             </tr>
                        <?php else: ?>
                            <?php foreach($warehouses as $index => $w): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= htmlspecialchars($w->name) ?></strong></td>
                                    <td><?= htmlspecialchars($w->location ?? '-') ?></td>
                                    <td>
                                        <?php if($w->is_active == 1): ?>
                                            <span class="badge bg-success">نشط</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">معطل</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($w->created_at)) ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editWarehouse(<?= $w->id ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="deleteWarehouse(<?= $w->id ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                 </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Warehouse Modal -->
<div class="modal fade" id="addWarehouseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> إضافة مستودع جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addWarehouseForm">
                    <div class="mb-3">
                        <label>اسم المستودع *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>الموقع</label>
                        <input type="text" name="location" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="saveWarehouseBtn" class="btn btn-primary">حفظ</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> تعديل المستودع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editWarehouseForm">
                    <input type="hidden" name="warehouse_id" id="edit_warehouse_id">
                    <div class="mb-3">
                        <label>اسم المستودع *</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>الموقع</label>
                        <input type="text" name="location" id="edit_location" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" id="updateWarehouseBtn" class="btn btn-primary">تحديث</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Save warehouse
    $('#saveWarehouseBtn').click(function() {
        $.ajax({
            url: '<?= base_url("warehouses/add") ?>',
            type: 'POST',
            data: $('#addWarehouseForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#addWarehouseModal').modal('hide');
                    $('#addWarehouseForm')[0].reset();
                    location.reload();
                }
                alert(response.message);
            }
        });
    });
    
    // Update warehouse
    $('#updateWarehouseBtn').click(function() {
        let id = $('#edit_warehouse_id').val();
        $.ajax({
            url: '<?= base_url("warehouses/edit/") ?>' + id,
            type: 'POST',
            data: $('#editWarehouseForm').serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('#editWarehouseModal').modal('hide');
                    location.reload();
                }
                alert(response.message);
            }
        });
    });
});

function editWarehouse(id) {
    $.ajax({
        url: '<?= base_url("warehouses/get_warehouse/") ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(warehouse) {
            $('#edit_warehouse_id').val(warehouse.id);
            $('#edit_name').val(warehouse.name);
            $('#edit_location').val(warehouse.location);
            $('#editWarehouseModal').modal('show');
        }
    });
}

function deleteWarehouse(id) {
    if(confirm('هل أنت متأكد من حذف هذا المستودع؟')) {
        $.ajax({
            url: '<?= base_url("warehouses/delete/") ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                alert(response.message);
                if(response.success) {
                    location.reload();
                }
            }
        });
    }
}
</script>