<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeaveApplicationController
# Description: 
# The LeaveApplicationController class handles leave application related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeaveApplicationController {
    private $leaveApplicationModel;
    private $employeeModel;
    private $leaveTypeModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeaveApplicationModel, UserModel and SecurityModel instances.
    # These instances are used for leave application related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeaveApplicationModel $leaveApplicationModel     The LeaveApplicationModel instance for leave application related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeaveApplicationModel $leaveApplicationModel, EmployeeModel $employeeModel, LeaveTypeModel $leaveTypeModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, UserModel $userModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leaveApplicationModel = $leaveApplicationModel;
        $this->userModel = $userModel;
        $this->employeeModel = $employeeModel;
        $this->leaveTypeModel = $leaveTypeModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemModel = $systemModel;
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
                case 'save leave application':
                    $this->saveLeaveApplication();
                    break;
                case 'leave application for recommendation':
                    $this->leaveApplicationForRecommendation();
                    break;
                case 'leave application recommendation':
                    $this->leaveApplicationRecommendation();
                    break;
                case 'leave application approved':
                    $this->leaveApplicationApproval();
                    break;
                case 'leave application reject':
                    $this->leaveApplicationReject();
                    break;
                case 'leave application cancel':
                    $this->leaveApplicationCancellation();
                    break;
                case 'leave application approval':
                    $this->leaveApplicationApproval();
                    break;
                case 'get leave application details':
                    $this->getLeaveApplicationDetails();
                    break;
                case 'delete leave application':
                    $this->deleteLeaveApplication();
                    break;
                case 'delete multiple leave application':
                    $this->deleteMultipleLeaveApplication();
                    break;
                case 'approve multiple leave':
                    $this->approveMultipleLeaveApplication();
                    break;
                case 'save leave form image':
                    $this->saveLeaveForm();
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

    public function saveLeaveForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $leaveApplicationID = htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $leaveFormFileName = $_FILES['leave_form_image']['name'];
            $leaveFormFileSize = $_FILES['leave_form_image']['size'];
            $leaveFormFileError = $_FILES['leave_form_image']['error'];
            $leaveFormTempName = $_FILES['leave_form_image']['tmp_name'];
            $leaveFormFileExtension = explode('.', $leaveFormFileName);
            $leaveFormActualFileExtension = strtolower(end($leaveFormFileExtension));

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $clientleaveForm = !empty($leaveApplicationDetails['leave_form']) ? '.' . $leaveApplicationDetails['leave_form'] : null;
    
            if(file_exists($clientleaveForm)){
                if (!unlink($clientleaveForm)) {
                    echo json_encode(['success' => false, 'message' => 'Leave form cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(15);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(15);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($leaveFormActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($leaveFormTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the leave form.']);
                exit;
            }
            
            if($leaveFormFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($leaveFormFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The leave form exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $leaveFormActualFileExtension;

            $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE.'/leave_form/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . '/leave_form/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($leaveFormTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->leaveApplicationModel->updateLeaveForm($leaveApplicationID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The leave application does not exists.']);
            exit;
        }
    }

    # -------------------------------------------------------------
    #
    # Function: saveLeaveApplication
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $creation_type = $_POST['creation_type'];
        $employee_id = $_POST['employee_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $leaveTypeID = htmlspecialchars($_POST['leave_type_id'], ENT_QUOTES, 'UTF-8');
        $reason  = $_POST['reason'];
        $application_type  = $_POST['application_type'];
        $leaveDate = $this->systemModel->checkDate('empty', $_POST['leave_date'], '', 'Y-m-d', '');
        $leaveStartTime = $this->systemModel->checkDate('empty', $_POST['leave_start_time'], '', 'H:i:s', '');
        $leaveEndTime = $this->systemModel->checkDate('empty', $_POST['leave_end_time'], '', 'H:i:s', '');
        $numberOfHours = round(abs(strtotime($leaveEndTime) - strtotime($leaveStartTime)) / 3600, 2);
    
        if($creation_type == 'own'){
            $contactID = $_SESSION['contact_id'];
        }
        else{
            $contactID = $employee_id;
        }

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($leaveTypeID == '1'){
            $entitlement = $this->leaveApplicationModel->getEmployeeLeaveEntitlement($contactID, $leaveDate)['entitlement_amount'] ?? 0;

            if($application_type === 'Whole Day'){
                $application_amount = 8;
            }
            else{
                $application_amount = 4;
            }

            $entitlement_total = $entitlement - $application_amount;

            if($entitlement == 0 || $entitlement_total < 0){
                echo json_encode(['success' => false, 'entitlementZero' => true]);
                exit;
            }
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplication($leaveApplicationID, $contactID, $leaveTypeID, $application_type, $reason, $leaveDate, $leaveStartTime, $leaveEndTime, $numberOfHours, $creation_type, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leaveApplicationID' => $this->securityModel->encryptData($leaveApplicationID)]);
            exit;
        } 
        else {
            $leaveApplicationID = $this->leaveApplicationModel->insertLeaveApplication($contactID, $leaveTypeID, $application_type, $reason, $leaveDate, $leaveStartTime, $leaveEndTime, $numberOfHours, $creation_type, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leaveApplicationID' => $this->securityModel->encryptData($leaveApplicationID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationForRecommendation
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationForRecommendation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
           

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];
            $application_type = $leaveApplicationDetails['application_type'];
            $leave_date = $leaveApplicationDetails['leave_date'];
            $contact_id = $leaveApplicationDetails['contact_id'];

            if($leaveTypeID == '1'){    
                if($application_type === 'Whole Day'){
                    $application_amount = 8;
                }
                else{
                    $application_amount = 4;
                }
    
                $this->leaveApplicationModel->updateLeaveEntitlementAmount($contact_id, $leaveTypeID, $leave_date, $application_amount, 'decrease', $userID);
            }

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $creation_type = $leaveApplicationDetails['creation_type'] ?? 'own';
            $leave_form = $leaveApplicationDetails['leave_form'] ?? null;

            if($creation_type === 'own'){
                $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'For Recommendation', '', $userID);
            }
            else{
                if(empty($leave_form)){
                    echo json_encode(['success' => false, 'leaveFormEmpty' => true]);
                    exit;
                }

                $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Approved', '', $userID);
            }

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationRecommendation
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationRecommendation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'For Approval', '', $userID);

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationApproval
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];
            $application_type = $leaveApplicationDetails['application_type'];
            $leave_date = $leaveApplicationDetails['leave_date'];
            $contact_id = $leaveApplicationDetails['contact_id'];

            if($leaveTypeID == '1'){
                $entitlement = $this->leaveApplicationModel->getEmployeeLeaveEntitlement($contact_id, $leave_date)['entitlement_amount'] ?? 0;
    
                if($application_type === 'Whole Day'){
                    $application_amount = 8;
                }
                else{
                    $application_amount = 4;
                }
    
                $entitlement_total = $entitlement - $application_amount;
    
                if($entitlement == 0 || $entitlement_total <= 0){
                    echo json_encode(['success' => false, 'entitlementZero' => true]);
                    exit;
                }
            }

            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Approved', '', $userID);

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationReject
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationReject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $rejectionReason = htmlspecialchars($_POST['rejection_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Rejected', $rejectionReason, $userID);

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];
            $application_type = $leaveApplicationDetails['application_type'];
            $leave_date = $leaveApplicationDetails['leave_date'];
            $contact_id = $leaveApplicationDetails['contact_id'];

            if($leaveTypeID == '1'){    
                if($application_type === 'Whole Day'){
                    $application_amount = 8;
                }
                else{
                    $application_amount = 4;
                }
    
                $this->leaveApplicationModel->updateLeaveEntitlementAmount($contact_id, $leaveTypeID, $leave_date, $application_amount, 'increase', $userID);
            }
            
            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: leaveApplicationCancellation
    # Description: 
    # Updates the existing leave application if it exists; otherwise, inserts a new leave application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function leaveApplicationCancellation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leaveApplicationID = isset($_POST['leave_application_id']) ? htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $cancellationReason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;
     
        if ($total > 0) {
            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Cancelled', $cancellationReason, $userID);

            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];
            $application_type = $leaveApplicationDetails['application_type'];
            $leave_date = $leaveApplicationDetails['leave_date'];
            $contact_id = $leaveApplicationDetails['contact_id'];

            if($leaveTypeID == '1'){    
                if($application_type === 'Whole Day'){
                    $application_amount = 8;
                }
                else{
                    $application_amount = 4;
                }
    
                $this->leaveApplicationModel->updateLeaveEntitlementAmount($contact_id, $leaveTypeID, $leave_date, $application_amount, 'increase', $userID);
            }

            echo json_encode(['success' => true]);
        } 
        else {
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveApplication
    # Description: 
    # Delete the leave application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveApplicationID = htmlspecialchars($_POST['leave_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeaveApplicationExist = $this->leaveApplicationModel->checkLeaveApplicationExist($leaveApplicationID);
        $total = $checkLeaveApplicationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leaveApplicationModel->deleteLeaveApplication($leaveApplicationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeaveApplication
    # Description: 
    # Delete the selected leave applications if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveApplicationIDs = $_POST['leave_application_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leaveApplicationIDs as $leaveApplicationID){
            $this->leaveApplicationModel->deleteLeaveApplication($leaveApplicationID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function approveMultipleLeaveApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leaveApplicationIDs = $_POST['leave_application_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leaveApplicationIDs as $leaveApplicationID){
            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];
            $application_type = $leaveApplicationDetails['application_type'];
            $leave_date = $leaveApplicationDetails['leave_date'];
            $contact_id = $leaveApplicationDetails['contact_id'];

            if($leaveTypeID == '1'){
                $entitlement = $this->leaveApplicationModel->getEmployeeLeaveEntitlement($contact_id, $leave_date)['entitlement_amount'] ?? 0;
    
                if($application_type === 'Whole Day'){
                    $application_amount = 8;
                }
                else{
                    $application_amount = 4;
                }
    
                $entitlement_total = $entitlement - $application_amount;
    
                if($entitlement == 0 || $entitlement_total <= 0){
                    continue;
                }
            }

            $this->leaveApplicationModel->updateLeaveApplicationStatus($leaveApplicationID, 'Approved', '', $userID);
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
    # Function: getLeaveApplicationDetails
    # Description: 
    # Handles the retrieval of leave application details such as leave application name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeaveApplicationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leave_application_id']) && !empty($_POST['leave_application_id'])) {
            $userID = $_SESSION['user_id'];
            $leaveApplicationID = $_POST['leave_application_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leaveApplicationDetails = $this->leaveApplicationModel->getLeaveApplication($leaveApplicationID);
            $leaveTypeID = $leaveApplicationDetails['leave_type_id'];

            $leaveTypeDetails = $this->leaveTypeModel->getLeaveType($leaveTypeID);
            $leaveTypeName = $leaveTypeDetails['leave_type_name'] ?? null;

            $response = [
                'success' => true,
                'leaveTypeID' => $leaveTypeID,
                'leaveTypeName' => $leaveTypeName,
                'reason' => $leaveApplicationDetails['reason'],
                'contactID' => $leaveApplicationDetails['contact_id'],
                'status' => $leaveApplicationDetails['status'],
                'applicationType' => $leaveApplicationDetails['application_type'],
                'leaveForm' => $this->systemModel->checkImage($leaveApplicationDetails['leave_form'], 'default'),
                'leaveDate' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_date'], '', 'm/d/Y', ''),
                'leaveStartTime' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_start_time'], '', 'H:i', ''),
                'leaveEndTime' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_end_time'], '', 'H:i', ''),
                'leaveStartTimeLabel' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_start_time'], '', 'h:i a', ''),
                'leaveEndTimeLabel' =>  $this->systemModel->checkDate('empty', $leaveApplicationDetails['leave_end_time'], '', 'h:i a', '')
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
require_once '../model/leave-application-model.php';
require_once '../model/leave-type-model.php';
require_once '../model/employee-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeaveApplicationController(new LeaveApplicationModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new LeaveTypeModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>