<?php
session_start();

# -------------------------------------------------------------
#
# Function: BranchController
# Description: 
# The BranchController class handles branch related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class BranchController {
    private $branchModel;
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
    # The constructor initializes the object with the provided BranchModel, UserModel and SecurityModel instances.
    # These instances are used for branch related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param BranchModel $branchModel     The BranchModel instance for branch related operations.
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
    public function __construct(BranchModel $branchModel, UserModel $userModel, CompanyModel $companyModel, CityModel $cityModel, StateModel $stateModel, CountryModel $countryModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->branchModel = $branchModel;
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
                case 'save branch':
                    $this->saveBranch();
                    break;
                case 'get branch details':
                    $this->getBranchDetails();
                    break;
                case 'delete branch':
                    $this->deleteBranch();
                    break;
                case 'delete multiple branch':
                    $this->deleteMultipleBranch();
                    break;
                case 'duplicate branch':
                    $this->duplicateBranch();
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
    # Function: saveBranch
    # Description: 
    # Updates the existing branch if it exists; otherwise, inserts a new branch.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveBranch() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $branchID = isset($_POST['branch_id']) ? htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8') : null;
        $branchName = htmlspecialchars($_POST['branch_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $cityID = htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8');
        $companyID = htmlspecialchars($_POST['company_id'], ENT_QUOTES, 'UTF-8');
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
    
        $checkBranchExist = $this->branchModel->checkBranchExist($branchID);
        $total = $checkBranchExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->branchModel->updateBranch($branchID, $branchName, $address, $cityID, $companyID, $phone, $mobile, $telephone, $email, $website, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'branchID' => $this->securityModel->encryptData($branchID)]);
            exit;
        } 
        else {
            $branchID = $this->branchModel->insertBranch($branchName, $address, $cityID, $companyID, $phone, $mobile, $telephone, $email, $website, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'branchID' => $this->securityModel->encryptData($branchID)]);
            exit;
        }
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteBranch
    # Description: 
    # Delete the branch if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteBranch() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $branchID = htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBranchExist = $this->branchModel->checkBranchExist($branchID);
        $total = $checkBranchExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->branchModel->deleteBranch($branchID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleBranch
    # Description: 
    # Delete the selected branchs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleBranch() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $branchIDs = $_POST['branch_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($branchIDs as $branchID){
            $this->branchModel->deleteBranch($branchID);
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
    # Function: duplicateBranch
    # Description: 
    # Duplicates the branch if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateBranch() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $branchID = htmlspecialchars($_POST['branch_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkBranchExist = $this->branchModel->checkBranchExist($branchID);
        $total = $checkBranchExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $branchID = $this->branchModel->duplicateBranch($branchID, $userID);

        echo json_encode(['success' => true, 'branchID' =>  $this->securityModel->encryptData($branchID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getBranchDetails
    # Description: 
    # Handles the retrieval of branch details such as branch name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getBranchDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['branch_id']) && !empty($_POST['branch_id'])) {
            $userID = $_SESSION['user_id'];
            $branchID = $_POST['branch_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $branchDetails = $this->branchModel->getBranch($branchID);
            $cityID = $branchDetails['city_id'] ?? null;
            $companyID = $branchDetails['company_id'] ?? null;

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
                'branchName' => $branchDetails['branch_name'],
                'address' => $branchDetails['address'],
                'cityID' => $cityID,
                'cityName' => $cityName,
                'companyID' => $companyID,
                'companyName' => $companyName,
                'phone' => $branchDetails['phone'],
                'mobile' => $branchDetails['mobile'],
                'telephone' => $branchDetails['telephone'],
                'email' => $branchDetails['email'],
                'website' => $branchDetails['website']
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
require_once '../model/branch-model.php';
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new BranchController(new BranchModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new CompanyModel(new DatabaseModel), new CityModel(new DatabaseModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>