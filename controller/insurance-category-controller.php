<?php
session_start();

class InsuranceCategoryController {
    private $insuranceCategoryModel;
    private $userModel;
    private $securityModel;

    public function __construct(InsuranceCategoryModel $insuranceCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->insuranceCategoryModel = $insuranceCategoryModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save insurance category':
                    $this->saveInsuranceCategory();
                    break;
                case 'get insurance category details':
                    $this->getInsuranceCategoryDetails();
                    break;
                case 'delete insurance category':
                    $this->deleteInsuranceCategory();
                    break;
                case 'delete multiple insurance category':
                    $this->deleteMultipleInsuranceCategory();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInsuranceCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceCategoryID = isset($_POST['insurance_category_id']) ? htmlspecialchars($_POST['insurance_category_id'], ENT_QUOTES, 'UTF-8') : null;
        $insuranceCategoryName = htmlspecialchars($_POST['insurance_category_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceCategoryExist = $this->insuranceCategoryModel->checkInsuranceCategoryExist($insuranceCategoryID);
        $total = $checkInsuranceCategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceCategoryModel->updateInsuranceCategory($insuranceCategoryID, $insuranceCategoryName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'insuranceCategoryID' => $this->securityModel->encryptData($insuranceCategoryID)]);
            exit;
        } 
        else {
            $insuranceCategoryID = $this->insuranceCategoryModel->insertInsuranceCategory($insuranceCategoryName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'insuranceCategoryID' => $this->securityModel->encryptData($insuranceCategoryID)]);
            exit;
        }
    }
    
    public function deleteInsuranceCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceCategoryID = htmlspecialchars($_POST['insurance_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInsuranceCategoryExist = $this->insuranceCategoryModel->checkInsuranceCategoryExist($insuranceCategoryID);
        $total = $checkInsuranceCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->insuranceCategoryModel->deleteInsuranceCategory($insuranceCategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInsuranceCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $insuranceCategoryIDs = $_POST['insurance_category_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($insuranceCategoryIDs as $insuranceCategoryID){
            $this->insuranceCategoryModel->deleteInsuranceCategory($insuranceCategoryID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInsuranceCategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['insurance_category_id']) && !empty($_POST['insurance_category_id'])) {
            $userID = $_SESSION['user_id'];
            $insuranceCategoryID = $_POST['insurance_category_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $insuranceCategoryDetails = $this->insuranceCategoryModel->getInsuranceCategory($insuranceCategoryID);

            $response = [
                'success' => true,
                'insuranceCategoryName' => $insuranceCategoryDetails['insurance_category_name']
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/insurance-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceCategoryController(new InsuranceCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
