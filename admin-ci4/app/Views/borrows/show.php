<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('borrows') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0">Transaction #<?= esc($borrow['id']) ?></h5>
            </div>
            <div class="card-body mt-3">
                <div class="row mb-3">
                    <div class="col-md-4 text-muted fw-bold">Member</div>
                    <div class="col-md-8"><?= esc($borrow['member_name']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted fw-bold">Book</div>
                    <div class="col-md-8"><?= esc($borrow['book_title']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted fw-bold">Borrow Date</div>
                    <div class="col-md-8"><?= esc($borrow['borrow_date']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted fw-bold">Due Date</div>
                    <div class="col-md-8"><?= esc($borrow['due_date']) ?></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted fw-bold">Status</div>
                    <div class="col-md-8">
                        <?php if($borrow['status'] === 'borrowed'): ?>
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Borrowed</span>
                        <?php elseif($borrow['status'] === 'returned'): ?>
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Returned</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Overdue</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if($borrow['status'] !== 'returned'): ?>
                    <hr>
                    <div class="text-center mt-4">
                        <a href="<?= base_url('borrows/process_return/' . $borrow['id']) ?>" class="btn btn-success btn-lg w-100"><i class="bi bi-arrow-return-left"></i> Process Return Now</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
