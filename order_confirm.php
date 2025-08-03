<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ตรวจสอบว่ามีคำขอให้ลบหรือไม่
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];

    // ตรวจสอบว่าสถานะเป็น "จัดส่งสำเร็จ" หรือไม่
    $check_sql = "SELECT shipping_status FROM Orders WHERE order_id = $delete_id";
    $check_result = $conn->query($check_sql);
    $row = $check_result->fetch_assoc();

    if ($row['shipping_status'] === 'delivered') {
        echo "<div class='alert alert-danger'>❌ ไม่สามารถลบคำสั่งซื้อที่จัดส่งสำเร็จแล้วได้</div>";
    } else {
        // ลบคำสั่งซื้อถ้าไม่ได้อยู่ในสถานะ "จัดส่งสำเร็จ"
        $delete_sql = "DELETE FROM Orders WHERE order_id = $delete_id";
        if ($conn->query($delete_sql) === TRUE) {
            echo "<div class='alert alert-success'>✅ ลบคำสั่งซื้อสำเร็จ</div>";
        } else {
            echo "<div class='alert alert-danger'>❌ เกิดข้อผิดพลาด: " . $conn->error . "</div>";
        }
    }
}

// ตรวจสอบว่ามีการส่งข้อมูลเพื่ออัปเดตคำสั่งซื้อหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = (int)$_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $shipping_status = $_POST['shipping_status'];

    // อัปเดตข้อมูลสถานะในฐานข้อมูล
    $update_sql = "UPDATE Orders SET payment_status = ?, shipping_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ssi', $payment_status, $shipping_status, $order_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ อัปเดตสถานะคำสั่งซื้อสำเร็จ</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// ดึงข้อมูลคำสั่งซื้อและอีเมลจากผู้ใช้
$sql = "SELECT Orders.order_id, Orders.product_id, Orders.order_date, Orders.payment_status, Orders.shipping_status, Users.email
        FROM Orders
        JOIN Users ON Orders.email = Users.email
        WHERE Orders.payment_status = 'paid' OR Orders.shipping_status IN ('shipped', 'delivered')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการคำสั่งซื้อที่ยืนยันแล้ว</title>
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
        .update-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
        }
        .update-btn { background-color: #2196F3; }
        .delete-btn { background-color: #f44336; }
        select {
            padding: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📦 รายการคำสั่งซื้อที่ยืนยันแล้ว</h2>

    <?php if ($result->num_rows > 0): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>รหัสคำสั่งซื้อ</th>
                    <th>รหัสสินค้า</th>
                    <th>วันที่สั่งซื้อ</th>
                    <th>สถานะการชำระเงิน</th>
                    <th>สถานะการจัดส่ง</th>
                    <th>อีเมลผู้สั่ง</th>
                    <th>การดำเนินการ</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= $row['order_date'] ?></td>
                        <td>
                            <select name="payment_status">
                                <option value="pending" <?= ($row['payment_status'] == "pending") ? "selected" : "" ?>>⏳ รอดำเนินการ</option>
                                <option value="paid" <?= ($row['payment_status'] == "paid") ? "selected" : "" ?>>✅ ชำระเงินแล้ว</option>
                                <option value="failed" <?= ($row['payment_status'] == "failed") ? "selected" : "" ?>>❌ ชำระเงินล้มเหลว</option>
                            </select>
                        </td>
                        <td>
                            <select name="shipping_status">
                                <option value="pending" <?= ($row['shipping_status'] == "pending") ? "selected" : "" ?>>⏳ รอดำเนินการ</option>
                                <option value="shipped" <?= ($row['shipping_status'] == "shipped") ? "selected" : "" ?>>🚚 กำลังจัดส่ง</option>
                                <option value="delivered" <?= ($row['shipping_status'] == "delivered") ? "selected" : "" ?>>📦 จัดส่งสำเร็จ</option>
                            </select>
                        </td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <button type="submit" name="order_id" value="<?= $row['order_id'] ?>" class="update-btn">อัปเดต</button>

                            <?php if ($row['shipping_status'] != 'delivered'): ?>
                                <a href="?delete_id=<?= $row['order_id'] ?>" class="delete-btn" onclick="return confirm('⚠️ คุณแน่ใจว่าต้องการลบคำสั่งซื้อนี้หรือไม่?');">
                                    ลบ
                                </a>
                            <?php else: ?>
                                <span style="color:gray;">❌ ลบไม่ได้</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </form>
    <?php else: ?>
        <div class='alert alert-warning'>🚨 ไม่มีคำสั่งซื้อที่ยืนยันแล้ว</div>
    <?php endif; ?>
</div>

</body>
</html>
