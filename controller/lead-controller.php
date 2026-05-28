<?php
session_start();

class LeadController {
    private $leadModel;
    private $leadStatusModel;
    private $employeeModel;
    private $userModel;
    private $securityModel;
    private $systemModel;

    public function __construct(LeadModel $leadModel, LeadStatusModel $leadStatusModel, EmployeeModel $employeeModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leadModel = $leadModel;
        $this->leadStatusModel = $leadStatusModel;
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
    }

    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {

                case 'save lead':
                    $this->saveLead();
                break;

                case 'save lead note':
                    $this->saveLeadNote();
                break;

                case 'save lead status':
                    $this->saveLeadStatus();
                break;

                case 'get lead details':
                    $this->getLeadDetails();
                break;

                case 'delete lead':
                    $this->deleteLead();
                break;

                case 'delete lead note':
                    $this->deleteLeadNote();
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

        $leadID = htmlspecialchars($_POST['lead_id'] ?? '', ENT_QUOTES, 'UTF-8');

        /* =========================
            SANITIZE INPUTS
        ========================== */

        $firstName      = trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8'));
        $middleName     = trim(htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8'));
        $lastName       = trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8'));
        $corporateName  = trim(htmlspecialchars($_POST['corporate_name'], ENT_QUOTES, 'UTF-8'));
        $stockNumber    = trim(htmlspecialchars($_POST['stock_number'], ENT_QUOTES, 'UTF-8'));
        $address        = trim(htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8'));
        $cityID         = !empty($_POST['city_id']) ? (int) $_POST['city_id'] : null;
        $email          = trim(htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'));
        $phone          = trim(htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8'));
        $genderID       = !empty($_POST['gender_id']) ? (int) $_POST['gender_id'] : null;
        $leadStatusID   = !empty($_POST['lead_status_id']) ? (int) $_POST['lead_status_id'] : null;
        $inquiryTypeID  = !empty($_POST['inquiry_type_id']) ? (int) $_POST['inquiry_type_id'] : null;
        $remarks        = trim(htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8'));
        $inquiry_date = $this->systemModel->checkDate('empty', $_POST['inquiry_date'], '', 'Y-m-d', '');

        /* =========================
            BUILD FILE AS
        ========================== */

        $fileAs = trim(
            $firstName . ' ' .
            (!empty($middleName) ? $middleName . ' ' : '') .
            $lastName
        );

        /* =========================
            VALIDATE USER
        ========================== */

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {

            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        /* =========================
            CHECK EXISTENCE
        ========================== */

        $checkLeadExist = $this->leadModel->checkLeadExist($leadID);
        $total = $checkLeadExist['total'] ?? 0;

        /* =========================
            UPDATE
        ========================== */

        if ($total > 0) {

            $this->leadModel->updateLead(
                $leadID,
                $fileAs,
                $firstName,
                $middleName,
                $lastName,
                $corporateName,
                $stockNumber,
                $address,
                $cityID,
                $email,
                $phone,
                $genderID,
                $leadStatusID,
                $inquiryTypeID,
                $remarks,
                $inquiry_date,
                $userID
            );

            echo json_encode([
                'success' => true,
                'insertRecord' => false,
                'leadID' => $this->securityModel->encryptData($leadID)
            ]);

            exit;
        }

        /* =========================
            INSERT
        ========================== */

        else {

            $leadID = $this->leadModel->insertLead(
                $fileAs,
                $firstName,
                $middleName,
                $lastName,
                $corporateName,
                $stockNumber,
                $address,
                $cityID,
                $email,
                $phone,
                $genderID,
                $leadStatusID,
                $inquiryTypeID,
                $remarks,
                $inquiry_date,
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

    public function saveLeadNote() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $leadID = (int) $_POST['lead_id'];

        $note = trim(
            htmlspecialchars(
                $_POST['lead_note'],
                ENT_QUOTES,
                'UTF-8'
            )
        );

        $leadNoteID = $this->leadModel->insertLeadNote(
            $leadID,
            $note,
            $userID
        );

        echo json_encode([
            'success' => true,
            'leadNoteID' => $leadNoteID
        ]);

        exit;
    }

    public function saveLeadStatus() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $leadID = (int) $_POST['lead_id'];
        $leadStatusId = (int) $_POST['lead_status_id2'];

        $this->leadModel->updateLeadStatus(
            $leadID,
            $leadStatusId,
            $userID
        );

        echo json_encode([
            'success' => true,
        ]);

        exit;
    }

public function deleteLeadNote() {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $userID = $_SESSION['user_id'];

    $leadNoteID = (int) $_POST['lead_note_id'];

    $this->leadModel->deleteLeadNote(
        $leadNoteID,
        $userID
    );

    echo json_encode([
        'success' => true
    ]);

    exit;
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

                'firstName' => $leadDetails['first_name'],
                'middleName' => $leadDetails['middle_name'],
                'lastName' => $leadDetails['last_name'],

                'corporateName' => $leadDetails['corporate_name'],
                'stockNumber' => $leadDetails['stock_number'],

                'address' => $leadDetails['address'],

                'cityId' => $leadDetails['city_id'],

                'email' => $leadDetails['email'],
                'phone' => $leadDetails['phone'],

                'genderId' => $leadDetails['gender_id'],

                'leadStatusId' => $leadDetails['lead_status_id'],

                'inquiryTypeId' => $leadDetails['inquiry_type_id'],
                'inquiryDate' =>  $this->systemModel->checkDate('empty', $leadDetails['inquiry_date'], '', 'm/d/Y', ''),

                'remarks' => $leadDetails['remarks']
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
    new SecurityModel(),
    new SystemModel()
);

$controller->handleRequest();