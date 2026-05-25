<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-invoice"></i> قائمة الفواتير</h2>
        <a href="<?= base_url('invoices/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> فاتورة جديدة
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>رقم الفاتورة</th>
                            <th>التاريخ</th>
                            <th>العميل</th>
                            <th>المستودع</th>
                            <th>المجموع</th>
                            <th>الخصم</th>
                            <th>الإجمالي</th>
                            <th>بواسطة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($invoices)): ?>
                            <tr>
                                <td colspan="10" class="text-center">لا توجد فواتير</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($invoices as $index => $inv): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <strong><?= $inv->invoice_no ?></strong>
                                    </td>
                                    <td><?= date('Y-m-d', strtotime($inv->date)) ?></td>
                                    <td><?= htmlspecialchars($inv->customer_name) ?></td>
                                    <td><?= htmlspecialchars($inv->warehouse_name) ?></td>
                                    <td class="text-end"><?= number_format($inv->subtotal, 2) ?></td>
                                    <td class="text-end">
                                        <?php if($inv->discount_percent > 0): ?>
                                            <?= $inv->discount_percent ?>% (<?= number_format($inv->discount_amount, 2) ?>)
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <strong><?= number_format($inv->total, 2) ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($inv->created_by_name ?? '-') ?></td>
                                    <td>
                                        <a href="<?= base_url('invoices/view/'.$inv->id) ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($this->session->userdata('role') == 'admin'): ?>
                                            <button class="btn btn-sm btn-danger" onclick="deleteInvoice(<?= $inv->id ?>, '<?= $inv->invoice_no ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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

<script>
function deleteInvoice(id, invoiceNo) {
    if(confirm('هل أنت متأكد من حذف الفاتورة ' + invoiceNo + '؟')) {
        $.ajax({
            url: '<?= base_url("invoices/delete/") ?>' + id,
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