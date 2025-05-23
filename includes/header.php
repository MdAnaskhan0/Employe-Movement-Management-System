<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Employee Movement Management System</title>
    <link rel="stylesheet" href="/ManagementProject/assets/css/header.css" />
    <link rel="stylesheet" href="/ManagementProject/assets/css/signup.css" />
    <link rel="stylesheet" href="/ManagementProject/assets/css/my_info.css" />
</head>

<body>

<header>
    <nav>
        <a href="/ManagementProject/index.php"><img src="/ManagementProject/assets/images/logo.png" alt="logo"></a>
        <a href="/ManagementProject/index.php">Employee Movement Management System</a>
        <a href="/ManagementProject/index.php">Home</a> 
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/ManagementProject/pages/my_info.php">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a> 
            <a href="/ManagementProject/pages/logout.php">Logout</a>
        <?php else: ?>
            <a href="/ManagementProject/pages/login.php">Login</a> 
            <a href="/ManagementProject/pages/signup.php">Signup</a>
        <?php endif; ?>
    </nav>
</header>

</body>
</html>
