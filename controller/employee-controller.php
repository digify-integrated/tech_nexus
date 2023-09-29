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
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $systemModel;
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
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeModel $employeeModel, UserModel $userModel, DepartmentModel $departmentModel, JobPositionModel $jobPositionModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->departmentModel = $departmentModel;
        $this->jobPositionModel = $jobPositionModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->systemModel = $systemModel;
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
                case 'save employee personal information':
                    $this->saveEmployeePersonalInformation();
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
                case 'change employee image':
                    $this->updateEmployeeImage();
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
    # Function: saveEmployeePersonalInformation
    # Description: 
    # Updates the existing employee personal information if it exists; otherwise, inserts a new employee personal information.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveEmployeePersonalInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeID = isset($_POST['employee_id']) ? htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8') : null;
        $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
        $suffix = htmlspecialchars($_POST['suffix'], ENT_QUOTES, 'UTF-8');
        $nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
        $bio = htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8');
        $civilStatus = htmlspecialchars($_POST['civil_status'], ENT_QUOTES, 'UTF-8');
        $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
        $religion = htmlspecialchars($_POST['religion'], ENT_QUOTES, 'UTF-8');
        $bloodType = htmlspecialchars($_POST['blood_type'], ENT_QUOTES, 'UTF-8');
        $birthday = $this->systemModel->checkDate('empty', $_POST['birthday'], '', 'Y-m-d', '');
        $birthPlace = htmlspecialchars($_POST['birth_place'], ENT_QUOTES, 'UTF-8');
        $height = htmlspecialchars($_POST['height'], ENT_QUOTES, 'UTF-8');
        $weight = htmlspecialchars($_POST['weight'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmployeePersonalInformationExist = $this->employeeModel->checkEmployeePersonalInformationExist($employeeID);
        $total = $checkEmployeePersonalInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateEmployeePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $nickname, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertEmployeePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $nickname, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
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
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $employeeID = $this->employeeModel->insertEmployee($userID);
        $this->employeeModel->insertPartialEmployeePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'employeeID' => $this->securityModel->encryptData($employeeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateEmployeeImage
    # Description: 
    # Handles the update of the employee image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateEmployeeImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $employeeImageFileName = $_FILES['employee_image']['name'];
        $employeeImageFileSize = $_FILES['employee_image']['size'];
        $employeeImageFileError = $_FILES['employee_image']['error'];
        $employeeImageTempName = $_FILES['employee_image']['tmp_name'];
        $employeeImageFileExtension = explode('.', $employeeImageFileName);
        $employeeImageActualFileExtension = strtolower(end($employeeImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(4);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(4);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($employeeImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($employeeImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the employee image.']);
            exit;
        }
        
        if($employeeImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($employeeImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $employeeImageActualFileExtension;

        $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'employee/employee_image/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'employee/employee_image/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $employeeDetails = $this->employeeModel->getEmployeePersonalInformation($employeeID);
        $employeeImage = $employeeDetails['employee_image'] !== null ? '.' . $employeeDetails['employee_image'] : null;

        if(file_exists($employeeImage)){
            if (!unlink($employeeImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($employeeImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->employeeModel->updateEmployeeImage($employeeID, $filePath, $userID);

        echo json_encode(['success' => true]);
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
            $employeeImage = $this->systemModel->checkImage($employeeDetails['employee_image'], 'profile');

            $response = [
                'success' => true,
                'employeeImage' => $employeeImage,
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
                'birthday' =>  $this->systemModel->checkDate('empty', $employeeDetails['birthday'], '', 'm/d/Y', ''),
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
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EmployeeController(new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new DepartmentModel(new DatabaseModel), new JobPositionModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>