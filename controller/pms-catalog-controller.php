<?php
session_start();

# -------------------------------------------------------------
#
# Function: PMSCatalogController
# Description: 
# The PMSCatalogController class handles pms catalog related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class PMSCatalogController {
    private $pmsCatalogModel;
    private $userModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided PMSCatalogModel, UserModel and SecurityModel instances.
    # These instances are used for pms catalog related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param PMSCatalogModel $pmsCatalogModel     The PMSCatalogModel instance for pms catalog related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(PMSCatalogModel $pmsCatalogModel, UserModel $userModel, SecurityModel $securityModel) {
        $this->pmsCatalogModel = $pmsCatalogModel;
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
                case 'save pms catalog':
                    $this->savePMSCatalog();
                    break;
                case 'unlock pms catalog':
                    $this->unlockPMSCatalog();
                    break;
                case 'lock pms catalog':
                    $this->lockPMSCatalog();
                    break;
                case 'save pms matrix':
                    $this->savePMSMatrix();
                    break;
                case 'save pms matrix part':
                    $this->savePMSMatrixPart();
                    break;
                case 'save pms catalog subcategory':
                    $this->savePMSCatalogSubcategory();
                    break;
                case 'get pms catalog details':
                    $this->getPMSCatalogDetails();
                    break;
                case 'get pms matrix details':
                    $this->getPMSMatrixDetails();
                    break;
                case 'get pms matrix part details':
                    $this->getPMSMatrixPartDetails();
                    break;
                case 'get pms matrices':
                    $this->getPMSMatrices();
                    break;
                case 'get pms matrix total details':
                    $this->getPMSMatrixTotal();
                    break;
                case 'generate pms matrix by catalog':
                    $this->generatePMSMatrixByCatalog();
                    break;
                case 'delete pms catalog':
                    $this->deletePMSCatalog();
                    break;
                case 'delete pms matrix':
                    $this->deletePMSMatrix();
                    break;
                case 'delete pms matrix part':
                    $this->deletePMSMatrixPart();
                    break;
                case 'delete multiple pms catalog':
                    $this->deleteMultiplePMSCatalog();
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
    # Function: savePMSCatalog
    # Description: 
    # Updates the existing pms catalog if it exists; otherwise, inserts a new pms catalog.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function savePMSCatalog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogID = isset($_POST['pms_catalog_id']) ? htmlspecialchars($_POST['pms_catalog_id'], ENT_QUOTES, 'UTF-8') : null;
        $pms_catalog_name = $_POST['pms_catalog_name'];
        $description = $_POST['description'];
        $company_id = $_POST['company_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSCatalogExist = $this->pmsCatalogModel->checkPMSCatalogExist($pmsCatalogID);
        $total = $checkPMSCatalogExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->pmsCatalogModel->updatePMSCatalog($pmsCatalogID, $pms_catalog_name, $description, $company_id, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'pmsCatalogID' => $this->securityModel->encryptData($pmsCatalogID)]);
            exit;
            
        }
        else{
            $pmsCatalogID = $this->pmsCatalogModel->insertPMSCatalog($pms_catalog_name, $description, $company_id, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false, 'pmsCatalogID' => $this->securityModel->encryptData($pmsCatalogID)]);
            exit;
        }
    }

    public function lockPMSCatalog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogID = $_POST['pms_catalog_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSCatalogExist = $this->pmsCatalogModel->checkPMSCatalogExist($pmsCatalogID);
        $total = $checkPMSCatalogExist['total'] ?? 0;
    
        if ($total > 0) {

            $this->pmsCatalogModel->updatePMSCatalogStatus($pmsCatalogID, 'locked', $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        }
    }

    public function unlockPMSCatalog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogID = $_POST['pms_catalog_id'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSCatalogExist = $this->pmsCatalogModel->checkPMSCatalogExist($pmsCatalogID);
        $total = $checkPMSCatalogExist['total'] ?? 0;
    
        if ($total > 0) {

            $this->pmsCatalogModel->updatePMSCatalogStatus($pmsCatalogID, 'open', $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => false]);
            exit;
        }
    }

    public function savePMSMatrix() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogID = $_POST['pms_catalog_id'];
        $pms_matrix_id = $_POST['pms_matrix_id'];
        $odometer = $_POST['odometer'];
        $months_interval = $_POST['months_interval'];
        $consumables = $_POST['consumables'];
        $consumables_quantity = $_POST['consumables_quantity'];
        $consumables_total = $_POST['consumables_total'];
        $labor_cost = $_POST['labor_cost'];
        $labor_quantity = $_POST['labor_quantity'];
        $labor_total = $_POST['labor_total'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSMatrixExist = $this->pmsCatalogModel->checkPMSMatrixExist($pms_matrix_id);
        $total = $checkPMSMatrixExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->pmsCatalogModel->updatePMSMatrix($pms_matrix_id, $odometer, $months_interval, $consumables, $consumables_quantity, $consumables_total, $labor_cost, $labor_quantity, $labor_total, $userID);
            echo json_encode(['success' => true, 'insertRecord' => false]);
        }
        else{
            $this->pmsCatalogModel->insertPMSMatrix($pmsCatalogID, $odometer, $months_interval, $consumables, $consumables_quantity, $consumables_total, $labor_cost, $labor_quantity, $labor_total, $userID);
            
            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }

    public function savePMSMatrixPart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pms_catalog_id = $_POST['pms_catalog_id'];
        $pms_matrix_part_id = $_POST['pms_matrix_part_id'];
        $pms_matrix_id = $_POST['pms_matrix_id2'];
        $part_id = $_POST['part_id'];
        $required_quantity = $_POST['required_quantity'];
        $unit_id = $_POST['unit_id'];
        
        $alternate_part_ids = $_POST['alternate_part_id'] ?? [];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSMatrixPartExist = $this->pmsCatalogModel->checkPMSMatrixPartExist($pms_matrix_part_id);
        $total = $checkPMSMatrixPartExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->pmsCatalogModel->updatePMSMatrixPart($pms_matrix_part_id, $part_id, $required_quantity, '', $userID);
            echo json_encode(['success' => true, 'insertRecord' => false]);

            $this->pmsCatalogModel->deletePMSMatrixAlternateParts($pms_matrix_part_id);

           if(is_array($alternate_part_ids)){

                foreach($alternate_part_ids as $alternate_part_id){

                    $this->pmsCatalogModel->insertPMSMatrixAlternatePart(
                        $pms_matrix_id,
                        $pms_matrix_part_id,
                        $alternate_part_id,
                        $userID
                    );

                }

            }
        }
        else{
            $pms_matrix_part_id = $this->pmsCatalogModel->insertPMSMatrixPart($pms_matrix_id, $part_id, $required_quantity, $unit_id, $userID);

            // Insert new selections
            if(is_array($alternate_part_ids)){

                foreach($alternate_part_ids as $alternate_part_id){

                    $this->pmsCatalogModel->insertPMSMatrixAlternatePart(
                        $pms_matrix_id,
                        $pms_matrix_part_id,
                        $alternate_part_id,
                        $userID
                    );

                }

            }
            
            echo json_encode(['success' => true, 'insertRecord' => true]);
            exit;
        }
    }

    public function savePMSCatalogSubcategory() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $userID = $_SESSION['user_id'];

        $pmsCatalogID = $_POST['pms_catalog_id'] ?? null;
        $productSubcategoryIDs = $_POST['product_subcategory_id'] ?? [];

        $user = $this->userModel->getUserByID($userID);

        if (!$user || !$user['is_active']) {
            echo json_encode([
                'success' => false,
                'isInactive' => true
            ]);
            exit;
        }

        // Always remove old records first
        $this->pmsCatalogModel->deletePMSCategory($pmsCatalogID);

        // Insert new selections
        if(is_array($productSubcategoryIDs)){

            foreach($productSubcategoryIDs as $subcategoryID){

                $this->pmsCatalogModel->insertPMSCategory(
                    $pmsCatalogID,
                    $subcategoryID,
                    $userID
                );

            }

        }

        echo json_encode([
            'success' => true
        ]);

        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePMSCatalog
    # Description: 
    # Delete the pms catalog if it exists; otherwise, return an error message.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deletePMSCatalog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogID = htmlspecialchars($_POST['pms_catalog_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkPMSCatalogExist = $this->pmsCatalogModel->checkPMSCatalogExist($pmsCatalogID);
        $total = $checkPMSCatalogExist['total'] ?? 0;

        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }
    
        $this->pmsCatalogModel->deletePMSCatalog($pmsCatalogID);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePMSMatrix() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pms_matrix_id = htmlspecialchars($_POST['pms_matrix_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->pmsCatalogModel->deletePMSMatrix($pms_matrix_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function deletePMSMatrixPart() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pms_matrix_part_id = htmlspecialchars($_POST['pms_matrix_part_id'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $this->pmsCatalogModel->deletePMSMatrixPart($pms_matrix_part_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMultiplePMSCatalog
    # Description: 
    # Delete the selected pms catalogs if it exists; otherwise, skip it.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function deleteMultiplePMSCatalog() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $pmsCatalogIDs = $_POST['pms_catalog_id'];

        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }

        foreach($pmsCatalogIDs as $pmsCatalogID){
            $this->pmsCatalogModel->deletePMSCatalog($pmsCatalogID);
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
    # Function: getPMSCatalogDetails
    # Description: 
    # Handles the retrieval of pms catalog details such as pms catalog name, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getPMSCatalogDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['pms_catalog_id']) && !empty($_POST['pms_catalog_id'])) {
            $userID = $_SESSION['user_id'];
            $pmsCatalogID = $_POST['pms_catalog_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $pmsCatalogDetails = $this->pmsCatalogModel->getPMSCatalog($pmsCatalogID);

            $productSubcategories = $this->pmsCatalogModel->getPMSCatalogSubcategories($pmsCatalogID);

            $response = [
                'success' => true,
                'pmsCatalogName' => $pmsCatalogDetails['pms_catalog_name'],
                'description' => $pmsCatalogDetails['description'],
                'companyId' => $pmsCatalogDetails['company_id'],
                'productSubcategoryIds' => $productSubcategories
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getPMSMatrixDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['pms_matrix_id']) && !empty($_POST['pms_matrix_id'])) {
            $userID = $_SESSION['user_id'];
            $pmsMatrixID = $_POST['pms_matrix_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $pmsMatrixDetails = $this->pmsCatalogModel->getPMSMatrix($pmsMatrixID);


            $response = [
                'success' => true,
                'odometer' => $pmsMatrixDetails['odometer'],
                'monthsInterval' => $pmsMatrixDetails['months_interval'],
                'consumables' => $pmsMatrixDetails['consumables'],
                'consumables_quantity' => $pmsMatrixDetails['consumables_quantity'],
                'laborCost' => $pmsMatrixDetails['labor_cost'],
                'labor_quantity' => $pmsMatrixDetails['labor_quantity'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    public function getPMSMatrixPartDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['pms_matrix_part_id']) && !empty($_POST['pms_matrix_part_id'])) {
            $userID = $_SESSION['user_id'];
            $pmsMatrixPartID = $_POST['pms_matrix_part_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $pmsMatrixPartDetails = $this->pmsCatalogModel->getPMSMatrixPart($pmsMatrixPartID);

            $alternateParts = $this->pmsCatalogModel->getPMSMatrixPartAternate($pmsMatrixPartID);


            $response = [
                'success' => true,
                'partId' => $pmsMatrixPartDetails['part_id'],
                'requiredQuantity' => $pmsMatrixPartDetails['required_quantity'],
                'unitId' => $pmsMatrixPartDetails['unit_id'],
                'alternateParts' => $alternateParts
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getPMSMatrixTotal() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $pms_matrix_id = $_POST['pms_matrix_id'] ?? [];

        // Ensure it's passed as an array to the model
        if (!is_array($pms_matrix_id)) {
            $pms_matrix_id = explode(',', $pms_matrix_id);
        }

        $totals = $this->pmsCatalogModel->getPMSMatrixTotal($pms_matrix_id);

        $response = [
            'success'         => true,
            'consumableTotal' => $totals['consumableTotal'],
            'laborCost'       => $totals['laborCost'],
            'partsCost'       => $totals['partsCost'],
            'totalCost'       => $totals['totalCost']
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    public function getPMSMatrices()
    {
        $pmsCatalogID = $_POST['pms_catalog_id'];

        $matrices = $this->pmsCatalogModel->getPMSMatrices($pmsCatalogID);

        $response = [];

        foreach ($matrices as $row) {

            $matrixId = $row['pms_matrix_id'];

            $parts = $this->pmsCatalogModel->getPMSMatrixParts($matrixId);

            foreach ($parts as &$part) {

                $part['alternate_parts'] = $this->pmsCatalogModel
                    ->getPMSMatrixAlternateParts($part['pms_matrix_part_id']);
            }

            $response[] = [
                'pms_matrix_id'   => $row['pms_matrix_id'],
                'pms_catalog_id'  => $row['pms_catalog_id'],
                'odometer'        => $row['odometer'],
                'months_interval' => $row['months_interval'],
                'consumables'     => $row['consumables'],
                'consumables_quantity'     => $row['consumables_quantity'],
                'consumables_total'     => $row['consumables_total'],
                'labor_cost'      => $row['labor_cost'],
                'labor_quantity'      => $row['labor_quantity'],
                'labor_total'      => $row['labor_total'],
                'parts'           => $parts
            ];
        }

        echo json_encode($response);
    }

    public function generatePMSMatrixByCatalog()
    {
        $pmsCatalogID = $_POST['pms_catalog_id'];

        $matrices = $this->pmsCatalogModel->getPMSMatrices($pmsCatalogID);

        $response = [];

        foreach ($matrices as $row) {
            $response[] = [
                'pms_matrix_id'   => $row['pms_matrix_id'],
                'odometer'        => $row['odometer'],
                'months_interval' => $row['months_interval'],
            ];
        }

        echo json_encode($response);
    }
    # -------------------------------------------------------------
}
# -------------------------------------------------------------

require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/pms-catalog-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new PMSCatalogController(new PMSCatalogModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SecurityModel());
$controller->handleRequest();
?>