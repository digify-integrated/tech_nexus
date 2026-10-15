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
                case 'save coverage':
                    $this->saveCoverage();
                    break;
                case 'get insurance provider details':
                    $this->getInsuranceProviderDetails();
                    break;
                case 'get coverage details':
                    $this->getCoverageDetails();
                    break;
                case 'delete insurance provider':
                    $this->deleteInsuranceProvider();
                    break;
                case 'delete multiple insurance provider':
                    $this->deleteMultipleInsuranceProvider();
                    break;
                case 'delete coverage':
                    $this->deleteCoverage();
                    break;
                case 'generate category rate form':
                    $provider_id = $_POST['provider_id'] ?? null;
                    if (!$provider_id) {
                        echo json_encode(['success' => false, 'message' => 'Provider ID is required']);
                        exit;
                    }

                    $categories = $this->insuranceProviderModel->getCategoryRatesByProvider($provider_id);
                    
                    echo json_encode([
                        'success' => true,
                        'categories' => $categories
                    ]);
                    exit;
                    break;

                case 'save category rates':
                    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                        echo json_encode(['success' => false, 'isInactive' => true]);
                        exit;
                    }
                    $userID = $_SESSION['user_id'];

                    $provider_id = $_POST['insurance_provider_id'] ?? null;
                    $rates = $_POST['rates'] ?? []; // Array of [category_id => ['insurance_rate' => x, 'commission_rate' => y]]

                    if (!$provider_id || empty($rates)) {
                        echo json_encode(['success' => false, 'message' => 'No rates provided to save.']);
                        exit;
                    }

                    foreach ($rates as $category_id => $values) {
                        $insurance_rate = (float)($values['insurance_rate'] ?? 0);
                        $commission_rate = (float)($values['commission_rate'] ?? 0);

                        // Basic validation: ensure rates aren't negative
                        if ($insurance_rate < 0 || $commission_rate < 0) {
                            echo json_encode(['success' => false, 'message' => 'Rates cannot be negative.']);
                            exit;
                        }

                        $this->insuranceProviderModel->saveCategoryRate(
                            $provider_id, 
                            $category_id, 
                            $insurance_rate, 
                            $commission_rate, 
                            $userID
                        );
                    }

                    echo json_encode(['success' => true]);
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
        $local_government_tax_rate = htmlspecialchars($_POST['local_government_tax_rate'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkProviderExist = $this->insuranceProviderModel->checkInsuranceProviderExist($providerID);
        $total = $checkProviderExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->insuranceProviderModel->updateInsuranceProvider($providerID, $providerName, $address, $cityID, $taxId, $currencyID, $mobile, $telephone, $email, $website, $local_government_tax_rate, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'providerID' => $this->securityModel->encryptData($providerID)]);
            exit;
        } 
        else {
            $providerID = $this->insuranceProviderModel->insertInsuranceProvider($providerName, $address, $cityID, $taxId, $currencyID, $mobile, $telephone, $email, $website, $local_government_tax_rate, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'providerID' => $this->securityModel->encryptData($providerID)]);
            exit;
        }
    }

    public function saveCoverage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $insurance_coverage_rate_id = isset($_POST['insurance_coverage_rate_id']) ? htmlspecialchars($_POST['insurance_coverage_rate_id'], ENT_QUOTES, 'UTF-8') : null;
        $insurance_provider_id = $_POST['insurance_provider_id'] ?? null;
        $coverage_amount_start = $_POST['coverage_amount_start'] ?? null;
        $coverage_amount_end = $_POST['coverage_amount_end'] ?? null;
        $bi_rate = $_POST['bi_rate'] ?? null;
        $pd_amount = $_POST['pd_amount'] ?? null;

        // --- Start & End Validation ---
        if (!is_numeric($coverage_amount_start) || !is_numeric($coverage_amount_end)) {
            echo json_encode(['success' => false, 'message' => 'Coverage start and end amounts must be valid numbers.']);
            exit;
        }

        $start = (float) $coverage_amount_start;
        $end = (float) $coverage_amount_end;

        // Prevents start from being greater than or equal to end (e.g. start: 100, end: 12)
        if ($start >= $end) {
            echo json_encode(['success' => false, 'message' => 'Start coverage amount cannot be greater than or equal to the end coverage amount.']);
            exit;
        }
        // ------------------------------

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $checkCoverageExist = $this->insuranceProviderModel->checkCoverageExist($insurance_coverage_rate_id);
        $total = $checkCoverageExist['total'] ?? 0;

        if ($total > 0) {
            $this->insuranceProviderModel->updateCoverage($insurance_coverage_rate_id, $insurance_provider_id, $start, $end, $bi_rate, $pd_amount, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->insuranceProviderModel->insertCoverage($insurance_provider_id, $start, $end, $bi_rate, $pd_amount, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
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

    public function deleteCoverage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $insurance_coverage_rate_id = isset($_POST['insurance_coverage_rate_id']) ? htmlspecialchars($_POST['insurance_coverage_rate_id'], ENT_QUOTES, 'UTF-8') : null;
       
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $this->insuranceProviderModel->deleteCoverage($insurance_coverage_rate_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    
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
                'website' => $providerDetails['website'],
                'localGovernmentTaxRate' => $providerDetails['local_government_tax_rate'],
            ];

            echo json_encode($response);
            exit;
        }
    }

     public function getCoverageDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['insurance_coverage_rate_id']) && !empty($_POST['insurance_coverage_rate_id'])) {
            $userID = $_SESSION['user_id'];
            $insurance_coverage_rate_id = $_POST['insurance_coverage_rate_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $coverageDetails = $this->insuranceProviderModel->getCoverage($insurance_coverage_rate_id);

            $response = [
                'success' => true,
                'coverage_amount_start' => $coverageDetails['coverage_amount_start'] ?? 0,
                'coverage_amount_end' => $coverageDetails['coverage_amount_end'] ?? 0,
                'bi_rate' => $coverageDetails['bi_rate'] ?? 0,
                'pd_amount' => $coverageDetails['pd_amount'] ?? 0,
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