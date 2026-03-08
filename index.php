<?php
$page_title = 'Investment Dashboard';
require_once __DIR__ . '/includes/db.php';

$stmt = $pdo->query('SELECT ID, type, description, amt, exch_rate, qty, amt_hkd FROM asset ORDER BY ID');
$assets = $stmt->fetchAll();

$total_hkd = 0;
foreach ($assets as $a) {
    $total_hkd += (float) $a['amt_hkd'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="static/style.css">
</head>
<body>
    <header class="header">
        <h1><?php echo htmlspecialchars($page_title); ?></h1>
        <p class="total">Total (HKD): <strong><?php echo number_format($total_hkd, 2); ?></strong></p>
    </header>

    <main class="main">
        <table class="asset-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Exch. rate</th>
                    <th>Qty</th>
                    <th>Amount (HKD)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($assets as $a): ?>
                <tr>
                    <td><?php echo (int) $a['ID']; ?></td>
                    <td><?php echo htmlspecialchars($a['type'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($a['description'] ?? ''); ?></td>
                    <td><?php echo number_format((float)($a['amt'] ?? 0), 2); ?></td>
                    <td><?php echo number_format((float)($a['exch_rate'] ?? 0), 6); ?></td>
                    <td><?php echo (int)($a['qty'] ?? 0); ?></td>
                    <td><?php echo number_format((float)($a['amt_hkd'] ?? 0), 2); ?></td>
                    <td><a href="edit.php?id=<?php echo (int) $a['ID']; ?>" class="btn btn-edit">Edit</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($assets)): ?>
        <p class="empty">No assets yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>
