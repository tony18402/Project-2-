<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
include("include/exit.php");

if (!isset($_GET['id'])) {
    exit("ไม่พบข้อมูลสินค้า");
}

$product_id = $_GET['id'];

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT * FROM Products WHERE product_id = $product_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    exit("ไม่พบสินค้านี้ในระบบ");
}

$row = mysqli_fetch_assoc($result);
$product_name = $row['product_name'];
$product_price = $row['price'];
$product_image = $row['image_url'] ?: 'default-image.jpg';
$product_desc = $row['description'] ?: 'ไม่มีคำอธิบาย';

// สมมุติว่าเราเก็บอีเมลของผู้ใช้ในเซสชั่น
session_start();
$email = $_SESSION['email']; // อีเมลของผู้ใช้ที่ล็อกอินแล้ว

// ดึงข้อมูลที่อยู่ของผู้ใช้จากฐานข้อมูล
$sql_address = "SELECT shipping_address FROM Users WHERE email = '$email'";
$result_address = mysqli_query($conn, $sql_address);

$shipping_address = '';
if (mysqli_num_rows($result_address) > 0) {
    $address_row = mysqli_fetch_assoc($result_address);
    $shipping_address = $address_row['shipping_address'];
}

// ถ้ามีการส่งข้อมูลการสั่งซื้อ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $quantity = $_POST['quantity'];
    $shipping_address = $_POST['shipping_address'];

    if ($quantity <= 0) {
        echo "กรุณาระบุจำนวนสินค้าที่ต้องการ";
    } else {
        // คำนวณราคาสินค้ารวม
        $total_price = $product_price * $quantity;

        // เพิ่มข้อมูลการสั่งซื้อในฐานข้อมูล
        $sql_order = "INSERT INTO Orders (product_id, quantity, total_price, payment_status, shipping_status, shipping_address, email) 
                      VALUES ('$product_id', '$quantity', '$total_price', 'pending', 'pending', '$shipping_address', '$email')";
        
        if (mysqli_query($conn, $sql_order)) {
            echo "สั่งซื้อสำเร็จ!";
            header("Location: view_orders_user.php"); // ไปที่หน้าดูคำสั่งซื้อของผู้ใช้
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งซื้อสินค้า</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #4CAF50;
            font-size: 2.5em;
        }

        .product-image {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            border-radius: 10px;
        }

        .product-info {
            text-align: center;
            margin-top: 20px;
        }

        .product-info p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .form-container {
            margin-top: 30px;
            padding: 25px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="number"], input[type="email"], textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 1em;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>สั่งซื้อสินค้า</h1>

    <h2><?php echo $product_name; ?></h2>
    <img class="product-image" src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>">
    <div class="product-info">
        <p>ราคา: ฿<?php echo number_format($product_price, 2); ?></p>
        <p>คำอธิบาย: <?php echo $product_desc; ?></p>
    </div>

    <form method="POST" class="form-container">
        <div>
            <label for="quantity">จำนวน:</label>
            <input type="number" id="quantity" name="quantity" min="1" max="15" required>
        </div>
        <div>
            <label for="shipping_address">ที่อยู่สำหรับจัดส่ง:</label>
            <textarea id="shipping_address" name="shipping_address" required><?php echo $shipping_address; ?></textarea>
        </div>
        <div>
            <label for="email">อีเมล:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly>
        </div>
        <button type="submit">สั่งซื้อ</button>
    </form>
</div>

<div class="footer">
    <p>© 2025 ร้านค้าออนไลน์ | ทุกสิทธิ์สงวน</p>
</div>

</body>
</html>
