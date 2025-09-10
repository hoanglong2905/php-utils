# Array Utils

Một package PHP đơn giản để làm việc với mảng.

## Cài đặt

```bash
composer require hoanglong2905/php-utils
```

## Sử dụng

### ArrayHelper

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

// Xem nhanh giá trị mảng (dừng chương trình)
ArrayHelper::view($array);
```

### ApiHelper

```php
require 'vendor/autoload.php';

use PHPUtils\ApiHelper;

// Gọi GET
$getResult = ApiHelper::get('https://jsonplaceholder.typicode.com/posts', ['userId' => 1]);
print_r(json_decode($getResult['response'], true));

// Gọi POST
$postResult = ApiHelper::post('https://jsonplaceholder.typicode.com/posts', [
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1
]);
print_r(json_decode($postResult['response'], true));

// Gọi PUT
$putResult = ApiHelper::put('https://jsonplaceholder.typicode.com/posts/1', [
    'id' => 1,
    'title' => 'updated',
    'body' => 'baz',
    'userId' => 1
]);
print_r(json_decode($putResult['response'], true));

// Gọi DELETE
$deleteResult = ApiHelper::delete('https://jsonplaceholder.typicode.com/posts/1');
print_r(json_decode($deleteResult['response'], true));
```

## Tác giả
- Hoang Long <hoanglong2905@gmail.com>
