<?php
session_start();

class LeadStatusController {
    private $leadStatusModel;
    private $userModel;
    private $securityModel;

    public function __construct(LeadStatusModel $leadStatusModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->leadStatusModel = $leadStatusModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save lead status':
                    $this->saveLeadStatus();
                    break;
                case 'get lead status details':
                    $this->getLeadStatusDetails();
                    break;
                case 'delete lead status':
                    $this->deleteLeadStatus();
                    break;
                case 'delete multiple lead status':
                    $this->deleteMultipleLeadStatus();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveLeadStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadStatusID = isset($_POST['lead_status_id']) ? htmlspecialchars($_POST['lead_status_id'], ENT_QUOTES, 'UTF-8') : null;
        $leadStatusName = htmlspecialchars($_POST['lead_status_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeadStatusExist = $this->leadStatusModel->checkLeadStatusExist($leadStatusID);
        $total = $checkLeadStatusExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->leadStatusModel->updateLeadStatus($leadStatusID, $leadStatusName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leadStatusID' => $this->securityModel->encryptData($leadStatusID)]);
            exit;
        } 
        else {
            $leadStatusID = $this->leadStatusModel->insertLeadStatus($leadStatusName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leadStatusID' => $this->securityModel->encryptData($leadStatusID)]);
            exit;
        }
    }
    
    public function deleteLeadStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadStatusID = htmlspecialchars($_POST['lead_status_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeadStatusExist = $this->leadStatusModel->checkLeadStatusExist($leadStatusID);
        $total = $checkLeadStatusExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadStatusModel->deleteLeadStatus($leadStatusID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleLeadStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadStatusIDs = $_POST['lead_status_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leadStatusIDs as $leadStatusID){
            $this->leadStatusModel->deleteLeadStatus($leadStatusID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getLeadStatusDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['lead_status_id']) && !empty($_POST['lead_status_id'])) {
            $userID = $_SESSION['user_id'];
            $leadStatusID = $_POST['lead_status_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leadStatusDetails = $this->leadStatusModel->getLeadStatus($leadStatusID);

            $response = [
                'success' => true,
                'leadStatusName' => $leadStatusDetails['lead_status_name']
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/lead-status-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeadStatusController(new LeadStatusModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
