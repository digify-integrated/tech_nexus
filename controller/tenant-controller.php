<?php
session_start();

# -------------------------------------------------------------
#
# Function: TenantController
# Description: 
# The TenantController class handles tenant related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class TenantController {
    private $tenantModel;
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
    # The constructor initializes the object with the provided TenantModel, UserModel and SecurityModel instances.
    # These instances are used for tenant related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param TenantModel $tenantModel     The TenantModel instance for tenant related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
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
    public function __construct(TenantModel $tenantModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, CurrencyModel $currencyModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->tenantModel = $tenantModel;
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
                case 'save tenant':
                    $this->saveTenant();
                    break;
                case 'get tenant details':
                    $this->getTenantDetails();
                    break;
                case 'delete tenant':
                    $this->deleteTenant();
                    break;
                case 'delete multiple tenant':
                    $this->deleteMultipleTenant();
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
    # Function: saveTenant
    # Description: 
    # Updates the existing tenant if it exists; otherwise, inserts a new tenant.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveTenant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $tenantID = isset($_POST['tenant_id']) ? htmlspecialchars($_POST['tenant_id'], ENT_QUOTES, 'UTF-8') : null;
        $tenantName = htmlspecialchars($_POST['tenant_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTenantExist = $this->tenantModel->checkTenantExist($tenantID);
        $total = $checkTenantExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->tenantModel->updateTenant($tenantID, $tenantName, $address, $cityID, $phone, $mobile, $telephone, $email, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'tenantID' => $this->securityModel->encryptData($tenantID)]);
            exit;
        } 
        else {
            $tenantID = $this->tenantModel->insertTenant($tenantName, $address, $cityID, $phone, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'tenantID' => $this->securityModel->encryptData($tenantID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTenant
    # Description: 
    # Delete the tenant if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteTenant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $tenantID = htmlspecialchars($_POST['tenant_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTenantExist = $this->tenantModel->checkTenantExist($tenantID);
        $total = $checkTenantExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $tenantDetails = $this->tenantModel->getTenant($tenantID);
        $tenantLogo = !empty($tenantDetails['tenant_logo']) ? '.' . $tenantDetails['tenant_logo'] : null;

        if(file_exists($tenantLogo)){
            if (!unlink($tenantLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->tenantModel->deleteTenant($tenantID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleTenant
    # Description: 
    # Delete the selected tenants if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleTenant() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $tenantIDs = $_POST['tenant_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($tenantIDs as $tenantID){
            $tenantDetails = $this->tenantModel->getTenant($tenantID);
            $tenantLogo = !empty($tenantDetails['tenant_logo']) ? '.' . $tenantDetails['tenant_logo'] : null;

            if(file_exists($tenantLogo)){
                if (!unlink($tenantLogo)) {
                    echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                    exit;
                }
            }
            
            $this->tenantModel->deleteTenant($tenantID);
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
    # Function: getTenantDetails
    # Description: 
    # Handles the retrieval of tenant details such as tenant name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getTenantDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['tenant_id']) && !empty($_POST['tenant_id'])) {
            $userID = $_SESSION['user_id'];
            $tenantID = $_POST['tenant_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $tenantDetails = $this->tenantModel->getTenant($tenantID);
            $cityID = $tenantDetails['city_id'];

            $cityDetails = $this->cityModel->getCity($cityID);
            $cityName = $cityDetails['city_name'];
            $stateID = $cityDetails['state_id'];

            $stateDetails = $this->stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'];
            $countryID = $stateDetails['country_id'];

            $countryName = $this->countryModel->getCountry($countryID)['country_name'];
            $cityName = $cityDetails['city_name'] . ', ' . $stateName . ', ' . $countryName;

            $response = [
                'success' => true,
                'tenantName' => $tenantDetails['tenant_name'],
                'address' => $tenantDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'currencyName' => $currencyName,
                'phone' => $tenantDetails['phone'],
                'mobile' => $tenantDetails['mobile'],
                'telephone' => $tenantDetails['telephone'],
                'email' => $tenantDetails['email']
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
require_once '../model/tenant-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/currency-model.php';
require_once '../model/user-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new TenantController(new TenantModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new CurrencyModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>