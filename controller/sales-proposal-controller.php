<?php
session_start();

# -------------------------------------------------------------
#
# Function: CustomerController
# Description: 
# The CustomerController class handles customer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class SalesProposalController {
    private $salesProposalModel;
    private $customerModel;
    private $productModel;
    private $userModel;
    private $systemSettingModel;
    private $companyModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CustomerModel, UserModel and SecurityModel instances.
    # These instances are used for customer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param SalesProposalModel $salesProposalModel     The SalesProposalModel instance for sales proposal related operations.
    # - @param CustomerModel $customerModel     The CustomerModel instance for customer related operations.
    # - @param ProductModel $productModel     The ProductModel instance for product related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(SalesProposalModel $salesProposalModel, CustomerModel $customerModel, ProductModel $productModel, UserModel $userModel, CompanyModel $companyModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->salesProposalModel = $salesProposalModel;
        $this->customerModel = $customerModel;
        $this->productModel = $productModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
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
            $transaction = isset($_POST['transaction']) ? trim($_POST['transaction']) : null;

            switch ($transaction) {
                case 'save sales proposal':
                    $this->saveSalesProposal();
                    break;
                case 'save sales proposal accessories':
                    $this->saveSalesProposalAccessories();
                    break;
                case 'save sales proposal job order':
                    $this->saveSalesProposalJobOrder();
                    break;
                case 'save sales proposal additional job order':
                    $this->saveSalesProposalAdditionalJobOrder();
                    break;
                case 'save sales proposal pricing computation':
                    $this->saveSalesProposalPricingComputation();
                    break;
                case 'save sales proposal other charges':
                    $this->saveSalesProposalOtherCharges();
                    break;
                case 'save sales proposal other product details':
                    $this->saveSalesProposalOtherProductDetails();
                    break;
                case 'save sales proposal renewal amount':
                    $this->saveSalesProposalRenewalAmount();
                    break;
                case 'save sales proposal deposit amount':
                    $this->saveSalesProposalDepositAmount();
                    break;
                case 'tag for initial approval':
                    $this->tagSalesProposalForInitialApproval();
                    break;
                case 'tag for ci':
                    $this->tagSalesProposalForCI();
                    break;
                case 'complete ci':
                    $this->tagCIAsComplete();
                    break;
                case 'sales proposal initial approval':
                    $this->tagSalesProposalInitialApproval();
                    break;
                case 'sales proposal final approval':
                    $this->tagSalesProposalFinalApproval();
                    break;
                case 'sales proposal reject':
                    $this->tagSalesProposalReject();
                    break;
                case 'sales proposal cancel':
                    $this->tagSalesProposalCancel();
                    break;
                case 'sales proposal set to draft':
                    $this->tagSalesProposalSetToDraft();
                    break;
                case 'tag on process':
                    $this->tagSalesProposalOnProcess();
                    break;
                case 'tag ready for release':
                    $this->tagSalesProposalReadyForRelease();
                    break;
                case 'tag for DR':
                    $this->tagSalesProposalForDR();
                    break;
                case 'delete sales proposal accessories':
                    $this->deleteSalesProposalAccessories();
                    break;
                case 'delete sales proposal job order':
                    $this->deleteSalesProposalJobOrder();
                    break;
                case 'delete sales proposal additional job order':
                    $this->deleteSalesProposalAdditionalJobOrder();
                    break;
                case 'delete sales proposal deposit amount':
                    $this->deleteSalesProposalDepositAmount();
                    break;
                case 'get sales proposal details':
                    $this->getSalesProposalDetails();
                    break;
                case 'get sales proposal accessories details':
                    $this->getSalesProposalAccessoriesDetails();
                    break;
                case 'get sales proposal accessories total details':
                    $this->getSalesProposalAccessoriesTotalDetails();
                    break;
                case 'get sales proposal job order details':
                    $this->getSalesProposalJobOrderDetails();
                    break;
                case 'get sales proposal job order total details':
                    $this->getSalesProposalJobOrderTotalDetails();
                    break;
                case 'get sales proposal additional job order details':
                    $this->getSalesProposalAdditionalJobOrderDetails();
                    break;
                case 'get sales proposal additional job order total details':
                    $this->getSalesProposalAdditionalJobOrderTotalDetails();
                    break;
                case 'get sales proposal deposit amount details':
                    $this->getSalesProposalDepositAmountDetails();
                    break;
                case 'get sales proposal pricing computation details':
                    $this->getSalesProposalPricingComputationDetails();
                    break;
                case 'get sales proposal other charges details':
                    $this->getSalesProposalOtherChargesDetails();
                    break;
                case 'get sales proposal other product details':
                    $this->getSalesProposalOtherProductDetails();
                    break;
                case 'get sales proposal renewal amount details':
                    $this->getSalesProposalRenewalAmountDetails();
                    break;
                case 'save sales proposal client confirmation':
                    $this->saveSalesProposalClientConfirmation();
                    break;
                case 'save sales proposal quality control form':
                    $this->saveSalesProposalQualityControlForm();
                    break;
                case 'save sales proposal outgoing checklist':
                    $this->saveSalesProposalOutgoingChecklist();
                    break;
                case 'save sales proposal unit image':
                    $this->saveSalesProposalUnitImage();
                    break;
                case 'save sales proposal additional job order confirmation':
                    $this->saveSalesProposalAdditionalJobOrderConfirmationImage();
                    break;
                case 'save sales proposal credit advice':
                    $this->saveSalesProposalCreditAdvice();
                    break;
                case 'save sales proposal new engine stencil':
                    $this->saveSalesProposalNewEngineStencil();
                    break;

                   
                case 'save sales proposal pdc manual input':
                    $this->saveSalesProposalPDCManualInput();
                    break; 
                case 'delete sales proposal pdc manual input':
                    $this->deleteSalesProposalPDCManualInput();
                    break;
                case 'tag for release':
                    $this->tagSalesProposalAsReleased();
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
    # Function: saveSalesProposal
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = isset($_POST['sales_proposal_id']) ? $_POST['sales_proposal_id'] : null;
        $customerID = $_POST['customer_id'];
        $fuelTypes = $_POST['fuel_type'] ?? null;
        $productType = htmlspecialchars($_POST['product_type'], ENT_QUOTES, 'UTF-8');
        $renewalTag = htmlspecialchars($_POST['renewal_tag'], ENT_QUOTES, 'UTF-8');
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $fuelQuantity = htmlspecialchars($_POST['fuel_quantity'], ENT_QUOTES, 'UTF-8');
        $pricePerLiter = htmlspecialchars($_POST['price_per_liter'], ENT_QUOTES, 'UTF-8');
        $commissionAmount = htmlspecialchars($_POST['commission_amount'], ENT_QUOTES, 'UTF-8');
        $transactionType = htmlspecialchars($_POST['transaction_type'], ENT_QUOTES, 'UTF-8');
        $financingInstitution = htmlspecialchars($_POST['financing_institution'], ENT_QUOTES, 'UTF-8');
        $comakerID = htmlspecialchars($_POST['comaker_id'], ENT_QUOTES, 'UTF-8');
        $referredBy = htmlspecialchars($_POST['referred_by'], ENT_QUOTES, 'UTF-8');
        $releaseDate = $this->systemModel->checkDate('empty', $_POST['release_date'], '', 'Y-m-d', '');
        $startDate = $this->systemModel->checkDate('empty', $_POST['start_date'], '', 'Y-m-d', '');
        $firstDueDate = $this->systemModel->checkDate('empty', $_POST['first_due_date'], '', 'Y-m-d', '');
        $termLength = htmlspecialchars($_POST['term_length'], ENT_QUOTES, 'UTF-8');
        $termType = htmlspecialchars($_POST['term_type'], ENT_QUOTES, 'UTF-8');
        $numberOfPayments = htmlspecialchars($_POST['number_of_payments'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $forRegistration = htmlspecialchars($_POST['for_registration'], ENT_QUOTES, 'UTF-8');
        $withCR = htmlspecialchars($_POST['with_cr'], ENT_QUOTES, 'UTF-8');
        $forTransfer = htmlspecialchars($_POST['for_transfer'], ENT_QUOTES, 'UTF-8');
        $forChangeColor = htmlspecialchars($_POST['for_change_color'], ENT_QUOTES, 'UTF-8');
        $newColor = htmlspecialchars($_POST['new_color'], ENT_QUOTES, 'UTF-8');
        $forChangeBody = htmlspecialchars($_POST['for_change_body'], ENT_QUOTES, 'UTF-8');
        $newBody = htmlspecialchars($_POST['new_body'], ENT_QUOTES, 'UTF-8');
        $forChangeEngine = htmlspecialchars($_POST['for_change_engine'], ENT_QUOTES, 'UTF-8');
        $newEngine  = htmlspecialchars($_POST['new_engine'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');
        $initialApprovingOfficer = htmlspecialchars($_POST['initial_approving_officer'], ENT_QUOTES, 'UTF-8');
        $finalApprovingOfficer = htmlspecialchars($_POST['final_approving_officer'], ENT_QUOTES, 'UTF-8');
        $refEngineNo = htmlspecialchars($_POST['ref_engine_no'], ENT_QUOTES, 'UTF-8');
        $refChassisNo = htmlspecialchars($_POST['ref_chassis_no'], ENT_QUOTES, 'UTF-8');
        $refPlateNo = htmlspecialchars($_POST['ref_plate_no'], ENT_QUOTES, 'UTF-8');

        $fuelType = '';
        if(!empty($fuelTypes)){
            $counter = 1;
            foreach ($fuelTypes as $fuelTypez) {
                $fuelType .= $fuelTypez;
                if ($counter < count($fuelTypes)) {
                    $fuelType .= ", ";
                }
                $counter++;
            }
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $refStockNo = '';
            if($productType == 'Refinancing'){
                $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
                
                $refStockNo = 'REF' . $salesProposalDetails['sales_proposal_number'];
            }
            
            $this->salesProposalModel->updateSalesProposal($salesProposalID, $customerID, $comakerID, $productID, $productType, $fuelType, $fuelQuantity, $pricePerLiter, $commissionAmount, $transactionType, $financingInstitution, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $forChangeColor, $newColor, $forChangeBody, $newBody, $forChangeEngine, $newEngine, $remarks, $initialApprovingOfficer, $finalApprovingOfficer, $renewalTag, $refStockNo, $refEngineNo, $refChassisNo, $refPlateNo, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        } 
        else {
            $salesProposalNumber = $this->systemSettingModel->getSystemSetting(6)['value'] + 1;

            $refStockNo = '';
            if($productType == 'Refinancing'){
                $refStockNo = 'REF' . $salesProposalNumber;
            }

            $salesProposalID = $this->salesProposalModel->insertSalesProposal($salesProposalNumber, $customerID, $comakerID, $productID, $productType, $fuelType, $fuelQuantity, $pricePerLiter, $commissionAmount, $transactionType, $financingInstitution, $referredBy, $releaseDate, $startDate, $firstDueDate, $termLength, $termType, $numberOfPayments, $paymentFrequency, $forRegistration, $withCR, $forTransfer, $forChangeColor, $newColor, $forChangeBody, $newBody, $forChangeEngine, $newEngine, $remarks, $contactID, $initialApprovingOfficer, $finalApprovingOfficer, $renewalTag, $refStockNo, $refEngineNo, $refChassisNo, $refPlateNo, $userID);

            $this->systemSettingModel->updateSystemSettingValue(6, $salesProposalNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'customerID' => $this->securityModel->encryptData($customerID), 'salesProposalID' => $this->securityModel->encryptData($salesProposalID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalClientConfirmation
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalClientConfirmation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $confirmationImageFileName = $_FILES['client_confirmation_image']['name'];
            $confirmationImageFileSize = $_FILES['client_confirmation_image']['size'];
            $confirmationImageFileError = $_FILES['client_confirmation_image']['error'];
            $confirmationImageTempName = $_FILES['client_confirmation_image']['tmp_name'];
            $confirmationImageFileExtension = explode('.', $confirmationImageFileName);
            $confirmationImageActualFileExtension = strtolower(end($confirmationImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientConfirmationImage = !empty($salesProposalDetails['client_confirmation']) ? '.' . $salesProposalDetails['client_confirmation'] : null;
    
            if(file_exists($clientConfirmationImage)){
                if (!unlink($clientConfirmationImage)) {
                    echo json_encode(['success' => false, 'message' => 'Client confirmation image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(11);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(11);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($confirmationImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($confirmationImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the client confirmation image.']);
                exit;
            }
            
            if($confirmationImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($confirmationImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The client confirmation image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $confirmationImageActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/client_confirmation/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/client_confirmation/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($confirmationImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->salesProposalModel->updateSalesProposalClientConfirmation($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalQualityControlForm
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalQualityControlForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $qualityControlImageFileName = $_FILES['quality_control_image']['name'];
            $qualityControlImageFileSize = $_FILES['quality_control_image']['size'];
            $qualityControlImageFileError = $_FILES['quality_control_image']['error'];
            $qualityControlImageTempName = $_FILES['quality_control_image']['tmp_name'];
            $qualityControlImageFileExtension = explode('.', $qualityControlImageFileName);
            $qualityControlImageActualFileExtension = strtolower(end($qualityControlImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientqualityControlImage = !empty($salesProposalDetails['quality_control_form']) ? '.' . $salesProposalDetails['quality_control_form'] : null;
    
            if(file_exists($clientqualityControlImage)){
                if (!unlink($clientqualityControlImage)) {
                    echo json_encode(['success' => false, 'message' => 'Quality control form image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(14);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(14);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($qualityControlImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($qualityControlImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the quality control form image.']);
                exit;
            }
            
            if($qualityControlImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($qualityControlImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The quality control form image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $qualityControlImageActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/quality_control_form/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/quality_control_form/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($qualityControlImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->salesProposalModel->updateSalesProposalQualityControlForm($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalOutgoingChecklist
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalOutgoingChecklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['outgoing_checklist_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['outgoing_checklist_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['outgoing_checklist_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['outgoing_checklist_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientoutgoingChecklistImage = !empty($salesProposalDetails['outgoing_checklist']) ? '.' . $salesProposalDetails['outgoing_checklist'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Outgoing checklist form image cannot be deleted due to an error.']);
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

            if (!in_array($outgoingChecklistImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($outgoingChecklistImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the outgoing checklist image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The outgoing checklist image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/outgoing_checklist/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/outgoing_checklist/' . $fileNew;
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

            $this->salesProposalModel->updateSalesProposalOutgoingChecklist($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalUnitImage
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalUnitImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_image_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_image_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_image_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_image_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientoutgoingChecklistImage = !empty($salesProposalDetails['unit_image']) ? '.' . $salesProposalDetails['unit_image'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Unit image cannot be deleted due to an error.']);
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

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/unit_image/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/unit_image/' . $fileNew;
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

            $this->salesProposalModel->updateSalesProposalUnitImage($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAdditionalJobOrderConfirmationImage
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAdditionalJobOrderConfirmationImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['additional_job_order_confirmation_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['additional_job_order_confirmation_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['additional_job_order_confirmation_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['additional_job_order_confirmation_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientoutgoingChecklistImage = !empty($salesProposalDetails['additional_job_order_confirmation']) ? '.' . $salesProposalDetails['additional_job_order_confirmation'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Additional job order confirmation cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(16);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(16);
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

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/additional_job_order_confirmation/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/additional_job_order_confirmation/' . $fileNew;
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

            $this->salesProposalModel->updateSalesProposalAdditionalJobOrderConfirmationImage($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalNewEngineStencil
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalNewEngineStencil() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $stencilImageFileName = $_FILES['new_engine_stencil_image']['name'];
            $stencilImageFileSize = $_FILES['new_engine_stencil_image']['size'];
            $stencilImageFileError = $_FILES['new_engine_stencil_image']['error'];
            $stencilImageTempName = $_FILES['new_engine_stencil_image']['tmp_name'];
            $stencilImageFileExtension = explode('.', $stencilImageFileName);
            $stencilImageActualFileExtension = strtolower(end($stencilImageFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientstencilImage = !empty($salesProposalDetails['new_engine_stencil']) ? '.' . $salesProposalDetails['new_engine_stencil'] : null;
    
            if(file_exists($clientstencilImage)){
                if (!unlink($clientstencilImage)) {
                    echo json_encode(['success' => false, 'message' => 'New engine stencil image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(13);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(13);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($stencilImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($stencilImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the new engine stencil image.']);
                exit;
            }
            
            if($stencilImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($stencilImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The new engine stencil image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $stencilImageActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/new_engine_stencil/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/new_engine_stencil/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($stencilImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->salesProposalModel->updateSalesProposalStencil($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalCreditAdvice
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalCreditAdvice() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if ($total > 0) {
            $creditAdviceFileName = $_FILES['credit_advice_image']['name'];
            $creditAdviceFileSize = $_FILES['credit_advice_image']['size'];
            $creditAdviceFileError = $_FILES['credit_advice_image']['error'];
            $creditAdviceTempName = $_FILES['credit_advice_image']['tmp_name'];
            $creditAdviceFileExtension = explode('.', $creditAdviceFileName);
            $creditAdviceActualFileExtension = strtolower(end($creditAdviceFileExtension));

            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $clientcreditAdvice = !empty($salesProposalDetails['credit_advice']) ? '.' . $salesProposalDetails['credit_advice'] : null;;
    
            if(file_exists($clientcreditAdvice)){
                if (!unlink($clientcreditAdvice)) {
                    echo json_encode(['success' => false, 'message' => 'Credit advice image cannot be deleted due to an error.']);
                    exit;
                }
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(12);
            $maxFileSize = $uploadSetting['max_file_size'];

            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(12);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }

            if (!in_array($creditAdviceActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($creditAdviceTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the credit advice image.']);
                exit;
            }
            
            if($creditAdviceFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($creditAdviceFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The credit advice image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $creditAdviceActualFileExtension;

            $directory = DEFAULT_SALES_PROPOSAL_RELATIVE_PATH_FILE.'/credit_advice/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_SALES_PROPOSAL_FULL_PATH_FILE . '/credit_advice/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($creditAdviceTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->salesProposalModel->updateSalesProposalCreditAdvice($salesProposalID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAccessories
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $accessories = htmlspecialchars($_POST['accessories'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['accessories_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAccessories($salesProposalAccessoriesID, $salesProposalID, $accessories, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAccessories($salesProposalID, $accessories, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalForInitialApproval
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalForInitialApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        /*$salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
        $forChangeEngine = $salesProposalDetails['for_change_engine'] ?? 'No';
        $newEngineStencil = $salesProposalDetails['new_engine_stencil'] ?? null;
        $clientstencilImage = !empty($newEngineStencil) ? '.' . $newEngineStencil : null;

        if($forChangeEngine == 'Yes' && (empty($newEngineStencil) || (!empty($newEngineStencil) && !file_exists($clientstencilImage)))){            
            echo json_encode(['success' => false, 'emptyStencil' => true]);
            exit;
        }*/
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'For Initial Approval', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalForCI
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalForCI() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'For CI', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagCIAsComplete
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagCIAsComplete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalCIStatus($salesProposalID, 'Completed', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalInitialApproval
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalInitialApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $initialApprovalRemarks = htmlspecialchars($_POST['initial_approval_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'For Final Approval', $initialApprovalRemarks, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalFinalApproval
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalFinalApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $finalApprovalRemarks = htmlspecialchars($_POST['final_approval_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'Proceed', $finalApprovalRemarks, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalSetToDraft
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalSetToDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $setToDraftReason = htmlspecialchars($_POST['set_to_draft_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'Draft', $setToDraftReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalOnProcess
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalOnProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'On-Process', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalReadyForRelease
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalReadyForRelease() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'Ready For Release', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalForDR
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalForDR() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'For DR', '', $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalAsReleased
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $releaseRemarks = htmlspecialchars($_POST['release_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $loanNumber = $this->systemSettingModel->getSystemSetting(7)['value'] + 1;
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalAsReleased($salesProposalID, $loanNumber, 'Released', $releaseRemarks, $userID);

        $this->systemSettingModel->updateSystemSettingValue(7, $loanNumber, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalCancel
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'Cancelled', $cancellationReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagSalesProposalReject
    # Description: 
    # Updates the existing sales proposal accessories if it exists; otherwise, inserts a new sales proposal accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagSalesProposalReject() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $rejectionReason = htmlspecialchars($_POST['rejection_reason'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalExist = $this->salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->updateSalesProposalStatus($salesProposalID, $contactID, 'Rejected', $rejectionReason, $userID);
            
        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrder = htmlspecialchars($_POST['job_order'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['job_order_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalJobOrder($salesProposalJobOrderID, $salesProposalID, $jobOrder, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalJobOrder($salesProposalID, $jobOrder, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalAdditionalJobOrder
    # Description: 
    # Updates the existing sales proposal job order if it exists; otherwise, inserts a new sales proposal job order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $jobOrderNumber = htmlspecialchars($_POST['job_order_number'], ENT_QUOTES, 'UTF-8');
        $jobOrderDate = $this->systemModel->checkDate('empty', $_POST['job_order_date'], '', 'Y-m-d', '');
        $particulars = htmlspecialchars($_POST['particulars'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['additional_job_order_cost'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID, $salesProposalID, $jobOrderNumber, $jobOrderDate, $particulars, $cost, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalAdditionalJobOrder($salesProposalID, $jobOrderNumber, $jobOrderDate, $particulars, $cost, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalPricingComputation
    # Description: 
    # Updates the existing sales proposal pricing computation if it exists; otherwise, inserts a new sales proposal pricing computation.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalPricingComputation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $deliverPrice = htmlspecialchars($_POST['delivery_price'], ENT_QUOTES, 'UTF-8');
        $nominalDiscount = htmlspecialchars($_POST['nominal_discount'], ENT_QUOTES, 'UTF-8');
        $addOnCharge = htmlspecialchars($_POST['add_on_charge'], ENT_QUOTES, 'UTF-8');
        $totalDeliveryPrice = htmlspecialchars($_POST['total_delivery_price'], ENT_QUOTES, 'UTF-8');
        $costOfAccessories = htmlspecialchars($_POST['cost_of_accessories'], ENT_QUOTES, 'UTF-8');
        $reconditioningCost = htmlspecialchars($_POST['reconditioning_cost'], ENT_QUOTES, 'UTF-8');
        $subtotal = htmlspecialchars($_POST['subtotal'], ENT_QUOTES, 'UTF-8');
        $downpayment = htmlspecialchars($_POST['downpayment'], ENT_QUOTES, 'UTF-8');
        $outstandingBalance = htmlspecialchars($_POST['outstanding_balance'], ENT_QUOTES, 'UTF-8');
        $amountFinanced = htmlspecialchars($_POST['amount_financed'], ENT_QUOTES, 'UTF-8');
        $pnAmount = htmlspecialchars($_POST['pn_amount'], ENT_QUOTES, 'UTF-8');
        $repaymentAmount = htmlspecialchars($_POST['repayment_amount'], ENT_QUOTES, 'UTF-8');
        $interestRate = htmlspecialchars($_POST['interest_rate'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalPricingComputationExist = $this->salesProposalModel->checkSalesProposalPricingComputationExist($salesProposalID);
        $total = $checkSalesProposalPricingComputationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalPricingComputation($salesProposalID, $deliverPrice, $costOfAccessories, $reconditioningCost, $subtotal, $downpayment, $outstandingBalance, $amountFinanced, $pnAmount, $repaymentAmount, $interestRate, $nominalDiscount, $totalDeliveryPrice, $addOnCharge, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalPricingComputation($salesProposalID, $deliverPrice, $costOfAccessories, $reconditioningCost, $subtotal, $downpayment, $outstandingBalance, $amountFinanced, $pnAmount, $repaymentAmount, $interestRate, $nominalDiscount, $totalDeliveryPrice, $addOnCharge, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalOtherCharges
    # Description: 
    # Updates the existing sales proposal other charges if it exists; otherwise, inserts a new sales proposal other charges.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalOtherCharges() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverage = htmlspecialchars($_POST['insurance_coverage'], ENT_QUOTES, 'UTF-8');
        $insurancePremium = htmlspecialchars($_POST['insurance_premium'], ENT_QUOTES, 'UTF-8');
        $handlingFee = htmlspecialchars($_POST['handling_fee'], ENT_QUOTES, 'UTF-8');
        $transferFee = htmlspecialchars($_POST['transfer_fee'], ENT_QUOTES, 'UTF-8');
        $registrationFee = htmlspecialchars($_POST['registration_fee'], ENT_QUOTES, 'UTF-8');
        $docStampTax = htmlspecialchars($_POST['doc_stamp_tax'], ENT_QUOTES, 'UTF-8');
        $transactionFee = htmlspecialchars($_POST['transaction_fee'], ENT_QUOTES, 'UTF-8');
        $totalOtherCharges = htmlspecialchars($_POST['total_other_charges'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalPricingOtherChargesExist = $this->salesProposalModel->checkSalesProposalPricingOtherChargesExist($salesProposalID);
        $total = $checkSalesProposalPricingOtherChargesExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalOtherCharges($salesProposalID, $insuranceCoverage, $insurancePremium, $handlingFee, $transferFee, $registrationFee, $docStampTax, $transactionFee, $totalOtherCharges, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalOtherCharges($salesProposalID, $insuranceCoverage, $insurancePremium, $handlingFee, $transferFee, $registrationFee, $docStampTax, $transactionFee, $totalOtherCharges, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalOtherProductDetails
    # Description: 
    # Updates the existing sales proposal other charges if it exists; otherwise, inserts a new sales proposal other charges.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalOtherProductDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $yearModel = htmlspecialchars($_POST['year_model'], ENT_QUOTES, 'UTF-8');
        $crNo = htmlspecialchars($_POST['cr_no'], ENT_QUOTES, 'UTF-8');
        $mvFileNo = htmlspecialchars($_POST['mv_file_no'], ENT_QUOTES, 'UTF-8');
        $make = htmlspecialchars($_POST['make'], ENT_QUOTES, 'UTF-8');
        $productDescription = htmlspecialchars($_POST['product_description'], ENT_QUOTES, 'UTF-8');
        $drNumber = htmlspecialchars($_POST['dr_number'], ENT_QUOTES, 'UTF-8');
        $startDate = $this->systemModel->checkDate('empty', $_POST['actual_start_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalOtherProductDetailsExist = $this->salesProposalModel->checkSalesProposalOtherProductDetailsExist($salesProposalID);
        $total = $checkSalesProposalOtherProductDetailsExist['total'] ?? 0;

        $this->salesProposalModel->updateSalesProposalActualStartDate($salesProposalID, $drNumber, $startDate, $userID);
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalOtherProductDetails($salesProposalID, $yearModel, $crNo, $mvFileNo, $make, $productDescription, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalOtherProductDetails($salesProposalID, $yearModel, $crNo, $mvFileNo, $make, $productDescription, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalRenewalAmount
    # Description: 
    # Updates the existing sales proposal other charges if it exists; otherwise, inserts a new sales proposal other charges.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalRenewalAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $registrationSecondYear = htmlspecialchars($_POST['registration_second_year'], ENT_QUOTES, 'UTF-8');
        $registrationThirdYear = htmlspecialchars($_POST['registration_third_year'], ENT_QUOTES, 'UTF-8');
        $registrationFourthYear = htmlspecialchars($_POST['registration_fourth_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageSecondYear = htmlspecialchars($_POST['insurance_coverage_second_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageThirdYear = htmlspecialchars($_POST['insurance_coverage_third_year'], ENT_QUOTES, 'UTF-8');
        $insuranceCoverageFourthYear = htmlspecialchars($_POST['insurance_coverage_fourth_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumSecondYear = htmlspecialchars($_POST['insurance_premium_second_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumThirdYear = htmlspecialchars($_POST['insurance_premium_third_year'], ENT_QUOTES, 'UTF-8');
        $insurancePremiumFourthYear = htmlspecialchars($_POST['insurance_premium_fourth_year'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalRenewalAmountExist = $this->salesProposalModel->checkSalesProposalRenewalAmountExist($salesProposalID);
        $total = $checkSalesProposalRenewalAmountExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalRenewalAmount($salesProposalID, $registrationSecondYear, $registrationThirdYear, $registrationFourthYear, $insuranceCoverageSecondYear, $insuranceCoverageThirdYear, $insuranceCoverageFourthYear, $insurancePremiumSecondYear, $insurancePremiumThirdYear, $insurancePremiumFourthYear, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalRenewalAmount($salesProposalID, $registrationSecondYear, $registrationThirdYear, $registrationFourthYear, $insuranceCoverageSecondYear, $insuranceCoverageThirdYear, $insuranceCoverageFourthYear, $insurancePremiumSecondYear, $insurancePremiumThirdYear, $insurancePremiumFourthYear, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalDepositAmount
    # Description: 
    # Updates the existing sales proposal deposit amount if it exists; otherwise, inserts a new sales proposal deposit amount.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalDepositAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalDepositAmountID = htmlspecialchars($_POST['sales_proposal_deposit_amount_id'], ENT_QUOTES, 'UTF-8');
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $depositDate = $this->systemModel->checkDate('empty', $_POST['deposit_date'], '', 'Y-m-d', '');
        $referenceNumber = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $depositAmount = htmlspecialchars($_POST['deposit_amount'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalDepositAmountExist = $this->salesProposalModel->checkSalesProposalDepositAmountExist($salesProposalDepositAmountID);
        $total = $checkSalesProposalDepositAmountExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->salesProposalModel->updateSalesProposalDepositAmount($salesProposalDepositAmountID, $salesProposalID, $depositDate, $referenceNumber, $depositAmount, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->salesProposalModel->insertSalesProposalDepositAmount($salesProposalID, $depositDate, $referenceNumber, $depositAmount, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: saveSalesProposalPDCManualInput
    # Description: 
    # Updates the existing sales proposal deposit amount if it exists; otherwise, inserts a new sales proposal deposit amount.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSalesProposalPDCManualInput() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalID = htmlspecialchars($_POST['sales_proposal_id'], ENT_QUOTES, 'UTF-8');
        $paymentFrequency = htmlspecialchars($_POST['payment_frequency'], ENT_QUOTES, 'UTF-8');
        $paymentFor = htmlspecialchars($_POST['payment_for'], ENT_QUOTES, 'UTF-8');
        $bankBranch = htmlspecialchars($_POST['bank_branch'], ENT_QUOTES, 'UTF-8');
        $noOfPayments = htmlspecialchars($_POST['no_of_payments'], ENT_QUOTES, 'UTF-8');
        $firstCheckNumber = htmlspecialchars($_POST['first_check_number'], ENT_QUOTES, 'UTF-8');
        $firstCheckDate = $this->systemModel->checkDate('empty', $_POST['first_check_date'], '', 'Y-m-d', '');
        $amountDue = htmlspecialchars($_POST['amount_due'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        for ($i = 0; $i < $noOfPayments; $i++) {
            $dueDate = $this->calculateDueDate($firstCheckDate, $paymentFrequency, $i);

            if($i > 0){
                $firstCheckNumber = $firstCheckNumber + 1;
            }

            $this->salesProposalModel->insertSalesProposalManualPDCInput($salesProposalID, $bankBranch, $dueDate, $firstCheckNumber, $paymentFor, $amountDue, $userID);
        }
    
        

        echo json_encode(['success' => true, 'insertRecord' => true]);
        exit;
    }
    # -------------------------------------------------------------

    private function calculateDueDate($startDate, $frequency, $iteration) {
        $date = new DateTime($startDate);
        switch ($frequency) {
            case 'Monthly':
                $date->modify("+$iteration months");
                break;
            case 'Yearly':
                $date->modify("+$iteration years");
                break;
            case 'Daily':
                $date->modify("+$iteration days");
                break;
            default:
                break;
        }
        return $date->format('Y-m-d');
    }
    

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAccessories
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAccessories() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAccessoriesID = htmlspecialchars($_POST['sales_proposal_accessories_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAccessoriesExist = $this->salesProposalModel->checkSalesProposalAccessoriesExist($salesProposalAccessoriesID);
        $total = $checkSalesProposalAccessoriesExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAccessories($salesProposalAccessoriesID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['sales_proposal_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalJobOrderExist = $this->salesProposalModel->checkSalesProposalJobOrderExist($salesProposalJobOrderID);
        $total = $checkSalesProposalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalJobOrder($salesProposalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAdditionalJobOrder
    # Description: 
    # Delete the sales proposal accessories if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalAdditionalJobOrderID = htmlspecialchars($_POST['sales_proposal_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalAdditionalJobOrderExist = $this->salesProposalModel->checkSalesProposalAdditionalJobOrderExist($salesProposalAdditionalJobOrderID);
        $total = $checkSalesProposalAdditionalJobOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalDepositAmount
    # Description: 
    # Delete the sales proposal deposit amount if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalDepositAmount() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalDepositAmountID = htmlspecialchars($_POST['sales_proposal_deposit_amount_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalDepositAmountExist = $this->salesProposalModel->checkSalesProposalDepositAmountExist($salesProposalDepositAmountID);
        $total = $checkSalesProposalDepositAmountExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalDepositAmount($salesProposalDepositAmountID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalPDCManualInput
    # Description: 
    # Delete the sales proposal deposit amount if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalPDCManualInput() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalManualPDCInputID = htmlspecialchars($_POST['sales_proposal_manual_pdc_input_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkSalesProposalManualPDCInputExist = $this->salesProposalModel->checkSalesProposalManualPDCInputExist($salesProposalManualPDCInputID);
        $total = $checkSalesProposalManualPDCInputExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->salesProposalModel->deleteSalesProposalManualPDCInput($salesProposalManualPDCInputID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDetails
    # Description: 
    # Handles the retrieval of product subcategory details such as product subcategory name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($salesProposalID);
            $comakerID = $salesProposalDetails['comaker_id'];
            $productID = $salesProposalDetails['product_id'];
            $initialApprovingOfficer = $salesProposalDetails['initial_approving_officer'];
            $finalApprovingOfficer = $salesProposalDetails['final_approving_officer'];
            $initialApprovalBy = $salesProposalDetails['initial_approval_by'];
            $approvalBy = $salesProposalDetails['approval_by'];

            $createdByDetails = $this->customerModel->getPersonalInformation($salesProposalDetails['created_by']);
            $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

            $productDetails = $this->productModel->getProduct($productID);
            $stockNumber = $productDetails['stock_number'] ?? null;
            $productDescription = $productDetails['description'] ?? null;

            $approvalByDetails = $this->customerModel->getPersonalInformation($approvalBy);
            $approvalByName = $approvalByDetails['file_as'] ?? null;

            $initialApprovalByDetails = $this->customerModel->getPersonalInformation($initialApprovalBy);
            $initialApprovalByName = $initialApprovalByDetails['file_as'] ?? null;

            $comakerDetails = $this->customerModel->getPersonalInformation($comakerID);
            $comakerName = $comakerDetails['file_as'] ?? null;

            $initialApprovingOfficerDetails = $this->customerModel->getPersonalInformation($initialApprovingOfficer);
            $initialApprovingOfficerName = strtoupper($initialApprovingOfficerDetails['file_as'] ?? null);

            $finalApprovingOfficerDetails = $this->customerModel->getPersonalInformation($finalApprovingOfficer);
            $finalApprovingOfficerName = strtoupper($finalApprovingOfficerDetails['file_as'] ?? null);

            $selectedOptions = array();
            $selectedOptions[] = $salesProposalDetails["fuel_type"];


            $response = [
                'success' => true,
                'salesProposalNumber' => $salesProposalDetails['sales_proposal_number'],
                'comakerID' => $comakerID,
                'comakerName' => $comakerName,
                'productID' => $productID,
                'productType' => $salesProposalDetails['product_type'] ?? null,
                'transactionType' => $salesProposalDetails['transaction_type'] ?? null,
                'forChangeColor' => $salesProposalDetails['for_change_color'] ?? null,
                'forChangeBody' => $salesProposalDetails['for_change_body'] ?? null,
                'forChangeEngine' => $salesProposalDetails['for_change_engine'] ?? null,
                'financingInstitution' => $salesProposalDetails['financing_institution'] ?? null,
                'refStockNo' => $salesProposalDetails['ref_stock_no'] ?? null,
                'refEngineNo' => $salesProposalDetails['ref_engine_no'] ?? null,
                'refChassisNo' => $salesProposalDetails['ref_chassis_no'] ?? null,
                'refPlateNo' => $salesProposalDetails['ref_plate_no'] ?? null,
                'renewalTag' => $salesProposalDetails['renewal_tag'] ?? null,
                'newColor' => $salesProposalDetails['new_color'] ?? null,
                'newBody' => $salesProposalDetails['new_body'] ?? null,
                'newEngine' => $salesProposalDetails['new_engine'] ?? null,
                'fuelType' => $selectedOptions,
                'fuelQuantity' => $salesProposalDetails['fuel_quantity'] ?? 0,
                'pricePerLiter' => $salesProposalDetails['price_per_liter'] ?? 0,
                'commissionAmount' => $salesProposalDetails['commission_amount'] ?? 0,
                'changeRequestStatus' => $salesProposalDetails['change_request_status'] ?? null,
                'initialApprovalByName' => $initialApprovalByName,
                'approvalByName' => $approvalByName,
                'productName' => $stockNumber . ' - ' . $productDescription,
                'referredBy' => $salesProposalDetails['referred_by'],
                'drNumber' => $salesProposalDetails['dr_number'],
                'releaseDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['release_date'], '', 'm/d/Y', ''),
                'startDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['start_date'], '', 'm/d/Y', ''),
                'actualStartDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['actual_start_date'], '', 'm/d/Y', ''),
                'firstDueDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['first_due_date'], '', 'm/d/Y', ''),
                'initialApprovalDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['initial_approval_date'], '', 'm/d/Y', ''),
                'approvalDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['approval_date'], '', 'm/d/Y', ''),
                'forCIDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['for_ci_date'], '', 'm/d/Y', ''),
                'rejectionDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['rejection_date'], '', 'm/d/Y', ''),
                'cancellationDate' =>  $this->systemModel->checkDate('empty', $salesProposalDetails['cancellation_date'], '', 'm/d/Y', ''),
                'creditAdvice' => $this->systemModel->checkImage($salesProposalDetails['credit_advice'], 'default'),
                'clientConfirmation' => $this->systemModel->checkImage($salesProposalDetails['client_confirmation'], 'default'),
                'newEngineStencil' => $this->systemModel->checkImage($salesProposalDetails['new_engine_stencil'], 'default'),
                'qualityControlForm' => $this->systemModel->checkImage($salesProposalDetails['quality_control_form'], 'default'),
                'outgoingChecklist' => $this->systemModel->checkImage($salesProposalDetails['outgoing_checklist'], 'default'),
                'unitImage' => $this->systemModel->checkImage($salesProposalDetails['unit_image'], 'default'),
                'additionalJobOrderConfirmationImage' => $this->systemModel->checkImage($salesProposalDetails['additional_job_order_confirmation'], 'default'),
                'termLength' => $salesProposalDetails['term_length'],
                'termType' => $salesProposalDetails['term_type'],
                'numberOfPayments' => $salesProposalDetails['number_of_payments'],
                'paymentFrequency' => $salesProposalDetails['payment_frequency'],
                'forRegistration' => $salesProposalDetails['for_registration'],
                'initialApprovalRemarks' => $salesProposalDetails['initial_approval_remarks'],
                'finalApprovalRemarks' => $salesProposalDetails['final_approval_remarks'],
                'rejectionReason' => $salesProposalDetails['rejection_reason'],
                'cancellationReason' => $salesProposalDetails['cancellation_reason'],
                'setToDraftReason' => $salesProposalDetails['set_to_draft_reason'],
                'withCR' => $salesProposalDetails['with_cr'],
                'forTransfer' => $salesProposalDetails['for_transfer'],
                'remarks' => $salesProposalDetails['remarks'],
                'initialApprovingOfficer' => $initialApprovingOfficer,
                'finalApprovingOfficer' => $finalApprovingOfficer,
                'createdByName' => $createdByName,
                'initialApprovingOfficerName' => $initialApprovingOfficerName,
                'finalApprovingOfficerName' => $finalApprovingOfficerName,
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessoriesDetails
    # Description: 
    # Handles the retrieval of sales proposal accessories details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_accessories_id']) && !empty($_POST['sales_proposal_accessories_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAccessoriesID = $_POST['sales_proposal_accessories_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAccessoriesDetails = $this->salesProposalModel->getSalesProposalAccessories($salesProposalAccessoriesID);

            $response = [
                'success' => true,
                'accessories' => $salesProposalAccessoriesDetails['accessories'],
                'cost' => $salesProposalAccessoriesDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessoriesTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal accessories total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAccessoriesTotalDetails = $this->salesProposalModel->getSalesProposalAccessoriesTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalAccessoriesTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_job_order_id']) && !empty($_POST['sales_proposal_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalJobOrderID = $_POST['sales_proposal_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalJobOrderDetails = $this->salesProposalModel->getSalesProposalJobOrder($salesProposalJobOrderID);

            $response = [
                'success' => true,
                'jobOrder' => $salesProposalJobOrderDetails['job_order'],
                'cost' => $salesProposalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrderTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal job order total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalJobOrderTotalDetails = $this->salesProposalModel->getSalesProposalJobOrderTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalJobOrderTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrderDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_additional_job_order_id']) && !empty($_POST['sales_proposal_additional_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalAdditionalJobOrderID = $_POST['sales_proposal_additional_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAdditionalJobOrderDetails = $this->salesProposalModel->getSalesProposalAdditionalJobOrder($salesProposalAdditionalJobOrderID);

            $response = [
                'success' => true,
                'jobOrderNumber' => $salesProposalAdditionalJobOrderDetails['job_order_number'],
                'jobOrderDate' =>  $this->systemModel->checkDate('empty', $salesProposalAdditionalJobOrderDetails['job_order_date'], '', 'm/d/Y', ''),
                'particulars' =>  $salesProposalAdditionalJobOrderDetails['particulars'],
                'cost' => $salesProposalAdditionalJobOrderDetails['cost']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrderTotalDetails
    # Description: 
    # Handles the retrieval of sales proposal additional job order total details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalAdditionalJobOrderTotalDetails = $this->salesProposalModel->getSalesProposalAdditionalJobOrderTotal($salesProposalID);

            $response = [
                'success' => true,
                'total' => number_format($salesProposalAdditionalJobOrderTotalDetails['total'] ?? 0, 2)
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalPricingComputationDetails
    # Description: 
    # Handles the retrieval of sales proposal pricing computation details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalPricingComputationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalPricingComputationDetails = $this->salesProposalModel->getSalesProposalPricingComputation($salesProposalID);

            $response = [
                'success' => true,
                'deliveryPrice' => $salesProposalPricingComputationDetails['delivery_price'] ?? '',
                'nominalDiscount' => $salesProposalPricingComputationDetails['nominal_discount'] ?? '',
                'totalDeliveryPrice' => $salesProposalPricingComputationDetails['total_delivery_price'] ?? 0,
                'addOnCharge' => $salesProposalPricingComputationDetails['add_on_charge'] ?? 0,
                'costOfAccessories' => $salesProposalPricingComputationDetails['cost_of_accessories'] ?? 0,
                'reconditioningCost' => $salesProposalPricingComputationDetails['reconditioning_cost'] ?? 0,
                'downpayment' => $salesProposalPricingComputationDetails['downpayment'] ?? 0,
                'interestRate' => $salesProposalPricingComputationDetails['interest_rate'] ?? 0,
                'repaymentAmount' => $salesProposalPricingComputationDetails['repayment_amount'] ?? 0,
                'pnAmount' => $salesProposalPricingComputationDetails['pn_amount'] ?? 0,
                'subtotal' => $salesProposalPricingComputationDetails['subtotal'] ?? 0,
                'outstandingBalance' => $salesProposalPricingComputationDetails['outstanding_balance'] ?? 0,
                'amountFinanced' => $salesProposalPricingComputationDetails['amount_financed'] ?? 0,
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalOtherChargesDetails
    # Description: 
    # Handles the retrieval of sales proposal other charges details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalOtherChargesDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalOtherChargesDetails = $this->salesProposalModel->getSalesProposalOtherCharges($salesProposalID);

            $response = [
                'success' => true,
                'insuranceCoverage' => $salesProposalOtherChargesDetails['insurance_coverage'] ?? 0,
                'insurancePremium' => $salesProposalOtherChargesDetails['insurance_premium'] ?? 0,
                'handlingFee' => $salesProposalOtherChargesDetails['handling_fee'] ?? 0,
                'transferFee' => $salesProposalOtherChargesDetails['transfer_fee'] ?? 0,
                'registrationFee' => $salesProposalOtherChargesDetails['registration_fee'] ?? 0,
                'docStampTax' => $salesProposalOtherChargesDetails['doc_stamp_tax'] ?? 0,
                'totalOtherCharges' => $salesProposalOtherChargesDetails['total_other_charges'] ?? 0,
                'transactionFee' => $salesProposalOtherChargesDetails['transaction_fee'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalOtherProductDetails
    # Description: 
    # Handles the retrieval of sales proposal other charges details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalOtherProductDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalOtherChargesDetails = $this->salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);

            $response = [
                'success' => true,
                'yearModel' => $salesProposalOtherChargesDetails['year_model'] ?? null,
                'crNo' => $salesProposalOtherChargesDetails['cr_no'] ?? null,
                'mvFileNo' => $salesProposalOtherChargesDetails['mv_file_no'] ?? null,
                'make' => $salesProposalOtherChargesDetails['make'] ?? null,
                'productDescription' => $salesProposalOtherChargesDetails['product_description'] ?? null,
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalRenewalAmountDetails
    # Description: 
    # Handles the retrieval of sales proposal renewal amount details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalRenewalAmountDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_id']) && !empty($_POST['sales_proposal_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalID = $_POST['sales_proposal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalRenewalAmountDetails = $this->salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);

            $response = [
                'success' => true,
                'registrationSecondYear' => $salesProposalRenewalAmountDetails['registration_second_year'] ?? 0,
                'registrationThirdYear' => $salesProposalRenewalAmountDetails['registration_third_year'] ?? 0,
                'registrationFourthYear' => $salesProposalRenewalAmountDetails['registration_fourth_year'] ?? 0,
                'insuranceCoverageSecondYear' => $salesProposalRenewalAmountDetails['insurance_coverage_second_year'] ?? 0,
                'insuranceCoverageThirdYear' => $salesProposalRenewalAmountDetails['insurance_coverage_third_year'] ?? 0,
                'insuranceCoverageFourthYear' => $salesProposalRenewalAmountDetails['insurance_coverage_fourth_year'] ?? 0,
                'insurancePremiumSecondYear' => $salesProposalRenewalAmountDetails['insurance_premium_second_year'] ?? 0,
                'insurancePremiumThirdYear' => $salesProposalRenewalAmountDetails['insurance_premium_third_year'] ?? 0,
                'insurancePremiumFourthYear' => $salesProposalRenewalAmountDetails['insurance_premium_fourth_year'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDepositAmountDetails
    # Description: 
    # Handles the retrieval of sales proposal job order details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getSalesProposalDepositAmountDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['sales_proposal_deposit_amount_id']) && !empty($_POST['sales_proposal_deposit_amount_id'])) {
            $userID = $_SESSION['user_id'];
            $salesProposalDepositAmountID = $_POST['sales_proposal_deposit_amount_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $salesProposalDepositAmountDetails = $this->salesProposalModel->getSalesProposalDepositAmount($salesProposalDepositAmountID);

            $response = [
                'success' => true,
                'depositDate' =>  $this->systemModel->checkDate('empty', $salesProposalDepositAmountDetails['deposit_date'], '', 'm/d/Y', ''),
                'referenceNumber' =>  $salesProposalDepositAmountDetails['reference_number'],
                'depositAmount' =>  $salesProposalDepositAmountDetails['deposit_amount']
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
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/user-model.php';
require_once '../model/company-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new SalesProposalController(new SalesProposalModel(new DatabaseModel), new CustomerModel(new DatabaseModel), new ProductModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>