<?php
$ngayHienTai = date('d/m/Y');
$thoiGian    = date('H:i');
$thuHienTai  = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'][date('w')];
?>

<div class="container-fluid" id="dashboard-main">

    <!-- ===== TIÊU ĐỀ ===== -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 mt-3">
        <div>
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-tooth text-primary mr-2"></i> Tổng Quan Phòng Khám
            </h1>
            <small class="text-muted">
                <i class="fas fa-calendar-alt mr-1"></i><?= $thuHienTai ?>, ngày <?= $ngayHienTai ?>
            </small>
        </div>
        <div class="text-right">
            <div id="live-clock" class="h4 font-weight-bold text-primary mb-0"><?= $thoiGian ?></div>
            <small class="text-muted">Giờ hiện tại</small>
        </div>
    </div>

    <!-- ===== ROW 1: 4 KPI CARDS CHÍNH ===== -->
    <div class="row">

        <!-- Tổng bệnh nhân -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 border-0" style="border-radius:16px; background:linear-gradient(135deg,#4e73df,#224abe)">
                <div class="card-body text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 text-uppercase font-weight-bold" style="font-size:.75rem;letter-spacing:.05rem">Tổng bệnh nhân</div>
                            <div class="h2 font-weight-bold mb-0 counter" data-target="<?= $tongBenhNhan ?>"><?= number_format($tongBenhNhan) ?></div>
                            <small class="text-white-50">Tất cả thời gian</small>
                        </div>
                        <div style="font-size:3rem; opacity:.3">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng lịch khám -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 border-0" style="border-radius:16px; background:linear-gradient(135deg,#1cc88a,#13855c)">
                <div class="card-body text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 text-uppercase font-weight-bold" style="font-size:.75rem;letter-spacing:.05rem">Tổng lịch khám</div>
                            <div class="h2 font-weight-bold mb-0"><?= number_format($tongLichKham) ?></div>
                            <small class="text-white-50">Tất cả thời gian</small>
                        </div>
                        <div style="font-size:3rem; opacity:.3">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng doanh thu -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 border-0" style="border-radius:16px; background:linear-gradient(135deg,#f6c23e,#dda20a)">
                <div class="card-body text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 text-uppercase font-weight-bold" style="font-size:.75rem;letter-spacing:.05rem">Tổng doanh thu</div>
                            <div class="h4 font-weight-bold mb-0"><?= number_format($tongDoanhThu, 0, ',', '.') ?>đ</div>
                            <small class="text-white-50">Tất cả thời gian</small>
                        </div>
                        <div style="font-size:3rem; opacity:.3">
                            <i class="fas fa-sack-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Số bác sĩ -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 border-0" style="border-radius:16px; background:linear-gradient(135deg,#36b9cc,#1a849e)">
                <div class="card-body text-white py-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-white-50 text-uppercase font-weight-bold" style="font-size:.75rem;letter-spacing:.05rem">Đội ngũ bác sĩ</div>
                            <div class="h2 font-weight-bold mb-0"><?= number_format($tongBacSi) ?></div>
                            <small class="text-white-50">Đang hoạt động</small>
                        </div>
                        <div style="font-size:3rem; opacity:.3">
                            <i class="fas fa-user-doctor"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== ROW 2: THỐNG KÊ HÔM NAY + TRẠNG THÁI LỊCH ===== -->
    <div class="row mb-2">

        <!-- Lịch khám hôm nay + breakdown trạng thái -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100" style="border-radius:12px">
                <div class="card-body py-3 px-4">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-2">
                        <i class="fas fa-calendar-day mr-1"></i>Lịch khám hôm nay
                    </div>
                    <div class="h3 font-weight-bold text-gray-800 mb-3"><?= number_format($lichKhamHomNay) ?> lịch</div>
                    <!-- Breakdown trạng thái -->
                    <div class="d-flex justify-content-between">
                        <div class="text-center">
                            <span class="badge badge-warning px-2 py-1" style="font-size:.8rem;border-radius:8px">
                                <?= $trangThaiHomNay['cho_kham'] ?>
                            </span>
                            <div style="font-size:.7rem;color:#6c757d;margin-top:3px">Chờ khám</div>
                        </div>
                        <div class="text-center">
                            <span class="badge badge-info px-2 py-1" style="font-size:.8rem;border-radius:8px">
                                <?= $trangThaiHomNay['dang_kham'] ?>
                            </span>
                            <div style="font-size:.7rem;color:#6c757d;margin-top:3px">Đang khám</div>
                        </div>
                        <div class="text-center">
                            <span class="badge badge-success px-2 py-1" style="font-size:.8rem;border-radius:8px">
                                <?= $trangThaiHomNay['da_kham'] ?>
                            </span>
                            <div style="font-size:.7rem;color:#6c757d;margin-top:3px">Đã khám</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu hôm nay -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2" style="border-radius:12px">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Doanh thu hôm nay</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($doanhThuHomNay, 0, ',', '.') ?>đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-coins fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Doanh thu tháng này -->
        <div class="col-xl-4 col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2" style="border-radius:12px">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Doanh thu tháng <?= date('n') ?></div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($doanhThuThangNay, 0, ',', '.') ?>đ</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== ROW 3: BIỂU ĐỒ BỆNH NHÂN THEO NGÀY + TOP DỊCH VỤ ===== -->
    <div class="row">

        <!-- Bệnh nhân khám theo ngày (7 ngày gần nhất) -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow" style="border-radius:16px">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:16px 16px 0 0; background:#fff">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-clock mr-2"></i>Bệnh nhân khám theo ngày (7 ngày gần nhất)
                    </h6>
                    <span class="badge badge-primary badge-pill">Hôm nay: <?= $lichKhamHomNay ?></span>
                </div>
                <div class="card-body">
                    <div class="chart-bar">
                        <canvas id="dailyPatientChart" style="max-height:280px"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top dịch vụ -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow" style="border-radius:16px">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:16px 16px 0 0; background:#fff">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star mr-2"></i>Top dịch vụ phổ biến
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-2">
                        <canvas id="serviceChart" style="max-height:260px"></canvas>
                    </div>
                    <div class="mt-3 text-center small" id="service-legend"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- ===== ROW 4: DOANH THU THEO THÁNG ===== -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow" style="border-radius:16px">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between" style="border-radius:16px 16px 0 0; background:#fff">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-area mr-2"></i>Doanh thu theo tháng — Năm <?= date('Y') ?>
                    </h6>
                    <span class="badge badge-success badge-pill">
                        Tổng: <?= number_format($tongDoanhThu, 0, ',', '.') ?>đ
                    </span>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueChart" style="max-height:300px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- ===== ROW 5: BẢNG LỊCH HÔM NAY + CẢNH BÁO VẬT TƯ ===== -->
<div class="row">

    <!-- Bảng lịch khám hôm nay -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow" style="border-radius:16px">
            <div class="card-header py-3 d-flex align-items-center justify-content-between" style="border-radius:16px 16px 0 0; background:#fff">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list-ul mr-2"></i>Lịch khám hôm nay
                    <span class="badge badge-primary ml-1"><?= count($lichHomNayChiTiet) ?></span>
                </h6>
                <a href="admin.php?admin=listLichKham" class="btn btn-sm btn-outline-primary" style="border-radius:8px">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (empty($lichHomNayChiTiet)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-calendar-times fa-3x mb-3" style="opacity:.3"></i>
                        <p>Hôm nay chưa có lịch khám nào</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" style="font-size:.88rem">
                            <thead style="background:#f8f9fc">
                                <tr>
                                    <th class="border-0 px-3 py-2">#</th>
                                    <th class="border-0 py-2">Bệnh nhân</th>
                                    <th class="border-0 py-2">SĐT</th>
                                    <th class="border-0 py-2">Dịch vụ</th>
                                    <th class="border-0 py-2">Bác sĩ</th>
                                    <th class="border-0 py-2">Giờ</th>
                                    <th class="border-0 py-2">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lichHomNayChiTiet as $i => $lk): ?>
                                    <tr>
                                        <td class="px-3 text-muted"><?= $i + 1 ?></td>
                                        <td class="font-weight-bold"><?= htmlspecialchars($lk['ten_benh_nhan']) ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($lk['so_dien_thoai'] ?? '—') ?></td>
                                        <td><?= htmlspecialchars($lk['ten_dich_vu'] ?? '—') ?></td>
                                        <td><?= htmlspecialchars($lk['ten_bac_si'] ?? '—') ?></td>
                                        <td class="text-nowrap">
                                            <?= $lk['gio_bat_dau'] ? substr($lk['gio_bat_dau'], 0, 5) : '—' ?>
                                            <?php if ($lk['gio_ket_thuc']): ?>
                                                <span class="text-muted">→ <?= substr($lk['gio_ket_thuc'], 0, 5) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($lk['trang_thai'] === 'cho_kham'): ?>
                                                <span class="badge badge-warning">Chờ khám</span>
                                            <?php elseif ($lk['trang_thai'] === 'dang_kham'): ?>
                                                <span class="badge badge-info">Đang khám</span>
                                            <?php elseif ($lk['trang_thai'] === 'da_kham'): ?>
                                                <span class="badge badge-success">Đã khám</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary"><?= htmlspecialchars($lk['trang_thai']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Cảnh báo vật tư / thuốc -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow" style="border-radius:16px">
            <div class="card-header py-3 d-flex align-items-center justify-content-between" style="border-radius:16px 16px 0 0; background:#fff">
                <h6 class="m-0 font-weight-bold text-danger">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Cảnh báo vật tư
                    <?php if (!empty($vatTuCanhBao)): ?>
                        <span class="badge badge-danger ml-1"><?= count($vatTuCanhBao) ?></span>
                    <?php endif; ?>
                </h6>
                <a href="admin.php?admin=materials" class="btn btn-sm btn-outline-danger" style="border-radius:8px">
                    Quản lý <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-body p-0" style="max-height:320px; overflow-y:auto">
                <?php if (empty($vatTuCanhBao)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success" style="opacity:.6"></i>
                        <p>Tất cả vật tư đều ổn</p>
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($vatTuCanhBao as $alert): ?>
                            <li class="list-group-item border-left-<?= $alert['type'] === 'danger' ? 'danger' : 'warning' ?> py-2 px-3"
                                style="border-left-width:4px!important; border-left-style:solid!important;
                                       border-left-color:<?= $alert['type'] === 'danger' ? '#e74a3b' : '#f6c23e' ?>!important">
                                <div class="d-flex align-items-start">
                                    <i class="fas <?= htmlspecialchars($alert['icon']) ?> mt-1 mr-2
                                       text-<?= $alert['type'] === 'danger' ? 'danger' : 'warning' ?>"></i>
                                    <span style="font-size:.85rem"><?= htmlspecialchars($alert['msg']) ?></span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js + Plugin datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
    // ===== Dữ liệu PHP -> JS =====
    const revenueData = <?= json_encode($revenueData ?? array_fill(0, 12, 0)) ?>;
    const serviceLabels = <?= json_encode($serviceLabels ?? []) ?>;
    const serviceData = <?= json_encode($serviceData ?? []) ?>;
    const dailyLabels = <?= json_encode($ngayLabels ?? []) ?>;
    const dailyData = <?= json_encode($ngayData ?? array_fill(0, 7, 0)) ?>;

    const COLORS = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1', '#fd7e14'];

    // ===== Đồng hồ =====
    function updateClock() {
        const now = new Date();
        const hh = String(now.getHours()).padStart(2, '0');
        const mm = String(now.getMinutes()).padStart(2, '0');
        const ss = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('live-clock');
        if (el) el.textContent = `${hh}:${mm}:${ss}`;
    }
    setInterval(updateClock, 1000);

    // ===== Biểu đồ bệnh nhân theo ngày (Bar) =====
    new Chart(document.getElementById('dailyPatientChart'), {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Số lượt khám',
                data: dailyData,
                backgroundColor: dailyData.map((_, i) =>
                    i === dailyData.length - 1 ? '#4e73df' : 'rgba(78,115,223,.45)'
                ),
                borderColor: dailyData.map((_, i) =>
                    i === dailyData.length - 1 ? '#224abe' : 'rgba(78,115,223,.8)'
                ),
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    color: '#4e73df',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: v => v > 0 ? v : ''
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} lượt khám`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0,0,0,.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // ===== Biểu đồ Top dịch vụ (Doughnut) =====
    const hasSvcData = serviceData.length > 0 && serviceData.some(v => v > 0);
    const svcChart = new Chart(document.getElementById('serviceChart'), {
        type: 'doughnut',
        data: {
            labels: hasSvcData ? serviceLabels : ['Chưa có dữ liệu'],
            datasets: [{
                data: hasSvcData ? serviceData : [1],
                backgroundColor: COLORS.slice(0, hasSvcData ? serviceLabels.length : 1),
                hoverOffset: 8,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    color: '#fff',
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: (value, ctx) => {
                        if (!hasSvcData) return '';
                        const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        return total ? (value / total * 100).toFixed(1) + '%' : '';
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Legend thủ công cho doughnut
    if (hasSvcData) {
        const legendEl = document.getElementById('service-legend');
        serviceLabels.forEach((lbl, i) => {
            legendEl.innerHTML +=
                `<span class="mr-2">
                    <i class="fas fa-circle" style="color:${COLORS[i]}"></i> ${lbl}
                </span>`;
        });
    }

    // ===== Biểu đồ Doanh thu theo tháng (Line) =====
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: revenueData.length ? revenueData : Array(12).fill(0),
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28,200,138,.1)',
                pointBackgroundColor: '#1cc88a',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: .4,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: false
                },
                datalabels: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + Number(ctx.parsed.y).toLocaleString('vi-VN') + 'đ'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,.05)'
                    },
                    ticks: {
                        callback: v => {
                            if (v >= 1e9) return (v / 1e9).toFixed(1) + 'B';
                            if (v >= 1e6) return (v / 1e6).toFixed(1) + 'M';
                            if (v >= 1e3) return (v / 1e3).toFixed(0) + 'K';
                            return v;
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>