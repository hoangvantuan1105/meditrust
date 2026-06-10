<?php
ini_set('session.cookie_path', '/');
session_start();
require_once "./backend/views/auth/auth.php";
require_once "router.php";
require_once "config/ai.php";
$router = new Router;

// ===== Dashboard =====
$router->addRouterAdmin("index",      "DashboardController", "index");
$router->addRouterAdmin("tables",     "DashboardController", "tables");
$router->addRouterAdmin("dashBoard",  "DashboardController", "dashBoard");
$router->addRouterAdmin("profile",    "DashboardController", "profile");
$router->addRouterAdmin("updateAvatar", "DashboardController", "updateAvatar");

// ===== Auth =====
$router->addRouterAdmin("loginSystem",   "AuthController", "loginSystem");
$router->addRouterAdmin("logout",        "AuthController", "logout");
$router->addRouterAdmin("addAccount",    "AuthController", "addAccount");
$router->addRouterAdmin("formRoleAccess","AuthController", "formRoleAccess");
$router->addRouterAdmin("changePassword","AuthController", "changePassword");
$router->addRouterAdmin("accessDenied",  "AuthController", "accessDenied");
$router->addRouterAdmin("adminLogs",     "AuthController", "adminLogs");

// ===== Supplies (Vật tư) =====
$router->addRouterAdmin("materials",            "SuppliesController", "materials");
$router->addRouterAdmin("addFormMaterials",     "SuppliesController", "addFormMaterials");
$router->addRouterAdmin("addMaterials",         "SuppliesController", "addMaterials");
$router->addRouterAdmin("editMaterials",        "SuppliesController", "editMaterials");
$router->addRouterAdmin("toggleMaterialProductStatus", "SuppliesController", "toggleMaterialProductStatus");
$router->addRouterAdmin("deleteMaterials",      "SuppliesController", "deleteMaterials");
$router->addRouterAdmin("updateFormMaterials",  "SuppliesController", "updateFormMaterials");
$router->addRouterAdmin("searchMaterials",      "SuppliesController", "searchMaterials");
$router->addRouterAdmin("exportMaterial",       "SuppliesController", "exportMaterial");
$router->addRouterAdmin("historyExportMaterial","SuppliesController", "historyExportMaterial");
$router->addRouterAdmin("exportExcel",          "SuppliesController", "exportExcel");
$router->addRouterAdmin("aiChatMaterials",      "SuppliesController", "aiChatMaterials");

// ===== Medicine (Thuốc & Đơn thuốc) =====
$router->addRouterAdmin("listMedicine",        "MedicineController", "listMedicine");
$router->addRouterAdmin("formAddMedicine",     "MedicineController", "formAddMedicine");
$router->addRouterAdmin("addMedicine",         "MedicineController", "addMedicine");
$router->addRouterAdmin("formEditMedicine",    "MedicineController", "formEditMedicine");
$router->addRouterAdmin("editMedicine",        "MedicineController", "editMedicine");
$router->addRouterAdmin("deleteMedicine",      "MedicineController", "deleteMedicine");
$router->addRouterAdmin("detailMedicine",      "MedicineController", "detailMedicine");
$router->addRouterAdmin("listDispenseMedicine","MedicineController", "listDispenseMedicine");
$router->addRouterAdmin("formDispenseMedicine","MedicineController", "formDispenseMedicine");
$router->addRouterAdmin("dispenseMedicine",    "MedicineController", "dispenseMedicine");
$router->addRouterAdmin("formPrescription",    "MedicineController", "formPrescription");
$router->addRouterAdmin("listPrescription",    "MedicineController", "listPrescription");
$router->addRouterAdmin("prescription",        "MedicineController", "prescription");
$router->addRouterAdmin("detailPrescription",  "MedicineController", "detailPrescription");

// ===== Order (Hóa đơn & Voucher) =====
$router->addRouterAdmin("formCreateOrder",    "OrderController", "formCreateOrder");
$router->addRouterAdmin("createInvoice",      "OrderController", "createInvoice");
$router->addRouterAdmin("getAllOrder",         "OrderController", "getAllOrder");
$router->addRouterAdmin("updateHoaDonStatus", "OrderController", "updateHoaDonStatus");
$router->addRouterAdmin("checkVoucher",       "OrderController", "checkVoucher");
$router->addRouterAdmin("saveOrder",          "OrderController", "saveOrder");
$router->addRouterAdmin("listDiscount",       "OrderController", "listDiscount");
$router->addRouterAdmin("createDiscount",     "OrderController", "createDiscount");
$router->addRouterAdmin("storeDiscount",      "OrderController", "storeDiscount");
$router->addRouterAdmin("editDiscount",       "OrderController", "editDiscount");
$router->addRouterAdmin("updateDiscount",     "OrderController", "updateDiscount");
$router->addRouterAdmin("deleteDiscount",     "OrderController", "deleteDiscount");
$router->addRouterAdmin("listKetQuaKham",     "OrderController", "listKetQuaKham");
$router->addRouterAdmin("exportInvoice",      "OrderController", "exportInvoice");

// ===== Patient (Bệnh nhân) =====
$router->addRouterAdmin("dsbenhnhan",             "PatientController", "dsbenhnhan");
$router->addRouterAdmin("form",                   "PatientController", "form");
$router->addRouterAdmin("add",                    "PatientController", "add");
$router->addRouterAdmin("edit",                   "PatientController", "edit");
$router->addRouterAdmin("delete",                 "PatientController", "delete");
$router->addRouterAdmin("detail",                 "PatientController", "detail");
$router->addRouterAdmin("aiSummarizePatient",     "PatientController", "aiSummarizePatient");
$router->addRouterAdmin("patient-accounts",       "PatientController", "patientAccounts");
$router->addRouterAdmin("formAddPatientAccounts", "PatientController", "formAddPatientAccounts");
$router->addRouterAdmin("addPatientAccount",      "PatientController", "addPatientAccount");
$router->addRouterAdmin("formEditPatientAccount", "PatientController", "formEditPatientAccount");
$router->addRouterAdmin("updatePatientAccount",   "PatientController", "updatePatientAccount");

// ===== Service (Dịch vụ) =====
$router->addRouterAdmin("qlydichvu",             "ServiceController", "qlydichvu");
$router->addRouterAdmin("addDich_vu",            "ServiceController", "addDich_vu");
$router->addRouterAdmin("suadichvu",             "ServiceController", "suaDichVu");
$router->addRouterAdmin("xemchitietdichvu",      "ServiceController", "xemchitietdichvu");
$router->addRouterAdmin("capNhatDichVu",         "ServiceController", "capNhatDichVu");
$router->addRouterAdmin("toggleTrangThaiDichVu", "ServiceController", "toggleTrangThaiDichVu");
$router->addRouterAdmin("vattuthem",             "ServiceController", "vattuthem");
$router->addRouterAdmin("vattusua",              "ServiceController", "vattusua");
$router->addRouterAdmin("deleteDichVu",          "ServiceController", "deleteDichVu");
$router->addRouterAdmin("hienthivattu",          "ServiceController", "hienthivattu");
$router->addRouterAdmin("showFormBenhNhanh",     "ServiceController", "showFormBenhNhanh");

// ===== Appointment (Lịch hẹn & Lịch khám) =====
$router->addRouterAdmin("listYcLichHen",         "AppointmentController", "listYcLichHen");
$router->addRouterAdmin("formSuaYeuCauDatLich",  "AppointmentController", "formSuaYeuCauDatLich");
$router->addRouterAdmin("suaYeuCauDatLich",      "AppointmentController", "suaYeuCauDatLich");
$router->addRouterAdmin("listLichHen",           "AppointmentController", "listLichHen");
$router->addRouterAdmin("themlichhenmoi",        "AppointmentController", "themlichhenmoi");
$router->addRouterAdmin("ganHoSo",               "AppointmentController", "ganHoSo");
$router->addRouterAdmin("listLichKham",          "AppointmentController", "listLichKham");
$router->addRouterAdmin("tiepNhanKham",          "AppointmentController", "tiepNhanKham");
$router->addRouterAdmin("tiepNhanBenhNhan",      "AppointmentController", "tiepNhanBenhNhan");
$router->addRouterAdmin("formKham",              "AppointmentController", "formKham");
$router->addRouterAdmin("getAvailableTime",      "AppointmentController", "getAvailableTime");
$router->addRouterAdmin("themlichhentructiep",   "AppointmentController", "themlichhentructiep");

// ===== Doctor (Bác sĩ) =====
$router->addRouterAdmin("qlybacsi",      "DoctorController", "qlybacsi");
$router->addRouterAdmin("formThemBacSi", "DoctorController", "formThemBacSi");
$router->addRouterAdmin("addBacSi",      "DoctorController", "addBacSi");
$router->addRouterAdmin("suaBacSi",      "DoctorController", "suaBacSi");
$router->addRouterAdmin("formSuaBacSi",  "DoctorController", "formSuaBacSi");
$router->addRouterAdmin("xoaBacSi",      "DoctorController", "xoaBacSi");
$router->addRouterAdmin("toggleBacSi",   "DoctorController", "toggleBacSi");

// ===== Medical Record (Kết quả & Lịch sử khám) =====
$router->addRouterAdmin("luuKetQuaKham",    "MedicalRecordController", "luuKetQuaKham");
$router->addRouterAdmin("listLichSuKham",   "MedicalRecordController", "listLichSuKham");
$router->addRouterAdmin("chiTietLichSuKham","MedicalRecordController", "chiTietLichSuKham");
$router->addRouterAdmin("searchLichSuKham", "MedicalRecordController", "searchLichSuKham");

// ===== Message (Tin nhắn) =====
$router->addRouterAdmin("tatCaTin",      "MessageController", "tatCaTin");
$router->addRouterAdmin("chiTietTin",    "MessageController", "chiTietTin");
$router->addRouterAdmin("hienFormTraLoi","MessageController", "hienFormTraLoi");
$router->addRouterAdmin("xuLyGuiTraLoi", "MessageController", "xuLyGuiTraLoi");
$router->addRouterAdmin("xoaTin",        "MessageController", "xoaTin");

// ===== User Management (Lễ tân) =====
$router->addRouterAdmin("listTaiKhoanLeTan",    "UserManagementController", "listTaiKhoanLeTan");
$router->addRouterAdmin("formThemTaiKhoanLeTan","UserManagementController", "formThemTaiKhoanLeTan");
$router->addRouterAdmin("themTaiKhoanLeTan",    "UserManagementController", "themTaiKhoanLeTan");
$router->addRouterAdmin("formSuaTaiKhoanLeTan", "UserManagementController", "formSuaTaiKhoanLeTan");
$router->addRouterAdmin("suaTaiKhoanLeTan",     "UserManagementController", "suaTaiKhoanLeTan");

// ===== POS (Bán hàng tại quầy) =====
$router->addRouterAdmin("pos",                "PosController", "index");
$router->addRouterAdmin("apiGetItems",        "PosController", "apiGetItems");
$router->addRouterAdmin("apiGetProducts",     "PosController", "apiGetProducts");
$router->addRouterAdmin("apiSaveProduct",     "PosController", "apiSaveProduct");
$router->addRouterAdmin("apiToggleProduct",   "PosController", "apiToggleProduct");
$router->addRouterAdmin("apiSearchPatient",   "PosController", "apiSearchPatient");
$router->addRouterAdmin("apiAddPatientQuick", "PosController", "apiAddPatientQuick");
$router->addRouterAdmin("apiProcessCheckout", "PosController", "apiProcessCheckout");
$router->addRouterAdmin("invoicePrint",       "PosController", "invoicePrint");

$router->dispatchAdmin();
