<?php
// test_api_helper.php
require_once __DIR__ . '/src/ApiHelper.php';

use PHPUtils\ApiHelper;

// Test GET
$getResult = ApiHelper::get('https://jsonplaceholder.typicode.com/posts', ['userId' => 1]);
echo "GET /posts?userId=1\n";
print_r(json_decode($getResult['response'], true));
echo "\n---------------------\n";

// Test POST
$postResult = ApiHelper::post('https://jsonplaceholder.typicode.com/posts', [
    'title' => 'foo',
    'body' => 'bar',
    'userId' => 1
]);
echo "POST /posts\n";
print_r(json_decode($postResult['response'], true));
echo "\n---------------------\n";

// Test PUT
$putResult = ApiHelper::put('https://jsonplaceholder.typicode.com/posts/1', [
    'id' => 1,
    'title' => 'updated',
    'body' => 'baz',
    'userId' => 1
]);
echo "PUT /posts/1\n";
print_r(json_decode($putResult['response'], true));
echo "\n---------------------\n";

// Test DELETE
$deleteResult = ApiHelper::delete('https://jsonplaceholder.typicode.com/posts/1');
echo "DELETE /posts/1\n";
print_r(json_decode($deleteResult['response'], true));
echo "\n---------------------\n";
