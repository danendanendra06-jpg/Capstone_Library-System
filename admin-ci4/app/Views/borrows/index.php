<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('borrows/new') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Borrow</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="<?= base_url('borrows') ?>" method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search member or book..." value="<?= esc($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <?php if(!empty($search)): ?>
                <a href="<?= base_url('borrows') ?>" class="btn btn-outline-secondary ms-2">Clear</a>
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
                        <th>No</th>
                        <th>Member</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $currentPage = $pager->getCurrentPage() ?? 1;
                        $no = 1 + (10 * ($currentPage - 1));
                        foreach($borrows as $t): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
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
                            <a href="<?= base_url('borrows/' . $t['id']) ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> View</a>
                            <?php if($t['status'] === 'borrowed' || $t['status'] === 'overdue'): ?>
                                <a href="<?= base_url('borrows/process_return/' . $t['id']) ?>" class="btn btn-sm btn-success">Process Return</a>
                            <?php else: ?>
                                <span class="text-muted"><i class="bi bi-check-circle-fill text-success"></i> Completed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($borrows)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">No borrows found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links('default', 'bootstrap_pagination') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
