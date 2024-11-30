<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ</title>
</head>
<body>
    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== 'admin'): ?>
        <h2>Xin chào, <?= $_SESSION['user_username'] ?>!</h2>
        <a href="logout.php">Đăng xuất</a>
    <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 'admin'): ?>
        <h2>Chào admin!</h2>
        <a href="admin.php">Quản lý sản phẩm</a>
    <?php else: ?>
        <a href="login.php">Đăng nhập</a> | <a href="register.php">Đăng ký</a>
    <?php endif; ?>
</body>
</html>
