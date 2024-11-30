<?php
// Cấu hình trả về JSON
header('Content-Type: application/json');

// Kết nối tới database
$host = 'localhost';
$username = 'root';
$password = ''; // Thay bằng mật khẩu của bạn
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
if (isset($data['name'], $data['phone'], $data['email'], $data['car'], $data['date'], $data['time'])) {
    $name = $conn->real_escape_string($data['name']);
    $phone = $conn->real_escape_string($data['phone']);
    $email = $conn->real_escape_string($data['email']);
    $car = $conn->real_escape_string($data['car']);
    $date = $conn->real_escape_string($data['date']);
    $time = $conn->real_escape_string($data['time']);

    // Thêm dữ liệu vào bảng appointments
    $sql = "INSERT INTO appointments (name, phone, email, car, date, time) VALUES ('$name', '$phone', '$email', '$car', '$date', '$time')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Appointment booked successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}

// Đóng kết nối
$conn->close();
?>
