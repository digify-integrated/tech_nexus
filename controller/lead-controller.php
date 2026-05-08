<?php
session_start();

class LeadController {
    private $leadModel;
    private $leadStatusModel;
    private $employeeModel;
    private $userModel;
    private $securityModel;

    public function __construct(LeadModel $leadModel, LeadStatusModel $leadStatusModel, EmployeeModel $employeeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->leadModel = $leadModel;
        $this->leadStatusModel = $leadStatusModel;
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {

                case 'save lead':
                    $this->saveLead();
                break;

                case 'get lead details':
                    $this->getLeadDetails();
                break;

                case 'delete lead':
                    $this->deleteLead();
                break;

                case 'delete multiple lead':
                    $this->deleteMultipleLead();
                break;

                case 'duplicate lead':
                    $this->duplicateLead();
                break;

                default:
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid transaction.'
                    ]);
                break;
            }
        }
    }

    /* -----------------------------------------------
    | SAVE LEAD (INSERT / UPDATE)
    |----------------------------------------------- */
    public function saveLead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $leadID = isset($_POST['lead_id']) 
            ? htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8') 
            : null;

        $leadName = htmlspecialchars($_POST['lead_name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
        $leadStatusID = htmlspecialchars($_POST['lead_status_id'], ENT_QUOTES, 'UTF-8');
        $assignedTo = htmlspecialchars($_POST['assigned_to'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $leadStatusName = $this->leadStatusModel->getLeadStatus($leadStatusID);
        $leadStatusName = $leadStatusName['lead_status_name'] ?? null;

        $employeeDetails = $this->employeeModel->getPersonalInformation($assignedTo);
        $employeeName = $employeeDetails['file_as'] ?? null;

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        $checkLeadExist = $this->leadModel->checkLeadExist($leadID);
        $total = $checkLeadExist['total'] ?? 0;

        if ($total > 0) {

            $this->leadModel->updateLead(
                $leadID,
                $leadName,
                $email,
                $phone,
                $leadStatusID,
                $leadStatusName,
                $assignedTo,
                $employeeName,
                $remarks,
                $userID
            );

            echo json_encode([
                'success' => true,
                'insertRecord' => false,
                'leadID' => $this->securityModel->encryptData($leadID)
            ]);
            exit;

        } 
        else {

            $leadID = $this->leadModel->insertLead(
                $leadName,
                $email,
                $phone,
                $leadStatusID,
                $leadStatusName,
                $assignedTo,
                $employeeName,
                $remarks,
                $userID
            );

            echo json_encode([
                'success' => true,
                'insertRecord' => true,
                'leadID' => $this->securityModel->encryptData($leadID)
            ]);
            exit;
        }
    }

    /* -----------------------------------------------
    | GET LEAD DETAILS
    |----------------------------------------------- */
    public function getLeadDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['lead_id']) && !empty($_POST['lead_id'])) {

            $userID = $_SESSION['user_id'];
            $leadID = $_POST['lead_id'];

            $user = $this->userModel->getUserByID($userID);

            if (!$user || !$user['is_active']) {
                echo json_encode([
                    'success' => false,
                    'isInactive' => true
                ]);
                exit;
            }

            $leadDetails = $this->leadModel->getLead($leadID);

            echo json_encode([
                'success' => true,
                'leadName' => $leadDetails['lead_name'],
                'email' => $leadDetails['email'],
                'phone' => $leadDetails['phone'],
                'leadStatusId' => $leadDetails['lead_status_id'],
                'leadStatusName' => $leadDetails['lead_status_name'],
                'assignedTo' => $leadDetails['assigned_to'],
                'assignedToName' => $leadDetails['assigned_to_name'],
                'remarks' => $leadDetails['remarks'],
            ]);
            exit;
        }
    }

    /* -----------------------------------------------
    | DELETE LEAD
    |----------------------------------------------- */
    public function deleteLead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];
        $leadID = htmlspecialchars($_POST['lead_id'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        $checkLeadExist = $this->leadModel->checkLeadExist($leadID);
        $total = $checkLeadExist['total'] ?? 0;

        if ($total === 0) {
            echo json_encode([
                'success' => false,
                'notExist' => true
            ]);
            exit;
        }

        $this->leadModel->deleteLead($leadID);

        echo json_encode([
            'success' => true
        ]);
        exit;
    }

    /* -----------------------------------------------
    | DELETE MULTIPLE LEAD
    |----------------------------------------------- */
    public function deleteMultipleLead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];
        $leadIDs = $_POST['lead_id'];

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        foreach ($leadIDs as $leadID) {
            $this->leadModel->deleteLead($leadID);
        }

        echo json_encode([
            'success' => true
        ]);
        exit;
    }

    /* -----------------------------------------------
    | DUPLICATE LEAD
    |----------------------------------------------- */
    public function duplicateLead() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];
        $leadID = $_POST['lead_id'];

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        $newLeadID = $this->leadModel->duplicateLead($leadID, $userID);

        echo json_encode([
            'success' => true,
            'leadID' => $this->securityModel->encryptData($newLeadID)
        ]);
        exit;
    }
}

/* -----------------------------------------------
| BOOTSTRAP CONTROLLER
|----------------------------------------------- */
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/lead-model.php';
require_once '../model/lead-status-model.php';
require_once '../model/employee-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeadController(
    new LeadModel(new DatabaseModel),
    new LeadStatusModel(new DatabaseModel),
    new EmployeeModel(new DatabaseModel),
    new UserModel(new DatabaseModel, new SystemModel),
    new SecurityModel()
);

$controller->handleRequest();