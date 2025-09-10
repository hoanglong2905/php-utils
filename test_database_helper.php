<?php
// test_database_helper.php
require_once __DIR__ . '/src/DatabaseHelper.php';

// Thông tin kết nối (bạn cần sửa lại cho đúng với database của bạn)
$host = 'localhost';
$dbname = 'kellsler_db';
$username = 'user_db';
$password = '123';

try {
    $db = new DatabaseHelper($host, $dbname, $username, $password);
    
    // Test lấy tất cả dữ liệu (giả sử có bảng users)
    echo "\n--- Test fetchAll ---\n";
    $users = $db->fetchAll('users', '', [], 'id DESC', 5);
    print_r($users);
    
    // Test phân trang
    echo "\n--- Test paginate ---\n";
    $result = $db->paginate('users', 2, 3); // Trang 2, mỗi trang 3 bản ghi
    print_r($result);
    
} catch (PDOException $e) {
    echo "Lỗi kết nối hoặc truy vấn: " . $e->getMessage();
}
