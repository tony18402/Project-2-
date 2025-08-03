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
    background-color: #f3f4f6;
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
    font-size: 3em;
    margin-bottom: 40px;
    color: #2ecc71;
    font-weight: 700;
    letter-spacing: 1px;
}
.product-list {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: space-evenly;
}
.product-card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    width: calc(33.333% - 20px);
    margin-bottom: 30px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
    border-radius: 15px;
}
.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 30px rgba(0, 0, 0, 0.15);
}
.product-img img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-bottom: 1px solid #ddd;
    border-radius: 15px 15px 0 0;
    transition: transform 0.3s ease;
}
.product-img img:hover {
    transform: scale(1.1);
}
.product-info {
    padding: 20px;
    text-align: center;
}
.product-name {
    font-size: 1.8em;
    color: #333;
    margin-bottom: 15px;
    font-weight: 600;
}
.product-price {
    font-weight: bold;
    color: #2ecc71;
    margin-bottom: 15px;
    font-size: 1.4em;
}
.product-category, .product-cond {
    font-size: 1.1em;
    color: #777;
}
.manage-buttons {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-top: 20px;
}
.manage-buttons a {
    text-decoration: none;
    padding: 15px 30px;
    border-radius: 30px;
    color: #fff;
    font-size: 1.2em;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
}
.manage-buttons a:hover {
    transform: scale(1.05);
}

/* ปุ่มซื้อ */
.manage-buttons .buy {
    background-color: #2ecc71;
}
.manage-buttons .buy:hover {
    background-color: #27ae60;
}
.manage-buttons .buy i {
    font-size: 1.4em;
}

/* ปุ่มรายละเอียด */
.manage-buttons .details {
    background-color: #3498db;
}
.manage-buttons .details:hover {
    background-color: #2980b9;
}
.manage-buttons .details i {
    font-size: 1.4em;
}

/* ปุ่มคอมเมนต์ */
.manage-buttons .comment {
    background-color: #f39c12;
}
.manage-buttons .comment:hover {
    background-color: #e67e22;
}
.manage-buttons .comment i {
    font-size: 1.4em;
}

/* ข้อความสินค้าหมด */
.out-of-stock {
    color: #e74c3c;
    font-weight: bold;
    margin-top: 15px;
    font-size: 1.2em;
    text-align: center;
}

/* การตั้งค่า Responsive */
@media (max-width: 768px) {
    .product-card {
        width: calc(50% - 20px);
    }
}
@media (max-width: 480px) {
    .product-card {
        width: 100%;
    }
}
</style>

<div class="container">
    <h1 class="page-title">Manage Products</h1>

    <?php
    // ตรวจสอบว่าอีเมลของผู้ใช้มีข้อมูลในฐานข้อมูล
    $sql = "SELECT * FROM Users WHERE email = '".$_SESSION['email']."'"; // ใช้ session เพื่อดึงข้อมูลผู้ใช้ที่ล็อกอิน
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $sql_products = "SELECT * FROM Products";
    $result_products = mysqli_query($conn, $sql_products);

    // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
    if (mysqli_num_rows($result_products) > 0) {
        echo "<div class='product-list'>";
        while ($row = mysqli_fetch_assoc($result_products)) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_price = number_format($row['price'], 2);
            $product_category = ucfirst($row['category']);
            $product_cond = ucfirst($row['cond']);
            $product_image = $row['image_url'] ?: 'default-image.jpg';
            $is_orderable = $row['is_orderable']; // สถานะสินค้า

            echo "<div class='product-card'>
                    <div class='product-img'>
                        <img src='$product_image' alt='$product_name' />
                    </div>
                    <div class='product-info'>
                        <h2 class='product-name'>$product_name</h2>
                        <p class='product-category'>$product_category</p>
                        <p class='product-price'><i class='fas fa-tag'></i> ฿$product_price</p>
                        <p class='product-cond'><em>$product_cond</em></p>";

            // ตรวจสอบสถานะของสินค้า
            if ($is_orderable == 1) {
                // ตรวจสอบว่าอีเมลของผู้ใช้มีข้อมูลที่อยู่หรือไม่
                if ($user && empty($user['shipping_address'])) { // ถ้าไม่มีที่อยู่
                    echo "<p>กรุณากรอกที่อยู่ใน <a href='profile.php'>โปรไฟล์</a> ก่อนทำการสั่งซื้อ</p>";
                } else {
                    // ถ้ามีที่อยู่หรือไม่มีการล็อกอินก็สามารถไปหน้า add_order.php ได้
                    echo "<a class='buy' href='add_order.php?id=$product_id'><i class='fas fa-shopping-cart'></i> สั่งซื้อ</a>";
                }
                echo "<a class='details' href='#' onclick='openModal($product_id)'><i class='fas fa-info-circle'></i> ลายละเอียด</a>
                      <a class='comment' href='comment.php?product_id=$product_id'><i class='fas fa-comment'></i> คอมเมนต์</a>
                    </div>";
            } else {
                echo "<p class='out-of-stock'>สินค้าหมด</p>";
            }
            echo "</div>
                </div>";

            // Modal content
            echo "<div id='modal$product_id' class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick='closeModal($product_id)'>&times;</span>
                        <h2>$product_name</h2>
                        <p><strong>ราคา:</strong> ฿$product_price</p>
                        <p><strong>หมวดหมู่:</strong> $product_category</p>
                        <p><strong>สภาพ:</strong> $product_cond</p>
                        <p><strong>คำอธิบาย:</strong> " . ($row['description'] ?: 'ไม่มีคำอธิบาย') . "</p>
                    </div>
                </div>";
        }
        echo "</div>";
    } else {
        echo "<p>No products available.</p>";
    }

    mysqli_close($conn);
    ?>

</div>

<!-- JavaScript สำหรับเปิด/ปิด Modal -->
<script>
function openModal(productId) {
    document.getElementById('modal' + productId).style.display = 'block';
}

function closeModal(productId) {
    document.getElementById('modal' + productId).style.display = 'none';
}

// ปิด modal เมื่อคลิกที่นอกกล่อง
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            modal.style.display = 'none';
        });
    }
}
</script>

<?php include("include/footer.php"); ?>
