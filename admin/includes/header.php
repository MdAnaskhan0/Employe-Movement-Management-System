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
        <a href="/ManagementProject/admin/index.php">Employee Movement Management System</a>
        <a href="/ManagementProject/admin/index.php">Home</a> 
        <a href="/ManagementProject/admin/pages/dashboard.php">Dashboard</a>
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/ManagementProject/pages/my_info.php">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a> 
            <a href="/ManagementProject/admin/pages/logout.php">Logout</a>
        <?php else: ?>
            <a href="/ManagementProject/admin/pages/admin_login.php">Login</a> 
        <?php endif; ?>
    </nav>
</header>

</body>
</html>
