<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0 max-w-sm mx-auto mt-4">
    <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
        <h5 class="mb-0"><?= esc($title) ?></h5>
    </div>
    <div class="card-body p-4">
        <form action="<?= base_url('users/' . $user['id']) ?>" method="post">
            <input type="hidden" name="_method" value="PUT">
            <div class="mb-3">
                <label class="form-label text-muted">Username</label>
                <input type="text" class="form-control" value="<?= esc($user['username']) ?>" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label text-muted">Email</label>
                <input type="email" class="form-control" value="<?= esc($user['email']) ?>" readonly>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Role Assignment</label>
                <select name="role" class="form-select border-primary shadow-sm">
                    <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Member</option>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
                <small class="text-muted d-block mt-2"><i class="bi bi-info-circle"></i> Admins have full access to this dashboard.</small>
            </div>
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save"></i> Update Role</button>
            <a href="<?= base_url('users') ?>" class="btn btn-light w-100 mt-2">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
