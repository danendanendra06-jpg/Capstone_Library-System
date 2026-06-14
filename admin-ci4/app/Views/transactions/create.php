<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0"><?= esc($title) ?></h5>
    </div>
    <div class="card-body">
        <?php if(isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('transactions/new') ?>" method="post">
            <div class="mb-3">
                <label>Member</label>
                <select name="user_id" class="form-select" required>
                    <option value="">-- Select Member --</option>
                    <?php foreach($users as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= set_select('user_id', $user['id']) ?>><?= esc($user['username']) ?> (<?= esc($user['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Book</label>
                <select name="book_id" class="form-select" required>
                    <option value="">-- Select Book --</option>
                    <?php foreach($books as $book): ?>
                        <option value="<?= $book['id'] ?>" <?= set_select('book_id', $book['id']) ?>><?= esc($book['title']) ?> (Stock: <?= $book['stock'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Borrow Date</label>
                    <input type="date" name="borrow_date" class="form-control" value="<?= set_value('borrow_date', date('Y-m-d')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label>Due Date</label>
                    <input type="date" name="due_date" class="form-control" value="<?= set_value('due_date', date('Y-m-d', strtotime('+7 days'))) ?>" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Transaction</button>
            <a href="<?= base_url('transactions') ?>" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
