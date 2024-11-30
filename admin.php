<?php
// Kết nối cơ sở dữ liệu
$conn = new mysqli("localhost", "root", "", "projectcuoiky");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm sản phẩm mới
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = 'images/' . basename($_FILES['image']['name']);

    // Upload ảnh
    move_uploaded_file($_FILES['image']['tmp_name'], $image);

    // Thêm sản phẩm vào cơ sở dữ liệu
    $insert_sql = "INSERT INTO cars (name, price, description, image) VALUES ('$name', '$price', '$description', '$image')";
    if ($conn->query($insert_sql) === TRUE) {
        echo "Sản phẩm mới đã được thêm thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM cars WHERE id = $id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Sản phẩm đã được xóa thành công.";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Xử lý cập nhật sản phẩm
if (isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Nếu có ảnh mới, tải lên, nếu không, giữ ảnh cũ
    $image = $_FILES['image']['name'] ? 'images/' . basename($_FILES['image']['name']) : $_POST['old_image'];

    // Upload ảnh mới nếu có
    if ($_FILES['image']['name']) {
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    // Cập nhật sản phẩm
    $update_sql = "UPDATE cars SET name='$name', price='$price', description='$description', image='$image' WHERE id=$id";
    if ($conn->query($update_sql) === TRUE) {
        echo "Sản phẩm đã được cập nhật thành công.";
        // Không cần header() để chuyển hướng, vì nội dung đã được thay đổi
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

// Lấy danh sách sản phẩm
$sql = "SELECT * FROM cars";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Quản lý sản phẩm</title>
</head>
<body>
    <h2>Admin Panel - Quản lý sản phẩm</h2>

    <!-- Form Thêm sản phẩm -->
    <h3>Thêm sản phẩm mới</h3>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Tên sản phẩm" required><br>
        <input type="number" name="price" placeholder="Giá" required><br>
        <textarea name="description" placeholder="Mô tả" required></textarea><br>
        <input type="file" name="image" required><br>
        <input type="submit" name="add_product" value="Thêm sản phẩm">
    </form>

    <hr>

    <!-- Hiển thị danh sách sản phẩm -->
    <h3>Danh sách sản phẩm</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hình ảnh</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['name'] ?></td>
                <td><?= $row['price'] ?></td>
                <td><?= $row['description'] ?></td>
                <td><img src="<?= $row['image'] ?>" alt="image" width="100"></td>
                <td>
                    <a href="admin.php?edit_id=<?= $row['id'] ?>">Sửa</a> | 
                    <a href="admin.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không?')">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php
    // Nếu có yêu cầu sửa sản phẩm
    if (isset($_GET['edit_id'])) {
        $edit_id = $_GET['edit_id'];
        $edit_sql = "SELECT * FROM cars WHERE id = $edit_id";
        $edit_result = $conn->query($edit_sql);
        $edit_product = $edit_result->fetch_assoc();
    ?>

    <!-- Form Sửa sản phẩm -->
    <h3>Sửa sản phẩm</h3>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
        <input type="text" name="name" value="<?= $edit_product['name'] ?>" required><br>
        <input type="number" name="price" value="<?= $edit_product['price'] ?>" required><br>
        <textarea name="description" required><?= $edit_product['description'] ?></textarea><br>
        <input type="file" name="image"><br>
        <input type="hidden" name="old_image" value="<?= $edit_product['image'] ?>"> <!-- Giữ ảnh cũ nếu không thay đổi -->
        <input type="submit" name="update_product" value="Cập nhật sản phẩm">
    </form>

    <?php
    }
    ?>

</body>
</html>

<?php
$conn->close();
?>
