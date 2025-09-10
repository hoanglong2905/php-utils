<?php

namespace PHPUtils;

/**
 * Một số hàm tiện ích để làm việc với mảng trong PHP:
 *
 * - first(array $array): Lấy phần tử đầu tiên của mảng.
 * - last(array $array): Lấy phần tử cuối cùng của mảng.
 * - isAssoc(array $array): Kiểm tra mảng có phải là mảng kết hợp không.
 * - filter(array $array, callable $callback): Lọc các phần tử của mảng theo điều kiện.
 * - map(array $array, callable $callback): Ánh xạ các phần tử sang giá trị mới.
 * - contains(array $array, $value, bool $strict = false): Kiểm tra giá trị có tồn tại trong mảng.
 * - get(array $array, $key, $default = null): Lấy giá trị theo key, có thể trả về mặc định.
 * - remove(array $array, $value, bool $strict = false): Xóa phần tử theo giá trị.
 * - merge(...$arrays): Gộp nhiều mảng lại với nhau.
 * - keys(array $array): Lấy tất cả key của mảng.
 * - values(array $array): Lấy tất cả giá trị của mảng.
 * - unique(array $array): Lấy các giá trị duy nhất.
 * - join(array $array, string $glue = ","): Gộp các giá trị thành chuỗi.
 */

class ArrayHelper
{
    /**
     * Xuất giá trị của một biến (thường là mảng) và dừng chương trình
     *
     * @param mixed $var
     * @return void
     */
    public static function view(array $var)
    {
        echo '<pre style="background:#222;color:#fff;padding:10px;border-radius:6px;">';
        print_r($var);
        echo '</pre>';
        die();
    }
}
