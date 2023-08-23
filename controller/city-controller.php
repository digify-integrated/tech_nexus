<?php
session_start();

# -------------------------------------------------------------
#
# Function: CityController
# Description: 
# The CityController class handles city related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CityController {
    private $cityModel;
    private $stateModel;
    private $countryModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CityModel, UserModel and SecurityModel instances.
    # These instances are used for city related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CityModel $cityModel     The CityModel instance for city related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param RoleModel $roleModel     The RoleModel instance for role related operations.
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CityModel $cityModel, UserModel $userModel, RoleModel $roleModel, StateModel $stateModel, CountryModel $countryModel, SecurityModel $securityModel) {
        $this->cityModel = $cityModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->stateModel = $stateModel;
        $this->countryModel = $countryModel;
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
                case 'save city':
                    $this->saveCity();
                    break;
                case 'get city details':
                    $this->getCityDetails();
                    break;
                case 'delete city':
                    $this->deleteCity();
                    break;
                case 'delete multiple city':
                    $this->deleteMultipleCity();
                    break;
                case 'duplicate city':
                    $this->duplicateCity();
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
    # Function: saveCity
    # Description: 
    # Updates the existing city if it exists; otherwise, inserts a new city.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCity() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cityID = isset($_POST['city_id']) ? htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8') : null;
        $cityName = htmlspecialchars($_POST['city_name'], ENT_QUOTES, 'UTF-8');
        $stateID = htmlspecialchars($_POST['state_id'], ENT_QUOTES, 'UTF-8');

        $stateDetails = $this->stateModel->getState($stateID);
        $countryID = $stateDetails['country_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCityExist = $this->cityModel->checkCityExist($cityID);
        $total = $checkCityExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->cityModel->updateCity($cityID, $cityName, $stateID, $countryID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'cityID' => $this->securityModel->encryptData($cityID)]);
            exit;
        } 
        else {
            $cityID = $this->cityModel->insertCity($cityName, $stateID, $countryID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'cityID' => $this->securityModel->encryptData($cityID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCity
    # Description: 
    # Delete the city if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCity() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCityExist = $this->cityModel->checkCityExist($cityID);
        $total = $checkCityExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->cityModel->deleteCity($cityID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCity
    # Description: 
    # Delete the selected citys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCity() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cityIDs = $_POST['city_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($cityIDs as $cityID){
            $this->cityModel->deleteCity($cityID);
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
    # Function: duplicateCity
    # Description: 
    # Duplicates the city if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCity() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCityExist = $this->cityModel->checkCityExist($cityID);
        $total = $checkCityExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $cityID = $this->cityModel->duplicateCity($cityID, $userID);

        echo json_encode(['success' => true, 'cityID' =>  $this->securityModel->encryptData($cityID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCityDetails
    # Description: 
    # Handles the retrieval of city details such as city name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCityDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['city_id']) && !empty($_POST['city_id'])) {
            $userID = $_SESSION['user_id'];
            $cityID = $_POST['city_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $cityDetails = $this->cityModel->getCity($cityID);
            $countryID = $cityDetails['country_id'];
            $stateID = $cityDetails['state_id'];

            $stateDetails = $this->stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'];

            $countryDetails = $this->countryModel->getCountry($countryID);
            $countryName = $countryDetails['country_name'];

            $response = [
                'success' => true,
                'cityName' => $cityDetails['city_name'],
                'stateID' => $stateID,
                'stateName' => $stateName . ', ' . $countryName
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
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CityController(new CityModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>