<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
include("include/exit.php");
?>

<!-- เชื่อมต่อ Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
.page-title {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: #4CAF50;
    text-transform: uppercase;
    font-weight: bold;
}
.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.product-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}
.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}
.product-img img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}
.product-info {
    padding: 15px;
    text-align: center;
}
.product-name {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}
.product-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #4CAF50;
    margin: 10px 0;
}
.product-category,
.product-condition {
    font-size: 1rem;
    color: #777;
    margin: 5px 0;
}
.product-status {
    margin: 10px 0;
    font-size: 1rem;
}
.product-status span {
    font-weight: bold;
}
.manage-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
}
.manage-buttons a {
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    color: #fff;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}
.manage-buttons .edit {
    background-color: #f39c12;
}
.manage-buttons .edit:hover {
    background-color: #e67e22;
}
.manage-buttons .toggle-status {
    background-color: #3498db;
}
.manage-buttons .toggle-status:hover {
    background-color: #2980b9;
}
.manage-buttons .delete {
    background-color: #e74c3c;
}
.manage-buttons .delete:hover {
    background-color: #c0392b;
}
</style>

<div class="container">
    <h1 class="page-title">จัดการสินค้า</h1>

    <?php
    // ฟังก์ชันลบสินค้า
    if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
        $product_id = $_GET['id'];
        if (is_numeric($product_id)) {
            $product_id = mysqli_real_escape_string($conn, $product_id);
            
            // ดึงข้อมูลรูปภาพสินค้า
            $sql = "SELECT image_url FROM Products WHERE product_id = $product_id";
            $result = $conn->query($sql);
            if ($result && $row = $result->fetch_assoc() && !empty($row['image_url'])) {
                $image_path = $row['image_url'];
                if (file_exists($image_path)) {
                    unlink($image_path); // ลบไฟล์ภาพ
                }
            }

            // ลบข้อมูลสินค้า
            $delete_sql = "DELETE FROM Products WHERE product_id = $product_id";
            if ($conn->query($delete_sql) === TRUE) {
                echo "<p style='text-align: center; color: #4CAF50;'>ลบสินค้าสำเร็จ</p>";
            } else {
                echo "<p style='text-align: center; color: #e74c3c;'>เกิดข้อผิดพลาดในการลบสินค้า: " . $conn->error . "</p>";
            }
        }
    }

    // ฟังก์ชันเปลี่ยนสถานะสินค้า (สั่งซื้อได้/ไม่ได้)
    if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'toggle') {
        $product_id = $_GET['id'];
        if (is_numeric($product_id)) {
            $product_id = mysqli_real_escape_string($conn, $product_id);
            // ดึงสถานะปัจจุบัน
            $sql = "SELECT is_orderable FROM Products WHERE product_id = $product_id";
            $result = $conn->query($sql);
            if ($result && $row = $result->fetch_assoc()) {
                $new_status = $row['is_orderable'] == 1 ? 0 : 1;
                $update_sql = "UPDATE Products SET is_orderable = $new_status WHERE product_id = $product_id";
                if ($conn->query($update_sql) === TRUE) {
                    echo "<p style='text-align: center; color: #4CAF50;'>อัปเดตสถานะสินค้าเรียบร้อย</p>";
                } else {
                    echo "<p style='text-align: center; color: #e74c3c;'>เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . $conn->error . "</p>";
                }
            }
        }
    }

    // ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูล
    $sql = "SELECT * FROM Products";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<div class='product-list'>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='product-card'>
                    <div class='product-img'>
                        <img src='" . ($row['image_url'] ?: 'default-image.jpg') . "' alt='" . $row['product_name'] . "' />
                    </div>
                    <div class='product-info'>
                        <h2 class='product-name'>" . $row['product_name'] . "</h2>
                        <p class='product-category'>" . ucfirst($row['category']) . "</p>
                        <p class='product-price'>฿" . number_format($row['price'], 2) . "</p>
                        <p class='product-condition'><em>" . ucfirst($row['cond']) . "</em></p>
                        <p class='product-status'>สถานะ: " . ($row['is_orderable'] ? "<span style='color: green;'>สั่งซื้อได้</span>" : "<span style='color: red;'>สั่งซื้อไม่ได้</span>") . "</p>
                        <div class='manage-buttons'>
                            <a href='edit_product.php?id=" . $row['product_id'] . "' class='edit'><i class='fas fa-edit'></i> แก้ไข</a>
                            <a href='?id=" . $row['product_id'] . "&action=toggle' class='toggle-status'><i class='fas fa-sync'></i> เปลี่ยนสถานะ</a>
                            <a href='?id=" . $row['product_id'] . "&action=delete' class='delete' onclick='return confirm(\"คุณแน่ใจหรือไม่ว่าต้องการลบสินค้า?\");'><i class='fas fa-trash'></i> ลบ</a>
                        </div>
                    </div>
                </div>";
        }
        echo "</div>";
    } else {
        echo "<p style='text-align: center;'>ไม่มีสินค้าในระบบ</p>";
    }

    mysqli_close($conn);
    ?>
</div>

<?php include("include/footer.php"); ?>
