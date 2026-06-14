<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="<?= base_url('fines') ?>" method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search member or book..." value="<?= esc($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <?php if(!empty($search)): ?>
                <a href="<?= base_url('fines') ?>" class="btn btn-outline-secondary ms-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($fines as $fine): ?>
                    <tr>
                        <td>#<?= esc($fine['id']) ?></td>
                        <td><?= esc($fine['member_name']) ?></td>
                        <td><?= esc($fine['book_title']) ?></td>
                        <td class="fw-bold">Rp<?= number_format($fine['amount'], 0, ',', '.') ?></td>
                        <td>
                            <?php if($fine['payment_status'] === 'PAID'): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Paid</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('fines/' . $fine['id']) ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> View</a>
                            <?php if($fine['payment_status'] === 'UNPAID'): ?>
                                <form action="<?= base_url('fines/mark_paid/' . $fine['id']) ?>" method="post" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Mark this fine as paid?');"><i class="bi bi-cash"></i> Pay</button>
                                </form>
                            <?php else: ?>
                                <form action="<?= base_url('fines/mark_unpaid/' . $fine['id']) ?>" method="post" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Revert to unpaid?');"><i class="bi bi-arrow-counterclockwise"></i> Unpay</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($fines)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No fines found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <?= $pager->links() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
