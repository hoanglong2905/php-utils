<?php

namespace PHPUtils;

/**
 * Class hỗ trợ gọi API bằng curl với các method thông dụng: GET, POST, PUT, DELETE, PATCH.
 *
 * Ví dụ sử dụng:
 *   use PHPUtils\ApiHelper;
 *   $result = ApiHelper::get('https://api.example.com/users', ['page' => 1]);
 *   $result = ApiHelper::post('https://api.example.com/users', ['name' => 'Cascade']);
 */
class ApiHelper
{
    /**
     * Gửi một request HTTP với method bất kỳ.
     *
     * @param string $method  Phương thức HTTP (GET, POST, PUT, DELETE, PATCH)
     * @param string $url     Địa chỉ endpoint API
     * @param array  $data    Dữ liệu gửi đi (query hoặc body)
     * @param array  $headers Mảng header (mỗi phần tử dạng "Key: Value")
     * @param array  $options Các tuỳ chọn curl_setopt bổ sung (nếu cần)
     * @return array Kết quả gồm: [response, info, error]
     */
    public static function request($method, $url, $data = [], $headers = [], $options = [])
    {
        // Khởi tạo session curl
        $ch = curl_init();
        // Đảm bảo method luôn viết hoa
        $method = strtoupper($method);

        // Tuỳ thuộc vào method, cấu hình curl và xử lý dữ liệu gửi đi
        switch ($method) {
            case 'GET':
                // Nếu có dữ liệu, nối vào URL dạng query string
                if (!empty($data)) {
                    $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($data);
                }
                break;
            case 'POST':
                // Thiết lập method là POST
                curl_setopt($ch, CURLOPT_POST, true);
                // Thiết lập dữ liệu gửi đi (body)
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
                // Thiết lập method tuỳ chỉnh (PUT, DELETE, PATCH)
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                // Thiết lập dữ liệu gửi đi (body)
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                break;
        }

        // Thiết lập URL cho curl
        curl_setopt($ch, CURLOPT_URL, $url);
        // Trả về nội dung thay vì xuất trực tiếp ra màn hình
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Nếu có header, thiết lập header gửi đi
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        // Thiết lập thêm các option curl custom nếu có
        foreach ($options as $opt => $val) {
            curl_setopt($ch, $opt, $val);
        }

        // Thực thi request và lấy kết quả trả về
        $response = curl_exec($ch);
        // Lấy thông tin chi tiết về request/response (status, thời gian, ...)
        $info = curl_getinfo($ch);
        // Lấy lỗi (nếu có)
        $error = curl_error($ch);
        // Đóng session curl
        curl_close($ch);

        // Trả về mảng gồm: nội dung trả về, info chi tiết, và lỗi (nếu có)
        return [
            'response' => $response,
            'info' => $info,
            'error' => $error,
        ];
    }
    /**
     * Gửi request GET tới API.
     *
     * @param string $url     Địa chỉ endpoint
     * @param array  $params  Tham số query string
     * @param array  $headers Header gửi kèm
     * @param array  $options Tuỳ chọn curl bổ sung
     * @return array
     */
    public static function get($url, $params = [], $headers = [], $options = [])
    {
        return self::request('GET', $url, $params, $headers, $options);
    }

    /**
     * Gửi request POST tới API.
     *
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @param array  $options
     * @return array
     */
    public static function post($url, $data = [], $headers = [], $options = [])
    {
        return self::request('POST', $url, $data, $headers, $options);
    }

    /**
     * Gửi request PUT tới API.
     *
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @param array  $options
     * @return array
     */
    public static function put($url, $data = [], $headers = [], $options = [])
    {
        return self::request('PUT', $url, $data, $headers, $options);
    }

    /**
     * Gửi request DELETE tới API.
     *
     * @param string $url
     * @param array  $data
     * @param array  $headers
     * @param array  $options
     * @return array
     */
    public static function delete($url, $data = [], $headers = [], $options = [])
    {
        return self::request('DELETE', $url, $data, $headers, $options);
    }
}
