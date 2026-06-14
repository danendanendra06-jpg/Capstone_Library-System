<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0">Generate Reports</h5>
    </div>
    <div class="card-body">
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('reports/generate') ?>" method="post">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Report Type</label>
                    <select name="report_type" class="form-select" required>
                        <option value="borrows">Borrow Transactions (Active)</option>
                        <option value="returns">Return Transactions</option>
                        <option value="fines">Fines</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Format</label>
                    <select name="format" class="form-select" required>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-file-earmark-arrow-down"></i> Generate Report</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
