<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <strong>Chọn vật phẩm sử dụng</strong>
    <button type="button" class="btn btn-primary btn-sm" onclick="confirmSelection()">
      <i class="fas fa-check"></i> Xác nhận chọn vật tư
    </button>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Chọn</th>
          <th>Tên vật phẩm</th>
          <th>Đơn vị</th>
          <th>Giá</th>
          <th>Số lượng</th>
          <th>Thành tiền</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vatTuList as $vt): ?>
          <tr>
            <td>
              <input type="checkbox" class="vt-check" data-gia="<?= $vt['gia_nhap'] ?>">
              <input type="hidden" name="vattu_id[]" value="<?= $vt['id'] ?>">
            </td>
            <td><?= $vt['ten_vat_tu'] ?></td>
            <td><?= $vt['don_vi'] ?></td>
            <td><?= number_format($vt['gia_nhap']) ?> đ</td>
            <td>
              <input type="number" name="so_luong[]" class="form-control so-luong" min="1" disabled>
            </td>
            <td class="thanh-tien">0 đ</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="text-end">
      <strong>Tổng chi phí vật phẩm: </strong>
      <span id="tongVatTu">0 đ</span>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.vt-check').forEach((checkbox) => {
    const row = checkbox.closest('tr');
    const soLuongInput = row.querySelector('.so-luong');

    checkbox.addEventListener('change', function() {
      if (this.checked) {
        soLuongInput.disabled = false;
        if (!soLuongInput.value) soLuongInput.value = 1;
        tinhDong(row);
      } else {
        soLuongInput.disabled = true;
        soLuongInput.value = '';
        row.querySelector('.thanh-tien').innerText = '0 đ';
        tinhTong();
      }
    });

    soLuongInput.addEventListener('input', function() {
      tinhDong(row);
    });
  });

  function tinhDong(row) {
    const gia = row.querySelector('.vt-check').dataset.gia;
    const soLuong = row.querySelector('.so-luong').value;
    const thanhTien = gia * soLuong;
    row.querySelector('.thanh-tien').innerText = thanhTien.toLocaleString() + ' đ';
    tinhTong();
  }

  function tinhTong() {
    let tong = 0;
    document.querySelectorAll('.thanh-tien').forEach(cell => {
      tong += parseInt(cell.innerText.replace(/\D/g, '')) || 0;
    });
    const el = document.getElementById('tongVatTu');
    if (el) el.innerText = tong.toLocaleString() + ' đ';
  }

  function confirmSelection() {
    const selected = [];
    document.querySelectorAll('.vt-check:checked').forEach(checkbox => {
      const row = checkbox.closest('tr');
      const id = row.querySelector('input[name="vattu_id[]"]').value;
      const name = row.cells[1].innerText.trim();
      const unit = row.cells[2].innerText.trim();
      const price = checkbox.dataset.gia;
      const qty = row.querySelector('.so-luong').value;

      selected.push({
        id: id,
        ten_vat_tu: name,
        don_vi: unit,
        gia_nhap: price,
        so_luong: qty
      });
    });

    if (selected.length === 0) {
      alert("Vui lòng chọn ít nhất một vật tư!");
      return;
    }

    if (window.opener && !window.opener.closed && typeof window.opener.receiveMaterials === 'function') {
      window.opener.receiveMaterials(selected);
      window.close();
    } else {
      alert("Không tìm thấy cửa sổ cha để gửi dữ liệu! Hãy đảm bảo bạn mở trang này từ nút 'Thêm vật tư'.");
    }
  }
</script>