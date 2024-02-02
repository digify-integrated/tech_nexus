<?php
session_start();

# -------------------------------------------------------------
#
# Function: TransmittalController
# Description: 
# The TransmittalController class handles transmittal related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class TransmittalController {
    private $transmittalModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided TransmittalModel, UserModel and SecurityModel instances.
    # These instances are used for transmittal related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param TransmittalModel $transmittalModel     The TransmittalModel instance for transmittal related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(TransmittalModel $transmittalModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->transmittalModel = $transmittalModel;
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
                case 'save transmittal':
                    $this->saveTransmittal();
                    break;
                case 'get transmittal details':
                    $this->getTransmittalDetails();
                    break;
                case 'delete transmittal':
                    $this->deleteTransmittal();
                    break;
                case 'delete multiple transmittal':
                    $this->deleteMultipleTransmittal();
                    break;
                case 'duplicate transmittal':
                    $this->duplicateTransmittal();
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
    # Function: saveTransmittal
    # Description: 
    # Updates the existing transmittal if it exists; otherwise, inserts a new transmittal.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = isset($_POST['transmittal_id']) ? htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8') : null;
        $transmittalName = htmlspecialchars($_POST['transmittal_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->transmittalModel->updateTransmittal($transmittalID, $transmittalName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'transmittalID' => $this->securityModel->encryptData($transmittalID)]);
            exit;
        } 
        else {
            $transmittalID = $this->transmittalModel->insertTransmittal($transmittalName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'transmittalID' => $this->securityModel->encryptData($transmittalID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTransmittal
    # Description: 
    # Delete the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->transmittalModel->deleteTransmittal($transmittalID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleTransmittal
    # Description: 
    # Delete the selected transmittals if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalIDs = $_POST['transmittal_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($transmittalIDs as $transmittalID){
            $this->transmittalModel->deleteTransmittal($transmittalID);
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
    # Function: duplicateTransmittal
    # Description: 
    # Duplicates the transmittal if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateTransmittal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transmittalID = htmlspecialchars($_POST['transmittal_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTransmittalExist = $this->transmittalModel->checkTransmittalExist($transmittalID);
        $total = $checkTransmittalExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $transmittalID = $this->transmittalModel->duplicateTransmittal($transmittalID, $userID);

        echo json_encode(['success' => true, 'transmittalID' =>  $this->securityModel->encryptData($transmittalID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTransmittalDetails
    # Description: 
    # Handles the retrieval of transmittal details such as transmittal name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getTransmittalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['transmittal_id']) && !empty($_POST['transmittal_id'])) {
            $userID = $_SESSION['user_id'];
            $transmittalID = $_POST['transmittal_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $transmittalDetails = $this->transmittalModel->getTransmittal($transmittalID);

            $response = [
                'success' => true,
                'transmittalName' => $transmittalDetails['transmittal_name']
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
require_once '../model/transmittal-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new TransmittalController(new TransmittalModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>