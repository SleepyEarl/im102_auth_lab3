<?php
require_once 'auth.php';
require_once 'config/config.php';

if (isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password_hash, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header('Location: index.php');
                exit;
            }
        }
        $error = "Invalid username or password.";
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory Management</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #fbf8f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 420px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        h2 {
            color: #111827;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6B7280;
            font-size: 14px;
        }

        .error-message {
            background-color: #fde8e8;
            color: #9b1c1c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #fbd5d5;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            background-color: #FAFAFA;
            transition: all 0.2s ease;
        }

        input:focus {
            outline: none;
            border-color: #111827;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background: #1F2937;
            transform: translateY(-1px);
        }

        .register-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6B7280;
        }

        .register-link a {
            color: #ec6530;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="img/inv_icon.png" alt="Logo" style="width: 80px; height: auto;">
        </div>
        <div class="login-header">
            <h2>Welcome Back</h2>
            <div class="subtitle">Sign in to access your inventory system</div>
        </div>
        <?php if (!empty($error)): ?>
            <div class="error-message"><span>⚠️</span> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
            </div>
            <button type="submit">Log In</button>
            <div class="register-link">Don't have an account? <a href="register.php">Register</a></div>
        </form>
    </div>
</body>

</html>