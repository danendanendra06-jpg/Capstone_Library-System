<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Dashboard Overview</h4>
        <p class="text-muted mb-0">Here's what's happening with your library today.</p>
    </div>
    <div class="bg-white px-4 py-2 rounded-pill shadow-sm border">
        <span class="text-primary fw-medium"><i class="bi bi-calendar3 me-2"></i> <?= date('l, d F Y') ?></span>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <div class="card-body p-4 position-relative">
                <i class="bi bi-journal-richtext position-absolute text-white opacity-25" style="font-size: 5rem; right: -10px; bottom: -20px;"></i>
                <p class="mb-1 text-white text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Total Books</p>
                <h2 class="mb-0 fw-bold display-5 text-white"><?= number_format($total_books) ?></h2>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <div class="card-body p-4 position-relative">
                <i class="bi bi-people-fill position-absolute text-white opacity-25" style="font-size: 5rem; right: -10px; bottom: -20px;"></i>
                <p class="mb-1 text-white text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Members</p>
                <h2 class="mb-0 fw-bold display-5 text-white"><?= number_format($total_users) ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
            <div class="card-body p-4 position-relative">
                <i class="bi bi-arrow-left-right position-absolute text-white opacity-25" style="font-size: 5rem; right: -10px; bottom: -20px;"></i>
                <p class="mb-1 text-white text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Active Borrows</p>
                <h2 class="mb-0 fw-bold display-5 text-white"><?= number_format($active_loans) ?></h2>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 overflow-hidden" style="background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);">
            <div class="card-body p-4 position-relative">
                <i class="bi bi-cash-stack position-absolute text-white opacity-25" style="font-size: 5rem; right: -10px; bottom: -20px;"></i>
                <p class="mb-1 text-white text-uppercase fw-semibold" style="letter-spacing: 1px; font-size: 0.8rem;">Unpaid Fines</p>
                <h2 class="mb-0 fw-bold fs-2 text-white">Rp <?= number_format($total_fines, 0, ',', '.') ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Chart Area -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-dark"><i class="bi bi-bar-chart-fill text-primary me-2"></i> Library Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="dashboardChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history text-info me-2"></i> Recent Borrows</h5>
                <a href="<?= base_url('transactions') ?>" class="btn btn-sm btn-light text-primary fw-medium rounded-pill px-3">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php 
                    $count = 0;
                    foreach($recent_borrows as $b): 
                        if($count >= 5) break; 
                        $count++;
                    ?>
                    <div class="list-group-item border-0 py-3 px-4 mb-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1 fw-bold text-dark"><?= esc($b['book_title']) ?></h6>
                                <small class="text-muted"><i class="bi bi-person me-1"></i><?= esc($b['member_name']) ?> &bull; <?= esc($b['borrow_date']) ?></small>
                            </div>
                            <div>
                                <?php if($b['status'] === 'borrowed'): ?>
                                    <span class="badge bg-warning bg-opacity-25 text-warning-emphasis rounded-pill px-3 py-2 border border-warning-subtle">Active</span>
                                <?php elseif($b['status'] === 'returned'): ?>
                                    <span class="badge bg-success bg-opacity-25 text-success-emphasis rounded-pill px-3 py-2 border border-success-subtle">Returned</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-25 text-danger-emphasis rounded-pill px-3 py-2 border border-danger-subtle">Overdue</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if(empty($recent_borrows)): ?>
                        <div class="text-center py-5 text-muted">No recent activity.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('dashboardChart').getContext('2d');
    
    // Gradient for the bars
    let gradientBooks = ctx.createLinearGradient(0, 0, 0, 400);
    gradientBooks.addColorStop(0, '#4facfe');
    gradientBooks.addColorStop(1, '#00f2fe');

    let gradientMembers = ctx.createLinearGradient(0, 0, 0, 400);
    gradientMembers.addColorStop(0, '#43e97b');
    gradientMembers.addColorStop(1, '#38f9d7');

    let gradientBorrows = ctx.createLinearGradient(0, 0, 0, 400);
    gradientBorrows.addColorStop(0, '#fa709a');
    gradientBorrows.addColorStop(1, '#fee140');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Books', 'Members', 'Active Borrows'],
            datasets: [{
                label: 'Library Statistics',
                data: [
                    <?= esc($total_books) ?>, 
                    <?= esc($total_users) ?>, 
                    <?= esc($active_loans) ?>
                ],
                backgroundColor: [gradientBooks, gradientMembers, gradientBorrows],
                borderRadius: 8,
                borderSkipped: false,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 28, 67, 0.9)',
                    titleFont: { family: 'Poppins', size: 14 },
                    bodyFont: { family: 'Poppins', size: 14 },
                    padding: 12,
                    cornerRadius: 8
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)', borderDash: [5, 5] },
                    ticks: { font: { family: 'Poppins' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Poppins', weight: '500' } }
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>
