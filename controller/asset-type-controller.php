<?php
session_start();

# -------------------------------------------------------------
#
# Function: AssetTypeController
# Description: 
# The AssetTypeController class handles asset type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class AssetTypeController {
    private $assetTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided AssetTypeModel, UserModel and SecurityModel instances.
    # These instances are used for asset type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param AssetTypeModel $assetTypeModel     The AssetTypeModel instance for asset type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(AssetTypeModel $assetTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->assetTypeModel = $assetTypeModel;
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
                case 'save asset type':
                    $this->saveAssetType();
                    break;
                case 'get asset type details':
                    $this->getAssetTypeDetails();
                    break;
                case 'delete asset type':
                    $this->deleteAssetType();
                    break;
                case 'delete multiple asset type':
                    $this->deleteMultipleAssetType();
                    break;
                case 'duplicate asset type':
                    $this->duplicateAssetType();
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
    # Function: saveAssetType
    # Description: 
    # Updates the existing asset type if it exists; otherwise, inserts a new asset type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAssetType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $assetTypeID = isset($_POST['asset_type_id']) ? htmlspecialchars($_POST['asset_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $assetTypeName = htmlspecialchars($_POST['asset_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAssetTypeExist = $this->assetTypeModel->checkAssetTypeExist($assetTypeID);
        $total = $checkAssetTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->assetTypeModel->updateAssetType($assetTypeID, $assetTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'assetTypeID' => $this->securityModel->encryptData($assetTypeID)]);
            exit;
        } 
        else {
            $assetTypeID = $this->assetTypeModel->insertAssetType($assetTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'assetTypeID' => $this->securityModel->encryptData($assetTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAssetType
    # Description: 
    # Delete the asset type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteAssetType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $assetTypeID = htmlspecialchars($_POST['asset_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAssetTypeExist = $this->assetTypeModel->checkAssetTypeExist($assetTypeID);
        $total = $checkAssetTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->assetTypeModel->deleteAssetType($assetTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleAssetType
    # Description: 
    # Delete the selected asset types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleAssetType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $assetTypeIDs = $_POST['asset_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($assetTypeIDs as $assetTypeID){
            $this->assetTypeModel->deleteAssetType($assetTypeID);
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
    # Function: duplicateAssetType
    # Description: 
    # Duplicates the asset type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateAssetType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $assetTypeID = htmlspecialchars($_POST['asset_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkAssetTypeExist = $this->assetTypeModel->checkAssetTypeExist($assetTypeID);
        $total = $checkAssetTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $assetTypeID = $this->assetTypeModel->duplicateAssetType($assetTypeID, $userID);

        echo json_encode(['success' => true, 'assetTypeID' =>  $this->securityModel->encryptData($assetTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAssetTypeDetails
    # Description: 
    # Handles the retrieval of asset type details such as asset type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getAssetTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['asset_type_id']) && !empty($_POST['asset_type_id'])) {
            $userID = $_SESSION['user_id'];
            $assetTypeID = $_POST['asset_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $assetTypeDetails = $this->assetTypeModel->getAssetType($assetTypeID);

            $response = [
                'success' => true,
                'assetTypeName' => $assetTypeDetails['asset_type_name']
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
require_once '../model/asset-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new AssetTypeController(new AssetTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>