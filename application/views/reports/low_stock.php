<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-exclamation-triangle"></i> تقرير المخزون المنخفض</h2>
        <div>
            <button onclick="window.print();" class="btn btn-secondary">
                <i class="fas fa-print"></i> طباعة
            </button>
            <a href="<?= base_url('reports/export_csv') ?><?= $this->input->get('warehouse_id') ? '?warehouse_id=' . $this->input->get('warehouse_id') : '' ?>" class="btn btn-success">
                <i class="fas fa-download"></i> تصدير CSV
            </a>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">منتجات منخفضة</h5>
                            <h2 class="mb-0"><?= $stats['total_products'] ?></h2>
                        </div>
                        <i class="fas fa-boxes fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">منتجات منعدمة</h5>
                            <h2 class="mb-0"><?= $stats['critical_count'] ?></h2>
                        </div>
                        <i class="fas fa-ban fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">منتجات حرجة</h5>
                            <h2 class="mb-0"><?= $stats['warning_count'] ?></h2>
                        </div>
                        <i class="fas fa-hourglass-half fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title">إجمالي النقص</h5>
                            <h2 class="mb-0"><?= $stats['total_shortage'] ?></h2>
                        </div>
                        <i class="fas fa-calculator fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label>فلتر حسب المستودع</label>
                    <select id="warehouse_filter" class="form-control">
                        <option value="">جميع المستودعات</option>
                        <?php foreach($warehouses as $w): ?>
                            <option value="<?= $w->id ?>" <?= $this->input->get('warehouse_id') == $w->id ? 'selected' : '' ?>>
                                <?= $w->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button id="filter_btn" class="btn btn-primary form-control">بحث</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5><i class="fas fa-list"></i> قائمة المنتجات المنخفضة المخزون</h5>
        </div>
        <div class="card-body">
            <?php if(empty($low_stock_products)): ?>
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle fa-3x"></i>
                    <h4>لا توجد منتجات منخفضة المخزون</h4>
                    <p>جميع المنتجات لديها مخزون كافٍ</p>
                </div>
            <?php else: ?>
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
                                <th>مقدار النقص</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($low_stock_products as $index => $product): ?>
                                <?php
                                    $status_class = '';
                                    $status_text = '';
                                    if($product->quantity == 0) {
                                        $status_class = 'danger';
                                        $status_text = 'منعدم';
                                    } elseif($product->quantity <= $product->alert_quantity / 2) {
                                        $status_class = 'warning';
                                        $status_text = 'حرج';
                                    } else {
                                        $status_class = 'info';
                                        $status_text = 'منخفض';
                                    }
                                ?>
                                <tr class="table-<?= $status_class ?>">
                                    <td><?= $index + 1 ?></td>
                                    <td><strong><?= $product->code ?></strong></td>
                                    <td><?= htmlspecialchars($product->name) ?></td>
                                    <td><?= htmlspecialchars($product->warehouse_name) ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $status_class ?> fs-6"><?= $product->quantity ?></span>
                                    </td>
                                    <td class="text-center"><?= $product->alert_quantity ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger">
                                            بحاجة <?= $product->shortage ?> قطعة
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-<?= $status_class ?>"><?= $status_text ?></span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?= base_url('stock#product_' . $product->id) ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> تحديث المخزون
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">الإجمالي:</th>
                                <th class="text-center"><?= array_sum(array_column($low_stock_products, 'quantity')) ?></th>
                                <th></th>
                                <th class="text-center"><?= array_sum(array_column($low_stock_products, 'shortage')) ?></th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5><i class="fas fa-lightbulb"></i> توصيات</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <?php if($stats['total_products'] > 0): ?>
                    <li class="list-group-item">
                        <i class="fas fa-shopping-cart text-danger"></i>
                        تحتاج إلى طلب <strong><?= $stats['total_shortage'] ?></strong> قطعة لتغطية النقص
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-clock text-warning"></i>
                        هناك <strong><?= $stats['warning_count'] ?></strong> منتج بحاجة إعادة طلب عاجلة
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-ban text-danger"></i>
                        هناك <strong><?= $stats['critical_count'] ?></strong> منتج نفد بالكامل من المخزون
                    </li>
                <?php else: ?>
                    <li class="list-group-item text-success">
                        <i class="fas fa-check-circle"></i>
                        جميع المنتجات لديها مخزون كافٍ، لا توجد توصيات
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#filter_btn').click(function() {
        let warehouse_id = $('#warehouse_filter').val();
        let url = '<?= base_url("reports/low_stock") ?>';
        
        if(warehouse_id) {
            url += '?warehouse_id=' + warehouse_id;
        }
        
        window.location.href = url;
    });
    

    $('#warehouse_filter').on('keypress', function(e) {
        if(e.which == 13) {
            $('#filter_btn').click();
        }
    });
});
</script>

<style>
@media print {
    .btn, #filter_btn, .card-header .btn, .d-flex .btn {
        display: none !important;
    }
    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>