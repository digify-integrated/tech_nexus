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
    private $brandModel;
    private $makeModel;
    private $cabinModel;
    private $modelModel;
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
    public function __construct(ProductModel $productModel, ProductCategoryModel $productCategoryModel, ProductSubcategoryModel $productSubcategoryModel, CompanyModel $companyModel, BodyTypeModel $bodyTypeModel, WarehouseModel $warehouseModel, UnitModel $unitModel, ColorModel $colorModel, CabinModel $cabinModel, BrandModel $brandModel, MakeModel $makeModel, ModelModel $modelModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->productModel = $productModel;
        $this->productCategoryModel = $productCategoryModel;
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->companyModel = $companyModel;
        $this->bodyTypeModel = $bodyTypeModel;
        $this->warehouseModel = $warehouseModel;
        $this->unitModel = $unitModel;
        $this->colorModel = $colorModel;
        $this->brandModel = $brandModel;
        $this->makeModel = $makeModel;
        $this->cabinModel = $cabinModel;
        $this->modelModel = $modelModel;
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
                case 'save new product':
                    $this->saveNewProduct();
                    break;
                case 'save product details':
                    $this->saveProductDetails();
                    break;
                case 'save landed cost':
                    $this->saveLandedCost();
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
                case 'add product document':
                    $this->addProductDocument();
                    break;
                case 'insert product image':
                    $this->insertProductImage();
                    break;
                case 'delete product':
                    $this->deleteProduct();
                    break;
                case 'delete product image':
                    $this->deleteProductImage();
                    break;
                case 'delete product document':
                    $this->deleteProductDocument();
                    break;
                case 'delete multiple product':
                    $this->deleteMultipleProduct();
                    break;
                case 'delete product expense':
                    $this->deleteProductExpense();
                    break;
                case 'tag for sale':
                    $this->tagProductForSale();
                    break;
                case 'tag as sold':
                    $this->tagProductAsSold();
                    break;
                case 'tag as returned':
                    $this->tagProductAsReturned();
                    break;
                case 'tag as ROPA':
                    $this->tagProductAsROPA();
                    break;
                case 'tag as repossessed':
                    $this->tagProductAsRepossessed();
                    break;
                case 'save product expense':
                    $this->saveProductExpense();
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

    public function tagProductForSale() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
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
        $preorder = $productDetails['preorder'];
        $description = $productDetails['description'];
        $totalLandedCost = $productDetails['total_landed_cost'];
        $unitCost = $productDetails['unit_cost'];
        $productPrice = $productDetails['product_price'];

        if($preorder == 'Yes'){
            echo json_encode(['success' => false, 'preOrder' =>  true]);
            exit;
        }

        /*if($unitCost == 0 || $productPrice == 0){
            echo json_encode(['success' => false, 'zeroCost' =>  true]);
            exit;
        }*/

        if($unitCost == 0){
            echo json_encode(['success' => false, 'zeroCost' =>  true]);
            exit;
        }

        $rrNumber = $this->systemSettingModel->getSystemSetting(18)['value'] + 1;
        $this->productModel->updateProductStatus($productID, 'For Sale', $description, $totalLandedCost, 'Landed Cost', $userID);
        $this->productModel->updateProductRRNumber($productID, $rrNumber, $userID);
        $this->systemSettingModel->updateSystemSettingValue(18, $rrNumber, $userID);
            
        echo json_encode(['success' => true]);
    }

    public function tagProductAsSold() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
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
        $preorder = $productDetails['preorder'];

        if($preorder == 'Yes'){
            echo json_encode(['success' => false, 'preOrder' =>  true]);
            exit;
        }

        $this->productModel->updateProductStatus($productID, 'Sold', '', '', '', $userID);
        $this->productModel->insertProductExpense($productID, '', '', 0, 'Sold', 'Sold', '', $userID);
            
        echo json_encode(['success' => true]);
    }

    public function tagProductAsReturned() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
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
        $preorder = $productDetails['preorder'];

        if($preorder == 'Yes'){
            echo json_encode(['success' => false, 'preOrder' =>  true]);
            exit;
        }

        $this->productModel->updateProductStatus($productID, 'Returned', '', '', '', $userID);
        $this->productModel->insertProductExpense($productID, '', '', 0, 'Returned', 'Returned', '', $userID);
            
        echo json_encode(['success' => true]);
    }

    public function tagProductAsROPA() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
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
        $preorder = $productDetails['preorder'];

        if($preorder == 'Yes'){
            echo json_encode(['success' => false, 'preOrder' =>  true]);
            exit;
        }

        $this->productModel->updateProductStatus($productID, 'ROPA', '', '', '', $userID);
        $this->productModel->insertProductExpense($productID, '', '', 0, 'ROPA', 'ROPA', '', $userID);
            
        echo json_encode(['success' => true]);
    }

    public function tagProductAsRepossessed() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
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
        $preorder = $productDetails['preorder'];

        if($preorder == 'Yes'){
            echo json_encode(['success' => false, 'preOrder' =>  true]);
            exit;
        }

        $this->productModel->updateProductStatus($productID, 'Repossessed', '', '', '', $userID);
        $this->productModel->insertProductExpense($productID, '', '', 0, 'Repossessed', 'Repossessed', '', $userID);
            
        echo json_encode(['success' => true]);
    }

    # -------------------------------------------------------------
    #
    # Function: saveNewProduct
    # Description: 
    # Updates the existing product if it exists; otherwise, inserts a new product.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveNewProduct() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brandID = $_POST['brand_id'];
        $productSubcategoryID = $_POST['product_subcategory_id'];
        $companyID = $_POST['company_id'];
        $quantity = $_POST['quantity'];
        $preorder = $_POST['preorder'];
        $remarks = $_POST['remarks'];
        $supplierID = $_POST['supplier_id'];
        $refNo = $_POST['ref_no'];
        $broker = $_POST['broker'];
        $modeOfAcquisitionID = $_POST['mode_of_acquisition_id'];
        $receivedFrom = $_POST['received_from'];
        $receivedFromAddress = $_POST['received_from_address'];
        $receivedFromIDType = $_POST['received_from_id_type'];
        $receivedFromIDNumber = $_POST['received_from_id_number'];
        $modelID = $_POST['model_id'];
        $makeID = $_POST['make_id'];
        $bodyTypeID = $_POST['body_type_id'];
        $length = $_POST['length'];
        $lengthUnit = $_POST['length_unit'];
        $classID = $_POST['class_id'];
        $cabinID = $_POST['cabin_id'];
        $yearModel = $_POST['year_model'];
        $is_service = $_POST['is_service'];
        $engineNumber = $_POST['engine_number'];
        $chassisNumber = $_POST['chassis_number'];
        $chassisNumber = str_replace(['-', ' '], '', $chassisNumber);
        $plateNumber = $_POST['plate_number'];
        $orcrNo = $_POST['orcr_no'];
        $registeredOwner = $_POST['registered_owner'];
        $colorID = $_POST['color_id'];
        $modeOfRegistration = $_POST['mode_of_registration'];
        $withCR = $_POST['with_cr'];
        $withPlate = $_POST['with_plate'];
        $returnedToSupplier = $_POST['returned_to_supplier'];
        $warehouseID = $_POST['warehouse_id'];
        $runningHours = $_POST['running_hours'];
        $mileage = $_POST['mileage'];

        $orcrDate = $this->systemModel->checkDate('empty', $_POST['orcr_date'], '', 'Y-m-d', '');
        $orcrExpiryDate = $this->systemModel->checkDate('empty', $_POST['orcr_expiry_date'], '', 'Y-m-d', '');
        $arrivalDate = $this->systemModel->checkDate('empty', $_POST['arrival_date'], '', 'Y-m-d', '');
        $checklistDate = $this->systemModel->checkDate('empty', $_POST['checklist_date'], '', 'Y-m-d', '');

        $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
        $productCategoryID = $productSubcategoryDetails['product_category_id'];
        $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'];

        $stockNumberLatest = $this->systemSettingModel->getSystemSetting(17)['value'] + 1;

        if($preorder == 'No'){
            $stockNumber = $productSubcategoryCode . date('my') . $stockNumberLatest;
            $this->systemSettingModel->updateSystemSettingValue(17, $stockNumberLatest, $userID);
        }
        else{
            $stockNumber = '';
        }
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $brandName = $this->brandModel->getBrand($brandID)['brand_name'] ?? '';
        $makeName = $this->makeModel->getMake($makeID)['make_name'] ?? '';
        $cabinName = $this->cabinModel->getCabin($cabinID)['cabin_name'] ?? '';
        $modelName = $this->modelModel->getModel($modelID)['model_name'] ?? '';
        $bodyTypeName = $this->bodyTypeModel->getBodyType($bodyTypeID)['body_type_name'] ?? '';

        $unitDetails = $this->unitModel->getUnit($lengthUnit);
        $unitShortName = $unitDetails['short_name'] ?? null;

        $descriptionParts = [];

        if (!empty($brandName)) {
            $descriptionParts[] = $brandName;
        }
        if (!empty($makeName)) {
            $descriptionParts[] = $makeName;
        }
        if (!empty($cabinName)) {
            $descriptionParts[] = $cabinName;
        }
        if (!empty($modelName)) {
            $descriptionParts[] = $modelName;
        }
        if (!empty($bodyTypeName)) {
            $descriptionParts[] = $bodyTypeName;
        }
        if (!empty($length)) {
            $descriptionParts[] = $length;
        }
        if (!empty($unitShortName)) {
            $descriptionParts[] = $unitShortName;
        }
        if (!empty($yearModel)) {
            $descriptionParts[] = $yearModel;
        }

        $description = implode(' ', $descriptionParts);
    
        $productID = $this->productModel->insertProduct($productCategoryID, $productSubcategoryID, $companyID, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $remarks, $orcrNo, $orcrDate, $orcrExpiryDate, $receivedFrom, $receivedFromAddress, $receivedFromIDType, $receivedFromIDNumber, '', $supplierID, $refNo, $brandID, $cabinID, $modelID, $makeID, $classID, $modeOfAcquisitionID, $broker, $registeredOwner, $modeOfRegistration, $yearModel, $arrivalDate, $checklistDate, $withCR, $withPlate, $returnedToSupplier, $quantity, $preorder, $is_service, $userID);        

        echo json_encode(['success' => true, 'insertRecord' => true, 'productID' => $this->securityModel->encryptData($productID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveProductDetails
    # Description: 
    # Updates the existing product if it exists; otherwise, inserts a new product.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveProductDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productID = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $companyID = $_POST['company_id'];
        $supplierID = $_POST['supplier_id'];
        $refNo = $_POST['ref_no'];
        $preorder = $_POST['preorder'];
        $brandID = $_POST['brand_id'];
        $cabinID = $_POST['cabin_id'];
        $modelID = $_POST['model_id'];
        $bodyTypeID = $_POST['body_type_id'];
        $length = $_POST['length'];
        $lengthUnit = $_POST['length_unit'];
        $classID = $_POST['class_id'];
        $modeOfAcquisitionID = $_POST['mode_of_acquisition_id'];
        $broker = $_POST['broker'];
        $engineNumber = $_POST['engine_number'];
        $chassisNumber = $_POST['chassis_number'];
        $chassisNumber = str_replace(['-', ' '], '', $chassisNumber);
        $plateNumber = $_POST['plate_number'];
        $registeredOwner = $_POST['registered_owner'];
        $colorID = $_POST['color_id'];
        $modeOfRegistration = $_POST['mode_of_registration'];
        $warehouseID = $_POST['warehouse_id'];
        $yearModel = $_POST['year_model'];
        $is_service = $_POST['is_service'];
        $withCR = $_POST['with_cr'];
        $withPlate = $_POST['with_plate'];
        $returnedToSupplier = $_POST['returned_to_supplier'];
        $productSubcategoryID = $_POST['product_subcategory_id'];
        $runningHours = $_POST['running_hours'];
        $mileage = $_POST['mileage'];
        $orcrNo = $_POST['orcr_no'];
        $receivedFrom = $_POST['received_from'];
        $receivedFromAddress = $_POST['received_from_address'];
        $receivedFromIDType = $_POST['received_from_id_type'];
        $receivedFromIDNumber = $_POST['received_from_id_number'];
        $remarks = $_POST['remarks'];
        $makeID = $_POST['make_id'];
        $quantity = $_POST['quantity'];
        $arrivalDate = $this->systemModel->checkDate('empty', $_POST['arrival_date'], '', 'Y-m-d', '');
        $checklistDate = $this->systemModel->checkDate('empty', $_POST['checklist_date'], '', 'Y-m-d', '');
        $orcrDate = $this->systemModel->checkDate('empty', $_POST['orcr_date'], '', 'Y-m-d', '');
        $orcrExpiryDate = $this->systemModel->checkDate('empty', $_POST['orcr_expiry_date'], '', 'Y-m-d', '');

        $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubcategoryID);
        $productCategoryID = $productSubcategoryDetails['product_category_id'];
        $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'];

        $brandName = $this->brandModel->getBrand($brandID)['brand_name'] ?? '';
        $makeName = $this->makeModel->getMake($makeID)['make_name'] ?? '';
        $cabinName = $this->cabinModel->getCabin($cabinID)['cabin_name'] ?? '';
        $modelName = $this->modelModel->getModel($modelID)['model_name'] ?? '';
        $bodyTypeName = $this->bodyTypeModel->getBodyType($bodyTypeID)['body_type_name'] ?? '';

        $productDetails = $this->productModel->getProduct($productID);
        $stockNumber = $productDetails['stock_number'] ?? null;

        if($preorder == 'No' && empty($stockNumber)){
            $stockNumberLatest = $this->systemSettingModel->getSystemSetting(17)['value'] + 1;
            $stockNumber = $productSubcategoryCode . date('my') . $stockNumberLatest;
            $this->systemSettingModel->updateSystemSettingValue(17, $stockNumberLatest, $userID);
            $this->productModel->updateRRDate($productID, $userID);
        }

        $unitDetails = $this->unitModel->getUnit($lengthUnit);
        $unitShortName = $unitDetails['short_name'] ?? null;

        $descriptionParts = [];

        if (!empty($brandName)) {
            $descriptionParts[] = $brandName;
        }
        if (!empty($makeName)) {
            $descriptionParts[] = $makeName;
        }
        if (!empty($cabinName)) {
            $descriptionParts[] = $cabinName;
        }
        if (!empty($modelName)) {
            $descriptionParts[] = $modelName;
        }
        if (!empty($bodyTypeName)) {
            $descriptionParts[] = $bodyTypeName;
        }
        if (!empty($length)) {
            $descriptionParts[] = $length;
        }
        if (!empty($unitShortName)) {
            $descriptionParts[] = $unitShortName;
        }
        if (!empty($yearModel)) {
            $descriptionParts[] = $yearModel;
        }

        $description = implode(' ', $descriptionParts);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productModel->updateProductDetails($productID, $stockNumber, $productCategoryID, $productSubcategoryID, $companyID, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $remarks, $orcrNo, $orcrDate, $orcrExpiryDate, $receivedFrom, $receivedFromAddress, $receivedFromIDType, $receivedFromIDNumber, $supplierID, $refNo, $brandID, $cabinID, $modelID, $makeID, $classID, $modeOfAcquisitionID, $broker, $registeredOwner, $modeOfRegistration, $yearModel, $arrivalDate, $checklistDate, $withCR, $withPlate, $returnedToSupplier, $quantity, $preorder, $is_service, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productID' => $this->securityModel->encryptData($productID)]);
            exit;
        } 
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveProductExpense
    # Description: 
    # Updates the existing product if it exists; otherwise, inserts a new product.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveProductExpense() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productID = $_POST['product_id'];
        $reference_type = $_POST['reference_type'];
        $reference_number = $_POST['reference_number'];
        $expense_amount = $_POST['expense_amount'];
        $expense_type = $_POST['expense_type'];
        $issuance_date = $this->systemModel->checkDate('empty', $_POST['issuance_date'], '', 'Y-m-d', '');
        $particulars = $_POST['particulars'];
        
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productModel->insertProductExpense($productID, $reference_type, $reference_number, $expense_amount, $expense_type, $particulars, $issuance_date, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        } 
    }
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
    public function saveLandedCost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productID = isset($_POST['product_id']) ? htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8') : null;
        $productPrice = $_POST['product_price'];
        $unitCost = $_POST['unit_cost'];
        $fxRate = $_POST['fx_rate'];
        $convertedAmount = $_POST['converted_amount'];
        $packageDeal = $_POST['package_deal'];
        $taxesDuties = $_POST['taxes_duties'];
        $freight = $_POST['freight'];
        $ltoRegistration = $_POST['lto_registration'];
        $royalties = $_POST['royalties'];
        $conversion = $_POST['conversion'];
        $arrastre = $_POST['arrastre'];
        $wharrfage = $_POST['wharrfage'];
        $insurance = $_POST['insurance'];
        $aircon = $_POST['aircon'];
        $importPermit = $_POST['import_permit'];
        $others = $_POST['others'];
        $totalLandedCost = $_POST['total_landed_cost'];
        $paymentRefNo = $_POST['payment_ref_no'];
        $paymentRefDate = $this->systemModel->checkDate('empty', $_POST['payment_ref_date'], '', 'Y-m-d', '');
        $paymentRefAmount = $_POST['payment_ref_amount'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductExist = $this->productModel->checkProductExist($productID);
        $total = $checkProductExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productModel->updateProductLandedCost($productID, $productPrice, $fxRate, $convertedAmount, $unitCost, $packageDeal, $taxesDuties, $freight, $ltoRegistration, $royalties, $conversion, $arrastre, $wharrfage, $insurance, $aircon, $importPermit, $others, $totalLandedCost, $paymentRefNo, $paymentRefDate, $paymentRefAmount, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productID' => $this->securityModel->encryptData($productID)]);
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
            $warehouseID = $row[10];
            $bodyTypeID = $row[11];
            $length = $row[12];
            $lengthUnit = $row[13];
            $runningHours = $row[14];
            $mileage = $row[15];
            $colorID = $row[16];
            $productCost = $row[17];
            $productPrice = $row[18];
            $remarks = $row[19];

            $checkProductCategoryExist = $this->productCategoryModel->checkProductCategoryExist($productCategoryID)['total'];
            $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID)['total'];
            $checkCompanyExist = $this->companyModel->checkCompanyExist($companyID)['total'] ?? 0;
            $checkWarehouseExist = $this->warehouseModel->checkWarehouseExist($warehouseID)['total'] ?? 0;
            $checkBodyTypeExist = $this->bodyTypeModel->checkBodyTypeExist($bodyTypeID)['total'] ?? 0;
            $checkColorExist = $this->colorModel->checkColorExist($colorID)['total'] ?? 0;

            if($checkProductCategoryExist == 0){
                $productSubCategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productCategoryID);
                $productCategoryID = $productSubCategoryDetails['product_category_id'] ?? null;
            }

            if($checkBodyTypeExist == 0){
                $bodyTypeID = null;
            }

            if($checkColorExist == 0){
                $colorID = null;
            }

            /*if(!empty($productCategoryID) && !empty($productSubcategoryID) && !empty($companyID) && !empty($description) && !empty($warehouseID) && $checkProductSubcategoryExist > 0 && $checkCompanyExist > 0 && $checkWarehouseExist > 0){
                $this->productModel->insertImportProduct($productID, $productCategoryID, $productSubcategoryID, $companyID, $productStatus, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks);
            }*/

            $this->productModel->insertImportProduct($productID, $productCategoryID, $productSubcategoryID, $companyID, $productStatus, $stockNumber, $engineNumber, $chassisNumber, $plateNumber, $description, $warehouseID, $bodyTypeID, $length, $lengthUnit, $runningHours, $mileage, $colorID, $productCost, $productPrice, $remarks);
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
            $chassisNumber = str_replace(['-', ' '], '', $chassisNumber);
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
    #
    # Function: insertProductImage
    # Description: 
    # Handles the update of the product image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function insertProductImage() {
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

        $productImageFileName = $_FILES['product_other_image']['name'];
        $productImageFileSize = $_FILES['product_other_image']['size'];
        $productImageFileError = $_FILES['product_other_image']['error'];
        $productImageTempName = $_FILES['product_other_image']['tmp_name'];
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

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $productID  . '/other_images/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $productID . '/other_images/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($productImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->productModel->insertProductImage($productID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: addProductDocument
    # Description: 
    # Handles the update of the product image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function addProductDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $documentType = htmlspecialchars($_POST['document_type'], ENT_QUOTES, 'UTF-8');
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

        $productImageFileName = $_FILES['product_document']['name'];
        $productImageFileSize = $_FILES['product_document']['size'];
        $productImageFileError = $_FILES['product_document']['error'];
        $productImageTempName = $_FILES['product_document']['tmp_name'];
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

        $directory = DEFAULT_PRODUCT_RELATIVE_PATH_FILE . $productID  . '/document/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_PRODUCT_FULL_PATH_FILE . $productID . '/document/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        if(!move_uploaded_file($productImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->productModel->insertProductDocument($productID, $documentType, $filePath, $userID);

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

    public function deleteProductExpense() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productExpenseID = htmlspecialchars($_POST['product_expense_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->productModel->deleteProductExpense($productExpenseID);
    
        echo json_encode(['success' => true]);
        exit;
    }
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
    public function deleteProductImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productImageID = htmlspecialchars($_POST['product_image_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductImageExist = $this->productModel->checkProductImageExist($productImageID);
        $total = $checkProductImageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productDetails = $this->productModel->getProductImage($productImageID);
        $productImage = !empty($productDetails['product_image']) ? '.' . $productDetails['product_image'] : null;

        if(file_exists($productImage)){
            if (!unlink($productImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->productModel->deleteProductImage($productImageID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function deleteProductDocument() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productImageID = htmlspecialchars($_POST['product_document_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductImageExist = $this->productModel->checkProductDocumentExist($productImageID);
        $total = $checkProductImageExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productDetails = $this->productModel->getProductDocument($productImageID);
        $productImage = !empty($productDetails['document_path']) ? '.' . $productDetails['document_path'] : null;

        if(file_exists($productImage)){
            if (!unlink($productImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->productModel->deleteProductDocument($productImageID);
            
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
            $productCategoryID = $productDetails['product_category_id'];
            $colorID = $productDetails['color_id'];
            
            $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ?? null;

            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number']);
            $fullStockNumber = $productSubcategoryCode . $stockNumber;

            $getBodyType = $this->bodyTypeModel->getBodyType($bodyTypeID);
            $bodyTypeName = $getBodyType['body_type_name'] ?? null;

            $getColor = $this->colorModel->getColor($colorID);
            $colorName = $getColor['color_name'] ?? null;

            $productCategoryDetails = $this->productCategoryModel->getProductCategory($productCategoryID);
            $productCategoryName = $productCategoryDetails['product_category_name'] ?? null;

            
            $productCost = $this->productModel->getTotalProductCost($productID)['expense_amount'] ?? 0;

        
            $response = [
                'success' => true,
                'productSubcategoryID' => $productSubategoryID,
                'productCategoryName' => $productCategoryName,
                'companyID' => $productDetails['company_id'],
                'stockNumber' => $stockNumber,
                'fullStockNumber' => $fullStockNumber,
                'productCategoryID' => $productCategoryID,
                'summaryStockNumber' => $productDetails['stock_number'],
                'productStatus' => $productDetails['product_status'],
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
                'productCost' => $productCost,
                'productPrice' => $productDetails['product_price'],
                'remarks' => $productDetails['remarks'],
                'orcrNo' => $productDetails['orcr_no'],
                'receivedFrom' => $productDetails['received_from'],
                'receivedFromAddress' => $productDetails['received_from_address'],
                'receivedFromIDType' => $productDetails['received_from_id_type'],
                'receivedFromIDNumber' => $productDetails['received_from_id_number'],
                'rr_no' => $productDetails['rr_no'],
                'supplier_id' => $productDetails['supplier_id'],
                'ref_no' => $productDetails['ref_no'],
                'brand_id' => $productDetails['brand_id'],
                'cabin_id' => $productDetails['cabin_id'],
                'model_id' => $productDetails['model_id'],
                'make_id' => $productDetails['make_id'],
                'class_id' => $productDetails['class_id'],
                'mode_of_acquisition_id' => $productDetails['mode_of_acquisition_id'],
                'broker' => $productDetails['broker'],
                'registered_owner' => $productDetails['registered_owner'],
                'mode_of_registration' => $productDetails['mode_of_registration'],
                'year_model' => $productDetails['year_model'],
                'fx_rate' => $productDetails['fx_rate'],
                'unit_cost' => $productDetails['unit_cost'],
                'package_deal' => $productDetails['package_deal'],
                'taxes_duties' => $productDetails['taxes_duties'],
                'freight' => $productDetails['freight'],
                'lto_registration' => $productDetails['lto_registration'],
                'royalties' => $productDetails['royalties'],
                'conversion' => $productDetails['conversion'],
                'arrastre' => $productDetails['arrastre'],
                'wharrfage' => $productDetails['wharrfage'],
                'insurance' => $productDetails['insurance'],
                'aircon' => $productDetails['aircon'],
                'import_permit' => $productDetails['import_permit'],
                'others' => $productDetails['others'],
                'sub_total' => $productDetails['sub_total'],
                'total_landed_cost' => $productDetails['total_landed_cost'],
                'with_cr' => $productDetails['with_cr'] ?? 'No',
                'with_plate' => $productDetails['with_plate'] ?? 'No',
                'returned_to_supplier' => $productDetails['returned_to_supplier'],
                'quantity' => $productDetails['quantity'],
                'preorder' => $productDetails['preorder'],
                'is_service' => $productDetails['is_service'],
                'productImage' => $this->systemModel->checkImage($productDetails['product_image'], 'default'),
                'orcrDate' =>  $this->systemModel->checkDate('empty', $productDetails['orcr_date'], '', 'm/d/Y', ''),
                'orcrExpiryDate' =>  $this->systemModel->checkDate('empty', $productDetails['orcr_expiry_date'], '', 'm/d/Y', ''),
                'rrDate' =>  $this->systemModel->checkDate('empty', $productDetails['rr_date'], '', 'm/d/Y', ''),
                'arrival_date' =>  $this->systemModel->checkDate('empty', $productDetails['arrival_date'], '', 'm/d/Y', ''),
                'checklist_date' =>  $this->systemModel->checkDate('empty', $productDetails['checklist_date'], '', 'm/d/Y', '')
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
require_once '../model/brand-model.php';
require_once '../model/cabin-model.php';
require_once '../model/make-model.php';
require_once '../model/model-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ProductController(new ProductModel(new DatabaseModel), new ProductCategoryModel(new DatabaseModel), new ProductSubcategoryModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new BodyTypeModel(new DatabaseModel), new WarehouseModel(new DatabaseModel), new UnitModel(new DatabaseModel), new ColorModel(new DatabaseModel), new CabinModel(new DatabaseModel), new BrandModel(new DatabaseModel), new MakeModel(new DatabaseModel), new ModelModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();

?>