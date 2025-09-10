<?php
// src/DatabaseHelper.php

class DatabaseHelper {
    private $pdo;

    /**
     * Khởi tạo kết nối database sử dụng PDO
     * @param string $host     Địa chỉ host của database (VD: 'localhost')
     * @param string $dbname   Tên database
     * @param string $username Tên đăng nhập
     * @param string $password Mật khẩu
     * @param string $charset  Bộ mã ký tự (mặc định: utf8mb4)
     *
     * Các tham số static của PDO:
     *   - PDO::ATTR_ERRMODE: Thiết lập chế độ báo lỗi. ERRMODE_EXCEPTION sẽ ném ra exception nếu có lỗi.
     *   - PDO::ATTR_DEFAULT_FETCH_MODE: Thiết lập kiểu trả về mặc định là mảng kết hợp (FETCH_ASSOC).
     *   - PDO::ATTR_EMULATE_PREPARES: false sẽ dùng prepared statement thật (bảo mật hơn, đúng chuẩn MySQL).
     */
    public function __construct($host, $dbname, $username, $password, $charset = 'utf8mb4') {
        // Tạo chuỗi DSN cho kết nối MySQL
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        // Thiết lập các tuỳ chọn cho PDO
        $options = [
            // Báo lỗi dưới dạng exception (giúp dễ debug, an toàn)
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            // Kết quả trả về mặc định là mảng kết hợp (tên cột => giá trị)
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            // Không giả lập prepared statement, dùng thật của MySQL (bảo mật hơn)
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            // Khởi tạo đối tượng PDO với DSN, username, password và options
            $this->pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            // Nếu kết nối lỗi thì ném exception với thông báo lỗi
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * Lấy tất cả dữ liệu từ một bảng với các điều kiện tuỳ chọn
     * @param string $table   Tên bảng
     * @param string $where   Điều kiện WHERE (tùy chọn)
     * @param array  $params  Tham số truyền vào cho WHERE (nếu có)
     * @param string $orderBy Sắp xếp (ORDER BY)
     * @param int|null $limit Giới hạn số bản ghi trả về
     * @param int|null $offset Vị trí bắt đầu lấy bản ghi
     * @return array           Mảng dữ liệu kết quả
     * Thuật toán:
     * 1. Tạo câu truy vấn SELECT động dựa trên các tham số truyền vào
     * 2. Chuẩn bị truy vấn và bind các tham số nếu có
     * 3. Thực thi truy vấn và trả về tất cả kết quả
     */
    public function fetchAll($table, $where = '', $params = [], $orderBy = '', $limit = null, $offset = null) {
        // Bước 1: Khởi tạo câu truy vấn SELECT
        $sql = "SELECT * FROM `$table`";
        if ($where) {
            // Nếu có điều kiện WHERE thì thêm vào truy vấn
            $sql .= " WHERE $where";
        }
        if ($orderBy) {
            // Nếu có sắp xếp thì thêm ORDER BY
            $sql .= " ORDER BY $orderBy";
        }
        if ($limit !== null) {
            // Nếu có giới hạn số bản ghi thì thêm LIMIT
            $sql .= " LIMIT :limit";
            if ($offset !== null) {
                // Nếu có offset thì thêm OFFSET
                $sql .= " OFFSET :offset";
            }
        }
        // Bước 2: Chuẩn bị truy vấn
        $stmt = $this->pdo->prepare($sql);
        // Bind các tham số cho WHERE nếu có
        foreach ($params as $key => $value) {
            $stmt->bindValue(is_int($key) ? $key+1 : ":$key", $value);
        }
        // Bind giá trị LIMIT và OFFSET nếu có
        if ($limit !== null) {
            $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
            }
        }
        // Bước 3: Thực thi truy vấn và trả về kết quả
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Phân trang dữ liệu từ một bảng trong database
     *
     * Ví dụ cách gọi hàm paginate:
     *   // Lấy trang 1, mỗi trang 10 bản ghi, không điều kiện
     *   $db->paginate('users', 1, 10);
     *
     *   // Lấy trang 2, mỗi trang 5 bản ghi, có điều kiện WHERE và sắp xếp
     *   $db->paginate('users', 2, 5, 'is_active = :active', ['active' => 1], 'id DESC');
     *
     *   // Lấy trang 1, mỗi trang 20 bản ghi, lọc theo tên
     *   $db->paginate('users', 1, 20, 'name LIKE :name', ['name' => '%Long%']);
     *
     * Ví dụ truy vấn SQL sinh ra:
     *   SELECT * FROM `users` WHERE is_active = 1 ORDER BY id DESC LIMIT 10 OFFSET 20
     *   -- Lấy trang 3, mỗi trang 10 bản ghi, với điều kiện is_active = 1, sắp xếp id giảm dần
     *
     * @param string $table        Tên bảng
     * @param int    $page         Trang hiện tại (bắt đầu từ 1)
     * @param int    $perPage      Số bản ghi mỗi trang
     * @param string $where        Điều kiện WHERE (tùy chọn)
     * @param array  $params       Tham số truyền vào cho WHERE (nếu có)
     * @param string $orderBy      Sắp xếp (ORDER BY)
     * @return array               Trả về mảng gồm: data, total, page, per_page, last_page, raw_sql
     * Thuật toán:
     * 1. Tính offset = (page - 1) * perPage
     * 2. Lấy dữ liệu theo trang bằng fetchAll (có limit, offset)
     * 3. Đếm tổng số bản ghi phù hợp (COUNT(*))
     * 4. Tính số trang cuối cùng
     * 5. Trả về dữ liệu, tổng số, trang hiện tại, số trang cuối, raw SQL
     */
    public function paginate($table, $page = 1, $perPage = 10, $where = '', $params = [], $orderBy = '') {
        // Bước 1: Tính offset (vị trí bắt đầu lấy bản ghi)
        $offset = ($page - 1) * $perPage;

        // Bước 2: Lấy dữ liệu trang hiện tại
        $data = $this->fetchAll($table, $where, $params, $orderBy, $perPage, $offset);

        // Bước 3: Tạo câu truy vấn đếm tổng số bản ghi
        $countSql = "SELECT COUNT(*) FROM `$table`";
        if ($where) {
            $countSql .= " WHERE $where";
        }

        // Bước 4: Chuẩn bị và bind các tham số cho truy vấn đếm
        $stmt = $this->pdo->prepare($countSql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(is_int($key) ? $key+1 : ":$key", $value);
        }
        $stmt->execute();
        $total = $stmt->fetchColumn();

        // Sinh câu truy vấn raw cho trang hiện tại (giúp debug/hiển thị)
        $rawSql = "SELECT * FROM `$table`";
        if ($where) {
            $rawSql .= " WHERE $where";
        }
        if ($orderBy) {
            $rawSql .= " ORDER BY $orderBy";
        }
        $rawSql .= " LIMIT $perPage OFFSET $offset";

        // Bước 5: Trả về kết quả cùng raw SQL
        return [
            'data' => $data,
            'total' => (int)$total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => ceil($total / $perPage),
            'raw_sql' => $rawSql
        ];
    }
}
