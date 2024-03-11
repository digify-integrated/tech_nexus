<?php
session_start();

# -------------------------------------------------------------
#
# Function: ProductController
# Description: 
# The ProductController class handles product related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ProductController {
    private $productModel;
    private $productCategoryModel;
    private $productSubcategoryModel;
    private $companyModel;
    private $bodyTypeModel;
    private $warehouseModel;
    private $unitModel;
    private $colorModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ProductModel, UserModel and SecurityModel instances.
    # These instances are used for product related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ProductModel $productModel     The ProductModel instance for product related operations.
    # - @param ProductCategoryModel $productCategoryModel     The ProductCategoryModel instance for product category related operations.
    # - @param ProductSubcategoryModel $productSubcategoryModel     The ProductSubcategoryModel instance for product subcategory related operations.
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
    # -------------------------------------------------------------
    public function __construct(ProductModel $productModel, ProductCategoryModel $productCategoryModel, ProductSubcategoryModel $productSubcategoryModel, CompanyModel $companyModel, BodyTypeModel $bodyTypeModel, WarehouseModel $warehouseModel, UnitModel $unitModel, ColorModel $colorModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->productModel = $productModel;
        $this->productCategoryModel = $productCategoryModel;
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->companyModel = $companyModel;
        $this->bodyTypeModel = $bodyTypeModel;
        $this->warehouseModel = $warehouseModel;
        $this->unitModel = $unitModel;
        $this->colorModel = $colorModel;
        $this->userModel = $userModel;
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
                case 'save product':
                    $this->saveProduct();
                    break;
                case 'save product import':
                    $this->saveImportProduct();
                    break;
                case 'save imported product':
                    $this->saveSelectedImportProduct();
                    break;
                case 'get product details':
                    $this->getProductDetails();
                    break;
                case 'update product image':
                    $this->updateProductImage();
                    break;
                case 'delete product':
                    $this->deleteProduct();
                    break;
                case 'delete multiple product':
                    $this->deleteMultipleProduct();
                    break;
                case 'duplicate product':
                    $this->duplicateProduct();
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
    # Function: saveProduct
    # Description: 
    # Updates the existing product if it exists; otherwise, inserts a new product.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productID = isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8') : null;
        $productSubcategoryID = htmlspecialchars($_POST['product_subcategory_id'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
        $stockNumber = htmlspecialchars($_POST['stock_number'], ENT_QUOTES, 'UTF-8');
        $engineNumber = htmlspecialchars($_POST['engine_number'], ENT_QUOTES, 'UTF-8');
        $chassisNumber = htmlspecialchars($_POST['chassis_number'], ENT_QUOTES, 'UTF-8');
        $plateNumber = htmlspecialchars($_POST['plate_number'], ENT_QUOTES, 'UTF-8');
        $warehouseID = htmlspecialchars($_POST['warehouse_id'], ENT_QUOTES, 'UTF-8');
        $bodyTypeID = htmlspecialchars($_POST['body_type_id'], ENT_QUOTES, 'UTF-8');
        $colorID = htmlspecialchars($_POST['color_id'], ENT_QUOTES, 'UTF-8');
        $runningHours = htmlspecialchars($_POST['running_hours'], ENT_QUOTES, 'UTF-8');
        $mileage = htmlspecialchars($_POST['mileage'], ENT_QUOTES, 'UTF-8');
        $length = htmlspecialchars($_POST['length'], ENT_QUOTES, 'UTF-8');
        $lengthUnit = htmlspecialchars($_POST['length_unit'], ENT_QUOTES, 'UTF-8');
        $productPrice = htmlspecialchars($_POST['product_price'], ENT_QUOTES, 'UTF-8');
        $productCost = htmlspecialchars($_POST['product_cost'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
        $productCategoryID = $productSubcategoryDetails['product_category_id'];
        $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'];

        $stockNumber = $productSubcategoryCode . $stockNumber;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productModel->updateProduct($productID, $productCategoryID, $productSubcategoryID, $companyID, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productID' => $this->securityModel->encryptData($productID)]);
            exit;
        } 
        else {
            $productID = $this->productModel->insertProduct($productCategoryID, $productSubcategoryID, $companyID, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'productID' => $this->securityModel->encryptData($productID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveImportProduct
    # Description: 
    # Save the imported product for loading.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveImportProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $importFileFileName = $_FILES['import_file']['name'];
        $importFileFileSize = $_FILES['import_file']['size'];
        $importFileFileError = $_FILES['import_file']['error'];
        $importFileTempName = $_FILES['import_file']['tmp_name'];
        $importFileFileExtension = explode('.', $importFileFileName);
        $importFileActualFileExtension = strtolower(end($importFileFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(8);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(8);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($importFileActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($importFileTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the import file.']);
            exit;
        }
        
        if($importFileFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($importFileFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The import file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $this->productModel->deleteTempProduct();

        $importData = array_map('str_getcsv', file($importFileTempName));

        array_shift($importData);

        foreach ($importData as $row) {
            $productID = htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8');
            $productCategoryID = htmlspecialchars($row[1], ENT_QUOTES, 'UTF-8');
            $productSubcategoryID = htmlspecialchars($row[2], ENT_QUOTES, 'UTF-8');
            $companyID = htmlspecialchars($row[3], ENT_QUOTES, 'UTF-8');
            $productStatus = htmlspecialchars($row[4], ENT_QUOTES, 'UTF-8');
            $stockNumber = htmlspecialchars($row[5], ENT_QUOTES, 'UTF-8');
            $engineNumber = htmlspecialchars($row[6], ENT_QUOTES, 'UTF-8');
            $chassisNumber = htmlspecialchars($row[7], ENT_QUOTES, 'UTF-8');
            $plateNumber = htmlspecialchars($row[8], ENT_QUOTES, 'UTF-8');
            $description = htmlspecialchars($row[9], ENT_QUOTES, 'UTF-8');
            $warehouseID = htmlspecialchars($row[10], ENT_QUOTES, 'UTF-8');
            $bodyTypeID = htmlspecialchars($row[11], ENT_QUOTES, 'UTF-8');
            $length = htmlspecialchars($row[12], ENT_QUOTES, 'UTF-8');
            $lengthUnit = htmlspecialchars($row[13], ENT_QUOTES, 'UTF-8');
            $runningHours = htmlspecialchars($row[14], ENT_QUOTES, 'UTF-8');
            $mileage = htmlspecialchars($row[15], ENT_QUOTES, 'UTF-8');
            $colorID = htmlspecialchars($row[16], ENT_QUOTES, 'UTF-8');
            $productCost = htmlspecialchars($row[17], ENT_QUOTES, 'UTF-8');
            $productPrice = htmlspecialchars($row[18], ENT_QUOTES, 'UTF-8');
            $remarks = htmlspecialchars($row[19], ENT_QUOTES, 'UTF-8');

            $checkProductCategoryExist = $this->productCategoryModel->checkProductCategoryExist($productCategoryID)['total'];
            $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID)['total'];
            $checkCompanyExist = $this->companyModel->checkCompanyExist($companyID)['total'];
            $checkWarehouseExist = $this->warehouseModel->checkWarehouseExist($warehouseID)['total'];
            $checkBodyTypeExist = $this->bodyTypeModel->checkBodyTypeExist($bodyTypeID)['total'];
            $checkColorExist = $this->colorModel->checkColorExist($colorID)['total'];

            if($checkProductCategoryExist == 0){
                $productSubCategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
                $productCategoryID = $productSubCategoryDetails['product_category_id'] ?? null;
            }

            if($checkBodyTypeExist == 0){
                $bodyTypeID = null;
            }

            if($checkColorExist == 0){
                $colorID = null;
            }

            if(!empty($productCategoryID) && !empty($productSubcategoryID) && !empty($companyID) && !empty($description) && !empty($warehouseID) && $checkProductSubcategoryExist > 0 && $checkCompanyExist > 0 && $checkWarehouseExist > 0){
                $this->productModel->insertImportProduct($productID, $productCategoryID, $productSubcategoryID, $companyID, $productStatus, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks);
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveSelectedImportProduct
    # Description: 
    # Delete the selected products if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveSelectedImportProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $tempProductIDs = $_POST['temp_product_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($tempProductIDs as $tempProductID){
            $importedProductDetails = $this->productModel->getImportedProduct($tempProductID);
            $productID = $importedProductDetails['product_id'];
            $productCategoryID = $importedProductDetails['product_category_id'];
            $productSubcategoryID = $importedProductDetails['product_subcategory_id'];
            $companyID = $importedProductDetails['company_id'];
            $productStatus = $importedProductDetails['product_status'];
            $stockNumber = $importedProductDetails['stock_number'];
            $engineNumber = $importedProductDetails['engine_number'];
            $chassisNumber = $importedProductDetails['chassis_number'];
            $plateNumber = $importedProductDetails['plate_number'];
            $description = $importedProductDetails['description'];
            $warehouseID = $importedProductDetails['warehouse_id'];
            $bodyTypeID = $importedProductDetails['body_type_id'];
            $length = $importedProductDetails['length'];
            $lengthUnit = $importedProductDetails['length_unit'];
            $runningHours = $importedProductDetails['running_hours'];
            $mileage = $importedProductDetails['mileage'];
            $colorID = $importedProductDetails['color_id'];
            $productCost = $importedProductDetails['product_cost'];
            $productPrice = $importedProductDetails['product_price'];
            $remarks = $importedProductDetails['remarks'];

            $checkProductExist = $this->productModel->checkProductExist($productID);
            $total = $checkProductExist['total'] ?? 0;
        
            if ($total > 0) {
                $this->productModel->updateImportedProduct($productID, $productCategoryID, $productSubcategoryID, $companyID, $productStatus, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks, $userID);
            } 
            else {
                $productID = $this->productModel->insertImportedProduct($productCategoryID, $productSubcategoryID, $companyID, $productStatus, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks, $userID);
            }
        }
        
        $this->productModel->deleteTempProduct();

        echo json_encode(['success' => true]);
        exit;
    }
    
    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateProductImage
    # Description: 
    # Handles the update of the product image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateProductImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productImageFileName = $_FILES['product_image']['name'];
        $productImageFileSize = $_FILES['product_image']['size'];
        $productImageFileError = $_FILES['product_image']['error'];
        $productImageTempName = $_FILES['product_image']['tmp_name'];
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

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $productID  . '/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $productID . '/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $productDetails = $this->productModel->getProduct($productID);
        $productImage = !empty($productDetails['product_image']) ? '.' . $productDetails['product_image'] : null;

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

        $this->productModel->updateProductImage($productID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProduct
    # Description: 
    # Delete the product if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productDetails = $this->productModel->getProduct($productID);
        $productImage = !empty($productDetails['product_image']) ? '.' . $productDetails['product_image'] : null;

        if(file_exists($productImage)){
            if (!unlink($productImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->productModel->deleteProduct($productID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductDetails
    # Description: 
    # Handles the retrieval of product details such as product name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getProductDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
            $userID = $_SESSION['user_id'];
            $productID = $_POST['product_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $productDetails = $this->productModel->getProduct($productID);
            $productSubategoryID = $productDetails['product_subcategory_id'];
            $bodyTypeID = $productDetails['body_type_id'];
            $colorID = $productDetails['color_id'];
            
            $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ?? null;

            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number']);
            $fullStockNumber = $productSubcategoryCode . $productDetails['stock_number'];

            $getBodyType = $this->bodyTypeModel->getBodyType($bodyTypeID);
            $bodyTypeName = $getBodyType['body_type_name'];

            $getColor = $this->colorModel->getColor($colorID);
            $colorName = $getColor['color_name'];

            $response = [
                'success' => true,
                'productSubcategoryID' => $productSubategoryID,
                'companyID' => $productDetails['company_id'],
                'stockNumber' => $stockNumber,
                'fullStockNumber' => $fullStockNumber,
                'productCategoryID' => $productCategoryID,
                'summaryStockNumber' => $productDetails['stock_number'],
                'engineNumber' => $productDetails['engine_number'],
                'chassisNumber' => $productDetails['chassis_number'],
                'plateNumber' => $productDetails['plate_number'],
                'description' => $productDetails['description'],
                'warehouseID' => $productDetails['warehouse_id'],
                'bodyTypeID' => $bodyTypeID,
                'bodyTypeName' => $bodyTypeName,
                'length' => $productDetails['length'],
                'lengthUnit' => $productDetails['length_unit'],
                'runningHours' => $productDetails['running_hours'],
                'mileage' => $productDetails['mileage'],
                'colorID' => $colorID,
                'colorName' => $colorName,
                'productCost' => $productDetails['product_cost'],
                'productPrice' => $productDetails['product_price'],
                'remarks' => $productDetails['remarks']
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
require_once '../model/product-model.php';
require_once '../model/product-category-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/company-model.php';
require_once '../model/body-type-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/unit-model.php';
require_once '../model/color-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ProductController(new ProductModel(new DatabaseModel), new ProductCategoryModel(new DatabaseModel), new ProductSubcategoryModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new BodyTypeModel(new DatabaseModel), new WarehouseModel(new DatabaseModel), new UnitModel(new DatabaseModel), new ColorModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>