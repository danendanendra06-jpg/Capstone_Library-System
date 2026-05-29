<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="card shadow-sm"><div class="card-body">
    <h4 class="mb-3"><?= esc($title) ?></h4>
    <table class="table">
        <thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($users as $u): ?>
            <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['username']) ?></td>
                <td><?= esc($u['email']) ?></td>
                <td><span class="badge bg-<?= $u['role'] == 'admin' ? 'danger' : 'info' ?>"><?= esc($u['role']) ?></span></td>
                <td>
                    <a href="/users/<?= $u['id'] ?>/edit" class="btn btn-sm btn-info">Edit</a>
                    <form action="/users/<?= $u['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('Delete?');">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $pager->links() ?>
</div></div>
<?= $this->endSection() ?>
