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
                case 'save contact information':
                    $this->saveContactInformation();
                    break;
                case 'tag contact information as primary':
                    $this->tagContactInformationAsPrimary();
                    break;
                case 'save contact address':
                    $this->saveContactAddress();
                    break;
                case 'tag contact address as primary':
                    $this->tagContactAddressAsPrimary();
                    break;
                case 'save contact identification':
                    $this->saveContactIdentification();
                    break;
                case 'tag contact identification as primary':
                    $this->tagContactIdentificationAsPrimary();
                    break;
                case 'save contact educational background':
                    $this->saveContactEducationalBackground();
                    break;
                case 'save contact family background':
                    $this->saveContactFamilyBackground();
                    break;
                case 'save contact emergency contact':
                    $this->saveContactEmergencyContact();
                    break;
                case 'save contact training':
                    $this->saveContactTraining();
                    break;
                case 'save contact skills':
                    $this->saveContactSkills();
                    break;
                case 'save contact talents':
                    $this->saveContactTalents();
                    break;
                case 'save contact hobby':
                    $this->saveContactHobby();
                    break;
                case 'get personal information details':
                    $this->getPersonalInformation();
                    break;
                case 'get employment information details':
                    $this->getEmploymentInformation();
                    break;
                case 'get contact information details':
                    $this->getContactInformation();
                    break;
                case 'get contact address details':
                    $this->getContactAddress();
                    break;
                case 'get contact identification details':
                    $this->getContactIdentification();
                    break;
                case 'get contact educational background details':
                    $this->getContactEducationalBackground();
                    break;
                case 'get contact family background details':
                    $this->getContactFamilyBackground();
                    break;
                case 'get contact emergency contact details':
                    $this->getContactEmergencyContact();
                    break;
                case 'get contact training details':
                    $this->getContactTraining();
                    break;
                case 'get contact skills details':
                    $this->getContactSkills();
                    break;
                case 'get contact talents details':
                    $this->getContactTalents();
                    break;
                case 'get contact hobby details':
                    $this->getContactHobby();
                    break;
                case 'delete employee':
                    $this->deleteEmployee();
                    break;
                case 'delete multiple employee':
                    $this->deleteMultipleEmployee();
                    break;
                case 'delete contact information':
                    $this->deleteContactInformation();
                    break;
                case 'delete contact address':
                    $this->deleteContactAddress();
                    break;
                case 'delete contact identification':
                    $this->deleteContactIdentification();
                    break;
                case 'delete contact educational background':
                    $this->deleteContactEducationalBackground();
                    break;
                case 'delete contact family background':
                    $this->deleteContactFamilyBackground();
                    break;
                case 'delete contact emergency contact':
                    $this->deleteContactEmergencyContact();
                    break;
                case 'delete contact training':
                    $this->deleteContactTraining();
                    break;
                case 'delete contact skills':
                    $this->deleteContactSkills();
                    break;
                case 'delete contact talents':
                    $this->deleteContactTalents();
                    break;
                case 'delete contact hobby':
                    $this->deleteContactHobby();
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
            $this->employeeModel->insertPersonalInformation($employeeID, $firstName, $middleName, $lastName, $suffix, $nickname, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

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
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmploymentInformationExist = $this->employeeModel->checkEmploymentInformationExist($employeeID);
        $total = $checkEmploymentInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateEmploymentInformation($employeeID, $badgeID, $companyID, $employeeTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $onboardDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertEmploymentInformation($employeeID, $badgeID, $companyID, $employeeTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $onboardDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactInformation
    # Description: 
    # Updates the existing contact information if it exists; otherwise, inserts a new contact information.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationID = isset($_POST['contact_information_id']) ? htmlspecialchars($_POST['contact_information_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $contactInformationTypeID = htmlspecialchars($_POST['contact_information_type_id'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['contact_information_email'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['contact_information_mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['contact_information_telephone'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationExist = $this->employeeModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactInformation($contactInformationID, $employeeID, $contactInformationTypeID, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactInformation($employeeID, $contactInformationTypeID, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactAddress
    # Description: 
    # Updates the existing contact address if it exists; otherwise, inserts a new contact address.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactAddress() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactAddressID = isset($_POST['contact_address_id']) ? htmlspecialchars($_POST['contact_address_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $addressTypeID = htmlspecialchars($_POST['address_type_id'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['contact_address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['contact_address_city_id'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactAddressExist = $this->employeeModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactAddress($contactAddressID, $employeeID, $addressTypeID, $address, $cityID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactAddress($employeeID, $addressTypeID, $address, $cityID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactIdentification
    # Description: 
    # Updates the existing contact identification if it exists; otherwise, inserts a new contact identification.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactIdentification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactIdentificationID = isset($_POST['contact_identification_id']) ? htmlspecialchars($_POST['contact_identification_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $idTypeID = htmlspecialchars($_POST['id_type_id'], ENT_QUOTES, 'UTF-8');
        $idNumber = htmlspecialchars($_POST['contact_id_number'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactIdentificationExist = $this->employeeModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactIdentification($contactIdentificationID, $employeeID, $idTypeID, $idNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactIdentification($employeeID, $idTypeID, $idNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactEducationalBackground
    # Description: 
    # Updates the existing contact educational background if it exists; otherwise, inserts a new contact educational background.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactEducationalBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactEducationalBackgroundID = isset($_POST['contact_educational_background_id']) ? htmlspecialchars($_POST['contact_educational_background_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $educationalStageID = htmlspecialchars($_POST['educational_stage_id'], ENT_QUOTES, 'UTF-8');
        $institutionName = htmlspecialchars($_POST['contact_institution_name'], ENT_QUOTES, 'UTF-8');
        $degreeEarned = htmlspecialchars($_POST['contact_degree_earned'], ENT_QUOTES, 'UTF-8');
        $fieldOfStudy = htmlspecialchars($_POST['contact_field_of_study'], ENT_QUOTES, 'UTF-8');
        $startDate = $this->systemModel->checkDate('empty', $_POST['contact_start_date_attended'], '', 'Y-m-d', '');
        $endDate = $this->systemModel->checkDate('empty', $_POST['contact_end_date_attended'], '', 'Y-m-d', '');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEducationalBackgroundExist = $this->employeeModel->checkContactEducationalBackgroundExist($contactEducationalBackgroundID);
        $total = $checkContactEducationalBackgroundExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactEducationalBackground($contactEducationalBackgroundID, $employeeID, $educationalStageID, $institutionName, $degreeEarned, $fieldOfStudy, $startDate, $endDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactEducationalBackground($employeeID, $educationalStageID, $institutionName, $degreeEarned, $fieldOfStudy, $startDate, $endDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactFamilyBackground
    # Description: 
    # Updates the existing contact family background if it exists; otherwise, inserts a new contact family background.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactFamilyBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactFamilyBackgroundID = isset($_POST['contact_family_background_id']) ? htmlspecialchars($_POST['contact_family_background_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $familyName = htmlspecialchars($_POST['family_name'], ENT_QUOTES, 'UTF-8');
        $relationID = htmlspecialchars($_POST['family_background_relation_id'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['family_background_email'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['family_background_mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['family_background_telephone'], ENT_QUOTES, 'UTF-8');
        $birthday = $this->systemModel->checkDate('empty', $_POST['family_background_birthday'], '', 'Y-m-d', '');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactFamilyBackgroundExist = $this->employeeModel->checkContactFamilyBackgroundExist($contactFamilyBackgroundID);
        $total = $checkContactFamilyBackgroundExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactFamilyBackground($contactFamilyBackgroundID, $employeeID, $familyName, $relationID, $birthday, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactFamilyBackground($employeeID, $familyName, $relationID, $birthday, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactEmergencyContact
    # Description: 
    # Updates the existing contact emergency contact if it exists; otherwise, inserts a new contact emergency contact.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactEmergencyContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactEmergencyContactID = isset($_POST['contact_emergency_contact_id']) ? htmlspecialchars($_POST['contact_emergency_contact_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $emergencyContactName = htmlspecialchars($_POST['emergency_contact_name'], ENT_QUOTES, 'UTF-8');
        $relationID = htmlspecialchars($_POST['emergency_contact_relation_id'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['emergency_contact_email'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['emergency_contact_mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['emergency_contact_telephone'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEmergencyContactExist = $this->employeeModel->checkContactEmergencyContactExist($contactEmergencyContactID);
        $total = $checkContactEmergencyContactExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactEmergencyContact($contactEmergencyContactID, $employeeID, $emergencyContactName, $relationID, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactEmergencyContact($employeeID, $emergencyContactName, $relationID, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactTraining
    # Description: 
    # Updates the existing contact training if it exists; otherwise, inserts a new contact training.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactTraining() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactTrainingID = isset($_POST['contact_training_id']) ? htmlspecialchars($_POST['contact_training_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $trainingName = htmlspecialchars($_POST['training_name'], ENT_QUOTES, 'UTF-8');
        $trainingDate = $this->systemModel->checkDate('empty', $_POST['training_date'], '', 'Y-m-d', '');
        $trainingLocation = htmlspecialchars($_POST['training_location'], ENT_QUOTES, 'UTF-8');
        $trainingProvider = htmlspecialchars($_POST['training_provider'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTrainingExist = $this->employeeModel->checkContactTrainingExist($contactTrainingID);
        $total = $checkContactTrainingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactTraining($contactTrainingID, $employeeID, $trainingName, $trainingDate, $trainingLocation, $trainingProvider, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactTraining($employeeID, $trainingName, $trainingDate, $trainingLocation, $trainingProvider, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactSkills
    # Description: 
    # Updates the existing contact skills if it exists; otherwise, inserts a new contact skills.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactSkills() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactSkillsID = isset($_POST['contact_skills_id']) ? htmlspecialchars($_POST['contact_skills_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $skillName = htmlspecialchars($_POST['skill_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactSkillsExist = $this->employeeModel->checkContactSkillsExist($contactSkillsID);
        $total = $checkContactSkillsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactSkills($contactSkillsID, $employeeID, $skillName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactSkills($employeeID, $skillName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactTalents
    # Description: 
    # Updates the existing contact talents if it exists; otherwise, inserts a new contact talents.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactTalents() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactTalentsID = isset($_POST['contact_talents_id']) ? htmlspecialchars($_POST['contact_talents_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $talentName = htmlspecialchars($_POST['talent_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTalentsExist = $this->employeeModel->checkContactTalentsExist($contactTalentsID);
        $total = $checkContactTalentsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactTalents($contactTalentsID, $employeeID, $talentName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactTalents($employeeID, $talentName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactHobby
    # Description: 
    # Updates the existing contact hobby if it exists; otherwise, inserts a new contact hobby.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactHobby() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactHobbyID = isset($_POST['contact_hobby_id']) ? htmlspecialchars($_POST['contact_hobby_id'], ENT_QUOTES, 'UTF-8') : null;
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
        $hobbyName = htmlspecialchars($_POST['hobby_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactHobbyExist = $this->employeeModel->checkContactHobbyExist($contactHobbyID);
        $total = $checkContactHobbyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->employeeModel->updateContactHobby($contactHobbyID, $employeeID, $hobbyName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->employeeModel->insertContactHobby($employeeID, $hobbyName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
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
    #
    # Function: deleteContactInformation
    # Description: 
    # Delete the contact information if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationID = htmlspecialchars($_POST['contact_information_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationExist = $this->employeeModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactInformation($contactInformationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactAddress
    # Description: 
    # Delete the contact address if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactAddress() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactAddressID = htmlspecialchars($_POST['contact_address_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactAddressExist = $this->employeeModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactAddress($contactAddressID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactIdentification
    # Description: 
    # Delete the contact identification if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactIdentification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactIdentificationID = htmlspecialchars($_POST['contact_identification_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactIdentificationExist = $this->employeeModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactIdentification($contactIdentificationID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactEducationalBackground
    # Description: 
    # Delete the contact educational background if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactEducationalBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactEducationalBackgroundID = htmlspecialchars($_POST['contact_educational_background_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEducationalBackgroundExist = $this->employeeModel->checkContactEducationalBackgroundExist($contactEducationalBackgroundID);
        $total = $checkContactEducationalBackgroundExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactEducationalBackground($contactEducationalBackgroundID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactFamilyBackground
    # Description: 
    # Delete the contact family background if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactFamilyBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactFamilyBackgroundID = htmlspecialchars($_POST['contact_family_background_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactFamilyBackgroundExist = $this->employeeModel->checkContactFamilyBackgroundExist($contactFamilyBackgroundID);
        $total = $checkContactFamilyBackgroundExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactFamilyBackground($contactFamilyBackgroundID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactEmergencyContact
    # Description: 
    # Delete the contact emergency contact if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactEmergencyContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactEmergencyContactID = htmlspecialchars($_POST['contact_emergency_contact_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEmergencyContactExist = $this->employeeModel->checkContactEmergencyContactExist($contactEmergencyContactID);
        $total = $checkContactEmergencyContactExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactEmergencyContact($contactEmergencyContactID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteContactTraining
    # Description: 
    # Delete the contact training if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactTraining() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactTrainingID = htmlspecialchars($_POST['contact_training_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTrainingExist = $this->employeeModel->checkContactTrainingExist($contactTrainingID);
        $total = $checkContactTrainingExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactTraining($contactTrainingID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteContactSkills
    # Description: 
    # Delete the contact skills if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactSkills() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactSkillsID = htmlspecialchars($_POST['contact_skills_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactSkillsExist = $this->employeeModel->checkContactSkillsExist($contactSkillsID);
        $total = $checkContactSkillsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactSkills($contactSkillsID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteContactTalents
    # Description: 
    # Delete the contact talents if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactTalents() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactTalentsID = htmlspecialchars($_POST['contact_talents_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTalentsExist = $this->employeeModel->checkContactTalentsExist($contactTalentsID);
        $total = $checkContactTalentsExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactTalents($contactTalentsID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: deleteContactHobby
    # Description: 
    # Delete the contact hobby if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactHobby() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactHobbyID = htmlspecialchars($_POST['contact_hobby_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactHobbyExist = $this->employeeModel->checkContactHobbyExist($contactHobbyID);
        $total = $checkContactHobbyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->deleteContactHobby($contactHobbyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Custom methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagContactInformationAsPrimary
    # Description: 
    # Updates the contact information as primary.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagContactInformationAsPrimary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationID = htmlspecialchars($_POST['contact_information_id'], ENT_QUOTES, 'UTF-8');
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationExist = $this->employeeModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->updateContactInformationStatus($contactInformationID, $employeeID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagContactAddressAsPrimary
    # Description: 
    # Updates the contact address as primary.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagContactAddressAsPrimary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactAddressID = htmlspecialchars($_POST['contact_address_id'], ENT_QUOTES, 'UTF-8');
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactAddressExist = $this->employeeModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->updateContactAddressStatus($contactAddressID, $employeeID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: tagContactIdentificationAsPrimary
    # Description: 
    # Updates the contact identification as primary.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function tagContactIdentificationAsPrimary() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactIdentificationID = htmlspecialchars($_POST['contact_identification_id'], ENT_QUOTES, 'UTF-8');
        $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactIdentificationExist = $this->employeeModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->employeeModel->updateContactIdentificationStatus($contactIdentificationID, $employeeID, $userID);
            
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
                'bio' => $employeeDetails['bio'] ?? 'No personal summary.',
                'civilStatusID' => $civilStatusID,
                'genderID' => $genderID,
                'religionID' => $religionID,
                'bloodTypeID' => $bloodTypeID,
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
                'onboardDate' =>  $this->systemModel->checkDate('empty', $employeeDetails['onboard_date'] ?? null, '', 'm/d/Y', ''),
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactInformation
    # Description: 
    # Handles the retrieval of contact information details such as email, telephone, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactInformation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_information_id']) && !empty($_POST['contact_information_id'])) {
            $userID = $_SESSION['user_id'];
            $contactInformationID = $_POST['contact_information_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactInformationDetails = $this->employeeModel->getContactInformation($contactInformationID);

            $response = [
                'success' => true,
                'contactInformationTypeID' => $contactInformationDetails['contact_information_type_id'] ?? null,
                'mobile' => $contactInformationDetails['mobile'] ?? null,
                'telephone' => $contactInformationDetails['telephone'] ?? null,
                'email' => $contactInformationDetails['email'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactAddress
    # Description: 
    # Handles the retrieval of contact address details such as address type, address, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactAddress() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_address_id']) && !empty($_POST['contact_address_id'])) {
            $userID = $_SESSION['user_id'];
            $contactAddressID = $_POST['contact_address_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactAddressDetails = $this->employeeModel->getContactAddress($contactAddressID);

            $response = [
                'success' => true,
                'addressTypeID' => $contactAddressDetails['address_type_id'] ?? null,
                'address' => $contactAddressDetails['address'] ?? null,
                'cityID' => $contactAddressDetails['city_id'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactIdentification
    # Description: 
    # Handles the retrieval of contact identification details such as ID type, ID number, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactIdentification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_identification_id']) && !empty($_POST['contact_identification_id'])) {
            $userID = $_SESSION['user_id'];
            $contactIdentificationID = $_POST['contact_identification_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactIdentificationDetails = $this->employeeModel->getContactIdentification($contactIdentificationID);

            $response = [
                'success' => true,
                'idTypeID' => $contactIdentificationDetails['id_type_id'] ?? null,
                'idNumber' => $contactIdentificationDetails['id_number'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactEducationalBackground
    # Description: 
    # Handles the retrieval of contact educational background details such as institution name, field of study, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactEducationalBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_educational_background_id']) && !empty($_POST['contact_educational_background_id'])) {
            $userID = $_SESSION['user_id'];
            $contactEducationalBackgroundID = htmlspecialchars($_POST['contact_educational_background_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactEducationalBackgroundDetails = $this->employeeModel->getContactEducationalBackground($contactEducationalBackgroundID);

            $response = [
                'success' => true,
                'educationalStageID' => $contactEducationalBackgroundDetails['educational_stage_id'] ?? null,
                'institutionName' => $contactEducationalBackgroundDetails['institution_name'] ?? null,
                'degreeEarned' => $contactEducationalBackgroundDetails['degree_earned'] ?? null,
                'fieldOfStudy' => $contactEducationalBackgroundDetails['field_of_study'] ?? null,
                'startDate' =>  $this->systemModel->checkDate('empty', $contactEducationalBackgroundDetails['start_date'], '', 'm/d/Y', ''),
                'endDate' =>  $this->systemModel->checkDate('empty', $contactEducationalBackgroundDetails['end_date'], '', 'm/d/Y', '')
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactFamilyBackground
    # Description: 
    # Handles the retrieval of contact family background details such as institution name, field of study, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactFamilyBackground() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_family_background_id']) && !empty($_POST['contact_family_background_id'])) {
            $userID = $_SESSION['user_id'];
            $contactFamilyBackgroundID = htmlspecialchars($_POST['contact_family_background_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactFamilyBackgroundDetails = $this->employeeModel->getContactFamilyBackground($contactFamilyBackgroundID);

            $response = [
                'success' => true,
                'familyName' => $contactFamilyBackgroundDetails['family_name'] ?? null,
                'relationID' => $contactFamilyBackgroundDetails['relation_id'] ?? null,
                'birthday' =>  $this->systemModel->checkDate('empty', $contactFamilyBackgroundDetails['birthday'], '', 'm/d/Y', ''),
                'mobile' => $contactFamilyBackgroundDetails['mobile'] ?? null,
                'telephone' => $contactFamilyBackgroundDetails['telephone'] ?? null,
                'email' => $contactFamilyBackgroundDetails['email'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactEmergencyContact
    # Description: 
    # Handles the retrieval of contact emergency contact details such as institution name, field of study, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactEmergencyContact() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_emergency_contact_id']) && !empty($_POST['contact_emergency_contact_id'])) {
            $userID = $_SESSION['user_id'];
            $contactEmergencyContactID = htmlspecialchars($_POST['contact_emergency_contact_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactEmergencyContactDetails = $this->employeeModel->getContactEmergencyContact($contactEmergencyContactID);

            $response = [
                'success' => true,
                'emergencyContactName' => $contactEmergencyContactDetails['emergency_contact_name'] ?? null,
                'relationID' => $contactEmergencyContactDetails['relation_id'] ?? null,
                'mobile' => $contactEmergencyContactDetails['mobile'] ?? null,
                'telephone' => $contactEmergencyContactDetails['telephone'] ?? null,
                'email' => $contactEmergencyContactDetails['email'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactTraining
    # Description: 
    # Handles the retrieval of contact training details such as traning name, traning date, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactTraining() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_training_id']) && !empty($_POST['contact_training_id'])) {
            $userID = $_SESSION['user_id'];
            $contactTrainingID = htmlspecialchars($_POST['contact_training_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactTrainingDetails = $this->employeeModel->getContactTraining($contactTrainingID);

            $response = [
                'success' => true,
                'trainingName' => $contactTrainingDetails['training_name'] ?? null,
                'trainingDate' =>  $this->systemModel->checkDate('empty', $contactTrainingDetails['training_date'], '', 'm/d/Y', ''),
                'trainingLocation' => $contactTrainingDetails['training_location'] ?? null,
                'trainingProvider' => $contactTrainingDetails['training_provider'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactSkills
    # Description: 
    # Handles the retrieval of contact skills details such as skill name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactSkills() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_skills_id']) && !empty($_POST['contact_skills_id'])) {
            $userID = $_SESSION['user_id'];
            $contactSkillsID = htmlspecialchars($_POST['contact_skills_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactSkillsDetails = $this->employeeModel->getContactSkills($contactSkillsID);

            $response = [
                'success' => true,
                'skillName' => $contactSkillsDetails['skill_name'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactTalents
    # Description: 
    # Handles the retrieval of contact talents details such as talent name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactTalents() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_talents_id']) && !empty($_POST['contact_talents_id'])) {
            $userID = $_SESSION['user_id'];
            $contactTalentsID = htmlspecialchars($_POST['contact_talents_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactTalentsDetails = $this->employeeModel->getContactTalents($contactTalentsID);

            $response = [
                'success' => true,
                'talentName' => $contactTalentsDetails['talent_name'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactHobby
    # Description: 
    # Handles the retrieval of contact hobby details such as talent name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactHobby() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_hobby_id']) && !empty($_POST['contact_hobby_id'])) {
            $userID = $_SESSION['user_id'];
            $contactHobbyID = htmlspecialchars($_POST['contact_hobby_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactHobbyDetails = $this->employeeModel->getContactHobby($contactHobbyID);

            $response = [
                'success' => true,
                'hobbyName' => $contactHobbyDetails['hobby_name'] ?? null
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