<?php
require_once __DIR__ . '/includes/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('SELECT ID, type, description, amt, exch_rate, qty, amt_hkd FROM asset WHERE ID = ?');
$stmt->execute([$id]);
$asset = $stmt->fetch();

if (!$asset) {
    header('Location: index.php');
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type        = trim($_POST['type'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amt         = isset($_POST['amt']) ? (float) str_replace(',', '', $_POST['amt']) : 0;
    $exch_rate   = isset($_POST['exch_rate']) ? (float) str_replace(',', '', $_POST['exch_rate']) : 0;
    $qty         = isset($_POST['qty']) ? (int) $_POST['qty'] : 0;
    $amt_hkd     = isset($_POST['amt_hkd']) ? (float) str_replace(',', '', $_POST['amt_hkd']) : 0;

    $update = $pdo->prepare('UPDATE asset SET type = ?, description = ?, amt = ?, exch_rate = ?, qty = ?, amt_hkd = ? WHERE ID = ?');
    $update->execute([$type, $description, $amt, $exch_rate, $qty, $amt_hkd, $id]);
    $message = 'Asset updated.';

    $asset = [
        'ID'          => $asset['ID'],
        'type'        => $type,
        'description' => $description,
        'amt'         => $amt,
        'exch_rate'   => $exch_rate,
        'qty'         => $qty,
        'amt_hkd'     => $amt_hkd,
    ];
}

$page_title = 'Edit Asset #' . $asset['ID'];
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
        <a href="index.php" class="btn btn-back">Back to Dashboard</a>
    </header>

    <main class="main">
        <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" class="edit-form">
            <input type="hidden" name="id" value="<?php echo (int) $asset['ID']; ?>">
            <div class="form-row">
                <label for="type">Type</label>
                <input type="text" id="type" name="type" maxlength="200" value="<?php echo htmlspecialchars($asset['type'] ?? ''); ?>">
            </div>
            <div class="form-row">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" maxlength="400" value="<?php echo htmlspecialchars($asset['description'] ?? ''); ?>">
            </div>
            <div class="form-row">
                <label for="amt">Amount</label>
                <input type="text" id="amt" name="amt" value="<?php echo htmlspecialchars($asset['amt'] ?? '0'); ?>">
            </div>
            <div class="form-row">
                <label for="exch_rate">Exchange rate</label>
                <input type="text" id="exch_rate" name="exch_rate" value="<?php echo htmlspecialchars($asset['exch_rate'] ?? '0'); ?>">
            </div>
            <div class="form-row">
                <label for="qty">Quantity</label>
                <input type="number" id="qty" name="qty" value="<?php echo (int)($asset['qty'] ?? 0); ?>">
            </div>
            <div class="form-row">
                <label for="amt_hkd">Amount (HKD)</label>
                <input type="text" id="amt_hkd" name="amt_hkd" value="<?php echo htmlspecialchars($asset['amt_hkd'] ?? '0'); ?>">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-save">Save changes</button>
                <a href="index.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </main>
</body>
</html>
