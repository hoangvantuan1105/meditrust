<div class="container-fluid">

    <h1 class="h3 mb-3 text-gray-800">Xuất vật tư</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Biểu mẫu xuất vật tư</h6>
        </div>

        <div class="card-body">
            <form id="exportForm" method="POST" action="admin.php?admin=exportMaterial">

                <div id="rows">

                    <div class="form-row align-items-end mb-2 export-row">
                        <div class="form-group col-md-6">
                            <label>Vật tư *</label>
                            <select name="vat_tu_id[]" class="form-control vat_tu_id" required>
                                <option value="">-- Chọn vật tư --</option>
                                <?php foreach ($materials as $m): ?>
                                    <option value="<?= $m['id'] ?>" data-stock="<?= $m['so_luong'] ?>">
                                        <?= $m['ten_vat_tu'] ?> (Tồn: <?= $m['so_luong'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-4">
                            <label>Số lượng *</label>
                            <input type="number" name="so_luong[]" class="form-control so_luong" required>
                        </div>

                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                        </div>
                    </div>

                </div>

                <button type="button" id="addRow" class="btn btn-success btn-sm mb-3">
                    + Thêm vật tư
                </button>

                <!-- BÁC SĨ -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Bác sĩ bàn giao *</label>
                        <select name="bac_si_id" class="form-control" required>
                            <option value="">-- Chọn bác sĩ --</option>
                            <?php foreach ($doctors as $d): ?>
                                <option value="<?= $d['id'] ?>">
                                    <?= $d['ten_bac_si'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- LÝ DO -->
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Lý do</label>
                        <input type="text" name="ly_do" class="form-control" placeholder="Nhập lý do xuất kho">
                    </div>
                </div>

                <hr>

                <button class="btn btn-danger">
                    <i class="fas fa-share"></i> Xuất kho
                </button>

                <a href="admin.php?admin=materials" class="btn btn-info">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>

            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // CONFIRM
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Xác nhận xuất?',
                text: 'Số lượng sẽ bị trừ trong kho',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xuất',
                cancelButtonText: 'Huỷ'
            }).then((r) => {
                if (r.isConfirmed) this.submit();
            });
        });

        // ADD ROW
        document.getElementById('addRow').onclick = function() {
            document.getElementById('rows').insertAdjacentHTML('beforeend', `
        <div class="form-row align-items-end mb-2 export-row">
            <div class="form-group col-md-6">
                <select name="vat_tu_id[]" class="form-control vat_tu_id" required>
                    <option value="">-- Chọn vật tư --</option>
                    <?php foreach ($materials as $m): ?>
                        <option value="<?= $m['id'] ?>" data-stock="<?= $m['so_luong'] ?>">
                            <?= $m['ten_vat_tu'] ?> (Tồn: <?= $m['so_luong'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <input type="number" name="so_luong[]" class="form-control so_luong" required>
            </div>

            <div class="form-group col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
            </div>
        </div>
        `);
        };

        // REMOVE ROW
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('.export-row').remove();
            }
        });

        // CHECK TỒN
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('so_luong')) {
                let row = e.target.closest('.export-row');
                let select = row.querySelector('.vat_tu_id');
                if (!select.value) return;

                let stock = select.selectedOptions[0].dataset.stock;

                if (parseInt(e.target.value) > parseInt(stock)) {
                    Swal.fire('Vượt tồn', 'Tối đa: ' + stock, 'error');
                    e.target.value = stock;
                }
            }
        });

    });
</script>
<?php if (!empty($_SESSION['error'])): ?>
    <script>
        Swal.fire('Lỗi', '<?= $_SESSION['error'] ?>', 'error');
    </script>
<?php unset($_SESSION['error']);
endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <script>
        Swal.fire('Thành công', '<?= $_SESSION['success'] ?>', 'success');
    </script>
<?php unset($_SESSION['success']);
endif; ?>