<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
?>

<?php
$email = $_SESSION['email'];
$sql = "SELECT * FROM Users WHERE email = '$email'";
$re = $conn->query($sql);
$as = $re->fetch_assoc();

if (isset($_POST['update'])) {
    $username = $_POST['username']; 
    $password_hash = $_POST['password_hash'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone_number = $_POST['phone_number'];
    $shipping_address = $_POST['shipping_address'];  // เพิ่มที่อยู่
    
    $sql = "UPDATE Users SET email = '$email', 
                             password_hash = '$password_hash', 
                             first_name = '$first_name', 
                             last_name = '$last_name', 
                             phone_number = '$phone_number',
                             shipping_address = '$shipping_address'  
            WHERE email = '$email'";
    $conn->query($sql);
    header("Location: main.php");
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อัปเดตข้อมูลโปรไฟล์</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-image: url('https://www.example.com/background.jpg'); /* Replace with a relevant background image URL */
            background-size: cover;
            background-position: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }
        .container {
            margin-top: 100px;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        .form-container h2 {
            color: #333;
            font-size: 2.2em;
            text-align: center;
            margin-bottom: 40px;
            font-weight: bold;
        }
        .form-control {
            border-radius: 25px;
            padding: 10px 20px;
            font-size: 1.1em;
        }
        .form-control:focus {
            border-color: #FF7F50;
            box-shadow: 0 0 5px 2px rgba(255, 127, 80, 0.8);
        }
        .btn-primary {
            background-color: #FF7F50;
            border: none;
            padding: 12px 25px;
            font-size: 1.2em;
            width: 100%;
            border-radius: 50px;
            transition: 0.4s ease-in-out;
        }
        .btn-primary:hover {
            background-color: #FF6347;
            transform: scale(1.05);
        }
        .btn-danger {
            width: 100%;
            border-radius: 50px;
            background-color: #e74c3c;
            transition: 0.4s ease-in-out;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            transform: scale(1.05);
        }
        .form-footer {
            text-align: center;
            margin-top: 20px;
        }
        .form-footer a {
            color: #333;
            font-size: 1.1em;
            text-decoration: underline;
        }
        .form-label i {
            margin-right: 10px;
            color: #FF7F50;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="form-container">
                <h2><i class="fas fa-user-edit"></i> อัปเดตข้อมูลโปรไฟล์</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="fas fa-user"></i> ชื่อผู้ใช้</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $as['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_hash" class="form-label"><i class="fas fa-key"></i> รหัสผ่าน</label>
                        <input type="password" class="form-control" id="password_hash" name="password_hash" value="<?php echo $as['password_hash']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label"><i class="fas fa-id-card"></i> ชื่อจริง</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $as['first_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="last_name" class="form-label"><i class="fas fa-id-card"></i> นามสกุล</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $as['last_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label"><i class="fas fa-phone-alt"></i> เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?php echo $as['phone_number']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_address" class="form-label"><i class="fas fa-map-marker-alt"></i> ที่อยู่จัดส่ง</label>
                        <input type="text" class="form-control" id="shipping_address" name="shipping_address" value="<?php echo $as['shipping_address']; ?>" required>
                    </div>
                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i> อัปเดตข้อมูล</button>
                    <a href="main.php" class="btn btn-danger mt-3"><i class="fas fa-times"></i> ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
