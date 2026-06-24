<?php
require_once 'auth.php';
require_once 'config/config.php';
requireLogin();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");

$where = "WHERE 1=1";
if ($search !== '') {
    $where .= " AND (p.name LIKE '%" . $conn->real_escape_string($search) . "%')";
}
if ($category !== '') {
    $where .= " AND c.id = '" . $conn->real_escape_string($category) . "'";
}

$sql = "SELECT 
            p.id AS product_id,
            p.name AS product_name,
            p.price,
            p.stock,
            p.created_at,
            c.name AS category_name,
            s.name AS supplier_name
        FROM products p
        INNER JOIN categories c ON p.category_id = c.id
        INNER JOIN suppliers s ON p.supplier_id = s.id
        $where
        ORDER BY p.id ASC";

$result = $conn->query($sql);

$stats_sql = "SELECT 
                COUNT(p.id) as total_products,
                IFNULL(SUM(p.stock), 0) as total_stock,
                IFNULL(SUM(p.price * p.stock), 0) as total_value,
                IFNULL(SUM(IF(p.stock >= 20, 1, 0)), 0) as instock_count,
                IFNULL(SUM(IF(p.stock < 20 AND p.stock > 0, 1, 0)), 0) as low_stock_count,
                IFNULL(SUM(IF(p.stock = 0, 1, 0)), 0) as out_stock_count
            FROM products p
            INNER JOIN categories c ON p.category_id = c.id
            INNER JOIN suppliers s ON p.supplier_id = s.id
            $where";

$stats_result = $conn->query($stats_sql);
$stats = $stats_result ? $stats_result->fetch_assoc() : [
    'total_products' => 0,
    'total_stock' => 0,
    'total_value' => 0,
    'instock_count' => 0,
    'low_stock_count' => 0,
    'out_stock_count' => 0
];

$total_p = (int)$stats['total_products'];
$pct_instock = $total_p > 0 ? ((int)$stats['instock_count'] / $total_p) * 100 : 0;
$pct_low = $total_p > 0 ? ((int)$stats['low_stock_count'] / $total_p) * 100 : 0;
$pct_out = $total_p > 0 ? ((int)$stats['out_stock_count'] / $total_p) * 100 : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IM102 LAB 2 - Inventory View</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #fbf8f5;
            margin: 0;
            padding: 0;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 15px;
        }

        .welcome-banner {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #ffffff;
            padding: 24px 32px;
            border-radius: 16px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-badge {
            background-color: #ec6530;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
            margin-left: 8px;
        }

        .staff-badge {
            background-color: #3b82f6;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            text-transform: uppercase;
            margin-left: 8px;
        }

        .reports-btn {
            background-color: #ffffff;
            color: #111827;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .logout-btn {
            background-color: #dc2626;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        /* Dashboard Styles */
        .page-header-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 32px 0;
        }

        .dashboard-summary-widget {
            background-color: #FFFFFF;
            border-radius: 16px;
            padding: 32px;
            display: flex;
            gap: 64px;
            margin-bottom: 32px;
            border: 1px solid #E5E7EB;
        }

        .metric-block {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .metric-title {
            font-size: 15px;
            text-transform: uppercase;
            color: #6B7280;
            font-weight: 700;
        }

        .metric-display-val {
            font-size: 32px;
            font-weight: 800;
            color: #111827;
            margin: 0;
        }

        .status-progress-track {
            display: flex;
            height: 6px;
            width: 240px;
            background-color: #E5E7EB;
            border-radius: 3px;
            overflow: hidden;
            margin: 12px 0;
        }

        .segment-instock {
            background-color: #10B981;
        }

        .segment-low {
            background-color: #FFDA62;
        }

        .segment-out {
            background-color: #c9511a;
        }

        .status-legend-group {
            display: flex;
            gap: 16px;
            font-size: 12px;
            color: #6B7280;
            font-weight: 600;
        }

        .dot-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        /* Table Styles */
        .table-container {
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #E5E7EB;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #FAFAFA;
            padding: 16px 24px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #4B5563;
        }

        td {
            padding: 16px 24px;
            border-bottom: 1px solid #F3F4F6;
        }

        .stock-pill-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            background-color: #E0F2FE;
            color: #0369A1;
        }

        .low-stock-highlight {
            background-color: #fef2f2;
        }

        .action-edit {
            color: #2563EB;
            text-decoration: none;
            font-weight: 600;
            margin-right: 8px;
        }

        .action-delete {
            color: #DC2626;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>
    <div class="main-container">
        <div class="welcome-banner">
            <div class="welcome-text">
                <?php if (isAdmin()): ?>
                    <h2>Welcome back, <?php echo getUsername(); ?>!</h2>
                    <p></p>
                <?php else: ?>
                    <h2>Welcome back, <?php echo getUsername(); ?>!</h2>
                    <p></p>
                <?php endif; ?>
            </div>
        </div>

        <h1 class="page-header-title">Stocks</h1>

        <div class="dashboard-summary-widget">
            <div class="metric-block">
                <span class="metric-title">Total Asset Value</span>
                <h2 class="metric-display-val">₱<?= number_format((float)$stats['total_value'], 2) ?></h2>
            </div>
            <div class="metric-block">
                <div class="metric-sub-count"><span><?= (int)$stats['total_products'] ?></span> <span style="font-size: 12px; color: #6B7280;">PRODUCTS</span></div>
                <div class="status-progress-track">
                    <div class="segment-instock" style="width: <?= $pct_instock ?>%"></div>
                    <div class="segment-low" style="width: <?= $pct_low ?>%"></div>
                    <div class="segment-out" style="width: <?= $pct_out ?>%"></div>
                </div>
                <div class="status-legend-group">
                    <div class="legend-item"><span class="dot-indicator segment-instock"></span> In stock: <?= (int)$stats['instock_count'] ?></div>
                    <div class="legend-item"><span class="dot-indicator segment-low"></span> Low: <?= (int)$stats['low_stock_count'] ?></div>
                    <div class="legend-item"><span class="dot-indicator segment-out"></span> Out: <?= (int)$stats['out_stock_count'] ?></div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Product Details</th>
                        <th>Category</th>
                        <th>Supplier</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): while ($row = $result->fetch_assoc()): ?>
                            <tr class="<?= ($row["stock"] < 20) ? 'low-stock-highlight' : '' ?>">
                                <td><strong><?= htmlspecialchars($row["product_name"]) ?></strong><br><small>ID: <?= (int)$row["product_id"] ?></small></td>
                                <td><?= htmlspecialchars($row["category_name"]) ?></td>
                                <td><?= htmlspecialchars($row["supplier_name"]) ?></td>
                                <td>₱<?= number_format((float)$row["price"], 2) ?></td>
                                <td><span class="stock-pill-badge"><?= (int)$row["stock"] ?> items</span></td>
                                <td>
                                    <a href='edit.php?id=<?= (int)$row["product_id"] ?>' class='action-edit'>Edit</a>
                                    <a href='delete.php?id=<?= (int)$row["product_id"] ?>' class='action-delete' onclick='return confirm("Delete?");'>Delete</a>
                                </td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>