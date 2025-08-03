<?php
session_start();
include("include/config.php");

// ตรวจสอบการเข้าสู่ระบบ
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE email = '$email' AND password_hash = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['email'] = $email;
        $_SESSION['username'] = $user['username'];  // แก้ไขจาก $username เป็น $user['username']
        $_SESSION['level'] = $user['level'];
        header("Location: main.php");
        exit();
    }
}

// ตรวจสอบการสมัครสมาชิก
if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['user_number'];  // แก้ไขจาก $_POST['phone_number'] เป็น $_POST['user_number']
    $username = $_POST['user_name'];  // แก้ไขจาก $_POST['username'] เป็น $_POST['user_name']
    $level = "buyer";

    $sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        $sql = "INSERT INTO Users (username, password_hash, email, phone_number, level) 
                VALUES ('$username', '$password', '$email', '$phone_number', '$level')";
        if ($conn->query($sql)) {
            echo "Registration successful!";
        } else {
            echo "Registration failed.";
        }
    } else {
        echo "Email already exists.";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ/สมัครสมาชิก</title>
    <link rel="stylesheet" href="include/login.css">
    <script src="include/login.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container" id="container">

        <!-- สมัครสมาชิก -->
        <div class="form-container sign-up-container">
            <form action="login&regis.php" method="POST">
                <h1>สร้างบัญชีผู้ใช้</h1>
                <div class="social-container">
                </div>
                <span>หรือใช้ที่อยู่อีเมลของคุณในการลงทะเบียน</span>
                <input type="text" name="user_name" placeholder="ชื่อ" required>  <!-- แก้ไขจาก name="username" เป็น name="user_name" -->
                <input type="email" name="email" placeholder="อีเมล" required>
                <input type="number" name="user_number" placeholder="เบอร์โทร" required>  <!-- แก้ไขจาก name="phone_number" เป็น name="user_number" -->
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <button type="submit" name="register">สมัครสมาชิก</button>
            </form>
        </div>

        <!-- เข้าสู่ระบบ -->
        <div class="form-container sign-in-container">
            <form action="login&regis.php" method="POST">
                <h1>เข้าสู่ระบบ</h1>
                <div class="social-container">
                </div>
                <span>หรือใช้บัญชีของคุณในการเข้าสู่ระบบ</span>
                <input type="email" name="email" placeholder="อีเมล" required>
                <input type="password" name="password" placeholder="รหัสผ่าน" required>
                <a href="#">ลืมรหัสผ่าน?</a>
                <button type="submit" name="login">เข้าสู่ระบบ</button>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>ยินดีต้อนรับกลับ!</h1>
                    <p>เพื่อเชื่อมต่อกับเรา กรุณาเข้าสู่ระบบด้วยข้อมูลส่วนตัวของคุณ</p>
                    <button class="ghost" id="signIn">เข้าสู่ระบบ</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>สวัสดีเพื่อน!</h1>
                    <p>กรุณากรอกข้อมูลส่วนตัวของคุณและเริ่มต้นการเดินทางกับเรา</p>
                    <button class="ghost" id="signUp">สมัครสมาชิก</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
