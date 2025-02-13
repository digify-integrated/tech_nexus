<?php
session_start();

# -------------------------------------------------------------
#
# Function: ProductInventoryReportController
# Description: 
# The ProductInventoryReportController class handles property related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ProductInventoryReportController {
    private $propertyModel;
    private $userModel;
    private $uploadSettingModel;
    private $fileExtensionModel;
    private $companyModel;
    private $cityModel;
    private $stateModel;
    private $countryModel;
    private $currencyModel;
    private $productInventoryReportModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PropertyModel, UserModel and SecurityModel instances.
    # These instances are used for property related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PropertyModel $propertyModel     The PropertyModel instance for property related operations.
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
    public function __construct(PropertyModel $propertyModel, UserModel $userModel, UploadSettingModel $uploadSettingModel, FileExtensionModel $fileExtensionModel, CompanyModel $companyModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, CurrencyModel $currencyModel, ProductInventoryReportModel $productInventoryReportModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->propertyModel = $propertyModel;
        $this->userModel = $userModel;
        $this->uploadSettingModel = $uploadSettingModel;
        $this->fileExtensionModel = $fileExtensionModel;
        $this->companyModel = $companyModel;
        $this->cityModel = $cityModel;
        $this->stateModel = $stateModel;
        $this->countryModel = $countryModel;
        $this->currencyModel = $currencyModel;
        $this->productInventoryReportModel = $productInventoryReportModel;
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
                case 'open inventory report':
                    $this->openInventoryReport();
                    break;
                case 'close inventory report':
                    $this->closeInventoryReport();
                    break;
                case 'scan inventory report':
                    $this->scanInventoryReport();
                    break;
                case 'get property details':
                    $this->getPropertyDetails();
                    break;
                case 'delete property':
                    $this->deleteProperty();
                    break;
                case 'delete multiple property':
                    $this->deleteMultipleProperty();
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
    # Function: openInventoryReport
    # Description: 
    # Updates the existing property if it exists; otherwise, inserts a new property.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function openInventoryReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
    
        $this->productInventoryReportModel->openProductInventoryReport($userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function closeInventoryReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
    
        $this->productInventoryReportModel->closeProductInventoryReport($userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function scanInventoryReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $product_inventory_id = htmlspecialchars($_POST['product_inventory_id'], ENT_QUOTES, 'UTF-8');
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
    
        $this->productInventoryReportModel->scanProduct($product_inventory_id, $product_id, $userID);

        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteProperty
    # Description: 
    # Delete the property if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteProperty() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $propertyID = htmlspecialchars($_POST['property_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPropertyExist = $this->propertyModel->checkPropertyExist($propertyID);
        $total = $checkPropertyExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $propertyDetails = $this->propertyModel->getProperty($propertyID);
        $propertyLogo = !empty($propertyDetails['property_logo']) ? '.' . $propertyDetails['property_logo'] : null;

        if(file_exists($propertyLogo)){
            if (!unlink($propertyLogo)) {
                echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                exit;
            }
        }
    
        $this->propertyModel->deleteProperty($propertyID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleProperty
    # Description: 
    # Delete the selected propertys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleProperty() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $propertyIDs = $_POST['property_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($propertyIDs as $propertyID){
            $propertyDetails = $this->propertyModel->getProperty($propertyID);
            $propertyLogo = !empty($propertyDetails['property_logo']) ? '.' . $propertyDetails['property_logo'] : null;

            if(file_exists($propertyLogo)){
                if (!unlink($propertyLogo)) {
                    echo json_encode(['success' => false, 'message' => 'File cannot be deleted due to an error.']);
                    exit;
                }
            }
            
            $this->propertyModel->deleteProperty($propertyID);
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
    # Function: getPropertyDetails
    # Description: 
    # Handles the retrieval of property details such as property name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPropertyDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['property_id']) && !empty($_POST['property_id'])) {
            $userID = $_SESSION['user_id'];
            $propertyID = $_POST['property_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $propertyDetails = $this->propertyModel->getProperty($propertyID);
            $companyID = $propertyDetails['company_id'];
            $cityID = $propertyDetails['city_id'];

            $companyDetails = $this->companyModel->getCompany($companyID);
            $companyName = $companyDetails['company_name'] ?? null;

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
                'propertyName' => $propertyDetails['property_name'],
                'address' => $propertyDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'companyID' => $companyID,
                'companyName' => $companyName
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
require_once '../model/property-model.php';
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/currency-model.php';
require_once '../model/user-model.php';
require_once '../model/product-inventory-report-model.php';
require_once '../model/upload-setting-model.php';
require_once '../model/file-extension-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ProductInventoryReportController(new PropertyModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new UploadSettingModel(new DatabaseModel), new FileExtensionModel(new DatabaseModel), new CompanyModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new CurrencyModel(new DatabaseModel), new ProductInventoryReportModel(new DatabaseModel()), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>