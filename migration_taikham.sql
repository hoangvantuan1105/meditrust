-- Chạy file này 1 lần trong phpMyAdmin hoặc MySQL terminal
-- Thêm cột lưu ID lịch khám gốc đã tạo ra lịch tái khám này
ALTER TABLE lich_kham
    ADD COLUMN lich_kham_goc_id INT NULL DEFAULT NULL AFTER la_tai_kham;