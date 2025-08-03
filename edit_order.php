<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ตรวจสอบว่ามีการส่งฟอร์มด้วย POST หรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าที่ส่งมาจากฟอร์ม
    $order_id = $_POST['order_id'];
    $product_id = $_POST['product_id'];
    $order_date = $_POST['order_date'];
    $quantity = $_POST['quantity'];
    $total_price = $_POST['total_price'];
    $payment_status = $_POST['payment_status'];
    $shipping_status = $_POST['shipping_status'];
    $shipping_address = $_POST['shipping_address'];
    $email = $_POST['email'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE Orders 
            SET product_id = '$product_id', 
                order_date = '$order_date', 
                quantity = '$quantity', 
                total_price = '$total_price', 
                payment_status = '$payment_status', 
                shipping_status = '$shipping_status', 
                shipping_address = '$shipping_address', 
                email = '$email'
            WHERE order_id = '$order_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color: green;'>Order ID $order_id updated successfully.</p>";
    } else {
        echo "<p style='color: red;'>Error updating Order ID $order_id: " . $conn->error . "</p>";
    }
}

// Fetch data from the `Orders` table
$sql = "SELECT * FROM Orders";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Orders</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Edit Orders</h1>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Product ID</th>
                <th>Order Date</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Status</th>
                <th>Shipping Status</th>
                <th>Shipping Address</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST">
                            <td><input type="hidden" name="order_id" value="<?= $row['order_id'] ?>"><?= $row['order_id'] ?></td>
                            <td><input type="number" name="product_id" value="<?= $row['product_id'] ?>"></td>
                            <td><input type="datetime-local" name="order_date" value="<?= date('Y-m-d\TH:i', strtotime($row['order_date'])) ?>"></td>
                            <td><input type="number" name="quantity" value="<?= $row['quantity'] ?>"></td>
                            <td><input type="text" name="total_price" value="<?= $row['total_price'] ?>"></td>
                            <td>
                                <select name="payment_status">
                                    <option value="pending" <?= $row['payment_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="paid" <?= $row['payment_status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="failed" <?= $row['payment_status'] == 'failed' ? 'selected' : '' ?>>Failed</option>
                                </select>
                            </td>
                            <td>
                                <select name="shipping_status">
                                    <option value="pending" <?= $row['shipping_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="shipped" <?= $row['shipping_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                    <option value="delivered" <?= $row['shipping_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                    <option value="canceled" <?= $row['shipping_status'] == 'canceled' ? 'selected' : '' ?>>Canceled</option>
                                </select>
                            </td>
                            <td><input type="text" name="shipping_address" value="<?= $row['shipping_address'] ?>"></td>
                            <td><input type="email" name="email" value="<?= $row['email'] ?>"></td>
                            <td>
                                <button type="submit">Update</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close();
?>
