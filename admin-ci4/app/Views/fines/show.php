<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><?= esc($title) ?></h4>
    <a href="<?= base_url('fines') ?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Fines</a>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
                <h5>Fine Receipt #<?= esc($fine['id']) ?></h5>
                <?php if($fine['payment_status'] === 'PAID'): ?>
                    <span class="badge bg-success fs-6 mt-2"><i class="bi bi-check-circle"></i> PAID</span>
                <?php else: ?>
                    <span class="badge bg-danger fs-6 mt-2"><i class="bi bi-x-circle"></i> UNPAID</span>
                <?php endif; ?>
            </div>
            <div class="card-body mt-3">
                <table class="table table-borderless">
                    <tr>
                        <th class="text-muted w-50">Member Name</th>
                        <td><?= esc($fine['member_name']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Member Email</th>
                        <td><?= esc($fine['email']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Book Borrowed</th>
                        <td><?= esc($fine['book_title']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Borrow Date</th>
                        <td><?= esc($fine['borrow_date']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Due Date</th>
                        <td><?= esc($fine['due_date']) ?></td>
                    </tr>
                    <tr>
                        <th class="text-muted">Reason</th>
                        <td><?= esc($fine['reason']) ?></td>
                    </tr>
                </table>
                <hr>
                <div class="text-center">
                    <p class="text-muted mb-1">Total Fine Amount</p>
                    <h2 class="text-danger fw-bold">Rp<?= number_format($fine['amount'], 0, ',', '.') ?></h2>
                </div>
                
                <div class="mt-4 text-center">
                    <?php if($fine['payment_status'] === 'UNPAID'): ?>
                        <form action="<?= base_url('fines/mark_paid/' . $fine['id']) ?>" method="post">
                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold">Payment Method</label>
                                <select class="form-select" name="payment_method" id="paymentMethod" onchange="toggleCashInput()">
                                    <option value="CASH">Cash</option>
                                    <option value="ATM">Kartu ATM</option>
                                    <option value="DIGITAL">Digital (DANA, GoPay, dll)</option>
                                </select>
                            </div>
                            
                            <div class="mb-3 text-start" id="cashInputContainer">
                                <label class="form-label fw-bold">Amount Received</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" name="amount_received" id="amountReceived" value="<?= $fine['amount'] ?>" min="<?= $fine['amount'] ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success btn-lg w-100 shadow-sm"><i class="bi bi-cash"></i> Process Payment</button>
                        </form>
                        <script>
                        function toggleCashInput() {
                            const method = document.getElementById('paymentMethod').value;
                            const cashInput = document.getElementById('cashInputContainer');
                            if (method === 'CASH') {
                                cashInput.style.display = 'block';
                            } else {
                                cashInput.style.display = 'none';
                            }
                        }
                        </script>
                    <?php else: ?>
                        <form action="<?= base_url('fines/mark_unpaid/' . $fine['id']) ?>" method="post">
                            <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-arrow-counterclockwise"></i> Revert to Unpaid</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
