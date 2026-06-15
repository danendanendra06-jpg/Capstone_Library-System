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

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="<?= base_url('borrows/returns') ?>" method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search member or book..." value="<?= esc($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <?php if(!empty($search)): ?>
                <a href="<?= base_url('borrows/returns') ?>" class="btn btn-outline-secondary ms-2">Clear</a>
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
                        <th>Borrow ID</th>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Return Date</th>
                        <th>Fine Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($returns as $r): ?>
                    <tr>
                        <td>#<?= esc($r['id']) ?></td>
                        <td>#<?= esc($r['borrow_id']) ?></td>
                        <td><?= esc($r['member_name']) ?></td>
                        <td><?= esc($r['book_title']) ?></td>
                        <td><?= esc($r['return_date']) ?></td>
                        <td>
                            <?php if($r['fine_amount'] > 0): ?>
                                <span class="text-danger fw-bold">Rp<?= number_format($r['fine_amount'], 0, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="text-success">Rp0</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($returns)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No returns found.</td></tr>
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
