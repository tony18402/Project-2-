<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
include("include/exit.php");

// ตรวจสอบว่าได้รับการส่ง ID ของผู้ใช้มา
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $sql = "SELECT * FROM Users WHERE user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='alert alert-danger'>ไม่พบข้อมูลผู้ใช้!</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger'>ไม่พบข้อมูล ID ของผู้ใช้!</div>";
    exit;
}

// ตรวจสอบและอัปเดตข้อมูลเมื่อมีการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $level = $_POST['level'];

    // คำสั่ง SQL อัปเดตข้อมูล
    $update_sql = "UPDATE Users SET username = '$username', email = '$email', level = '$level' WHERE user_id = $user_id";
    
    if (mysqli_query($conn, $update_sql)) {
        echo "<div class='alert alert-success'>อัพเดตข้อมูลผู้ใช้สำเร็จ!</div>";
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการอัพเดต: " . mysqli_error($conn) . "</div>";
    }
}
?>

<div class="container">
    <h1 class="page-title text-center text-primary mb-4">แก้ไขข้อมูลผู้ใช้</h1>

    <form method="POST">
        <div class="form-group">
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" class="form-control" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">อีเมล:</label>
            <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="level">สถานะ:</label>
            <select class="form-control" name="level" id="level" required>
                <option value="buyer" <?php echo ($user['level'] == 'buyer') ? 'selected' : ''; ?>>Buyer</option>
                <option value="admin" <?php echo ($user['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success btn-lg">บันทึกการเปลี่ยนแปลง</button>
        <a href="manage_users.php" class="btn btn-secondary btn-lg ml-2">กลับ</a>
    </form>
</div>

<?php
include("include/footer.php");
?>

<!-- เพิ่ม FontAwesome CDN สำหรับไอคอน -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<!-- เพิ่ม Bootstrap CDN สำหรับสไตล์ -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- การปรับแต่ง CSS ให้มีการใช้งานที่ดูดีขึ้น -->
<style>
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #f39c12; /* สีทองเข้มเพื่อให้ดูหรูหรา */
        margin-bottom: 20px;
    }

    .form-group label {
        font-size: 1.2rem;
    }

    .btn-lg {
        font-size: 1.2rem;
        padding: 10px 20px;
    }

    .shadow-lg {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        font-size: 1.1rem;
        padding: 10px;
    }
</style>
