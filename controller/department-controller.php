<?php
session_start();

# -------------------------------------------------------------
#
# Function: DepartmentController
# Description: 
# The DepartmentController class handles department related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DepartmentController {
    private $departmentModel;
    private $userModel;
    private $roleModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DepartmentModel, UserModel and SecurityModel instances.
    # These instances are used for department related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DepartmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DepartmentModel $departmentModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->departmentModel = $departmentModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
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
                case 'save department':
                    $this->saveDepartment();
                    break;
                case 'get department details':
                    $this->getDepartmentDetails();
                    break;
                case 'delete department':
                    $this->deleteDepartment();
                    break;
                case 'delete multiple department':
                    $this->deleteMultipleDepartment();
                    break;
                case 'duplicate department':
                    $this->duplicateDepartment();
                    break;
                case 'change department logo':
                    $this->updateDepartmentLogo();
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
    # Function: saveDepartment
    # Description: 
    # Updates the existing department if it exists; otherwise, inserts a new department.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDepartment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departmentID = isset($_POST['department_id']) ? htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8') : null;
        $departmentName = htmlspecialchars($_POST['department_name'], ENT_QUOTES, 'UTF-8');
        $parentDepartment = htmlspecialchars($_POST['parent_department'], ENT_QUOTES, 'UTF-8');
        $manager = htmlspecialchars($_POST['manager'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartmentExist = $this->departmentModel->checkDepartmentExist($departmentID);
        $total = $checkDepartmentExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->departmentModel->updateDepartment($departmentID, $departmentName, $parentDepartment, $manager, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'departmentID' => $this->securityModel->encryptData($departmentID)]);
            exit;
        } 
        else {
            $departmentID = $this->departmentModel->insertDepartment($departmentName, $parentDepartment, $manager, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'departmentID' => $this->securityModel->encryptData($departmentID)]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDepartment
    # Description: 
    # Delete the department if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDepartment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartmentExist = $this->departmentModel->checkDepartmentExist($departmentID);
        $total = $checkDepartmentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->departmentModel->deleteDepartment($departmentID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDepartment
    # Description: 
    # Delete the selected departments if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDepartment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departmentIDs = $_POST['department_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($departmentIDs as $departmentID){
            $this->departmentModel->deleteDepartment($departmentID);
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
    # Function: duplicateDepartment
    # Description: 
    # Duplicates the department if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateDepartment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartmentExist = $this->departmentModel->checkDepartmentExist($departmentID);
        $total = $checkDepartmentExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $departmentID = $this->departmentModel->duplicateDepartment($departmentID, $userID);

        echo json_encode(['success' => true, 'departmentID' =>  $this->securityModel->encryptData($departmentID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDepartmentDetails
    # Description: 
    # Handles the retrieval of department details such as department name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDepartmentDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['department_id']) && !empty($_POST['department_id'])) {
            $userID = $_SESSION['user_id'];
            $departmentID = $_POST['department_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $departmentDetails = $this->departmentModel->getDepartment($departmentID);
            $parentDepartment = $departmentDetails['parent_department'];

            $parentDepartmentDetails = $this->departmentModel->getDepartment($parentDepartment);
            $parentDepartmentName = $parentDepartmentDetails['department_name'];

            $response = [
                'success' => true,
                'departmentName' => $departmentDetails['department_name'],
                'parentDepartment' => $parentDepartment,
                'parentDepartmentName' => $parentDepartmentName,
                'manager' => null
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
require_once '../model/department-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DepartmentController(new DepartmentModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>