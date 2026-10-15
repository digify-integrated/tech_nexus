<?php
session_start();

# -------------------------------------------------------------
#
# Function: ProductSubcategoryController
# Description: 
# The ProductSubcategoryController class handles product subcategory related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ProductSubcategoryController {
    private $productSubcategoryModel;
    private $productCategoryModel;
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
    # The constructor initializes the object with the provided ProductSubcategoryModel, UserModel and SecurityModel instances.
    # These instances are used for product subcategory related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ProductSubcategoryModel $productSubcategoryModel     The ProductSubcategoryModel instance for product subcategory related operations.
    # - @param ProductCategoryModel $productSubcategoryModel     The ProductCategoryModel instance for product category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ProductSubcategoryModel $productSubcategoryModel, ProductCategoryModel $productCategoryModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->productCategoryModel = $productCategoryModel;
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
                case 'save product subcategory':
                    $this->saveProductSubcategory();
                    break;
                case 'save pricing threshold':
                    $this->savePricingThreshold();
                    break;
                case 'update default image':
                    $this->updateDefaultImage();
                    break;
                case 'get product subcategory details':
                    $this->getProductSubcategoryDetails();
                    break;
                case 'get quotation standard pricing details':
                    $this->getQuotationStandardPricingDetails();
                    break;
                case 'delete product subcategory':
                    $this->deleteProductSubcategory();
                    break;
                case 'delete multiple product subcategory':
                    $this->deleteMultipleProductSubcategory();
                    break;
                case 'delete pricing threshold':
                    $this->deletePricingThreshold();
                    break;
                case 'delete multiple pricing threshold':
                    $this->deleteMultiplePricingThreshold();
                    break;
                case 'duplicate product subcategory':
                    $this->duplicateProductSubcategory();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    public function updateDefaultImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $productSubcategoryID = htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID);
        $total = $checkProductSubcategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productImageFileName = $_FILES['default_image']['name'];
        $productImageFileSize = $_FILES['default_image']['size'];
        $productImageFileError = $_FILES['default_image']['error'];
        $productImageTempName = $_FILES['default_image']['tmp_name'];
        $productImageFileExtension = explode('.', $productImageFileName);
        $productImageActualFileExtension = strtolower(end($productImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(7);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(7);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($productImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($productImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the product image.']);
            exit;
        }
        
        if($productImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($productImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The product image exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $productImageActualFileExtension;

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $productSubcategoryID  . '/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $productSubcategoryID . '/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $productDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
        $productImage = !empty($productDetails['default_image']) ? '.' . $productDetails['default_image'] : null;

        if(file_exists($productImage)){
            if (!unlink($productImage)) {
                echo json_encode(['success' => false, 'message' => 'Product image cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($productImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->productSubcategoryModel->updateDefaultImage($productSubcategoryID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    # -------------------------------------------------------------
    #   Save methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveProductSubcategory
    # Description: 
    # Updates the existing product subcategory if it exists; otherwise, inserts a new product subcategory.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveProductSubcategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productSubcategoryID = isset($_POST['product_subcategory_id']) ? htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8') : null;
        $productSubcategoryName = htmlspecialchars($_POST['product_subcategory_name'], ENT_QUOTES, 'UTF-8');
        $productSubcategoryCode = htmlspecialchars($_POST['product_subcategory_code'], ENT_QUOTES, 'UTF-8');
        $productCategoryID = htmlspecialchars($_POST['product_category_id'], ENT_QUOTES, 'UTF-8');
        $standardInclusions = htmlspecialchars($_POST['standard_inclusions'], ENT_QUOTES, 'UTF-8');
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID);
        $total = $checkProductSubcategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productSubcategoryModel->updateProductSubcategory($productSubcategoryID, $productSubcategoryName, $productSubcategoryCode, $productCategoryID, $standardInclusions, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productSubcategoryID' => $this->securityModel->encryptData($productSubcategoryID)]);
            exit;
        } 
        else {
            $productSubcategoryID = $this->productSubcategoryModel->insertProductSubcategory($productSubcategoryName, $productSubcategoryCode, $productCategoryID, $standardInclusions, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'productSubcategoryID' => $this->securityModel->encryptData($productSubcategoryID)]);
            exit;
        }
    }
    
    public function savePricingThreshold() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];
        $quotation_standard_pricing_id = htmlspecialchars($_POST['quotation_standard_pricing_id'], ENT_QUOTES, 'UTF-8');
        $product_subcategory_id = htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8');
        $year_model = htmlspecialchars($_POST['year_model'], ENT_QUOTES, 'UTF-8');
        $transmission_type = htmlspecialchars($_POST['transmission_type'], ENT_QUOTES, 'UTF-8');
        $length = htmlspecialchars($_POST['length'], ENT_QUOTES, 'UTF-8');
        $length_unit = htmlspecialchars($_POST['length_unit'], ENT_QUOTES, 'UTF-8');
        $price_threshold = htmlspecialchars($_POST['price_threshold'], ENT_QUOTES, 'UTF-8');
        $discount_threshold = htmlspecialchars($_POST['discount_threshold'], ENT_QUOTES, 'UTF-8');

        // Check if the record already exists
        $checkExist = $this->productSubcategoryModel->checkPricingThresholdExist($product_subcategory_id, $year_model, $transmission_type, $length, $length_unit);

        if ($checkExist) {
            $this->productSubcategoryModel->updatePricingThreshold($quotation_standard_pricing_id, $product_subcategory_id, $year_model, $transmission_type, $length, $length_unit, $price_threshold, $discount_threshold, $userID);
        }
        else{
            $this->productSubcategoryModel->insertPricingThreshold($product_subcategory_id, $year_model, $transmission_type, $length, $length_unit, $price_threshold, $discount_threshold, $userID);
        }
        

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProductSubcategory
    # Description: 
    # Delete the product subcategory if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteProductSubcategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productSubcategoryID = htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID);
        $total = $checkProductSubcategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->productSubcategoryModel->deleteProductSubcategory($productSubcategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePricingThreshold() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_standard_pricing_id = htmlspecialchars($_POST['quotation_standard_pricing_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
        
        $this->productSubcategoryModel->deletePricingThreshold($quotation_standard_pricing_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleProductSubcategory
    # Description: 
    # Delete the selected product subcategories if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleProductSubcategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productSubcategoryIDs = $_POST['product_subcategory_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($productSubcategoryIDs as $productSubcategoryID){
            $this->productSubcategoryModel->deleteProductSubcategory($productSubcategoryID);
        }
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteMultiplePricingThreshold() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $quotation_standard_pricing_ids = $_POST['quotation_standard_pricing_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($quotation_standard_pricing_ids as $quotation_standard_pricing_id){
            $this->productSubcategoryModel->deletePricingThreshold($quotation_standard_pricing_id);
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
    # Function: duplicateProductSubcategory
    # Description: 
    # Duplicates the product subcategory if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateProductSubcategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productSubcategoryID = htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID);
        $total = $checkProductSubcategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productSubcategoryID = $this->productSubcategoryModel->duplicateProductSubcategory($productSubcategoryID, $userID);

        echo json_encode(['success' => true, 'productSubcategoryID' =>  $this->securityModel->encryptData($productSubcategoryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductSubcategoryDetails
    # Description: 
    # Handles the retrieval of product subcategory details such as product subcategory name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getProductSubcategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['product_subcategory_id']) && !empty($_POST['product_subcategory_id'])) {
            $userID = $_SESSION['user_id'];
            $productSubcategoryID = $_POST['product_subcategory_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
            $productCategoryID = $productSubcategoryDetails['product_category_id'];

            $productCategory = $this->productCategoryModel->getProductCategory($productCategoryID);
            $productCategoryName = $productCategory['product_category_name'];

            $response = [
                'success' => true,
                'productSubcategoryName' => $productSubcategoryDetails['product_subcategory_name'],
                'productSubcategoryCode' => $productSubcategoryDetails['product_subcategory_code'],
                'standardInclusions' => $productSubcategoryDetails['standard_inclusions'],
                'productCategoryID' => $productCategoryID,
                'productCategoryName' => $productCategoryName,
                'productImage' => $this->systemModel->checkImage($productSubcategoryDetails['default_image'], 'default'),
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getQuotationStandardPricingDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['quotation_standard_pricing_id']) && !empty($_POST['quotation_standard_pricing_id'])) {
            $userID = $_SESSION['user_id'];
            $quotation_standard_pricing_id = $_POST['quotation_standard_pricing_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $quotationStandardPricingDetails = $this->productSubcategoryModel->getQuotationStandardPricingDetails($quotation_standard_pricing_id);

            $response = [
                'success' => true,
                'product_subcategory_id' => $quotationStandardPricingDetails['product_subcategory_id'],
                'transmission_type' => $quotationStandardPricingDetails['transmission_type'],
                'length' => $quotationStandardPricingDetails['length'],
                'length_unit' => $quotationStandardPricingDetails['length_unit'],
                'year_model' => $quotationStandardPricingDetails['year_model'],
                'price_threshold' => $quotationStandardPricingDetails['price_threshold'],
                'discount_threshold' => $quotationStandardPricingDetails['discount_threshold'],
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
require_once '../model/product-subcategory-model.php';
require_once '../model/product-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/system-model.php';

$controller = new ProductSubcategoryController(new ProductSubcategoryModel(new DatabaseModel), new ProductCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>