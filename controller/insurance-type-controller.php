<?php
session_start();

class InsuranceTypeController {
    private $insuranceTypeModel;
    private $userModel;
    private $securityModel;

    public function __construct(InsuranceTypeModel $insuranceTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->insuranceTypeModel = $insuranceTypeModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save insurance type':
                    $this->saveInsuranceType();
                    break;
                case 'get insurance type details':
                    $this->getInsuranceTypeDetails();
                    break;
                case 'delete insurance type':
                    $this->deleteInsuranceType();
                    break;
                case 'delete multiple insurance type':
                    $this->deleteMultipleInsuranceType();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInsuranceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceTypeID = isset($_POST['insurance_type_id']) ? htmlspecialchars($_POST['insurance_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $insuranceTypeName = htmlspecialchars($_POST['insurance_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceTypeExist = $this->insuranceTypeModel->checkInsuranceTypeExist($insuranceTypeID);
        $total = $checkInsuranceTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceTypeModel->updateInsuranceType($insuranceTypeID, $insuranceTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'insuranceTypeID' => $this->securityModel->encryptData($insuranceTypeID)]);
            exit;
        } 
        else {
            $insuranceTypeID = $this->insuranceTypeModel->insertInsuranceType($insuranceTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'insuranceTypeID' => $this->securityModel->encryptData($insuranceTypeID)]);
            exit;
        }
    }
    
    public function deleteInsuranceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceTypeID = htmlspecialchars($_POST['insurance_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceTypeExist = $this->insuranceTypeModel->checkInsuranceTypeExist($insuranceTypeID);
        $total = $checkInsuranceTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->insuranceTypeModel->deleteInsuranceType($insuranceTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInsuranceType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceTypeIDs = $_POST['insurance_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($insuranceTypeIDs as $insuranceTypeID){
            $this->insuranceTypeModel->deleteInsuranceType($insuranceTypeID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInsuranceTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['insurance_type_id']) && !empty($_POST['insurance_type_id'])) {
            $userID = $_SESSION['user_id'];
            $insuranceTypeID = $_POST['insurance_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $insuranceTypeDetails = $this->insuranceTypeModel->getInsuranceType($insuranceTypeID);

            $response = [
                'success' => true,
                'insuranceTypeName' => $insuranceTypeDetails['insurance_type_name']
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/insurance-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceTypeController(new InsuranceTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
