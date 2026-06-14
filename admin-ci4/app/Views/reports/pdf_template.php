<!DOCTYPE html>
<html>
<head>
    <title>Report - <?= ucfirst($type) ?></title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Library System - <?= ucfirst($type) ?> Report</h2>
    <p>Generated on: <?= date('Y-m-d H:i:s') ?></p>
    
    <table>
        <thead>
            <?php if ($type === 'fines'): ?>
                <tr>
                    <th>ID</th>
                    <th>Member</th>
                    <th>Book</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
            <?php else: ?>
                <tr>
                    <th>ID</th>
                    <th>Member</th>
                    <th>Book</th>
                    <th>Borrow Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Condition</th>
                </tr>
            <?php endif; ?>
        </thead>
        <tbody>
            <?php foreach ($data as $item): ?>
                <?php if ($type === 'fines'): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['member_name'] ?></td>
                        <td><?= $item['book_title'] ?></td>
                        <td>Rp<?= number_format($item['amount'], 0, ',', '.') ?></td>
                        <td><?= $item['fine_type'] ?></td>
                        <td><?= $item['payment_status'] ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['member_name'] ?></td>
                        <td><?= $item['book_title'] ?></td>
                        <td><?= $item['borrow_date'] ?></td>
                        <td><?= $item['due_date'] ?></td>
                        <td><?= $item['status'] ?></td>
                        <td><?= $item['return_condition'] ?? '-' ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if(empty($data)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
