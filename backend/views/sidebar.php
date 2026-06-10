<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="admin.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tổng Quan</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Danh Mục Quản Lý
    </div>

    <!-- Bán Hàng Tại Quầy POS -->
    <li class="nav-item">
        <a class="nav-link font-weight-bold" href="admin.php?admin=pos" style="color: #fff; background-color: rgba(255,255,255,0.1); border-radius: 5px; margin: 0 10px;">
            <i class="fas fa-fw fa-desktop" style="color: #fff;"></i>
            <span>Bán Hàng Tại Quầy (POS)</span>
        </a>
    </li>
    <!-- Quản lý lịch khám và bác sĩ -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAppointments"
            aria-expanded="true" aria-controls="collapseAppointments">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Lịch Khám</span>
        </a>
        <div id="collapseAppointments" class="collapse" aria-labelledby="headingAppointments"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="admin.php?admin=listYcLichHen">Yêu Cầu Lịch Hẹn</a>
                <a class="collapse-item" href="admin.php?admin=listLichHen">Lịch Hẹn</a>
                <a class="collapse-item" href="admin.php?admin=listLichKham">Lịch Khám</a>
            </div>
        </div>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="index.php?page=tables" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Người Dùng</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                <a class="collapse-item" href="admin.php?page=tables">Quản Lý Lễ Tân</a>
                <a class="collapse-item" href="admin.php?admin=qlybacsi">Quản Lý Bác Sĩ</a>
            </div>
        </div>
    </li>
    <!-- Quản lý Vật Tư -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseInventory"
            aria-expanded="true" aria-controls="collapseInventory">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Vật Tư</span>
        </a>
        <div id="collapseInventory" class="collapse" aria-labelledby="headingInventory" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="admin.php?admin=materials">Danh Sách Vật Tư</a>
                <a class="collapse-item" href="admin.php?admin=historyExportMaterial">Lịch Sử Xuất Vật Tư</a>

                <a class="collapse-item" href="admin.php?admin=listMedicine">Danh Sách Thuốc</a>
                <!-- <a class="collapse-item" href="utilities-animation.html">Báo Cáo Số Lượng Bệnh Nhân</a> -->
                <!-- <a class="collapse-item" href="utilities-other.html">Other</a> -->
            </div>
        </div>
    </li>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Bệnh Nhân</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="admin.php?admin=patient-accounts">Tài Khoản Bệnh Nhân</a>
                <a class="collapse-item" href="admin.php?admin=dsbenhnhan">Hồ Sơ Bệnh Nhân</a>
                <a class="collapse-item" href="admin.php?admin=listLichSuKham">Lịch Sử Khám</a>
                <!-- <a class="collapse-item" href="utilities-other.html">Other</a> -->
            </div>
        </div>
    </li>

    <!-- Quản lý dịch vụ -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices"
            aria-expanded="true" aria-controls="collapseServices">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Dịch Vụ</span>
        </a>
        <div id="collapseServices" class="collapse" aria-labelledby="headingServices" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="admin.php?admin=qlydichvu">Danh Sách Dịch Vụ</a>
                <!-- <a class="collapse-item" href="admin.php?admin=treatmentPackageIndex">Danh Sách Gói Điều Trị</a> -->
                <!-- <a class="collapse-item" href="utilities-animation.html">Báo Cáo Số Lượng Bệnh Nhân</a> -->
                <!-- <a class="collapse-item" href="utilities-other.html">Other</a> -->
            </div>
        </div>
    </li>
    <!-- Quản lý hóa đơn -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBilling"
            aria-expanded="true" aria-controls="collapseBilling">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Quản Lý Thanh Toán</span>
        </a>
        <div id="collapseBilling" class="collapse" aria-labelledby="headingBilling" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <!-- <h6 class="collapse-header">Custom Utilities:</h6> -->
                <a class="collapse-item" href="admin.php?admin=getAllOrder">Danh Sách Hóa Đơn</a>
                <a class="collapse-item" href="admin.php?admin=listDiscount">Quản Lý Mã Giảm Giá</a>
                <a class="collapse-item" href="admin.php?admin=listKetQuaKham">Tạo Hóa Đơn</a>
                <!-- <a class="collapse-item" href="utilities-other.html">Other</a> -->
            </div>
        </div>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->



    <!-- Divider -->


    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

    <!-- Sidebar Message -->


</ul>
<!-- End Sidebar -->