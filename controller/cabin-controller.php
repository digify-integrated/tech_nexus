<?php
session_start();

# -------------------------------------------------------------
#
# Function: CabinController
# Description: 
# The CabinController class handles cabin related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CabinController {
    private $cabinModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CabinModel, UserModel and SecurityModel instances.
    # These instances are used for cabin related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CabinModel $cabinModel     The CabinModel instance for cabin related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CabinModel $cabinModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->cabinModel = $cabinModel;
        $this->userModel = $userModel;
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
                case 'save cabin':
                    $this->saveCabin();
                    break;
                case 'get cabin details':
                    $this->getCabinDetails();
                    break;
                case 'delete cabin':
                    $this->deleteCabin();
                    break;
                case 'delete multiple cabin':
                    $this->deleteMultipleCabin();
                    break;
                case 'duplicate cabin':
                    $this->duplicateCabin();
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
    # Function: saveCabin
    # Description: 
    # Updates the existing cabin if it exists; otherwise, inserts a new cabin.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCabin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cabinID = isset($_POST['cabin_id']) ? htmlspecialchars($_POST['cabin_id'], ENT_QUOTES, 'UTF-8') : null;
        $cabinName = htmlspecialchars($_POST['cabin_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCabinExist = $this->cabinModel->checkCabinExist($cabinID);
        $total = $checkCabinExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->cabinModel->updateCabin($cabinID, $cabinName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'cabinID' => $this->securityModel->encryptData($cabinID)]);
            exit;
        } 
        else {
            $cabinID = $this->cabinModel->insertCabin($cabinName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'cabinID' => $this->securityModel->encryptData($cabinID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCabin
    # Description: 
    # Delete the cabin if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteCabin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cabinID = htmlspecialchars($_POST['cabin_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCabinExist = $this->cabinModel->checkCabinExist($cabinID);
        $total = $checkCabinExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->cabinModel->deleteCabin($cabinID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleCabin
    # Description: 
    # Delete the selected cabins if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleCabin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cabinIDs = $_POST['cabin_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($cabinIDs as $cabinID){
            $this->cabinModel->deleteCabin($cabinID);
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
    # Function: duplicateCabin
    # Description: 
    # Duplicates the cabin if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateCabin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $cabinID = htmlspecialchars($_POST['cabin_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCabinExist = $this->cabinModel->checkCabinExist($cabinID);
        $total = $checkCabinExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $cabinID = $this->cabinModel->duplicateCabin($cabinID, $userID);

        echo json_encode(['success' => true, 'cabinID' =>  $this->securityModel->encryptData($cabinID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCabinDetails
    # Description: 
    # Handles the retrieval of cabin details such as cabin name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCabinDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['cabin_id']) && !empty($_POST['cabin_id'])) {
            $userID = $_SESSION['user_id'];
            $cabinID = $_POST['cabin_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $cabinDetails = $this->cabinModel->getCabin($cabinID);

            $response = [
                'success' => true,
                'cabinName' => $cabinDetails['cabin_name']
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
require_once '../model/cabin-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CabinController(new CabinModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>