<?php
session_start();

# -------------------------------------------------------------
#
# Function: BackJobMonitoringController
# Description: 
# The BackJobMonitoringController class handles internal DR related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BackJobMonitoringController {
    private $backJobMonitoringModel;
    private $salesProposalModel;
    private $productModel;
    private $contractorModel;
    private $workCenterModel;
    private $userModel;
    private $securityModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BackJobMonitoringModel, UserModel and SecurityModel instances.
    # These instances are used for internal DR related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BackJobMonitoringModel $backJobMonitoringModel     The BackJobMonitoringModel instance for internal DR related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BackJobMonitoringModel $backJobMonitoringModel, SalesProposalModel $salesProposalModel, ProductModel $productModel, ContractorModel $contractorModel, WorkCenterModel $workCenterModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->backJobMonitoringModel = $backJobMonitoringModel;
        $this->salesProposalModel = $salesProposalModel;
        $this->productModel = $productModel;
        $this->contractorModel = $contractorModel;
        $this->workCenterModel = $workCenterModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
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
                case 'save backjob monitoring':
                    $this->saveBackJobMonitoring();
                    break;
                case 'save unit image':
                    $this->saveBackJobMonitoringUnitImage();
                    break;
                case 'save unit back':
                    $this->saveBackJobMonitoringUnitBack();
                    break;
                case 'save unit left':
                    $this->saveBackJobMonitoringUnitLeft();
                    break;
                case 'save unit right':
                    $this->saveBackJobMonitoringUnitRight();
                    break;
                case 'save unit interior':
                    $this->saveBackJobMonitoringUnitInterior();
                    break;
                case 'save outgoing checklist':
                    $this->saveBackJobMonitoringOutgoingChecklist();
                    break;
                case 'save quality control form':
                    $this->saveBackJobMonitoringQualityControlForm();
                    break;
                case 'save approval form':
                    $this->saveBackJobMonitoringApprovalForm();
                    break;
                case 'save progress job order':
                    $this->saveBackJobMonitoringJobOrder();
                    break;
                case 'save progress additional job order':
                    $this->saveBackJobMonitoringAdditionalJobOrder();
                    break;
                case 'get backjob monitoring details':
                    $this->getBackJobMonitoringDetails();
                    break;
                case 'get job order details':
                    $this->getBackJobMonitoringJobOrderDetails();
                    break;
                case 'get additional job order details':
                    $this->getBackJobMonitoringAdditionalJobOrderDetails();
                    break;
                case 'tag for cancelled':
                    $this->tagBackJobMonitoringAsCancelled();
                    break;
                case 'approve':
                    $this->tagBackJobMonitoringApprove();
                    break;
                case 'set to draft':
                    $this->tagBackJobMonitoringSetToDraft();
                    break;
                case 'tag for release':
                    $this->tagBackJobMonitoringAsReleased();
                    break;
                case 'tag for approval':
                    $this->tagBackJobMonitoringForApproval();
                    break;
                case 'tag as on process':
                    $this->tagBackJobMonitoringAsOnProcess();
                    break;
                case 'tag as ready for release':
                    $this->tagBackJobMonitoringAsReadyForRelease();
                    break;
                case 'tag as for dr':
                    $this->tagBackJobMonitoringAsForDR();
                    break;
                case 'delete backjob monitoring':
                    $this->deleteBackJobMonitoring();
                    break;
                case 'delete job order':
                    $this->deleteBackJobMonitoringJobOrder();
                    break;
                case 'delete additional job order':
                    $this->deleteBackJobMonitoringAdditionalJobOrder();
                    break;
                 case 'paid job order':
                    $this->paidJobOrder();
                    break;
                case 'cancel paid job order':
                    $this->cancelPaidJobOrder();
                    break;
                case 'paid additional job order':
                    $this->paidAdditionalJobOrder();
                    break;
                case 'cancel paid additional job order':
                    $this->cancelPaidAdditionalJobOrder();
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

    public function paidJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringJobOrderID = htmlspecialchars($_POST['job_order_id'], ENT_QUOTES, 'UTF-8');
        $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $payment_date = $this->systemModel->checkDate('empty', $_POST['payment_date'], '', 'Y-m-d', '');

        $backjobOrderMonitoringJobOrderDetails = $this->backJobMonitoringModel->getBackJobMonitoringJobOrder($backjobMonitoringJobOrderID);
        $backjob_monitoring_id = $backjobOrderMonitoringJobOrderDetails['backjob_monitoring_id'];
        $cost_markup = $backjobOrderMonitoringJobOrderDetails['cost_markup'];
        $job_cost = $backjobOrderMonitoringJobOrderDetails['cost'];
        $job_order = $backjobOrderMonitoringJobOrderDetails['job_order'];
        $work_center_id = $backjobOrderMonitoringJobOrderDetails['work_center_id'];

        $workCenterDetails = $this->workCenterModel->getWorkCenter($work_center_id);
        $work_center_name = $workCenterDetails['work_center_name'] ?? null;

        $entry_type = 'Internal Job Order';

        $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
        $sales_proposal_id = $backJobMonitoringDetails['sales_proposal_id'] ?? null;
        $product_id = $backJobMonitoringDetails['product_id'] ?? null;

        if(!empty($sales_proposal_id)){
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($sales_proposal_id);
            $company_id = $salesProposalDetails['company_id'] ?? null;
            $product_id = $salesProposalDetails['product_id'] ?? null;

            $productDetails = $this->productModel->getProduct($product_id);
            $product_status = $productDetails['product_status'] ?? 'Draft';
        }
        else{
            $productDetails = $this->productModel->getProduct($product_id);
            $company_id = $productDetails['company_id'] ?? null;
            $product_status = $productDetails['product_status'] ?? 'Draft';
        }

        $this->backJobMonitoringModel->createBackjobJobOrderEntry($backjob_monitoring_id, $backjobMonitoringJobOrderID, $entry_type, $company_id, $job_cost, $cost_markup, $product_status, $userID);

        $this->productModel->insertProductExpense($product_id, 'Contractor Report', $backjob_monitoring_id, $cost_markup, $work_center_name, $job_order, date('Y-m-d'), $userID);

        $this->backJobMonitoringModel->updateBackjobMonitoringJobOrderPaid($backjobMonitoringJobOrderID, $reference_number, $payment_date, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    
    public function cancelPaidJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesProposalJobOrderID = htmlspecialchars($_POST['backjob_monitoring_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $this->backJobMonitoringModel->updateBackjobMonitoringJobOrderPaidCancel($salesProposalJobOrderID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function paidAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesAdditionalProposalJobOrderID = htmlspecialchars($_POST['job_order_id'], ENT_QUOTES, 'UTF-8');
        $reference_number = htmlspecialchars($_POST['reference_number'], ENT_QUOTES, 'UTF-8');
        $payment_date = $this->systemModel->checkDate('empty', $_POST['payment_date'], '', 'Y-m-d', '');

        $backjobOrderMonitoringJobOrderDetails = $this->backJobMonitoringModel->getBackJobMonitoringAdditionalJobOrder($salesAdditionalProposalJobOrderID);
        $backjob_monitoring_id = $backjobOrderMonitoringJobOrderDetails['backjob_monitoring_id'];
        $cost_markup = $backjobOrderMonitoringJobOrderDetails['cost_markup'];
        $job_cost = $backjobOrderMonitoringJobOrderDetails['job_cost']; 
        $work_center_id = $backjobOrderMonitoringJobOrderDetails['work_center_id'];
        $particulars = $backjobOrderMonitoringJobOrderDetails['particulars'];

        $workCenterDetails = $this->workCenterModel->getWorkCenter($work_center_id);
        $work_center_name = $workCenterDetails['work_center_name'] ?? null;
        $entry_type = 'Internal Additional Job Order';

        $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjob_monitoring_id);
        $sales_proposal_id = $backJobMonitoringDetails['sales_proposal_id'] ?? null;
        $product_id = $backJobMonitoringDetails['product_id'] ?? null;

        if(!empty($sales_proposal_id)){
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($sales_proposal_id);
            $company_id = $salesProposalDetails['company_id'] ?? null;
            $product_id = $salesProposalDetails['product_id'] ?? null;

            $productDetails = $this->productModel->getProduct($product_id);
            $product_status = $productDetails['product_status'] ?? 'Draft';
        }
        else{
            $productDetails = $this->productModel->getProduct($product_id);
            $company_id = $productDetails['company_id'] ?? null;
            $product_status = $productDetails['product_status'] ?? 'Draft';
        }

        $this->backJobMonitoringModel->createBackjobJobOrderEntry($backjob_monitoring_id, $salesAdditionalProposalJobOrderID, $entry_type, $company_id, $job_cost, $cost_markup, $product_status, $userID);

        $this->productModel->insertProductExpense($product_id, 'Contractor Report', $backjob_monitoring_id, $cost_markup, $work_center_name, $particulars, date('Y-m-d'), $userID);
    
        $this->backJobMonitoringModel->updateBackjobMonitoringAdditionalJobOrderPaid($salesAdditionalProposalJobOrderID, $reference_number, $payment_date, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
    public function cancelPaidAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $salesAdditionalProposalJobOrderID = htmlspecialchars($_POST['backjob_monitoring_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $this->backJobMonitoringModel->updateBackjobMonitoringAdditionalJobOrderPaidCancel($salesAdditionalProposalJobOrderID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    # -------------------------------------------------------------
    #
    # Function: saveBackJobMonitoring
    # Description: 
    # Updates the existing internal DR if it exists; otherwise, inserts a new internal DR.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBackJobMonitoring() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = isset($_POST['backjob_monitoring_id']) ? htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8') : null;
        $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($type === 'Backjob'){
            $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        }
        else{
            $backjob_monitoring_id = null;
        }

        if($type === 'Internal Repair'){
            $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        }
        else if($type === 'Warranty'){
            $product_id = htmlspecialchars($_POST['product_id2'], ENT_QUOTES, 'UTF-8');
        }
        else{
            $salesProposalDetails = $this->salesProposalModel->getSalesProposal($backjob_monitoring_id);
            $product_id = $salesProposalDetails['product_id'] ?? null;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->backJobMonitoringModel->updateBackJobMonitoring($backjobMonitoringID, $type, $product_id, $backjob_monitoring_id, $userID);

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);

            if($type != $backJobMonitoringDetails['type'] && $type == 'Backjob'){
                $this->backJobMonitoringModel->loadBackJobMonitoringJobOrder($backjobMonitoringID, $backjob_monitoring_id, $userID);
            }
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'backjobMonitoringID' => $this->securityModel->encryptData($backjobMonitoringID)]);
            exit;
        } 
        else {
            $backjobMonitoringID = $this->backJobMonitoringModel->insertBackJobMonitoring($type, $product_id, $backjob_monitoring_id, $userID);

            if($type == 'Backjob'){
                $this->backJobMonitoringModel->loadBackJobMonitoringJobOrder($backjobMonitoringID, $backjob_monitoring_id, $userID);
            }

            echo json_encode(['success' => true, 'insertRecord' => true, 'backjobMonitoringID' => $this->securityModel->encryptData($backjobMonitoringID)]);
            exit;
        }
    }
    
    public function saveBackJobMonitoringJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjob_monitoring_job_order_id = htmlspecialchars($_POST['backjob_monitoring_job_order_id'], ENT_QUOTES, 'UTF-8');
        $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $progress = htmlspecialchars($_POST['job_order_progress'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['job_order_cost'], ENT_QUOTES, 'UTF-8');
        $contractor_id = htmlspecialchars($_POST['job_order_contractor_id'], ENT_QUOTES, 'UTF-8');
        $work_center_id = htmlspecialchars($_POST['job_order_work_center_id'], ENT_QUOTES, 'UTF-8');
        $job_order = htmlspecialchars($_POST['job_order'], ENT_QUOTES, 'UTF-8');
        $job_order_remarks = htmlspecialchars($_POST['job_order_remarks'], ENT_QUOTES, 'UTF-8');
        $completionDate = $this->systemModel->checkDate('empty', $_POST['job_order_completion_date'], '', 'Y-m-d', '');
        $job_order_planned_start_date = $this->systemModel->checkDate('empty', $_POST['job_order_planned_start_date'], '', 'Y-m-d', '');
        $job_order_planned_finish_date = $this->systemModel->checkDate('empty', $_POST['job_order_planned_finish_date'], '', 'Y-m-d', '');
        $job_order_date_started = $this->systemModel->checkDate('empty', $_POST['job_order_date_started'], '', 'Y-m-d', '');

        if($progress < 100){
            $completionDate = null;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringJobOrder($backjob_monitoring_id, $backjob_monitoring_job_order_id, $progress, $contractor_id, $work_center_id, $completionDate, $cost, $job_order, $job_order_planned_start_date, $job_order_planned_finish_date, $job_order_date_started, $job_order_remarks, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function saveBackJobMonitoringAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjob_monitoring_additional_job_order_id = htmlspecialchars($_POST['backjob_monitoring_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
        $backjob_monitoring_id = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $progress = htmlspecialchars($_POST['additional_job_order_progress'], ENT_QUOTES, 'UTF-8');
        $cost = htmlspecialchars($_POST['additional_job_order_cost'], ENT_QUOTES, 'UTF-8');
        $contractor_id = htmlspecialchars($_POST['additional_job_order_contractor_id'], ENT_QUOTES, 'UTF-8');
        $work_center_id = htmlspecialchars($_POST['additional_job_order_work_center_id'], ENT_QUOTES, 'UTF-8');
        $additional_job_order_remarks = htmlspecialchars($_POST['additional_job_order_remarks'], ENT_QUOTES, 'UTF-8');
        $job_order_number = htmlspecialchars($_POST['job_order_number'], ENT_QUOTES, 'UTF-8');
        $job_order_date = $this->systemModel->checkDate('empty', $_POST['job_order_date'], '', 'Y-m-d', '');
        $particulars = htmlspecialchars($_POST['particulars'], ENT_QUOTES, 'UTF-8');
        $completionDate = $this->systemModel->checkDate('empty', $_POST['additional_job_order_completion_date'], '', 'Y-m-d', '');
        $additional_job_order_planned_start_date = $this->systemModel->checkDate('empty', $_POST['additional_job_order_planned_start_date'], '', 'Y-m-d', '');
        $additional_job_order_planned_finish_date = $this->systemModel->checkDate('empty', $_POST['additional_job_order_planned_finish_date'], '', 'Y-m-d', '');
        $additional_job_order_date_started = $this->systemModel->checkDate('empty', $_POST['additional_job_order_date_started'], '', 'Y-m-d', '');

         if($progress < 100){
            $completionDate = null;
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAdditionalJobOrder($backjob_monitoring_id, $backjob_monitoring_additional_job_order_id, $progress, $contractor_id, $work_center_id, $completionDate, $cost, $job_order_number, $job_order_date, $particulars, $additional_job_order_planned_start_date, $additional_job_order_planned_finish_date, $additional_job_order_date_started, $additional_job_order_remarks, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveBackJobMonitoringUnitImage
    # Description: 
    # Updates the existing sales proposal if it exists; otherwise, inserts a new sales proposal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBackJobMonitoringUnitImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_image_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_image_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_image_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_image_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['unit_image']) ? '.' . $backJobMonitoringDetails['unit_image'] : null;
    
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

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/unit_image/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/unit_image/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringUnitImage($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The back job monitoring does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringUnitBack() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_back_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_back_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_back_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_back_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['unit_back']) ? '.' . $backJobMonitoringDetails['unit_back'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Image cannot be deleted due to an error.']);
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
                echo json_encode(['success' => false, 'message' => 'Please choose the image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/unit_back/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/unit_back/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringUnitBack($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The back job monitoring does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringUnitLeft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_left_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_left_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_left_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_left_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['unit_left']) ? '.' . $backJobMonitoringDetails['unit_left'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Image cannot be deleted due to an error.']);
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
                echo json_encode(['success' => false, 'message' => 'Please choose the image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/unit_left/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/unit_left/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringUnitLeft($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The back job monitoring does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringUnitRight() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_right_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_right_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_right_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_right_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['unit_right']) ? '.' . $backJobMonitoringDetails['unit_right'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Image cannot be deleted due to an error.']);
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
                echo json_encode(['success' => false, 'message' => 'Please choose the image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/unit_right/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/unit_right/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringUnitRight($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The back job monitoring does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringUnitInterior() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['unit_interior_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['unit_interior_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['unit_interior_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['unit_interior_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['unit_interior']) ? '.' . $backJobMonitoringDetails['unit_interior'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Image cannot be deleted due to an error.']);
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
                echo json_encode(['success' => false, 'message' => 'Please choose the image.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/unit_interior/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/unit_interior/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringUnitInterior($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The back job monitoring does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringOutgoingChecklist() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $outgoingChecklistImageFileName = $_FILES['outgoing_checklist_image']['name'];
            $outgoingChecklistImageFileSize = $_FILES['outgoing_checklist_image']['size'];
            $outgoingChecklistImageFileError = $_FILES['outgoing_checklist_image']['error'];
            $outgoingChecklistImageTempName = $_FILES['outgoing_checklist_image']['tmp_name'];
            $outgoingChecklistImageFileExtension = explode('.', $outgoingChecklistImageFileName);
            $outgoingChecklistImageActualFileExtension = strtolower(end($outgoingChecklistImageFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientoutgoingChecklistImage = !empty($backJobMonitoringDetails['outgoing_checklist']) ? '.' . $backJobMonitoringDetails['outgoing_checklist'] : null;
    
            if(file_exists($clientoutgoingChecklistImage)){
                if (!unlink($clientoutgoingChecklistImage)) {
                    echo json_encode(['success' => false, 'message' => 'Outgoing checklist cannot be deleted due to an error.']);
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
                echo json_encode(['success' => false, 'message' => 'Please choose the outgoing checklist.']);
                exit;
            }
            
            if($outgoingChecklistImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($outgoingChecklistImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The outgoing checklist exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $outgoingChecklistImageActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/outgoing_checklist/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/outgoing_checklist/' . $fileNew;
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

            $this->backJobMonitoringModel->updateBackJobMonitoringOutgoingChecklist($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringQualityControlForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $qualityControlFormFileName = $_FILES['quality_control_form']['name'];
            $qualityControlFormFileSize = $_FILES['quality_control_form']['size'];
            $qualityControlFormFileError = $_FILES['quality_control_form']['error'];
            $qualityControlFormTempName = $_FILES['quality_control_form']['tmp_name'];
            $qualityControlFormFileExtension = explode('.', $qualityControlFormFileName);
            $qualityControlFormActualFileExtension = strtolower(end($qualityControlFormFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientqualityControlForm = !empty($backJobMonitoringDetails['quality_control_form']) ? '.' . $backJobMonitoringDetails['quality_control_form'] : null;
    
            if(file_exists($clientqualityControlForm)){
                if (!unlink($clientqualityControlForm)) {
                    echo json_encode(['success' => false, 'message' => 'Quality control form cannot be deleted due to an error.']);
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

            if (!in_array($qualityControlFormActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($qualityControlFormTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the quality control form.']);
                exit;
            }
            
            if($qualityControlFormFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($qualityControlFormFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The quality control form exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $qualityControlFormActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/quality_control_form/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/quality_control_form/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($qualityControlFormTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->backJobMonitoringModel->updateBackJobMonitoringQualityControlForm($backjobMonitoringID, $filePath, $userID);

            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            echo json_encode(['success' => false, 'message' => 'The sales proposal does not exists.']);
            exit;
        }
    }

    public function saveBackJobMonitoringApprovalForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'] ?? 1;
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if ($total > 0) {
            $approvalFormFileName = $_FILES['approval_form']['name'];
            $approvalFormFileSize = $_FILES['approval_form']['size'];
            $approvalFormFileError = $_FILES['approval_form']['error'];
            $approvalFormTempName = $_FILES['approval_form']['tmp_name'];
            $approvalFormFileExtension = explode('.', $approvalFormFileName);
            $approvalFormActualFileExtension = strtolower(end($approvalFormFileExtension));

            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
            $clientapprovalForm = !empty($backJobMonitoringDetails['approval_form']) ? '.' . $backJobMonitoringDetails['approval_form'] : null;
    
            if(file_exists($clientapprovalForm)){
                if (!unlink($clientapprovalForm)) {
                    echo json_encode(['success' => false, 'message' => 'Quality control form cannot be deleted due to an error.']);
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

            if (!in_array($approvalFormActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
            
            if(empty($approvalFormTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the quality control form.']);
                exit;
            }
            
            if($approvalFormFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
            
            if($approvalFormFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The quality control form exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $approvalFormActualFileExtension;

            $directory = DEFAULT_BACKJOB_RELATIVE_PATH_FILE.'/approval_form/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_BACKJOB_FULL_PATH_FILE . '/approval_form/' . $fileNew;
            $filePath = $directory . $fileNew;

            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }

            if(!move_uploaded_file($approvalFormTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $this->backJobMonitoringModel->updateBackJobMonitoringApprovalForm($backjobMonitoringID, $filePath, $userID);

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
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBackJobMonitoring
    # Description: 
    # Delete the internal DR if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBackJobMonitoring() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->deleteBackJobMonitoring($backjobMonitoringID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteBackJobMonitoringJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjob_monitoring_job_order_id = htmlspecialchars($_POST['backjob_monitoring_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->backJobMonitoringModel->deleteBackJobMonitoringJobOrder($backjob_monitoring_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteBackJobMonitoringAdditionalJobOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjob_monitoring_additional_job_order_id = htmlspecialchars($_POST['backjob_monitoring_additional_job_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->backJobMonitoringModel->deleteBackJobMonitoringAdditionalJobOrder($backjob_monitoring_additional_job_order_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBackJobMonitoring
    # Description: 
    # Delete the selected internal DRs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBackJobMonitoring() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringIDs = $_POST['backjob_monitoring_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($backjobMonitoringIDs as $backjobMonitoringID){
            $this->backJobMonitoringModel->deleteBackJobMonitoring($backjobMonitoringID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagBackJobMonitoringAsCancelled
    # Description: 
    # Updates the existing accessories if it exists; otherwise, inserts a new accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------

    public function tagBackJobMonitoringAsReleased() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $releaseRemarks = $_POST['release_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsReleased($backjobMonitoringID, 'Released', $releaseRemarks, $userID);

        echo json_encode(['success' => true]);
    }
    public function tagBackJobMonitoringAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $cancellationReason = $_POST['cancellation_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsCancelled($backjobMonitoringID, 'Cancelled', $cancellationReason, $userID);

        echo json_encode(['success' => true]);
    }

    public function tagBackJobMonitoringApprove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = $_POST['approval_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsApproved($backjobMonitoringID, 'Approved', $approval_remarks, $userID);

        echo json_encode(['success' => true]);
    }

    public function tagBackJobMonitoringSetToDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
        $set_to_draft_reason = $_POST['set_to_draft_reason'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsDraft($backjobMonitoringID, 'Draft', $set_to_draft_reason, $userID);

        echo json_encode(['success' => true]);
    }

    # -------------------------------------------------------------
    #
    # Function: tagBackJobMonitoringAsCancelled
    # Description: 
    # Updates the existing accessories if it exists; otherwise, inserts a new accessories.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagBackJobMonitoringAsOnProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
        $type = $backJobMonitoringDetails['type'];
        $backjob_monitoring_id = $backJobMonitoringDetails['backjob_monitoring_id'];
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsOnProcess($backjobMonitoringID, 'On-Process', $userID);

        echo json_encode(['success' => true]);
    }

    public function tagBackJobMonitoringAsReadyForRelease() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
        
        $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);
        $type = $backJobMonitoringDetails['type'];
        $backjob_monitoring_id = $backJobMonitoringDetails['backjob_monitoring_id'] ?? null;
        $outgoing_checklist = $backJobMonitoringDetails['outgoing_checklist'];
        $quality_control_form = $backJobMonitoringDetails['quality_control_form'];
        $unit_image = $backJobMonitoringDetails['unit_image'];
        $product_id = $backJobMonitoringDetails['product_id'] ?? null;

        $total = $this->backJobMonitoringModel->getBackJobMonitoringJobOrderCount($backjobMonitoringID, 'unfinished')['total'];

        if($total > 0){
            echo json_encode(['success' => false, 'jobOrderUnfinished' =>  true]);
            exit;
        }

        if((empty($unit_image) || empty($outgoing_checklist) || empty($quality_control_form)) && $type == 'Backjob'){
            echo json_encode(['success' => false, 'imageNotUploaded' =>  true]);
            exit;
        }

        if($type == 'Backjob'){
            $reference_number = $backjob_monitoring_id;
        }
        else{
            $reference_number = $backjobMonitoringID;
        }

        $jobOrders = $this->backJobMonitoringModel->getBackJobMonitoringJobOrderList($backjobMonitoringID);

        foreach ($jobOrders as $row) {
            $backjob_monitoring_job_order_id = $row['backjob_monitoring_job_order_id'];
            $job_order = $row['job_order'];
            $contractor_id = $row['contractor_id'];
            $cost_markup = $row['cost_markup'];

            $contractorDetails = $this->contractorModel->getContractor($contractor_id);
            $contractor_name = $contractorDetails['contractor_name'] ?? null;

            $particulars = $contractor_name . ' - ' . $job_order;

            $this->productModel->insertProductExpense($product_id, 'Contractor Report', $reference_number . ' - ' . $backjob_monitoring_job_order_id, $cost_markup, 'Repairs & Maintenance', $particulars, null, $userID);

            $this->backJobMonitoringModel->updateBackjobJobOrderExpenseCreatedDate($backjob_monitoring_job_order_id, $userID);
        }

        $additionalJobOrders = $this->backJobMonitoringModel->getBackJobMonitoringAdditionalJobOrderList($backjobMonitoringID);

        foreach ($additionalJobOrders as $row) {
            $backjob_monitoring_additional_job_order_id = $row['backjob_monitoring_additional_job_order_id'];
            $job_order = $row['particulars'];
            $contractor_id = $row['contractor_id'];
            $job_order_number = $row['job_order_number'];
            $cost_markup = $row['cost_markup'];

            $contractorDetails = $this->contractorModel->getContractor($contractor_id);
            $contractor_name = $contractorDetails['contractor_name'] ?? null;

            $particulars = $contractor_name . ' - ' . $job_order_number . ' - ' . $job_order;

            $this->productModel->insertProductExpense($product_id, 'Contractor Report', $reference_number . ' - ' . $backjob_monitoring_additional_job_order_id, $cost_markup, 'Repairs & Maintenance', $particulars, null, $userID);

            $this->backJobMonitoringModel->updateBackjobAdditionalJobOrderExpenseCreatedDate($backjob_monitoring_additional_job_order_id, $userID);
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsReadyForRelease($backjobMonitoringID, 'Ready For Release', $userID);

        echo json_encode(['success' => true]);
    }

    public function tagBackJobMonitoringAsForDR() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsForDR($backjobMonitoringID, 'For DR', $userID);

        echo json_encode(['success' => true]);
    }

    public function tagBackJobMonitoringForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $backjobMonitoringID = htmlspecialchars($_POST['backjob_monitoring_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBackJobMonitoringExist = $this->backJobMonitoringModel->checkBackJobMonitoringExist($backjobMonitoringID);
        $total = $checkBackJobMonitoringExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->backJobMonitoringModel->updateBackJobMonitoringAsForApproval($backjobMonitoringID, 'For Approval', $userID);

        echo json_encode(['success' => true]);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBackJobMonitoringDetails
    # Description: 
    # Handles the retrieval of internal DR details such as internal DR name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBackJobMonitoringDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['backjob_monitoring_id']) && !empty($_POST['backjob_monitoring_id'])) {
            $userID = $_SESSION['user_id'];
            $backjobMonitoringID = $_POST['backjob_monitoring_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoring($backjobMonitoringID);

            $response = [
                'success' => true,
                'type' => $backJobMonitoringDetails['type'],
                'product_id' => $backJobMonitoringDetails['product_id'],
                'backjob_monitoring_id' => $backJobMonitoringDetails['backjob_monitoring_id'],
                'unitImage' =>  $this->systemModel->checkImage($backJobMonitoringDetails['unit_image'], 'default'),
                'unitBack' =>  $this->systemModel->checkImage($backJobMonitoringDetails['unit_back'], 'default'),
                'unitLeft' =>  $this->systemModel->checkImage($backJobMonitoringDetails['unit_left'], 'default'),
                'unitRight' =>  $this->systemModel->checkImage($backJobMonitoringDetails['unit_right'], 'default'),
                'unitInterior' =>  $this->systemModel->checkImage($backJobMonitoringDetails['unit_interior'], 'default'),
                'outgoingChecklist' =>  $this->systemModel->checkImage($backJobMonitoringDetails['outgoing_checklist'], 'default'),
                'qualityControlForm' =>  $this->systemModel->checkImage($backJobMonitoringDetails['quality_control_form'], 'default'),
                'approvalForm' =>  $this->systemModel->checkImage($backJobMonitoringDetails['approval_form'], 'default')
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getBackJobMonitoringJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['backjob_monitoring_job_order_id']) && !empty($_POST['backjob_monitoring_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $backjob_monitoring_job_order_id = $_POST['backjob_monitoring_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoringJobOrder($backjob_monitoring_job_order_id);

            $response = [
                'success' => true,
                'cost' => $backJobMonitoringDetails['cost'],
                'jobOrder' => $backJobMonitoringDetails['job_order'],
                'progress' => $backJobMonitoringDetails['progress'],
                'remarks' => $backJobMonitoringDetails['remarks'],
                'contractorID' => $backJobMonitoringDetails['contractor_id'],
                'completionDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['completion_date'], '', 'm/d/Y', ''),
                'plannedStartDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['planned_start_date'], '', 'm/d/Y', ''),
                'plannedFinishDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['planned_finish_date'], '', 'm/d/Y', ''),
                'dateStarted' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['date_started'], '', 'm/d/Y', ''),
                'workCenterID' => $backJobMonitoringDetails['work_center_id']
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getBackJobMonitoringAdditionalJobOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['backjob_monitoring_additional_job_order_id']) && !empty($_POST['backjob_monitoring_additional_job_order_id'])) {
            $userID = $_SESSION['user_id'];
            $backjob_monitoring_additional_job_order_id = $_POST['backjob_monitoring_additional_job_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $backJobMonitoringDetails = $this->backJobMonitoringModel->getBackJobMonitoringAdditionalJobOrder($backjob_monitoring_additional_job_order_id);

            $response = [
                'success' => true,
                'cost' => $backJobMonitoringDetails['cost'],
                'jobOrderNumber' => $backJobMonitoringDetails['job_order_number'],
                'progress' => $backJobMonitoringDetails['progress'],
                'remarks' => $backJobMonitoringDetails['remarks'],
                'contractorID' => $backJobMonitoringDetails['contractor_id'],
                'particulars' => $backJobMonitoringDetails['particulars'],
                'completionDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['completion_date'], '', 'm/d/Y', ''),
                'plannedStartDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['planned_start_date'], '', 'm/d/Y', ''),
                'plannedFinishDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['planned_finish_date'], '', 'm/d/Y', ''),
                'dateStarted' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['date_started'], '', 'm/d/Y', ''),
                'jobOrderDate' =>  $this->systemModel->checkDate('empty', $backJobMonitoringDetails['job_order_date'], '', 'm/d/Y', ''),
                'workCenterID' => $backJobMonitoringDetails['work_center_id']
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
require_once '../model/back-job-monitoring-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/contractor-model.php';
require_once '../model/work-center-model.php';
require_once '../model/product-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BackJobMonitoringController(new BackJobMonitoringModel(new DatabaseModel), new SalesProposalModel(new DatabaseModel()), new ProductModel(new DatabaseModel()), new ContractorModel(new DatabaseModel()), new WorkCenterModel(new DatabaseModel()), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>