<?php

namespace PHPUtils;

class ImageHelper
{
    /**
     * Tạo hình ảnh tự động với kích thước và màu nền truyền vào.
     *
     * @param int $width Chiều rộng ảnh
     * @param int $height Chiều cao ảnh
     * @param string|null $savePath Đường dẫn lưu file (nếu null sẽ trả về dữ liệu ảnh tạm thời)
     * @param string $format Định dạng ảnh (png, jpeg, gif)
     * @param array $bgColor Màu nền, dạng [R, G, B] (mặc định: trắng)
     * @return string|bool Trả về đường dẫn file nếu lưu, hoặc dữ liệu ảnh nếu tạm thời. False nếu lỗi.
     */
    public static function createImage($width, $height, $savePath = null, $format = 'png', $bgColor = [255,255,255])
    {
        // Kiểm tra extension GD đã được cài đặt chưa
        if (!extension_loaded('gd')) {
            return false;
        }
        // Tạo ảnh mới với kích thước truyền vào
        $image = imagecreatetruecolor($width, $height);
        // Tạo màu nền từ mảng RGB
        $color = imagecolorallocate($image, $bgColor[0], $bgColor[1], $bgColor[2]);
        // Đổ màu nền cho toàn bộ ảnh
        imagefilledrectangle($image, 0, 0, $width, $height, $color);

        // Nếu không có $savePath hoặc $savePath không hợp lệ (không phải string hoặc là rỗng)
        if (!$savePath || !is_string($savePath) || trim($savePath) === '') {
            // Xác định thư mục gốc project
            $root = dirname(__DIR__, 1); // src -> project root
            $dir = $root . '/public/created_image';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            // Đặt tên file random dựa trên thời gian và random bytes
            $filename = 'created_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($format);
            $savePath = $dir . '/' . $filename;
        }

        ob_start();
        $result = false;
        // Chọn định dạng xuất ảnh
        switch (strtolower($format)) {
            case 'jpeg':
            case 'jpg':
                if ($savePath) {
                    $result = imagejpeg($image, $savePath);
                } else {
                    imagejpeg($image);
                    $result = ob_get_contents();
                }
                break;
            case 'gif':
                if ($savePath) {
                    $result = imagegif($image, $savePath);
                } else {
                    imagegif($image);
                    $result = ob_get_contents();
                }
                break;
            case 'png':
            default:
                if ($savePath) {
                    $result = imagepng($image, $savePath);
                } else {
                    imagepng($image);
                    $result = ob_get_contents();
                }
                break;
        }
        ob_end_clean();
        imagedestroy($image);
        if ($savePath) {
            return $savePath;
        }
        return $result;
    }

    /**
     * Lấy một ảnh bất kỳ từ trang miễn phí (Lorem Picsum).
     *
     * @param int $width Chiều rộng mong muốn
     * @param int $height Chiều cao mong muốn
     * @param string|null $savePath Đường dẫn lưu file (nếu null sẽ trả về dữ liệu ảnh)
     * @return string|bool Nếu truyền $savePath sẽ trả về đường dẫn file (string) hoặc false nếu lỗi. Nếu không truyền $savePath sẽ trả về dữ liệu nhị phân ảnh (string) hoặc false nếu lỗi.
     */
    public static function getRandomFreeImage($width = 400, $height = 300, $savePath = null)
    {
        // Tạo URL ảnh random từ Lorem Picsum
        $url = "https://picsum.photos/{$width}/{$height}";
        // $imageData: string|false - dữ liệu nhị phân ảnh nếu thành công, false nếu lỗi
        $imageData = @file_get_contents($url);
        if ($imageData === false) {
            // Lỗi khi tải ảnh, trả về false
            return false;
        }
        // Nếu không có $savePath hoặc $savePath không hợp lệ (không phải string hoặc là rỗng)
        if (!$savePath || !is_string($savePath) || trim($savePath) === '') {
            // Xác định thư mục gốc project
            $root = dirname(__DIR__, 1); // src -> project root
            $dir = $root . '/public/created_image';
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            // Đặt tên file random dựa trên thời gian và random bytes
            $filename = 'image_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.jpg';
            $savePath = $dir . '/' . $filename;
        }
        // $result: int|false - số byte đã ghi nếu thành công, false nếu lỗi
        $result = file_put_contents($savePath, $imageData);
        // Nếu ghi file thành công, trả về đường dẫn file ($savePath), ngược lại trả về false
        return $result !== false ? $savePath : false;
    }
}
