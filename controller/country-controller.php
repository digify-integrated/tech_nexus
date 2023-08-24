<?php
session_start();

# -------------------------------------------------------------
#
# Function: CountryController
# Description: 
# The CountryController class handles country related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CountryController {
    private $countryModel;
    private $userModel;
    private $roleModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CountryModel, UserModel and SecurityModel instances.
    # These instances are used for country related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param roleModel $roleModel     The RoleModel instance for role related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CountryModel $countryModel, UserModel $userModel, RoleModel $roleModel, SecurityModel $securityModel) {
        $this->countryModel = $countryModel;
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
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
                case 'save country':
                    $this->saveCountry();
                    break;
                case 'get country details':
                    $this->getCountryDetails();
                    break;
                case 'delete country':
                    $this->deleteCountry();
                    break;
                case 'delete multiple country':
                    $this->deleteMultipleCountry();
                    break;
                case 'duplicate country':
                    $this->duplicateCountry();
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
    # Function: saveCountry
    # Description: 
    # Updates the existing country if it exists; otherwise, inserts a new country.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCountry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $countryID = isset($_POST['country_id']) ? htmlspecialchars($_POST['country_id'], ENT_QUOTES, 'UTF-8') : null;
        $countryName = htmlspecialchars($_POST['country_name'], ENT_QUOTES, 'UTF-8');
        $countryCode = htmlspecialchars($_POST['country_code'], ENT_QUOTES, 'UTF-8');
        $phoneCode = htmlspecialchars($_POST['phone_code'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCountryExist = $this->countryModel->checkCountryExist($countryID);
        $total = $checkCountryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->countryModel->updateCountry($countryID, $countryName, $countryCode, $phoneCode, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'countryID' => $this->securityModel->encryptData($countryID)]);
            exit;
        } 
        else {
            $countryID = $this->countryModel->insertCountry($countryName, $countryCode, $phoneCode, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'countryID' => $this->securityModel->encryptData($countryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCountry
    # Description: 
    # Delete the country if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCountry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $countryID = htmlspecialchars($_POST['country_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCountryExist = $this->countryModel->checkCountryExist($countryID);
        $total = $checkCountryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->countryModel->deleteLinkedCountry($countryID);
        $this->countryModel->deleteCountry($countryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCountry
    # Description: 
    # Delete the selected countrys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCountry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $countryIDs = $_POST['country_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($countryIDs as $countryID){
            $this->countryModel->deleteLinkedCountry($countryID);
            $this->countryModel->deleteCountry($countryID);
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
    # Function: duplicateCountry
    # Description: 
    # Duplicates the country if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCountry() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $countryID = htmlspecialchars($_POST['country_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCountryExist = $this->countryModel->checkCountryExist($countryID);
        $total = $checkCountryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $countryID = $this->countryModel->duplicateCountry($countryID, $userID);

        echo json_encode(['success' => true, 'countryID' =>  $this->securityModel->encryptData($countryID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCountryDetails
    # Description: 
    # Handles the retrieval of country details such as country name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCountryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['country_id']) && !empty($_POST['country_id'])) {
            $userID = $_SESSION['user_id'];
            $countryID = $_POST['country_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $countryDetails = $this->countryModel->getCountry($countryID);

            $response = [
                'success' => true,
                'countryName' => $countryDetails['country_name'],
                'countryCode' => $countryDetails['country_code'],
                'phoneCode' => $countryDetails['phone_code']
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
require_once '../model/country-model.php';
require_once '../model/role-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CountryController(new CountryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new RoleModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>