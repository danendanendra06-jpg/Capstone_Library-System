<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<div class="card shadow-sm"><div class="card-body">
    <h5 class="card-title"><?= esc($title) ?></h5>
    <form action="<?= isset($category) ? '/categories/'.$category['id'] : '/categories' ?>" method="post">
        <?php if(isset($category)): ?><input type="hidden" name="_method" value="PUT"><?php endif; ?>
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= old('name', $category['name'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control"><?= old('description', $category['description'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="/categories" class="btn btn-secondary">Cancel</a>
    </form>
</div></div>
<?= $this->endSection() ?>
