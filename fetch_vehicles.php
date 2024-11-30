<?php
$conn = new mysqli("localhost", "root", "", "projectcuoiky");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$sql = "SELECT * FROM cars";
$result = $conn->query($sql);

$vehicles = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['image'] = trim($row['image']); // Loại bỏ ký tự xuống dòng
        $vehicles[] = $row;
    }
}

echo json_encode($vehicles);
?>
