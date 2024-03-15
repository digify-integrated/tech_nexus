<?php
session_start();

# -------------------------------------------------------------
#
# Function: CustomerController
# Description: 
# The CustomerController class handles customer related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CustomerController {
    private $customerModel;
    private $userModel;
    private $systemSettingModel;
    private $companyModel;
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
    # The constructor initializes the object with the provided CustomerModel, UserModel and SecurityModel instances.
    # These instances are used for customer related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CustomerModel $customerModel     The CustomerModel instance for customer related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemSettingModel $systemSettingModel     The SystemSettingModel instance for system setting related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
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
    public function __construct(CustomerModel $customerModel, UserModel $userModel, CompanyModel $companyModel, SystemSettingModel $systemSettingModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, GenderModel $genderModel, CivilStatusModel $civilStatusModel, ReligionModel $religionModel, BloodTypeModel $bloodTypeModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->customerModel = $customerModel;
        $this->userModel = $userModel;
        $this->systemSettingModel = $systemSettingModel;
        $this->companyModel = $companyModel;
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
                case 'add customer':
                    $this->insertCustomer();
                    break;
                case 'save personal information':
                    $this->savePersonalInformation();
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
                case 'save contact family background':
                    $this->saveContactFamilyBackground();
                    break;
                case 'get personal information details':
                    $this->getPersonalInformation();
                    break;
                case 'get contact information details':
                    $this->getContactInformation();
                    break;
                case 'get contact address details':
                    $this->getContactAddress();
                    break;
                case 'get comaker details':
                    $this->getComakerDetails();
                    break;
                case 'get contact identification details':
                    $this->getContactIdentification();
                    break;
                case 'get contact family background details':
                    $this->getContactFamilyBackground();
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
                case 'delete contact family background':
                    $this->deleteContactFamilyBackground();
                    break;
                case 'delete contact comaker':
                    $this->deleteContactComaker();
                    break;
                case 'change customer image':
                    $this->updateCustomerImage();
                    break;
                case 'update contact status to active':
                    $this->updateCustomerStatusToActive();
                    break;
                case 'update contact status to for updating':
                    $this->updateCustomerStatusToForUpdating();
                    break;
                case 'search customer':
                    $this->searchCustomer();
                    break;
                case 'search customer comaker':
                    $this->searchCustomerComaker();
                    break;
                case 'assign co-maker':
                    $this->assignComaker();
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => 'Invalid transaction.']);
                    break;
            }
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Search methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: searchCustomer
    # Description: 
    # Check the customer if exist and number of results.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function searchCustomer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerSearch = $this->customerModel->checkCustomerSearch($firstName, $middleName, $lastName);
        $resultCount = $checkCustomerSearch['total'] ?? 0;
    
        echo json_encode(['success' => true, 'resultCount' => $resultCount]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: searchCustomerComaker
    # Description: 
    # Check the customer comaker if exist and number of results.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function searchCustomerComaker() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $firstName = htmlspecialchars($_POST['comaker_first_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['comaker_last_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerComakerSearch = $this->customerModel->checkCustomerComakerSearch($customerID, $firstName, $lastName);
        $resultCount = $checkCustomerComakerSearch['total'] ?? 0;
    
        echo json_encode(['success' => true, 'resultCount' => $resultCount]);
        exit;
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
        $customerID = isset($_POST['customer_id']) ? htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8') : null;
        $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
        $suffix = htmlspecialchars($_POST['suffix'], ENT_QUOTES, 'UTF-8');
        $corporateName = htmlspecialchars($_POST['corporate_name'], ENT_QUOTES, 'UTF-8');
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

        $fileAs = $this->systemSettingModel->getSystemSetting(4)['value'];
        $fileAs = str_replace('{first_name}', $firstName, $fileAs);
        $fileAs = str_replace('{middle_name}', $middleName, $fileAs);
        $fileAs = str_replace('{last_name}', $lastName, $fileAs);
        $fileAs = str_replace('{suffix}', $suffix, $fileAs);
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPersonalInformationExist = $this->customerModel->checkPersonalInformationExist($customerID);
        $total = $checkPersonalInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updatePersonalInformation($customerID, $fileAs, $firstName, $middleName, $lastName, $suffix, $nickname, $corporateName, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertPersonalInformation($customerID, $fileAs, $firstName, $middleName, $lastName, $suffix, $nickname, $corporateName, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

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
        $customerID = isset($_POST['customer_id']) ? htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8') : null;
        $badgeID = htmlspecialchars($_POST['badge_id'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');
        $jobLevelID = htmlspecialchars($_POST['job_level_id'], ENT_QUOTES, 'UTF-8');
        $customerTypeID = htmlspecialchars($_POST['customer_type_id'], ENT_QUOTES, 'UTF-8');
        $branchID = htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8');
        $managerID = htmlspecialchars($_POST['manager_id'], ENT_QUOTES, 'UTF-8');
        $workScheduleID = htmlspecialchars($_POST['work_schedule_id'], ENT_QUOTES, 'UTF-8');
        $biometricsID = htmlspecialchars($_POST['biometrics_id'], ENT_QUOTES, 'UTF-8');
        $kioskPinCode = $this->securityModel->encryptData($_POST['kiosk_pin_code']);
        $onboardDate = $this->systemModel->checkDate('empty', $_POST['onboard_date'], '', 'Y-m-d', '');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkEmploymentInformationExist = $this->customerModel->checkEmploymentInformationExist($customerID);
        $total = $checkEmploymentInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateEmploymentInformation($customerID, $badgeID, $companyID, $customerTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $managerID, $workScheduleID, $kioskPinCode, $biometricsID, $onboardDate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertEmploymentInformation($customerID, $badgeID, $companyID, $customerTypeID, $departmentID, $jobPositionID, $jobLevelID, $branchID, $managerID, $workScheduleID, $kioskPinCode, $biometricsID, $onboardDate, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $contactInformationTypeID = htmlspecialchars($_POST['contact_information_type_id'], ENT_QUOTES, 'UTF-8');
        $facebook = htmlspecialchars($_POST['contact_information_facebook'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['contact_information_email'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['contact_information_mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['contact_information_telephone'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationExist = $this->customerModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactInformation($contactInformationID, $customerID, $contactInformationTypeID, $mobile, $telephone, $email, $facebook, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactInformation($customerID, $contactInformationTypeID, $mobile, $telephone, $email, $facebook, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $addressTypeID = htmlspecialchars($_POST['address_type_id'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['contact_address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactAddressExist = $this->customerModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactAddress($contactAddressID, $customerID, $addressTypeID, $address, $cityID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactAddress($customerID, $addressTypeID, $address, $cityID, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $idTypeID = htmlspecialchars($_POST['id_type_id'], ENT_QUOTES, 'UTF-8');
        $idNumber = htmlspecialchars($_POST['id_number'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $idImageFileName = $_FILES['id_image']['name'];
        $idImageFileSize = $_FILES['id_image']['size'];
        $idImageFileError = $_FILES['id_image']['error'];
        $idImageTempName = $_FILES['id_image']['tmp_name'];
        $idImageFileExtension = explode('.', $idImageFileName);
        $idImageActualFileExtension = strtolower(end($idImageFileExtension));
    
        $checkContactIdentificationExist = $this->customerModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactIdentification($contactIdentificationID, $customerID, $idTypeID, $idNumber, $userID);

            if(!empty($idImageFileName)){
                $contactIdentificationDetails = $this->customerModel->getContactIdentification($contactIdentificationID);
                $idImage = !empty($contactIdentificationDetails['id_image']) ? '.' . $contactIdentificationDetails['id_image'] : null;
            
                if(file_exists($idImage)){
                    if (!unlink($idImage)) {
                        echo json_encode(['success' => false, 'message' => 'Transmittal image cannot be deleted due to an error.']);
                        exit;
                    }
                }

                $uploadSetting = $this->uploadSettingModel->getUploadSetting(10);
                $maxFileSize = $uploadSetting['max_file_size'];
        
                $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(10);
                $allowedFileExtensions = [];
        
                foreach ($uploadSettingFileExtension as $row) {
                    $fileExtensionID = $row['file_extension_id'];
                    $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                    $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
                }
        
                if (!in_array($idImageActualFileExtension, $allowedFileExtensions)) {
                    $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                    echo json_encode($response);
                    exit;
                }
                    
                if(empty($idImageTempName)){
                    echo json_encode(['success' => false, 'message' => 'Please choose the document file.']);
                    exit;
                }
                    
                if($idImageFileError){
                    echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                    exit;
                }
                    
                if($idImageFileSize > ($maxFileSize * 1048576)){
                    echo json_encode(['success' => false, 'message' => 'The customer ID exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                    exit;
                }
        
                $fileName = $this->securityModel->generateFileName();
                $fileNew = $fileName . '.' . $idImageActualFileExtension;
        
                $directory = DEFAULT_CUSTOMER_RELATIVE_PATH_FILE . $customerID .'/customer_id/';
                $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_CUSTOMER_FULL_PATH_FILE . $customerID . '/customer_id/' . $fileNew;
                $filePath = $directory . $fileNew;
        
                $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);
        
                if(!$directoryChecker){
                    echo json_encode(['success' => false, 'message' => $directoryChecker]);
                    exit;
                }
        
                if(!move_uploaded_file($idImageTempName, $fileDestination)){
                    echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                    exit;
                }
        
                $this->customerModel->updateContactIdentificationImage($contactIdentificationID, $filePath, $userID);
            }

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $contactIdentificationID = $this->customerModel->insertContactIdentification($customerID, $idTypeID, $idNumber, $userID);

            if(empty($idImageFileName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the ID image.']);
                exit;
            }

            $uploadSetting = $this->uploadSettingModel->getUploadSetting(10);
            $maxFileSize = $uploadSetting['max_file_size'];
    
            $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(10);
            $allowedFileExtensions = [];
    
            foreach ($uploadSettingFileExtension as $row) {
                $fileExtensionID = $row['file_extension_id'];
                $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
                $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
            }
    
            if (!in_array($idImageActualFileExtension, $allowedFileExtensions)) {
                $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
                echo json_encode($response);
                exit;
            }
                
            if(empty($idImageTempName)){
                echo json_encode(['success' => false, 'message' => 'Please choose the document file.']);
                exit;
            }
                
            if($idImageFileError){
                echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
                exit;
            }
                
            if($idImageFileSize > ($maxFileSize * 1048576)){
                echo json_encode(['success' => false, 'message' => 'The customer ID exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
                exit;
            }
    
            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $idImageActualFileExtension;
    
            $directory = DEFAULT_CUSTOMER_RELATIVE_PATH_FILE . $customerID .'/customer_id/';
            $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_CUSTOMER_FULL_PATH_FILE . $customerID . '/customer_id/' . $fileNew;
            $filePath = $directory . $fileNew;
    
            $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);
    
            if(!$directoryChecker){
                echo json_encode(['success' => false, 'message' => $directoryChecker]);
                exit;
            }
    
            if(!move_uploaded_file($idImageTempName, $fileDestination)){
                echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
                exit;
            }
    
            $this->customerModel->updateContactIdentificationImage($contactIdentificationID, $filePath, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $educationalStageID = htmlspecialchars($_POST['educational_stage_id'], ENT_QUOTES, 'UTF-8');
        $institutionName = htmlspecialchars($_POST['institution_name'], ENT_QUOTES, 'UTF-8');
        $degreeEarned = htmlspecialchars($_POST['degree_earned'], ENT_QUOTES, 'UTF-8');
        $fieldOfStudy = htmlspecialchars($_POST['field_of_study'], ENT_QUOTES, 'UTF-8');
        $startMonth = htmlspecialchars($_POST['educational_background_start_month'], ENT_QUOTES, 'UTF-8');
        $startYear = htmlspecialchars($_POST['educational_background_start_year'], ENT_QUOTES, 'UTF-8');
        $endMonth = htmlspecialchars($_POST['educational_background_end_month'], ENT_QUOTES, 'UTF-8');
        $endYear = htmlspecialchars($_POST['educational_background_end_year'], ENT_QUOTES, 'UTF-8');
        $courseHighlights = htmlspecialchars($_POST['course_highlights'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEducationalBackgroundExist = $this->customerModel->checkContactEducationalBackgroundExist($contactEducationalBackgroundID);
        $total = $checkContactEducationalBackgroundExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactEducationalBackground($contactEducationalBackgroundID, $customerID, $educationalStageID, $institutionName, $degreeEarned, $fieldOfStudy, $startMonth, $startYear, $endMonth, $endYear, $courseHighlights, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactEducationalBackground($customerID, $educationalStageID, $institutionName, $degreeEarned, $fieldOfStudy, $startMonth, $startYear, $endMonth, $endYear, $courseHighlights, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
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
    
        $checkContactFamilyBackgroundExist = $this->customerModel->checkContactFamilyBackgroundExist($contactFamilyBackgroundID);
        $total = $checkContactFamilyBackgroundExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactFamilyBackground($contactFamilyBackgroundID, $customerID, $familyName, $relationID, $birthday, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactFamilyBackground($customerID, $familyName, $relationID, $birthday, $mobile, $telephone, $email, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
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
    
        $checkContactEmergencyContactExist = $this->customerModel->checkContactEmergencyContactExist($contactEmergencyContactID);
        $total = $checkContactEmergencyContactExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactEmergencyContact($contactEmergencyContactID, $customerID, $emergencyContactName, $relationID, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactEmergencyContact($customerID, $emergencyContactName, $relationID, $mobile, $telephone, $email, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $trainingName = htmlspecialchars($_POST['training_name'], ENT_QUOTES, 'UTF-8');
        $trainingDate = $this->systemModel->checkDate('empty', $_POST['training_date'], '', 'Y-m-d', '');
        $trainingLocation = htmlspecialchars($_POST['training_location'], ENT_QUOTES, 'UTF-8');
        $trainingProvider = htmlspecialchars($_POST['training_provider'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTrainingExist = $this->customerModel->checkContactTrainingExist($contactTrainingID);
        $total = $checkContactTrainingExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactTraining($contactTrainingID, $customerID, $trainingName, $trainingDate, $trainingLocation, $trainingProvider, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactTraining($customerID, $trainingName, $trainingDate, $trainingLocation, $trainingProvider, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $skillName = htmlspecialchars($_POST['skill_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactSkillsExist = $this->customerModel->checkContactSkillsExist($contactSkillsID);
        $total = $checkContactSkillsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactSkills($contactSkillsID, $customerID, $skillName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactSkills($customerID, $skillName, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $talentName = htmlspecialchars($_POST['talent_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactTalentsExist = $this->customerModel->checkContactTalentsExist($contactTalentsID);
        $total = $checkContactTalentsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactTalents($contactTalentsID, $customerID, $talentName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactTalents($customerID, $talentName, $userID);

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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $hobbyName = htmlspecialchars($_POST['hobby_name'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactHobbyExist = $this->customerModel->checkContactHobbyExist($contactHobbyID);
        $total = $checkContactHobbyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactHobby($contactHobbyID, $customerID, $hobbyName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactHobby($customerID, $hobbyName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactEmploymentHistory
    # Description: 
    # Updates the existing contact employment history if it exists; otherwise, inserts a new contact employment history.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactEmploymentHistory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactEmploymentHistoryID = isset($_POST['contact_employment_history_id']) ? htmlspecialchars($_POST['contact_employment_history_id'], ENT_QUOTES, 'UTF-8') : null;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $lastPositionHeld = htmlspecialchars($_POST['employment_history_last_position_held'], ENT_QUOTES, 'UTF-8');
        $company = htmlspecialchars($_POST['employment_history_company'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['employment_history_address'], ENT_QUOTES, 'UTF-8');
        $startMonth = htmlspecialchars($_POST['employment_history_start_month'], ENT_QUOTES, 'UTF-8');
        $startYear = htmlspecialchars($_POST['employment_history_start_year'], ENT_QUOTES, 'UTF-8');
        $endMonth = htmlspecialchars($_POST['employment_history_end_month'], ENT_QUOTES, 'UTF-8');
        $endYear = htmlspecialchars($_POST['employment_history_end_year'], ENT_QUOTES, 'UTF-8');
        $startingSalary = htmlspecialchars($_POST['starting_salary'], ENT_QUOTES, 'UTF-8');
        $finalSalary = htmlspecialchars($_POST['final_salary'], ENT_QUOTES, 'UTF-8');
        $basicFunction = htmlspecialchars($_POST['basic_function'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactEmploymentHistoryExist = $this->customerModel->checkContactEmploymentHistoryExist($contactEmploymentHistoryID);
        $total = $checkContactEmploymentHistoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactEmploymentHistory($contactEmploymentHistoryID, $customerID, $company, $address, $lastPositionHeld, $startMonth, $startYear, $endMonth, $endYear, $basicFunction, $startingSalary, $finalSalary, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactEmploymentHistory($customerID, $company, $address, $lastPositionHeld, $startMonth, $startYear, $endMonth, $endYear, $basicFunction, $startingSalary, $finalSalary, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactLicense
    # Description: 
    # Updates the existing contact license if it exists; otherwise, inserts a new contact license.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactLicense() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactLicenseID = isset($_POST['contact_license_id']) ? htmlspecialchars($_POST['contact_license_id'], ENT_QUOTES, 'UTF-8') : null;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $licenseName = htmlspecialchars($_POST['license_name'], ENT_QUOTES, 'UTF-8');
        $issuingOrganization = htmlspecialchars($_POST['issuing_organization'], ENT_QUOTES, 'UTF-8');
        $startMonth = htmlspecialchars($_POST['license_start_month'], ENT_QUOTES, 'UTF-8');
        $startYear = htmlspecialchars($_POST['license_start_year'], ENT_QUOTES, 'UTF-8');
        $endMonth = htmlspecialchars($_POST['license_end_month'], ENT_QUOTES, 'UTF-8');
        $endYear = htmlspecialchars($_POST['license_end_year'], ENT_QUOTES, 'UTF-8');
        $description = htmlspecialchars($_POST['contact_license_description'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactLicenseExist = $this->customerModel->checkContactLicenseExist($contactLicenseID);
        $total = $checkContactLicenseExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactLicense($contactLicenseID, $customerID, $licenseName, $issuingOrganization, $startMonth, $startYear, $endMonth, $endYear, $description, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactLicense($customerID, $licenseName, $issuingOrganization, $startMonth, $startYear, $endMonth, $endYear, $description, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactLanguage
    # Description: 
    # Updates the existing contact language if it exists; otherwise, inserts a new contact language.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactLanguage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactLanguageID = isset($_POST['contact_language_id']) ? htmlspecialchars($_POST['contact_language_id'], ENT_QUOTES, 'UTF-8') : null;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $languageID = htmlspecialchars($_POST['language_id'], ENT_QUOTES, 'UTF-8');
        $languageProficiencyID = htmlspecialchars($_POST['language_proficiency_id'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactLanguageExist = $this->customerModel->checkContactLanguageExist($contactLanguageID);
        $total = $checkContactLanguageExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactLanguage($contactLanguageID, $customerID, $languageID, $languageProficiencyID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactLanguage($customerID, $languageID, $languageProficiencyID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveContactBank
    # Description: 
    # Updates the existing contact bank if it exists; otherwise, inserts a new contact bank.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactBankID = isset($_POST['contact_bank_id']) ? htmlspecialchars($_POST['contact_bank_id'], ENT_QUOTES, 'UTF-8') : null;
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        $bankID = htmlspecialchars($_POST['bank_id'], ENT_QUOTES, 'UTF-8');
        $bankAccountTypeID = htmlspecialchars($_POST['bank_account_type_id'], ENT_QUOTES, 'UTF-8');
        $accountNumber = htmlspecialchars($_POST['account_number'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactBankExist = $this->customerModel->checkContactBankExist($contactBankID);
        $total = $checkContactBankExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->customerModel->updateContactBank($contactBankID, $customerID, $bankID, $bankAccountTypeID, $accountNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $this->customerModel->insertContactBank($customerID, $bankID, $bankAccountTypeID, $accountNumber, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveAttendanceRecordRegular
    # Description: 
    # Updates the existing personal information if it exists; otherwise, inserts a new personal information.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveAttendanceRecordRegular() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
        
        $attendanceDate = date('Y-m-d H:i:s');
        $userID = $_SESSION['user_id'];
        $contactID = $_SESSION['contact_id'];
        $attendanceimageData = $_POST['attendance_image_data'];
        $decodedAttendanceimageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $attendanceimageData));
        $attendanceID = htmlspecialchars($_POST['attendance_id'], ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
        $ipAddress = htmlspecialchars($_POST['ip_address'], ENT_QUOTES, 'UTF-8');
        $notes = htmlspecialchars($_POST['notes'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.png';

        $directory = DEFAULT_EMPLOYEE_RELATIVE_PATH_FILE . $contactID .'/customer_attendance/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_EMPLOYEE_FULL_PATH_FILE . $contactID . '/customer_attendance/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }
    
        $checkAttendanceExist = $this->customerModel->checkAttendanceExist($attendanceID);
        $total = $checkAttendanceExist['total'] ?? 0;
    
        if ($total > 0) {
            if(!file_put_contents('.' . $filePath, $decodedAttendanceimageData)){
                echo json_encode(['success' => true, 'insertRecord' => false, 'message' => 'Unable to save image.']);
                exit;
            }

            $this->customerModel->updateRegularAttendanceExit($attendanceID, $contactID, $filePath, $attendanceDate, $location, $userID, $notes, $userID);

            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            if(!file_put_contents('.' . $filePath, $decodedAttendanceimageData)){
                echo json_encode(['success' => true, 'insertRecord' => false, 'message' => 'Unable to save image.']);
                exit;
            }

            $this->customerModel->insertRegularAttendanceEntry($contactID, $filePath, $attendanceDate, $location, $userID, $notes, $userID);

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
    # Function: insertCustomer
    # Description: 
    # Inserts the customer.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function insertCustomer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $middleName = htmlspecialchars($_POST['middle_name'], ENT_QUOTES, 'UTF-8');
        $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
        $suffix = htmlspecialchars($_POST['suffix'], ENT_QUOTES, 'UTF-8');
        $nickname = htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8');
        $corporateName = htmlspecialchars($_POST['corporate_name'], ENT_QUOTES, 'UTF-8');
        $bio = htmlspecialchars($_POST['bio'], ENT_QUOTES, 'UTF-8');
        $civilStatus = htmlspecialchars($_POST['civil_status'], ENT_QUOTES, 'UTF-8');
        $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
        $religion = htmlspecialchars($_POST['religion'], ENT_QUOTES, 'UTF-8');
        $bloodType = htmlspecialchars($_POST['blood_type'], ENT_QUOTES, 'UTF-8');
        $birthday = $this->systemModel->checkDate('empty', $_POST['birthday'], '', 'Y-m-d', '');
        $birthPlace = htmlspecialchars($_POST['birth_place'], ENT_QUOTES, 'UTF-8');
        $height = htmlspecialchars($_POST['height'], ENT_QUOTES, 'UTF-8');
        $weight = htmlspecialchars($_POST['weight'], ENT_QUOTES, 'UTF-8');

        $fileAs = $this->systemSettingModel->getSystemSetting(4)['value'];
        $fileAs = str_replace('{first_name}', $firstName, $fileAs);
        $fileAs = str_replace('{middle_name}', $middleName, $fileAs);
        $fileAs = str_replace('{last_name}', $lastName, $fileAs);
        $fileAs = str_replace('{suffix}', $suffix, $fileAs);
        
        $customerUniqueID = $this->systemSettingModel->getSystemSetting(5)['value'] + 1;
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $customerID = $this->customerModel->insertCustomer($customerUniqueID, $userID);
        $this->customerModel->insertPersonalInformation($customerID, $fileAs, $firstName, $middleName, $lastName, $suffix, $nickname, $corporateName, $bio, $civilStatus, $gender, $religion, $bloodType, $birthday, $birthPlace, $height, $weight, $userID);

        $this->systemSettingModel->updateSystemSettingValue(5, $customerUniqueID, $userID);

        echo json_encode(['success' => true, 'insertRecord' => true, 'customerID' => $this->securityModel->encryptData($customerID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCustomerImage
    # Description: 
    # Handles the update of the customer image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateCustomerImage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $customerImageFileName = $_FILES['customer_image']['name'];
        $customerImageFileSize = $_FILES['customer_image']['size'];
        $customerImageFileError = $_FILES['customer_image']['error'];
        $customerImageTempName = $_FILES['customer_image']['tmp_name'];
        $customerImageFileExtension = explode('.', $customerImageFileName);
        $customerImageActualFileExtension = strtolower(end($customerImageFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(4);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(4);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($customerImageActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($customerImageTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the customer image.']);
            exit;
        }
        
        if($customerImageFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($customerImageFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $customerImageActualFileExtension;

        $directory = DEFAULT_CUSTOMER_RELATIVE_PATH_FILE . $customerID .'/customer_image/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_CUSTOMER_FULL_PATH_FILE . $customerID . '/customer_image/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $customerDetails = $this->customerModel->getPersonalInformation($customerID);
        $customerImage = !empty($customerDetails['contact_image']) ? '.' . $customerDetails['contact_image'] : null;

        if(file_exists($customerImage)){
            if (!unlink($customerImage)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($customerImageTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->customerModel->updateCustomerImage($customerID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCustomerStatusToActive
    # Description: 
    # Update the customer status to active if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateCustomerStatusToActive() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

        $customerDetails = $this->customerModel->getPersonalInformation($customerID);
        $contactImage = $customerDetails['contact_image'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerExist = $this->customerModel->checkCustomerExist($customerID);
        $total = $checkCustomerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        if(empty($contactImage)){
            echo json_encode(['success' => false, 'noContactImage' =>  true]);
            exit;
        }
    
    
        $checkCustomerPrimaryAddress = $this->customerModel->checkCustomerPrimaryAddress($customerID);
        $total = $checkCustomerPrimaryAddress['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'noPrimaryAddress' =>  true]);
            exit;
        }
    
        $checkCustomerPrimaryContactInformation = $this->customerModel->checkCustomerPrimaryContactInformation($customerID);
        $total = $checkCustomerPrimaryContactInformation['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'noPrimaryContactInformation' =>  true]);
            exit;
        }
    
        $checkCustomerPrimaryIdentification = $this->customerModel->checkCustomerPrimaryIdentification($customerID);
        $total = $checkCustomerPrimaryIdentification['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'noPrimaryIdentification' =>  true]);
            exit;
        }
    
        $this->customerModel->updateCustomerStatus($customerID, 'Active', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCustomerStatusToForUpdating
    # Description: 
    # Update the customer status to for updating if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateCustomerStatusToForUpdating() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerExist = $this->customerModel->checkCustomerExist($customerID);
        $total = $checkCustomerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->updateCustomerStatus($customerID, 'For Updating', $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Assign methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: assignComaker
    # Description: 
    # Assign the customer if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function assignComaker() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $comakerID = htmlspecialchars($_POST['comaker_id'], ENT_QUOTES, 'UTF-8');
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerExist = $this->customerModel->checkCustomerExist($customerID);
        $total = $checkCustomerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $checkComakerExist = $this->customerModel->checkCustomerExist($comakerID);
        $total = $checkComakerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->insertCustomerComaker($customerID, $comakerID, $userID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCustomer
    # Description: 
    # Delete the customer if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCustomer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCustomerExist = $this->customerModel->checkCustomerExist($customerID);
        $total = $checkCustomerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteCustomer($customerID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCustomer
    # Description: 
    # Delete the selected customers if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCustomer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $customerIDs = $_POST['customer_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($customerIDs as $customerID){
            $this->customerModel->deleteCustomer($customerID);
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
    
        $checkContactInformationExist = $this->customerModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactInformation($contactInformationID);
            
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
    
        $checkContactAddressExist = $this->customerModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactAddress($contactAddressID);
            
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
    
        $checkContactIdentificationExist = $this->customerModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactIdentification($contactIdentificationID);
            
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
    
        $checkContactEducationalBackgroundExist = $this->customerModel->checkContactEducationalBackgroundExist($contactEducationalBackgroundID);
        $total = $checkContactEducationalBackgroundExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactEducationalBackground($contactEducationalBackgroundID);
            
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
    
        $checkContactFamilyBackgroundExist = $this->customerModel->checkContactFamilyBackgroundExist($contactFamilyBackgroundID);
        $total = $checkContactFamilyBackgroundExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactFamilyBackground($contactFamilyBackgroundID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactComaker
    # Description: 
    # Delete the contact family background if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactComaker() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactComakerID = htmlspecialchars($_POST['contact_comaker_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactComakerExist = $this->customerModel->checkContactComakerExist($contactComakerID);
        $total = $checkContactComakerExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->deleteContactComaker($contactComakerID);
            
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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationExist = $this->customerModel->checkContactInformationExist($contactInformationID);
        $total = $checkContactInformationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->updateContactInformationStatus($contactInformationID, $customerID, $userID);
            
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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactAddressExist = $this->customerModel->checkContactAddressExist($contactAddressID);
        $total = $checkContactAddressExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->updateContactAddressStatus($contactAddressID, $customerID, $userID);
            
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
        $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactIdentificationExist = $this->customerModel->checkContactIdentificationExist($contactIdentificationID);
        $total = $checkContactIdentificationExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->customerModel->updateContactIdentificationStatus($contactIdentificationID, $customerID, $userID);
            
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
    
        if (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) {
            $userID = $_SESSION['user_id'];
            $customerID = $_POST['customer_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $customerDetails = $this->customerModel->getPersonalInformation($customerID);
            $customerImage = $this->systemModel->checkImage($customerDetails['contact_image'], 'profile');
            $fileAs = $customerDetails['file_as'];
            $firstName = $customerDetails['first_name'];
            $middleName = $customerDetails['middle_name'];
            $lastName = $customerDetails['last_name'];
            $suffix = $customerDetails['suffix'];
            $genderID = $customerDetails['gender_id'];
            $civilStatusID = $customerDetails['civil_status_id'];
            $religionID = $customerDetails['religion_id'];
            $bloodTypeID = $customerDetails['blood_type_id'];

            $genderName = $this->genderModel->getGender($genderID)['gender_name'] ?? null;
            $civilStatusName = $this->civilStatusModel->getCivilStatus($civilStatusID)['civil_status_name'] ?? null;
            $religionName = $this->religionModel->getReligion($religionID)['religion_name'] ?? null;
            $bloodTypeName = $this->bloodTypeModel->getBloodType($bloodTypeID)['blood_type_name'] ?? null;

            $response = [
                'success' => true,
                'customerImage' => $customerImage,
                'fileAs' => $fileAs,
                'firstName' => $firstName,
                'middleName' => $middleName,
                'lastName' => $lastName,
                'suffix' => $suffix,
                'nickname' => $customerDetails['nickname'],
                'corporateName' => $customerDetails['corporate_name'],
                'bio' => $customerDetails['bio'] ?? 'No personal summary.',
                'civilStatusID' => $civilStatusID,
                'genderID' => $genderID,
                'religionID' => $religionID,
                'bloodTypeID' => $bloodTypeID,
                'birthday' =>  $this->systemModel->checkDate('empty', $customerDetails['birthday'], '', 'm/d/Y', ''),
                'birthPlace' => $customerDetails['birth_place'],
                'height' => $customerDetails['height'] ?? 0,
                'weight' => $customerDetails['weight'] ?? 0
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
    
            $contactInformationDetails = $this->customerModel->getContactInformation($contactInformationID);

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
    
            $contactAddressDetails = $this->customerModel->getContactAddress($contactAddressID);

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
    
            $contactIdentificationDetails = $this->customerModel->getContactIdentification($contactIdentificationID);

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
    
            $contactFamilyBackgroundDetails = $this->customerModel->getContactFamilyBackground($contactFamilyBackgroundID);

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
    # Function: getComakerDetails
    # Description: 
    # Handles the retrieval of comaker details.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getComakerDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['comaker_id']) && !empty($_POST['comaker_id'])) {
            $userID = $_SESSION['user_id'];
            $comakerID = htmlspecialchars($_POST['comaker_id'], ENT_QUOTES, 'UTF-8');
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $comakerDetails = $this->customerModel->getPersonalInformation($comakerID);
            $comakerName = $comakerDetails['file_as'] ?? null;

            $comakerPrimaryAddress = $this->customerModel->getcustomerPrimaryAddress($comakerID);
            $comakerAddress = $comakerPrimaryAddress['address'] . ', ' . $comakerPrimaryAddress['city_name'] . ', ' . $comakerPrimaryAddress['state_name'] . ', ' . $comakerPrimaryAddress['country_name'];

            $comakerContactInformation = $this->customerModel->getcustomerPrimaryContactInformation($comakerID);
            $comakerMobile = !empty($comakerContactInformation['mobile']) ? $comakerContactInformation['mobile'] : '--';
            $comakerTelephone = !empty($comakerContactInformation['telephone']) ? $comakerContactInformation['telephone'] : '--';
            $comakerEmail = !empty($comakerContactInformation['email']) ? $comakerContactInformation['email'] : '--';

            $response = [
                'success' => true,
                'comakerName' => $comakerName,
                'comakerAddress' => $comakerAddress,
                'comakerMobile' => $comakerMobile,
                'comakerTelephone' => $comakerTelephone,
                'comakerEmail' => $comakerEmail
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
require_once '../model/customer-model.php';
require_once '../model/user-model.php';
require_once '../model/company-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/gender-model.php';
require_once '../model/civil-status-model.php';
require_once '../model/religion-model.php';
require_once '../model/blood-type-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CustomerController(new CustomerModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new SystemSettingModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new GenderModel(new DatabaseModel), new CivilStatusModel(new DatabaseModel), new ReligionModel(new DatabaseModel), new BloodTypeModel(new DatabaseModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>