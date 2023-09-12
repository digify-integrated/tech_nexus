<?php
session_start();

# -------------------------------------------------------------
#
# Function: EmployeeTypeController
# Description: 
# The EmployeeTypeController class handles employee type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class EmployeeTypeController {
    private $employeeTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EmployeeTypeModel, UserModel and SecurityModel instances.
    # These instances are used for employee type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param EmployeeTypeModel $employeeTypeModel     The EmployeeTypeModel instance for employee type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeTypeModel $employeeTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->employeeTypeModel = $employeeTypeModel;
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
                case 'save employee type':
                    $this->saveEmployeeType();
                    break;
                case 'get employee type details':
                    $this->getEmployeeTypeDetails();
                    break;
                case 'delete employee type':
                    $this->deleteEmployeeType();
                    break;
                case 'delete multiple employee type':
                    $this->deleteMultipleEmployeeType();
                    break;
                case 'duplicate employee type':
                    $this->duplicateEmployeeType();
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
    # Function: saveEmployeeType
    # Description: 
    # Updates the existing employee type if it exists; otherwise, inserts a new employee type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveEmployeeType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeTypeID = isset($_POST['employee_type_id']) ? htmlspecialchars($_POST['employee_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeTypeName = htmlspecialchars($_POST['employee_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeeTypeExist = $this->employeeTypeModel->checkEmployeeTypeExist($employeeTypeID);
        $total = $checkEmployeeTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeTypeModel->updateEmployeeType($employeeTypeID, $employeeTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'employeeTypeID' => $this->securityModel->encryptData($employeeTypeID)]);
            exit;
        } 
        else {
            $employeeTypeID = $this->employeeTypeModel->insertEmployeeType($employeeTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'employeeTypeID' => $this->securityModel->encryptData($employeeTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmployeeType
    # Description: 
    # Delete the employee type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteEmployeeType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeTypeID = htmlspecialchars($_POST['employee_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeeTypeExist = $this->employeeTypeModel->checkEmployeeTypeExist($employeeTypeID);
        $total = $checkEmployeeTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeTypeModel->deleteEmployeeType($employeeTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleEmployeeType
    # Description: 
    # Delete the selected employee types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleEmployeeType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeTypeIDs = $_POST['employee_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($employeeTypeIDs as $employeeTypeID){
            $this->employeeTypeModel->deleteEmployeeType($employeeTypeID);
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
    # Function: duplicateEmployeeType
    # Description: 
    # Duplicates the employee type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateEmployeeType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeTypeID = htmlspecialchars($_POST['employee_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeeTypeExist = $this->employeeTypeModel->checkEmployeeTypeExist($employeeTypeID);
        $total = $checkEmployeeTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $employeeTypeID = $this->employeeTypeModel->duplicateEmployeeType($employeeTypeID, $userID);

        echo json_encode(['success' => true, 'employeeTypeID' =>  $this->securityModel->encryptData($employeeTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmployeeTypeDetails
    # Description: 
    # Handles the retrieval of employee type details such as employee type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEmployeeTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['employee_type_id']) && !empty($_POST['employee_type_id'])) {
            $userID = $_SESSION['user_id'];
            $employeeTypeID = $_POST['employee_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $employeeTypeDetails = $this->employeeTypeModel->getEmployeeType($employeeTypeID);

            $response = [
                'success' => true,
                'employeeTypeName' => $employeeTypeDetails['employee_type_name']
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
require_once '../model/employee-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EmployeeTypeController(new EmployeeTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>