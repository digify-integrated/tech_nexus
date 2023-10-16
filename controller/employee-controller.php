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
    private $companyModel;
    private $departmentModel;
    private $jobPositionModel;
    private $employeeTypeModel;
    private $jobLevelModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $genderModel;
    private $civilStatusModel;
    private $religionModel;
    private $bloodTypeModel;
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
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param DepartmentModel $departmentModel     The DepartmentModel instance for department related operations.
    # - @param JobPositionModel $jobPositionModel     The JobPositionModel instance for job position related operations.
    # - @param EmployeeTypeModel $employeeTypeModel     The EmployeeTypeModel instance for employee type related operations.
    # - @param JobLevelModel $jobLevelModel     The JobLevelModel instance for job level related operations.
    # - @param BranchModel $branchModel     The BranchModel instance for branch related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param GenderModel $genderModel     The GenderModel instance for gender related operations.
    # - @param CivilStatusModel $civilStatusModel     The CivilStatusModel instance for civil status related operations.
    # - @param ReligionModel $religionModel     The ReligionModel instance for religion related operations.
    # - @param BloodTypeModel $religionModel     The BloodTypeModel instance for blood type related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(EmployeeModel $employeeModel, UserModel $userModel, CompanyModel $companyModel, DepartmentModel $departmentModel, JobPositionModel $jobPositionModel, EmployeeTypeModel $employeeTypeModel, JobLevelModel $jobLevelModel, BranchModel $branchModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, GenderModel $genderModel, CivilStatusModel $civilStatusModel, ReligionModel $religionModel, BloodTypeModel $bloodTypeModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->employeeModel = $employeeModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
        $this->departmentModel = $departmentModel;
        $this->jobPositionModel = $jobPositionModel;
        $this->employeeTypeModel = $employeeTypeModel;
        $this->jobLevelModel = $jobLevelModel;
        $this->branchModel = $branchModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->genderModel = $genderModel;
        $this->civilStatusModel = $civilStatusModel;
        $this->religionModel = $religionModel;
        $this->bloodTypeModel = $bloodTypeModel;
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
                case 'save personal information':
                    $this->savePersonalInformation();
                    break;
                case 'save employment information':
                    $this->saveEmploymentInformation();
                    break;
                case 'get personal information details':
                    $this->getPersonalInformation();
                    break;
                case 'get employment information details':
                    $this->getEmploymentInformation();
                    break;
                case 'delete employee':
                    $this->deleteEmployee();
                    break;
                case 'delete multiple employee':
                    $this->deleteMultipleEmployee();
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
    # Function: savePersonalInformation
    # Description: 
    # Updates the existing personal information if it exists; otherwise, inserts a new personal information.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePersonalInformation() {
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
    
        $checkPersonalInformationExist = $this->employeeModel->checkPersonalInformationExist($employeeID);
        $total = $checkPersonalInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updatePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $nickname, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->updatePersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $nickname, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveEmploymentInformation
    # Description: 
    # Updates the existing employment information if it exists; otherwise, inserts a new employment information.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveEmploymentInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $employeeID = isset($_POST['employee_id']) ? htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8') : null;
        $badgeID = htmlspecialchars($_POST['badge_id'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $jobLevelID = htmlspecialchars($_POST['job_level_id'], ENT_QUOTES, 'UTF-8');
        $employeeTypeID = htmlspecialchars($_POST['employee_type_id'], ENT_QUOTES, 'UTF-8');
        $branchID = htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8');
        $onboardDate = $this->systemModel->checkDate('empty', $_POST['onboard_date'], '', 'Y-m-d', '');
        $permanencyDate = $this->systemModel->checkDate('empty', $_POST['permanency_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmploymentInformationExist = $this->employeeModel->checkEmploymentInformationExist($employeeID);
        $total = $checkEmploymentInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateEmploymentInformation($employeeID, $badgeID, $companyID, $employeeTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $permanencyDate, $onboardDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertEmploymentInformation($employeeID, $badgeID, $companyID, $employeeTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $permanencyDate, $onboardDate, $userID);

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
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $branchID = htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $employeeID = $this->employeeModel->insertEmployee($userID);
        $this->employeeModel->insertPartialPersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $userID);
        $this->employeeModel->insertPartialEmploymentInformation($employeeID, $companyID, $departmentID, $jobPositionID, $branchID, $userID);

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

        $employeeDetails = $this->employeeModel->getPersonalInformation($employeeID);
        $employeeImage = $employeeDetails['contact_image'] !== null ? '.' . $employeeDetails['contact_image'] : null;

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
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPersonalInformation
    # Description: 
    # Handles the retrieval of personal information details such as first name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPersonalInformation() {
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
    
            $employeeDetails = $this->employeeModel->getPersonalInformation($employeeID);
            $employeeImage = $this->systemModel->checkImage($employeeDetails['contact_image'], 'profile');
            $firstName = $employeeDetails['first_name'];
            $middleName = $employeeDetails['middle_name'];
            $lastName = $employeeDetails['last_name'];
            $suffix = $employeeDetails['suffix'];
            $genderID = $employeeDetails['gender_id'];
            $civilStatusID = $employeeDetails['civil_status_id'];
            $religionID = $employeeDetails['religion_id'];
            $bloodTypeID = $employeeDetails['blood_type_id'];

            $genderName = $this->genderModel->getGender($genderID)['gender_name'] ?? null;
            $civilStatusName = $this->civilStatusModel->getCivilStatus($civilStatusID)['civil_status_name'] ?? null;
            $religionName = $this->religionModel->getReligion($religionID)['religion_name'] ?? null;
            $bloodTypeName = $this->bloodTypeModel->getBloodType($bloodTypeID)['blood_type_name'] ?? null;

            $employeeName = $this->systemSettingModel->getSystemSetting(4)['value'];
            $employeeName = str_replace('{last_name}', $lastName, $employeeName);
            $employeeName = str_replace('{first_name}', $firstName, $employeeName);
            $employeeName = str_replace('{suffix}', $suffix, $employeeName);
            $employeeName = str_replace('{middle_name}', $middleName, $employeeName);

            $response = [
                'success' => true,
                'employeeImage' => $employeeImage,
                'employeeName' => $employeeName,
                'firstName' => $firstName,
                'middleName' => $middleName,
                'lastName' => $lastName,
                'suffix' => $suffix,
                'nickname' => $employeeDetails['nickname'],
                'bio' => $employeeDetails['bio'] ?? 'No employee bio.',
                'civilStatusID' => $civilStatusID,
                'civilStatusName' => $civilStatusName,
                'genderID' => $genderID,
                'genderName' => $genderName,
                'religionID' => $religionID,
                'religionName' => $religionName,
                'bloodTypeID' => $bloodTypeID,
                'bloodTypeName' => $bloodTypeName,
                'birthday' =>  $this->systemModel->checkDate('empty', $employeeDetails['birthday'], '', 'm/d/Y', ''),
                'birthPlace' => $employeeDetails['birth_place'],
                'height' => $employeeDetails['height'] ?? 0,
                'weight' => $employeeDetails['weight'] ?? 0
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getEmploymentInformation
    # Description: 
    # Handles the retrieval of employment information details such as badge ID, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getEmploymentInformation() {
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
    
            $employeeDetails = $this->employeeModel->getEmploymentInformation($employeeID);
            $departmentID = $employeeDetails['department_id'] ?? null;
            $companyID = $employeeDetails['company_id'] ?? null;
            $jobPositionID = $employeeDetails['job_position_id'] ?? null;
            $employeeTypeID = $employeeDetails['employee_type_id'] ?? null;
            $jobLevelID = $employeeDetails['job_level_id'] ?? null;
            $branchID = $employeeDetails['branch_id'] ?? null;
            $employmentStatus = $employeeDetails['employment_status'] ?? null;
            $jobPositionName = $this->jobPositionModel->getJobPosition($jobPositionID)['job_position_name'] ?? null;
            $companyName = $this->companyModel->getCompany($companyID)['company_name'] ?? null;
            $departmentName = $this->departmentModel->getDepartment($departmentID)['department_name'] ?? null;
            $employeeTypeName = $this->employeeTypeModel->getEmployeeType($employeeTypeID)['employee_type_name'] ?? null;
            $jobLevelName = $this->jobLevelModel->getJobLevel($jobLevelID)['rank'] ?? null;
            $branchName = $this->branchModel->getBranch($branchID)['branch_name'] ?? null;

            $isActiveBadge = $employmentStatus ? '<span class="badge bg-light-success">Active</span>' : '<span class="badge bg-light-danger">Inactive</span>';

            $response = [
                'success' => true,
                'badgeID' => $employeeDetails['badge_id'] ?? null,
                'employeeTypeID' => $employeeTypeID,
                'employeeTypeName' => $employeeTypeName,
                'companyID' => $companyID,
                'companyName' => $companyName,
                'departmentID' => $departmentID,
                'departmentName' => $departmentName,
                'jobPositionID' => $jobPositionID,
                'jobPositionName' => $jobPositionName,
                'jobLevelID' => $jobLevelID,
                'jobLevelName' => $jobLevelName,
                'branchID' => $branchID,
                'branchName' => $branchName,
                'isActiveBadge' => $isActiveBadge,
                'permanencyDate' =>  $this->systemModel->checkDate('empty', $employeeDetails['permanency_date'] ?? null, '', 'm/d/Y', ''),
                'onboardDate' =>  $this->systemModel->checkDate('empty', $employeeDetails['onboard_date'] ?? null, '', 'm/d/Y', ''),
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
require_once '../model/user-model.php';
require_once '../model/company-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/employee-type-model.php';
require_once '../model/job-level-model.php';
require_once '../model/branch-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/gender-model.php';
require_once '../model/civil-status-model.php';
require_once '../model/religion-model.php';
require_once '../model/blood-type-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new EmployeeController(new EmployeeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new DepartmentModel(new DatabaseModel), new JobPositionModel(new DatabaseModel), new EmployeeTypeModel(new DatabaseModel), new JobLevelModel(new DatabaseModel), new BranchModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new GenderModel(new DatabaseModel), new CivilStatusModel(new DatabaseModel), new ReligionModel(new DatabaseModel), new BloodTypeModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>