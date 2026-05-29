<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 max-w-lg mx-auto">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0 text-center"><?= esc($title) ?></h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Borrow ID:</strong> #<?= esc($borrow['id']) ?><br>
            <strong>Due Date:</strong> <?= esc($borrow['due_date']) ?><br>
            <strong>Current Date:</strong> <?= esc($currentDate) ?>
        </div>
        
        <?php if($overdueDays > 0): ?>
            <div class="alert alert-danger text-center">
                <h5><i class="bi bi-exclamation-triangle"></i> Book is Overdue!</h5>
                <p class="mb-1">Overdue by: <strong><?= $overdueDays ?> days</strong></p>
                <h3 class="mb-0">Fine: Rp<?= number_format($fineAmount, 0, ',', '.') ?></h3>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center">
                <h5><i class="bi bi-check-circle"></i> On Time</h5>
                <p class="mb-0">No fines will be issued.</p>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('transactions/process_return/' . $borrow['id']) ?>" method="post" class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg w-100"><i class="bi bi-arrow-return-left"></i> Confirm Return</button>
            <a href="<?= base_url('transactions') ?>" class="btn btn-outline-secondary mt-3 w-100">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
