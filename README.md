# Array Utils

Một package PHP đơn giản để làm việc với mảng.

## Cài đặt

```bash
composer require hoanglong2905/php-utils
```

## Sử dụng

```php
require 'vendor/autoload.php';

use PHPUtils\ArrayHelper;

$array = [1, 2, 3];

// Lấy phần tử đầu tiên
$first = ArrayHelper::first($array);

// Lấy phần tử cuối cùng
$last = ArrayHelper::last($array);

// Kiểm tra mảng liên kết
$isAssoc = ArrayHelper::isAssoc(['a' => 1, 'b' => 2]);
```
## Tác giả
- Hoang Long <hoanglong2905@gmail.com>
