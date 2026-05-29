<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="card shadow-sm"><div class="card-body">
    <h5 class="card-title"><?= esc($title) ?></h5>
    <form action="/users/<?= $user['id'] ?>" method="post">
        <input type="hidden" name="_method" value="PUT">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" class="form-control" value="<?= esc($user['username']) ?>" readonly>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select">
                <option value="member" <?= $user['role'] == 'member' ? 'selected' : '' ?>>Member</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
        <a href="/users" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
<?= $this->endSection() ?>
