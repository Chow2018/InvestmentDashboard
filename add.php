<?php
require_once __DIR__ . '/includes/db.php';

$page_title = 'Add Asset';
$message = '';
$error = '';

// Default form values
$form = [
    'type'        => '',
    'description' => '',
    'amt'         => '',
    'exch_rate'   => '',
    'qty'         => '',
    'amt_hkd'     => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form['type']        = trim($_POST['type'] ?? '');
    $form['description'] = trim($_POST['description'] ?? '');
    $form['amt']         = trim($_POST['amt'] ?? '');
    $form['exch_rate']   = trim($_POST['exch_rate'] ?? '');
    $form['qty']         = trim($_POST['qty'] ?? '');
    $form['amt_hkd']     = trim($_POST['amt_hkd'] ?? '');

    $amt       = $form['amt'] !== '' ? (float) str_replace(',', '', $form['amt']) : 0;
    $exch_rate = $form['exch_rate'] !== '' ? (float) str_replace(',', '', $form['exch_rate']) : 0;
    $qty       = $form['qty'] !== '' ? (int) $form['qty'] : 0;
    $amt_hkd   = $form['amt_hkd'] !== '' ? (float) str_replace(',', '', $form['amt_hkd']) : 0;

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO asset (type, description, amt, exch_rate, qty, amt_hkd) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $form['type'],
            $form['description'],
            $amt,
            $exch_rate,
            $qty,
            $amt_hkd,
        ]);

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = 'Failed to create asset: ' . htmlspecialchars($e->getMessage());
    }
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
            <div class="form-row">
                <label for="type">Type</label>
                <input type="text" id="type" name="type" maxlength="200" value="<?php echo htmlspecialchars($form['type']); ?>">
            </div>
            <div class="form-row">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" maxlength="400" value="<?php echo htmlspecialchars($form['description']); ?>">
            </div>
            <div class="form-row">
                <label for="amt">Amount</label>
                <input type="text" id="amt" name="amt" value="<?php echo htmlspecialchars($form['amt']); ?>">
            </div>
            <div class="form-row">
                <label for="exch_rate">Exchange rate</label>
                <input type="text" id="exch_rate" name="exch_rate" value="<?php echo htmlspecialchars($form['exch_rate']); ?>">
            </div>
            <div class="form-row">
                <label for="qty">Quantity</label>
                <input type="number" id="qty" name="qty" value="<?php echo htmlspecialchars($form['qty']); ?>">
            </div>
            <div class="form-row">
                <label for="amt_hkd">Amount (HKD)</label>
                <input type="text" id="amt_hkd" name="amt_hkd" value="<?php echo htmlspecialchars($form['amt_hkd']); ?>" readonly>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-save">Create asset</button>
                <a href="index.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </main>
    <script>
    (function () {
        function parseNumber(value) {
            if (!value) return 0;
            value = String(value).replace(/,/g, '');
            var n = parseFloat(value);
            return isNaN(n) ? 0 : n;
        }

        function updateHKD() {
            var amtField = document.getElementById('amt');
            var rateField = document.getElementById('exch_rate');
            var qtyField = document.getElementById('qty');
            var hkdField = document.getElementById('amt_hkd');
            if (!amtField || !rateField || !qtyField || !hkdField) return;

            var amt = parseNumber(amtField.value);
            var rate = parseNumber(rateField.value);
            var qty = parseInt(qtyField.value, 10) || 0;
            var total = amt * rate * qty;

            hkdField.value = total ? total.toFixed(2) : '';
        }

        ['amt', 'exch_rate', 'qty'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', updateHKD);
                el.addEventListener('change', updateHKD);
            }
        });

        updateHKD();
    })();
    </script>
</body>
</html>

