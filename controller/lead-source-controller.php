<?php
session_start();

class LeadSourceController {
    private $leadSourceModel;
    private $userModel;
    private $securityModel;

    public function __construct(LeadSourceModel $leadSourceModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->leadSourceModel = $leadSourceModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save lead source':
                    $this->saveLeadSource();
                    break;
                case 'get lead source details':
                    $this->getLeadSourceDetails();
                    break;
                case 'delete lead source':
                    $this->deleteLeadSource();
                    break;
                case 'delete multiple lead source':
                    $this->deleteMultipleLeadSource();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveLeadSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadSourceID = isset($_POST['lead_source_id']) ? htmlspecialchars($_POST['lead_source_id'], ENT_QUOTES, 'UTF-8') : null;
        $leadSourceName = htmlspecialchars($_POST['lead_source_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeadSourceExist = $this->leadSourceModel->checkLeadSourceExist($leadSourceID);
        $total = $checkLeadSourceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->leadSourceModel->updateLeadSource($leadSourceID, $leadSourceName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leadSourceID' => $this->securityModel->encryptData($leadSourceID)]);
            exit;
        } 
        else {
            $leadSourceID = $this->leadSourceModel->insertLeadSource($leadSourceName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leadSourceID' => $this->securityModel->encryptData($leadSourceID)]);
            exit;
        }
    }
    
    public function deleteLeadSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadSourceID = htmlspecialchars($_POST['lead_source_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeadSourceExist = $this->leadSourceModel->checkLeadSourceExist($leadSourceID);
        $total = $checkLeadSourceExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadSourceModel->deleteLeadSource($leadSourceID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleLeadSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leadSourceIDs = $_POST['lead_source_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leadSourceIDs as $leadSourceID){
            $this->leadSourceModel->deleteLeadSource($leadSourceID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function getLeadSourceDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['lead_source_id']) && !empty($_POST['lead_source_id'])) {
            $userID = $_SESSION['user_id'];
            $leadSourceID = $_POST['lead_source_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leadSourceDetails = $this->leadSourceModel->getLeadSource($leadSourceID);

            $response = [
                'success' => true,
                'leadSourceName' => $leadSourceDetails['lead_source_name'],
            ];

            echo json_encode($response);
            exit;
        }
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/lead-source-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeadSourceController(new LeadSourceModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
