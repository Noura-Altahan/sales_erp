<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-file-invoice"></i> تفاصيل الفاتورة</h4>
                <div>
                    <a href="<?= base_url('invoices') ?>" class="btn btn-light">
                        <i class="fas fa-arrow-right"></i> العودة للقائمة
                    </a>
                    <button onclick="window.print();" class="btn btn-light">
                        <i class="fas fa-print"></i> طباعة
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            
            <!-- معلومات الفاتورة -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <strong>معلومات الفاتورة</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>رقم الفاتورة:</strong></td>
                                    <td><?= $invoice->invoice_no ?></td>
                                </tr>
                                <tr>
                                    <td><strong>التاريخ:</strong></td>
                                    <td><?= date('Y-m-d H:i:s', strtotime($invoice->date)) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>المستودع:</strong></td>
                                    <td><?= htmlspecialchars($invoice->warehouse_name) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>بواسطة:</strong></td>
                                    <td><?= htmlspecialchars($invoice->created_by_name ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <strong>معلومات العميل</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td width="40%"><strong>الاسم:</strong></td>
                                    <td><?= htmlspecialchars($invoice->customer_name) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>الهاتف:</strong></td>
                                    <td><?= htmlspecialchars($invoice->customer_phone ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>البريد الإلكتروني:</strong></td>
                                    <td><?= htmlspecialchars($invoice->customer_email ?? '-') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>العنوان:</strong></td>
                                    <td><?= htmlspecialchars($invoice->customer_address ?? '-') ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>كود المنتج</th>
                            <th>اسم المنتج</th>
                            <th class="text-end">السعر</th>
                            <th class="text-end">الكمية</th>
                            <th class="text-end">الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $index => $item): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $item->code ?></td>
                                <td><?= htmlspecialchars($item->product_name) ?></td>
                                <td class="text-end"><?= number_format($item->price, 2) ?></td>
                                <td class="text-end"><?= $item->quantity ?></td>
                                <td class="text-end"><?= number_format($item->total, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="5" class="text-end">المجموع الفرعي:</th>
                            <th class="text-end"><?= number_format($invoice->subtotal, 2) ?></th>
                        </tr>
                        <?php if($invoice->discount_percent > 0): ?>
                            <tr>
                                <th colspan="5" class="text-end">الخصم (<?= $invoice->discount_percent ?>%):</th>
                                <th class="text-end">- <?= number_format($invoice->discount_amount, 2) ?></th>
                            </tr>
                        <?php endif; ?>
                        <tr class="table-primary">
                            <th colspan="5" class="text-end">الإجمالي النهائي:</th>
                            <th class="text-end"><?= number_format($invoice->total, 2) ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header .btn, .card-header .d-flex .btn {
        display: none !important;
    }
    .card-header .d-flex {
        display: block !important;
    }
}
</style>