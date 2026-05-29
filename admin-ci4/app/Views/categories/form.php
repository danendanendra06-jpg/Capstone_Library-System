<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm border-0 max-w-lg mx-auto">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0"><?= esc($title) ?></h5>
    </div>
    <div class="card-body p-4">
        <?php if(session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($category) ? base_url('categories/'.$category['id']) : base_url('categories') ?>" method="post">
            <?php if(isset($category)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4"><?= old('description', $category['description'] ?? '') ?></textarea>
            </div>
            <hr class="mt-4 mb-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Category</button>
            <a href="<?= base_url('categories') ?>" class="btn btn-light ms-2">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
