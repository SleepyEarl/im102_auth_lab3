<?php
require_once 'config.php';  

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id < 1) {
    header('Location: index.php');
    exit;
}

$categories = $conn->query('SELECT id, name FROM categories ORDER BY name');
$suppliers = $conn->query('SELECT id, name FROM suppliers ORDER BY name');

$product = null;
$product_sql = "SELECT p.id, 
                p.name, 
                p.price, 
                p.stock, 
                p.category_id, 
                p.supplier_id, 
                c.name AS category_name, s.name AS supplier_name
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

$errors = [];
$name = $product['name'];
$price = $product['price'];
$stock = $product['stock'];
$category_id = $product['category_id'];
$supplier_id = $product['supplier_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $supplier_id = isset($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : 0;

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($price === '') {
        $errors[] = 'Price is required.';
    }
    if ($stock === '') {
        $errors[] = 'Stock is required.';
    }
    if ($category_id < 1) {
        $errors[] = 'Category is required.';
    }
    if ($supplier_id < 1) {
        $errors[] = 'Supplier is required.';
    }

    if (empty($errors)) {
        $name_safe = $conn->real_escape_string($name);
        $price_value = (float) $price;
        $stock_value = (int) $stock;
        $category_safe = (int) $category_id;
        $supplier_safe = (int) $supplier_id;

        $update_sql = "UPDATE products SET
            name = '$name_safe',
            price = $price_value,
            stock = $stock_value,
            category_id = $category_safe,
            supplier_id = $supplier_safe
            WHERE id = $id";

        if ($conn->query($update_sql)) {
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Cannot update the product.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Zendenta</title>
    <style>
        .page-header-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 32px 0;
        }

        .form-card-container {
            width: 100%;
            max-width: 600px;
            background: #FFFFFF;
            padding: 32px;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            box-sizing: border-box;
        }

        .form-row {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #4B5563;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input[type=text],
        input[type=number],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
            background-color: #FAFAFA;
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        select:focus {
            border-color: #EC6530;
            background-color: #FFFFFF;
            outline: none;
            box-shadow: 0 0 0 3px #FCEADE;
        }

        .btn-group {
            margin-top: 32px;
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: #111827;
            color: #FFFFFF;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-align: center;
            transition: background 0.2s;
        }

        .btn:hover {
            background: #1F2937;
        }

        .btn-primary {
            background: #EC6530;
        }

        .btn-primary:hover {
            background: #d45220;
        }

        .btn-secondary {
            background: #FFFFFF;
            color: #374151;
            border: 1px solid #D1D5DB;
        }

        .btn-secondary:hover {
            background: #F9FAFB;
        }

        .error-list {
            margin-bottom: 24px;
            color: #B91C1C;
            background: #FEF2F2;
            border: 1px solid #FEE2E2;
            padding: 14px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        
        .error-list ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="main-container">
        <h1 class="page-header-title">Edit Product</h1>

        <div class="form-card-container">
            <?php if (!empty($errors)): ?>
                <div class="error-list">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-row">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                </div>

                <div class="form-row">
                    <label for="price">Price (PHP)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>" required>
                </div>

                <div class="form-row">
                    <label for="stock">Available Stock</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($stock) ?>" required>
                </div>

                <div class="form-row">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php while ($sup = $suppliers->fetch_assoc()): ?>
                            <option value="<?= $sup['id'] ?>" <?= $sup['id'] == $supplier_id ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sup['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Update Details</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>