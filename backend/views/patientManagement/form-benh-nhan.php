<?php
$lichHen = $lichHen ?? null; // dữ liệu lịch hẹn controller truyền sang
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $title ?? 'Thêm bệnh nhân'; ?></title>

    <!-- Custom fonts for this template -->
    <link rel="stylesheet" href="backend/assets/css/admin-main.css">
    <link rel="stylesheet" href="backend/assets/css/admin-main2.css">
    <link href="backend/assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- jsQR library for QR code decoding -->
    <script defer src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <!-- HTML5 QR Code Scanner from CDN -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js"></script>
    <style>
        #qr_reader {
            width: 100%;
            max-width: 420px;
            min-height: 320px;
            border: 2px dashed #ccc;
        }


        #qr_reader_controls {
            margin: 10px 0;
        }

        .qr_section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>


<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <?php echo isset($item) ? 'Sửa thông tin bệnh nhân' : 'Thêm bệnh nhân mới'; ?>
        </h1>
    </div>

    <?php if (isset($msg)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $msg; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <?php echo isset($item) ? 'Chỉnh sửa thông tin' : 'Nhập thông tin bệnh nhân'; ?>
            </h6>
        </div>
        <div class="card-body">
            <?php
            $isEdit = isset($item) && !empty($item['id']);
            $formAction = $isEdit
                ? 'admin.php?admin=edit&id=' . urlencode((string) $item['id'])
                : 'admin.php?admin=add';
            ?>
            <?php $errors = $errors ?? []; ?>
            <form method="POST" action="<?= htmlspecialchars($formAction) ?>">

                <?php if (!empty($_GET['from']) && $_GET['from'] === 'tiep_nhan'): ?>
                    <input type="hidden" name="from" value="tiep_nhan">
                    <input type="hidden" name="lich_hen_id" value="<?= $_GET['lich_hen_id'] ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ho_ten">Họ Tên <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten"
                            value="<?= htmlspecialchars($item['ho_ten'] ?? $lichHen['ho_ten'] ?? '') ?>" required>


                    </div>
                    <div class="form-group col-md-6">
                        <label for="so_dien_thoai">Số Điện Thoại <span class="text-danger">*</span></label>
                        <input type="tel"
                            class="form-control <?= isset($errors['so_dien_thoai']) ? 'is-invalid' : '' ?>"
                            id="so_dien_thoai"
                            name="so_dien_thoai"
                            value="<?= htmlspecialchars($item['so_dien_thoai'] ?? $lichHen['so_dien_thoai'] ?? '') ?>"
                            required>

                        <?php if (isset($errors['so_dien_thoai'])): ?>
                            <div class="invalid-feedback d-block">
                                <?= $errors['so_dien_thoai'] ?>
                            </div>
                        <?php endif; ?>


                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email"
                            class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                            id="email"
                            name="email"
                            value="<?= htmlspecialchars($item['email'] ?? $lichHen['email'] ?? '') ?>"
                            required>

                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback d-block">
                                <?= $errors['email'] ?>
                            </div>
                        <?php endif; ?>


                    </div>
                    <div class="form-group col-md-6">
                        <label for="gioi_tinh">Giới Tính</label>
                        <select class="form-control" id="gioi_tinh" name="gioi_tinh">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="Nam" <?php echo (isset($item) && $item['gioi_tinh'] == 'Nam') ? 'selected' : ''; ?>>Nam</option>
                            <option value="Nữ" <?php echo (isset($item) && $item['gioi_tinh'] == 'Nữ') ? 'selected' : ''; ?>>Nữ</option>
                            <option value="Khác" <?php echo (isset($item) && $item['gioi_tinh'] == 'Khác') ? 'selected' : ''; ?>>Khác</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="ngay_sinh">Ngày Sinh</label>
                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh"
                            value="<?php echo isset($item) ? htmlspecialchars($item['ngay_sinh'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="dia_chi">Địa Chỉ</label>
                        <input type="text" class="form-control" id="dia_chi" name="dia_chi"
                            value="<?php echo isset($item) ? htmlspecialchars($item['dia_chi'] ?? '') : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="tien_su_benh">Tiền Sử Bệnh</label>
                    <textarea class="form-control" id="tien_su_benh" name="tien_su_benh"
                        rows="4"><?php echo isset($item) ? htmlspecialchars($item['tien_su_benh'] ?? '') : ''; ?></textarea>
                </div>

                <!-- QR Scanner Section -->
                <div class="qr_section">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fas fa-qrcode"></i> Quét QR Code từ CMND/CCCD
                    </h6>

                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="camera-tab" data-toggle="tab" href="#camera-panel"
                                role="tab">
                                <i class="fas fa-camera"></i> Quét Camera
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="upload-tab" data-toggle="tab" href="#upload-panel" role="tab">
                                <i class="fas fa-image"></i> Upload Ảnh
                            </a>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Camera Panel -->
                        <div class="tab-pane fade show active" id="camera-panel" role="tabpanel">
                            <div id="qr_reader_controls" class="mb-3">
                                <button type="button" class="btn btn-info" id="start_camera_btn">
                                    <i class="fas fa-camera"></i> Bật Camera
                                </button>
                                <button type="button" class="btn btn-warning" id="stop_camera_btn"
                                    style="display: none;">
                                    <i class="fas fa-times"></i> Tắt Camera
                                </button>
                                <button type="button" class="btn btn-secondary" id="clear_qr_btn">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                            <video id="qr_reader" autoplay playsinline></video>

                            <div id="qr_result" class="alert alert-info" style="display: none; margin-top: 10px;">
                                <strong>Kết quả quét:</strong>
                                <pre id="qr_result_text"></pre>
                            </div>
                        </div>

                        <!-- Upload Panel -->
                        <div class="tab-pane fade" id="upload-panel" role="tabpanel">
                            <div class="form-group">
                                <label for="cccd_image_upload">Chọn ảnh CMND/CCCD</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="cccd_image_upload"
                                        accept="image/*">
                                    <label class="custom-file-label" for="cccd_image_upload">Chọn ảnh...</label>
                                </div>
                                <small class="form-text text-muted">
                                    Hỗ trợ: JPG, PNG. Kích thước tối đa 5MB
                                </small>
                            </div>
                            <div id="upload_preview" style="display: none; margin-top: 15px;">
                                <p><strong>Xem trước:</strong></p>
                                <img id="preview_img" src="" alt="Preview"
                                    style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; padding: 5px;">
                            </div>
                            <div id="upload_result" class="alert alert-info" style="display: none; margin-top: 10px;">
                                <strong>Kết quả xử lý:</strong>
                                <pre id="upload_result_text"></pre>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="cmnd_cccd">CMND/CCCD</label>
                        <input type="text"
                            class="form-control <?= isset($errors['cmnd_cccd']) ? 'is-invalid' : '' ?>"
                            id="cmnd_cccd"
                            name="cmnd_cccd"
                            value="<?= htmlspecialchars($item['cmnd_cccd'] ?? '') ?>">

                        <?php if (isset($errors['cmnd_cccd'])): ?>
                            <div class="invalid-feedback d-block">
                                <?= $errors['cmnd_cccd'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="bao_hiem_y_te">Bảo Hiểm Y Tế</label>
                        <input type="text"
                            class="form-control <?= isset($errors['bao_hiem_y_te']) ? 'is-invalid' : '' ?>"
                            id="bao_hiem_y_te"
                            name="bao_hiem_y_te"
                            value="<?= htmlspecialchars($item['bao_hiem_y_te'] ?? '') ?>">

                        <?php if (isset($errors['bao_hiem_y_te'])): ?>
                            <div class="invalid-feedback d-block">
                                <?= $errors['bao_hiem_y_te'] ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="nguoi_lien_he_khan_cap">Người Liên Hệ Khẩn Cấp</label>
                        <input type="text" class="form-control" id="nguoi_lien_he_khan_cap"
                            name="nguoi_lien_he_khan_cap"
                            value="<?php echo isset($item) ? htmlspecialchars($item['nguoi_lien_he_khan_cap'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="quan_he">Quan Hệ</label>
                        <input type="text" class="form-control" id="quan_he" name="quan_he"
                            value="<?php echo isset($item) ? htmlspecialchars($item['quan_he'] ?? '') : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="sdt_nguoi_lien_he">Số Điện Thoại Người Liên Hệ</label>
                        <input type="tel" class="form-control" id="sdt_nguoi_lien_he" name="sdt_nguoi_lien_he"
                            value="<?php echo isset($item) ? htmlspecialchars($item['sdt_nguoi_lien_he'] ?? '') : ''; ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="trang_thai">Trạng Thái</label>
                        <select class="form-control" id="trang_thai" name="trang_thai">
                            <option value="1" <?php echo (isset($item) && $item['trang_thai'] == 1) ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="0" <?php echo (isset($item) && $item['trang_thai'] == 0) ? 'selected' : ''; ?>>Không hoạt động</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> <?php echo isset($item) ? 'Cập nhật' : 'Thêm mới'; ?>
                    </button>
                    <a href="admin.php?admin=dsbenhnhan" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- Footer -->

<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Bootstrap core JavaScript -->
<script src="backend/assets/vendor/jquery/jquery.min.js"></script>
<script src="backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript -->
<script src="backend/assets/vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages -->
<script src="backend/assets/js/sb-admin-2.min.js"></script>
<script src="https://unpkg.com/@zxing/library@latest"></script>


<script>
    let html5QrcodeScanner = null;
    let qrScannerActive = false;

    // Đợi thư viện Html5Qrcode được load
    function waitForHtml5Qrcode(callback, attempts = 0) {
        // Kiểm tra Html5Qrcode có trong window object không
        if (typeof window !== 'undefined' && window.Html5Qrcode) {
            console.log('Html5Qrcode library loaded successfully');
            callback();
        } else if (attempts < 50) {
            console.log('Waiting for Html5Qrcode... attempt ' + (attempts + 1));
            setTimeout(() => waitForHtml5Qrcode(callback, attempts + 1), 100);
        } else {
            console.error('Html5Qrcode library failed to load after 50 attempts');
            alert('Lỗi: Thư viện QR code không được tải sau 5 giây.\nVui lòng kiểm tra console (F12) để xem chi tiết lỗi.');
        }
    }

    // Khởi tạo event listener khi DOM sẵn sàng
    function initQRScanner() {
        const startBtn = document.getElementById('start_camera_btn');
        const stopBtn = document.getElementById('stop_camera_btn');
        const clearBtn = document.getElementById('clear_qr_btn');

        if (startBtn) {
            startBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Start camera button clicked');
                waitForHtml5Qrcode(() => startQRScanner());
            });
        }

        if (stopBtn) {
            stopBtn.addEventListener('click', function(e) {
                e.preventDefault();
                stopQRScanner();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                clearQRResult();
            });
        }
    }

    // Gọi initQRScanner khi DOM được load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initQRScanner);
        document.addEventListener('DOMContentLoaded', initImageUploadHandler);
    } else {
        initQRScanner();
        initImageUploadHandler();
    }

    // Xử lý upload ảnh CCCD
    function initImageUploadHandler() {
        const uploadInput = document.getElementById('cccd_image_upload');
        if (!uploadInput) return;

        uploadInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Kiểm tra loại file
            if (!file.type.startsWith('image/')) {
                alert('Vui lòng chọn tệp hình ảnh');
                return;
            }

            // Kiểm tra kích thước (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Kích thước ảnh không được vượt quá 5MB');
                return;
            }

            // Cập nhật label
            const label = document.querySelector('label[for="cccd_image_upload"]');
            if (label) {
                label.textContent = file.name;
            }

            // Hiển thị xem trước
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('upload_preview');
                const previewImg = document.getElementById('preview_img');
                previewImg.src = event.target.result;
                preview.style.display = 'block';

                // Xử lý ảnh bằng jsQR
                processUploadedImage(event.target.result);
            };
            reader.readAsDataURL(file);
        });
    }

    // Xử lý ảnh upload
    async function processUploadedImage(imageSrc) {
        const img = new Image();
        img.onload = async function() {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0);

            // 1️⃣ Thử BarcodeDetector trước (PDF417)
            if ('BarcodeDetector' in window) {
                try {
                    console.log('Trying BarcodeDetector...');

                    const detector = new BarcodeDetector({
                        formats: ['qr_code', 'pdf417', 'data_matrix', 'code_128']
                    });

                    const barcodes = await detector.detect(canvas);

                    if (barcodes.length > 0) {
                        console.log('✓ Barcode found:', barcodes[0].rawValue);
                        displayUploadResult('✓ Tìm thấy mã:\n' + barcodes[0].rawValue);
                        parseAndFillCCCDData(barcodes[0].rawValue);
                        return;
                    }
                } catch (e) {
                    console.log('BarcodeDetector error:', e);
                }
            }

            // 2️⃣ Fallback jsQR (QR thường)
            if (window.jsQR) {
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

                let code = window.jsQR(imageData.data, canvas.width, canvas.height, {
                    inversionAttempts: "attemptBoth"
                });

                if (code) {
                    console.log('✓ QR found:', code.data);
                    displayUploadResult('✓ Tìm thấy QR:\n' + code.data);
                    parseAndFillCCCDData(code.data);
                    return;
                }
            }

            displayUploadResult('❌ Không tìm thấy QR / PDF417 trong ảnh.');
        };

        img.src = imageSrc;
    }


    function displayUploadResult(message) {
        const resultDiv = document.getElementById('upload_result');
        const resultText = document.getElementById('upload_result_text');
        resultText.textContent = message;
        resultDiv.style.display = 'block';
    }

    function startQRScanner() {
        if (qrScannerActive) {
            console.log('Scanner already running');
            return;
        }

        qrScannerActive = true;

        console.log('Starting QR Scanner with ZXing...');

        document.getElementById('start_camera_btn').style.display = 'none';
        document.getElementById('stop_camera_btn').style.display = 'inline-block';

        try {
            zxingReader = new ZXing.BrowserMultiFormatReader();

            const constraints = {
                video: {
                    facingMode: {
                        ideal: "environment"
                    }
                }
            };

            zxingReader.decodeFromConstraints(
                constraints,
                'qr_reader',
                (result, err) => {
                    if (result) {
                        console.log('✓ ZXing detected:', result.text);

                        displayQRResult(result.text);
                        parseAndFillCCCDData(result.text);

                        stopQRScanner();
                    }
                }
            );

        } catch (e) {
            console.error('ZXing init error:', e);
            stopQRScanner();
        }
    }


    function stopQRScanner() {
        if (zxingReader) {
            zxingReader.reset();
            zxingReader = null;
        }

        qrScannerActive = false;

        document.getElementById('start_camera_btn').style.display = 'inline-block';
        document.getElementById('stop_camera_btn').style.display = 'none';
    }

    function onQRCodeScanned(decodedText, decodedResult) {
        console.log('onQRCodeScanned called with:', decodedText, decodedResult);

        // Nếu decodedText là một object, lấy text property
        const qrText = typeof decodedText === 'string' ? decodedText : (decodedText && decodedText.text ? decodedText.text : '');

        if (!qrText) {
            console.warn('No QR text found in result');
            return;
        }

        console.log('QR Code detected and decoded:', qrText);
        try {
            displayQRResult(qrText);
            parseAndFillCCCDData(qrText);
            stopQRScanner();
        } catch (error) {
            console.error('Error parsing QR:', error);
        }
    }

    // Hàm quét Barcode sử dụng Barcode Detection API (hỗ trợ Datamatrix, PDF417, v.v.)
    async function scanWithBarcodeDetectionAPI(videoElement) {
        if (typeof BarcodeDetector === 'undefined') {
            console.log('❌ BarcodeDetector not supported on this browser');
            return null;
        }

        try {
            const canvas = document.createElement('canvas');
            canvas.width = videoElement.videoWidth;
            canvas.height = videoElement.videoHeight;

            const ctx = canvas.getContext('2d');
            ctx.drawImage(videoElement, 0, 0);

            console.log('Trying BarcodeDetector API...');

            const detector = new BarcodeDetector({
                formats: ['qr_code', 'data_matrix', 'code_128', 'code_39', 'pdf417']
            });

            const barcodes = await detector.detect(canvas);
            if (barcodes && barcodes.length > 0) {
                console.log('✓✓✓ Barcode detected:', barcodes[0].format, '-', barcodes[0].rawValue);
                return barcodes[0].rawValue;
            }
            console.log('No barcode detected in this frame (BarcodeDetector)');
            return null;
        } catch (err) {
            console.error('BarcodeDetector error:', err.message);
            return null;
        }
    }

    // Hàm quét QR code sử dụng jsQR khi html5-qrcode không hoạt động
    let lastScanTime = 0;

    function scanQRWithJsQR(videoElement) {
        if (!window.jsQR) {
            return null;
        }

        if (!videoElement || videoElement.videoWidth === 0 || videoElement.videoHeight === 0) {
            return null;
        }

        try {
            const vw = videoElement.videoWidth;
            const vh = videoElement.videoHeight;

            const canvas = document.createElement('canvas');
            canvas.width = vw;
            canvas.height = vh;

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                return null;
            }

            ctx.drawImage(videoElement, 0, 0);

            const imageData = ctx.getImageData(0, 0, vw, vh);

            // Kiểm tra xem video có thực sự được render không
            // Lấy sample pixels từ các vị trí khác nhau
            let colorVariance = 0;
            const samples = [];

            // Sample 10 pixels từ các vị trí khác nhau
            const positions = [
                0, Math.floor(vw * 4 / 2), (vw - 1) * 4,
                Math.floor(vw * vh / 4) * 4, Math.floor(vw * vh / 2) * 4,
                (vw * vh - 1) * 4
            ];

            for (let pos of positions) {
                if (pos >= 0 && pos < imageData.data.length - 3) {
                    const r = imageData.data[pos];
                    const g = imageData.data[pos + 1];
                    const b = imageData.data[pos + 2];
                    samples.push(r + g + b);
                }
            }

            // Tính variance
            if (samples.length > 0) {
                const avg = samples.reduce((a, b) => a + b, 0) / samples.length;
                colorVariance = samples.reduce((a, b) => a + Math.abs(b - avg), 0) / samples.length;
            }

            console.log('Color variance:', colorVariance.toFixed(2));

            // Nếu variance quá thấp, video stream chưa ready
            if (colorVariance < 20) {
                console.log('Video stream initializing, waiting...');
                return null;
            }

            // Gọi jsQR dengan preprocessing untuk meningkatkan kontras
            let code = null;
            let scanAttempted = false;

            try {
                scanAttempted = true;
                console.log('Attempting jsQR decode, image:', vw, 'x', vh);

                // Cố gắng jsQR trên image gốc
                code = window.jsQR(imageData.data, vw, vh);

                if (!code) {
                    console.log('First attempt: no QR found, trying with inversion...');
                    code = window.jsQR(imageData.data, vw, vh, {
                        inversionAttempts: "attemptBoth"
                    });
                }

                // Nếu vẫn không tìm thấy, thử tăng kontras
                if (!code) {
                    console.log('No QR with standard attempts, trying with contrast enhancement...');
                    const enhancedData = enhanceImageContrast(imageData.data);
                    const enhancedImageData = new ImageData(enhancedData, vw, vh);
                    code = window.jsQR(enhancedImageData.data, vw, vh, {
                        inversionAttempts: "attemptBoth"
                    });
                }
            } catch (e) {
                console.error('jsQR error:', e.message);
            }

            if (code) {
                console.log('✓✓✓ QR CODE FOUND:', code.data);
                return code.data;
            }

            if (scanAttempted) {
                console.log('No QR code detected in this frame');
            }

            return null;
        } catch (err) {
            console.error('scanQRWithJsQR error:', err.message);
            return null;
        }
    }

    // Hàm tăng cường độ tương phản của hình ảnh
    function enhanceImageContrast(imageData) {
        const enhanced = new Uint8ClampedArray(imageData.length);

        // Tìm min/max giá trị pixel
        let min = 255,
            max = 0;
        for (let i = 0; i < imageData.length; i += 4) {
            const gray = imageData[i] * 0.299 + imageData[i + 1] * 0.587 + imageData[i + 2] * 0.114;
            if (gray < min) min = gray;
            if (gray > max) max = gray;
        }

        const range = max - min || 1;

        // Tăng cường độ tương phản
        for (let i = 0; i < imageData.length; i += 4) {
            const r = imageData[i];
            const g = imageData[i + 1];
            const b = imageData[i + 2];
            const a = imageData[i + 3];

            const gray = r * 0.299 + g * 0.587 + b * 0.114;
            const enhanced_value = Math.round(((gray - min) / range) * 255);

            enhanced[i] = enhanced_value;
            enhanced[i + 1] = enhanced_value;
            enhanced[i + 2] = enhanced_value;
            enhanced[i + 3] = a;
        }

        return enhanced;
    }

    // Hàm quét liên tục từ camera sử dụng BarcodeDetector hoặc jsQR
    async function startManualQRScanning() {
        if (!qrScannerActive) {
            console.log('QR scanning stopped (qrScannerActive = false)');
            return;
        }

        // Tìm video element
        let videoElement = null;
        const qrReader = document.getElementById('qr_reader');

        if (qrReader) {
            videoElement = qrReader.querySelector('video');
        }

        if (!videoElement) {
            videoElement = document.querySelector('video');
        }

        if (!videoElement) {
            if (qrScannerActive) {
                setTimeout(startManualQRScanning, 300);
            }
            return;
        }

        // Thử BarcodeDetector API trước (hỗ trợ Datamatrix từ CCCD)
        console.log('Checking BarcodeDetector support...');
        if (typeof BarcodeDetector !== 'undefined') {
            console.log('✓ BarcodeDetector is supported, scanning...');
            const barcodeResult = await scanWithBarcodeDetectionAPI(videoElement);
            if (barcodeResult) {
                console.log('✓ Barcode scan succeeded:', barcodeResult);
                showAlert('✓ Quét thành công! ' + barcodeResult, 'success');
                onQRCodeScanned(barcodeResult);
                return;
            }
        } else {
            console.log('❌ BarcodeDetector not supported, will try jsQR only');
        }

        // Fallback: Thử jsQR (QR code tiêu chuẩn)
        const qrResult = scanQRWithJsQR(videoElement);
        if (qrResult) {
            console.log('✓ Manual QR scan succeeded:', qrResult);
            showAlert('✓ Quét QR thành công! ' + qrResult, 'success');
            onQRCodeScanned(qrResult);
            return; // Dừng quét
        } else {
            // Tiếp tục quét mỗi 300ms (tăng từ 200ms vì BarcodeDetector async)
            if (qrScannerActive) {
                // Hiển thị thông báo "đang quét" mỗi 2 giây
                if (!window.__scanningNotified || Date.now() - window.__scanningNotified > 2000) {
                    console.log('🔍 Đang quét QR / Barcode...');
                    window.__scanningNotified = Date.now();
                }
                setTimeout(startManualQRScanning, 300);
            }
        }
    }

    function onQRCodeError(errorMessage) {
        console.log('QR scan error from html5-qrcode:', errorMessage);

        if (qrScannerActive && !window.__jsQRScanningStarted) {
            window.__jsQRScanningStarted = true;
            console.log('Starting manual QR scanning with jsQR in 5 seconds...');
            showAlert('🔍 Đang chuẩn bị quét QR code...', 'info');
            setTimeout(startManualQRScanning, 5000); // Chờ 5 giây để video stream hoàn toàn ổn định
        }
    }

    function displayQRResult(text) {
        document.getElementById('qr_result').style.display = 'block';
        document.getElementById('qr_result_text').textContent = text;
    }

    function clearQRResult() {
        document.getElementById('qr_result').style.display = 'none';
        document.getElementById('qr_result_text').textContent = '';
        if (qrScannerActive) {
            stopQRScanner();
        }
    }

    function parseAndFillCCCDData(qrData) {
        // Phân tích dữ liệu QR code từ CCCD
        // Định dạng: CCCD||Họ tên|Ngày sinh|Giới tính|Địa chỉ|Ngày cấp
        // Ví dụ: 020206004910||Vũ Văn Dũng|05112006|Nam|Thôn Sẩy Hạ...|16052024

        try {
            console.log('Raw QR data:', qrData);

            // Thử phân tách bằng |
            let parts = qrData.split('|');

            console.log('Parsed parts:', parts);
            console.log('Number of parts:', parts.length);

            if (parts.length > 0) {
                // Phần 0: Số CCCD/CMND (loại bỏ các ký tự đặc biệt)
                const cccdNumber = parts[0].trim().replace(/[^0-9]/g, '');
                if (cccdNumber && cccdNumber.length >= 9) {
                    document.getElementById('cmnd_cccd').value = cccdNumber;
                    console.log('✓ Filled CMND/CCCD (parts[0]):', cccdNumber);
                }

                // Phần 2: Họ và tên (parts[1] thường trống, họ tên ở parts[2])
                let hoTen = '';
                if (parts.length > 2 && parts[2].trim()) {
                    hoTen = parts[2].trim();
                } else if (parts.length > 1 && parts[1].trim()) {
                    hoTen = parts[1].trim();
                }

                if (hoTen && hoTen.length > 2) {
                    document.getElementById('ho_ten').value = hoTen;
                    console.log('✓ Filled Họ tên (parts[2]):', hoTen);
                } else {
                    console.warn('⚠ Họ tên không tìm thấy');
                }

                // Phần 3: Ngày sinh (DDMMYYYY format ở parts[3])
                if (parts.length > 3 && parts[3].trim()) {
                    const dob = formatDateForInput(parts[3].trim());
                    if (dob) {
                        document.getElementById('ngay_sinh').value = dob;
                        console.log('✓ Filled Ngày sinh (parts[3]):', dob);
                    }
                }

                // Phần 4: Giới tính (ở parts[4])
                if (parts.length > 4 && parts[4].trim()) {
                    const genderStr = parts[4].trim().toUpperCase();
                    let gender = '';

                    if (genderStr.includes('NAM') || genderStr === 'M' || genderStr === 'MALE') {
                        gender = 'Nam';
                    } else if (genderStr.includes('NỮ') || genderStr === 'F' || genderStr === 'FEMALE') {
                        gender = 'Nữ';
                    } else if (genderStr === '0') {
                        gender = 'Nam';
                    } else if (genderStr === '1') {
                        gender = 'Nữ';
                    }

                    if (gender) {
                        document.getElementById('gioi_tinh').value = gender;
                        console.log('✓ Filled Giới tính (parts[4]):', gender);
                    }
                }

                // Phần 5+: Địa chỉ (ở parts[5])
                if (parts.length > 5 && parts[5].trim()) {
                    const diaChi = parts[5].trim();
                    document.getElementById('dia_chi').value = diaChi;
                    console.log('✓ Filled Địa chỉ (parts[5]):', diaChi);
                }

                showAlert('✓ Quét QR thành công!\n\nĐã điền thông tin:\n- Số CMND/CCCD\n- Họ tên\n- Ngày sinh\n- Giới tính\n- Địa chỉ\n\nVui lòng kiểm tra và điền đầy đủ thông tin còn lại.', 'success');

                console.log('✓✓✓ CCCD data filled successfully');
            }
        } catch (error) {
            console.error('Error parsing CCCD data:', error);
            showAlert('⚠ Không thể phân tích dữ liệu QR.\nVui lòng kiểm tra Console để chi tiết.\nHãy điền thông tin thủ công hoặc thử lại.', 'warning');
        }
    }

    function formatDateForInput(dateStr) {
        // Chuyển đổi định dạng ngày từ các format khác nhau
        // Hỗ trợ: DD/MM/YYYY, DD-MM-YYYY, DDMMYYYY
        try {
            let day, month, year;

            if (dateStr.includes('/')) {
                [day, month, year] = dateStr.split('/');
            } else if (dateStr.includes('-')) {
                [day, month, year] = dateStr.split('-');
            } else if (dateStr.length === 8) {
                day = dateStr.substring(0, 2);
                month = dateStr.substring(2, 4);
                year = dateStr.substring(4, 8);
            }

            if (day && month && year) {
                day = String(day).padStart(2, '0');
                month = String(month).padStart(2, '0');
                year = String(year).padStart(4, '0');
                return year + '-' + month + '-' + day; // YYYY-MM-DD format
            }
        } catch (e) {
            console.error('Date format error:', e);
        }
        return null;
    }

    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
                ${message}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;

        const form = document.querySelector('form');
        form.parentElement.insertBefore(alertDiv, form);

        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
</script>

</body>

</html>