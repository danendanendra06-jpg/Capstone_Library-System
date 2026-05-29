<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold"><?= esc($title) ?></h4>
    <div>
        <span class="text-muted"><i class="bi bi-calendar3"></i> <?= date('l, d F Y') ?></span>
    </div>
</div>

<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-primary text-white overflow-hidden rounded-4 position-relative">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-white-50 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Total Books</p>
                        <h2 class="mb-0 fw-bold display-5 mt-1"><?= number_format($total_books) ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-journal-richtext fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-success text-white overflow-hidden rounded-4 position-relative">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-white-50 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Members</p>
                        <h2 class="mb-0 fw-bold display-5 mt-1"><?= number_format($total_users) ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-warning text-dark overflow-hidden rounded-4 position-relative">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-dark-50 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Active Borrows</p>
                        <h2 class="mb-0 fw-bold display-5 mt-1"><?= number_format($active_loans) ?></h2>
                    </div>
                    <div class="bg-dark bg-opacity-10 p-3 rounded-3">
                        <i class="bi bi-arrow-left-right fs-1 text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 bg-danger text-white overflow-hidden rounded-4 position-relative">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-white-50 text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Unpaid Fines</p>
                        <h2 class="mb-0 fw-bold fs-2 mt-1">Rp <?= number_format($total_fines, 0, ',', '.') ?></h2>
                    </div>
                    <div class="bg-white bg-opacity-25 p-3 rounded-3">
                        <i class="bi bi-cash-stack fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Table -->
<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white border-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history text-primary me-2"></i> Recent Borrow Activity</h5>
        <a href="<?= base_url('transactions') ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">View All</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Member</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $count = 0;
                    foreach($recent_borrows as $b): 
                        if($count >= 5) break; 
                        $count++;
                    ?>
                    <tr>
                        <td class="ps-4 fw-semibold text-dark"><?= esc($b['member_name']) ?></td>
                        <td><?= esc($b['book_title']) ?></td>
                        <td><span class="text-muted"><i class="bi bi-calendar2-day me-1"></i> <?= esc($b['borrow_date']) ?></span></td>
                        <td>
                            <?php if($b['status'] === 'borrowed'): ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-hourglass-split me-1"></i> Active</span>
                            <?php elseif($b['status'] === 'returned'): ?>
                                <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check2-all me-1"></i> Returned</span>
                            <?php else: ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-exclamation-triangle me-1"></i> Overdue</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($recent_borrows)): ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No recent activity.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
