<?php
session_start();

# -------------------------------------------------------------
#
# Function: EmployeeController
# Description: 
# The EmployeeController class handles employee related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class EmployeeController {
    private $employeeModel;
    private $userModel;
    private $systemSettingModel;
    private $departmentModel;
    private $jobPositionModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided EmployeeModel, UserModel and SecurityModel instances.
    # These instances are used for employee related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param EmployeeModel $employeeModel     The EmployeeModel instance for employee related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param DepartmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param JobPositionModel $jobPositionModel     The JobPositionModel instance for job position related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeModel $employeeModel, UserModel $userModel, DepartmentModel $departmentModel, JobPositionModel $jobPositionModel, SystemSettingModel $systemSettingModel, SecurityModel $securityModel) {
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->departmentModel = $departmentModel;
        $this->jobPositionModel = $jobPositionModel;
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
                case 'add employee':
                    $this->insertEmployee();
                    break;
                case 'get employee personal information details':
                    $this->getEmployeePersonalInformation();
                    break;
                case 'delete employee':
                    $this->deleteEmployee();
                    break;
                case 'delete multiple employee':
                    $this->deleteMultipleEmployee();
                    break;
                case 'duplicate employee':
                    $this->duplicateEmployee();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertEmployee
    # Description: 
    # Inserts the employee.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function insertEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
        $suffix = htmlspecialchars($_POST['suffix'], ENT_QUOTES, 'UTF-8');

        $systemSettingDetails = $this->systemSettingModel->getSystemSetting(4);
        $fileAs = $systemSettingDetails['value'];
        $fileAs = str_replace('{last_name}', $lastName, $fileAs);
        $fileAs = str_replace('{first_name}', $firstName, $fileAs);
        $fileAs = str_replace('{middle_name}', $middleName, $fileAs);
        $fileAs = str_replace('{suffix}', $suffix, $fileAs);
        $fileAs = trim($fileAs);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $employeeID = $this->employeeModel->insertEmployee($fileAs, $userID);
        $this->employeeModel->insertEmployeePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'employeeID' => $this->securityModel->encryptData($employeeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteEmployee
    # Description: 
    # Delete the employee if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeeExist = $this->employeeModel->checkEmployeeExist($employeeID);
        $total = $checkEmployeeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteEmployee($employeeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleEmployee
    # Description: 
    # Delete the selected employees if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeIDs = $_POST['employee_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($employeeIDs as $employeeID){
            $this->employeeModel->deleteEmployee($employeeID);
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
    # Function: duplicateEmployee
    # Description: 
    # Duplicates the employee if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateEmployee() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeeExist = $this->employeeModel->checkEmployeeExist($employeeID);
        $total = $checkEmployeeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $employeeID = $this->employeeModel->duplicateEmployee($employeeID, $userID);

        echo json_encode(['success' => true, 'employeeID' =>  $this->securityModel->encryptData($employeeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmployeePersonalInformation
    # Description: 
    # Handles the retrieval of employee personal information details such as first name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEmployeePersonalInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
            $userID = $_SESSION['user_id'];
            $employeeID = $_POST['employee_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $employeeDetails = $this->employeeModel->getEmployeePersonalInformation($employeeID);

            $response = [
                'success' => true,
                'firstName' => $employeeDetails['first_name'],
                'middleName' => $employeeDetails['middle_name'],
                'lastName' => $employeeDetails['last_name'],
                'suffix' => $employeeDetails['suffix'],
                'nickname' => $employeeDetails['nickname'],
                'bio' => $employeeDetails['bio'],
                'civilStatusID' => $employeeDetails['civil_status_id'],
                'genderID' => $employeeDetails['gender_id'],
                'religionID' => $employeeDetails['religion_id'],
                'bloodTypeID' => $employeeDetails['blood_type_id'],
                'birthday' => $employeeDetails['birthday'],
                'birth_place' => $employeeDetails['birth_place'],
                'height' => $employeeDetails['height'],
                'weight' => $employeeDetails['weight']
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
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/user-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EmployeeController(new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new DepartmentModel(new DatabaseModel), new JobPositionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>