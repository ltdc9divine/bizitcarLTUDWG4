<?php
// Cấu hình trả về JSON
header('Content-Type: application/json');

// Kết nối tới database
$host = 'localhost';
$username = 'root';
$password = ''; // Thay bằng mật khẩu của bạn nếu có
$dbname = 'projectcuoiky'; // Thay bằng tên database của bạn

$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Lấy dữ liệu từ AJAX
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra dữ liệu có hợp lệ không
if (isset($data['name'], $data['email'], $data['subject'], $data['message'])) {
    $name = $conn->real_escape_string($data['name']);
    $email = $conn->real_escape_string($data['email']);
    $subject = $conn->real_escape_string($data['subject']);
    $message = $conn->real_escape_string($data['message']);

    // Thêm dữ liệu vào bảng contact_messages
    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

// Đóng kết nối
$conn->close();
?>