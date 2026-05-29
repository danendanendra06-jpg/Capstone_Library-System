<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between mb-3">
    <h4><?= esc($title) ?></h4>
    <a href="/categories/new" class="btn btn-primary">Add Category</a>
</div>
<div class="card shadow-sm"><div class="card-body">
    <table class="table">
        <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Actions</th></tr></thead>
        <tbody>
            <?php foreach($categories as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['name']) ?></td>
                <td><?= esc($c['description']) ?></td>
                <td>
                    <a href="/categories/<?= $c['id'] ?>/edit" class="btn btn-sm btn-info">Edit</a>
                    <form action="/categories/<?= $c['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('Delete?');">
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
