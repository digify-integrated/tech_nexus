<?php
session_start();

# -------------------------------------------------------------
#
# Function: LeasingApplicationController
# Description: 
# The LeasingApplicationController class handles leasing application related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class LeasingApplicationController {
    private $leasingApplicationModel;
    private $userModel;
    private $securityModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided LeasingApplicationModel, UserModel and SecurityModel instances.
    # These instances are used for leasing application related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param LeasingApplicationModel $leasingApplicationModel     The LeasingApplicationModel instance for leasing application related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(LeasingApplicationModel $leasingApplicationModel, UserModel $userModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leasingApplicationModel = $leasingApplicationModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->securityModel = $securityModel;
        $this->systemModel = $systemModel;
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
                case 'save leasing application':
                    $this->saveLeasingApplication();
                    break;
                case 'save leasing other charges':
                    $this->saveLeasingOtherCharges();
                    break;
                case 'save leasing rental payment':
                    $this->saveLeasingRentalPayment();
                    break;
                case 'save leasing other charges payment':
                    $this->saveLeasingOtherChargesPayment();
                    break;
                case 'get leasing application details':
                    $this->getLeasingApplicationDetails();
                    break;
                case 'get leasing application repayment details':
                    $this->getLeasingApplicationRepaymentDetails();
                    break;
                case 'delete leasing application':
                    $this->deleteLeasingApplication();
                    break;
                case 'delete leasing other charges':
                    $this->deleteLeasingOtherCharges();
                    break;
                case 'delete leasing collections':
                    $this->deleteLeasingCollections();
                    break;
                case 'delete multiple leasing application':
                    $this->deleteMultipleLeasingApplication();
                    break;
                case 'save leasing application contract image':
                    $this->saveLeasingApplicationContactImage();
                    break;
                case 'tag for approval':
                    $this->tagLeasingApplicationForApproval();
                    break;
                case 'leasing application reject':
                    $this->tagLeasingApplicationReject();
                    break;
                case 'leasing application cancel':
                    $this->tagLeasingApplicationCancel();
                    break;
                case 'leasing application set to draft':
                    $this->tagLeasingApplicationSetToDraft();
                    break;
                case 'leasing application approval':
                    $this->tagLeasingApplicationApprove();
                    break;
                case 'leasing application activation':
                    $this->tagLeasingApplicationActive();
                    break;
                case 'generate schedule':
                    $this->generateSchedule();
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
    # Function: saveLeasingApplication
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = isset($_POST['leasing_application_id']) ? htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8') : null;
        $renewalTag = htmlspecialchars($_POST['renewal_tag'], ENT_QUOTES, 'UTF-8');
        $tenantID = htmlspecialchars($_POST['tenant_id'], ENT_QUOTES, 'UTF-8');
        $propertyID = htmlspecialchars($_POST['property_id'], ENT_QUOTES, 'UTF-8');
        $termLength = htmlspecialchars($_POST['term_length'], ENT_QUOTES, 'UTF-8');
        $termType = htmlspecialchars($_POST['term_type'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $startDate = $this->systemModel->checkDate('empty', $_POST['start_date'], '', 'Y-m-d', '');
        $contractDate = $this->systemModel->checkDate('empty', $_POST['contract_date'], '', 'Y-m-d', '');
        $maturityDate = $this->systemModel->checkDate('empty', $_POST['maturity_date'], '', 'Y-m-d', '');
        $initialBasicRental = htmlspecialchars($_POST['initial_basic_rental'], ENT_QUOTES, 'UTF-8');
        $escalationRate = htmlspecialchars($_POST['escalation_rate'], ENT_QUOTES, 'UTF-8');
        $securityDeposit = htmlspecialchars($_POST['security_deposit'], ENT_QUOTES, 'UTF-8');
        $floorArea = htmlspecialchars($_POST['floor_area'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->leasingApplicationModel->updateLeasingApplication($leasingApplicationID, $tenantID, $propertyID, $termLength, $termType, $paymentFrequency, $renewalTag, $contractDate, $startDate, $maturityDate, $securityDeposit, $floorArea, $initialBasicRental, $escalationRate, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'leasingApplicationID' => $this->securityModel->encryptData($leasingApplicationID)]);
            exit;
        } 
        else {
            $leasingApplicationNumber = $this->systemSettingModel->getSystemSetting(8)['value'] + 1;

            $leasingApplicationID = $this->leasingApplicationModel->insertLeasingApplication($leasingApplicationNumber, $tenantID, $propertyID, $termLength, $termType, $paymentFrequency, $renewalTag, $contractDate, $startDate, $maturityDate, $securityDeposit, $floorArea, $initialBasicRental, $escalationRate, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(8, $leasingApplicationNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'leasingApplicationID' => $this->securityModel->encryptData($leasingApplicationID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveLeasingOtherCharges
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingOtherCharges() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingOtherChargesID = isset($_POST['leasing_other_charges_id']) ? htmlspecialchars($_POST['leasing_other_charges_id'], ENT_QUOTES, 'UTF-8') : null;
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $leasingApplicationRepaymentID = htmlspecialchars($_POST['leasing_application_repayment_id'], ENT_QUOTES, 'UTF-8');
        $otherChargesReferenceNumber = htmlspecialchars($_POST['other_charges_reference_number'], ENT_QUOTES, 'UTF-8');
        $otherChargesType = htmlspecialchars($_POST['other_charges_type'], ENT_QUOTES, 'UTF-8');
        $otherChargesDueAmount = htmlspecialchars($_POST['other_charges_due_amount'], ENT_QUOTES, 'UTF-8');
        $otherChargesDueDate = $this->systemModel->checkDate('empty', $_POST['other_charges_due_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leasingApplicationModel->insertLeasingOtherCharges($leasingApplicationRepaymentID, $leasingApplicationID, $otherChargesType, $otherChargesDueAmount, 0, $otherChargesDueDate, $otherChargesDueAmount, $otherChargesReferenceNumber, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveLeasingRentalPayment
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingRentalPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $leasingApplicationRepaymentID = htmlspecialchars($_POST['leasing_application_repayment_id'], ENT_QUOTES, 'UTF-8');
        $rentPaymentMode = htmlspecialchars($_POST['rental_payment_mode'], ENT_QUOTES, 'UTF-8');
        $rentReferenceNumber = htmlspecialchars($_POST['rental_reference_number'], ENT_QUOTES, 'UTF-8');
        $rentPaymentAmount = htmlspecialchars($_POST['rental_payment_amount'], ENT_QUOTES, 'UTF-8');
        $rentPaymentDate = $this->systemModel->checkDate('empty', $_POST['rental_payment_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leasingApplicationModel->insertLeasingRentalPayment($leasingApplicationRepaymentID, $leasingApplicationID, 'Rent', '', $rentReferenceNumber, $rentPaymentMode, $rentPaymentDate, $rentPaymentAmount, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveLeasingOtherChargesPayment
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingOtherChargesPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $leasingApplicationRepaymentID = htmlspecialchars($_POST['leasing_application_repayment_id'], ENT_QUOTES, 'UTF-8');
        $paymentFor = htmlspecialchars($_POST['payment_for'], ENT_QUOTES, 'UTF-8');
        $paymentID = htmlspecialchars($_POST['payment_id'], ENT_QUOTES, 'UTF-8');
        $otherChargesPaymentMode = htmlspecialchars($_POST['other_charges_payment_mode'], ENT_QUOTES, 'UTF-8');
        $otherChargesReferenceNumber = htmlspecialchars($_POST['other_charges_reference_number'], ENT_QUOTES, 'UTF-8');
        $otherChargesPaymentAmount = htmlspecialchars($_POST['other_charges_payment_amount'], ENT_QUOTES, 'UTF-8');
        $otherChargesPaymentDate = $this->systemModel->checkDate('empty', $_POST['other_charges_payment_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leasingApplicationModel->insertLeasingOtherChargesPayment($leasingApplicationRepaymentID, $leasingApplicationID, $paymentFor, $paymentID, $otherChargesReferenceNumber, $otherChargesPaymentMode, $otherChargesPaymentDate, $otherChargesPaymentAmount, $userID);
        $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
        $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
        $this->leasingApplicationModel->updateLeasingApplicationStatusToClosed();

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveLeasingApplicationContactImage
    # Description: 
    # Updates the existing leasing application if it exists; otherwise, inserts a new leasing application.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveLeasingApplicationContactImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['contract_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['contract_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['contract_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['contract_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $leasingApplicationDetails = $this->leasingApplicationModel->getLeasingApplication($leasingApplicationID);
            $clientoutgoingChecklistImage = !empty($leasingApplicationDetails['contract_image']) ? '.' . $leasingApplicationDetails['contract_image'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Unit image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(17);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(17);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($outgoingChecklistImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($outgoingChecklistImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the unit image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The unit image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_LEASING_APPLICATION_RELATIVE_PATH_FILE.'/contract_image/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_LEASING_APPLICATION_FULL_PATH_FILE . '/contract_image/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($outgoingChecklistImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->leasingApplicationModel->updateLeasingApplicationContactImage($leasingApplicationID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The leasing application does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateSchedule
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function generateSchedule() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->leasingApplicationModel->deleteLeasingApplicationRepayment($leasingApplicationID);

        $leasingApplicationDetails = $this->leasingApplicationModel->getLeasingApplication($leasingApplicationID);

        $leasingApplicationNumber = $leasingApplicationDetails['leasing_application_number'];
        $startDate = $this->systemModel->checkDate('empty', $leasingApplicationDetails['start_date'], '', 'Y-m-d', '');
        $initialBasicRental = $leasingApplicationDetails['initial_basic_rental'] ?? null;
        $escalationRate = $leasingApplicationDetails['escalation_rate'] ?? null;
        $escalationRateDecimal = $escalationRate / 100.0;
        $termLength = $leasingApplicationDetails['term_length'] ?? null;
        $paymentFrequency = $leasingApplicationDetails['payment_frequency'] ?? null;
        $termType = $leasingApplicationDetails['term_type'] ?? null;

        for ($x = 0; $x < $termLength; $x++) {
            if (($x + 1) % 12 == 0 && $x > 0) { // Increase the initial basic rental value every 12 months after the first year
                $initialBasicRental *= (1 + $escalationRateDecimal);
            }
        
            if($x == 0){
                $matdt = $startDate;

                $this->leasingApplicationModel->insertLeasingApplicationRepayment($leasingApplicationID, $leasingApplicationNumber . ' - 0' . ($x + 1), $startDate, $initialBasicRental, $initialBasicRental, $userID);
            }
            else{
                # Get due dates
                $matdt = date('Y-m-d', strtotime(getNextDuedate($matdt, $startDate, $paymentFrequency)));
        
                if(date('d', strtotime($matdt)) == '31'){
                    $maturity = date('Y-m-30', strtotime($matdt));
                }
                else{
                    $maturity = $matdt;
                }

                if($x >= 1 && $x <= 9){
                    $extension = '0' . ($x + 1);
                }
                else{
                    $extension = ($x + 1);
                }
        
                $this->leasingApplicationModel->insertLeasingApplicationRepayment($leasingApplicationID, $leasingApplicationNumber . ' - ' . ($extension), $maturity, $initialBasicRental, $initialBasicRental, $userID);
            }
        }
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationForApproval
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'For Approval', null, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationApprove
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationApprove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $approvalRemarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'Approved', $approvalRemarks, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationFinalApproval
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationActive() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $activationRemarks = htmlspecialchars($_POST['activation_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'Active', $activationRemarks, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationSetToDraft
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationSetToDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $setToDraftReason = htmlspecialchars($_POST['set_to_draft_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'Draft', $setToDraftReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationCancel
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'Cancelled', $cancellationReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagLeasingApplicationReject
    # Description: 
    # Updates the existing leasing application accessories if it exists; otherwise, inserts a new leasing application accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagLeasingApplicationReject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
        $rejectionReason = htmlspecialchars($_POST['rejection_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->updateLeasingApplicationStatus($leasingApplicationID, $contactID, 'Rejected', $rejectionReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingApplication
    # Description: 
    # Delete the leasing application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingApplicationExist = $this->leasingApplicationModel->checkLeasingApplicationExist($leasingApplicationID);
        $total = $checkLeasingApplicationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->deleteLeasingApplication($leasingApplicationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingOtherCharges
    # Description: 
    # Delete the leasing application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeasingOtherCharges() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingOtherChargesID = htmlspecialchars($_POST['leasing_other_charges_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingOtherChargesExist = $this->leasingApplicationModel->checkLeasingOtherChargesExist($leasingOtherChargesID);
        $total = $checkLeasingOtherChargesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leasingApplicationModel->deleteLeasingOtherCharges($leasingOtherChargesID);
        $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
        $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
        $this->leasingApplicationModel->updateLeasingApplicationStatusToClosed();
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingCollections
    # Description: 
    # Delete the leasing application if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteLeasingCollections() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingCollectionsID = htmlspecialchars($_POST['leasing_collections_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkLeasingCollectionExist = $this->leasingApplicationModel->checkLeasingCollectionExist($leasingCollectionsID);
        $total = $checkLeasingCollectionExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $leasingCollectionDetails = $this->leasingApplicationModel->getLeasingCollections($leasingCollectionsID);
        $leasingApplicationRepaymentID = $leasingCollectionDetails['leasing_application_repayment_id'];
        $paymentFor = $leasingCollectionDetails['payment_for'];
        $paymentID = $leasingCollectionDetails['payment_id'];
        $paymentAmount = $leasingCollectionDetails['payment_amount'];

    
        $this->leasingApplicationModel->deleteLeasingCollections($leasingCollectionsID, $leasingApplicationRepaymentID, $paymentFor, $paymentID, $paymentAmount);
        $this->leasingApplicationModel->updateLeasingOtherChargesStatus();
        $this->leasingApplicationModel->updateLeasingApplicationRepaymentStatus();
        $this->leasingApplicationModel->updateLeasingApplicationStatusToClosed();
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleLeasingApplication
    # Description: 
    # Delete the selected leasing applications if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleLeasingApplication() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $leasingApplicationIDs = $_POST['leasing_application_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($leasingApplicationIDs as $leasingApplicationID){
            $this->leasingApplicationModel->deleteLeasingApplication($leasingApplicationID);
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
    # Function: getLeasingApplicationDetails
    # Description: 
    # Handles the retrieval of leasing application details such as leasing application name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leasing_application_id']) && !empty($_POST['leasing_application_id'])) {
            $userID = $_SESSION['user_id'];
            $leasingApplicationID = $_POST['leasing_application_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leasingApplicationDetails = $this->leasingApplicationModel->getLeasingApplication($leasingApplicationID);

            $unpaidRental = $this->leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Rental')['total'];
            $unpaidElectricity = $this->leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Electricity')['total'];
            $unpaidWater = $this->leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Water')['total'];
            $unpaidOtherCharges = $this->leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Other Charges')['total'];
            $outstandingBalance = $this->leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Outstanding Balance')['total'];

            $response = [
                'success' => true,
                'leasingApplicationNumber' => $leasingApplicationDetails['leasing_application_number'],
                'tenantID' => $leasingApplicationDetails['tenant_id'],
                'propertyID' => $leasingApplicationDetails['property_id'],
                'termLength' => $leasingApplicationDetails['term_length'],
                'termType' => $leasingApplicationDetails['term_type'],
                'paymentFrequency' => $leasingApplicationDetails['payment_frequency'],
                'renewalTag' => $leasingApplicationDetails['renewal_tag'],
                'remarks' => $leasingApplicationDetails['remarks'],
                'securityDeposit' => $leasingApplicationDetails['security_deposit'],
                'floorArea' => $leasingApplicationDetails['floor_area'],
                'initialBasicRental' => $leasingApplicationDetails['initial_basic_rental'],
                'escalationRate' => $leasingApplicationDetails['escalation_rate'],
                'activationRemarks' => $leasingApplicationDetails['activation_remarks'],
                'setToDraftReason' => $leasingApplicationDetails['set_to_draft_reason'],
                'rejectionReason' => $leasingApplicationDetails['rejection_reason'],
                'cancellationReason' => $leasingApplicationDetails['cancellation_reason'],
                'approvalRemarks' => $leasingApplicationDetails['approval_remarks'],
                'unpaidRental' => number_format($unpaidRental, 2) . ' Php',
                'unpaidElectricity' => number_format($unpaidElectricity, 2) . ' Php',
                'unpaidWater' => number_format($unpaidWater, 2) . ' Php',
                'unpaidOtherCharges' => number_format($unpaidOtherCharges, 2) . ' Php',
                'outstandingBalance' => number_format($outstandingBalance, 2) . ' Php',
                'contractImage' => $this->systemModel->checkImage($leasingApplicationDetails['contract_image'], 'default'),
                'startDate' =>  $this->systemModel->checkDate('empty', $leasingApplicationDetails['start_date'], '', 'm/d/Y', ''),
                'contractDate' =>  $this->systemModel->checkDate('empty', $leasingApplicationDetails['contract_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingApplicationRepaymentDetails
    # Description: 
    # Handles the retrieval of leasing application details such as leasing application name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationRepaymentDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['leasing_application_repayment_id']) && !empty($_POST['leasing_application_repayment_id'])) {
            $userID = $_SESSION['user_id'];
            $leasingApplicationRepaymentID = $_POST['leasing_application_repayment_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $leasingApplicationRepaymentDetails = $this->leasingApplicationModel->getLeasingApplicationRepayment($leasingApplicationRepaymentID);

            $response = [
                'success' => true,
                'unpaidRental' => number_format($leasingApplicationRepaymentDetails['unpaid_rental'], 2) . ' Php',
                'paidRental' => number_format($leasingApplicationRepaymentDetails['paid_rental'], 2) . ' Php',
                'unpaidElectricity' => number_format($leasingApplicationRepaymentDetails['unpaid_electricity'], 2) . ' Php',
                'paidElectricity' => number_format($leasingApplicationRepaymentDetails['paid_electricity'], 2) . ' Php',
                'unpaidWater' => number_format($leasingApplicationRepaymentDetails['unpaid_water'], 2) . ' Php',
                'paidWater' => number_format($leasingApplicationRepaymentDetails['paid_water'], 2) . ' Php',
                'unpaidOtherCharges' => number_format($leasingApplicationRepaymentDetails['unpaid_other_charges'], 2) . ' Php',
                'paidOtherCharges' => number_format($leasingApplicationRepaymentDetails['paid_other_charges'], 2) . ' Php',
                'outstandingBalance' => number_format($leasingApplicationRepaymentDetails['outstanding_balance'], 2) . ' Php',
                'dueDate' =>  $this->systemModel->checkDate('empty', $leasingApplicationRepaymentDetails['due_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------


function getNextDuedate($prevdate, $maturitydate, $frequency){
    $matdteom = check_end_of_month($maturitydate);
    $maturityday = date('d', strtotime($maturitydate));

    $prevdate = getCalculatedDate($prevdate, 1);
    $prevlastday = date('t', strtotime($prevdate));

    if($prevlastday < $maturityday){
        $duedate = $prevdate;
    }
    else{
        $duedate = date('m', strtotime($prevdate)) . '/'. date('d', strtotime($maturitydate)) .'/' . date('Y', strtotime($prevdate));
    }
        
    $flag = '1';

    if($matdteom == '1' && $flag == '1'){
        $duedate = date('m/t/Y', strtotime($duedate));
    }
    
    return $duedate;
}

function check_end_of_month($date){
    //adds 1 day to date
    $Temp = date('m/d/Y',strtotime("+1 day", strtotime($date)));

    //get the month of each date
    $tempmonth = date('m', strtotime($Temp));
    $datemonth = date('m', strtotime($date));
    
    //check if the months are equal
    if($tempmonth != $datemonth){
        return '1';
    }
    else{
        return '0';
    }
}

function getCalculatedDate($d1, $months){
    $date = new DateTime($d1);

    # call add_months function to add the months
    $newDate = $date->add(add_months($months, $date));

    #formats final date to m/d/Y form
    $dateReturned = $newDate->format('m/d/Y'); 

    return $dateReturned;
}

function add_months($months, DateTime $dateObject){
    #format date to Y-m-d
    #get the last day of the given month
    $next = new DateTime($dateObject->format('Y-m-d'));
    $next->modify('last day of +'.$months.' month');

    #if $dateObject day is greater than the day of $next
    #return the difference
    #else create a new interval
    if($dateObject->format('d') > $next->format('d')) {
        return $dateObject->diff($next);
    } else {
        return new DateInterval('P'.$months.'M');
    }
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/leasing-application-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new LeasingApplicationController(new LeasingApplicationModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>