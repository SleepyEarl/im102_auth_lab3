<?php
require_once 'auth.php';
require_once 'config/config.php';
requireLogin();
requireAdmin();


$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $delete_sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($delete_sql)) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Failed to delete the product. It might be linked to other records.";
    }
}
$product_sql = "SELECT p.id, p.name, p.price, p.stock, c.name AS category_name, s.name AS supplier_name
                FROM products p
                INNER JOIN categories c ON p.category_id = c.id
                INNER JOIN suppliers s ON p.supplier_id = s.id
                WHERE p.id = $id";

$result = $conn->query($product_sql);
if ($result && $result->num_rows === 1) {
    $product = $result->fetch_assoc();
} else {
    header('Location: index.php');
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product - Zendenta</title>
    <style>
        .page-header-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 32px 0;
        }

        .confirm-card-container {
            width: 100%;
            max-width: 600px;
            background: #FFFFFF;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            box-sizing: border-box;
        }

        .warning-text {
            color: #4B5563;
            margin: 0 0 24px 0;
            font-size: 15px;
            line-height: 1.5;
        }
        
        .details-box {
            background: #FAFAFA;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 8px 24px;
            margin-bottom: 32px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: 700;
            color: #4B5563;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .value {
            color: #111827;
            font-weight: 600;
        }

        .btn-group {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background 0.2s;
        }

        .btn-danger {
            background: #DC2626;
            color: #FFFFFF;
        }

        .btn-danger:hover {
            background: #B91C1C;
        }

        .btn-secondary {
            background: #FFFFFF;
            color: #374151;
            border: 1px solid #D1D5DB;
        }

        .btn-secondary:hover {
            background: #F9FAFB;
        }

        .error-box {
            color: #B91C1C;
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="main-container">
        <h1 class="page-header-title" style="color: #DC2626;">Delete Product?</h1>

        <div class="confirm-card-container">
            <p class="warning-text">Are you sure you want to permanently delete this product? This choice cannot be reversed.</p>

            <?php if(isset($error)): ?>
                <div class="error-box"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="details-box">
                <div class="detail-row">
                    <span class="label">Product Name</span>
                    <span class="value"><?= htmlspecialchars($product['name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Category</span>
                    <span class="value"><?= htmlspecialchars($product['category_name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Supplier</span>
                    <span class="value"><?= htmlspecialchars($product['supplier_name']) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Price</span>
                    <span class="value" style="color: #EC6530;">₱<?= number_format($product['price'], 2) ?></span>
                </div>
                <div class="detail-row">
                    <span class="label">Stock Available</span>
                    <span class="value"><?= (int)$product['stock'] ?> items</span>
                </div>
            </div>

            <form method="POST" action="">
                <div class="btn-group">
                    <button type="submit" class="btn btn-danger">Confirm Delete</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>