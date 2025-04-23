<?php
session_start();

# -------------------------------------------------------------
#
# Function: MiscellaneousClientController
# Description: 
# The MiscellaneousClientController class handles miscellaneous client related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class MiscellaneousClientController {
    private $miscellaneousClientModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided MiscellaneousClientModel, UserModel and SecurityModel instances.
    # These instances are used for miscellaneous client related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param MiscellaneousClientModel $miscellaneousClientModel     The MiscellaneousClientModel instance for miscellaneous client related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(MiscellaneousClientModel $miscellaneousClientModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->miscellaneousClientModel = $miscellaneousClientModel;
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
                case 'save miscellaneous client':
                    $this->saveMiscellaneousClient();
                    break;
                case 'get miscellaneous client details':
                    $this->getMiscellaneousClientDetails();
                    break;
                case 'delete miscellaneous client':
                    $this->deleteMiscellaneousClient();
                    break;
                case 'delete multiple miscellaneous client':
                    $this->deleteMultipleMiscellaneousClient();
                    break;
                case 'duplicate miscellaneous client':
                    $this->duplicateMiscellaneousClient();
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
    # Function: saveMiscellaneousClient
    # Description: 
    # Updates the existing miscellaneous client if it exists; otherwise, inserts a new miscellaneous client.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveMiscellaneousClient() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $miscellaneousClientID = isset($_POST['miscellaneous_client_id']) ? htmlspecialchars($_POST['miscellaneous_client_id'], ENT_QUOTES, 'UTF-8') : null;
        $miscellaneousClientName = $_POST['client_name'];
        $tin = htmlspecialchars($_POST['tin'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMiscellaneousClientExist = $this->miscellaneousClientModel->checkMiscellaneousClientExist($miscellaneousClientID);
        $total = $checkMiscellaneousClientExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->miscellaneousClientModel->updateMiscellaneousClient($miscellaneousClientID, $miscellaneousClientName, $address, $tin, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'miscellaneousClientID' => $this->securityModel->encryptData($miscellaneousClientID)]);
            exit;
        } 
        else {
            $miscellaneousClientID = $this->miscellaneousClientModel->insertMiscellaneousClient($miscellaneousClientName, $address, $tin, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'miscellaneousClientID' => $this->securityModel->encryptData($miscellaneousClientID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMiscellaneousClient
    # Description: 
    # Delete the miscellaneous client if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMiscellaneousClient() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $miscellaneousClientID = htmlspecialchars($_POST['miscellaneous_client_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMiscellaneousClientExist = $this->miscellaneousClientModel->checkMiscellaneousClientExist($miscellaneousClientID);
        $total = $checkMiscellaneousClientExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->miscellaneousClientModel->deleteMiscellaneousClient($miscellaneousClientID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleMiscellaneousClient
    # Description: 
    # Delete the selected miscellaneous clients if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleMiscellaneousClient() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $miscellaneousClientIDs = $_POST['miscellaneous_client_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($miscellaneousClientIDs as $miscellaneousClientID){
            $this->miscellaneousClientModel->deleteMiscellaneousClient($miscellaneousClientID);
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
    # Function: duplicateMiscellaneousClient
    # Description: 
    # Duplicates the miscellaneous client if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateMiscellaneousClient() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $miscellaneousClientID = htmlspecialchars($_POST['miscellaneous_client_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkMiscellaneousClientExist = $this->miscellaneousClientModel->checkMiscellaneousClientExist($miscellaneousClientID);
        $total = $checkMiscellaneousClientExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $miscellaneousClientID = $this->miscellaneousClientModel->duplicateMiscellaneousClient($miscellaneousClientID, $userID);

        echo json_encode(['success' => true, 'miscellaneousClientID' =>  $this->securityModel->encryptData($miscellaneousClientID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMiscellaneousClientDetails
    # Description: 
    # Handles the retrieval of miscellaneous client details such as miscellaneous client name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getMiscellaneousClientDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['miscellaneous_client_id']) && !empty($_POST['miscellaneous_client_id'])) {
            $userID = $_SESSION['user_id'];
            $miscellaneousClientID = $_POST['miscellaneous_client_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $miscellaneousClientDetails = $this->miscellaneousClientModel->getMiscellaneousClient($miscellaneousClientID);

            $response = [
                'success' => true,
                'clientName' => $miscellaneousClientDetails['client_name'],
                'tin' => $miscellaneousClientDetails['tin'],
                'address' => $miscellaneousClientDetails['address']
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
require_once '../model/miscellaneous-client-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new MiscellaneousClientController(new MiscellaneousClientModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>