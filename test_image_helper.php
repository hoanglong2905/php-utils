<?php
require_once __DIR__ . '/src/ImageHelper.php';
use PHPUtils\ImageHelper;

echo "Test tạo ảnh không truyền savePath...\n";
$result = ImageHelper::createImage(120, 90, null, 'png', [0,255,0]);
if ($result !== false && file_exists($result)) {
    echo "Đã tự động lưu ảnh tại $result!\n";
} else {
    echo "Tạo/lưu ảnh tự động thất bại!\n";
}

echo "Test tạo ảnh truyền savePath...\n";
$savePath = 'test_save.png';
$result = ImageHelper::createImage(150, 150, $savePath, 'png', [255,0,0]);
if ($result !== false && file_exists($savePath)) {
    echo "Đã lưu ảnh tại $savePath!\n";
} else {
    echo "Tạo/lưu ảnh thất bại!\n";
}

echo "Test lấy ảnh miễn phí không truyền savePath...\n";
$result = ImageHelper::getRandomFreeImage(200, 200, null);
if ($result !== false && file_exists($result)) {
    echo "Đã tự động lưu ảnh miễn phí tại $result!\n";
} else {
    echo "Lấy/lưu ảnh miễn phí tự động thất bại!\n";
}

echo "Test lấy ảnh miễn phí truyền savePath...\n";
$savePath = 'random_free_image.jpg';
$result = ImageHelper::getRandomFreeImage(320, 240, $savePath);
if ($result !== false && file_exists($savePath)) {
    echo "Đã lưu ảnh miễn phí tại $savePath!\n";
} else {
    echo "Lấy/lưu ảnh miễn phí thất bại!\n";
}
