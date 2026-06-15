<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('books/new') ?>" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Book</a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="<?= base_url('books') ?>" method="get" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search by title, author, or ISBN..." value="<?= esc($search) ?>">
            <button type="submit" class="btn btn-outline-secondary">Search</button>
            <?php if($search): ?>
                <a href="<?= base_url('books') ?>" class="btn btn-link">Clear</a>
            <?php endif; ?>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>ISBN</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($books)): ?>
                        <?php 
                            $currentPage = $pager->getCurrentPage() ?? 1;
                            $no = 1 + (10 * ($currentPage - 1));
                            foreach($books as $book): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= esc($book['title']) ?></strong></td>
                                <td><?= esc($book['author']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc($book['category_name']) ?></span></td>
                                <td><?= esc($book['isbn'] ?: 'N/A') ?></td>
                                <td>
                                    <?php if($book['stock'] > 0): ?>
                                        <span class="badge bg-success"><?= esc($book['stock']) ?> in stock</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Out of stock</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('books/' . $book['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="<?= base_url('books/' . $book['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Delete this book?');">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No books found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            <?= $pager->links('default', 'bootstrap_pagination') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
