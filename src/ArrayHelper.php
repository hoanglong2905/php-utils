<?php

namespace ArrayUtils;

class ArrayHelper
{
    /**
     * Lấy phần tử đầu tiên của mảng
     */
    public static function first(array $array)
    {
        return reset($array);
    }

    /**
     * Lấy phần tử cuối cùng của mảng
     */
    public static function last(array $array)
    {
        return end($array);
    }

    /**
     * Kiểm tra mảng có giá trị không
     */
    public static function isAssoc(array $array): bool
    {
        if ([] === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
