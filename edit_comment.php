<?php
// เชื่อมต่อฐานข้อมูล
include("include/config.php");

// ตรวจสอบว่าได้รับ comment_id และ product_id หรือไม่
if (isset($_GET['comment_id']) && isset($_GET['product_id'])) {
    $comment_id = $_GET['comment_id'];
    $product_id = $_GET['product_id'];

    // ดึงข้อมูลคอมเมนต์จากฐานข้อมูล
    $sql = "SELECT * FROM comments WHERE comment_id = '$comment_id' AND product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);

    // หากไม่พบคอมเมนต์
    if (mysqli_num_rows($result) == 0) {
        echo "ไม่พบคอมเมนต์ที่ต้องการแก้ไข";
        exit();
    }

    // ดึงข้อมูลคอมเมนต์ที่ต้องการแก้ไข
    $row = mysqli_fetch_assoc($result);
    $comment_text = $row['comment_text'];

    // หากผู้ใช้ส่งข้อมูลมา (เมื่อกด Submit)
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_text'])) {
        $updated_comment_text = $_POST['comment_text'];

        // อัปเดตข้อมูลคอมเมนต์ในฐานข้อมูล
        $sql_update = "UPDATE comments SET comment_text = '$updated_comment_text' WHERE comment_id = '$comment_id'";
        mysqli_query($conn, $sql_update);

        // รีไดเร็กไปยังหน้าแสดงคอมเมนต์
        header("Location: comment.php?product_id=$product_id");
        exit();
    }
} else {
    echo "ไม่พบข้อมูลที่ต้องการแก้ไข";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขคอมเมนต์</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #4CAF50;
        }
        textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 20px;
            resize: vertical;
            outline: none;
        }
        textarea:focus {
            border-color: #4CAF50;
        }
        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            font-size: 18px;
            color: #ff0000;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>แก้ไขคอมเมนต์</h1>

    <!-- ฟอร์มสำหรับแก้ไขคอมเมนต์ -->
    <form method="POST" action="">
        <textarea name="comment_text" required><?= htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8'); ?></textarea><br><br>
        <button type="submit">อัปเดตคอมเมนต์</button>
    </form>
</div>

</body>
</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
