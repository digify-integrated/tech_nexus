<?php
session_start();

# -------------------------------------------------------------
#
# Function: BrandController
# Description: 
# The BrandController class handles brand related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BrandController {
    private $brandModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided BrandModel, UserModel and SecurityModel instances.
    # These instances are used for brand related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BrandModel $brandModel     The BrandModel instance for brand related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(BrandModel $brandModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->brandModel = $brandModel;
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
                case 'save brand':
                    $this->saveBrand();
                    break;
                case 'get brand details':
                    $this->getBrandDetails();
                    break;
                case 'delete brand':
                    $this->deleteBrand();
                    break;
                case 'delete multiple brand':
                    $this->deleteMultipleBrand();
                    break;
                case 'duplicate brand':
                    $this->duplicateBrand();
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
    # Function: saveBrand
    # Description: 
    # Updates the existing brand if it exists; otherwise, inserts a new brand.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBrand() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brandID = isset($_POST['brand_id']) ? htmlspecialchars($_POST['brand_id'], ENT_QUOTES, 'UTF-8') : null;
        $brandName = htmlspecialchars($_POST['brand_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBrandExist = $this->brandModel->checkBrandExist($brandID);
        $total = $checkBrandExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->brandModel->updateBrand($brandID, $brandName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'brandID' => $this->securityModel->encryptData($brandID)]);
            exit;
        } 
        else {
            $brandID = $this->brandModel->insertBrand($brandName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'brandID' => $this->securityModel->encryptData($brandID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBrand
    # Description: 
    # Delete the brand if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBrand() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brandID = htmlspecialchars($_POST['brand_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBrandExist = $this->brandModel->checkBrandExist($brandID);
        $total = $checkBrandExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->brandModel->deleteBrand($brandID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBrand
    # Description: 
    # Delete the selected brands if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBrand() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brandIDs = $_POST['brand_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($brandIDs as $brandID){
            $this->brandModel->deleteBrand($brandID);
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
    # Function: duplicateBrand
    # Description: 
    # Duplicates the brand if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBrand() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $brandID = htmlspecialchars($_POST['brand_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBrandExist = $this->brandModel->checkBrandExist($brandID);
        $total = $checkBrandExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $brandID = $this->brandModel->duplicateBrand($brandID, $userID);

        echo json_encode(['success' => true, 'brandID' =>  $this->securityModel->encryptData($brandID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBrandDetails
    # Description: 
    # Handles the retrieval of brand details such as brand name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBrandDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['brand_id']) && !empty($_POST['brand_id'])) {
            $userID = $_SESSION['user_id'];
            $brandID = $_POST['brand_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $brandDetails = $this->brandModel->getBrand($brandID);

            $response = [
                'success' => true,
                'brandName' => $brandDetails['brand_name']
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
require_once '../model/brand-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BrandController(new BrandModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>