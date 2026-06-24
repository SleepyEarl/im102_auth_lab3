<?php
require_once 'auth.php';
require_once 'config/config.php';
requireLogin();
requireAdmin();

$is_modal_request = isset($_GET['modal']);

$categories = $conn->query('SELECT id, name FROM categories ORDER BY name');
$suppliers = $conn->query('SELECT id, name FROM suppliers ORDER BY name');

$name = '';
$price = '';
$stock = '';
$category_id = '';
$supplier_id = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $stock = trim($_POST['stock'] ?? '');
    $category_id = isset($_POST['category_id']) ? (int) $_POST['category_id'] : 0;
    $supplier_id = isset($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : 0;

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if ($price === '' || !is_numeric($price) || (float)$price < 0) {
        $errors[] = 'Valid positive price value is required.';
    }
    if ($stock === '' || !is_numeric($stock) || (int)$stock < 0) {
        $errors[] = 'Valid positive stock value is required.';
    }
    if ($category_id < 1) {
        $errors[] = 'Please select a product category.';
    }
    if ($supplier_id < 1) {
        $errors[] = 'Please choose an inventory vendor supplier.';
    }

    if (empty($errors)) {
        $name_safe = $conn->real_escape_string($name);
        $price_value = (float) $price;
        $stock_value = (int) $stock;

        $insert_sql = "INSERT INTO products (name, price, stock, category_id, supplier_id)
            VALUES ('$name_safe', $price_value, $stock_value, $category_id, $supplier_id)";

        if ($conn->query($insert_sql)) {
            if ($is_modal_request) {
                header('Content-Type: application/json');
                echo json_with_escape(['success' => true]);
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        } else {
            $errors[] = 'Failed database initialization: Could not save records.';
        }
    }

    if ($is_modal_request && !empty($errors)) {
        header('Content-Type: application/json');
        echo json_with_escape(['success' => false, 'errors' => $errors]);
        exit;
    }
}

function json_with_escape($array) {
    return json_encode($array, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

if ($is_modal_request): ?>
    <form method="POST" action="add.php?modal=1">
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold; color:#333;" for="name">Product Name</label>
            <input style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter product name">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold; color:#333;" for="price">Price (₱)</label>
            <input style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>" required placeholder="0.00">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold; color:#333;" for="stock">Stock Quantity</label>
            <input style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" type="number" id="stock" name="stock" value="<?= htmlspecialchars($stock) ?>" required placeholder="0">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold; color:#333;" for="category_id">Category</label>
            <select style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" id="category_id" name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom:8px; font-weight:bold; color:#333;" for="supplier_id">Supplier</label>
            <select style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" id="supplier_id" name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php while ($sup = $suppliers->fetch_assoc()): ?>
                    <option value="<?= $sup['id'] ?>" <?= $sup['id'] == $supplier_id ? 'selected' : '' ?>><?= htmlspecialchars($sup['name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div style="display:flex; gap:10px; margin-top:25px;">
            <button type="submit" style="padding:10px 20px; border:none; border-radius:4px; background-color:#e63946; color:white; font-weight:bold; cursor:pointer;">Save Product</button>
        </div>
    </form>
<?php exit; endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style> 
        .form-box {
            background: rgba(241, 238, 238, 0.95);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            max-width: 600px;
            margin: 30px auto;
        }
                    


        .formRow { margin-bottom: 20px; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }
        input[type=text], input[type=number], select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #be2410;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
        }
        .btn-secondary { background-color: #666; }
        .error-list {
            margin: 20px auto;
            max-width: 600px;
            background-color: #ffebeba8;
            border-left: 4px solid #d32f2f;
            padding: 12px 20px;
            border-radius: 4px;
            color: #d32f2f;
        }
        .error-list ul { margin: 0; padding-left: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="main-container">
        <?php if (!empty($errors)): ?>
            <div class="error-list">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-box">
            <form method="POST" action="add.php">
                <div class="formRow">
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter product name">
                </div>
                <div class="formRow">
                    <label for="price">Price (₱)</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?= htmlspecialchars($price) ?>" required placeholder="0.00">
                </div>
                <div class="formRow">
                    <label for="stock">Stock Quantity</label>
                    <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($stock) ?>" required placeholder="0">
                </div>
                <div class="formRow">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>
                        <?php while ($cat = $categories->fetch_assoc()): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $category_id ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="formRow">
                    <label for="supplier_id">Supplier</label>
                    <select id="supplier_id" name="supplier_id" required>
                        <option value="">-- Select Supplier --</option>
                        <?php while ($sup = $suppliers->fetch_assoc()): ?>
                            <option value="<?= $sup['id'] ?>" <?= $sup['id'] == $supplier_id ? 'selected' : '' ?>><?= htmlspecialchars($sup['name']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn">Save Product</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>