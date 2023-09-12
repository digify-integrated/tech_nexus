<?php
session_start();

# -------------------------------------------------------------
#
# Function: DepartureReasonController
# Description: 
# The DepartureReasonController class handles departure reason related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class DepartureReasonController {
    private $departureReasonModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided DepartureReasonModel, UserModel and SecurityModel instances.
    # These instances are used for departure reason related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param DepartureReasonModel $departureReasonModel     The DepartureReasonModel instance for departure reason related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(DepartureReasonModel $departureReasonModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->departureReasonModel = $departureReasonModel;
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
                case 'save departure reason':
                    $this->saveDepartureReason();
                    break;
                case 'get departure reason details':
                    $this->getDepartureReasonDetails();
                    break;
                case 'delete departure reason':
                    $this->deleteDepartureReason();
                    break;
                case 'delete multiple departure reason':
                    $this->deleteMultipleDepartureReason();
                    break;
                case 'duplicate departure reason':
                    $this->duplicateDepartureReason();
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
    # Function: saveDepartureReason
    # Description: 
    # Updates the existing departure reason if it exists; otherwise, inserts a new departure reason.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveDepartureReason() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departureReasonID = isset($_POST['departure_reason_id']) ? htmlspecialchars($_POST['departure_reason_id'], ENT_QUOTES, 'UTF-8') : null;
        $departureReasonName = htmlspecialchars($_POST['departure_reason_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartureReasonExist = $this->departureReasonModel->checkDepartureReasonExist($departureReasonID);
        $total = $checkDepartureReasonExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->departureReasonModel->updateDepartureReason($departureReasonID, $departureReasonName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'departureReasonID' => $this->securityModel->encryptData($departureReasonID)]);
            exit;
        } 
        else {
            $departureReasonID = $this->departureReasonModel->insertDepartureReason($departureReasonName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'departureReasonID' => $this->securityModel->encryptData($departureReasonID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDepartureReason
    # Description: 
    # Delete the departure reason if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteDepartureReason() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departureReasonID = htmlspecialchars($_POST['departure_reason_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartureReasonExist = $this->departureReasonModel->checkDepartureReasonExist($departureReasonID);
        $total = $checkDepartureReasonExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->departureReasonModel->deleteDepartureReason($departureReasonID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleDepartureReason
    # Description: 
    # Delete the selected departure reasons if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleDepartureReason() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departureReasonIDs = $_POST['departure_reason_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($departureReasonIDs as $departureReasonID){
            $this->departureReasonModel->deleteDepartureReason($departureReasonID);
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
    # Function: duplicateDepartureReason
    # Description: 
    # Duplicates the departure reason if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateDepartureReason() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $departureReasonID = htmlspecialchars($_POST['departure_reason_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkDepartureReasonExist = $this->departureReasonModel->checkDepartureReasonExist($departureReasonID);
        $total = $checkDepartureReasonExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $departureReasonID = $this->departureReasonModel->duplicateDepartureReason($departureReasonID, $userID);

        echo json_encode(['success' => true, 'departureReasonID' =>  $this->securityModel->encryptData($departureReasonID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDepartureReasonDetails
    # Description: 
    # Handles the retrieval of departure reason details such as departure reason name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getDepartureReasonDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['departure_reason_id']) && !empty($_POST['departure_reason_id'])) {
            $userID = $_SESSION['user_id'];
            $departureReasonID = $_POST['departure_reason_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $departureReasonDetails = $this->departureReasonModel->getDepartureReason($departureReasonID);

            $response = [
                'success' => true,
                'departureReasonName' => $departureReasonDetails['departure_reason_name']
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
require_once '../model/departure-reason-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new DepartureReasonController(new DepartureReasonModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>