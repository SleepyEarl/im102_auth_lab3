<?php
require_once 'auth.php';
require_once 'config/config.php';
requireLogin();


$summary_sql = "SELECT COUNT(id) AS total_products, IFNULL(SUM(stock), 0) AS total_stock FROM products";
$summary_result = $conn->query($summary_sql);
$summary_row = $summary_result ? $summary_result->fetch_assoc() : ['total_products' => 0, 'total_stock' => 0];

$category_sql = "SELECT c.name, COUNT(p.id) AS products,
        IFNULL(SUM(p.stock), 0) AS total_stock,
        IFNULL(SUM(p.price * p.stock), 0) AS total_value,
        IFNULL(AVG(p.price), 0) AS avg_price
        FROM categories c
        LEFT JOIN products p ON c.id = p.category_id
        GROUP BY c.id, c.name
        ORDER BY total_value DESC";
$category_result = $conn->query($category_sql);

$supplier_sql = "SELECT s.name, COUNT(p.id) AS products,
        IFNULL(SUM(p.stock), 0) AS total_stock
        FROM suppliers s
        LEFT JOIN products p ON s.id = p.supplier_id
        GROUP BY s.id, s.name
        ORDER BY products DESC";
$supplier_result = $conn->query($supplier_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Reports</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #fbf8f5;
            padding: 0;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 15px;
        }
        h3 {
            color: #222121;
            margin-top: 35px;
            margin-bottom: 15px;
            font-size: 20px;
            border-left: 4px solid #EC6530;
            padding-left: 10px;
        }
        .summary-section {
            margin-bottom: 25px;
            padding: 15px 20px;
            background: #fff;
            border-radius: 8px;
            display: flex;
            gap: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .stat-card {
            display: flex;
            align-items: baseline;
            gap: 8px;
            background-color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            border-left: 4px solid #FFAE6E;
        }
        .stat-card.secondary-card {
            border-left-color: #EC6530;
            background-color: #FCEADE;
        }
        .stat-label {
            font-size: 14px;
            color: #555;
            font-weight: bold;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #222;
        }
        .table-container {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color:  #111827;
            color: white;
            font-weight: bold;
        }
        tr:hover { 
            background-color: #fafafa; 
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="main-container">

        <div class="summary-section">
            <div class="stat-card">
                <span class="stat-label">Overall Total Products:</span>
                <span class="stat-value"><?= (int)($summary_row['total_products'] ?? 0) ?></span>
            </div>
            <div class="stat-card secondary-card">
                <span class="stat-label" style="color: #a8380c;">Overall Total Stock:</span>
                <span class="stat-value" style="color: #a8380c;"><?= (int)($summary_row['total_stock'] ?? 0) ?></span>
            </div>
        </div>

        <h3>Category Breakdown</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Product Count</th>
                        <th>Total Stock</th>
                        <th>Average Price</th>
                        <th>Total Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($category_result && $category_result->num_rows > 0) {
                        while ($row = $category_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . (int)$row['products'] . "</td>";
                            echo "<td>" . (int)$row['total_stock'] . "</td>";
                            echo "<td>₱" . number_format((float)$row['avg_price'], 2) . "</td>";
                            echo "<td style='font-weight: bold; color: #EC6530;'>₱" . number_format((float)$row['total_value'], 2) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No categories found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <h3>Supplier Breakdown</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Product Count</th>
                        <th>Total Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($supplier_result && $supplier_result->num_rows > 0) {
                        while ($row = $supplier_result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td>" . (int)$row['products'] . "</td>";
                            echo "<td>" . (int)$row['total_stock'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No suppliers found.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>