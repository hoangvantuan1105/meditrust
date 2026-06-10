<?php
require_once __DIR__ . '/backend/models/db.php';
$db = new modelClinic();
$db->ketNoiDB();
$conn = $db->getConnection();

function columnExists(PDO $conn, string $table, string $column): bool
{
    $stmt = $conn->prepare("SHOW COLUMNS FROM `$table` LIKE ?");
    $stmt->execute([$column]);
    return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
}

$queries = [
    "ALTER TABLE hoa_don MODIFY bac_si_id INT NULL",
    "ALTER TABLE chi_tiet_hoa_don MODIFY loai_item ENUM('dich_vu','thuoc','san_pham','vat_tu') NOT NULL",
    "UPDATE chi_tiet_hoa_don SET loai_item = 'san_pham' WHERE loai_item = 'vat_tu'",
    "ALTER TABLE chi_tiet_hoa_don MODIFY loai_item ENUM('dich_vu','thuoc','san_pham') NOT NULL",
];

$columns = [
    [
        'table' => 'vat_tu',
        'column' => 'type',
        'sql' => "ALTER TABLE vat_tu ADD COLUMN type TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: vat tu, 1: san pham' AFTER trang_thai_han",
    ],
    [
        'table' => 'vat_tu',
        'column' => 'trang_thai_su_dung',
        'sql' => "ALTER TABLE vat_tu ADD COLUMN trang_thai_su_dung TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: dang ban, 0: khoa' AFTER type",
    ],
    [
        'table' => 'chi_tiet_hoa_don',
        'column' => 'lieu_luong',
        'sql' => "ALTER TABLE chi_tiet_hoa_don ADD COLUMN lieu_luong VARCHAR(255) NULL AFTER thanh_tien",
    ],
    [
        'table' => 'chi_tiet_hoa_don',
        'column' => 'cach_uong',
        'sql' => "ALTER TABLE chi_tiet_hoa_don ADD COLUMN cach_uong VARCHAR(255) NULL AFTER lieu_luong",
    ],
];

foreach ($columns as $column) {
    if (!columnExists($conn, $column['table'], $column['column'])) {
        $queries[] = $column['sql'];
    } else {
        echo "SKIP: {$column['table']}.{$column['column']} da ton tai\n";
    }
}

foreach ($queries as $sql) {
    try {
        $conn->exec($sql);
        echo "OK: $sql\n";
    } catch (Exception $e) {
        echo "ERR: " . $e->getMessage() . " -- $sql\n";
    }
}
echo "Done.\n";
