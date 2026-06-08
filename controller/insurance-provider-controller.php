<?php
session_start();

# -------------------------------------------------------------
#
# Function: InsuranceProviderController
# Description: 
# The InsuranceProviderController class handles insurance provider related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class InsuranceProviderController {
    private $insuranceProviderModel;
    private $userModel;
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
    # The constructor initializes the object with the provided models and security instances.
    # These instances are used for provider related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param InsuranceProviderModel $insuranceProviderModel  The InsuranceProviderModel instance for provider related operations.
    # - @param UserModel $userModel             The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param CityModel $cityModel             The CityModel instance for city related operations.
    # - @param StateModel $stateModel           The StateModel instance for state related operations.
    # - @param CountryModel $countryModel       The CountryModel instance for country related operations.
    # - @param CurrencyModel $currencyModel     The CurrencyModel instance for currency related operations.
    # - @param SecurityModel $securityModel     The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel         The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(InsuranceProviderModel $insuranceProviderModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, CurrencyModel $currencyModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->insuranceProviderModel = $insuranceProviderModel;
        $this->userModel = $userModel;
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
                case 'save insurance provider':
                    $this->saveInsuranceProvider();
                    break;
                case 'get insurance provider details':
                    $this->getInsuranceProviderDetails();
                    break;
                case 'delete insurance provider':
                    $this->deleteInsuranceProvider();
                    break;
                case 'delete multiple insurance provider':
                    $this->deleteMultipleInsuranceProvider();
                    break;
                case 'duplicate insurance provider':
                    $this->duplicateInsuranceProvider();
                    break;
                case 'change insurance provider logo':
                    $this->updateInsuranceProviderLogo();
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
    # Function: saveInsuranceProvider
    # Description: 
    # Updates the existing insurance provider if it exists; otherwise, inserts a new insurance provider.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveInsuranceProvider() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $providerID = isset($_POST['provider_id']) ? htmlspecialchars($_POST['provider_id'], ENT_QUOTES, 'UTF-8') : null;
        $providerName = htmlspecialchars($_POST['provider_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
        $taxId = htmlspecialchars($_POST['tax_id'], ENT_QUOTES, 'UTF-8'); // Swapped out tax_id
        $currencyID = htmlspecialchars($_POST['currency_id'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $website = htmlspecialchars($_POST['website'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProviderExist = $this->insuranceProviderModel->checkInsuranceProviderExist($providerID);
        $total = $checkProviderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceProviderModel->updateInsuranceProvider($providerID, $providerName, $address, $cityID, $taxId, $currencyID, $mobile, $telephone, $email, $website, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'providerID' => $this->securityModel->encryptData($providerID)]);
            exit;
        } 
        else {
            $providerID = $this->insuranceProviderModel->insertInsuranceProvider($providerName, $address, $cityID, $taxId, $currencyID, $mobile, $telephone, $email, $website, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'providerID' => $this->securityModel->encryptData($providerID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateInsuranceProviderLogo
    # Description: 
    # Handles the update of the provider's brand logo image.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function updateInsuranceProviderLogo() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $providerID = htmlspecialchars($_POST['provider_id'], ENT_QUOTES, 'UTF-8');
        
        $user = $this->userModel->getUserByID($userID);
        $isActive = $user['is_active'] ?? 0;
    
        if (!$isActive) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $providerLogoFileName = $_FILES['provider_logo']['name'];
        $providerLogoFileSize = $_FILES['provider_logo']['size'];
        $providerLogoFileError = $_FILES['provider_logo']['error'];
        $providerLogoTempName = $_FILES['provider_logo']['tmp_name'];
        $providerLogoFileExtension = explode('.', $providerLogoFileName);
        $providerLogoActualFileExtension = strtolower(end($providerLogoFileExtension));

        $uploadSetting = $this->uploadSettingModel->getUploadSetting(3);
        $maxFileSize = $uploadSetting['max_file_size'];

        $uploadSettingFileExtension = $this->uploadSettingModel->getUploadSettingFileExtension(3);
        $allowedFileExtensions = [];

        foreach ($uploadSettingFileExtension as $row) {
            $fileExtensionID = $row['file_extension_id'];
            $fileExtensionDetails = $this->fileExtensionModel->getFileExtension($fileExtensionID);
            $allowedFileExtensions[] = $fileExtensionDetails['file_extension_name'];
        }

        if (!in_array($providerLogoActualFileExtension, $allowedFileExtensions)) {
            $response = ['success' => false, 'message' => 'The file uploaded is not supported.'];
            echo json_encode($response);
            exit;
        }
        
        if(empty($providerLogoTempName)){
            echo json_encode(['success' => false, 'message' => 'Please choose the insurance provider logo.']);
            exit;
        }
        
        if($providerLogoFileError){
            echo json_encode(['success' => false, 'message' => 'An error occurred while uploading the file.']);
            exit;
        }
        
        if($providerLogoFileSize > ($maxFileSize * 1048576)){
            echo json_encode(['success' => false, 'message' => 'The uploaded file exceeds the maximum allowed size of ' . $maxFileSize . ' Mb.']);
            exit;
        }

        $fileName = $this->securityModel->generateFileName();
        $fileNew = $fileName . '.' . $providerLogoActualFileExtension;

        $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'insurance_provider/';
        $fileDestination = $_SERVER['DOCUMENT_ROOT'] . DEFAULT_IMAGES_FULL_PATH_FILE . 'insurance_provider/' . $fileNew;
        $filePath = $directory . $fileNew;

        $directoryChecker = $this->securityModel->directoryChecker('.' . $directory);

        if(!$directoryChecker){
            echo json_encode(['success' => false, 'message' => $directoryChecker]);
            exit;
        }

        $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($providerID);
        $providerLogo = !empty($providerDetails['provider_logo']) ? '.' . $providerDetails['provider_logo'] : null;

        if(file_exists($providerLogo)){
            if (!unlink($providerLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }

        if(!move_uploaded_file($providerLogoTempName, $fileDestination)){
            echo json_encode(['success' => false, 'message' => 'There was an error uploading your file.']);
            exit;
        }

        $this->insuranceProviderModel->updateInsuranceProviderLogo($providerID, $filePath, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteInsuranceProvider
    # Description: 
    # Delete the insurance provider if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteInsuranceProvider() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $providerID = htmlspecialchars($_POST['provider_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProviderExist = $this->insuranceProviderModel->checkInsuranceProviderExist($providerID);
        $total = $checkProviderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($providerID);
        $providerLogo = !empty($providerDetails['provider_logo']) ? '.' . $providerDetails['provider_logo'] : null;

        if(file_exists($providerLogo)){
            if (!unlink($providerLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->insuranceProviderModel->deleteInsuranceProvider($providerID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleInsuranceProvider
    # Description: 
    # Delete the selected insurance providers if they exist; otherwise, skip.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleInsuranceProvider() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $providerIDs = $_POST['provider_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($providerIDs as $providerID){
            $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($providerID);
            $providerLogo = !empty($providerDetails['provider_logo']) ? '.' . $providerDetails['provider_logo'] : null;

            if(file_exists($providerLogo)){
                if (!unlink($providerLogo)) {
                    echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                    exit;
                }
            }
            
            $this->insuranceProviderModel->deleteInsuranceProvider($providerID);
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
    # Function: duplicateInsuranceProvider
    # Description: 
    # Duplicates the insurance provider configuration details if it exists.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateInsuranceProvider() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $providerID = htmlspecialchars($_POST['provider_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProviderExist = $this->insuranceProviderModel->checkInsuranceProviderExist($providerID);
        $total = $checkProviderExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($providerID);
        $providerLogo = !empty($providerDetails['provider_logo']) ? '.' . $providerDetails['provider_logo'] : null;

        if(file_exists($providerLogo)){
            $extension = pathinfo($providerLogo, PATHINFO_EXTENSION);

            $fileName = $this->securityModel->generateFileName();
            $fileNew = $fileName . '.' . $extension;

            $directory = DEFAULT_IMAGES_RELATIVE_PATH_FILE . 'insurance_provider/';
            $filePath = $directory . $fileNew;

            copy($providerLogo, '.' . $filePath);
        }
        else{
            $filePath = null;
        }

        $providerID = $this->insuranceProviderModel->duplicateInsuranceProvider($providerID, $userID);

        if(file_exists($providerLogo)){
            $this->insuranceProviderModel->updateInsuranceProviderLogo($providerID, $filePath, $userID);
        }        

        echo json_encode(['success' => true, 'providerID' =>  $this->securityModel->encryptData($providerID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getInsuranceProviderDetails
    # Description: 
    # Handles the retrieval of specific details for an insurance provider profile.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getInsuranceProviderDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['provider_id']) && !empty($_POST['provider_id'])) {
            $userID = $_SESSION['user_id'];
            $providerID = $_POST['provider_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $providerDetails = $this->insuranceProviderModel->getInsuranceProvider($providerID);
            $cityID = $providerDetails['city_id'];
            $currencyID = $providerDetails['currency_id'];

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
                'providerName' => $providerDetails['provider_name'],
                'address' => $providerDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'taxID' => $providerDetails['tax_id'],
                'currencyID' => $currencyID,
                'currencyName' => $currencyName,
                'mobile' => $providerDetails['mobile'],
                'telephone' => $providerDetails['telephone'],
                'email' => $providerDetails['email'],
                'website' => $providerDetails['website']
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
require_once '../model/insurance-provider-model.php'; // Updated inclusion
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/currency-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new InsuranceProviderController(
    new InsuranceProviderModel(new DatabaseModel), 
    new UserModel(new DatabaseModel, new SystemModel), 
    new UploadSettingModel(new DatabaseModel), 
    new FileExtensionModel(new DatabaseModel), 
    new CityModel(new DatabaseModel), 
    new StateModel(new DatabaseModel), 
    new CountryModel(new DatabaseModel), 
    new CurrencyModel(new DatabaseModel), 
    new SecurityModel(), 
    new SystemModel()
);
$controller->handleRequest();
?>