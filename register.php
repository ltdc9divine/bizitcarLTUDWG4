<?php
// Connect to the database
$conn = new mysqli("localhost", "root", "", "projectcuoiky");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variable to store error messages
$error_message = '';

// Handle registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $confirm_email = $_POST['confirm_email'];

    // Check if username, email, and phone already exist
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ? OR phone = ?");
    $stmt->bind_param("sss", $username, $email, $phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $existing_usernames = [];
        $existing_emails = [];
        $existing_phones = [];

        while ($row = $result->fetch_assoc()) {
            if ($row['username'] === $username) {
                $existing_usernames[] = $username;
            }
            if ($row['email'] === $email) {
                $existing_emails[] = $email;
            }
            if ($row['phone'] === $phone) {
                $existing_phones[] = $phone;
            }
        }

        if (!empty($existing_usernames)) {
            $error_message = "Username '$username' already exists. Please choose another one.";
        } 
        if (!empty($existing_emails)) {
            $error_message .= "<br>Email '$email' has already been used. Please choose another email.";
        }
        if (!empty($existing_phones)) {
            $error_message .= "<br>Phone number '$phone' has already been used. Please choose another number.";
        }
    } else if ($password != $confirm_password || $email != $confirm_email) {
        $error_message = "Password or confirmation email does not match.";
    } else {
        // Use Prepared Statement to insert data without hashing the password
        $stmt = $conn->prepare("INSERT INTO users (username, password, phone, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $password, $phone, $email); // No hashing

        if ($stmt->execute()) {
            // If registration is successful, redirect to index.html
            echo "<script>
                alert('Account created successfully!');
                window.location.href = 'index.html';
            </script>";
        } else {
            $error_message = "An error occurred while creating the account: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required>
        <?php if ($error_message): ?>
            <div style="color: red;"><?= $error_message; ?></div>
        <?php endif; ?>
        <br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="email" name="confirm_email" placeholder="Confirm Email" required><br>
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>