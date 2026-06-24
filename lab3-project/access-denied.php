
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Access Denied</title>
    <style>
        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #F3F4F6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-card {
            background: #FFFFFF;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            border: 1px solid #E5E7EB;
        }

        .icon {
            font-size: 50px;
            color: #DC2626;
            margin-bottom: 20px;
        }

        h1 {
            color: #1F2937;
            margin-bottom: 10px;
        }

        p {
            color: #6B7280;
            margin-bottom: 25px;
        }

        .btn {
            padding: 10px 24px;
            background-color: #EC6530;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }

        .btn:hover {
            background-color: #d65a2a;
        }
    </style>
</head>

<body>
    <div class="error-card">
        <div class="icon">🚫</div>
        <img src="img/restric.png">
        <h1>Access Denied</h1>
        <p>You do not have the required administrator permissions to view this page.</p>
        <a href="index.php" class="btn">Return to Home</a>
    </div>
</body>

</html>