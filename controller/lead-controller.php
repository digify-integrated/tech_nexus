<?php
session_start();

class LeadController {
    private $leadModel;
    private $leadStatusModel;
    private $employeeModel;
    private $productModel;
    private $productSubcategoryModel;
    private $companyModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemSettingModel;
    private $securityModel;
    private $systemModel;

    public function __construct(LeadModel $leadModel, LeadStatusModel $leadStatusModel, EmployeeModel $employeeModel, ProductModel $productModel, ProductSubcategoryModel $productSubcategoryModel, CompanyModel $companyModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->leadModel = $leadModel;
        $this->leadStatusModel = $leadStatusModel;
        $this->employeeModel = $employeeModel;
        $this->productModel = $productModel;
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->companyModel = $companyModel;
        $this->userModel = $userModel;
        $this->securityModel = $securityModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
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

                case 'save product inquiry':
                    $this->saveProductInquiry();
                break;

                case 'save lead status':
                    $this->saveLeadStatus();
                break;

                case 'save quotation':
                    $this->saveQuotation();
                break;

                case 'save cif':
                    $this->saveCif();
                break;

                case 'save supplier':
                    $this->saveSupplier();
                break;

                case 'save spaf':
                    $this->saveSpaf();
                break;

                case 'get lead details':
                    $this->getLeadDetails();
                break;

                case 'get quotation details':
                    $this->getQuotationDetails();
                break;

                case 'get cif details':
                    $this->getCifDetails();
                break;

                case 'get cif supplier details':
                    $this->getCifSupplierDetails();
                break;

                case 'get spaf details':
                    $this->getSPAFDetails();
                break;

                case 'get inclusions details':
                    $this->getInclusionsDetails();
                break;

                case 'delete lead':
                    $this->deleteLead();
                break;

                case 'delete lead note':
                    $this->deleteLeadNote();
                break;

                case 'delete product inquiry':
                    $this->deleteProductInquiry();
                break;

                case 'delete multiple lead':
                    $this->deleteMultipleLead();
                break;

                case 'duplicate lead':
                    $this->duplicateLead();
                break;

                case 'delete quotation':
                    $this->deleteQuotation();
                break;

                case 'delete cif':
                    $this->deleteCif();
                break;

                case 'delete spaf':
                    $this->deleteSpaf();
                break;

                case 'for approval quotation':
                    $this->forApprovalQuotation();
                break;

                case 'draft quotation':
                    $this->draftQuotation();
                break;

                case 'cancel quotation':
                    $this->cancelQuotation();
                break;

                case 'cancel cif':
                    $this->cancelCIF();
                break;

                case 'cancel spaf':
                    $this->cancelSpaf();
                break;

                case 'approve quotation':
                    $this->approveQuotation();
                break;

                case 'complete cif':
                    $this->completeCIF();
                break;

                case 'approve spaf':
                    $this->approveSpaf();
                break;

                case 'on-process cif':
                    $this->onProcessCIF();
                break;

                case 'for approval spaf':
                    $this->forApprovalSPAF();
                break;

                case 'save supplier quotations':
                    $this->saveSupplierQuotations();
                break;

                case 'delete supplier quotation':
                    $this->deleteSupplierQuotations();
                break;

                case 'get cif suppliers for quotation':
                    if (!isset($_POST['cif_id']) || empty($_POST['cif_id'])) {
                        echo json_encode(['success' => false, 'message' => 'Invalid CIF ID.']);
                        exit;
                    }
                    
                    $cif_id = $_POST['cif_id'];
                    $suppliers = $this->leadModel->getCifSuppliersWithDetails($cif_id); // Returns array of suppliers + existing quotation/remarks/file path

                    echo json_encode([
                        'success' => true,
                        'suppliers' => $suppliers
                    ]);
                    exit;
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
        
        // Check if the user already clicked "Yes" to override the duplicate warning
        $confirmDuplicate = isset($_POST['confirm_duplicate']) && $_POST['confirm_duplicate'] == '1';

        /* =========================
            SANITY & SANITIZE INPUTS
        ========================== */

        $firstName      = trim(htmlspecialchars($_POST['first_name'] ?? '', ENT_QUOTES, 'UTF-8'));
        $middleName     = trim(htmlspecialchars($_POST['middle_name'] ?? '', ENT_QUOTES, 'UTF-8'));
        $lastName       = trim(htmlspecialchars($_POST['last_name'] ?? '', ENT_QUOTES, 'UTF-8'));
        $corporateName  = trim(htmlspecialchars($_POST['corporate_name'] ?? '', ENT_QUOTES, 'UTF-8'));
        $address        = trim(htmlspecialchars($_POST['address'] ?? '', ENT_QUOTES, 'UTF-8'));
        $cityID         = !empty($_POST['city_id']) ? (int) $_POST['city_id'] : null;
        $email          = trim(htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'));
        $phone          = trim(htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8'));
        $social_media_account = trim(htmlspecialchars($_POST['social_media_account'] ?? '', ENT_QUOTES, 'UTF-8'));
        $genderID       = !empty($_POST['gender_id']) ? (int) $_POST['gender_id'] : null;
        $leadStatusID   = !empty($_POST['lead_status_id']) ? (int) $_POST['lead_status_id'] : null;
        $leadSourceID   = !empty($_POST['lead_source_id']) ? (int) $_POST['lead_source_id'] : null;
        $leadPriority   = !empty($_POST['lead_priority']) ? $_POST['lead_priority'] : null;
        $inquiryTypeID  = !empty($_POST['inquiry_type_id']) ? (int) $_POST['inquiry_type_id'] : null;
        $remarks        = trim(htmlspecialchars($_POST['remarks'] ?? '', ENT_QUOTES, 'UTF-8'));
        $inquiry_date   = $this->systemModel->checkDate('empty', $_POST['inquiry_date'] ?? '', '', 'Y-m-d', '');

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
            CHECK DUPLICATE LEAD
        ========================== */

        if (!$confirmDuplicate) {
            $duplicate = $this->leadModel->checkDuplicateLead($firstName, $lastName, $leadID);
            
            if ($duplicate) {
                $assignedTo = !empty($duplicate['assigned_user_name']) 
                    ? $duplicate['assigned_user_name'] 
                    : 'another agent';

                echo json_encode([
                    'success' => false,
                    'isDuplicate' => true,
                    'message' => 'The lead name "' . $firstName . ' ' . $lastName . '" was already encoded and assigned to ' . $assignedTo . '. Do you still want to proceed?'
                ]);
                exit;
            }
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
                $address,
                $cityID,
                $email,
                $phone,
                $social_media_account,
                $genderID,
                $leadStatusID,
                $inquiryTypeID,
                $leadSourceID,
                $leadPriority,
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
                $address,
                $cityID,
                $email,
                $phone,
                $social_media_account,
                $genderID,
                $leadStatusID,
                $inquiryTypeID,
                $leadSourceID,
                $leadPriority,
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

    public function saveProductInquiry() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $leadID = htmlspecialchars($_POST['lead_id'] ?? '', ENT_QUOTES, 'UTF-8');
        $stockNumber    = trim(htmlspecialchars($_POST['stock_number'] ?? '', ENT_QUOTES, 'UTF-8'));
        $unit_description  = !empty($_POST['unit_description']) ? $_POST['unit_description'] : null;
        $product_subcategory_id  = !empty($_POST['product_subcategory_id']) ? $_POST['product_subcategory_id'] : null;
        $engine_no  = !empty($_POST['engine_no']) ? $_POST['engine_no'] : null;
        $chassis_no  = !empty($_POST['chassis_no']) ?  $_POST['chassis_no'] : null;
        $color  = !empty($_POST['color']) ? $_POST['color'] : null;
        $year_model  = !empty($_POST['year_model']) ? $_POST['year_model'] : null;
        $transmission_type  = !empty($_POST['transmission_type']) ? $_POST['transmission_type'] : null;
        $length  = !empty($_POST['length']) ? $_POST['length'] : null;
        $length_unit  = !empty($_POST['length_unit']) ? $_POST['length_unit'] : null;

        $this->leadModel->insertLeadProductInquiry(
            $leadID,
            $stockNumber,
            $product_subcategory_id,
            $unit_description,
            $engine_no,
            $chassis_no,
            $color,
            $year_model,
            $transmission_type,
            $length,
            $length_unit,
            $userID
        );

        echo json_encode([
            'success' => true,
        ]);

        exit;
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

    public function saveSpaf() {
        // Ensure it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
            exit;
        }

        try {
            // Check if user session exists
            if (!isset($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'User session expired or not found.']);
                exit;
            }

            $userID = $_SESSION['user_id'];
            
            // Use null-coalescing operators to prevent undefined index notices
            $spaf_id = !empty($_POST['spaf_id']) ? $_POST['spaf_id'] : null;
            $cif_id = $_POST['cif_id'] ?? null;
            $supplier_id = $_POST['spaf_supplier_id'] ?? null;
            $unit_price = $_POST['unit_price'] ?? 0;
            $dealer_discount = $_POST['dealer_discount'] ?? 0;
            $net_price = $_POST['net_price'] ?? 0;
            $remarks = $_POST['remarks'] ?? '';

            $cifDetails = $this->leadModel->getCIF($cif_id);
            $lead_id = $cifDetails['lead_id'];
            $leads_product_inquiry_id = $cifDetails['leads_product_inquiry_id'];

            // Validate required fields
            if (empty($cif_id)) {
                echo json_encode(['success' => false, 'message' => 'CIF ID is required.']);
                exit;
            }

            // Safely check existence only if spaf_id is present and numeric
            $checkExist = ($spaf_id) ? $this->leadModel->checkSPAFExist($spaf_id) : false;

            if ($checkExist) {
                $this->leadModel->updateSPAF($spaf_id, $cif_id, $lead_id, $leads_product_inquiry_id, $supplier_id, $unit_price, $dealer_discount, $net_price, $remarks, $userID);
            } else {
                $this->leadModel->insertSPAF($cif_id, $lead_id, $leads_product_inquiry_id, $supplier_id, $unit_price, $dealer_discount, $net_price, $remarks, $userID);
            }

            echo json_encode(['success' => true]);

        } catch (PDOException $e) {
            // Catch database errors (like foreign key constraint failures)
            echo json_encode(['success' => false, 'message' => 'Database Error: ' . $e->getMessage()]);
        } catch (Exception $e) {
            // Catch general errors
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
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

    public function saveCif() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID =$_SESSION['user_id'] ?? null;
        $lead_id =$_POST['lead_id'] ?? null;
        $cif_id =$_POST['cif_id'] ?? null;
        $leads_product_inquiry_id2 =$_POST['leads_product_inquiry_id2'] ?? null;
        $company_id2 =$_POST['company_id2'] ?? null;
        
        // Capture and sanitize multiple supplier IDs from the array
        $rawSuppliers =$_POST['supplier_id'] ?? [];
        $suppliers = array_filter(array_map('intval', (array)$rawSuppliers));

        $add_ons = $_POST['add_ons'] ?? 0;
        $remarks_3 =$_POST['remarks_3'] ?? '';

        $filePath = null;
        $isNewUpload = isset($_FILES['cif_image']) &&$_FILES['cif_image']['error'] !== UPLOAD_ERR_NO_FILE;

        /* =========================================================
        1. VALIDATE & UPLOAD FILE FIRST
        ========================================================= */
        if ($isNewUpload) {
            $file =$_FILES['cif_image'];

            if (!empty($file['error'])) {
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }

            if (empty($file['tmp_name'])) {
                echo json_encode(['success' => false, 'message' => 'Please choose the cif image.']);
                exit;
            }

            // Validate Extension
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));$uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);$allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {$fileExtensionDetails = $this->fileExtensionModel->getFileExtension($row['file_extension_id']);
                $allowedFileExtensions[] = strtolower($fileExtensionDetails['file_extension_name']);
            }

            if (!in_array($fileExtension,$allowedFileExtensions)) {
                echo json_encode(['success' => false, 'message' => 'The file uploaded is not supported.']);
                exit;
            }

            // Validate Max File Size (Bug fixed: added multiplication operator *)
            $uploadSetting =$this->uploadSettingModel->getUploadSetting(7);
            $maxFileSizeMb =$uploadSetting['max_file_size'];
            
            if ($file['size'] > ($maxFileSizeMb * 1048576)) {
                echo json_encode(['success' => false, 'message' => 'The cif image exceeds the maximum allowed size of ' . $maxFileSizeMb . ' MB.']);
                exit;
            }

            // Prepare File Paths
            $fileName = $this->securityModel->generateFileName() . '.' .$fileExtension;
            
            $folderId = !empty($cif_id) ?$cif_id : 'temp'; 
            $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'cif/' .$folderId . '/';
            $fileDestination =$_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'cif/' . $folderId . '/' .$fileName;

            if (!$this->securityModel->directoryChecker('.' .$directory)) {
                echo json_encode(['success' => false, 'message' => 'Failed to verify or create upload directory.']);
                exit;
            }

            // Move the uploaded file before touching DB
            if (!move_uploaded_file($file['tmp_name'],$fileDestination)) {
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $filePath = $directory .$fileName;
        }

        /* =========================================================
        2. DATABASE OPERATIONS (INSERT / UPDATE)
        ========================================================= */
        $checkCIFExist = !empty($cif_id) ? $this->leadModel->checkCIFExist($cif_id) : null;
        $total =$checkCIFExist['total'] ?? 0;

        if ($total > 0) {
            // UPDATE Existing CIF
            $this->leadModel->updateCIF($cif_id, $company_id2,$add_ons, $remarks_3,$userID);

            // Remove old image if a new one was successfully uploaded
            if ($filePath) {
                $cifDetails = $this->leadModel->getCIF($cif_id);
                if (!empty($cifDetails['cif_image']) && file_exists('.' .$cifDetails['cif_image'])) {
                    unlink('.' . $cifDetails['cif_image']);
                }
                $this->leadModel->updateCIFImage($cif_id, $filePath,$userID);
            }
        } else {
            $cif_reference_no =$this->systemSettingModel->getSystemSetting(53)['value'] + 1;

            // INSERT New CIF
            $cif_id = $this->leadModel->insertCIF($lead_id,
                $leads_product_inquiry_id2,$cif_reference_no,
                $company_id2,$add_ons,
                $remarks_3,$userID
            );

            $this->systemSettingModel->updateSystemSettingValue(53, $cif_reference_no,$userID);

            // Move file from temporary directory to actual directory if new ID was generated
            if ($filePath) {
                $newDirectory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'cif/' .$cif_id . '/';
                $newFileDestination =$_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'cif/' . $cif_id . '/' .$fileName;

                $this->securityModel->directoryChecker('.' .$newDirectory);
                
                if (file_exists($fileDestination)) {
                    rename($fileDestination, $newFileDestination);$filePath = $newDirectory .$fileName;
                }

                $this->leadModel->updateCIFImage($cif_id, $filePath,$userID);
            }
        }

        /* =========================================================
        3. SAVE / SYNC SUPPLIERS TO `cif_supplier`
        ========================================================= */
        // Clear out old mapping for this CIF and insert the current selections
        $this->leadModel->deleteCIFSuppliers($cif_id);
        
        foreach ($suppliers as$supplier_id) {
            $this->leadModel->insertCIFSupplier($cif_id, $supplier_id,$userID);
        }

        /* =========================================================
        4. RESPONSE
        ======================================================== */
        echo json_encode([
            'success' => true,
            'cifID' => $this->securityModel->encryptData($cif_id)
        ]);
    }

    public function saveSupplierQuotations() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'] ?? null;
        $cif_id = $_POST['cif_id'] ?? null;
        $supplierData = $_POST['suppliers'] ?? []; // Array containing remarks, delete flags per cif_supplier_id

        if (empty($cif_id)) {
            echo json_encode(['success' => false, 'message' => 'CIF ID is missing.']);
            exit;
        }

        foreach ($supplierData as $cif_supplier_id => $data) {
            $quoted_amount = $data['quoted_amount'] ?? '';
            $remarks = $data['remarks'] ?? '';
            $deleteFile = isset($data['delete_file']) && $data['delete_file'] == '1';

            // Fetch current supplier details
            $currentSupplier = $this->leadModel->getCifSupplierById($cif_supplier_id);
            $filePath = $currentSupplier['supplier_quotation'] ?? null;

            // Handle File Deletion if requested
            if ($deleteFile && !empty($filePath)) {
                if (file_exists('.' . $filePath)) {
                    unlink('.' . $filePath);
                }
                $filePath = null;
            }

            // Handle New File Upload if provided
            if (isset($_FILES['suppliers']['name'][$cif_supplier_id]['quotation_file']) && 
                $_FILES['suppliers']['error'][$cif_supplier_id]['quotation_file'] === UPLOAD_ERR_OK) {
                
                $file = [
                    'name' => $_FILES['suppliers']['name'][$cif_supplier_id]['quotation_file'],
                    'type' => $_FILES['suppliers']['type'][$cif_supplier_id]['quotation_file'],
                    'tmp_name' => $_FILES['suppliers']['tmp_name'][$cif_supplier_id]['quotation_file'],
                    'error' => $_FILES['suppliers']['error'][$cif_supplier_id]['quotation_file'],
                    'size' => $_FILES['suppliers']['size'][$cif_supplier_id]['quotation_file']
                ];

                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                // Generate unique filename & directory
                $fileName = $this->securityModel->generateFileName() . '.' . $fileExtension;
                $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'cif_quotation/' . $cif_id . '/';
                $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'cif_quotation/' . $cif_id . '/' . $fileName;

                if ($this->securityModel->directoryChecker('.' . $directory)) {
                    // Delete old file if exists before replacing
                    if (!empty($currentSupplier['supplier_quotation']) && file_exists('.' . $currentSupplier['supplier_quotation'])) {
                        unlink('.' . $currentSupplier['supplier_quotation']);
                    }

                    if (move_uploaded_file($file['tmp_name'], $fileDestination)) {
                        $filePath = $directory . $fileName;
                    }
                }
            }

            // Update database record for this supplier mapping
            $this->leadModel->updateCifSupplierQuotation($cif_supplier_id, $filePath, $quoted_amount, $remarks, $userID);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Supplier quotations saved successfully.'
        ]);
        exit;
    }

    public function deleteSupplierQuotations() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'] ?? null;
        $supplier_id = $_POST['supplier_id'] ?? [];

        $this->leadModel->updateCifSupplierQuotation($supplier_id, '', 0, '', $userID);

        echo json_encode([
            'success' => true,
            'message' => 'Supplier quotations saved successfully.'
        ]);
        exit;
    }

    public function saveSupplier() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'] ?? null;
        $cif_id = $_POST['cif_id'] ?? null;
        $supplier_id = $_POST['supplier_id'] ?? null;

        $checkCIFSupplierExist = $this->leadModel->checkCIFSupplierExist($cif_id, $supplier_id);
        $total = $checkCIFSupplierExist['total'] ?? 0;
    
        if ($total == 0) {
            $this->leadModel->insertCIFSupplier($cif_id, $supplier_id, $userID);

            echo json_encode(['success' => true]);
            exit;
        }       
       
        echo json_encode([
            'success' => true
        ]);
    }

    public function saveQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'] ?? null;
        $lead_id = $_POST['lead_id'] ?? null;
        $quotation_id = $_POST['quotation_id'] ?? null;
        $leads_product_inquiry_id = $_POST['leads_product_inquiry_id'] ?? null;
        $company_id = $_POST['company_id'] ?? null;
        $price = $_POST['price'] ?? 0;
        $quantity = $_POST['quantity'] ?? 0;
        $discount = $_POST['discount'] ?? 0;
        $inclusions = $_POST['inclusions'] ?? '';
        $delivery_address = $_POST['delivery_address'] ?? '';
        $delivery_instructions = $_POST['delivery_instructions'] ?? '';
        $delivery_conditions = $_POST['delivery_conditions'] ?? '';
        $pre_approve = $_POST['pre_approve'] ?? 'No';
        $expiration_date = $this->systemModel->checkDate('empty', $_POST['expiration_date'], '', 'Y-m-d', '');
        $remarks = $_POST['remarks2'] ?? '';

        $filePath = null;
        $isNewUpload = isset($_FILES['quotation_image']) && $_FILES['quotation_image']['error'] !== UPLOAD_ERR_NO_FILE;

        /* =========================================================
        1. VALIDATE & UPLOAD QUOTATION IMAGE FIRST (UNCHANGED)
        ========================================================= */
        if ($isNewUpload) {
            $file = $_FILES['quotation_image'];

            if (!empty($file['error'])) {
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }

            if (empty($file['tmp_name'])) {
                echo json_encode(['success' => false, 'message' => 'Please choose the quotation image.']);
                exit;
            }

            // Validate Extension
            $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);
            $allowedFileExtensions = [];

            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($row['file_extension_id']);
                $allowedFileExtensions[] = strtolower($fileExtensionDetails['file_extension_name']);
            }

            if (!in_array($fileExtension, $allowedFileExtensions)) {
                echo json_encode(['success' => false, 'message' => 'The file uploaded is not supported.']);
                exit;
            }

            // Validate Max File Size
            $uploadSetting = $this->uploadSettingModel->getUploadSetting(7);
            $maxFileSizeMb = $uploadSetting['max_file_size'];
            
            if ($file['size'] > ($maxFileSizeMb * 1048576)) {
                echo json_encode(['success' => false, 'message' => 'The quotation image exceeds the maximum allowed size of ' . $maxFileSizeMb . ' MB.']);
                exit;
            }

            $fileName = $this->securityModel->generateFileName() . '.' . $fileExtension;
            $folderId = !empty($quotation_id) ? $quotation_id : 'temp'; 
            $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'quotation/' . $folderId . '/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/' . $folderId . '/' . $fileName;

            if (!$this->securityModel->directoryChecker('.' . $directory)) {
                echo json_encode(['success' => false, 'message' => 'Failed to verify or create upload directory.']);
                exit;
            }

            if (!move_uploaded_file($file['tmp_name'], $fileDestination)) {
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }

            $filePath = $directory . $fileName;
        }

        /* =========================================================
        1.5. HANDLE APPROVAL CONFIRMATION UPLOAD / REMOVAL
        ========================================================= */
        $approvalFilePath = null;
        $isApprovalUpload = isset($_FILES['approval_confirmation']) && $_FILES['approval_confirmation']['error'] !== UPLOAD_ERR_NO_FILE;

        if ($pre_approve === 'Yes' && $isApprovalUpload) {
            $approvalFile = $_FILES['approval_confirmation'];

            if (empty($approvalFile['error']) && !empty($approvalFile['tmp_name'])) {
                $approvalExtension = strtolower(pathinfo($approvalFile['name'], PATHINFO_EXTENSION));
                // You can reuse setting 7 or your preferred validation logic
                $approvalFileName = 'approval_' . $this->securityModel->generateFileName() . '.' . $approvalExtension;
                $folderId = !empty($quotation_id) ? $quotation_id : 'temp'; 
                $approvalDirectory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'quotation/' . $folderId . '/';
                $approvalDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/' . $folderId . '/' . $approvalFileName;

                if ($this->securityModel->directoryChecker('.' . $approvalDirectory)) {
                    if (move_uploaded_file($approvalFile['tmp_name'], $approvalDestination)) {
                        $approvalFilePath = $approvalDirectory . $approvalFileName;
                    }
                }
            }
        }

        /* =========================================================
        2. DATABASE OPERATIONS (INSERT / UPDATE)
        ========================================================= */
        $checkQuotationExist = !empty($quotation_id) ? $this->leadModel->checkQuotationExist($quotation_id) : null;
        $total = $checkQuotationExist['total'] ?? 0;

        if ($total > 0) {
            // UPDATE Existing Quotation
            $this->leadModel->updateQuotation($quotation_id, $company_id, $price, $quantity, $discount, $inclusions, $delivery_address, $delivery_instructions, $delivery_conditions, $remarks, $expiration_date, $pre_approve, $userID);

            // Handle Quotation Image Update
            if ($filePath) {
                $quotationDetails = $this->leadModel->getQuotation($quotation_id);
                if (!empty($quotationDetails['quotation_image']) && file_exists('.' . $quotationDetails['quotation_image'])) {
                    unlink('.' . $quotationDetails['quotation_image']);
                }
                $this->leadModel->updateQuotationImage($quotation_id, $filePath, $userID);
            }

            // Handle Approval Confirmation based on pre_approve value
            $quotationDetails = $this->leadModel->getQuotation($quotation_id);
            if ($pre_approve === 'Yes') {
                if ($approvalFilePath) {
                    // Remove old approval confirmation if exists
                    if (!empty($quotationDetails['approval_confirmation']) && file_exists('.' . $quotationDetails['approval_confirmation'])) {
                        unlink('.' . $quotationDetails['approval_confirmation']);
                    }
                    $this->leadModel->updateApprovalConfirmation($quotation_id, $approvalFilePath, $userID);
                }
            } else {
                // If pre_approve is NOT 'Yes', delete existing approval file and clear DB path
                if (!empty($quotationDetails['approval_confirmation']) && file_exists('.' . $quotationDetails['approval_confirmation'])) {
                    unlink('.' . $quotationDetails['approval_confirmation']);
                }
                $this->leadModel->updateApprovalConfirmation($quotation_id, '', $userID);
            }

        } else {
            // INSERT New Quotation
            $quotation_id = $this->leadModel->insertQuotation(
                $lead_id,
                $leads_product_inquiry_id,
                $company_id,
                $price,
                $quantity,
                $discount,
                $inclusions,
                $delivery_address,
                $delivery_instructions,
                $delivery_conditions,
                $remarks,
                $expiration_date,
                $pre_approve,
                $userID
            );

            $newDirectory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . 'quotation/' . $quotation_id . '/';
            $this->securityModel->directoryChecker('.' . $newDirectory);

            // Move temporary Quotation Image to real ID folder
            if ($filePath) {
                $newFileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/' . $quotation_id . '/' . basename($filePath);
                $tempFileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/temp/' . basename($filePath);

                if (file_exists($tempFileDestination)) {
                    rename($tempFileDestination, $newFileDestination);
                    $filePath = $newDirectory . basename($filePath);
                }
                $this->leadModel->updateQuotationImage($quotation_id, $filePath, $userID);
            }

            // Move temporary Approval Confirmation to real ID folder (if pre_approve is Yes)
            if ($pre_approve === 'Yes' && $approvalFilePath) {
                $newApprovalDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/' . $quotation_id . '/' . basename($approvalFilePath);
                $tempApprovalDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . 'quotation/temp/' . basename($approvalFilePath);

                if (file_exists($tempApprovalDestination)) {
                    rename($tempApprovalDestination, $newApprovalDestination);
                    $approvalFilePath = $newDirectory . basename($approvalFilePath);
                }
                $this->leadModel->updateApprovalConfirmation($quotation_id, $approvalFilePath, $userID);
            }
        }

        /* =========================================================
        3. RESPONSE
        ========================================================= */
        echo json_encode([
            'success' => true,
            'quotationID' => $this->securityModel->encryptData($quotation_id)
        ]);
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

    public function deleteProductInquiry() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $leads_product_inquiry_id = (int) $_POST['leads_product_inquiry_id'];

        $this->leadModel->deleteProductInquiry(
            $leads_product_inquiry_id
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
                'leadPriority' => $leadDetails['lead_priority'],
                'leadSourceId' => $leadDetails['lead_source_id'],

                'inquiryTypeId' => $leadDetails['inquiry_type_id'],
                'unitDescription' => $leadDetails['unit_description'],
                'engineNo' => $leadDetails['engine_no'],
                'chassisNo' => $leadDetails['chassis_no'],
                'color' => $leadDetails['color'],
                'yearModel' => $leadDetails['year_model'],
                'inquiryDate' =>  $this->systemModel->checkDate('empty', $leadDetails['inquiry_date'], '', 'm/d/Y', ''),

                'remarks' => $leadDetails['remarks']
            ]);
            exit;
        }
    }

    public function getQuotationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['quotation_id']) && !empty($_POST['quotation_id'])) {

            $userID = $_SESSION['user_id'];
            $quotation_id = $_POST['quotation_id'];

            $quotationDetails = $this->leadModel->getQuotation($quotation_id);

            echo json_encode([
                'success' => true,
                'leads_product_inquiry_id' => $quotationDetails['leads_product_inquiry_id'],
                'company_id' => $quotationDetails['company_id'],
                'price' => $quotationDetails['price'],
                'quantity' => $quotationDetails['quantity'],
                'discount' => $quotationDetails['discount'],
                'inclusions' => $quotationDetails['inclusions'],
                'delivery_address' => $quotationDetails['delivery_address'],
                'delivery_instructions' => $quotationDetails['delivery_instructions'],
                'delivery_conditions' => $quotationDetails['delivery_conditions'],
                'remarks' => $quotationDetails['remarks'],
                'expiration_date' =>  $this->systemModel->checkDate('empty', $quotationDetails['expiration_date'], '', 'm/d/Y', ''),
            ]);
            exit;
        }
    }

    public function getCifDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['cif_id']) && !empty($_POST['cif_id'])) {
            $cif_id = $_POST['cif_id'];

            // Get main CIF details
            $cifDetails = $this->leadModel->getCif($cif_id);
            
            // Safety check if CIF exists
            if (!$cifDetails) {
                echo json_encode(['success' => false, 'message' => 'CIF record not found.']);
                exit;
            }

            // Fetch supplier IDs from the relational table for Select2
            $supplierIds = $this->leadModel->getCifSuppliers($cif_id);

            $file_as = $this->leadModel->getLead($cifDetails['lead_id'])['file_as'] ?? null;

            $leadInquiryDetails = $this->leadModel->getLeadInquiryDetails($cifDetails['leads_product_inquiry_id']);
            $product_id = $leadInquiryDetails['product_id'] ?? null;
            $unit_description = $leadInquiryDetails['unit_description'] ?? null;

            $productDetails = $this->productModel->getProduct($product_id);
            $stock_number = !empty($productDetails['stock_number'] ?? '') ? $productDetails['stock_number'] . ' - ' : '';

            $companyDetails = $this->companyModel->getCompany($cifDetails['company_id']);
            $companyName = $companyDetails['company_name'] ?? '';

            echo json_encode([
                'success' => true,
                'leads_product_inquiry_id' => $cifDetails['leads_product_inquiry_id'],
                'company_id' => $cifDetails['company_id'],
                'companyName' => $companyName,
                'file_as' => $file_as,
                'product' => $stock_number . ' ' . $unit_description,
                'cif_reference_no' => $cifDetails['cif_reference_no'],
                'add_ons' => $cifDetails['add_ons'],
                'supplier_id' => $supplierIds,
                'remarks' => $cifDetails['remarks'],
            ]);
            exit;
        }
    }

    public function getCifSupplierDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['cif_id']) && !empty($_POST['cif_id'])) {
            $cif_id = $_POST['cif_id'];
            $spaf_supplier_id = $_POST['spaf_supplier_id'];

            // Get main CIF details
            $cifDetails = $this->leadModel->getCif($cif_id);
            
            // Safety check if CIF exists
            if (!$cifDetails) {
                echo json_encode(['success' => false, 'message' => 'CIF record not found.']);
                exit;
            }

            // Fetch supplier IDs from the relational table for Select2
            $supplierDetails = $this->leadModel->getCifSupplierDetails($cif_id, $spaf_supplier_id);

            echo json_encode([
                'success' => true,
                'quoted_amount' => $supplierDetails['quoted_amount'] ?? 0
            ]);
            exit;
        }
    }

    public function getSPAFDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['spaf_id']) && !empty($_POST['spaf_id'])) {

            $userID = $_SESSION['user_id'];
            $spaf_id = $_POST['spaf_id'];

            $spafDetails = $this->leadModel->getSpaf($spaf_id);

            echo json_encode([
                'success' => true,
                'cif_id' => $spafDetails['cif_id'],
                'unit_price' => $spafDetails['unit_price'],
                'supplier_id' => $spafDetails['supplier_id'],
                'dealer_discount' => $spafDetails['dealer_discount'],
                'net_price' => $spafDetails['net_price'],
                'remarks' => $spafDetails['remarks'],
            ]);
            exit;
        }
    }

    public function getInclusionsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_POST['leads_product_inquiry_id']) && !empty($_POST['leads_product_inquiry_id'])) {

            $userID = $_SESSION['user_id'];
            $leads_product_inquiry_id = $_POST['leads_product_inquiry_id'];
            $standard_inclusions = '';

            $productInquiryDetails = $this->leadModel->getProductInquiry($leads_product_inquiry_id);
            $productId = $productInquiryDetails['product_id'] ?? null;
            $productSubcategoryId = $productInquiryDetails['product_subcategory_id'] ?? null;

            if (!empty($productId)) {
                $productDetails = $this->productModel->getProduct($productId);
                $productSubcategoryId = $productDetails['product_subcategory_id'] ?? $productSubcategoryId;
            }

            if (!empty($productSubcategoryId)) {
                $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryId);
                $standard_inclusions = $productSubcategoryDetails['standard_inclusions'] ?? null;
            }
            
            echo json_encode([
                'success' => true,
                'standard_inclusions' => $standard_inclusions,
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

    public function deleteQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_id = htmlspecialchars($_POST['quotation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkQuotationExist = $this->leadModel->checkQuotationExist($quotation_id);
        $total = $checkQuotationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $quotationDetails = $this->leadModel->getQuotation($quotation_id);
        $quotation_image = !empty($quotationDetails['quotation_image']) ? '.' . $quotationDetails['quotation_image'] : null;

        if(file_exists($quotation_image)){
            if (!unlink($quotation_image)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->leadModel->deleteQuotation($quotation_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteCif() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cif_id = htmlspecialchars($_POST['cif_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCifExist = $this->leadModel->checkCifExist($cif_id);
        $total = $checkCifExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $cifDetails = $this->leadModel->getCif($cif_id);
        $cif_image = !empty($cifDetails['cif_image']) ? '.' . $cifDetails['cif_image'] : null;

        if(file_exists($cif_image)){
            if (!unlink($cif_image)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->leadModel->deleteCIF($cif_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deleteSpaf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $spaf_id = htmlspecialchars($_POST['spaf_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leadModel->deleteSpaf($spaf_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function forApprovalQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];
        $quotation_id = htmlspecialchars($_POST['quotation_id'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkQuotationExist = $this->leadModel->checkQuotationExist($quotation_id);
        $total = $checkQuotationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' => true]);
            exit;
        }

        $quotationDetails = $this->leadModel->getQuotation($quotation_id);

        // Early exit if quotation or inquiry ID doesn't exist
        $leadsProductInquiryId = $quotationDetails['leads_product_inquiry_id'] ?? null;
        if (!$leadsProductInquiryId) {
            $this->leadModel->forApprovalQuotation($quotation_id, $userID);
            return;
        }

        $price = $quotationDetails['price'] ?? 0;
        $discount = $quotationDetails['discount'] ?? 0;
        $company_id = $quotationDetails['company_id'];
        $status = $quotationDetails['status'];
        $pre_approve = $quotationDetails['pre_approve'] ?? 'No';
        $approval_confirmation = $quotationDetails['approval_confirmation'] ?? null;

        $productInquiryDetails = $this->leadModel->getProductInquiry($leadsProductInquiryId);
        $productId = $productInquiryDetails['product_id'] ?? null;
        $productSubcategoryId = $productInquiryDetails['product_subcategory_id'] ?? null;
        $year_model = $productInquiryDetails['year_model'] ?? null;
        $transmission_type = $productInquiryDetails['transmission_type'] ?? null;
        $length = $productInquiryDetails['length'] ?? null;
        $length_unit = $productInquiryDetails['length_unit'] ?? null;

        $priceThreshold = 0;
        $discountThreshold = 0;
        $hasMatch = true; // Track if a valid pricing rule is found

        if (!empty($productSubcategoryId)) {
            $productSubcategoryDetails = $this->productSubcategoryModel->getQuotationStandardPricing($productSubcategoryId, $year_model, $transmission_type, $length, $length_unit);
            
            if (empty($productSubcategoryDetails)) {
                $hasMatch = false; // No match found for this subcategory configuration
            } else {
                $priceThreshold = $productSubcategoryDetails['price_threshold'] ?? 0;
                $discountThreshold = $productSubcategoryDetails['discount_threshold'] ?? 0;
            }
        }

        if (!empty($productId)) {
            $productDetails = $this->productModel->getProduct($productId);
            $productSubcategoryId = $productDetails['product_subcategory_id'] ?? $productSubcategoryId;
            
            $product_price = $productSubcategoryDetails['product_price'] ?? 0;
            $best_price = $productSubcategoryDetails['best_price'] ?? 0;
            $discountThreshold = 50000;
            
            if($best_price > 0){
                $priceThreshold = $best_price;
            } else {
                $priceThreshold = $product_price;
            }
        }

        // You can group excluded company IDs in an array

        if($pre_approve == 'Yes' && !empty($approval_confirmation)){
            $this->leadModel->approveQuotation($quotation_id, $userID); 
        }
        else{
            $excludedCompanies = [3, 15];

            if (empty($productId) && !in_array($company_id, $excludedCompanies, true) && $status != 'For Checking') {
                $this->leadModel->forCheckingQuotation($quotation_id, $userID);
            } 
            else if (!$hasMatch || $price < $priceThreshold || $discount > $discountThreshold) {
                $this->leadModel->forApprovalQuotation($quotation_id, $userID);
            } 
            else {
                $this->leadModel->approveQuotation($quotation_id, $userID);
            }
        }
                    
        echo json_encode(['success' => true]);
        exit;
    }

    public function completeCIF() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cif_id = htmlspecialchars($_POST['cif_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFExist = $this->leadModel->checkCIFExist($cif_id);
        $total = $checkCIFExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->completeCIF($cif_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function approveSpaf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $spaf_id = htmlspecialchars($_POST['spaf_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->leadModel->approveCIF($spaf_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function onProcessCIF() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cif_id = htmlspecialchars($_POST['cif_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFExist = $this->leadModel->checkCIFExist($cif_id);
        $total = $checkCIFExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->onProcessCIF($cif_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function forApprovalSPAF() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $spaf_id = htmlspecialchars($_POST['spaf_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leadModel->forApprovalCIF($spaf_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function draftQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_id = htmlspecialchars($_POST['quotation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkQuotationExist = $this->leadModel->checkQuotationExist($quotation_id);
        $total = $checkQuotationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->draftQuotation($quotation_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_id = htmlspecialchars($_POST['quotation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkQuotationExist = $this->leadModel->checkQuotationExist($quotation_id);
        $total = $checkQuotationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->cancelQuotation($quotation_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelCIF() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cif_id = htmlspecialchars($_POST['cif_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIFExist = $this->leadModel->checkCIFExist($cif_id);
        $total = $checkCIFExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->cancelCIF($cif_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelSpaf() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $spaf_id = htmlspecialchars($_POST['spaf_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->leadModel->cancelSPAF($spaf_id, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function approveQuotation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_id = htmlspecialchars($_POST['quotation_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkQuotationExist = $this->leadModel->checkQuotationExist($quotation_id);
        $total = $checkQuotationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->leadModel->approveQuotation($quotation_id, $userID);
            
        echo json_encode(['success' => true]);
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
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/product-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/company-model.php';
require_once '../model/system-model.php';

$controller = new LeadController(
    new LeadModel(new DatabaseModel),
    new LeadStatusModel(new DatabaseModel),
    new EmployeeModel(new DatabaseModel),
    new ProductModel(new DatabaseModel),
    new ProductSubcategoryModel(new DatabaseModel),
    new CompanyModel(new DatabaseModel),
    new UserModel(new DatabaseModel, new SystemModel), 
    new UploadSettingModel(new DatabaseModel), 
    new FileExtensionModel(new DatabaseModel),
    new SystemSettingModel(new DatabaseModel),
    new SecurityModel(),
    new SystemModel()
);

$controller->handleRequest();