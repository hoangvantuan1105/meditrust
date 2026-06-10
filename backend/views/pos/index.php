<div class="container-fluid pos-screen">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-desktop"></i> Bán Hàng Tại Quầy (POS)</h1>
        <button class="btn btn-sm btn-primary shadow-sm" id="btnOpenProductManager">
            <i class="fas fa-box-open"></i> Quản lý sản phẩm
        </button>
    </div>

    <div class="row pos-layout">
        <div class="col-lg-7 pos-column">
            <div class="card shadow mb-4 pos-card">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Danh mục sản phẩm & thuốc</h6>
                    <div class="input-group" style="width: 270px;">
                        <input type="text" id="posSearchItem" class="form-control form-control-sm" placeholder="Tìm sản phẩm hoặc thuốc...">
                        <div class="input-group-append">
                            <button class="btn btn-primary btn-sm" type="button"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>

                <div class="card-body pos-list-body">
                    <div class="mb-3">
                        <button class="btn btn-sm btn-outline-primary active filter-btn" data-filter="all">Tất cả</button>
                        <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="product">Sản phẩm</button>
                        <button class="btn btn-sm btn-outline-primary filter-btn" data-filter="medicine">Thuốc</button>
                    </div>
                    <div class="row" id="posItemList"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-5 pos-column">
            <div class="card shadow mb-4 pos-card">
                <div class="card-header py-2 bg-gradient-primary text-white position-relative">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="m-0 font-weight-bold"><i class="fas fa-user"></i> Khách hàng</span>
                        <button class="btn btn-sm btn-light text-primary" data-toggle="modal" data-target="#addPatientModal">
                            <i class="fas fa-plus"></i> Mới
                        </button>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" id="posSearchPatient" class="form-control" placeholder="Tìm SĐT hoặc tên bệnh nhân...">
                    </div>
                    <div id="patientDropdown" class="dropdown-menu w-100"></div>
                    <div id="selectedPatientInfo" class="mt-2" style="display:none;">
                        <span class="badge badge-light text-dark p-2 w-100 text-left" style="font-size: 14px;">
                            <i class="fas fa-check-circle text-success"></i>
                            <span id="lblPatientName"></span> - <span id="lblPatientPhone"></span>
                            <a href="#" id="removePatientBtn" class="float-right text-danger"><i class="fas fa-times"></i></a>
                        </span>
                        <input type="hidden" id="posPatientId" value="">
                    </div>
                </div>

                <div class="card-body p-0 d-flex flex-column pos-cart-body">
                    <div class="cart-table-wrap p-2">
                        <table class="table table-sm table-hover" id="cartTable">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 30%;">Tên</th>
                                    <th style="width: 14%;">SL</th>
                                    <th style="width: 34%;">Liều / cách dùng</th>
                                    <th style="width: 16%;">Giá</th>
                                    <th style="width: 6%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="text-center text-muted py-3">Giỏ hàng trống</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-light p-3 border-top">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Tổng tiền:</span>
                            <span class="font-weight-bold" id="cartTotal">0 đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 align-items-center">
                            <span class="text-secondary">Giảm giá:</span>
                            <input type="number" id="cartDiscount" class="form-control form-control-sm text-right w-50" value="0" min="0">
                        </div>
                        <div class="d-flex justify-content-between mb-3 align-items-center">
                            <span class="text-primary font-weight-bold" style="font-size: 1.2rem;">Cần thanh toán:</span>
                            <span class="text-danger font-weight-bold" style="font-size: 1.5rem;" id="cartFinalTotal">0 đ</span>
                        </div>

                        <div class="form-group">
                            <label class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Phương thức TT</label>
                            <select id="paymentMethod" class="form-control form-control-sm">
                                <option value="cash">Tiền mặt</option>
                                <option value="transfer">Chuyển khoản</option>
                                <option value="card">Quẹt thẻ</option>
                            </select>
                        </div>

                        <button class="btn btn-success btn-lg btn-block shadow-sm" id="btnCheckout" disabled>
                            <i class="fas fa-check-circle"></i> THANH TOÁN & IN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm bệnh nhân mới</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Họ và tên *</label>
                    <input type="text" class="form-control" id="newPatientName">
                </div>
                <div class="form-group">
                    <label>Số điện thoại *</label>
                    <input type="text" class="form-control" id="newPatientPhone">
                </div>
                <div id="addPatientAlert" class="alert alert-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="btnSaveQuickPatient">Lưu thông tin</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productManageModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quản lý sản phẩm POS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                        <form id="posProductForm">
                            <input type="hidden" id="productId">
                            <div class="form-group">
                                <label>Tên sản phẩm *</label>
                                <input type="text" class="form-control form-control-sm" id="productName" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Đơn vị *</label>
                                    <input type="text" class="form-control form-control-sm" id="productUnit" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Số lượng *</label>
                                    <input type="number" class="form-control form-control-sm" id="productQty" min="0" value="0" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Giá bán *</label>
                                <input type="number" class="form-control form-control-sm" id="productPrice" min="0" value="0" required>
                            </div>
                            <div class="form-group">
                                <label>Nhà phân phối *</label>
                                <input type="text" class="form-control form-control-sm" id="productManufacturer" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Danh mục</label>
                                    <select class="form-control form-control-sm" id="productCategory">
                                        <option value="tieu hao">Tiêu hao</option>
                                        <option value="tai su dung">Tái sử dụng</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Hạn sử dụng *</label>
                                    <input type="date" class="form-control form-control-sm" id="productExpiry" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Trạng thái POS</label>
                                <select class="form-control form-control-sm" id="productSaleStatus">
                                    <option value="1">Đang bán</option>
                                    <option value="0">Khóa</option>
                                </select>
                            </div>
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary btn-sm mr-2">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnResetProductForm">Làm mới</button>
                            </div>
                            <div id="productFormAlert" class="alert mt-3 py-2" style="display:none;"></div>
                        </form>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tên</th>
                                        <th>Tồn</th>
                                        <th>Giá</th>
                                        <th>HSD</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="productManageBody">
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Đang tải...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.pos-screen {
    height: calc(100vh - 100px);
    display: flex;
    flex-direction: column;
}
.pos-layout {
    flex: 1;
    overflow: hidden;
}
.pos-column {
    height: 100%;
}
.pos-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}
.pos-list-body {
    overflow-y: auto;
    background-color: #f8f9fc;
}
.pos-cart-body {
    overflow: hidden;
}
.cart-table-wrap {
    flex: 1;
    overflow-y: auto;
}
.pos-item-card {
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s;
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    background: #fff;
    padding: 10px;
    height: 100%;
    min-height: 108px;
    position: relative;
}
.pos-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15)!important;
    border-color: #4e73df;
}
.pos-item-card.disabled {
    opacity: .55;
    cursor: not-allowed;
}
.item-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 0.7rem;
}
#patientDropdown {
    display: none;
    position: absolute;
    z-index: 1000;
    margin-top: 2px;
    left: 0;
    right: 0;
}
.medicine-note-input {
    min-width: 130px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let allItems = [];
    let cart = [];
    let productRows = [];
    let debounceTimer;

    const itemList = document.getElementById('posItemList');
    const cartBody = document.querySelector('#cartTable tbody');
    const checkoutBtn = document.getElementById('btnCheckout');
    const patientDropdown = document.getElementById('patientDropdown');
    const searchPatientInput = document.getElementById('posSearchPatient');

    loadItems();

    function loadItems() {
        fetch('admin.php?admin=apiGetItems')
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    allItems = res.data;
                    applyItemFilters();
                }
            });
    }

    function renderItems(items) {
        itemList.innerHTML = '';

        if (!items.length) {
            itemList.innerHTML = '<div class="col-12 text-center text-muted py-5">Không có mặt hàng phù hợp</div>';
            return;
        }

        items.forEach(item => {
            const col = document.createElement('div');
            col.className = 'col-md-4 col-sm-6 mb-3';

            const isMedicine = item.type === 'medicine';
            const typeBadge = isMedicine
                ? '<span class="badge badge-success item-badge">Thuốc</span>'
                : '<span class="badge badge-info item-badge">Sản phẩm</span>';
            const disabled = item.stock <= 0 ? ' disabled' : '';

            col.innerHTML = `
                <div class="pos-item-card${disabled}" data-id="${item.id}" data-type="${item.type}">
                    ${typeBadge}
                    <div class="font-weight-bold text-dark text-truncate pr-5" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
                    <div class="text-primary font-weight-bold mt-2">${formatCurrency(item.price)}</div>
                    <small class="text-muted d-block">Tồn: ${item.stock}${item.unit ? ' ' + escapeHtml(item.unit) : ''}</small>
                    ${item.stock <= 0 ? '<small class="text-danger d-block">Hết hàng</small>' : ''}
                </div>
            `;
            col.querySelector('.pos-item-card').addEventListener('click', () => addToCart(item));
            itemList.appendChild(col);
        });
    }

    function applyItemFilters() {
        const activeFilter = document.querySelector('.filter-btn.active').dataset.filter;
        const searchText = document.getElementById('posSearchItem').value.trim().toLowerCase();

        let filtered = allItems;
        if (activeFilter !== 'all') {
            filtered = filtered.filter(i => i.type === activeFilter);
        }
        if (searchText) {
            filtered = filtered.filter(i => i.name.toLowerCase().includes(searchText));
        }

        renderItems(filtered);
    }

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            applyItemFilters();
        });
    });

    document.getElementById('posSearchItem').addEventListener('input', applyItemFilters);

    function addToCart(item) {
        if (item.stock <= 0) {
            alert('Mặt hàng này đã hết hàng!');
            return;
        }

        const existing = cart.find(i => i.id === item.id && i.type === item.type);
        if (existing) {
            if (existing.quantity >= item.stock) {
                alert('Không đủ tồn kho!');
                return;
            }
            existing.quantity++;
        } else {
            cart.push({
                ...item,
                quantity: 1,
                dosage: '',
                usage: ''
            });
        }
        renderCart();
    }

    function renderCart() {
        cartBody.innerHTML = '';

        if (cart.length === 0) {
            cartBody.innerHTML = '<tr id="emptyCartRow"><td colspan="5" class="text-center text-muted py-3">Giỏ hàng trống</td></tr>';
            checkoutBtn.disabled = true;
            calculateTotal();
            return;
        }

        checkoutBtn.disabled = false;
        cart.forEach((item, index) => {
            const tr = document.createElement('tr');
            const typeLabel = item.type === 'medicine'
                ? '<span class="badge badge-success">Thuốc</span>'
                : '<span class="badge badge-info">SP</span>';
            const medicineFields = item.type === 'medicine'
                ? `<input type="text" class="form-control form-control-sm mb-1 medicine-dosage medicine-note-input" data-index="${index}" value="${escapeAttr(item.dosage)}" placeholder="Liều lượng">
                   <input type="text" class="form-control form-control-sm medicine-usage medicine-note-input" data-index="${index}" value="${escapeAttr(item.usage)}" placeholder="Cách uống">`
                : '<span class="text-muted small">-</span>';

            tr.innerHTML = `
                <td class="align-middle">
                    <div class="text-truncate" style="max-width: 145px;" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
                    ${typeLabel}
                </td>
                <td class="align-middle">
                    <input type="number" class="form-control form-control-sm text-center cart-qty" value="${item.quantity}" min="1" data-index="${index}">
                </td>
                <td class="align-middle">${medicineFields}</td>
                <td class="align-middle">${formatCurrency(item.price)}</td>
                <td class="align-middle text-right">
                    <button class="btn btn-sm btn-danger btn-remove" data-index="${index}"><i class="fas fa-trash"></i></button>
                </td>
            `;
            cartBody.appendChild(tr);
        });

        document.querySelectorAll('.cart-qty').forEach(input => {
            input.addEventListener('change', function() {
                const idx = Number(this.dataset.index);
                let value = parseInt(this.value, 10);
                if (isNaN(value) || value < 1) value = 1;
                if (value > cart[idx].stock) {
                    alert('Không đủ tồn kho!');
                    value = cart[idx].stock;
                }
                this.value = value;
                cart[idx].quantity = value;
                calculateTotal();
            });
        });

        document.querySelectorAll('.medicine-dosage').forEach(input => {
            input.addEventListener('input', function() {
                cart[Number(this.dataset.index)].dosage = this.value;
            });
        });

        document.querySelectorAll('.medicine-usage').forEach(input => {
            input.addEventListener('input', function() {
                cart[Number(this.dataset.index)].usage = this.value;
            });
        });

        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function() {
                cart.splice(Number(this.dataset.index), 1);
                renderCart();
            });
        });

        calculateTotal();
    }

    function calculateTotal() {
        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = Math.max(0, parseFloat(document.getElementById('cartDiscount').value) || 0);
        const finalTotal = Math.max(0, total - discount);

        document.getElementById('cartTotal').innerText = formatCurrency(total);
        document.getElementById('cartFinalTotal').innerText = formatCurrency(finalTotal);
    }

    document.getElementById('cartDiscount').addEventListener('input', calculateTotal);

    searchPatientInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        if (q.length < 2) {
            patientDropdown.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch('admin.php?admin=apiSearchPatient&q=' + encodeURIComponent(q))
                .then(res => res.json())
                .then(res => {
                    patientDropdown.innerHTML = '';
                    if (res.status === 'success' && res.data.length > 0) {
                        res.data.forEach(p => {
                            const item = document.createElement('div');
                            item.className = 'dropdown-item';
                            item.style.cursor = 'pointer';
                            item.innerHTML = `<strong>${escapeHtml(p.ho_ten)}</strong> - ${escapeHtml(p.so_dien_thoai)}`;
                            item.addEventListener('mousedown', e => {
                                e.preventDefault();
                                selectPatient(p.id, p.ho_ten, p.so_dien_thoai);
                            });
                            patientDropdown.appendChild(item);
                        });
                    } else {
                        patientDropdown.innerHTML = '<span class="dropdown-item text-muted">Không tìm thấy</span>';
                    }
                    patientDropdown.style.display = 'block';
                });
        }, 350);
    });

    document.addEventListener('click', function(e) {
        if (e.target !== searchPatientInput) {
            patientDropdown.style.display = 'none';
        }
    });

    function selectPatient(id, name, phone) {
        document.getElementById('posPatientId').value = id;
        document.getElementById('lblPatientName').innerText = name;
        document.getElementById('lblPatientPhone').innerText = phone;
        searchPatientInput.value = '';
        searchPatientInput.style.display = 'none';
        document.getElementById('selectedPatientInfo').style.display = 'block';
    }

    document.getElementById('removePatientBtn').addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('posPatientId').value = '';
        document.getElementById('selectedPatientInfo').style.display = 'none';
        searchPatientInput.style.display = 'block';
        searchPatientInput.focus();
    });

    document.getElementById('btnSaveQuickPatient').addEventListener('click', function() {
        const name = document.getElementById('newPatientName').value.trim();
        const phone = document.getElementById('newPatientPhone').value.trim();
        const alertBox = document.getElementById('addPatientAlert');

        if (!name || !phone) {
            alertBox.innerText = 'Vui lòng điền đủ thông tin';
            alertBox.style.display = 'block';
            return;
        }

        fetch('admin.php?admin=apiAddPatientQuick', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ho_ten: name, so_dien_thoai: phone})
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                $('#addPatientModal').modal('hide');
                selectPatient(res.data.id, res.data.ho_ten, res.data.so_dien_thoai);
                document.getElementById('newPatientName').value = '';
                document.getElementById('newPatientPhone').value = '';
                alertBox.style.display = 'none';
            } else {
                alertBox.innerText = res.message;
                alertBox.style.display = 'block';
            }
        });
    });

    checkoutBtn.addEventListener('click', function() {
        if (cart.length === 0) return;

        const patientId = document.getElementById('posPatientId').value;
        if (!patientId) {
            alert('Vui lòng chọn khách hàng trước khi thanh toán!');
            return;
        }

        const missingMedicine = cart.find(item => item.type === 'medicine' && (!item.dosage.trim() || !item.usage.trim()));
        if (missingMedicine) {
            alert('Vui lòng nhập liều lượng và cách uống cho thuốc: ' + missingMedicine.name);
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';

        const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const discount = Math.max(0, parseFloat(document.getElementById('cartDiscount').value) || 0);

        fetch('admin.php?admin=apiProcessCheckout', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                patient_id: patientId,
                cart: cart,
                discount: discount,
                total: total,
                final_total: Math.max(0, total - discount),
                payment_method: document.getElementById('paymentMethod').value
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                const invoiceIds = Object.values(res.invoice_ids || {});
                invoiceIds.forEach((id, index) => {
                    setTimeout(() => {
                        window.open('admin.php?admin=invoicePrint&id=' + id, 'in_hoa_don_' + id, 'width=820,height=650');
                    }, index * 250);
                });

                cart = [];
                document.getElementById('cartDiscount').value = '0';
                renderCart();
                loadItems();
            } else {
                alert('Lỗi: ' + res.message);
            }
        })
        .catch(err => alert('Lỗi: ' + err))
        .finally(() => {
            btn.disabled = cart.length === 0;
            btn.innerHTML = '<i class="fas fa-check-circle"></i> THANH TOÁN & IN';
        });
    });

    document.getElementById('btnOpenProductManager').addEventListener('click', function() {
        resetProductForm();
        loadProductsForManagement();
        $('#productManageModal').modal('show');
    });

    document.getElementById('btnResetProductForm').addEventListener('click', resetProductForm);

    document.getElementById('posProductForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const payload = {
            id: document.getElementById('productId').value,
            ten_vat_tu: document.getElementById('productName').value.trim(),
            don_vi: document.getElementById('productUnit').value.trim(),
            so_luong: document.getElementById('productQty').value,
            gia_nhap: document.getElementById('productPrice').value,
            hang_san_xuat: document.getElementById('productManufacturer').value.trim(),
            danh_muc: document.getElementById('productCategory').value,
            han_su_dung: document.getElementById('productExpiry').value,
            trang_thai_su_dung: document.getElementById('productSaleStatus').value
        };

        if (!payload.ten_vat_tu || !payload.don_vi || !payload.hang_san_xuat || !payload.han_su_dung) {
            showProductAlert('danger', 'Vui lòng nhập đầy đủ thông tin sản phẩm.');
            return;
        }

        fetch('admin.php?admin=apiSaveProduct', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            showProductAlert(res.status === 'success' ? 'success' : 'danger', res.message);
            if (res.status === 'success') {
                resetProductForm();
                loadProductsForManagement();
                loadItems();
            }
        });
    });

    function loadProductsForManagement() {
        fetch('admin.php?admin=apiGetProducts')
            .then(res => res.json())
            .then(res => {
                productRows = res.status === 'success' ? res.data : [];
                renderProductManagement();
            });
    }

    function renderProductManagement() {
        const body = document.getElementById('productManageBody');
        body.innerHTML = '';

        if (!productRows.length) {
            body.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Chưa có sản phẩm</td></tr>';
            return;
        }

        productRows.forEach(product => {
            const active = Number(product.trang_thai_su_dung ?? 1) === 1;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${escapeHtml(product.ten_vat_tu)}</td>
                <td>${Number(product.so_luong || 0)} ${escapeHtml(product.don_vi || '')}</td>
                <td>${formatCurrency(product.gia_nhap || 0)}</td>
                <td>${escapeHtml((product.han_su_dung || '').substring(0, 10))}</td>
                <td>${active ? '<span class="badge badge-success">Đang bán</span>' : '<span class="badge badge-dark">Đã khóa</span>'}</td>
                <td>
                    <button class="btn btn-success btn-sm btn-edit-product" data-id="${product.id}" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn ${active ? 'btn-secondary' : 'btn-info'} btn-sm btn-toggle-product" data-id="${product.id}" data-status="${active ? 0 : 1}" title="${active ? 'Khóa' : 'Mở bán'}">
                        <i class="fas ${active ? 'fa-lock' : 'fa-unlock'}"></i>
                    </button>
                </td>
            `;
            body.appendChild(tr);
        });

        document.querySelectorAll('.btn-edit-product').forEach(btn => {
            btn.addEventListener('click', () => fillProductForm(Number(btn.dataset.id)));
        });

        document.querySelectorAll('.btn-toggle-product').forEach(btn => {
            btn.addEventListener('click', () => toggleProduct(Number(btn.dataset.id), Number(btn.dataset.status)));
        });
    }

    function fillProductForm(id) {
        const product = productRows.find(p => Number(p.id) === id);
        if (!product) return;

        document.getElementById('productId').value = product.id;
        document.getElementById('productName').value = product.ten_vat_tu || '';
        document.getElementById('productUnit').value = product.don_vi || '';
        document.getElementById('productQty').value = product.so_luong || 0;
        document.getElementById('productPrice').value = product.gia_nhap || 0;
        document.getElementById('productManufacturer').value = product.hang_san_xuat || '';
        document.getElementById('productCategory').value = product.danh_muc || 'tieu hao';
        document.getElementById('productExpiry').value = (product.han_su_dung || '').substring(0, 10);
        document.getElementById('productSaleStatus').value = product.trang_thai_su_dung ?? 1;
    }

    function toggleProduct(id, status) {
        if (status === 0 && !confirm('Bạn có chắc muốn khóa sản phẩm này khỏi POS?')) {
            return;
        }

        fetch('admin.php?admin=apiToggleProduct', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({id: id, status: status})
        })
        .then(res => res.json())
        .then(res => {
            showProductAlert(res.status === 'success' ? 'success' : 'danger', res.message);
            if (res.status === 'success') {
                loadProductsForManagement();
                loadItems();
            }
        });
    }

    function resetProductForm() {
        document.getElementById('productId').value = '';
        document.getElementById('productName').value = '';
        document.getElementById('productUnit').value = '';
        document.getElementById('productQty').value = 0;
        document.getElementById('productPrice').value = 0;
        document.getElementById('productManufacturer').value = '';
        document.getElementById('productCategory').value = 'tieu hao';
        document.getElementById('productExpiry').value = '';
        document.getElementById('productSaleStatus').value = 1;
    }

    function showProductAlert(type, message) {
        const alertBox = document.getElementById('productFormAlert');
        alertBox.className = 'alert mt-3 py-2 alert-' + type;
        alertBox.innerText = message;
        alertBox.style.display = 'block';
        setTimeout(() => alertBox.style.display = 'none', 2500);
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(Number(amount || 0));
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function escapeAttr(value) {
        return escapeHtml(value).replace(/`/g, '&#096;');
    }
});
</script>
