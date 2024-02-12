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
    private $securityModel;

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
    public function __construct(ProductSubcategoryModel $productSubcategoryModel, ProductCategoryModel $productCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->productSubcategoryModel = $productSubcategoryModel;
        $this->productCategoryModel = $productCategoryModel;
        $this->userModel = $userModel;
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
                case 'save product subcategory':
                    $this->saveProductSubcategory();
                    break;
                case 'get product subcategory details':
                    $this->getProductSubcategoryDetails();
                    break;
                case 'delete product subcategory':
                    $this->deleteProductSubcategory();
                    break;
                case 'delete multiple product subcategory':
                    $this->deleteMultipleProductSubcategory();
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
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductSubcategoryExist = $this->productSubcategoryModel->checkProductSubcategoryExist($productSubcategoryID);
        $total = $checkProductSubcategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productSubcategoryModel->updateProductSubcategory($productSubcategoryID, $productSubcategoryName, $productSubcategoryCode, $productCategoryID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productSubcategoryID' => $this->securityModel->encryptData($productSubcategoryID)]);
            exit;
        } 
        else {
            $productSubcategoryID = $this->productSubcategoryModel->insertProductSubcategory($productSubcategoryName, $productSubcategoryCode, $productCategoryID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'productSubcategoryID' => $this->securityModel->encryptData($productSubcategoryID)]);
            exit;
        }
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
                'productCategoryID' => $productCategoryID,
                'productCategoryName' => $productCategoryName
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
require_once '../model/system-model.php';

$controller = new ProductSubcategoryController(new ProductSubcategoryModel(new DatabaseModel), new ProductCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>