<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT * FROM Products";
$result = mysqli_query($conn, $sql);
?>

<!-- เชื่อมต่อ Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
body { font-family: 'Arial', sans-serif; background-color: #f9f9f9; color: #333; }
.container { max-width: 1200px; margin: 0 auto; padding: 20px; }
.page-title { text-align: center; font-size: 2.5em; margin-bottom: 30px; color: #4CAF50; }
.product-list { display: flex; flex-wrap: wrap; gap: 30px; justify-content: space-around; }
.product-card { background: #fff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: calc(33.333% - 20px); margin-bottom: 30px; transition: transform 0.3s, box-shadow 0.3s; }
.product-card:hover { transform: translateY(-10px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15); }
.product-img img { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid #ddd; }
.product-info { padding: 15px; }
.product-name { font-size: 1.8em; color: #333; }
.product-price { font-weight: bold; color: #4CAF50; }
.manage-buttons { display: flex; gap: 10px; justify-content: center; margin-top: 10px; }
.manage-buttons a { text-decoration: none; padding: 10px 15px; border-radius: 5px; color: #fff; transition: background-color 0.3s ease; font-size: 0.9em; }
.manage-buttons .buy { background-color: #e74c3c; }
.manage-buttons .buy:hover { background-color: #c0392b; }
.manage-buttons .details { background-color: #3498db; }
.manage-buttons .details:hover { background-color: #2980b9; }
.manage-buttons .comment { background-color: #f39c12; }
.manage-buttons .comment:hover { background-color: #e67e22; }

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    padding-top: 60px;
}

.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #ddd;
    width: 80%;
    max-width: 600px;
    border-radius: 10px;
}

.close {
    color: #aaa;
    font-size: 28px;
    font-weight: bold;
    position: absolute;
    top: 10px;
    right: 25px;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

.comment-section {
    margin-top: 20px;
    padding: 10px;
    border-top: 1px solid #ddd;
}

.comment {
    margin-bottom: 10px;
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.comment-email {
    font-weight: bold;
    color: #3498db;
}
</style>

<div class="container">
    <h1 class="page-title">Manage Products</h1>

    <?php
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='product-list'>";
        while ($row = mysqli_fetch_assoc($result)) {
            $product_id = $row['product_id'];
            $product_name = $row['product_name'];
            $product_price = number_format($row['price'], 2);
            $product_category = ucfirst($row['category']);
            $product_cond = ucfirst($row['cond']);
            $product_image = $row['image_url'] ?: 'default-image.jpg';
            $product_description = $row['description'];

            echo "<div class='product-card'>
                    <div class='product-img'>
                        <img src='$product_image' alt='$product_name' />
                    </div>
                    <div class='product-info'>
                        <h2 class='product-name'>$product_name</h2>
                        <p>$product_category</p>
                        <p class='product-price'><i class='fas fa-tag'></i> ฿$product_price</p>
                        <p><em>$product_cond</em></p>
                        <div class='manage-buttons'>
                            <a class='details' href='#' onclick='openModal($product_id)'><i class='fas fa-info-circle'></i> รายละเอียด</a>
                            <a class='comment' href='#' onclick='openCommentModal($product_id)'><i class='fas fa-comment'></i> คอมเม้น</a>
                        </div>
                    </div>
                </div>";

            // Modal แสดงรายละเอียดสินค้า
            echo "<div id='modal$product_id' class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick='closeModal($product_id)'>&times;</span>
                        <h2>$product_name</h2>
                        <p><strong>ราคา:</strong> ฿$product_price</p>
                        <p><strong>หมวดหมู่:</strong> $product_category</p>
                        <p><strong>สภาพ:</strong> $product_cond</p>
                        <p><strong>คำอธิบาย:</strong> $product_description</p>
                    </div>
                </div>";

            // Modal แสดงความคิดเห็น
            echo "<div id='commentModal$product_id' class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick='closeCommentModal($product_id)'>&times;</span>
                        <h2>ความคิดเห็นสำหรับ: $product_name</h2>";

            // ดึงความคิดเห็นจากฐานข้อมูล
            $comment_query = "SELECT user_name, email, comment_text, created_at FROM comments WHERE product_id = $product_id";
            $comment_result = mysqli_query($conn, $comment_query);

            echo "<div class='comment-section'>";
            if (mysqli_num_rows($comment_result) > 0) {
                while ($comment = mysqli_fetch_assoc($comment_result)) {
                    $comment_text = $comment['comment_text'];
                    $comment_email = $comment['email'];
                    $comment_date = $comment['created_at'];

                    echo "<div class='comment'>
                            <p class='comment-email'><i class='fas fa-envelope'></i> $comment_email</p>
                            <p>$comment_text</p>
                            <small><em>$comment_date</em></small>
                          </div>";
                }
            } else {
                echo "<p>ไม่มีความคิดเห็น</p>";
            }
            echo "</div>";

            if (isset($_SESSION['username'])) {
                echo "<form action='submit_comment.php' method='POST'>
                        <textarea name='comment' rows='5' placeholder='เขียนความคิดเห็นของคุณ...' required></textarea>
                        <input type='hidden' name='product_id' value='$product_id'>
                        <button type='submit'>ส่งคอมเม้นต์</button>
                    </form>";
            } else {
                echo "<p class='comment-message'>กรุณาล็อกอินเพื่อเพิ่มความคิดเห็น</p>";
            }

            echo "</div></div>";
        }
        echo "</div>";
    } else {
        echo "<p>ไม่มีสินค้าที่จะแสดง</p>";
    }

    mysqli_close($conn);
    ?>
</div>

<!-- JavaScript -->
<script>
function openModal(productId) {
    document.getElementById('modal' + productId).style.display = 'block';
}

function closeModal(productId) {
    document.getElementById('modal' + productId).style.display = 'none';
}

function openCommentModal(productId) {
    document.getElementById('commentModal' + productId).style.display = 'block';
}

function closeCommentModal(productId) {
    document.getElementById('commentModal' + productId).style.display = 'none';
}

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
