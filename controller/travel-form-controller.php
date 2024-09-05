<?php
session_start();

# -------------------------------------------------------------
#
# Function: TravelFormController
# Description: 
# The TravelFormController class handles travel form related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class TravelFormController {
    private $travelFormModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided TravelFormModel, UserModel and SecurityModel instances.
    # These instances are used for travel form related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param TravelFormModel $travelFormModel     The TravelFormModel instance for travel form related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(TravelFormModel $travelFormModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->travelFormModel = $travelFormModel;
        $this->userModel = $userModel;
        $this->systemModel = $systemModel;
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
                case 'save travel form':
                    $this->saveTravelForm();
                    break;
                case 'save travel form':
                    $this->saveTravelForm();
                    break;
                case 'save travel authorization':
                    $this->saveTravelAuthorization();
                    break;
                case 'save gate pass':
                    $this->saveGatePass();
                    break;
                case 'get travel form details':
                    $this->getTravelFormDetails();
                    break;
                case 'get travel authorization details':
                    $this->getTravelAuthorizationDetails();
                    break;
                case 'get gate pass details':
                    $this->getGatePassDetails();
                    break;
                case 'delete travel form':
                    $this->deleteTravelForm();
                    break;
                case 'delete multiple travel form':
                    $this->deleteMultipleTravelForm();
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
    # Function: saveTravelForm
    # Description: 
    # Updates the existing travel form if it exists; otherwise, inserts a new travel form.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveTravelForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $travelFormID = isset($_POST['travel_form_id']) ? htmlspecialchars($_POST['travel_form_id'], ENT_QUOTES, 'UTF-8') : null;
        $checkedBy = htmlspecialchars($_POST['checked_by'], ENT_QUOTES, 'UTF-8');
        $approvalBy = htmlspecialchars($_POST['approval_by'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTravelFormExist = $this->travelFormModel->checkTravelFormExist($travelFormID);
        $total = $checkTravelFormExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->travelFormModel->updateTravelForm($travelFormID, $checkedBy, $approvalBy, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'travelFormID' => $this->securityModel->encryptData($travelFormID)]);
            exit;
        } 
        else {
            $travelFormID = $this->travelFormModel->insertTravelForm($checkedBy, $approvalBy, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true, 'travelFormID' => $this->securityModel->encryptData($travelFormID)]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveTravelAuthorization
    # Description: 
    # Updates the existing travel form if it exists; otherwise, inserts a new travel form.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveTravelAuthorization() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $travelFormID = isset($_POST['travel_form_id']) ? htmlspecialchars($_POST['travel_form_id'], ENT_QUOTES, 'UTF-8') : null;
        $destination = htmlspecialchars($_POST['destination'], ENT_QUOTES, 'UTF-8');
        $modeOfTransportation = htmlspecialchars($_POST['mode_of_transportation'], ENT_QUOTES, 'UTF-8');
        $purposeOfTravel = htmlspecialchars($_POST['purpose_of_travel'], ENT_QUOTES, 'UTF-8');
        $authorizationDepartureDate = $this->systemModel->checkDate('empty', $_POST['authorization_departure_date'], '', 'Y-m-d', '');
        $authorizationReturnDate = $this->systemModel->checkDate('empty', $_POST['authorization_return_date'], '', 'Y-m-d', '');
        $accomodationDetails = htmlspecialchars($_POST['accomodation_details'], ENT_QUOTES, 'UTF-8');
        $tollFee = htmlspecialchars($_POST['toll_fee'], ENT_QUOTES, 'UTF-8');
        $accomodation = htmlspecialchars($_POST['accomodation'], ENT_QUOTES, 'UTF-8');
        $meals = htmlspecialchars($_POST['meals'], ENT_QUOTES, 'UTF-8');
        $otherExpenses = htmlspecialchars($_POST['other_expenses'], ENT_QUOTES, 'UTF-8');
        $totalEstimatedCost = htmlspecialchars($_POST['total_estimated_cost'], ENT_QUOTES, 'UTF-8');
        $additionalComments = htmlspecialchars($_POST['additional_comments'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTravelAuthorizationExist = $this->travelFormModel->checkTravelAuthorizationExist($travelFormID);
        $total = $checkTravelAuthorizationExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->travelFormModel->updateTravelAuthorization($travelFormID, $destination, $modeOfTransportation, $purposeOfTravel, $authorizationDepartureDate, $authorizationReturnDate, $accomodationDetails, $tollFee, $accomodation, $meals, $otherExpenses, $totalEstimatedCost, $additionalComments, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $travelFormID = $this->travelFormModel->insertTravelAuthorization($travelFormID, $destination, $modeOfTransportation, $purposeOfTravel, $authorizationDepartureDate, $authorizationReturnDate, $accomodationDetails, $tollFee, $accomodation, $meals, $otherExpenses, $totalEstimatedCost, $additionalComments, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: saveGatePass
    # Description: 
    # Updates the existing travel form if it exists; otherwise, inserts a new travel form.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveGatePass() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $travelFormID = isset($_POST['travel_form_id']) ? htmlspecialchars($_POST['travel_form_id'], ENT_QUOTES, 'UTF-8') : null;
        $nameOfDriver = htmlspecialchars($_POST['name_of_driver'], ENT_QUOTES, 'UTF-8');
        $contactNumber = htmlspecialchars($_POST['contact_number'], ENT_QUOTES, 'UTF-8');
        $vehicleType = htmlspecialchars($_POST['vehicle_type'], ENT_QUOTES, 'UTF-8');
        $plateNumber = htmlspecialchars($_POST['plate_number'], ENT_QUOTES, 'UTF-8');
        $departmentID = htmlspecialchars($_POST['department_id'], ENT_QUOTES, 'UTF-8');
        $gatePassDepartureDate = $this->systemModel->checkDate('empty', $_POST['gate_pass_departure_date'], '', 'Y-m-d', '');
        $odometerReading = htmlspecialchars($_POST['odometer_reading'], ENT_QUOTES, 'UTF-8');
        $remarks = htmlspecialchars($_POST['remarks'], ENT_QUOTES, 'UTF-8');

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkGatePassExist = $this->travelFormModel->checkGatePassExist($travelFormID);
        $total = $checkGatePassExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->travelFormModel->updateGatePass($travelFormID, $nameOfDriver, $contactNumber, $vehicleType, $plateNumber, $departmentID, $gatePassDepartureDate, $odometerReading, $remarks, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        } 
        else {
            $travelFormID = $this->travelFormModel->insertGatePass($travelFormID, $nameOfDriver, $contactNumber, $vehicleType, $plateNumber, $departmentID, $gatePassDepartureDate, $odometerReading, $remarks, $userID);

            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTravelForm
    # Description: 
    # Delete the travel form if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteTravelForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $travelFormID = htmlspecialchars($_POST['travel_form_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkTravelFormExist = $this->travelFormModel->checkTravelFormExist($travelFormID);
        $total = $checkTravelFormExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->travelFormModel->deleteTravelForm($travelFormID);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultipleTravelForm
    # Description: 
    # Delete the selected travel forms if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultipleTravelForm() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $travelFormIDs = $_POST['travel_form_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($travelFormIDs as $travelFormID){
            $this->travelFormModel->deleteTravelForm($travelFormID);
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
    # Function: getTravelFormDetails
    # Description: 
    # Handles the retrieval of travel form details such as travel form name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getTravelFormDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['travel_form_id']) && !empty($_POST['travel_form_id'])) {
            $userID = $_SESSION['user_id'];
            $travelFormID = $_POST['travel_form_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $travelFormDetails = $this->travelFormModel->getTravelForm($travelFormID);

            $response = [
                'success' => true,
                'checkedBy' => $travelFormDetails['checked_by'],
                'approvalBy' => $travelFormDetails['approval_by']
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTravelAuthorizationDetails
    # Description: 
    # Handles the retrieval of travel form details such as travel form name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getTravelAuthorizationDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['travel_form_id']) && !empty($_POST['travel_form_id'])) {
            $userID = $_SESSION['user_id'];
            $travelFormID = $_POST['travel_form_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $travelFormDetails = $this->travelFormModel->getTravelAuthorization($travelFormID);

            $response = [
                'success' => true,
                'destination' => $travelFormDetails['destination'] ?? null,
                'modeOfTransportation' => $travelFormDetails['mode_of_transportation'] ?? null,
                'purposeOfTravel' => $travelFormDetails['purpose_of_travel'] ?? null,
                'authorizationDepartureDate' =>  $this->systemModel->checkDate('empty', $travelFormDetails['authorization_departure_date'] ?? null, '', 'm/d/Y', ''),
                'authorizationReturnDate' =>  $this->systemModel->checkDate('empty', $travelFormDetails['authorization_return_date'] ?? null, '', 'm/d/Y', ''),
                'accomodationDetails' => $travelFormDetails['accomodation_details'] ?? null,
                'tollFee' => $travelFormDetails['toll_fee'] ?? null,
                'accomodation' => $travelFormDetails['accomodation'] ?? null,
                'meals' => $travelFormDetails['meals'] ?? null,
                'otherExpenses' => $travelFormDetails['other_expenses'] ?? null,
                'additionalComments' => $travelFormDetails['additional_comments'] ?? null
            ];

            echo json_encode($response);
            exit;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getGatePassDetails
    # Description: 
    # Handles the retrieval of travel form details such as travel form name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getGatePassDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['travel_form_id']) && !empty($_POST['travel_form_id'])) {
            $userID = $_SESSION['user_id'];
            $travelFormID = $_POST['travel_form_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $travelFormDetails = $this->travelFormModel->getTravelAuthorization($travelFormID);

            $response = [
                'success' => true,
                'nameOfDriver' => $travelFormDetails['name_of_driver'] ?? null,
                'contactNumber' => $travelFormDetails['contact_number'] ?? null,
                'vehicleType' => $travelFormDetails['vehicle_type'] ?? null,
                'plateNumber' => $travelFormDetails['plate_number'] ?? null,
                'departmentID' => $travelFormDetails['department_id'] ?? null,
                'gatePassDepartureDate' =>  $this->systemModel->checkDate('empty', $travelFormDetails['gate_pass_departure_date'] ?? null, '', 'm/d/Y', ''),
                'odometerReading' => $travelFormDetails['odometer_reading'] ?? null,
                'remarks' => $travelFormDetails['remarks'] ?? null
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
require_once '../model/travel-form-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new TravelFormController(new TravelFormModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>