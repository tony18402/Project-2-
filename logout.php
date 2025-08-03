<?php
session_start();

// ล้างค่า session ทั้งหมด
session_unset();

// ลdestroy session
session_destroy();

// รีไดเร็กต์ผู้ใช้ไปยังหน้าหลักหรือหน้าที่ต้องการ
header("Location: index.php"); // เปลี่ยนเป็นหน้าที่ต้องการให้ผู้ใช้ไปหลังออกจากระบบ
exit();
?>
