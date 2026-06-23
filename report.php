<?php
require_once 'auth.php';
require_once 'config.php';
requireLogin();

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: sans-serif;
            background: #f9f9f9;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #555;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .badge {
            background: #eee;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            color: #333;
        }

        .stat-row {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item h4 {
            margin: 0;
            font-size: 0.8rem;
            color: #888;
            text-transform: uppercase;
        }

        .stat-item p {
            margin: 5px 0 0;
            font-size: 1.8rem;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <a href="index.php" class="back-link">← Back</a>

        <span class="badge">Reports</span>
        <h2>Inventory Summary</h2>

        <div class="stat-row">
            <div class="stat-item">
                <h4>Total Products</h4>
            </div>
            <div class="stat-item">
                <h4>Total Stock</h4>
            </div>
        </div>
    </div>

</body>

</html>