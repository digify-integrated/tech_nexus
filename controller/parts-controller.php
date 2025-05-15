<?php
session_start();

# -------------------------------------------------------------
#
# Function: PartsController
# Description: 
# The PartsController class handles parts related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------

class PartsController {
    private $partsModel;
    private $partsCategoryModel;
    private $partsClassModel;
    private $partsSubclassModel;
    private $companyModel;
    private $supplierModel;
    private $warehouseModel;
    private $brandModel;
    private $unitModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemModel;
    private $systemSettingModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PartsModel, UserModel and SecurityModel instances.
    # These instances are used for parts related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PartsModel $partsModel     The PartsModel instance for parts related operations.
    # - @param PartsCategoryModel $partsCategoryModel     The PartsCategoryModel instance for parts category related operations.
    # - @param PartsSubcategoryModel $partsSubcategoryModel     The PartsSubcategoryModel instance for parts subcategory related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param BodyTypeModel $bodyTypeModel     The BodyTypeModel instance for body type related operations.
    # - @param WarehouseModel $warehouseModel     The WarehouseModel instance for warehouse related operations.
    # - @param UnitModel $unitModel     The UnitModel instance for unit related operations.
    # - @param ColorModel $colorModel     The ColorModel instance for color related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------adSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
    public function __construct(PartsModel $partsModel, PartsCategoryModel $partsCategoryModel, PartsClassModel $partsClassModel, PartsSubclassModel $partsSubclassModel, CompanyModel $companyModel, SupplierModel $supplierModel, WarehouseModel $warehouseModel, BrandModel $brandModel, UnitModel $unitModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->partsModel = $partsModel;
        $this->partsCategoryModel = $partsCategoryModel;
        $this->partsClassModel = $partsClassModel;
        $this->partsSubclassModel = $partsSubclassModel;
        $this->companyModel = $companyModel;
        $this->supplierModel = $supplierModel;
        $this->warehouseModel = $warehouseModel;
        $this->brandModel = $brandModel;
        $this->unitModel = $unitModel;    
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
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
                case 'save new parts':
                    $this->saveNewParts();
                    break;
                case 'save parts details':
                    $this->savePartsDetails();
                    break;
                case 'get parts details':
                    $this->getPartsDetails();
                    break;
                case 'update parts image':
                    $this->updatePartsImage();
                    break;
                case 'add parts document':
                    $this->addPartsDocument();
                    break;
                case 'insert parts image':
                    $this->insertPartsImage();
                    break;
                case 'delete parts':
                    $this->deleteParts();
                    break;
                case 'delete parts image':
                    $this->deletePartsImage();
                    break;
                case 'delete parts document':
                    $this->deletePartsDocument();
                    break;
                case 'delete parts expense':
                    $this->deletePartsExpense();
                    break;
                case 'tag for sale':
                    $this->tagPartsForSale();
                    break;
                case 'save parts expense':
                    $this->savePartsExpense();
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

    public function savePartsExpense() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
        $reference_type = $_POST['reference_type'];
        $reference_number = $_POST['reference_number'];
        $expense_amount = $_POST['expense_amount'];
        $expense_type = $_POST['expense_type'];
        $particulars = $_POST['particulars'];
        
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkProductExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsModel->insertPartsExpense($partsID, $reference_type, $reference_number, $expense_amount, $expense_type, $particulars, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        } 
    }

    public function tagPartsForSale() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkPartsExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsDetails = $this->partsModel->getParts($partsID);
        $partsPrice = $partsDetails['part_price'];

        if($partsPrice == 0){
            echo json_encode(['success' => false, 'zeroPrice' =>  true]);
            exit;
        }

        $this->partsModel->updatePartsStatus($partsID, 'For Sale', $userID);
            
        echo json_encode(['success' => true]);
    }

    # -------------------------------------------------------------
    #
    # Function: saveNewParts
    # Description: 
    # Updates the existing parts if it exists; otherwise, inserts a new parts.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveNewParts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brand_id = $_POST['brand_id'];
        $bar_code = $_POST['bar_code'];
        $part_category_id = $_POST['part_category_id'];
        $part_class_id = $_POST['part_class_id'];
        $part_subclass_id = $_POST['part_subclass_id'];
        $company_id = $_POST['company_id'];
        $quantity = $_POST['quantity'];
        $stock_alert = $_POST['stock_alert'];
        $unit_sale = $_POST['unit_sale'];
        $unit_purchase = $_POST['unit_purchase'];
        $remarks = $_POST['remarks'];
        $supplier_id = $_POST['supplier_id'];
        $jo_no = $_POST['jo_no'];
        $issuance_no = $_POST['issuance_no'];
        $warehouse_id = $_POST['warehouse_id'];
        $description = $_POST['description'];

        $issuance_date = $this->systemModel->checkDate('empty', $_POST['issuance_date'], '', 'Y-m-d', '');
        $jo_date = $this->systemModel->checkDate('empty', $_POST['jo_date'], '', 'Y-m-d', '');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $partsID = $this->partsModel->insertParts($part_category_id, $part_class_id, $part_subclass_id, $description, $bar_code, $company_id, $unit_sale, $unit_purchase, $stock_alert, $quantity, $brand_id, $warehouse_id, $issuance_date, $issuance_no, $jo_date, $jo_no, $supplier_id, $remarks, $userID);        

        echo json_encode(['success' => true, 'insertRecord' => true, 'partsID' => $this->securityModel->encryptData($partsID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: savePartsDetails
    # Description: 
    # Updates the existing parts if it exists; otherwise, inserts a new parts.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePartsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $parts_id = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
        $brand_id = $_POST['brand_id'];
        $bar_code = $_POST['bar_code'];
        $part_category_id = $_POST['part_category_id'];
        $part_class_id = $_POST['part_class_id'];
        $part_subclass_id = $_POST['part_subclass_id'];
        $company_id = $_POST['company_id'];
        $quantity = $_POST['quantity'];
        $stock_alert = $_POST['stock_alert'];
        $unit_sale = $_POST['unit_sale'];
        $unit_purchase = $_POST['unit_purchase'];
        $remarks = $_POST['remarks'];
        $supplier_id = $_POST['supplier_id'];
        $jo_no = $_POST['jo_no'];
        $issuance_no = $_POST['issuance_no'];
        $warehouse_id = $_POST['warehouse_id'];
        $description = $_POST['description'];
        $part_price = $_POST['part_price'];

        $issuance_date = $this->systemModel->checkDate('empty', $_POST['issuance_date'], '', 'Y-m-d', '');
        $jo_date = $this->systemModel->checkDate('empty', $_POST['jo_date'], '', 'Y-m-d', '');

    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsExist = $this->partsModel->checkPartsExist($parts_id);
        $total = $checkPartsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->partsModel->updateParts($parts_id, $part_category_id, $part_class_id, $part_subclass_id, $description, $bar_code, $company_id, $unit_sale, $unit_purchase, $stock_alert, $part_price, $quantity, $brand_id, $warehouse_id, $issuance_date, $issuance_no, $jo_date, $jo_no, $supplier_id, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'partsID' => $this->securityModel->encryptData($parts_id)]);
            exit;
        } 
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePartsImage
    # Description: 
    # Handles the update of the parts image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updatePartsImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkPartsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsImageFileName = $_FILES['parts_image']['name'];
        $partsImageFileSize = $_FILES['parts_image']['size'];
        $partsImageFileError = $_FILES['parts_image']['error'];
        $partsImageTempName = $_FILES['parts_image']['tmp_name'];
        $partsImageFileExtension = explode('.', $partsImageFileName);
        $partsImageActualFileExtension = strtolower(end($partsImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(7);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($partsImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($partsImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the parts image.']);
            exit;
        }
        
        if($partsImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($partsImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The parts image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $partsImageActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $partsID  . '/parts_images/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $partsID . '/parts_images/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $partsDetails = $this->partsModel->getParts($partsID);
        $partsImage = !empty($partsDetails['parts_image']) ? '.' . $partsDetails['parts_image'] : null;

        if(file_exists($partsImage)){
            if (!unlink($partsImage)) {
                echo json_encode(['success' => false, 'message' => 'Parts image cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($partsImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsModel->updatePartsImage($partsID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPartsImage
    # Description: 
    # Handles the update of the parts image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function insertPartsImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkPartsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsImageFileName = $_FILES['parts_other_image']['name'];
        $partsImageFileSize = $_FILES['parts_other_image']['size'];
        $partsImageFileError = $_FILES['parts_other_image']['error'];
        $partsImageTempName = $_FILES['parts_other_image']['tmp_name'];
        $partsImageFileExtension = explode('.', $partsImageFileName);
        $partsImageActualFileExtension = strtolower(end($partsImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(7);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($partsImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($partsImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the parts image.']);
            exit;
        }
        
        if($partsImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($partsImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The parts image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $partsImageActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $partsID  . '/parts_other_images/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $partsID . '/parts_other_images/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($partsImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsModel->insertPartsImage($partsID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addPartsDocument
    # Description: 
    # Handles the update of the parts image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addPartsDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $documentType = htmlspecialchars($_POST['document_type'], ENT_QUOTES, 'UTF-8');
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkPartsExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkPartsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsImageFileName = $_FILES['parts_document']['name'];
        $partsImageFileSize = $_FILES['parts_document']['size'];
        $partsImageFileError = $_FILES['parts_document']['error'];
        $partsImageTempName = $_FILES['parts_document']['tmp_name'];
        $partsImageFileExtension = explode('.', $partsImageFileName);
        $partsImageActualFileExtension = strtolower(end($partsImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(7);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($partsImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($partsImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the parts image.']);
            exit;
        }
        
        if($partsImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($partsImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The parts image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $partsImageActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $partsID  . '/part_document/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $partsID . '/part_document/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($partsImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->partsModel->insertPartsDocument($partsID, $documentType, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteParts
    # Description: 
    # Delete the parts if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteParts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsID = htmlspecialchars($_POST['parts_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsExist = $this->partsModel->checkPartsExist($partsID);
        $total = $checkPartsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsDetails = $this->partsModel->getParts($partsID);
        $partsImage = !empty($partsDetails['parts_image']) ? '.' . $partsDetails['parts_image'] : null;

        if(file_exists($partsImage)){
            if (!unlink($partsImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->partsModel->deleteParts($partsID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePartsExpense() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsExpenseID = htmlspecialchars($_POST['parts_expense_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->partsModel->deletePartsExpense($partsExpenseID);
    
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteParts
    # Description: 
    # Delete the parts if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePartsImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsImageID = htmlspecialchars($_POST['parts_image_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsImageExist = $this->partsModel->checkPartsImageExist($partsImageID);
        $total = $checkPartsImageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsDetails = $this->partsModel->getPartsImage($partsImageID);
        $partsImage = !empty($partsDetails['parts_image']) ? '.' . $partsDetails['parts_image'] : null;

        if(file_exists($partsImage)){
            if (!unlink($partsImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->partsModel->deletePartsImage($partsImageID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function deletePartsDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $partsImageID = htmlspecialchars($_POST['parts_document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPartsImageExist = $this->partsModel->checkPartsDocumentExist($partsImageID);
        $total = $checkPartsImageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $partsDetails = $this->partsModel->getPartsDocument($partsImageID);
        $partsImage = !empty($partsDetails['document_path']) ? '.' . $partsDetails['document_path'] : null;

        if(file_exists($partsImage)){
            if (!unlink($partsImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->partsModel->deletePartsDocument($partsImageID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPartsDetails
    # Description: 
    # Handles the retrieval of parts details such as parts name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPartsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['parts_id']) && !empty($_POST['parts_id'])) {
            $userID = $_SESSION['user_id'];
            $partsID = $_POST['parts_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $partsDetails = $this->partsModel->getParts($partsID);
        
            $response = [
                'success' => true,
                'bar_code' => $partsDetails['bar_code'],
                'quantity' => $partsDetails['quantity'],
                'stock_alert' => $partsDetails['stock_alert'],
                'description' => $partsDetails['description'],
                'remarks' => $partsDetails['remarks'],
                'jo_no' => $partsDetails['jo_no'],
                'issuance_no' => $partsDetails['issuance_no'],
                'brand_id' => $partsDetails['brand_id'],
                'part_category_id' => $partsDetails['part_category_id'],
                'part_class_id' => $partsDetails['part_class_id'],
                'part_subclass_id' => $partsDetails['part_subclass_id'],
                'company_id' => $partsDetails['company_id'],
                'unit_sale' => $partsDetails['unit_sale'],
                'unit_purchase' => $partsDetails['unit_purchase'],
                'part_price' => $partsDetails['part_price'],
                'supplier_id' => $partsDetails['supplier_id'],
                'warehouse_id' => $partsDetails['warehouse_id'],
                'part_image' => $this->systemModel->checkImage($partsDetails['part_image'], 'default'),
                'issuance_date' =>  $this->systemModel->checkDate('empty', $partsDetails['issuance_date'], '', 'm/d/Y', ''),
                'jo_date' =>  $this->systemModel->checkDate('empty', $partsDetails['jo_date'], '', 'm/d/Y', ''),
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
require_once '../model/parts-model.php';
require_once '../model/parts-category-model.php';
require_once '../model/parts-class-model.php';
require_once '../model/parts-subclass-model.php';
require_once '../model/company-model.php';
require_once '../model/supplier-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/brand-model.php';
require_once '../model/unit-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PartsController(new PartsModel(new DatabaseModel), new PartsCategoryModel(new DatabaseModel), new PartsClassModel(new DatabaseModel), new PartsSubclassModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new SupplierModel(new DatabaseModel), new WarehouseModel(new DatabaseModel), new BrandModel(new DatabaseModel), new UnitModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();

?>