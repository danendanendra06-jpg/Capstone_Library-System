<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('categories/new') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Category</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $currentPage = $pager->getCurrentPage() ?? 1;
                        $no = 1 + (10 * ($currentPage - 1));
                        foreach($categories as $c): 
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td class="fw-bold"><?= esc($c['name']) ?></td>
                        <td class="text-muted"><?= esc($c['description']) ?></td>
                        <td>
                            <a href="<?= base_url('categories/' . $c['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <form action="<?= base_url('categories/' . $c['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this category?');">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($categories)): ?>
                        <tr><td colspan="4" class="text-center py-4 text-muted">No categories found.</td></tr>
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
