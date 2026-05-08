<?php
session_start();

class CityController {
    private $cityModel;
    private $stateModel;
    private $countryModel;
    private $userModel;
    private $securityModel;

    public function __construct(CityModel $cityModel, UserModel $userModel,  StateModel $stateModel, CountryModel $countryModel, SecurityModel $securityModel) {
        $this->cityModel = $cityModel;
        $this->userModel = $userModel;
        $this->stateModel = $stateModel;
        $this->countryModel = $countryModel;
        $this->securityModel = $securityModel;
    }

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
    
    public function saveCity() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cityID = isset($_POST['city_id']) ? htmlspecialchars($_POST['city_id'], ENT_QUOTES, 'UTF-8') : null;
        $cityName = htmlspecialchars($_POST['city_name'], ENT_QUOTES, 'UTF-8');
        $stateID = htmlspecialchars($_POST['state_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCityExist = $this->cityModel->checkCityExist($cityID);
        $total = $checkCityExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->cityModel->updateCity($cityID, $cityName, $stateID, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'cityID' => $this->securityModel->encryptData($cityID)]);
            exit;
        } 
        else {
            $cityID = $this->cityModel->insertCity($cityName, $stateID, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'cityID' => $this->securityModel->encryptData($cityID)]);
            exit;
        }
    }
    
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
            $stateID = $cityDetails['state_id'];

            $stateDetails = $this->stateModel->getState($stateID);
            $countryID = $stateDetails['country_id'];
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
}

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CityController(new CityModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new StateModel(new DatabaseModel), new CountryModel(new DatabaseModel), new SecurityModel());
$controller->handleRequest();
?>