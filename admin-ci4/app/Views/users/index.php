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
        <form action="<?= base_url('users') ?>" method="get" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by username or email..." value="<?= esc($search) ?>">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <?php if(!empty($search)): ?>
                <a href="<?= base_url('users') ?>" class="btn btn-outline-secondary ms-2">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= esc($u['id']) ?></td>
                        <td class="fw-bold"><?= esc($u['username']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td>
                            <?php if($u['role'] === 'admin'): ?>
                                <span class="badge bg-danger"><i class="bi bi-shield-lock"></i> Admin</span>
                            <?php else: ?>
                                <span class="badge bg-info text-dark"><i class="bi bi-person"></i> Member</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $status = $u['status'] ?? 'ACTIVE'; 
                                if($status === 'ACTIVE'): 
                            ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($u['role'] !== 'admin'): ?>
                                <?php if($status === 'ACTIVE'): ?>
                                    <form action="<?= base_url('users/' . $u['id'] . '/suspend') ?>" method="post" class="d-inline" onsubmit="return confirm('Suspend this member?');">
                                        <button class="btn btn-sm btn-outline-warning" title="Suspend"><i class="bi bi-pause-circle"></i></button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= base_url('users/' . $u['id'] . '/activate') ?>" method="post" class="d-inline" onsubmit="return confirm('Activate this member?');">
                                        <button class="btn btn-sm btn-outline-success" title="Activate"><i class="bi bi-play-circle"></i></button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= base_url('users/' . $u['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this member?');">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted fst-italic">No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($users)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No members found.</td></tr>
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
