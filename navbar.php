<style>
    :root {
        --terracotta: #EC6530;
        --coral-glow: #FFAE6E;
        --muted-yellow: #FFDA62;
        --powder-petal: #FCEADE;
        --bg-gray: #F3F4F6;
        --text-dark: #1F2937;
        --text-muted: #4B5563;
    }

    body {
        margin: 0;
        font-family: 'Inter', Arial, sans-serif;
        background-color: var(--bg-gray);
        color: var(--text-dark);
        display: flex;
        min-height: 100vh;
    }

    .sidebar-container {
        width: 260px;
        background-color: #FFFFFF;
        border-right: 1px solid #E5E7EB;
        display: flex;
        flex-direction: column;
        padding: 24px 16px;
        position: fixed;
        height: 100vh;
        box-sizing: border-box;
        z-index: 100;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 12px 32px 12px;
    }

    .sidebar-logo {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }

    .sidebar-brand-text {
        font-size: 20px;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.5px;
    }

    .sidebar-content {
        flex: 1;
    }

    .sidebar-section-title {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #9CA3AF;
        padding: 0 12px;
        margin: 16px 0 8px 0;
        font-weight: bold;
    }

    .sidebar-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sidebar-links a {
        display: flex;
        align-items: center;
        padding: 12px;
        color: var(--text-muted);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .sidebar-links a:hover {
        background-color: #F9FAFB;
        color: var(--text-dark);
    }

    .sidebar-links .active-link {
        background-color: var(--powder-petal);
        color: var(--terracotta);
    }

    .sidebar-footer {
        padding-top: 16px;
        border-top: 1px solid #E5E7EB;
        margin-top: auto;
    }

    .logout-link {
        color: #DC2626 !important;
    }

    .logout-link:hover {
        background-color: #FEE2E2 !important;
        color: #991B1B !important;
    }

    .main-container {
        margin-left: 260px;
        width: calc(100% - 260px);
        padding: 40px 48px;
        box-sizing: border-box;
    }
</style>

<div class="sidebar-container">
    <div class="sidebar-brand">
        <img src="https://www.clipartmax.com/png/small/347-3475012_inventory-png-photos-inventory-icon-free.png" alt="Inventory Icon" class="sidebar-logo">
        <span class="sidebar-brand-text">Inventory Sys</span>
    </div>
    
    <div class="sidebar-content">
        <div class="sidebar-section-title">Core</div>
        <ul class="sidebar-links">
            <li>
                <a href="index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active-link' : '' ?>">
                    Products
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Finance</div>
        <ul class="sidebar-links">
            <li>
                <a href="report.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'report.php') ? 'active-link' : '' ?>">
                    Reports
                </a>
            </li>
        </ul>

        <div class="sidebar-section-title">Management</div>
        <ul class="sidebar-links">
            <li>
                <a href="add.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'add.php') ? 'active-link' : '' ?>">
                    Add Product
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <ul class="sidebar-links">
            <li>
                <a href="logout.php" class="logout-link" onclick="return confirm('Are you sure you want to log out?');">
                    Logout
                </a>
            </li>
        </ul>
    </div>
</div>