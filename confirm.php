<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ตรวจสอบว่าอีเมลของผู้ใช้ที่ล็อกอินมาแล้ว
session_start();
$email = $_SESSION['email'];

// ดึงข้อมูลคำสั่งซื้อที่มีสถานะการจัดส่งเป็น 'delivered'
$sql = "SELECT Orders.order_id, Orders.product_id, Orders.order_date, Orders.payment_status, Orders.shipping_status, Products.product_name
        FROM Orders
        JOIN Products ON Orders.product_id = Products.product_id
        WHERE Orders.email = '$email' AND Orders.shipping_status = 'delivered'"; // เฉพาะคำสั่งซื้อที่จัดส่งสำเร็จ
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าที่จัดส่งสำเร็จแล้ว</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
        }
        .container {
            width: 80%;
            margin: auto;
            text-align: center;
        }
        .alert {
            padding: 10px;
            margin-top: 20px;
            color: white;
            text-align: center;
        }
        .alert-danger { background-color: #ff4d4d; }
        .alert-success { background-color: #4CAF50; }
        .alert-warning { background-color: #ff9800; }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 สินค้าที่จัดส่งสำเร็จแล้ว</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>รหัสคำสั่งซื้อ</th>
                <th>ชื่อสินค้า</th>
                <th>วันที่จัดส่ง</th>
                <th>สถานะการชำระเงิน</th>
                <th>สถานะการจัดส่ง</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['order_id'] ?></td>
                    <td><?= $row['product_name'] ?></td>
                    <td><?= $row['order_date'] ?></td>
                    <td><?= $row['payment_status'] == 'paid' ? '✅ ชำระเงินแล้ว' : '⏳ รอดำเนินการ' ?></td>
                    <td>📦 จัดส่งสำเร็จ</td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class='alert alert-warning'>🚨 ยังไม่มีคำสั่งซื้อที่จัดส่งสำเร็จแล้ว</div>
    <?php endif; ?>
</div>

</body>
</html>

<?php
$conn->close();
?>
