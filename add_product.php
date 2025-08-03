<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
include("include/exit.php");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Prompt', sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 30px;
            background: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 5px rgba(40, 167, 69, 0.25);
        }
        .btn-primary {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4"><i class="fa fa-plus-circle"></i> เพิ่มสินค้าใหม่</h2>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $product_name = $_POST['product_name'];
            $category = $_POST['category'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $cond = $_POST['cond'];
            $size = $_POST['size'];
            $color = $_POST['color'];
            $brand = $_POST['brand'];
            $status = $_POST['status'];

            $target_dir = "uploads/";
            $image_url = "";

            // ตรวจสอบว่าโฟลเดอร์อัปโหลดมีอยู่หรือไม่
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // ตรวจสอบการอัปโหลดรูปภาพ
            if (!empty($_FILES['image']['name'])) {
                $file_name = basename($_FILES['image']['name']);
                $target_file = $target_dir . uniqid() . "_" . $file_name;
                $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_type, $allowed_types)) {
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $image_url = $target_file; // อัปเดต URL ของรูปภาพ
                    } else {
                        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>ประเภทไฟล์รูปภาพไม่ถูกต้อง (อนุญาตเฉพาะ JPG, JPEG, PNG, GIF)</div>";
                }
            }

            // การเพิ่มข้อมูลสินค้าลงในฐานข้อมูล
            if (!empty($image_url)) {
                $sql = "INSERT INTO Products (product_name, category, description, price, cond, size, color, brand, image_url, status) 
                        VALUES ('$product_name', '$category', '$description', '$price', '$cond', '$size', '$color', '$brand', '$image_url', '$status')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success'>เพิ่มสินค้าสำเร็จ!</div>";
                } else {
                    echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
                }
                $conn->close();
            } else {
                echo "<div class='alert alert-warning'>กรุณาเลือกไฟล์รูปภาพ</div>";
            }
        }
        ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="product_name" class="form-label">ชื่อสินค้า</label>
                <input type="text" class="form-control" id="product_name" name="product_name" required>
            </div>
            <div class="mb-3">
                <label for="category" class="form-label">หมวดหมู่</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="shoes">รองเท้า</option>
                    <option value="clothes">เสื้อผ้า</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">รายละเอียด</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">ราคา (บาท)</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="cond" class="form-label">สภาพสินค้า</label>
                <select class="form-select" id="cond" name="cond" required>
                    <option value="new">ใหม่</option>
                    <option value="used">มือสอง</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">ขนาด/ไซส์</label>
                <input type="text" class="form-control" id="size" name="size">
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">สี</label>
                <input type="text" class="form-control" id="color" name="color">
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">ยี่ห้อ</label>
                <input type="text" class="form-control" id="brand" name="brand">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">รูปภาพสินค้า</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">สถานะ</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="available">วางขาย</option>
                    <option value="sold">ขายแล้ว</option>
                    <option value="removed">นำออก</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">บันทึกสินค้า</button>
        </form>
    </div>
</body>
</html>
