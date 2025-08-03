<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
?>

<?php
// รับ product_id จาก URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    echo "ไม่พบสินค้า!";
    exit();
}

// เช็คว่า user ได้กรอกคอมเมนต์หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
    // รับข้อมูลจาก form
    $comment_text = $_POST['comment_text'];
    $email = $_SESSION['email'];  // รับอีเมลจาก session

    // เพิ่มคอมเมนต์ลงในฐานข้อมูล
    $sql = "INSERT INTO comments (product_id, comment_text, email) VALUES ('$product_id', '$comment_text', '$email')";
    mysqli_query($conn, $sql);
}

// ลบคอมเมนต์
if (isset($_GET['delete_comment_id'])) {
    $delete_comment_id = $_GET['delete_comment_id'];

    // ตรวจสอบว่าอีเมลของผู้ใช้ที่ล็อกอินตรงกับอีเมลของคอมเมนต์ที่ต้องการลบหรือไม่
    $sql_check = "SELECT email FROM comments WHERE comment_id = '$delete_comment_id'";
    $result_check = mysqli_query($conn, $sql_check);
    $row_check = mysqli_fetch_assoc($result_check);

    // ถ้าอีเมลตรงกับของผู้ใช้ที่ล็อกอินถึงจะลบได้ หรือถ้าเป็นแอดมิน
    if ($_SESSION['email'] == $row_check['email'] || $_SESSION['level'] == 'admin') {
        // ลบคอมเมนต์
        $sql_delete = "DELETE FROM comments WHERE comment_id = '$delete_comment_id'";
        mysqli_query($conn, $sql_delete);
    } else {
        echo "คุณไม่สามารถลบคอมเมนต์ของผู้อื่นได้";
        exit();
    }
}

// ดึงข้อมูลคอมเมนต์จากฐานข้อมูล
$sql_comments = "SELECT * FROM comments WHERE product_id = '$product_id' ORDER BY created_at DESC";
$result_comments = mysqli_query($conn, $sql_comments);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คอมเมนต์สำหรับสินค้า</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        h2 {
            color: #333;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .comment-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .comment-form textarea {
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
            font-size: 1rem;
            min-height: 100px;
        }

        .comment-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.1rem;
        }

        .comment-form button:hover {
            background-color: #45a049;
        }

        .comment-list {
            margin-top: 30px;
        }

        .comment-item {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .comment-item p {
            margin: 5px 0;
        }

        .comment-item .comment-text {
            font-size: 1rem;
            color: #555;
        }

        .comment-item .comment-time {
            font-size: 0.9rem;
            color: #888;
        }

        .comment-item .edit-delete-btns a {
            margin-right: 10px;
            color: #3498db;
            text-decoration: none;
        }

        .comment-item .edit-delete-btns a.delete {
            color: #e74c3c;
        }

        .comment-item .edit-delete-btns a:hover {
            text-decoration: underline;
        }

        .comment-item .edit-delete-btns {
            margin-top: 10px;
        }

        .comment-item:last-child {
            margin-bottom: 0;
        }

        .email {
            font-size: 0.9rem;
            color: #666;
            margin-top: 5px;
        }

    </style>
</head>
<body>

<div class="container">
    <h1>คอมเมนต์สำหรับสินค้า</h1>

    <!-- Form สำหรับเพิ่มคอมเมนต์ -->
    <div class="comment-form">
        <h2>เพิ่มคอมเมนต์</h2>
        <form method="POST" action="">
            <textarea name="comment_text" required placeholder="ใส่คอมเมนต์ของคุณที่นี่..."></textarea>
            <button type="submit">ส่งคอมเมนต์</button>
        </form>
    </div>

    <!-- แสดงคอมเมนต์ -->
    <div class="comment-list">
        <h2>คอมเมนต์ทั้งหมด</h2>
        <?php if (mysqli_num_rows($result_comments) > 0): ?>
            <?php while ($row = mysqli_fetch_assoc($result_comments)): ?>
                <div class="comment-item">
                    <p><strong>คอมเมนต์:</strong> <?= $row['comment_text']; ?></p>
                    <p class="comment-time"><small>วันที่: <?= date('d M Y H:i:s', strtotime($row['created_at'])); ?></small></p>
                    <p class="email"><strong>อีเมลผู้คอมเมนต์:</strong> <?= $row['email']; ?></p>
                    <div class="edit-delete-btns">
                        <?php
                        // หากผู้ใช้ล็อกอินเป็นแอดมิน หรือเจ้าของคอมเมนต์
                        if ($_SESSION['level'] == 'admin' || $_SESSION['email'] == $row['email']):
                        ?>
                            <!-- ปุ่มแก้ไข -->
                            <a href="edit_comment.php?comment_id=<?= $row['comment_id']; ?>&product_id=<?= $product_id; ?>">แก้ไข</a>
                        <?php endif; ?>
                        <!-- ปุ่มลบ -->
                        <?php
                        // หากผู้ใช้ล็อกอินเป็นแอดมิน หรือเจ้าของคอมเมนต์
                        if ($_SESSION['level'] == 'admin' || $_SESSION['email'] == $row['email']):
                        ?>
                            <a href="?product_id=<?= $product_id; ?>&delete_comment_id=<?= $row['comment_id']; ?>" class="delete" onclick="return confirm('คุณแน่ใจว่าต้องการลบคอมเมนต์นี้?')">ลบ</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>ยังไม่มีคอมเมนต์สำหรับสินค้านี้.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

<?php
include("include/footer.php");
?>
