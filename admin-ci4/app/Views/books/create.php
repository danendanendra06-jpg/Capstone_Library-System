<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0">
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

        <form action="<?= isset($book) ? base_url('books/' . esc($book['id'])) : base_url('books') ?>" method="post">
            <?php if(isset($book)): ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="<?= old('title', $book['title'] ?? '') ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Author</label>
                    <input type="text" name="author" class="form-control" value="<?= old('author', $book['author'] ?? '') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Select Category...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= old('category_id', $book['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="<?= old('isbn', $book['isbn'] ?? '') ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Stock Quantity</label>
                    <input type="number" name="stock" class="form-control" value="<?= old('stock', $book['stock'] ?? '0') ?>" required min="0">
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Book</button>
            <a href="<?= base_url('books') ?>" class="btn btn-light ms-2">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
