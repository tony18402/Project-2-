<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_status'])) {
        // อัปเดตสถานะของสินค้า
        $product_id = $_POST['product_id'];
        $new_status = $_POST['status'];

        // ถ้าสถานะเป็น "หมด" (out_of_stock), ให้ทำการตั้งค่า is_orderable เป็น 0 (ไม่สามารถสั่งซื้อได้)
        if ($new_status == 'out_of_stock') {
            $is_orderable = 0;
        } else {
            $is_orderable = 1;  // ตั้งค่าให้สามารถสั่งซื้อได้
        }

        // อัปเดตสถานะสินค้าและค่า is_orderable
        $sql = "UPDATE Products 
                SET status = '$new_status', 
                    is_orderable = '$is_orderable' 
                WHERE product_id = '$product_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>อัปเดตสถานะสินค้าสำเร็จ</p>";
        } else {
            echo "<p style='color: red;'>เกิดข้อผิดพลาดในการอัปเดตสถานะสินค้า: " . $conn->error . "</p>";
        }
    }

    // ตรวจสอบการอัปเดตข้อมูลสินค้า
    if (isset($_POST['product_name'])) {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $cond = $_POST['cond'];
        $size = $_POST['size'];
        $color = $_POST['color'];
        $brand = $_POST['brand'];
        $status = $_POST['status'];

        // จัดการการอัปโหลดไฟล์รูปภาพ
        $image_url = $_POST['image_url']; // เก็บค่า URL เดิมไว้ก่อน
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = "uploads/";
            $file_name = basename($_FILES['image']['name']);
            $target_file = $upload_dir . uniqid() . "_" . $file_name;

            // ตรวจสอบว่าเป็นไฟล์รูปภาพ
            $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $valid_types = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($image_type, $valid_types)) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_url = $target_file; // ใช้ URL ใหม่ที่อัปโหลด
                } else {
                    echo "<p style='color: red;'>ไม่สามารถอัปโหลดรูปภาพได้</p>";
                }
            } else {
                echo "<p style='color: red;'>ชนิดไฟล์รูปภาพไม่ถูกต้อง อนุญาตเฉพาะ JPG, JPEG, PNG และ GIF เท่านั้น</p>";
            }
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE Products 
                SET product_name = '$product_name', 
                    category = '$category', 
                    description = '$description', 
                    price = '$price', 
                    cond = '$cond', 
                    size = '$size', 
                    color = '$color', 
                    brand = '$brand', 
                    image_url = '$image_url', 
                    status = '$status'
                WHERE product_id = '$product_id'";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color: green;'>อัปเดตสินค้าสำเร็จ</p>";
        } else {
            echo "<p style='color: red;'>เกิดข้อผิดพลาดในการอัปเดตสินค้า: " . $conn->error . "</p>";
        }
    }
}

// ดึงข้อมูลจากตาราง Products
$sql = "SELECT * FROM Products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #e9ecef;
        }
        img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 8px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        input, select {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <h1>แก้ไขสินค้า</h1>
    <table>
        <thead>
            <tr>
                <th>รหัสสินค้า</th>
                <th>ชื่อสินค้า</th>
                <th>หมวดหมู่</th>
                <th>คำอธิบาย</th>
                <th>ราคา</th>
                <th>สภาพ</th>
                <th>ขนาด</th>
                <th>สี</th>
                <th>แบรนด์</th>
                <th>รูปภาพ</th>
                <th>สถานะ</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" enctype="multipart/form-data">
                            <td><input type="hidden" name="product_id" value="<?= $row['product_id'] ?>"><?= $row['product_id'] ?></td>
                            <td><input type="text" name="product_name" value="<?= $row['product_name'] ?>"></td>
                            <td>
                                <select name="category">
                                    <option value="shoes" <?= $row['category'] == 'shoes' ? 'selected' : '' ?>>รองเท้า</option>
                                    <option value="clothes" <?= $row['category'] == 'clothes' ? 'selected' : '' ?>>เสื้อผ้า</option>
                                </select>
                            </td>
                            <td><input type="text" name="description" value="<?= $row['description'] ?>"></td>
                            <td><input type="number" step="0.01" name="price" value="<?= $row['price'] ?>"></td>
                            <td>
                                <select name="cond">
                                    <option value="new" <?= $row['cond'] == 'new' ? 'selected' : '' ?>>ใหม่</option>
                                    <option value="used" <?= $row['cond'] == 'used' ? 'selected' : '' ?>>มือสอง</option>
                                </select>
                            </td>
                            <td><input type="text" name="size" value="<?= $row['size'] ?>"></td>
                            <td><input type="text" name="color" value="<?= $row['color'] ?>"></td>
                            <td><input type="text" name="brand" value="<?= $row['brand'] ?>"></td>
                            <td>
                                <img src="<?= $row['image_url'] ?>" alt="รูปภาพสินค้า">
                                <input type="file" name="image">
                                <input type="hidden" name="image_url" value="<?= $row['image_url'] ?>">
                            </td>
                            <td>
                                <select name="status">
                                    <option value="available" <?= $row['status'] == 'available' ? 'selected' : '' ?>>วางขาย</option>
                                    <option value="sold" <?= $row['status'] == 'sold' ? 'selected' : '' ?>>ขายแล้ว</option>
                                    <option value="removed" <?= $row['status'] == 'removed' ? 'selected' : '' ?>>นำออก</option>
                                    <option value="out_of_stock" <?= $row['status'] == 'out_of_stock' ? 'selected' : '' ?>>หมด</option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" name="update_status">อัปเดตสถานะ</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="12">ไม่พบข้อมูลสินค้า</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
