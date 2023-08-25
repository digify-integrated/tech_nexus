<?php
session_start();

# -------------------------------------------------------------
#
# Function: CompanyController
# Description: 
# The CompanyController class handles company related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CompanyController {
    private $companyModel;
    private $userModel;
    private $roleModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $cityModel;
    private $stateModel;
    private $countryModel;
    private $currencyModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CompanyModel, UserModel and SecurityModel instances.
    # These instances are used for company related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param CityModel $cityModel     The CityModel instance for city related operations.
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param CurrencyModel $currencyModel     The CurrencyModel instance for currency related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CompanyModel $companyModel, UserModel $userModel, RoleModel $roleModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, CurrencyModel $currencyModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->companyModel = $companyModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->cityModel = $cityModel;
        $this->stateModel = $stateModel;
        $this->countryModel = $countryModel;
        $this->currencyModel = $currencyModel;
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
                case 'save company':
                    $this->saveCompany();
                    break;
                case 'get company details':
                    $this->getCompanyDetails();
                    break;
                case 'delete company':
                    $this->deleteCompany();
                    break;
                case 'delete multiple company':
                    $this->deleteMultipleCompany();
                    break;
                case 'duplicate company':
                    $this->duplicateCompany();
                    break;
                case 'change company logo':
                    $this->updateCompanyLogo();
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
    # Function: saveCompany
    # Description: 
    # Updates the existing company if it exists; otherwise, inserts a new company.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCompany() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyID = isset($_POST['company_id']) ? htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8') : null;
        $companyName = htmlspecialchars($_POST['company_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
        $taxID = htmlspecialchars($_POST['tax_id'], ENT_QUOTES, 'UTF-8');
        $currencyID = htmlspecialchars($_POST['currency_id'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCompanyExist = $this->companyModel->checkCompanyExist($companyID);
        $total = $checkCompanyExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->companyModel->updateCompany($companyID, $companyName, $address, $cityID, $taxID, $currencyID, $phone, $mobile, $telephone, $email, $website, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'companyID' => $this->securityModel->encryptData($companyID)]);
            exit;
        } 
        else {
            $companyID = $this->companyModel->insertCompany($companyName, $address, $cityID, $taxID, $currencyID, $phone, $mobile, $telephone, $email, $website, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'companyID' => $this->securityModel->encryptData($companyID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCompanyLogo
    # Description: 
    # Handles the update of the profile picture.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateCompanyLogo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $companyLogoFileName = $_FILES['company_logo']['name'];
        $companyLogoFileSize = $_FILES['company_logo']['size'];
        $companyLogoFileError = $_FILES['company_logo']['error'];
        $companyLogoTempName = $_FILES['company_logo']['tmp_name'];
        $companyLogoFileExtension = explode('.', $companyLogoFileName);
        $companyLogoActualFileExtension = strtolower(end($companyLogoFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(3);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(3);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($companyLogoActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($companyLogoTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the company logo.']);
            exit;
        }
        
        if($companyLogoFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($companyLogoFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $companyLogoActualFileExtension;

        $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'company/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'company/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $company = $this->companyModel->getCompany($companyID);
        $companyLogo = $company['company_logo'] !== null ? '.' . $company['company_logo'] : null;

        if(file_exists($companyLogo)){
            if (!unlink($companyLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($companyLogoTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->companyModel->updateCompanyLogo($companyID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCompany
    # Description: 
    # Delete the company if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCompany() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCompanyExist = $this->companyModel->checkCompanyExist($companyID);
        $total = $checkCompanyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $company = $this->companyModel->getCompany($companyID);
        $companyLogo = $company['company_logo'] !== null ? '.' . $company['company_logo'] : null;

        if(file_exists($companyLogo)){
            if (!unlink($companyLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->companyModel->deleteCompany($companyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCompany
    # Description: 
    # Delete the selected companys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCompany() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyIDs = $_POST['company_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($companyIDs as $companyID){
            $company = $this->companyModel->getCompany($companyID);
            $companyLogo = $company['company_logo'] !== null ? '.' . $company['company_logo'] : null;

            if(file_exists($companyLogo)){
                if (!unlink($companyLogo)) {
                    echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                    exit;
                }
            }
            
            $this->companyModel->deleteCompany($companyID);
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
    # Function: duplicateCompany
    # Description: 
    # Duplicates the company if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCompany() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCompanyExist = $this->companyModel->checkCompanyExist($companyID);
        $total = $checkCompanyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $company = $this->companyModel->getCompany($companyID);
        $companyLogo = $company['company_logo'] !== null ? '.' . $company['company_logo'] : null;

        if(file_exists($companyLogo)){
            $extension = pathinfo($companyLogo, PATHINFO_EXTENSION);

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $extension;

            $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'company/';
            $filePath = $directory . $fileNew;

            copy($companyLogo, '.' . $filePath);
        }
        else{
            $filePath = null;
        }

        $companyID = $this->companyModel->duplicateCompany($companyID, $userID);

        if(file_exists($companyLogo)){
            $this->companyModel->updateCompanyLogo($companyID, $filePath, $userID);
        }        

        echo json_encode(['success' => true, 'companyID' =>  $this->securityModel->encryptData($companyID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCompanyDetails
    # Description: 
    # Handles the retrieval of company details such as company name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCompanyDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['company_id']) && !empty($_POST['company_id'])) {
            $userID = $_SESSION['user_id'];
            $companyID = $_POST['company_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $companyDetails = $this->companyModel->getCompany($companyID);
            $cityID = $companyDetails['city_id'];
            $currencyID = $companyDetails['currency_id'];

            $cityDetails = $this->cityModel->getCity($cityID);
            $cityName = $cityDetails['city_name'];
            $stateID = $cityDetails['state_id'];

            $stateDetails = $this->stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'];
            $countryID = $stateDetails['country_id'];

            $countryName = $this->countryModel->getCountry($countryID)['country_name'];
            $cityName = $cityDetails['city_name'] . ', ' . $stateName . ', ' . $countryName;

            $currencyDetails = $this->currencyModel->getCurrency($currencyID);
            $currencyName = $currencyDetails['currency_name'] ?? null;

            $response = [
                'success' => true,
                'companyName' => $companyDetails['company_name'],
                'address' => $companyDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'taxID' => $companyDetails['tax_id'],
                'currencyID' => $currencyID,
                'currencyName' => $currencyName,
                'phone' => $companyDetails['phone'],
                'mobile' => $companyDetails['mobile'],
                'telephone' => $companyDetails['telephone'],
                'email' => $companyDetails['email'],
                'website' => $companyDetails['website'],
                'companyLogo' => $this->systemModel->checkImage($companyDetails['company_logo'], 'company logo')
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
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/currency-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CompanyController(new CompanyModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new CurrencyModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>