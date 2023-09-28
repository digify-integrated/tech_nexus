<?php
session_start();

# -------------------------------------------------------------
#
# Function: ContactInformationTypeController
# Description: 
# The ContactInformationTypeController class handles contact information type related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class ContactInformationTypeController {
    private $contactInformationTypeModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided ContactInformationTypeModel, UserModel and SecurityModel instances.
    # These instances are used for contact information type related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param ContactInformationTypeModel $contactInformationTypeModel     The ContactInformationTypeModel instance for contact information type related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(ContactInformationTypeModel $contactInformationTypeModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->contactInformationTypeModel = $contactInformationTypeModel;
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
                case 'save contact information type':
                    $this->saveContactInformationType();
                    break;
                case 'get contact information type details':
                    $this->getContactInformationTypeDetails();
                    break;
                case 'delete contact information type':
                    $this->deleteContactInformationType();
                    break;
                case 'delete multiple contact information type':
                    $this->deleteMultipleContactInformationType();
                    break;
                case 'duplicate contact information type':
                    $this->duplicateContactInformationType();
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
    # Function: saveContactInformationType
    # Description: 
    # Updates the existing contact information type if it exists; otherwise, inserts a new contact information type.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveContactInformationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationTypeID = isset($_POST['contact_information_type_id']) ? htmlspecialchars($_POST['contact_information_type_id'], ENT_QUOTES, 'UTF-8') : null;
        $contactInformationTypeName = htmlspecialchars($_POST['contact_information_type_name'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationTypeExist = $this->contactInformationTypeModel->checkContactInformationTypeExist($contactInformationTypeID);
        $total = $checkContactInformationTypeExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->contactInformationTypeModel->updateContactInformationType($contactInformationTypeID, $contactInformationTypeName, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'contactInformationTypeID' => $this->securityModel->encryptData($contactInformationTypeID)]);
            exit;
        } 
        else {
            $contactInformationTypeID = $this->contactInformationTypeModel->insertContactInformationType($contactInformationTypeName, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'contactInformationTypeID' => $this->securityModel->encryptData($contactInformationTypeID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteContactInformationType
    # Description: 
    # Delete the contact information type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteContactInformationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationTypeID = htmlspecialchars($_POST['contact_information_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationTypeExist = $this->contactInformationTypeModel->checkContactInformationTypeExist($contactInformationTypeID);
        $total = $checkContactInformationTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->contactInformationTypeModel->deleteContactInformationType($contactInformationTypeID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleContactInformationType
    # Description: 
    # Delete the selected contact information types if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleContactInformationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationTypeIDs = $_POST['contact_information_type_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($contactInformationTypeIDs as $contactInformationTypeID){
            $this->contactInformationTypeModel->deleteContactInformationType($contactInformationTypeID);
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
    # Function: duplicateContactInformationType
    # Description: 
    # Duplicates the contact information type if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function duplicateContactInformationType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $contactInformationTypeID = htmlspecialchars($_POST['contact_information_type_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkContactInformationTypeExist = $this->contactInformationTypeModel->checkContactInformationTypeExist($contactInformationTypeID);
        $total = $checkContactInformationTypeExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $contactInformationTypeID = $this->contactInformationTypeModel->duplicateContactInformationType($contactInformationTypeID, $userID);

        echo json_encode(['success' => true, 'contactInformationTypeID' =>  $this->securityModel->encryptData($contactInformationTypeID)]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getContactInformationTypeDetails
    # Description: 
    # Handles the retrieval of contact information type details such as contact information type name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getContactInformationTypeDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['contact_information_type_id']) && !empty($_POST['contact_information_type_id'])) {
            $userID = $_SESSION['user_id'];
            $contactInformationTypeID = $_POST['contact_information_type_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $contactInformationTypeDetails = $this->contactInformationTypeModel->getContactInformationType($contactInformationTypeID);

            $response = [
                'success' => true,
                'contactInformationTypeName' => $contactInformationTypeDetails['contact_information_type_name']
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
require_once '../model/contact-information-type-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new ContactInformationTypeController(new ContactInformationTypeModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>