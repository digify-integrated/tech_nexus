<?php
session_start();

class CivilStatusController {
    private $civilStatusModel;
    private $userModel;
    private $securityModel;

    public function __construct(CivilStatusModel $civilStatusModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->civilStatusModel = $civilStatusModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save civil status':
                    $this->saveCivilStatus();
                    break;
                case 'get civil status details':
                    $this->getCivilStatusDetails();
                    break;
                case 'delete civil status':
                    $this->deleteCivilStatus();
                    break;
                case 'delete multiple civil status':
                    $this->deleteMultipleCivilStatus();
                    break;
                case 'duplicate civil status':
                    $this->duplicateCivilStatus();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }

    public function saveCivilStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $civilStatusID = isset($_POST['civil_status_id']) ? htmlspecialchars($_POST['civil_status_id'], ENT_QUOTES, 'UTF-8') : null;
        $civilStatusName = htmlspecialchars($_POST['civil_status_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCivilStatusExist = $this->civilStatusModel->checkCivilStatusExist($civilStatusID);
        $total = $checkCivilStatusExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->civilStatusModel->updateCivilStatus($civilStatusID, $civilStatusName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'civilStatusID' => $this->securityModel->encryptData($civilStatusID)]);
            exit;
        } 
        else {
            $civilStatusID = $this->civilStatusModel->insertCivilStatus($civilStatusName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'civilStatusID' => $this->securityModel->encryptData($civilStatusID)]);
            exit;
        }
    }
    
    public function deleteCivilStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $civilStatusID = htmlspecialchars($_POST['civil_status_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCivilStatusExist = $this->civilStatusModel->checkCivilStatusExist($civilStatusID);
        $total = $checkCivilStatusExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->civilStatusModel->deleteCivilStatus($civilStatusID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function deleteMultipleCivilStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $civilStatusIDs = $_POST['civil_status_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($civilStatusIDs as $civilStatusID){
            $this->civilStatusModel->deleteCivilStatus($civilStatusID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function duplicateCivilStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $civilStatusID = htmlspecialchars($_POST['civil_status_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCivilStatusExist = $this->civilStatusModel->checkCivilStatusExist($civilStatusID);
        $total = $checkCivilStatusExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $civilStatusID = $this->civilStatusModel->duplicateCivilStatus($civilStatusID, $userID);

        echo json_encode(['success' => true, 'civilStatusID' =>  $this->securityModel->encryptData($civilStatusID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCivilStatusDetails
    # Description: 
    # Handles the retrieval of civil status details such as civil status name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCivilStatusDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['civil_status_id']) && !empty($_POST['civil_status_id'])) {
            $userID = $_SESSION['user_id'];
            $civilStatusID = $_POST['civil_status_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $civilStatusDetails = $this->civilStatusModel->getCivilStatus($civilStatusID);

            $response = [
                'success' => true,
                'civilStatusName' => $civilStatusDetails['civil_status_name']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/civil-status-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CivilStatusController(new CivilStatusModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>