<div class="container-fluid">
    <h1 class="mb-4">مرحباً بك، <?= $this->session->userdata('username') ?> 👋</h1>
    
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">المنتجات</h6>
                        <h2 class="mb-0" id="totalProducts">0</h2>
                    </div>
                    <i class="fas fa-boxes" style="font-size: 2.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">الفواتير</h6>
                        <h2 class="mb-0" id="totalInvoices">0</h2>
                    </div>
                    <i class="fas fa-file-invoice" style="font-size: 2.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">المستودعات</h6>
                        <h2 class="mb-0" id="totalWarehouses">0</h2>
                    </div>
                    <i class="fas fa-warehouse" style="font-size: 2.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">العملاء</h6>
                        <h2 class="mb-0" id="totalCustomers">0</h2>
                    </div>
                    <i class="fas fa-users" style="font-size: 2.5rem; color: #667eea;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="stat-card">
                <h5>مرحباً بك في نظام ERP Mini</h5>
                <p class="text-muted">يمكنك من خلال هذا النظام إدارة المنتجات، المستودعات، وإنشاء فواتير البيع بسهولة.</p>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-boxes text-primary"></i>
                        <strong>المنتجات</strong>
                        <small class="d-block text-muted">إدارة المنتجات، البحث، والفلترة</small>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-warehouse text-success"></i>
                        <strong>المستودعات</strong>
                        <small class="d-block text-muted">متعدد المستودعات مع صلاحيات</small>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-file-invoice text-danger"></i>
                        <strong>الفواتير</strong>
                        <small class="d-block text-muted">إنشاء فواتير البيع وحساب المجاميع</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// جلب الإحصائيات عبر AJAX
$(document).ready(function() {
    $.ajax({
        url: '<?= base_url("dashboard/stats") ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#totalProducts').text(data.products);
            $('#totalInvoices').text(data.invoices);
            $('#totalWarehouses').text(data.warehouses);
            $('#totalCustomers').text(data.customers);
        }
    });
});
</script>