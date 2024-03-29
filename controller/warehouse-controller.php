<?php
session_start();

# -------------------------------------------------------------
#
# Function: WarehouseController
# Description: 
# The WarehouseController class handles warehouse related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class WarehouseController {
    private $warehouseModel;
    private $userModel;
    private $companyModel;
    private $cityModel;
    private $stateModel;
    private $countryModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided WarehouseModel, UserModel and SecurityModel instances.
    # These instances are used for warehouse related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param WarehouseModel $warehouseModel     The WarehouseModel instance for warehouse related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param CompanyModel $companyModel     The CompanyModel instance for company related operations.
    # - @param CityModel $cityModel     The CityModel instance for city related operations.
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(WarehouseModel $warehouseModel, UserModel $userModel, CompanyModel $companyModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->warehouseModel = $warehouseModel;
        $this->userModel = $userModel;
        $this->companyModel = $companyModel;
        $this->cityModel = $cityModel;
        $this->stateModel = $stateModel;
        $this->countryModel = $countryModel;
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
                case 'save warehouse':
                    $this->saveWarehouse();
                    break;
                case 'get warehouse details':
                    $this->getWarehouseDetails();
                    break;
                case 'delete warehouse':
                    $this->deleteWarehouse();
                    break;
                case 'delete multiple warehouse':
                    $this->deleteMultipleWarehouse();
                    break;
                case 'duplicate warehouse':
                    $this->duplicateWarehouse();
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
    # Function: saveWarehouse
    # Description: 
    # Updates the existing warehouse if it exists; otherwise, inserts a new warehouse.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveWarehouse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $warehouseID = isset($_POST['warehouse_id']) ? htmlspecialchars($_POST['warehouse_id'], ENT_QUOTES, 'UTF-8') : null;
        $warehouseName = htmlspecialchars($_POST['warehouse_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
        $mobile = htmlspecialchars($_POST['mobile'], ENT_QUOTES, 'UTF-8');
        $telephone = htmlspecialchars($_POST['telephone'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWarehouseExist = $this->warehouseModel->checkWarehouseExist($warehouseID);
        $total = $checkWarehouseExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->warehouseModel->updateWarehouse($warehouseID, $warehouseName, $address, $cityID, $companyID, $phone, $mobile, $telephone, $email, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'warehouseID' => $this->securityModel->encryptData($warehouseID)]);
            exit;
        } 
        else {
            $warehouseID = $this->warehouseModel->insertWarehouse($warehouseName, $address, $cityID, $companyID, $phone, $mobile, $telephone, $email, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'warehouseID' => $this->securityModel->encryptData($warehouseID)]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWarehouse
    # Description: 
    # Delete the warehouse if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteWarehouse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $warehouseID = htmlspecialchars($_POST['warehouse_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWarehouseExist = $this->warehouseModel->checkWarehouseExist($warehouseID);
        $total = $checkWarehouseExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->warehouseModel->deleteWarehouse($warehouseID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleWarehouse
    # Description: 
    # Delete the selected warehouses if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleWarehouse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $warehouseIDs = $_POST['warehouse_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($warehouseIDs as $warehouseID){
            $this->warehouseModel->deleteWarehouse($warehouseID);
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
    # Function: duplicateWarehouse
    # Description: 
    # Duplicates the warehouse if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateWarehouse() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $warehouseID = htmlspecialchars($_POST['warehouse_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkWarehouseExist = $this->warehouseModel->checkWarehouseExist($warehouseID);
        $total = $checkWarehouseExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $warehouseID = $this->warehouseModel->duplicateWarehouse($warehouseID, $userID);

        echo json_encode(['success' => true, 'warehouseID' =>  $this->securityModel->encryptData($warehouseID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWarehouseDetails
    # Description: 
    # Handles the retrieval of warehouse details such as warehouse name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getWarehouseDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['warehouse_id']) && !empty($_POST['warehouse_id'])) {
            $userID = $_SESSION['user_id'];
            $warehouseID = $_POST['warehouse_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $warehouseDetails = $this->warehouseModel->getWarehouse($warehouseID);
            $cityID = $warehouseDetails['city_id'] ?? null;
            $companyID = $warehouseDetails['company_id'] ?? null;

            $companyDetails = $this->companyModel->getCompany($companyID);
            $companyName = $companyDetails['company_name'] ?? null;

            $cityDetails = $this->cityModel->getCity($cityID);
            $cityName = $cityDetails['city_name'] ?? null;
            $stateID = $cityDetails['state_id'] ?? null;

            $stateDetails = $this->stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'] ?? null;
            $countryID = $stateDetails['country_id'] ?? null;

            $countryName = $this->countryModel->getCountry($countryID)['country_name'];
            $cityName = $cityName . ', ' . $stateName . ', ' . $countryName;

            $response = [
                'success' => true,
                'warehouseName' => $warehouseDetails['warehouse_name'],
                'address' => $warehouseDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'companyID' => $companyID,
                'companyName' => $companyName,
                'phone' => $warehouseDetails['phone'],
                'mobile' => $warehouseDetails['mobile'],
                'telephone' => $warehouseDetails['telephone'],
                'email' => $warehouseDetails['email']
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
require_once '../model/warehouse-model.php';
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new WarehouseController(new WarehouseModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>