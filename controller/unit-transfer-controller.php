<?php
session_start();

# -------------------------------------------------------------
#
# Function: UnitTransferController
# Description: 
# The UnitTransferController class handles property related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class UnitTransferController {
    private $unitTransferModel;
    private $userModel;
    private $warehouseModel;
    private $securityModel;
    private $systemModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PropertyModel, UserModel and SecurityModel instances.
    # These instances are used for property related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PropertyModel $propertyModel     The PropertyModel instance for property related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param UploadSettingModel $uploadSettingModel     The UploadSettingModel instance for upload setting related operations.
    # - @param FileExtensionModel $fileExtensionModel     The FileExtensionModel instance for file extension related operations.
    # - @param CityModel $cityModel     The CityModel instance for city related operations.
    # - @param StateModel $stateModel     The StateModel instance for state related operations.
    # - @param CountryModel $countryModel     The CountryModel instance for country related operations.
    # - @param CurrencyModel $currencyModel     The CurrencyModel instance for currency related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    # - @param SystemModel $systemModel   The SystemModel instance for system related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(UnitTransferModel $unitTransferModel, UserModel $userModel, WarehouseModel $warehouseModel, SecurityModel $securityModel, SystemModel $systemModel) {
        $this->unitTransferModel = $unitTransferModel;
        $this->userModel = $userModel;
        $this->warehouseModel = $warehouseModel;
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
                case 'scan unit':
                    $this->scanUnit();
                    break;
                case 'save unit transfer':
                    $this->saveUnitTransfer();
                    break;
                case 'save unit transfer details':
                    $this->saveUnitTransferDetails();
                    break;
                case 'cancel unit transfer':
                    $this->cancelUnitTransfer();
                    break;
                case 'save unit receive':
                    $this->saveUnitReceive();
                    break;
                case 'get unit transfer details':
                    $this->getUnitDetails();
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
    public function saveUnitTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
        $transferred_from = htmlspecialchars($_POST['transferred_from'], ENT_QUOTES, 'UTF-8');
        $transferred_to = htmlspecialchars($_POST['transferred_to'], ENT_QUOTES, 'UTF-8');
        $transfer_remarks = htmlspecialchars($_POST['transfer_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        if($transferred_from == $transferred_to){
            echo json_encode(['success' => false, 'sameWarehouse' => true]);
            exit;
        }
    
        $checkUnitTransferOpenExist = $this->unitTransferModel->checkUnitTransferOpenExist($product_id);
        $total = $checkUnitTransferOpenExist['total'] ?? 0;
    
        if ($total === 0) {
            $this->unitTransferModel->insertUnitTransfer($product_id, $transferred_from, $transferred_to, $transfer_remarks, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
        else{
            echo json_encode(['success' => false, 'openTransfer' => true]);
            exit;
        }
    }

    public function saveUnitReceive() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $transfer_unit_id = htmlspecialchars($_POST['transfer_unit_id'], ENT_QUOTES, 'UTF-8');
        $receive_remarks = htmlspecialchars($_POST['receive_remarks'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkUnitTransferExist = $this->unitTransferModel->checkUnitTransferExist($transfer_unit_id);
        $total = $checkUnitTransferExist['total'] ?? 0;

        $unitDetails = $this->unitTransferModel->getUnitTransferDetails($transfer_unit_id);
        $product_id = $unitDetails['product_id'] ?? null;
        $transferred_to = $unitDetails['transferred_to'] ?? null;

        $receiverAuthorization = $this->unitTransferModel->checkUnitReceiveAuthorization($transferred_to, $userID)['total'] ?? 0;

        if($receiverAuthorization == 0) {
            echo json_encode(['success' => false, 'notAuthorize' => true]);
            exit;
        }
    
        if ($total === 0) {
            $this->unitTransferModel->updateUnitReceive($transfer_unit_id, $receive_remarks, $product_id, $transferred_to, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function saveUnitTransferDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unit_transfer_id = htmlspecialchars($_POST['unit_transfer_id'], ENT_QUOTES, 'UTF-8');
        $transferred_to_update = htmlspecialchars($_POST['transferred_to_update'], ENT_QUOTES, 'UTF-8');
        $transfer_remarks_update = htmlspecialchars($_POST['transfer_remarks_update'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        $unitDetails = $this->unitTransferModel->getUnitTransferDetails($unit_transfer_id);

        if($transferred_to_update == $unitDetails['transferred_from']){
            echo json_encode(['success' => false, 'sameWarehouse' => true]);
            exit;
        }
    
        $this->unitTransferModel->updateUnitTransferDetails($unit_transfer_id, $transferred_to_update, $transfer_remarks_update, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function cancelUnitTransfer() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $unit_transfer_id = htmlspecialchars($_POST['unit_transfer_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->unitTransferModel->cancelUnitTransfer($unit_transfer_id, $userID);

        echo json_encode(['success' => true]);
        exit;
    }

    public function scanUnit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
    
        $details = $this->unitTransferModel->checkUnitTransferExist($product_id);
        $unitTransferID = $details['unit_transfer_id'] ?? null;

        if(!empty($unitTransferID)){
            $unitDetails = $this->unitTransferModel->getUnitTransferDetails($unitTransferID);
            $product_id = $unitDetails['product_id'] ?? null;
            $transferred_to = $unitDetails['transferred_to'] ?? null;

            $receiverAuthorization = $this->unitTransferModel->checkUnitReceiveAuthorization($transferred_to, $userID)['total'] ?? 0;

            if($receiverAuthorization == 0) {
                echo json_encode(['success' => false, 'notAuthorize' => true]);
                exit;
            }
        }

        echo json_encode(['success' => true, 'unitTransferID' => $unitTransferID]);
        exit;
    }

    public function getUnitDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['unit_transfer_id']) && !empty($_POST['unit_transfer_id'])) {
            $userID = $_SESSION['user_id'];
            $unit_transfer_id = $_POST['unit_transfer_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $unitDetails = $this->unitTransferModel->getUnitTransferDetails($unit_transfer_id);

            $response = [
                'success' => true,
                'transferredTo' => $unitDetails['transferred_to'],
                'transferRemarks' => $unitDetails['transfer_remarks']
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
require_once '../model/unit-transfer-model.php';
require_once '../model/user-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new UnitTransferController(new UnitTransferModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new WarehouseModel(new DatabaseModel), new SecurityModel(), new SystemModel());
$controller->handleRequest();
?>