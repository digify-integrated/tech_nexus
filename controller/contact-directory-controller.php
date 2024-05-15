<?php
session_start();

# -------------------------------------------------------------
#
# Function: ContactDirectoryController
# Description: 
# The ContactDirectoryController class handles contact directory related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ContactDirectoryController {
    private $contactDirectoryModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ContactDirectoryModel, UserModel and SecurityModel instances.
    # These instances are used for contact directory related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ContactDirectoryModel $contactDirectoryModel     The ContactDirectoryModel instance for contact directory related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ContactDirectoryModel $contactDirectoryModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->contactDirectoryModel = $contactDirectoryModel;
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
                case 'save contact directory':
                    $this->saveContactDirectory();
                    break;
                case 'get contact directory details':
                    $this->getContactDirectoryDetails();
                    break;
                case 'delete contact directory':
                    $this->deleteContactDirectory();
                    break;
                case 'delete multiple contact directory':
                    $this->deleteMultipleContactDirectory();
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
    # Function: saveContactDirectory
    # Description: 
    # Updates the existing contact directory if it exists; otherwise, inserts a new contact directory.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactDirectory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactDirectoryID = isset($_POST['contact_directory_id']) ? htmlspecialchars($_POST['contact_directory_id'], ENT_QUOTES, 'UTF-8') : null;
        $contactName = htmlspecialchars($_POST['contact_name'], ENT_QUOTES, 'UTF-8');
        $position = htmlspecialchars($_POST['position'], ENT_QUOTES, 'UTF-8');
        $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
        $directoryType = htmlspecialchars($_POST['directory_type'], ENT_QUOTES, 'UTF-8');
        $contactInformation = htmlspecialchars($_POST['contact_information'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactDirectoryExist = $this->contactDirectoryModel->checkContactDirectoryExist($contactDirectoryID);
        $total = $checkContactDirectoryExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->contactDirectoryModel->updateContactDirectory($contactDirectoryID, $contactName, $position, $location, $directoryType, $contactInformation, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'contactDirectoryID' => $this->securityModel->encryptData($contactDirectoryID)]);
            exit;
        } 
        else {
            $contactDirectoryID = $this->contactDirectoryModel->insertContactDirectory($contactName, $position, $location, $directoryType, $contactInformation, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'contactDirectoryID' => $this->securityModel->encryptData($contactDirectoryID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactDirectory
    # Description: 
    # Delete the contact directory if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactDirectory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactDirectoryID = htmlspecialchars($_POST['contact_directory_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactDirectoryExist = $this->contactDirectoryModel->checkContactDirectoryExist($contactDirectoryID);
        $total = $checkContactDirectoryExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->contactDirectoryModel->deleteContactDirectory($contactDirectoryID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleContactDirectory
    # Description: 
    # Delete the selected contact directorys if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleContactDirectory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactDirectoryIDs = $_POST['contact_directory_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($contactDirectoryIDs as $contactDirectoryID){
            $this->contactDirectoryModel->deleteContactDirectory($contactDirectoryID);
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
    # Function: getContactDirectoryDetails
    # Description: 
    # Handles the retrieval of contact directory details such as contact directory name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactDirectoryDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_directory_id']) && !empty($_POST['contact_directory_id'])) {
            $userID = $_SESSION['user_id'];
            $contactDirectoryID = $_POST['contact_directory_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactDirectoryDetails = $this->contactDirectoryModel->getContactDirectory($contactDirectoryID);

            $response = [
                'success' => true,
                'contactName' => $contactDirectoryDetails['contact_name'],
                'position' => $contactDirectoryDetails['position'],
                'location' => $contactDirectoryDetails['location'],
                'directoryType' => $contactDirectoryDetails['directory_type'],
                'contactInformation' => $contactDirectoryDetails['contact_information']
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
require_once '../model/contact-directory-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ContactDirectoryController(new ContactDirectoryModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>