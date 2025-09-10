# Array Utils

Một package giúp làm việc với PHP.

## Cài đặt

### Cài đặt bằng Composer (Khuyến nghị)

```bash
composer require hoanglong2905/php-utils
```

### Hoặc sử dụng thủ công (require từng file)

Chỉ cần copy các file cần dùng trong thư mục `src` vào project của bạn. Ví dụ:

```php
require_once '/path/to/ImageHelper.php';
require_once '/path/to/ArrayHelper.php';
require_once '/path/to/ApiHelper.php';

use PHPUtils\ImageHelper;
// ...
```

## Sử dụng

### ImageHelper

> **Lưu ý:** Nếu không truyền `savePath` hoặc truyền giá trị không hợp lệ, các hàm sẽ tự động lưu ảnh vào thư mục `public/created_image` ở thư mục gốc project và trả về đường dẫn file vừa lưu.

```php
require 'vendor/autoload.php';

use PHPUtils\ImageHelper;

// Tạo ảnh và tự động lưu vào public/created_image
$autoPath = ImageHelper::createImage(200, 100, null, 'png', [0,128,255]);
echo $autoPath; // Đường dẫn file ảnh vừa tạo

// Tạo ảnh và lưu trực tiếp ra file chỉ định
$savePath = 'my_image.png';
ImageHelper::createImage(300, 200, $savePath, 'png', [255,255,0]);

// Lấy ảnh miễn phí và tự động lưu vào public/created_image
$freeImgPath = ImageHelper::getRandomFreeImage(400, 300);
echo $freeImgPath; // Đường dẫn file ảnh miễn phí

// Lấy ảnh miễn phí và lưu ra file chỉ định
ImageHelper::getRandomFreeImage(400, 300, 'free.jpg');
```


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
