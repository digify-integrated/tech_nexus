<?php
session_start();

# -------------------------------------------------------------
#
# Function: DocumentCategoryController
# Description: 
# The DocumentCategoryController class handles document category related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DocumentCategoryController {
    private $documentCategoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DocumentCategoryModel, UserModel and SecurityModel instances.
    # These instances are used for document category related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DocumentCategoryModel $documentCategoryModel     The DocumentCategoryModel instance for document category related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DocumentCategoryModel $documentCategoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->documentCategoryModel = $documentCategoryModel;
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
                case 'save document category':
                    $this->saveDocumentCategory();
                    break;
                case 'get document category details':
                    $this->getDocumentCategoryDetails();
                    break;
                case 'delete document category':
                    $this->deleteDocumentCategory();
                    break;
                case 'delete multiple document category':
                    $this->deleteMultipleDocumentCategory();
                    break;
                case 'duplicate document category':
                    $this->duplicateDocumentCategory();
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
    # Function: saveDocumentCategory
    # Description: 
    # Updates the existing document category if it exists; otherwise, inserts a new document category.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDocumentCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentCategoryID = isset($_POST['document_category_id']) ? htmlspecialchars($_POST['document_category_id'], ENT_QUOTES, 'UTF-8') : null;
        $documentCategoryName = htmlspecialchars($_POST['document_category_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentCategoryExist = $this->documentCategoryModel->checkDocumentCategoryExist($documentCategoryID);
        $total = $checkDocumentCategoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->documentCategoryModel->updateDocumentCategory($documentCategoryID, $documentCategoryName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'documentCategoryID' => $this->securityModel->encryptData($documentCategoryID)]);
            exit;
        } 
        else {
            $documentCategoryID = $this->documentCategoryModel->insertDocumentCategory($documentCategoryName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'documentCategoryID' => $this->securityModel->encryptData($documentCategoryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDocumentCategory
    # Description: 
    # Delete the document category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDocumentCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentCategoryID = htmlspecialchars($_POST['document_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentCategoryExist = $this->documentCategoryModel->checkDocumentCategoryExist($documentCategoryID);
        $total = $checkDocumentCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->documentCategoryModel->deleteDocumentCategory($documentCategoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDocumentCategory
    # Description: 
    # Delete the selected document categorys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDocumentCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentCategoryIDs = $_POST['document_category_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($documentCategoryIDs as $documentCategoryID){
            $this->documentCategoryModel->deleteDocumentCategory($documentCategoryID);
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
    # Function: duplicateDocumentCategory
    # Description: 
    # Duplicates the document category if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateDocumentCategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentCategoryID = htmlspecialchars($_POST['document_category_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentCategoryExist = $this->documentCategoryModel->checkDocumentCategoryExist($documentCategoryID);
        $total = $checkDocumentCategoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $documentCategoryID = $this->documentCategoryModel->duplicateDocumentCategory($documentCategoryID, $userID);

        echo json_encode(['success' => true, 'documentCategoryID' =>  $this->securityModel->encryptData($documentCategoryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDocumentCategoryDetails
    # Description: 
    # Handles the retrieval of document category details such as document category name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDocumentCategoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['document_category_id']) && !empty($_POST['document_category_id'])) {
            $userID = $_SESSION['user_id'];
            $documentCategoryID = $_POST['document_category_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $documentCategoryDetails = $this->documentCategoryModel->getDocumentCategory($documentCategoryID);

            $response = [
                'success' => true,
                'documentCategoryName' => $documentCategoryDetails['document_category_name']
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
require_once '../model/document-category-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DocumentCategoryController(new DocumentCategoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>