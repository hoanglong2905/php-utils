<?php
// test_array_helper.php
require_once __DIR__ . '/src/ArrayHelper.php';

use PHPUtils\ArrayHelper;

$array = [
    'name' => 'Cascade',
    'type' => 'AI',
    'features' => ['chat', 'code', 'auto'],
];

// Kiểm thử nhanh hàm view (tương tự dd)
ArrayHelper::view($array);
