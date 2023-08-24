<?php
session_start();

# -------------------------------------------------------------
#
# Function: DistrictController
# Description: 
# The DistrictController class handles district related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DistrictController {
    private $districtModel;
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
    # The constructor initializes the object with the provided DistrictModel, UserModel and SecurityModel instances.
    # These instances are used for district related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DistrictModel $districtModel     The DistrictModel instance for district related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param RoleModel $roleModel     The RoleModel instance for role related operations.
    # - @param CityModel $stateModel     The CityModel instance for city related operations.
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DistrictModel $districtModel, UserModel $userModel, RoleModel $roleModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, SecurityModel $securityModel) {
        $this->districtModel = $districtModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
        $this->cityModel = $cityModel;
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
                case 'save district':
                    $this->saveDistrict();
                    break;
                case 'get district details':
                    $this->getDistrictDetails();
                    break;
                case 'delete district':
                    $this->deleteDistrict();
                    break;
                case 'delete multiple district':
                    $this->deleteMultipleDistrict();
                    break;
                case 'duplicate district':
                    $this->duplicateDistrict();
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
    # Function: saveDistrict
    # Description: 
    # Updates the existing district if it exists; otherwise, inserts a new district.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDistrict() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $districtID = isset($_POST['district_id']) ? htmlspecialchars($_POST['district_id'], ENT_QUOTES, 'UTF-8') : null;
        $districtName = htmlspecialchars($_POST['district_name'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');

        $cityDetails = $this->cityModel->getCity($cityID);
        $stateID = $cityDetails['state_id'];
        $countryID = $cityDetails['country_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDistrictExist = $this->districtModel->checkDistrictExist($districtID);
        $total = $checkDistrictExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->districtModel->updateDistrict($districtID, $districtName, $cityID, $stateID, $countryID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'districtID' => $this->securityModel->encryptData($districtID)]);
            exit;
        } 
        else {
            $districtID = $this->districtModel->insertDistrict($districtName, $cityID, $stateID, $countryID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'districtID' => $this->securityModel->encryptData($districtID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDistrict
    # Description: 
    # Delete the district if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDistrict() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $districtID = htmlspecialchars($_POST['district_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDistrictExist = $this->districtModel->checkDistrictExist($districtID);
        $total = $checkDistrictExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->districtModel->deleteDistrict($districtID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDistrict
    # Description: 
    # Delete the selected districts if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDistrict() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $districtIDs = $_POST['district_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($districtIDs as $districtID){
            $this->districtModel->deleteDistrict($districtID);
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
    # Function: duplicateDistrict
    # Description: 
    # Duplicates the district if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateDistrict() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $districtID = htmlspecialchars($_POST['district_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDistrictExist = $this->districtModel->checkDistrictExist($districtID);
        $total = $checkDistrictExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $districtID = $this->districtModel->duplicateDistrict($districtID, $userID);

        echo json_encode(['success' => true, 'districtID' =>  $this->securityModel->encryptData($districtID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDistrictDetails
    # Description: 
    # Handles the retrieval of district details such as district name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDistrictDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['district_id']) && !empty($_POST['district_id'])) {
            $userID = $_SESSION['user_id'];
            $districtID = $_POST['district_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $districtDetails = $this->districtModel->getDistrict($districtID);
            $cityID = $districtDetails['city_id'];
            $stateID = $districtDetails['state_id'];
            $countryID = $districtDetails['country_id'];

            $cityDetails = $this->cityModel->getCity($cityID);
            $cityName = $cityDetails['city_name'];

            $stateDetails = $this->stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'] ?? null;

            $countryDetails = $this->countryModel->getCountry($countryID);
            $countryName = $countryDetails['country_name'] ?? null;

            $response = [
                'success' => true,
                'districtName' => $districtDetails['district_name'],
                'cityID' => $cityID,
                'cityName' => $cityName . ', ' . $stateName . ', ' . $countryName
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
require_once '../model/district-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DistrictController(new DistrictModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>