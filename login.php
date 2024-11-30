<?php
session_start(); // Khởi tạo session

// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "projectcuoiky";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra tài khoản admin
    if ($username === "admin" && $password === "admin") {
        $_SESSION['loggedIn'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php"); // Chuyển hướng đến trang admin
        exit;
    }

    // Truy vấn thông tin user
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;

            echo "<script>
                localStorage.setItem('loggedIn', 'true'); // Lưu trạng thái vào localStorage
                alert('Login successful!');
                window.location.href = 'index.html'; // Chuyển hướng về trang chủ
            </script>";
        } else {
            echo "<script>
                alert('Incorrect username or password, please try again!');
                window.location.href = 'index.html';
            </script>";
        }
    } else {
        echo "<script>
            alert('Incorrect username or password, please try again!');
            window.location.href = 'index.html';
        </script>";
    }
}

$conn->close();
?>
