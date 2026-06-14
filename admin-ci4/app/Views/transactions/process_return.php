<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="card shadow-sm border-0 max-w-lg mx-auto">
    <div class="card-header bg-white border-0 pt-4 pb-0">
        <h5 class="mb-0 text-center"><?= esc($title) ?></h5>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Borrow ID:</strong> #<?= esc($borrow['id']) ?><br>
            <strong>Due Date:</strong> <?= esc($borrow['due_date']) ?><br>
            <strong>Current Date:</strong> <?= esc($currentDate) ?>
        </div>
        
        <?php if($overdueDays > 0): ?>
            <div class="alert alert-danger text-center">
                <h5><i class="bi bi-exclamation-triangle"></i> Book is Overdue!</h5>
                <p class="mb-1">Overdue by: <strong><?= $overdueDays ?> days</strong></p>
                <h3 class="mb-0">Fine: Rp<?= number_format($fineAmount, 0, ',', '.') ?></h3>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center">
                <h5><i class="bi bi-check-circle"></i> On Time</h5>
                <p class="mb-0">No fines will be issued.</p>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('transactions/process_return/' . $borrow['id']) ?>" method="post" class="mt-4">
            <div class="mb-3 text-start">
                <label for="condition" class="form-label font-weight-bold">Book Condition</label>
                <select name="condition" id="condition" class="form-select" onchange="toggleAdditionalFine()">
                    <option value="good">Good (No additional fine)</option>
                    <option value="damaged">Damaged</option>
                    <option value="lost">Lost</option>
                </select>
            </div>
            
            <div class="mb-3 text-start" id="damageDetailsDiv" style="display: none;">
                <label for="damage_type" class="form-label font-weight-bold">Jenis Kerusakan</label>
                <select name="damage_type" id="damage_type" class="form-select mb-2">
                    <option value="Ringan">Ringan</option>
                    <option value="Sedang">Sedang</option>
                    <option value="Parah">Parah</option>
                </select>
                <label for="damage_note" class="form-label font-weight-bold">Keterangan Kerusakan</label>
                <textarea name="damage_note" id="damage_note" class="form-control mb-2" rows="2" placeholder="Detail kerusakan..."></textarea>
            </div>

            <div class="mb-3 text-start" id="additionalFineDiv" style="display: none;">
                <label for="additional_fine" class="form-label font-weight-bold">Nominal Denda Tambahan (Rp) untuk Rusak/Hilang</label>
                <input type="number" name="additional_fine" id="additional_fine" class="form-control" value="0" min="0">
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 mt-3"><i class="bi bi-arrow-return-left"></i> Confirm Return</button>
            <a href="<?= base_url('transactions') ?>" class="btn btn-outline-secondary mt-3 w-100">Cancel</a>
        </form>

        <script>
            function toggleAdditionalFine() {
                var condition = document.getElementById('condition').value;
                var divAdditional = document.getElementById('additionalFineDiv');
                var divDamage = document.getElementById('damageDetailsDiv');
                
                if(condition === 'damaged' || condition === 'lost') {
                    divAdditional.style.display = 'block';
                } else {
                    divAdditional.style.display = 'none';
                    document.getElementById('additional_fine').value = 0;
                }

                if(condition === 'damaged') {
                    divDamage.style.display = 'block';
                } else {
                    divDamage.style.display = 'none';
                }
            }
        </script>
    </div>
</div>
<?= $this->endSection() ?>
