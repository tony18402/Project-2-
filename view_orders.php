<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ตรวจสอบการลบคำสั่งซื้อ
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "DELETE FROM Orders WHERE order_id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>ลบคำสั่งซื้อสำเร็จ</div>";
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }
}

// ตรวจสอบการอัปเดตสถานะ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = (int)$_POST['order_id'];
    $payment_status = $_POST['payment_status'];
    $shipping_status = $_POST['shipping_status'];

    $update_sql = "UPDATE Orders SET payment_status = ?, shipping_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('ssi', $payment_status, $shipping_status, $order_id);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>อัพเดทสถานะคำสั่งซื้อสำเร็จ</div>";
        
        // หากเป็นชำระเงินแล้ว หรือจัดส่งแล้ว ให้เด้งไปหน้า order_confirm.php
        if ($payment_status == 'paid' || in_array($shipping_status, ['shipped', 'delivered'])) {
            echo "<script>window.location.href='order_confirm.php';</script>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาด: " . $conn->error . "</div>";
    }

    $stmt->close();
}

// ดึงข้อมูลคำสั่งซื้อที่ยังไม่ชำระเงินหรือยังไม่ได้จัดส่ง
$sql = "SELECT * FROM Orders WHERE payment_status != 'paid' AND shipping_status NOT IN ('shipped', 'delivered')";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการคำสั่งซื้อ</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        select {
            width: 100%;
            padding: 5px;
        }
        .update-btn, .delete-btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .update-btn {
            background-color: #4CAF50;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>รายการคำสั่งซื้อ</h2>

    <?php if ($result->num_rows > 0): ?>
        <form method="POST">
            <table>
                <tr>
                    <th>รหัสคำสั่งซื้อ</th>
                    <th>รหัสสินค้า</th>
                    <th>วันที่สั่งซื้อ</th>
                    <th>จำนวน</th>
                    <th>ราคาทั้งหมด</th>
                    <th>สถานะการชำระเงิน</th>
                    <th>สถานะการจัดส่ง</th>
                    <th>ที่อยู่การจัดส่ง</th>
                    <th>อีเมล</th>
                    <th>การดำเนินการ</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= $row['product_id'] ?></td>
                        <td><?= $row['order_date'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= $row['total_price'] ?></td>
                        <td>
                            <select name="payment_status">
                                <option value="pending" <?= ($row['payment_status'] == 'pending') ? 'selected' : '' ?>>รอดำเนินการ</option>
                                <option value="paid" <?= ($row['payment_status'] == 'paid') ? 'selected' : '' ?>>ชำระเงินแล้ว</option>
                                <option value="failed" <?= ($row['payment_status'] == 'failed') ? 'selected' : '' ?>>การชำระเงินล้มเหลว</option>
                            </select>
                        </td>
                        <td>
                            <select name="shipping_status">
                                <option value="pending" <?= ($row['shipping_status'] == 'pending') ? 'selected' : '' ?>>รอดำเนินการ</option>
                                <option value="shipped" <?= ($row['shipping_status'] == 'shipped') ? 'selected' : '' ?>>ส่งแล้ว</option>
                                <option value="delivered" <?= ($row['shipping_status'] == 'delivered') ? 'selected' : '' ?>>จัดส่งแล้ว</option>
                                <option value="canceled" <?= ($row['shipping_status'] == 'canceled') ? 'selected' : '' ?>>ยกเลิก</option>
                            </select>
                        </td>
                        <td><?= $row['shipping_address'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td>
                            <button type="submit" name="order_id" value="<?= $row['order_id'] ?>" class="update-btn">อัพเดท</button>
                            <a href="?id=<?= $row['order_id'] ?>" class="delete-btn" onclick="return confirm('คุณต้องการลบคำสั่งซื้อนี้หรือไม่?')">ลบ</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </form>
    <?php else: ?>
        <div class='alert alert-warning'>ไม่มีข้อมูลคำสั่งซื้อ</div>
    <?php endif; ?>
</div>

</body>
</html>
