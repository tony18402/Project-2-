<?php
include("include/header.php");
include("include/config.php");
include("include/navbar.php");
include("include/exit.php");

// ตรวจสอบการลบผู้ใช้
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // สร้างคำสั่ง SQL เพื่อลบผู้ใช้
    $delete_sql = "DELETE FROM Users WHERE user_id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        echo "<div class='alert alert-success'>ลบผู้ใช้สำเร็จ!</div>";
    } else {
        echo "<div class='alert alert-danger'>เกิดข้อผิดพลาดในการลบผู้ใช้: " . mysqli_error($conn) . "</div>";
    }
}

// ตรวจสอบการค้นหาผู้ใช้
$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// ดึงข้อมูลผู้ใช้ทั้งหมดจากฐานข้อมูล
$sql = "SELECT * FROM Users WHERE username LIKE '%$search_query%'";
$result = mysqli_query($conn, $sql);
?>

<div class="container">
    <h1 class="page-title text-center text-primary mb-4">จัดการผู้ใช้</h1>

    <!-- ช่องค้นหาผู้ใช้ -->
    <form method="POST" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control form-control-lg" name="search" placeholder="ค้นหาผู้ใช้..." value="<?php echo htmlspecialchars($search_query); ?>" aria-label="ค้นหาผู้ใช้">
            <button class="btn btn-sell btn-lg" type="submit">
                <i class="fas fa-search"></i> ค้นหา
            </button>
        </div>
    </form>

    <!-- ตารางข้อมูลผู้ใช้ -->
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered shadow-lg">
            <thead class="thead-primary">
                <tr>
                    <th>หมายเลขผู้ใช้</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>อีเมล</th>
                    <th>สถานะ</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // แสดงข้อมูลผู้ใช้
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";

                        // สถานะของผู้ใช้
                        if ($row['level'] == 'admin') {
                            echo "<td>Admin</td>";
                        } else {
                            echo "<td>Buyer</td>";
                        }

                        // ตรวจสอบว่า user เป็น admin หรือไม่
                        if ($row['level'] !== 'admin') {
                            echo "<td>
                                    <a href='edit_user.php?id=" . $row['user_id'] . "' class='btn btn-warning btn-sm'>
                                        <i class='fas fa-edit'></i> แก้ไข
                                    </a>
                                    <a href='?delete_id=" . $row['user_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"คุณแน่ใจหรือไม่ที่จะลบผู้ใช้นี้?\")'>
                                        <i class='fas fa-trash'></i> ลบ
                                    </a>
                                  </td>";
                        } else {
                            echo "<td>ไม่สามารถแก้ไขหรือทำการลบผู้ใช้ได้</td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>ไม่พบข้อมูลผู้ใช้</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include("include/footer.php");
?>

<!-- เพิ่ม FontAwesome CDN สำหรับไอคอน -->
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<!-- เพิ่ม Bootstrap CDN สำหรับสไตล์ -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<!-- การปรับแต่ง CSS ให้มีการใช้งานที่ดูดีขึ้น -->
<style>
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #f39c12; /* สีทองเข้มเพื่อให้ดูหรูหรา */
        margin-bottom: 20px;
    }

    .table th, .table td {
        vertical-align: middle;
    }

    .table thead {
        background-color: #e67e22; /* สีส้มสด */
        color: #fff;
    }

    .btn-sell {
        background-color: #f39c12; /* สีทอง */
        border-color: #e67e22; /* สีส้ม */
        font-size: 1.2rem;
        padding: 10px 20px;
        transition: background-color 0.3s ease;
    }

    .btn-sell:hover {
        background-color: #e67e22; /* สีส้มเข้มเมื่อ hover */
        border-color: #d35400; /* สีส้มเข้มที่ใช้เมื่อ hover */
    }

    .badge {
        font-size: 1rem;
        padding: 5px 10px;
    }

    .shadow-lg {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* ปรับปุ่มใน navbar */
    .navbar-dark .navbar-nav .nav-link {
        color: #f8f9fa;
    }

    .navbar-dark .navbar-nav .nav-link:hover {
        color: #f39c12; /* สีทองเมื่อ hover */
    }

    /* ปรับสไตล์ของตาราง */
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
    }

    .table-hover tbody tr:hover {
        background-color: #f1c40f; /* สีเหลืองทองเมื่อ hover */
    }

    .table th, .table td {
        text-align: center;
    }

    .thead-primary th {
        color: #fff;
        background-color: #e67e22;
    }
</style>
