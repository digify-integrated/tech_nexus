<?php
session_start();

class InquiryTypeController {
    private $inquiryTypeModel;
    private $userModel;
    private $securityModel;

    public function __construct(InquiryTypeModel $inquiryTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->inquiryTypeModel = $inquiryTypeModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save inquiry type':
                    $this->saveInquiryType();
                    break;
                case 'get inquiry type details':
                    $this->getInquiryTypeDetails();
                    break;
                case 'delete inquiry type':
                    $this->deleteInquiryType();
                    break;
                case 'delete multiple inquiry type':
                    $this->deleteMultipleInquiryType();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveInquiryType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $inquiryTypeID = isset($_POST['inquiry_type_id']) ? htmlspecialchars($_POST['inquiry_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $inquiryTypeName = htmlspecialchars($_POST['inquiry_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInquiryTypeExist = $this->inquiryTypeModel->checkInquiryTypeExist($inquiryTypeID);
        $total = $checkInquiryTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->inquiryTypeModel->updateInquiryType($inquiryTypeID, $inquiryTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'inquiryTypeID' => $this->securityModel->encryptData($inquiryTypeID)]);
            exit;
        } 
        else {
            $inquiryTypeID = $this->inquiryTypeModel->insertInquiryType($inquiryTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'inquiryTypeID' => $this->securityModel->encryptData($inquiryTypeID)]);
            exit;
        }
    }
    
    public function deleteInquiryType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $inquiryTypeID = htmlspecialchars($_POST['inquiry_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkInquiryTypeExist = $this->inquiryTypeModel->checkInquiryTypeExist($inquiryTypeID);
        $total = $checkInquiryTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->inquiryTypeModel->deleteInquiryType($inquiryTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleInquiryType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $inquiryTypeIDs = $_POST['inquiry_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($inquiryTypeIDs as $inquiryTypeID){
            $this->inquiryTypeModel->deleteInquiryType($inquiryTypeID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getInquiryTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['inquiry_type_id']) && !empty($_POST['inquiry_type_id'])) {
            $userID = $_SESSION['user_id'];
            $inquiryTypeID = $_POST['inquiry_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $inquiryTypeDetails = $this->inquiryTypeModel->getInquiryType($inquiryTypeID);

            $response = [
                'success' => true,
                'inquiryTypeName' => $inquiryTypeDetails['inquiry_type_name']
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/inquiry-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InquiryTypeController(new InquiryTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
