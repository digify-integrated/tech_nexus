<?php
session_start();

# -------------------------------------------------------------
#
# Function: ProductCategoryController
# Description: 
# The ProductCategoryController class handles product category related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ProductCategoryController {
    private $productCategoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ProductCategoryModel, UserModel and SecurityModel instances.
    # These instances are used for product category related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ProductCategoryModel $productCategoryModel     The ProductCategoryModel instance for product category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ProductCategoryModel $productCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
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
                case 'save product category':
                    $this->saveProductCategory();
                    break;
                case 'get product category details':
                    $this->getProductCategoryDetails();
                    break;
                case 'delete product category':
                    $this->deleteProductCategory();
                    break;
                case 'delete multiple product category':
                    $this->deleteMultipleProductCategory();
                    break;
                case 'duplicate product category':
                    $this->duplicateProductCategory();
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
    # Function: saveProductCategory
    # Description: 
    # Updates the existing product category if it exists; otherwise, inserts a new product category.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveProductCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productCategoryID = isset($_POST['product_category_id']) ? htmlspecialchars($_POST['product_category_id'], ENT_QUOTES, 'UTF-8') : null;
        $productCategoryName = htmlspecialchars($_POST['product_category_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductCategoryExist = $this->productCategoryModel->checkProductCategoryExist($productCategoryID);
        $total = $checkProductCategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->productCategoryModel->updateProductCategory($productCategoryID, $productCategoryName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'productCategoryID' => $this->securityModel->encryptData($productCategoryID)]);
            exit;
        } 
        else {
            $productCategoryID = $this->productCategoryModel->insertProductCategory($productCategoryName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'productCategoryID' => $this->securityModel->encryptData($productCategoryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProductCategory
    # Description: 
    # Delete the product category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteProductCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productCategoryID = htmlspecialchars($_POST['product_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductCategoryExist = $this->productCategoryModel->checkProductCategoryExist($productCategoryID);
        $total = $checkProductCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->productCategoryModel->deleteProductCategory($productCategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleProductCategory
    # Description: 
    # Delete the selected product categorys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleProductCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productCategoryIDs = $_POST['product_category_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($productCategoryIDs as $productCategoryID){
            $this->productCategoryModel->deleteProductCategory($productCategoryID);
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
    # Function: duplicateProductCategory
    # Description: 
    # Duplicates the product category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateProductCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $productCategoryID = htmlspecialchars($_POST['product_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProductCategoryExist = $this->productCategoryModel->checkProductCategoryExist($productCategoryID);
        $total = $checkProductCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $productCategoryID = $this->productCategoryModel->duplicateProductCategory($productCategoryID, $userID);

        echo json_encode(['success' => true, 'productCategoryID' =>  $this->securityModel->encryptData($productCategoryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getProductCategoryDetails
    # Description: 
    # Handles the retrieval of product category details such as product category name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getProductCategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['product_category_id']) && !empty($_POST['product_category_id'])) {
            $userID = $_SESSION['user_id'];
            $productCategoryID = $_POST['product_category_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $productCategoryDetails = $this->productCategoryModel->getProductCategory($productCategoryID);

            $response = [
                'success' => true,
                'productCategoryName' => $productCategoryDetails['product_category_name']
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
require_once '../model/product-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ProductCategoryController(new ProductCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>