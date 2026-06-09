<?php

class MedicineController
{
    private $clinic;
    public function __construct()
    {
        require_once __DIR__ . '/../models/db.php';
        $this->clinic = new modelClinic();
        $this->clinic->ketNoiDB();
    }

    public function listMedicine()
    {
        $listMedicine = $this->clinic->modelListMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function formAddMedicine()
    {
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/addMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function addMedicine()
    {
        $medicineName = $_POST['medicineName'];
        $classMedicine = $_POST['classMedicine'];
        $dosageForm = $_POST['dosageForm'];
        $drugContent = $_POST['drugContent'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $expirationDate = $_POST['expirationDate'];
        $price = $_POST['price'];
        $manufacturer = $_POST['manufacturer'];
        $countryProduction = $_POST['countryProduction'];
        $description = $_POST['description'];

        $result = $this->clinic->addMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description);

        if ($result) {
            header("location: admin.php?admin=listMedicine&status=addSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=addError");
        }
    }

    public function formEditMedicine($idAdmin)
    {
        $medicineByID = $this->clinic->getMedicineByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/editMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function editMedicine($idAdmin)
    {
        $medicineName = $_POST['medicineName'];
        $classMedicine = $_POST['classMedicine'];
        $dosageForm = $_POST['dosageForm'];
        $drugContent = $_POST['drugContent'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $expirationDate = $_POST['expirationDate'];
        $price = $_POST['price'];
        $manufacturer = $_POST['manufacturer'];
        $countryProduction = $_POST['countryProduction'];
        $description = $_POST['description'];

        $result = $this->clinic->editMedicine($medicineName, $classMedicine, $dosageForm, $drugContent, $unit, $quantity, $expirationDate, $price, $manufacturer, $countryProduction, $description, $idAdmin);

        if ($result) {
            header("location: admin.php?admin=listMedicine&status=updateSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=updateError");
        }
    }

    public function deleteMedicine($idAdmin)
    {
        $result = $this->clinic->deleteMedicine($idAdmin);
        if ($result) {
            header("location: admin.php?admin=listMedicine&status=deleteSuccess");
        } else {
            header("location: admin.php?admin=listMedicine&status=errorDelete");
        }
    }

    public function detailMedicine($idAdmin)
    {
        $detailMedicineByID = $this->clinic->getMedicineByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/deltailMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function listDispenseMedicine()
    {
        $historyDispenseMedicine = $this->clinic->getAllDispenseMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listDispenseMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function formDispenseMedicine()
    {
        $listMedicine = $this->clinic->modelListMedicine();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/formDispenseMedicine.php";
        require_once "backend/views/fileJS.php";
    }

    public function dispenseMedicine()
    {
        $idMedicine = $_POST['idMedicine'];
        $quantityDispense = $_POST['quantityDispense'];
        $reasonDispense = $_POST['reasonDispense'];
        $dateDispense = $_POST['dateDispense'];

        $currentMedicine = $this->clinic->getMedicineByID($idMedicine);

        $stockAvailable = (int) $currentMedicine['so_luong'];

        if ($quantityDispense <= 0) {
            header("location: admin.php?admin=formDispenseMedicine&status=too_little");
        } else if ($stockAvailable < $quantityDispense) {
            header("location: admin.php?admin=formDispenseMedicine&status=out_of_stock&available=$stockAvailable");
        } else {
            $this->clinic->dispenseMedicine($idMedicine, $quantityDispense, $reasonDispense, $dateDispense);
            header("location: admin.php?admin=listDispenseMedicine&status=success");
        }
    }

    public function formPrescription()
    {
        $medicines = $this->clinic->modelListMedicine();
        $doctor = $this->clinic->getAllDoctor();
        $namePatient = $this->clinic->getAllPatient();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/formPrescription.php";
        require_once "backend/views/fileJS.php";
    }

    public function listPrescription()
    {
        $prescription = $this->clinic->modelListPrescription();
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/listPrescription.php";
        require_once "backend/views/fileJS.php";
    }

    public function prescription()
    {
        $patient = $_POST['patient_id'];
        $doctor = $_POST['doctor_id'];
        $diagnose = $_POST['diagnose'];
        $dosage = $_POST['dosage'];
        $idMedicine = $_POST['idMedicine'];
        $quantityMedicine = $_POST['quantityMedicine'];

        $result = $this->clinic->prescription($patient, $doctor, $diagnose, $idMedicine, $quantityMedicine, $dosage);

        if ($result === false) {
            header("location: admin.php?admin=formPrescription&status=error");
        } else {
            header("location: admin.php?admin=listPrescription&status=success");
        }
    }

    public function detailPrescription($idAdmin)
    {
        $detailPres = $this->clinic->getPrescriptionByID($idAdmin);
        require_once "backend/views/header.php";
        require_once "backend/views/sidebar.php";
        require_once "backend/views/topbar.php";
        require_once "backend/views/supplies/detailPrescription.php";
        require_once "backend/views/fileJS.php";
    }
}
