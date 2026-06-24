<?php
require_once 'config/config.php';

$errors = [];
$success = "";

$username = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = $_POST['confirm_password'];

    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Make sure to fill all the fields bro.";
    }

    if(strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Incorrect email format";
    }

    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if(strlen($password) < 6) {
        $errors[] = "Make sure the password is at least 6 characters long";
    }

    if(empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $errors[] = "Username or Email is already registered";
        }
        $stmt->close();
    }

    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $email, $hashed_password);

        if($stmt->execute()) {
            $success = "Registration Success! Redirecting to login page...";
            $username = $email = "";
            header('Refresh: 2; url=login.php');
        } else {
            $errors[] = "Something went wrong during registration";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account - Inventory Management</title>
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

        .register-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 440px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 32px;
        }

        h2 {
            color: #111827;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #6B7280;
            font-size: 14px;
        }

        .msg {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .msg-success {
            background-color: #e6f4ea;
            color: #137333;
            border: 1px solid #dadce0;
            text-align: center;
        }

        .msg-errors {
            background-color: #fce8e6;
            color: #c5221f;
            border: 1px solid #fbd5d5;
        }

        .msg-errors ul {
            padding-left: 20px;
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

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #D1D5DB;
            border-radius: 8px;
            font-size: 14px;
            color: #111827;
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
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        button:hover {
            background: #1F2937;
            transform: translateY(-1px);
        }

        button:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6B7280;
        }

        .login-link a {
            color: #ec6530;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="register-header">
            <h2>Create an Account</h2>
            <div class="subtitle">Get registered to access the system</div>
        </div>

        <?php if (!empty($success)): ?>
            <div class="msg msg-success">
                🎉 <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="msg msg-errors">
                <ul>
                    <?php foreach ($errors as $error_msg): ?>
                        <li><?php echo htmlspecialchars($error_msg); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="off" placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>" autocomplete="off" placeholder="Enter your email address">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Create a strong password">
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" placeholder="Repeat your password">
            </div>
            
            <button type="submit">Register</button>

            <div class="login-link">
                Already have an account? <a href="login.php">Login</a>
            </div>
        </form>
    </div>

</body>
</html>