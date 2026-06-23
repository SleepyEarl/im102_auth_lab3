<?php
require_once 'auth.php';
requireLogin();
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
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        
        .welcome-text h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .welcome-text p {
            margin: 4px 0 0 0;
            color: #9ca3af;
            font-size: 14px;
        }

        .admin-badge {
            background-color: #ec6530;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 8px;
            vertical-align: middle;
            display: inline-block;
        }

        .staff-badge {
            background-color: #3b82f6;
            color: white;
            font-size: 11px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
            margin-left: 8px;
            vertical-align: middle;
            display: inline-block;
        }

        .admin-actions {
            display: flex;
            gap: 12px;
        }

        .admin-actions .reports-btn, 
        .admin-actions .logout-btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .admin-actions .reports-btn {
            background-color: #ffffff;
            color: #111827;
        }

        .admin-actions .reports-btn:hover {
            background-color: #f3f4f6;
            transform: translateY(-1px);
        }

        .admin-actions .logout-btn {
            background-color: #dc2626;
            color: #ffffff;
        }

        .admin-actions .logout-btn:hover {
            background-color: #b91c1c;
            transform: translateY(-1px);
        }

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
            align-items: center;
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
            letter-spacing: 0.5px;
            color: #6B7280;
            font-weight: 700;
        }

        .metric-display-val {
            font-size: 32px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -1px;
            margin: 0;
        }

        .metric-sub-count {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }

        .status-progress-track {
            display: flex;
            height: 6px;
            width: 240px;
            background-color: #E5E7EB;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 12px;
            margin-bottom: 12px;
        }

        .segment-instock { background-color: #10B981; width: 0%; }
        .segment-low { background-color: #FFDA62; width: 0%; }
        .segment-out { background-color: #c9511a; width: 0%; }

        .status-legend-group {
            display: flex;
            gap: 16px;
            font-size: 12px;
            color: #6B7280; 
            font-weight: 600;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .dot-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .search-action-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 12px;
            background-color: #FFFFFF;
            padding: 6px 12px;
            border-radius: 10px;
            border: 1px solid #E5E7EB;
        }

        .search-input, .category-select {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #111827;
            padding: 6px 8px;
        }

        .search-input { width: 220px; border-right: 1px solid #E5E7EB; }

        .toolbar-btn-group {
            display: flex;
            gap: 12px;
        }

        .filter-btn {
            background-color: #111827;
            color: #FFFFFF;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-btn:hover { background-color: #1F2937; }

        .reset-btn {
            background-color: #FFFFFF;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }

        .reset-btn:hover { background-color: #F9FAFB; }

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

        th, td {
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid #F3F4F6;
            font-size: 14px;
        }

        th {
            background-color: #FAFAFA;
            color: #4B5563;
            font-weight: 700;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .no-data-row td {
            text-align: center;
            padding: 48px 24px;
            color: #6B7280;
            font-weight: 500;
            background-color: #FFFFFF;
        }
    </style>
</head>
<body>

    <div class="main-container">
        
        <div class="welcome-banner">
            <div class="welcome-text">
                <?php if (isAdmin()): ?>
                    <h2>Welcome back, <?php echo getUsername(); ?>! <span class="admin-badge">Admin</span></h2>
                    <p>You are successfully logged in as an admin. You have access to view reports.</p>
                <?php else: ?>
                    <h2>Welcome back, <?php echo getUsername(); ?>! <span class="staff-badge">Staff</span></h2>
                    <p>Staff member. You have access to view dashboard products.</p>
                <?php endif; ?>
            </div>
            <div class="admin-actions">
                <?php if (isAdmin()): ?>
                    <a href="report.php" class="reports-btn">View Reports</a>
                <?php endif; ?>
                
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>

        <h1 class="page-header-title">Stocks</h1>

        <div class="dashboard-summary-widget">
            <div class="metric-block">
                <span class="metric-title">Total Asset Value</span>
                <h2 class="metric-display-val">₱0.00</h2>
            </div>
            
            <div class="metric-block">
                <div class="metric-sub-count">
                    <span>0</span>
                    <span style="color: #6B7280; font-weight: 500; font-size: 12px; text-transform: uppercase;">Products</span>
                </div>
                
                <div class="status-progress-track">
                    <div class="segment-instock"></div>
                    <div class="segment-low"></div>
                    <div class="segment-out"></div>
                </div>

                <div class="status-legend-group">
                    <div class="legend-item">
                        <span class="dot-indicator" style="background-color: #10B981;"></span>
                        <span>In stock: 0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot-indicator" style="background-color: #FFDA62;"></span>
                        <span>Low stock: 0</span>
                    </div>
                    <div class="legend-item">
                        <span class="dot-indicator" style="background-color: #c9511a;"></span>
                        <span>Out of stock: 0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="search-action-toolbar">
            <form action="#" class="filter-form" onsubmit="return false;">
                <input type="text" placeholder="Search products..." class="search-input">
                <select class="category-select">
                    <option value="">All Categories</option>
                    <option value="1">Electronics</option>
                    <option value="2">Office Supplies</option>
                </select>
            </form>

            <div class="toolbar-btn-group">
                <button type="button" class="filter-btn">Filters</button>
                <a href="#" class="reset-btn">Clear</a>
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
                        <th>Stocks Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="no-data-row">
                        <td colspan="7">📦 No products found in inventory yet.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>