<?php
$conn = new mysqli('localhost', 'root', '', 'sales_erp');

if ($conn->connect_error) {
    die("فشل الاتصال: " . $conn->connect_error);
}

echo "<h2>فحص بيانات المستخدمين</h2>";

$result = $conn->query("SELECT id, username, password FROM users");

if ($result->num_rows == 0) {
    echo "<span style='color:red'>❌ لا يوجد مستخدمين في قاعدة البيانات!</span><br>";
    echo "الرجاء إضافة مستخدم أولاً";
} else {
    while($row = $result->fetch_assoc()) {
        echo "<hr>";
        echo "المستخدم: <strong>" . $row['username'] . "</strong><br>";
        echo "كلمة المرور المشفرة: " . $row['password'] . "<br>";
        
        // اختبار كلمة المرور 123456
        if (password_verify('123456', $row['password'])) {
            echo "<span style='color:green'>✅ كلمة المرور 123456 صحيحة لهذا المستخدم</span><br>";
        } else {
            echo "<span style='color:red'>❌ كلمة المرور 123456 غير صحيحة لهذا المستخدم</span><br>";
        }
    }
}

$conn->close();
?>