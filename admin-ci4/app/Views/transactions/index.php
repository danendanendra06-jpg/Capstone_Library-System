<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('transactions/new') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Borrow</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($transactions as $t): ?>
                    <tr>
                        <td>#<?= esc($t['id']) ?></td>
                        <td><?= esc($t['member_name']) ?></td>
                        <td><?= esc($t['book_title']) ?></td>
                        <td><?= esc($t['borrow_date']) ?></td>
                        <td><?= esc($t['due_date']) ?></td>
                        <td>
                            <?php if($t['status'] === 'borrowed'): ?>
                                <span class="badge bg-warning text-dark">Borrowed</span>
                            <?php elseif($t['status'] === 'returned'): ?>
                                <span class="badge bg-success">Returned</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Overdue</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($t['status'] === 'borrowed' || $t['status'] === 'overdue'): ?>
                                <a href="<?= base_url('transactions/process_return/' . $t['id']) ?>" class="btn btn-sm btn-success">Process Return</a>
                            <?php else: ?>
                                <span class="text-muted"><i class="bi bi-check-circle-fill text-success"></i> Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($transactions)): ?>
                        <tr><td colspan="7" class="text-center">No borrow transactions found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
