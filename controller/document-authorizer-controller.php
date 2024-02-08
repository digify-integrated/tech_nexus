<?php
session_start();

# -------------------------------------------------------------
#
# Function: DocumentAuthorizerController
# Description: 
# The DocumentAuthorizerController class handles document authorizer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DocumentAuthorizerController {
    private $documentAuthorizerModel;
    private $employeeModel;
    private $departmentModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DocumentAuthorizerModel, UserModel and SecurityModel instances.
    # These instances are used for document authorizer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DocumentAuthorizerModel $documentAuthorizerModel     The DocumentAuthorizerModel instance for document authorizer related operations.
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param DepartmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DocumentAuthorizerModel $documentAuthorizerModel, EmployeeModel $employeeModel, DepartmentModel $departmentModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->documentAuthorizerModel = $documentAuthorizerModel;
        $this->userModel = $userModel;
        $this->employeeModel = $employeeModel;
        $this->departmentModel = $departmentModel;
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
                case 'save document authorizer':
                    $this->saveDocumentAuthorizer();
                    break;
                case 'get document authorizer details':
                    $this->getDocumentAuthorizerDetails();
                    break;
                case 'delete document authorizer':
                    $this->deleteDocumentAuthorizer();
                    break;
                case 'delete multiple document authorizer':
                    $this->deleteMultipleDocumentAuthorizer();
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
    # Function: saveDocumentAuthorizer
    # Description: 
    # Updates the existing document authorizer if it exists; otherwise, inserts a new document authorizer.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDocumentAuthorizer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $authorizerID = htmlspecialchars($_POST['authorizer_id'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentDepartmentAuthorizerExist = $this->documentAuthorizerModel->checkDocumentDepartmentAuthorizerExist($departmentID, $authorizerID);
        $total = $checkDocumentDepartmentAuthorizerExist['total'] ?? 0;
    
        if ($total > 0) {            
            echo json_encode(['success' => false, 'exist' => true]);
            exit;
        }

        $documentAuthorizerID = $this->documentAuthorizerModel->insertDocumentAuthorizer($departmentID, $authorizerID, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'documentAuthorizerID' => $this->securityModel->encryptData($documentAuthorizerID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDocumentAuthorizer
    # Description: 
    # Delete the document authorizer if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDocumentAuthorizer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentAuthorizerID = htmlspecialchars($_POST['document_authorizer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDocumentAuthorizerExist = $this->documentAuthorizerModel->checkDocumentAuthorizerExist($documentAuthorizerID);
        $total = $checkDocumentAuthorizerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->documentAuthorizerModel->deleteDocumentAuthorizer($documentAuthorizerID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDocumentAuthorizer
    # Description: 
    # Delete the selected document authorizers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDocumentAuthorizer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $documentAuthorizerIDs = $_POST['document_authorizer_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($documentAuthorizerIDs as $documentAuthorizerID){
            $this->documentAuthorizerModel->deleteDocumentAuthorizer($documentAuthorizerID);
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
    # Function: getDocumentAuthorizerDetails
    # Description: 
    # Handles the retrieval of document authorizer details such as document authorizer name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDocumentAuthorizerDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['document_authorizer_id']) && !empty($_POST['document_authorizer_id'])) {
            $userID = $_SESSION['user_id'];
            $documentAuthorizerID = $_POST['document_authorizer_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $documentAuthorizerDetails = $this->documentAuthorizerModel->getDocumentAuthorizer($documentAuthorizerID);
            $departmentID = $documentAuthorizerDetails['department_id'];
            $authorizerID = $documentAuthorizerDetails['authorizer_id'];

            $departmentName = $this->departmentModel->getDepartment($departmentID)['department_name'] ?? null;

            $documentAuthorizerDetails = $this->employeeModel->getPersonalInformation($authorizerID);
            $authorizerName = $documentAuthorizerDetails['file_as'] ?? null;

            $response = [
                'success' => true,
                'authorizerName' => $authorizerName,
                'departmentName' => $departmentName
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
require_once '../model/document-authorizer-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DocumentAuthorizerController(new DocumentAuthorizerModel(new DatabaseModel), new EmployeeModel(new DatabaseModel), new DepartmentModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>