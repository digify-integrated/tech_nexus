<?php
session_start();

# -------------------------------------------------------------
#
# Function: TransmittalController
# Description: 
# The TransmittalController class handles transmittal related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class TransmittalController {
    private $transmittalModel;
    private $employeeModel;
    private $departmentModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided TransmittalModel, UserModel and SecurityModel instances.
    # These instances are used for transmittal related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param TransmittalModel $transmittalModel     The TransmittalModel instance for transmittal related operations.
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param DepartmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(TransmittalModel $transmittalModel, EmployeeModel $employeeModel, DepartmentModel $departmentModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->transmittalModel = $transmittalModel;
        $this->employeeModel = $employeeModel;
        $this->departmentModel = $departmentModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: handleRequest
    # Description: 
    # This method checks the request method and dispatches the corresponding transaction based on the provided transaction parameter.
    # The transaction determines which action should be performed.
    #
    # Parameters:
    # - $transaction (string): The type of transaction.
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function handleRequest(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $transaction = isset($_POST['transaction']) ? $_POST['transaction'] : null;

            switch ($transaction) {
                case 'save transmittal':
                    $this->saveTransmittal();
                    break;
                case 'get transmittal details':
                    $this->getTransmittalDetails();
                    break;
                case 'transmit transmittal':
                    $this->transmitTransmittal();
                    break;
                case 'transmit multiple transmittal':
                    $this->transmitMultipleTransmittal();
                    break;
                case 'retransmit transmittal':
                    $this->retransmitTransmittal();
                    break;
                case 'receive transmittal':
                    $this->receiveTransmittal();
                    break;
                case 'receive multiple transmittal':
                    $this->receiveMultipleTransmittal();
                    break;
                case 'file transmittal':
                    $this->fileTransmittal();
                    break;
                case 'file multiple transmittal':
                    $this->fileMultipleTransmittal();
                    break;
                case 'cancel transmittal':
                    $this->cancelTransmittal();
                    break;
                case 'cancel multiple transmittal':
                    $this->cancelMultipleTransmittal();
                    break;
                case 'delete transmittal':
                    $this->deleteTransmittal();
                    break;
                case 'delete multiple transmittal':
                    $this->deleteMultipleTransmittal();
                    break;
                case 'duplicate transmittal':
                    $this->duplicateTransmittal();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveTransmittal
    # Description: 
    # Updates the existing transmittal if it exists; otherwise, inserts a new transmittal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $transmittalID = isset($_POST['transmittal_id']) ? htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8') : null;
        $transmittalDescription = htmlspecialchars($_POST['transmittal_description'], ENT_QUOTES, 'UTF-8');
        $receiverDepartment = htmlspecialchars($_POST['receiver_department'], ENT_QUOTES, 'UTF-8');
        $receiverID = htmlspecialchars($_POST['receiver_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $transmitterDetails = $this->employeeModel->getPersonalInformation($contactID);
        $transmitterName = $transmitterDetails['file_as'] ?? null;

        $receiverDetails = $this->employeeModel->getPersonalInformation($receiverID);
        $receiverName = $receiverDetails['file_as'] ?? null;

        $employeeDetails = $this->employeeModel->getEmploymentInformation($contactID);
        $transmitterDepartmentID = $employeeDetails['department_id'] ?? null;

        $transmitterDepartmentname = $this->departmentModel->getDepartment($transmitterDepartmentID)['department_name'] ?? null;
        $receiverDepartmentname = $this->departmentModel->getDepartment($receiverDepartment)['department_name'] ?? null;
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;
    
        if ($total > 0) {
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            if($transmittalStatus == 'Received'){
                $this->transmittalModel->updateReTransmittal($transmittalID, $transmittalDescription, $contactID, $transmitterName, $transmitterDepartmentID, $transmitterDepartmentname, $receiverID, $receiverName, $receiverDepartment, $receiverDepartmentname, $userID);
            }
            else{
                $this->transmittalModel->updateTransmittal($transmittalID, $transmittalDescription, $receiverID, $receiverName, $receiverDepartment, $receiverDepartmentname, $userID);
            }
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'transmittalID' => $this->securityModel->encryptData($transmittalID)]);
            exit;
        } 
        else {
            $transmittalID = $this->transmittalModel->insertTransmittal($transmittalDescription, $contactID, $contactID, $transmitterName, $transmitterDepartmentID, $transmitterDepartmentname, $receiverID, $receiverName, $receiverDepartment, $receiverDepartmentname, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'transmittalID' => $this->securityModel->encryptData($transmittalID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTransmittal
    # Description: 
    # Delete the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->transmittalModel->deleteTransmittal($transmittalID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleTransmittal
    # Description: 
    # Delete the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $this->transmittalModel->deleteTransmittal($transmittalID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Transmit methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: transmitTransmittal
    # Description: 
    # Transmit the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function transmitTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
        $transmitterID = $transmittalDetails['transmitter_id'];
        $transmittalStatus = $transmittalDetails['transmittal_status'];

        if($transmittalStatus != 'Draft'){
            echo json_encode(['success' => false, 'notDraft' =>  true]);
            exit;
        }

        if($transmitterID != $contactID){
            echo json_encode(['success' => false, 'notTransmitter' =>  true]);
            exit;
        }
    
        $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Transmitted', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: transmitMultipleTransmittal
    # Description: 
    # Transmit the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function transmitMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $transmitterID = $transmittalDetails['transmitter_id'];
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            if($transmittalStatus == 'Draft' && $transmitterID == $contactID){
                $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Transmitted', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Retransmit methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: retransmitTransmittal
    # Description: 
    # Re-transmit the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function retransmitTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
        $transmitterID = $transmittalDetails['transmitter_id'];
        $transmittalStatus = $transmittalDetails['transmittal_status'];

        if($transmittalStatus != 'Received'){
            echo json_encode(['success' => false, 'notReceived' =>  true]);
            exit;
        }

        if($transmitterID != $contactID){
            echo json_encode(['success' => false, 'notTransmitter' =>  true]);
            exit;
        }
    
        $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Re-Transmitted', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Receive methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: receiveTransmittal
    # Description: 
    # Receive the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function receiveTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $employmentDetails = $this->employeeModel->getEmploymentInformation($contactID);
        $contactDepartment = $employmentDetails['department_id'];

        $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
        $receiverID = $transmittalDetails['receiver_id'] ?? null;
        $receiverDepartment = $transmittalDetails['receiver_department'];
        $transmittalStatus = $transmittalDetails['transmittal_status'];

        if($transmittalStatus != 'Transmitted' && $transmittalStatus != 'Re-Transmitted'){
            echo json_encode(['success' => false, 'notTransmitted' =>  true]);
            exit;
        }
        
        if((!empty($receiverID) && $receiverID != $contactID) || (empty($receiverID) && $receiverDepartment != $contactDepartment)){
            echo json_encode(['success' => false, 'notReceiver' =>  true]);
            exit;
        }
    
        $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Received', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: receiveMultipleTransmittal
    # Description: 
    # Receive the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function receiveMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $employmentDetails = $this->employeeModel->getEmploymentInformation($contactID);
        $contactDepartment = $employmentDetails['department_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $receiverID = $transmittalDetails['receiver_id'];
            $receiverDepartment = $transmittalDetails['receiver_department'];
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            if(($transmittalStatus == 'Transmitted' || $transmittalStatus == 'Re-Transmitted') && ((!empty($receiverID) && $receiverID == $contactID) || (empty($receiverID) && $receiverDepartment == $contactDepartment))){
                $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Received', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   File methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: fileTransmittal
    # Description: 
    # File the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function fileTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $employmentDetails = $this->employeeModel->getEmploymentInformation($contactID);
        $contactDepartment = $employmentDetails['department_id'];

        $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
        $receiverID = $transmittalDetails['receiver_id'] ?? null;
        $receiverDepartment = $transmittalDetails['receiver_department'];
        $transmittalStatus = $transmittalDetails['transmittal_status'];

        if($transmittalStatus != 'Received'){
            echo json_encode(['success' => false, 'notReceived' =>  true]);
            exit;
        }

        if((!empty($receiverID) && $receiverID != $contactID) || (empty($receiverID) && $receiverDepartment != $contactDepartment)){
            echo json_encode(['success' => false, 'notReceiver' =>  true]);
            exit;
        }
    
        $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Filed', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: fileMultipleTransmittal
    # Description: 
    # File the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function fileMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $employmentDetails = $this->employeeModel->getEmploymentInformation($contactID);
        $contactDepartment = $employmentDetails['department_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $receiverID = $transmittalDetails['receiver_id'];
            $receiverDepartment = $transmittalDetails['receiver_department'];
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            if($transmittalStatus == 'Received' && ((!empty($receiverID) && $receiverID == $contactID) || (empty($receiverID) && $receiverDepartment == $contactDepartment))){
                $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Filed', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Cancel methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: cancelTransmittal
    # Description: 
    # Cancel the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function cancelTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
        $transmitterID = $transmittalDetails['transmitter_id'];
        $transmittalStatus = $transmittalDetails['transmittal_status'];

        if($transmittalStatus != 'Draft' && $transmittalStatus != 'Transmitted' && $transmittalStatus != 'Re-Transmitted'){
            echo json_encode(['success' => false, 'notDraft' =>  true]);
            exit;
        }

        if($transmitterID != $contactID){
            echo json_encode(['success' => false, 'notTransmitter' =>  true]);
            exit;
        }
    
        $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Cancelled', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: cancelMultipleTransmittal
    # Description: 
    # Cancel the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function cancelMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $contactDetails = $this->userModel->getContactByID($userID);
        $contactID = $contactDetails['contact_id'] ?? null;

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $transmitterID = $transmittalDetails['transmitter_id'];
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            if(($transmittalStatus == 'Draft' || $transmittalStatus == 'Transmitted' || $transmittalStatus == 'Re-Transmitted') && $transmitterID == $contactID){
                $this->transmittalModel->updateTransmittalStatus($transmittalID, 'Cancelled', $userID);
            }
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTransmittalDetails
    # Description: 
    # Handles the retrieval of transmittal details such as transmittal name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getTransmittalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['transmittal_id']) && !empty($_POST['transmittal_id'])) {
            $userID = $_SESSION['user_id'];
            $transmittalID = $_POST['transmittal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);
            $receiverID = $transmittalDetails['receiver_id'];
            $receiverDepartment = $transmittalDetails['receiver_department'];
            $transmittalStatus = $transmittalDetails['transmittal_status'];

            $employeeDetails = $this->employeeModel->getPersonalInformation($receiverID);
            $receiverName = $employeeDetails['file_as'] ?? null;

            $departmentname = $this->departmentModel->getDepartment($receiverDepartment)['department_name'] ?? null;

            $transmittalStatusBadge = $this->transmittalModel->getTransmittalStatus($transmittalStatus);

            $response = [
                'success' => true,
                'transmittalDescription' => $transmittalDetails['transmittal_description'],
                'receiverID' => $receiverID,
                'receiverDepartment' => $receiverDepartment,
                'departmentName' => $departmentname,
                'receiverName' => $receiverName,
                'transmittalStatusBadge' => $transmittalStatusBadge
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
require_once '../model/transmittal-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new TransmittalController(new TransmittalModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new DepartmentModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>