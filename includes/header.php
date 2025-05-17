<?php ?>
<header>
    <h1>Management System</h1>
    <nav>
        <a href="/ManagementProject/index.php">Home</a> |
        <!-- <a href="/ManagementProject/pages/user_dashboard.php">Dashboard</a> | -->
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/ManagementProject/pages/my_info.php">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a> |
            <a href="/ManagementProject/pages/logout.php">Logout</a>
        <?php else: ?>
            <a href="/ManagementProject/pages/login.php">Login</a> |
            <a href="/ManagementProject/pages/signup.php">Signup</a>
        <?php endif; ?>
    </nav>
</header>
