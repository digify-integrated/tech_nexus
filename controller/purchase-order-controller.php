<?php
session_start();

# -------------------------------------------------------------
#
# Function: PurchaseOrderController
# Description: 
# The PurchaseOrderController class handles purchase order related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PurchaseOrderController {
    private $purchaseOrderModel;
    private $purchaseRequestModel;
    private $partsModel;
    private $productModel;
    private $productSubcategoryModel;
    private $unitModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $securityModel;
    private $systemSettingModel;
    private $brandModel;
    private $makeModel;
    private $cabinModel;
    private $modelModel;
    private $bodyTypeModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PurchaseOrderModel, UserModel and SecurityModel instances.
    # These instances are used for purchase order related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PurchaseOrderModel $purchaseOrderModel     The PurchaseOrderModel instance for purchase order related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PurchaseOrderModel $purchaseOrderModel, PurchaseRequestModel $purchaseRequestModel, PartsModel $partsModel, ProductModel $productModel, ProductSubcategoryModel $productSubcategoryModel, UnitModel $unitModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemSettingModel $systemSettingModel, BrandModel $brandModel, MakeModel $makeModel, CabinModel $cabinModel, ModelModel $modelModel, BodyTypeModel $bodyTypeModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->purchaseOrderModel = $purchaseOrderModel;
        $this->purchaseRequestModel = $purchaseRequestModel;
        $this->partsModel = $partsModel;
        $this->productModel = $productModel;
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->unitModel = $unitModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->brandModel = $brandModel;
        $this->makeModel = $makeModel;
        $this->cabinModel = $cabinModel;
        $this->modelModel = $modelModel;
        $this->bodyTypeModel = $bodyTypeModel;
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
                case 'save unit purchase order':
                    $this->saveUnitPurchaseOrder();
                    break;
                case 'save purchase order item unit':
                    $this->savePurchaseOrderItemUnit();
                    break;
                case 'save purchase order receive':
                    $this->savePurchaseOrderRecieve();
                    break;
                case 'save purchase order receive cancel':
                    $this->savePurchaseOrderCancel();
                    break;
                case 'get purchase order details':
                    $this->getPurchaseOrderDetails();
                    break;
                case 'get purchase order unit details':
                    $this->getPurchaseOrderUnitDetails();
                    break;
                case 'get receive details':
                    $this->getReceiveDetails();
                    break;
                case 'delete purchase order':
                    $this->deletePurchaseOrder();
                    break;
                case 'delete item unit':
                    $this->deletePurchaseOrderUnit();
                    break;
                case 'delete item part':
                    $this->deletePurchaseOrderPart();
                    break;
                case 'delete item supply':
                    $this->deletePurchaseOrderSupply();
                    break;
                case 'delete multiple purchase order':
                    $this->deleteMultiplePurchaseOrder();
                    break;
                case 'tag request as for approval':
                    $this->tagAsForApproval();
                    break;
                case 'tag request as on-process':
                    $this->tagAsOnProcess();
                    break;
                case 'tag request as complete':
                    $this->tagAsComplete();
                    break;
                case 'tag request as cancelled':
                    $this->tagAsCancelled();
                    break;
                case 'tag request as draft':
                    $this->tagAsDraft();
                    break;
                case 'tag request as approved':
                    $this->tagAsApproved();
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
    # Function: saveUnitPurchaseOrder
    # Description: 
    # Updates the existing purchase order if it exists; otherwise, inserts a new purchase order.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveUnitPurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_id = isset($_POST['purchase_order_id']) ? htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8') : null;
        $purchase_order_type = 'Product';
        $company_id = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $supplier_id = htmlspecialchars($_POST['supplier_id'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $checkPurchaseOrderExist = $this->purchaseOrderModel->checkPurchaseOrderExist($purchase_order_id);
        $total = $checkPurchaseOrderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->purchaseOrderModel->updatePurchaseOrder($purchase_order_id, $purchase_order_type, $company_id, $supplier_id, $remarks, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => false, 'purchaseOrderID' => $this->securityModel->encryptData($purchase_order_id)]);
            exit;
        }
        else {
            $reference_number = (int)$this->systemSettingModel->getSystemSetting(45)['value'] + 1;

            $purchase_order_id = $this->purchaseOrderModel->insertPurchaseOrder($reference_number, $purchase_order_type, $company_id, $supplier_id, $remarks, $userID);

            $this->systemSettingModel->updateSystemSettingValue(45, $reference_number, $userID);

            echo json_encode(value: ['success' => true, 'insertRecord' => true, 'purchaseOrderID' => $this->securityModel->encryptData($purchase_order_id)]);
            exit;
        }
    }

    public function savePurchaseOrderItemUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $purchase_order_id       = $_POST['purchase_order_id'] ?? null;
        $purchase_order_unit_id  = $_POST['purchase_order_unit_id'] ?? null;
        $purchase_request_cart_id= $_POST['purchase_request_cart_id'] ?? null;

        $product_category_id     = $_POST['product_subcategory_id'] ?? null;
        $brand_id                = $_POST['brand_id'] ?? null;
        $model_id                = $_POST['model_id'] ?? null;
        $body_type_id            = $_POST['body_type_id'] ?? null;
        $class_id                = $_POST['class_id'] ?? null;
        $color_id                = $_POST['color_id'] ?? null;
        $make_id                 = $_POST['make_id'] ?? null;
        $year_model              = $_POST['year_model'] ?? null;
        $cabin_id                = $_POST['cabin_id'] ?? null;

        $quantity                = $_POST['quantity_unit'] ?? 0;
        $unit_id                 = $_POST['quantity_unit_id'] ?? null;

        $length                  = $_POST['length'] ?? null;
        $length_unit             = $_POST['length_unit'] ?? null;

        $price_unit              = $_POST['price_unit'] ?? 0;
        $fx_rate                 = $_POST['fx_rate'] ?? 0;
        $converted_amount        = $_POST['converted_amount'] ?? 0;
        $package_deal            = $_POST['package_deal'] ?? null;
        $taxes_duties             = $_POST['taxes_duties'] ?? null;
        $freight                 = $_POST['freight'] ?? null;
        $lto_registration        = $_POST['lto_registration'] ?? null;
        $royalties               = $_POST['royalties'] ?? null;
        $conversion              = $_POST['conversion'] ?? null;
        $arrastre                = $_POST['arrastre'] ?? null;
        $wharrfage               = $_POST['wharrfage'] ?? null;
        $insurance               = $_POST['insurance'] ?? null;
        $aircon                  = $_POST['aircon'] ?? null;
        $import_permit           = $_POST['import_permit'] ?? null;
        $others                  = $_POST['others'] ?? null;
        $total_landed_cost       = $_POST['total_landed_cost'] ?? null;
        $preorder                = $_POST['preorder'] ?? null;
        $remarks                 = $_POST['unit_remarks'] ?? null;

        $exists = $this->purchaseOrderModel
            ->checkPurchaseOrderItemUnitExist($purchase_order_unit_id)['total'] ?? 0;

        if ($exists > 0) {
            $this->purchaseOrderModel->updatePurchaseOrderItemUnit(
                $purchase_order_unit_id,
                $purchase_request_cart_id,
                $product_category_id,
                $cabin_id,
                $brand_id,
                $model_id,
                $body_type_id,
                $class_id,
                $color_id,
                $make_id,
                $year_model,
                $quantity,
                $unit_id,
                $length,
                $length_unit,
                $price_unit,
                $fx_rate,
                $converted_amount,
                $package_deal,
                $taxes_duties,
                $freight,
                $lto_registration,
                $royalties,
                $conversion,
                $arrastre,
                $wharrfage,
                $insurance,
                $aircon,
                $import_permit,
                $others,
                $total_landed_cost,
                $preorder,
                $remarks,
                $userID
            );

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        }

        $this->purchaseOrderModel->insertPurchaseOrderItemUnit(
            $purchase_order_id,
            $purchase_request_cart_id,
            $product_category_id,
            $cabin_id,
            $brand_id,
            $model_id,
            $body_type_id,
            $class_id,
            $color_id,
            $make_id,
            $year_model,
            $quantity,
            $unit_id,
            $length,
            $length_unit,
            $price_unit,
            $fx_rate,
            $converted_amount,
            $package_deal,
            $taxes_duties,
            $freight,
            $lto_registration,
            $royalties,
            $conversion,
            $arrastre,
            $wharrfage,
            $insurance,
            $aircon,
            $import_permit,
            $others,
            $total_landed_cost,
            $preorder,
            $remarks,
            $userID
        );

        echo json_encode(['success' => true, 'insertRecord' => true]);
        exit;
    }

    public function savePurchaseOrderRecieve() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_cart_id = $_POST['purchase_order_cart_id'];
        $type = $_POST['type'];
        $received_quantity = $_POST['received_quantity'];

        if($type == 'unit'){
            $checkPurchaseOrderItemUnitExist = $this->purchaseOrderModel->checkPurchaseOrderItemUnitExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemUnitExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemUnitReceive($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
        else if($type == 'part'){
            $checkPurchaseOrderItemPartExist = $this->purchaseOrderModel->checkPurchaseOrderItemPartExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemPartExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemPartReceive($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
        else {
            $checkPurchaseOrderItemSupplyExist = $this->purchaseOrderModel->checkPurchaseOrderItemSupplyExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemSupplyExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemSupplyReceive($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
    }

    public function savePurchaseOrderCancel() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_cart_id = $_POST['purchase_order_cart_id'];
        $type = $_POST['type'];
        $received_quantity = $_POST['cancel_received_quantity'];

        if($type == 'unit'){
            $checkPurchaseOrderItemUnitExist = $this->purchaseOrderModel->checkPurchaseOrderItemUnitExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemUnitExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemUnitCancel($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
        else if($type == 'part'){
            $checkPurchaseOrderItemPartExist = $this->purchaseOrderModel->checkPurchaseOrderItemPartExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemPartExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemPartCancel($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
        else {
            $checkPurchaseOrderItemSupplyExist = $this->purchaseOrderModel->checkPurchaseOrderItemSupplyExist($purchase_order_cart_id);
            $total = $checkPurchaseOrderItemSupplyExist['total'] ?? 0;

            if ($total > 0) {
                $this->purchaseOrderModel->updatePurchaseOrderItemSupplyCancel($purchase_order_cart_id, $received_quantity, $userID);

                echo json_encode(value: ['success' => true]);
                exit;
            }
        }
    }

    public function tagAsForApproval() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrder($purchase_order_id);
        $purchase_order_type = $purchaseOrderDetails['purchase_order_type'] ?? '';

        if($purchase_order_type == 'Product'){
            $getPurchaseOrderCartCount = $this->purchaseOrderModel->getPurchaseOrderCartUnitCount($purchase_order_id);
        }
        else if($purchase_order_type == 'Parts'){
            $getPurchaseOrderCartCount = $this->purchaseOrderModel->getPurchaseOrderCartPartCount($purchase_order_id);
        }
        else if($purchase_order_type == 'Supplies'){
            $getPurchaseOrderCartCount = $this->purchaseOrderModel->getPurchaseOrderCartSupplyCount($purchase_order_id);
        }
      
        $count = $getPurchaseOrderCartCount['total'] ?? 0;

        if($count == 0) {
            echo json_encode(value: ['success' => false, 'message' => 'Please add items to the purchase order.']);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderForApproval($purchase_order_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsOnProcess() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderOnProcess($purchase_order_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsComplete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrder($purchase_order_id);
        $purchase_order_type = $purchaseOrderDetails['purchase_order_type'] ?? '';
        $company_id = $purchaseOrderDetails['company_id'] ?? '';
        $supplierID = $purchaseOrderDetails['supplier_id'] ?? '';
        $reference_no = $purchaseOrderDetails['reference_no'] ?? '';

        if($purchase_order_type == 'Product'){
            $cartID = $this->purchaseOrderModel->getPurchaseOrderCartUnit($purchase_order_id);
        }

        foreach ($cartID as $row) {
            if($purchase_order_type == 'Product'){
                $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderUnit($row['purchase_order_unit_id']);
                $purchase_request_cart_id = $purchaseOrderDetails['purchase_request_cart_id'];
                $brand_id = $purchaseOrderDetails['brand_id'];
                $model_id = $purchaseOrderDetails['model_id'];
                $body_type_id = $purchaseOrderDetails['body_type_id'];
                $class_id = $purchaseOrderDetails['class_id'];
                $color_id = $purchaseOrderDetails['color_id'];
                $make_id = $purchaseOrderDetails['make_id'];
                $year_model = $purchaseOrderDetails['year_model'];
                $product_category_id = $purchaseOrderDetails['product_category_id'];
                $length = $purchaseOrderDetails['length'];
                $length_unit = $purchaseOrderDetails['length_unit'];
                $cabin_id = $purchaseOrderDetails['cabin_id'];
                $quantity = $purchaseOrderDetails['quantity'];
                $unit_id = $purchaseOrderDetails['unit_id'];
                $price_unit = $purchaseOrderDetails['price']; // Note: mapping 'price' to $price_unit
                $remarks = $purchaseOrderDetails['remarks'];
                $preorder = $purchaseOrderDetails['preorder'] ?? '';
                $fx_rate = $purchaseOrderDetails['fx_rate'] ?? 0;
                $converted_amount = $purchaseOrderDetails['converted_amount'] ?? 0;
                $package_deal = $purchaseOrderDetails['package_deal'] ?? 0;
                $taxes_duties = $purchaseOrderDetails['taxes_duties'] ?? 0;
                $freight = $purchaseOrderDetails['freight'] ?? 0;
                $lto_registration = $purchaseOrderDetails['lto_registration'] ?? 0;
                $royalties = $purchaseOrderDetails['royalties'] ?? 0;
                $conversion = $purchaseOrderDetails['conversion'] ?? 0;
                $arrastre = $purchaseOrderDetails['arrastre'] ?? 0;
                $wharrfage = $purchaseOrderDetails['wharrfage'] ?? 0;
                $insurance = $purchaseOrderDetails['insurance'] ?? 0;
                $aircon = $purchaseOrderDetails['aircon'] ?? 0;
                $import_permit = $purchaseOrderDetails['import_permit'] ?? 0;
                $others = $purchaseOrderDetails['others'] ?? 0;
                $total_landed_cost = $purchaseOrderDetails['total_landed_cost'] ?? 0;

                $productSubcategoryDetails = $this->productSubcategoryModel->getProductSubcategory($product_category_id);
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

                $brandName = $this->brandModel->getBrand($brand_id)['brand_name'] ?? '';
                $makeName = $this->makeModel->getMake($make_id)['make_name'] ?? '';
                $cabinName = $this->cabinModel->getCabin($cabin_id)['cabin_name'] ?? '';
                $modelName = $this->modelModel->getModel($model_id)['model_name'] ?? '';
                $bodyTypeName = $this->bodyTypeModel->getBodyType($body_type_id)['body_type_name'] ?? '';

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

                $productID = $this->productModel->insertProduct(
                    $productCategoryID,
                    $product_category_id,
                    $company_id,
                    $stockNumber,
                    '',
                    '',
                    '',
                    $description,
                    '',
                    $body_type_id,
                    $length,
                    $length_unit,
                    '',
                    '',
                    $color_id,
                    $remarks,
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    '',
                    $supplierID,
                    '',
                    $brand_id,
                    $cabin_id,
                    $model_id,
                    $make_id,
                    $class_id,
                    '',
                    '',
                    '',
                    '',
                    $year_model,
                    '',
                    '',
                    '',
                    '',
                    '',
                    $quantity,
                    $preorder,
                    'No',
                    $userID
                );

                $this->productModel->updateProductLandedCost(
                    $productID,
                    $price_unit,
                    0,
                    $fx_rate,
                    $converted_amount,
                    $price_unit,
                    $package_deal,
                    $taxes_duties,
                    $freight,
                    $lto_registration,
                    $royalties,
                    $conversion,
                    $arrastre,
                    $wharrfage,
                    $insurance,
                    $aircon,
                    $import_permit,
                    $others,
                    $total_landed_cost,
                    '',
                    '',
                    '',
                    $userID
                );

                $this->purchaseOrderModel->createPurchaseOrderUnitEntry($purchase_order_id, $company_id, $reference_no, $total_landed_cost, $userID);
            }
        }

        $this->purchaseOrderModel->updatePurchaseOrderComplete($purchase_order_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsCancelled() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $cancellation_reason = htmlspecialchars($_POST['cancellation_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderCancel($purchase_order_id, $cancellation_reason, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsDraft() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $draft_reason = htmlspecialchars($_POST['draft_reason'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderDraft($purchase_order_id, $draft_reason, $userID);
        $this->purchaseOrderModel->updatePurchaseOrderDraftUnitReceive($purchase_order_id, $userID);
        $this->purchaseOrderModel->updatePurchaseOrderDraftPartReceive($purchase_order_id, $userID);
        $this->purchaseOrderModel->updatePurchaseOrderDraftSupplyReceive($purchase_order_id, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function tagAsApproved() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $userID = $_SESSION['user_id'];
        $purchase_order_id = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
        $approval_remarks = htmlspecialchars($_POST['approval_remarks'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->purchaseOrderModel->updatePurchaseOrderApprove($purchase_order_id, $approval_remarks, $userID);
        
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePurchaseOrder
    # Description: 
    # Delete the purchase order if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseOrderID = htmlspecialchars($_POST['purchase_order_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPurchaseOrderExist = $this->purchaseOrderModel->checkPurchaseOrderExist($purchaseOrderID);
        $total = $checkPurchaseOrderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrder($purchaseOrderID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePurchaseOrderUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_unit_id = htmlspecialchars($_POST['purchase_order_unit_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrderUnit($purchase_order_unit_id);

        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePurchaseOrderPart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_part_id = htmlspecialchars($_POST['purchase_order_part_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrderPart($purchase_order_part_id);

        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePurchaseOrderSupply() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchase_order_supply_id = htmlspecialchars($_POST['purchase_order_supply_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->purchaseOrderModel->deletePurchaseOrderSupply($purchase_order_supply_id);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePurchaseOrder
    # Description: 
    # Delete the selected purchase orders if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePurchaseOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $purchaseOrderIDs = $_POST['purchase_order_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($purchaseOrderIDs as $purchaseOrderID){
            $this->purchaseOrderModel->deletePurchaseOrder($purchaseOrderID);
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
    # Function: getPurchaseOrderDetails
    # Description: 
    # Handles the retrieval of purchase order details such as purchase order name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPurchaseOrderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_order_id']) && !empty($_POST['purchase_order_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_order_id = $_POST['purchase_order_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrder($purchase_order_id);

            $response = [
                'success' => true,
                'reference_no' => $purchaseOrderDetails['reference_no'],
                'purchase_order_type' => $purchaseOrderDetails['purchase_order_type'],
                'company_id' => $purchaseOrderDetails['company_id'],
                'supplier_id' => $purchaseOrderDetails['supplier_id'],
                'remarks' => $purchaseOrderDetails['remarks']
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getPurchaseOrderUnitDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_order_unit_id']) && !empty($_POST['purchase_order_unit_id'])) {
            $userID = $_SESSION['user_id'];
            $purchase_order_unit_id = $_POST['purchase_order_unit_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderUnit($purchase_order_unit_id);

            $response = [
                'success' => true,
                'purchase_request_cart_id' => $purchaseOrderDetails['purchase_request_cart_id'] ?? null,
                'brand_id' => $purchaseOrderDetails['brand_id'] ?? null,
                'model_id' => $purchaseOrderDetails['model_id'] ?? null,
                'body_type_id' => $purchaseOrderDetails['body_type_id'] ?? null,
                'class_id' => $purchaseOrderDetails['class_id'] ?? null,
                'color_id' => $purchaseOrderDetails['color_id'] ?? null,
                'make_id' => $purchaseOrderDetails['make_id'] ?? null,
                'year_model' => $purchaseOrderDetails['year_model'] ?? '',
                'product_category_id' => $purchaseOrderDetails['product_category_id'] ?? null,
                'length' => $purchaseOrderDetails['length'] ?? 0,
                'length_unit' => $purchaseOrderDetails['length_unit'] ?? null,
                'cabin_id' => $purchaseOrderDetails['cabin_id'] ?? null,
                'quantity' => $purchaseOrderDetails['quantity'] ?? 1,
                'unit_id' => $purchaseOrderDetails['unit_id'] ?? null,
                'price_unit' => $purchaseOrderDetails['price'] ?? 0,
                'remarks' => $purchaseOrderDetails['remarks'] ?? '',
                'preorder' => $purchaseOrderDetails['preorder'] ?? '',
                'fx_rate' => $purchaseOrderDetails['fx_rate'] ?? 0,
                'converted_amount' => $purchaseOrderDetails['converted_amount'] ?? 0,
                'package_deal' => $purchaseOrderDetails['package_deal'] ?? 0,
                'taxes_duties' => $purchaseOrderDetails['taxes_duties'] ?? 0,
                'freight' => $purchaseOrderDetails['freight'] ?? 0,
                'lto_registration' => $purchaseOrderDetails['lto_registration'] ?? 0,
                'royalties' => $purchaseOrderDetails['royalties'] ?? 0,
                'conversion' => $purchaseOrderDetails['conversion'] ?? 0,
                'arrastre' => $purchaseOrderDetails['arrastre'] ?? 0,
                'wharrfage' => $purchaseOrderDetails['wharrfage'] ?? 0,
                'insurance' => $purchaseOrderDetails['insurance'] ?? 0,
                'aircon' => $purchaseOrderDetails['aircon'] ?? 0,
                'import_permit' => $purchaseOrderDetails['import_permit'] ?? 0,
                'others' => $purchaseOrderDetails['others'] ?? 0,
                'total_landed_cost' => $purchaseOrderDetails['total_landed_cost'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getReceiveDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['purchase_order_cart_id']) && !empty($_POST['purchase_order_cart_id']) && isset($_POST['type']) && !empty($_POST['type'])) {
            $userID = $_SESSION['user_id'];
            $purchase_order_cart_id = $_POST['purchase_order_cart_id'];
            $type = $_POST['type'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }

            if($type === 'unit'){
                $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderUnit($purchase_order_cart_id);
            }
            else if($type === 'part'){
                $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderPart($purchase_order_cart_id);
            }
            else if($type === 'supply'){
                $purchaseOrderDetails = $this->purchaseOrderModel->getPurchaseOrderSupply($purchase_order_cart_id);
            }

            $quantity = $purchaseOrderDetails['quantity'];
            $actual_quantity = $purchaseOrderDetails['actual_quantity'];
            $cancelled_quantity = $purchaseOrderDetails['cancelled_quantity'];

            $remaining_quantity = $quantity - ($actual_quantity + $cancelled_quantity);

            $response = [
                'success' => true,
                'remaining_quantity' => $remaining_quantity
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
require_once '../model/purchase-order-model.php';
require_once '../model/purchase-request-model.php';
require_once '../model/parts-model.php';
require_once '../model/unit-model.php';
require_once '../model/product-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/brand-model.php';
require_once '../model/make-model.php';
require_once '../model/cabin-model.php';
require_once '../model/model-model.php';
require_once '../model/body-type-model.php';
require_once '../model/system-model.php';

$controller = new PurchaseOrderController(new PurchaseOrderModel(new DatabaseModel), new PurchaseRequestModel(new DatabaseModel), new PartsModel(new DatabaseModel), new ProductModel(new DatabaseModel), new ProductSubcategoryModel(new DatabaseModel), new UnitModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new BrandModel(new DatabaseModel), new MakeModel(new DatabaseModel), new CabinModel(new DatabaseModel), new ModelModel(new DatabaseModel), new BodyTypeModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
