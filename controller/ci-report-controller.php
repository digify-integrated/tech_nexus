<?php
session_start();

# -------------------------------------------------------------
#
# Function: CIReportController
# Description: 
# The CIReportController class handles ci report related operations and interactions.
#
# Parameters: None
#
# Returns: None
#
# -------------------------------------------------------------
class CIReportController {
    private $ciReportModel;
    private $userModel;
    private $systemModel;
    private $securityModel;

    # -------------------------------------------------------------
    #
    # Function: __construct
    # Description: 
    # The constructor initializes the object with the provided CIReportModel, UserModel and SecurityModel instances.
    # These instances are used for ci report related, user related operations and security related operations, respectively.
    #
    # Parameters:
    # - @param CIReportModel $ciReportModel     The CIReportModel instance for ci report related operations.
    # - @param UserModel $userModel     The UserModel instance for user related operations.
    # - @param SecurityModel $securityModel   The SecurityModel instance for security related operations.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function __construct(CIReportModel $ciReportModel, UserModel $userModel, SystemModel $systemModel, SecurityModel $securityModel) {
        $this->ciReportModel = $ciReportModel;
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
                case 'save ci report':
                    $this->saveCIReport();
                    break;
                case 'save ci report residence':
                    $this->saveCIReportResidence();
                    break;
                case 'save ci report dependents':
                    $this->saveCIReportDependents();
                    break;
                case 'save ci report business':
                    $this->saveCIReportBusiness();
                    break;
                case 'save ci report employment':
                    $this->saveCIReportEmployment();
                    break;
                case 'save ci report bank':
                    $this->saveCIReportBank();
                    break;
                case 'save ci report bank deposits':
                    $this->saveCIReportBankDeposits();
                    break;
                case 'save ci report appraisal source':
                    $this->saveCIReportAppraisalSource();
                    break;
                case 'save ci report loan':
                    $this->saveCIReportLoan();
                    break;
                case 'save ci report cmap':
                    $this->saveCIReportCMAP();
                    break;
                case 'save ci report collateral':
                    $this->saveCIReportCollateral();
                    break;
                case 'save ci report asset':
                    $this->saveCIReportAsset();
                    break;
                case 'get ci report details':
                    $this->getCIReportDetails();
                    break;
                case 'get ci report residence details':
                    $this->getCIReportResidenceDetails();
                    break;
                case 'get ci report residence total details':
                    $this->getCIReportResidenceTotalDetails();
                    break;
                case 'get ci report dependents details':
                    $this->getCIReportDependentsDetails();
                    break;
                case 'get ci report business details':
                    $this->getCIReportBusinessDetails();
                    break;
                case 'get ci report business total details':
                    $this->getCIReportBusinessTotalDetails();
                    break;
                case 'get ci report employment details':
                    $this->getCIReportEmploymentDetails();
                    break;
                case 'get ci report employment total details':
                    $this->getCIReportEmploymentTotalDetails();
                    break;
                case 'get ci report bank details':
                    $this->getCIReportBankDetails();
                    break;
                case 'get ci report bank deposits details':
                    $this->getCIReportBankDepositsDetails();
                    break;
                case 'get ci report appraisal source details':
                    $this->getCIReportAppraisalSourceDetails();
                    break;
                case 'get ci report loan details':
                    $this->getCIReportLoanDetails();
                    break;
                case 'get ci report cmap details':
                    $this->getCIReportCMAPDetails();
                    break;
                case 'get ci report collateral details':
                    $this->getCIReportCollateralDetails();
                    break;
                case 'get ci report asset details':
                    $this->getCIReportAssetDetails();
                    break;
                case 'delete ci report residence':
                    $this->deleteCIReportResidence();
                    break;
                case 'delete ci report dependents':
                    $this->deleteCIReportDependents();
                    break;
                case 'delete ci report business':
                    $this->deleteCIReportBusiness();
                    break;
                case 'delete ci report employment':
                    $this->deleteCIReportEmployment();
                    break;
                case 'delete ci report bank':
                    $this->deleteCIReportBank();
                    break;
                case 'delete ci report bank deposits':
                    $this->deleteCIReportBankDeposits();
                    break;
                case 'delete ci report appraisal source':
                    $this->deleteCIReportAppraisalSource();
                    break;
                case 'delete ci report loan':
                    $this->deleteCIReportLoan();
                    break;
                case 'delete ci report cmap':
                    $this->deleteCIReportCMAP();
                    break;
                case 'delete ci report collateral':
                    $this->deleteCIReportCollateral();
                    break;
                case 'delete ci report asset':
                    $this->deleteCIReportAsset();
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
    # Function: saveCIReport
    # Description: 
    # Updates the existing ci report if it exists; otherwise, inserts a new ci report.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function saveCIReport() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        $userID = $_SESSION['user_id'];
        $ciReportID = isset($_POST['ci_report_id']) ? htmlspecialchars($_POST['ci_report_id'], ENT_QUOTES, 'UTF-8') : null;
        $appraiser = htmlspecialchars($_POST['appraiser'], ENT_QUOTES, 'UTF-8');
        $investigator = htmlspecialchars($_POST['investigator'], ENT_QUOTES, 'UTF-8');
        $narrative_summary = htmlspecialchars($_POST['narrative_summary'], ENT_QUOTES, 'UTF-8');
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportExist = $this->ciReportModel->checkCIReportExist($ciReportID);
        $total = $checkCIReportExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReport($ciReportID, $appraiser, $investigator, $narrative_summary, $userID);
        } 
        
        echo json_encode(['success' => true]);
        exit;
    }

    public function saveCIReportResidence() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_residence_id = isset($_POST['ci_report_residence_id']) ? htmlspecialchars($_POST['ci_report_residence_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $address = $_POST['ci_residence_contact_address'];
        $city_id = $_POST['ci_residence_city_id'];
        $prev_address = $_POST['ci_residence_prev_address'];
        $prev_city_id = $_POST['ci_residence_prev_city_id'];
        $length_stay_year = $_POST['ci_residence_length_stay_year'];
        $length_stay_month = $_POST['ci_residence_length_stay_month'];
        $residence_type_id = $_POST['ci_residence_residence_type_id'];
        $rented_from = $_POST['ci_residence_rented_from'];
        $rent_amount = $_POST['ci_residence_rent_amount'];
        $estimated_value = $_POST['ci_residence_estimated_value'];
        $structure_type_id = $_POST['ci_residence_structure_type_id'];
        $residence_age = $_POST['ci_residence_residence_age'];
        $building_make_id = $_POST['ci_residence_building_make_id'];
        $lot_area = $_POST['ci_residence_lot_area'];
        $floor_area = $_POST['ci_residence_floor_area'];
        $furnishing_appliance = $_POST['ci_residence_furnishing_appliance'];
        $vehicle_owned = $_POST['ci_residence_vehicle_owned'];
        $real_estate_owned = $_POST['ci_residence_real_estate_owned'];
        $neighborhood_type_id = $_POST['ci_residence_neighborhood_type_id'];
        $income_level_id = $_POST['ci_residence_income_level_id'];
        $accessible_to = $_POST['ci_residence_accessible_to'];
        $nearest_corner = $_POST['ci_residence_nearest_corner'];
        $informant = $_POST['ci_residence_informant'];
        $informant_address = $_POST['ci_residence_informant_address'];
        $personal_expense = $_POST['ci_residence_personal_expense'];
        $utilities_expense = $_POST['ci_residence_utilities_expense'];
        $other_expense = $_POST['ci_residence_other_expense'];
        $total_expense = $_POST['ci_residence_total_expense'];
        $remarks = $_POST['ci_residence_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportResidenceExist = $this->ciReportModel->checkCIReportResidenceExist($ci_report_residence_id);
        $total = $checkCIReportResidenceExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportResidence($ci_report_residence_id, $ci_report_id, $address, $city_id, $prev_address, $prev_city_id, $length_stay_year, $length_stay_month, $residence_type_id, $rented_from, $rent_amount, $estimated_value, $structure_type_id, $residence_age, $building_make_id, $lot_area, $floor_area, $furnishing_appliance, $neighborhood_type_id, $income_level_id, $accessible_to, $nearest_corner, $informant, $informant_address, $personal_expense, $utilities_expense, $other_expense, $total_expense, $vehicle_owned, $real_estate_owned, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->ciReportModel->insertCIReportResidence($ci_report_id, $address, $city_id, $prev_address, $prev_city_id, $length_stay_year, $length_stay_month, $residence_type_id, $rented_from, $rent_amount, $estimated_value, $structure_type_id, $residence_age, $building_make_id, $lot_area, $floor_area, $furnishing_appliance, $neighborhood_type_id, $income_level_id, $accessible_to, $nearest_corner, $informant, $informant_address, $personal_expense, $utilities_expense, $other_expense, $total_expense, $vehicle_owned, $real_estate_owned, $remarks, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteCIReportResidence() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_residence_id = isset($_POST['ci_report_residence_id']) ? htmlspecialchars($_POST['ci_report_residence_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportResidenceExist = $this->ciReportModel->checkCIReportResidenceExist($ci_report_residence_id);
        $total = $checkCIReportResidenceExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportResidence($ci_report_residence_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportResidenceDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_residence_id']) && !empty($_POST['ci_report_residence_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_residence_id = $_POST['ci_report_residence_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportResidenceDetails = $this->ciReportModel->getCIReportResidence($ci_report_residence_id);

            $response = [
                'success' => true,
                'address' => $ciReportResidenceDetails['address'],
                'city_id' => $ciReportResidenceDetails['city_id'],
                'prev_address' => $ciReportResidenceDetails['prev_address'],
                'prev_city_id' => $ciReportResidenceDetails['prev_city_id'],
                'length_stay_year' => $ciReportResidenceDetails['length_stay_year'],
                'length_stay_month' => $ciReportResidenceDetails['length_stay_month'],
                'residence_type_id' => $ciReportResidenceDetails['residence_type_id'],
                'rented_from' => $ciReportResidenceDetails['rented_from'],
                'rent_amount' => $ciReportResidenceDetails['rent_amount'],
                'estimated_value' => $ciReportResidenceDetails['estimated_value'],
                'structure_type_id' => $ciReportResidenceDetails['structure_type_id'],
                'residence_age' => $ciReportResidenceDetails['residence_age'],
                'building_make_id' => $ciReportResidenceDetails['building_make_id'],
                'lot_area' => $ciReportResidenceDetails['lot_area'],
                'floor_area' => $ciReportResidenceDetails['floor_area'],
                'furnishing_appliance' => $ciReportResidenceDetails['furnishing_appliance'],
                'neighborhood_type_id' => $ciReportResidenceDetails['neighborhood_type_id'],
                'income_level_id' => $ciReportResidenceDetails['income_level_id'],
                'accessible_to' => $ciReportResidenceDetails['accessible_to'],
                'nearest_corner' => $ciReportResidenceDetails['nearest_corner'],
                'informant' => $ciReportResidenceDetails['informant'],
                'informant_address' => $ciReportResidenceDetails['informant_address'],
                'personal_expense' => $ciReportResidenceDetails['personal_expense'],
                'utilities_expense' => $ciReportResidenceDetails['utilities_expense'],
                'other_expense' => $ciReportResidenceDetails['other_expense'],
                'total' => $ciReportResidenceDetails['total'],
                'vehicle_owned' => $ciReportResidenceDetails['vehicle_owned'],
                'real_estate_owned' => $ciReportResidenceDetails['real_estate_owned'],
                'remarks' => $ciReportResidenceDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getCIReportResidenceTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_id']) && !empty($_POST['ci_report_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_id = $_POST['ci_report_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $personalExpenseTotal = $this->ciReportModel->getCIReportResidenceExpenseTotal($ci_report_id, 'personal')['total'] ?? 0;
            $utilitiesExpenseTotal = $this->ciReportModel->getCIReportResidenceExpenseTotal($ci_report_id, 'utilities')['total'] ?? 0;
            $otherExpenseTotal = $this->ciReportModel->getCIReportResidenceExpenseTotal($ci_report_id, 'other')['total'] ?? 0;
            $totalExpenseTotal = $this->ciReportModel->getCIReportResidenceExpenseTotal($ci_report_id, 'total')['total'] ?? 0;

            $response = [
                'success' => true,
                'personalExpenseTotal' => number_format($personalExpenseTotal, 2) . ' Php',
                'utilitiesExpenseTotal' => number_format($utilitiesExpenseTotal, 2) . ' Php',
                'otherExpenseTotal' => number_format($otherExpenseTotal, 2) . ' Php',
                'totalExpenseTotal' => number_format($totalExpenseTotal, 2) . ' Php',
            ];

            echo json_encode($response);
            exit;
        }
    }

    # -------------------------------------------------------------
    public function saveCIReportDependents() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_dependents_id = isset($_POST['ci_report_dependents_id']) ? htmlspecialchars($_POST['ci_report_dependents_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $name = $_POST['ci_dependents_name'];
        $age = $_POST['ci_dependents_age'];
        $school = $_POST['ci_dependents_school'];
        $employment = $_POST['ci_dependents_employment'];
        $remarks = $_POST['ci_dependents_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportDependentsExist = $this->ciReportModel->checkCIReportDependentsExist($ci_report_dependents_id);
        $total = $checkCIReportDependentsExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportDependents($ci_report_dependents_id, $ci_report_id, $name, $age, $school, $employment, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->ciReportModel->insertCIReportDependents($ci_report_id, $name, $age, $school, $employment, $remarks, $userID);

            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteCIReportDependents() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_dependents_id = isset($_POST['ci_report_dependents_id']) ? htmlspecialchars($_POST['ci_report_dependents_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportDependentsExist = $this->ciReportModel->checkCIReportDependentsExist($ci_report_dependents_id);
        $total = $checkCIReportDependentsExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportDependents($ci_report_dependents_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportDependentsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_dependents_id']) && !empty($_POST['ci_report_dependents_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_dependents_id = $_POST['ci_report_dependents_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportDependentsDetails = $this->ciReportModel->getCIReportDependents($ci_report_dependents_id);

            $response = [
                'success' => true,
                'name' => $ciReportDependentsDetails['name'],
                'age' => $ciReportDependentsDetails['age'],
                'school' => $ciReportDependentsDetails['school'],
                'employment' => $ciReportDependentsDetails['employment'],
                'remarks' => $ciReportDependentsDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportBusiness() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_business_id = isset($_POST['ci_report_business_id']) ? htmlspecialchars($_POST['ci_report_business_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $business_name = $_POST['ci_business_business_name'];
        $description = $_POST['ci_business_description'];
        $contact_address = $_POST['ci_business_contact_address'];
        $city_id = $_POST['ci_business_city_id'];
        $length_stay_year = $_POST['ci_business_length_stay_year'];
        $length_stay_month = $_POST['ci_business_length_stay_month'];
        $registered_with = $_POST['ci_business_registered_with'];
        $organization = $_POST['ci_business_organization'];
        $date_organized = $_POST['ci_business_date_organized'];
        $no_employee = $_POST['ci_business_no_employee'];
        $customer = $_POST['ci_business_customer'];
        $major_bank_id = $_POST['ci_business_major_bank_id'];
        $contact_person = $_POST['ci_business_contact_person'];
        $business_location_type_id = $_POST['ci_business_business_location_type_id'];
        $building_make_id = $_POST['ci_business_building_make_id'];
        $business_premises_id = $_POST['ci_business_business_premises_id'];
        $landlord = $_POST['ci_business_landlord'];
        $rental_amount = $_POST['ci_business_rental_amount'];
        $machineries = $_POST['ci_business_machineries'];
        $branches = $_POST['ci_business_branches'];
        $fixtures = $_POST['ci_business_fixtures'];
        $facility_condition = $_POST['ci_business_facility_condition'];
        $vehicle = $_POST['ci_business_vehicle'];
        $trade_reference = $_POST['ci_business_trade_reference'];
        $gross_monthly_sale = $_POST['ci_business_gross_monthly_sale'];
        $monthly_income = $_POST['ci_business_monthly_income'];
        $inventory = $_POST['ci_business_inventory'];
        $receivable = $_POST['ci_business_receivable'];
        $fixed_asset = $_POST['ci_business_fixed_asset'];
        $liabilities = $_POST['ci_business_liabilities'];
        $capital = $_POST['ci_business_capital'];
        $remarks = $_POST['ci_business_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBusinessExist = $this->ciReportModel->checkCIReportBusinessExist($ci_report_business_id);
        $total = $checkCIReportBusinessExist['total'] ?? 0;
    
        if ($total > 0) {
           $this->ciReportModel->updateCIReportBusiness(
                $ci_report_business_id, $ci_report_id, $business_name, $description, $contact_address, $city_id,
                $length_stay_year, $length_stay_month, $registered_with, $organization, $date_organized,
                $no_employee, $customer, $major_bank_id, $contact_person, $business_location_type_id,
                $building_make_id, $business_premises_id, $landlord, $rental_amount, $machineries,
                $branches, $fixtures, $facility_condition, $vehicle, $trade_reference, $gross_monthly_sale,
                $monthly_income, $inventory, $receivable, $fixed_asset, $liabilities, $capital, $remarks,
                $userID
            );
            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->ciReportModel->insertCIReportBusiness(
                $ci_report_id, $business_name, $description, $contact_address, $city_id,
                $length_stay_year, $length_stay_month, $registered_with, $organization, $date_organized,
                $no_employee, $customer, $major_bank_id, $contact_person, $business_location_type_id,
                $building_make_id, $business_premises_id, $landlord, $rental_amount, $machineries,
                $branches, $fixtures, $facility_condition, $vehicle, $trade_reference, $gross_monthly_sale,
                $monthly_income, $inventory, $receivable, $fixed_asset, $liabilities, $capital, $remarks,
                $userID
            );

            echo json_encode(['success' => true]);
            exit;
        }        
    }

    public function deleteCIReportBusiness() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_business_id = isset($_POST['ci_report_business_id']) ? htmlspecialchars($_POST['ci_report_business_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBusinessExist = $this->ciReportModel->checkCIReportBusinessExist($ci_report_business_id);
        $total = $checkCIReportBusinessExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportBusiness($ci_report_business_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportBusinessDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_business_id']) && !empty($_POST['ci_report_business_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_business_id = $_POST['ci_report_business_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportBusinessDetails = $this->ciReportModel->getCIReportBusiness($ci_report_business_id);

            $response = [
                'success' => true,
                'business_name' => $ciReportBusinessDetails['business_name'],
                'description' => $ciReportBusinessDetails['description'],
                'address' => $ciReportBusinessDetails['address'],
                'city_id' => $ciReportBusinessDetails['city_id'],
                'length_stay_year' => $ciReportBusinessDetails['length_stay_year'],
                'length_stay_month' => $ciReportBusinessDetails['length_stay_month'],
                'registered_with' => $ciReportBusinessDetails['registered_with'],
                'organization' => $ciReportBusinessDetails['organization'],
                'date_organized' => $ciReportBusinessDetails['date_organized'],
                'no_employee' => $ciReportBusinessDetails['no_employee'],
                'customer' => $ciReportBusinessDetails['customer'],
                'major_bank_id' => $ciReportBusinessDetails['major_bank_id'],
                'contact_person' => $ciReportBusinessDetails['contact_person'],
                'business_location_type_id' => $ciReportBusinessDetails['business_location_type_id'],
                'building_make_id' => $ciReportBusinessDetails['building_make_id'],
                'business_premises_id' => $ciReportBusinessDetails['business_premises_id'],
                'landlord' => $ciReportBusinessDetails['landlord'],
                'rental_amount' => $ciReportBusinessDetails['rental_amount'],
                'machineries' => $ciReportBusinessDetails['machineries'],
                'fixtures' => $ciReportBusinessDetails['fixtures'],
                'facility_condition' => $ciReportBusinessDetails['facility_condition'],
                'gross_monthly_sale' => $ciReportBusinessDetails['gross_monthly_sale'],
                'monthly_income' => $ciReportBusinessDetails['monthly_income'],
                'inventory' => $ciReportBusinessDetails['inventory'],
                'receivable' => $ciReportBusinessDetails['receivable'],
                'fixed_asset' => $ciReportBusinessDetails['fixed_asset'],
                'liabilities' => $ciReportBusinessDetails['liabilities'],
                'capital' => $ciReportBusinessDetails['capital'],
                'branch' => $ciReportBusinessDetails['branch'],
                'vehicle' => $ciReportBusinessDetails['vehicle'],
                'trade_reference' => $ciReportBusinessDetails['trade_reference'],
                'remarks' => $ciReportBusinessDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getCIReportBusinessTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_id']) && !empty($_POST['ci_report_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_id = $_POST['ci_report_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $grossMonthlySaleTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'gross monthly sale')['total'] ?? 0;
            $monthlyIncomeTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'monthly income')['total'] ?? 0;
            $InventoryTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'inventory')['total'] ?? 0;
            $receivableTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'receivable')['total'] ?? 0;
            $fixedAssetTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'fixed asset')['total'] ?? 0;
            $liabilitiesTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'liabilities')['total'] ?? 0;
            $capitalTotal = $this->ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'capital')['total'] ?? 0;

            $response = [
                'success' => true,
                'grossMonthlySaleTotal' => number_format($grossMonthlySaleTotal, 2) . ' Php',
                'monthlyIncomeTotal' => number_format($monthlyIncomeTotal, 2) . ' Php',
                'InventoryTotal' => number_format($InventoryTotal, 2) . ' Php',
                'receivableTotal' => number_format($receivableTotal, 2) . ' Php',
                'fixedAssetTotal' => number_format($fixedAssetTotal, 2) . ' Php',
                'liabilitiesTotal' => number_format($liabilitiesTotal, 2) . ' Php',
                'capitalTotal' => number_format($capitalTotal, 2) . ' Php',
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportEmployment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_employment_id = isset($_POST['ci_report_employment_id']) ? htmlspecialchars($_POST['ci_report_employment_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $employment_name = $_POST['ci_employment_employment_name'];
        $description = $_POST['ci_employment_description'];
        $contact_address = $_POST['ci_employment_contact_address'];
        $city_id = $_POST['ci_employment_city_id'];
        $department = $_POST['ci_employment_department'];
        $rank = $_POST['ci_employment_rank'];
        $position = $_POST['ci_employment_position'];
        $status = $_POST['ci_employment_status'];
        $length_stay_year = $_POST['ci_employment_length_stay_year'];
        $length_stay_month = $_POST['ci_employment_length_stay_month'];
        $pres_length_stay_year = $_POST['ci_employment_pres_length_stay_year'];
        $pres_length_stay_month = $_POST['ci_employment_pres_length_stay_month'];
        $informant = $_POST['ci_employment_informant'];
        $informant_address = $_POST['ci_employment_informant_address'];
        $net_salary = $_POST['ci_employment_net_salary'];
        $commission = $_POST['ci_employment_commission'];
        $allowance = $_POST['ci_employment_allowance'];
        $other_income = $_POST['ci_employment_other_income'];
        $grand_total = $_POST['ci_employment_grand_total'];
        $remarks = $_POST['ci_employment_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportEmploymentExist = $this->ciReportModel->checkCIReportEmploymentExist($ci_report_employment_id);
        $total = $checkCIReportEmploymentExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportEmployment(
                $ci_report_employment_id, $ci_report_id, $employment_name, $description, $contact_address, $city_id,
                $length_stay_year, $length_stay_month, $pres_length_stay_year, $pres_length_stay_month,
                $informant, $informant_address, $department, $rank, $position, $status,
                $net_salary, $commission, $allowance, $other_income, $grand_total,
                $remarks, $userID
            );

            
            echo json_encode(['success' => true]);
            exit;
        } 
        else {
            $this->ciReportModel->insertCIReportEmployment(
                $ci_report_id, $employment_name, $description, $contact_address, $city_id,
                $length_stay_year, $length_stay_month, $pres_length_stay_year, $pres_length_stay_month,
                $informant, $informant_address, $department, $rank, $position, $status,
                $net_salary, $commission, $allowance, $other_income, $grand_total,
                $remarks, $userID
            );


            echo json_encode(['success' => true]);
            exit;
        }        
    }

    public function deleteCIReportEmployment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_employment_id = isset($_POST['ci_report_employment_id']) ? htmlspecialchars($_POST['ci_report_employment_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportEmploymentExist = $this->ciReportModel->checkCIReportEmploymentExist($ci_report_employment_id);
        $total = $checkCIReportEmploymentExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportEmployment($ci_report_employment_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportEmploymentDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_employment_id']) && !empty($_POST['ci_report_employment_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_employment_id = $_POST['ci_report_employment_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportEmploymentDetails = $this->ciReportModel->getCIReportEmployment($ci_report_employment_id);

            $response = [
                'success' => true,
                'employment_name' => $ciReportEmploymentDetails['employment_name'],
                'description' => $ciReportEmploymentDetails['description'],
                'address' => $ciReportEmploymentDetails['address'],
                'city_id' => $ciReportEmploymentDetails['city_id'],
                'length_stay_year' => $ciReportEmploymentDetails['length_stay_year'],
                'length_stay_month' => $ciReportEmploymentDetails['length_stay_month'],
                'pres_length_stay_year' => $ciReportEmploymentDetails['pres_length_stay_year'],
                'pres_length_stay_month' => $ciReportEmploymentDetails['pres_length_stay_month'],
                'informant' => $ciReportEmploymentDetails['informant'],
                'informant_address' => $ciReportEmploymentDetails['informant_address'],
                'department' => $ciReportEmploymentDetails['department'],
                'rank' => $ciReportEmploymentDetails['rank'],
                'position' => $ciReportEmploymentDetails['position'],
                'status' => $ciReportEmploymentDetails['status'],
                'net_salary' => $ciReportEmploymentDetails['net_salary'],
                'commission' => $ciReportEmploymentDetails['commission'],
                'allowance' => $ciReportEmploymentDetails['allowance'],
                'other_income' => $ciReportEmploymentDetails['other_income'],
                'grand_total' => $ciReportEmploymentDetails['grand_total'],
                'remarks' => $ciReportEmploymentDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    public function getCIReportEmploymentTotalDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_id']) && !empty($_POST['ci_report_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_id = $_POST['ci_report_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $netSalaryTotal = $this->ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'net salary')['total'] ?? 0;
            $commissionTotal = $this->ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'commission')['total'] ?? 0;
            $allowanceTotal = $this->ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'allowance')['total'] ?? 0;
            $otherIncomeTotal = $this->ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'other income')['total'] ?? 0;
            $grandTotal = $this->ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'grand total')['total'] ?? 0;

            $response = [
                'success' => true,
                'netSalaryTotal' => number_format($netSalaryTotal, 2) . ' Php',
                'commissionTotal' => number_format($commissionTotal, 2) . ' Php',
                'allowanceTotal' => number_format($allowanceTotal, 2) . ' Php',
                'otherIncomeTotal' => number_format($otherIncomeTotal, 2) . ' Php',
                'grandTotal' => number_format($grandTotal, 2) . ' Php',
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportBankDeposits() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_bank_deposits_id = isset($_POST['ci_report_bank_deposits_id']) ? htmlspecialchars($_POST['ci_report_bank_deposits_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $ci_report_bank_id = $_POST['ci_report_bank_id'];
        $deposit_month = $this->systemModel->checkDate('empty', $_POST['ci_bank_deposits_deposit_month'], '', 'Y-m-d', '');
        $amount = $_POST['ci_bank_deposits_amount'];
        $remarks = $_POST['ci_bank_deposits_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBankExist = $this->ciReportModel->checkCIReportBankDepositsExist($ci_report_bank_deposits_id);
        $total = $checkCIReportBankExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportBankDeposits($ci_report_bank_deposits_id, $ci_report_bank_id, $ci_report_id, $deposit_month, $amount, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } else {
            $this->ciReportModel->insertCIReportBankDeposits($ci_report_bank_id, $ci_report_id, $deposit_month, $amount, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteCIReportBankDeposits() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_bank_deposits_id = isset($_POST['ci_report_bank_deposits_id']) ? htmlspecialchars($_POST['ci_report_bank_deposits_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBankDepositsExist = $this->ciReportModel->checkCIReportBankDepositsExist($ci_report_bank_deposits_id);
        $total = $checkCIReportBankDepositsExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportBankDeposits($ci_report_bank_deposits_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportBankDepositsDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_bank_deposits_id']) && !empty($_POST['ci_report_bank_deposits_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_bank_deposits_id = $_POST['ci_report_bank_deposits_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportBankDepositsDetails = $this->ciReportModel->getCIReportBankDeposits($ci_report_bank_deposits_id);

            $response = [
                'success' => true,
                'deposit_month' =>  $this->systemModel->checkDate('empty', $ciReportBankDepositsDetails['deposit_month'], '', 'm/d/Y', ''),
                'amount' => $ciReportBankDepositsDetails['amount'],
                'remarks' => $ciReportBankDepositsDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportAppraisalSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_appraisal_source_id = isset($_POST['ci_report_appraisal_source_id']) ? htmlspecialchars($_POST['ci_report_appraisal_source_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $ci_report_bank_id = $_POST['ci_report_bank_id'];
        $source = $_POST['ci_appraisal_source_source'];
        $amount = $_POST['ci_appraisal_source_amount'];
        $remarks = $_POST['ci_appraisal_source_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBankExist = $this->ciReportModel->checkCIReportAppraisalSourceExist($ci_report_appraisal_source_id);
        $total = $checkCIReportBankExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportAppraisalSource($ci_report_appraisal_source_id, $ci_report_bank_id, $ci_report_id, $source, $amount, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        } else {
            $this->ciReportModel->insertCIReportAppraisalSource($ci_report_bank_id, $ci_report_id, $source, $amount, $remarks, $userID);
            
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteCIReportAppraisalSource() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_appraisal_source_id = isset($_POST['ci_report_appraisal_source_id']) ? htmlspecialchars($_POST['ci_report_appraisal_source_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportAppraisalSourceExist = $this->ciReportModel->checkCIReportAppraisalSourceExist($ci_report_appraisal_source_id);
        $total = $checkCIReportAppraisalSourceExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportAppraisalSource($ci_report_appraisal_source_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportAppraisalSourceDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_appraisal_source_id']) && !empty($_POST['ci_report_appraisal_source_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_appraisal_source_id = $_POST['ci_report_appraisal_source_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportAppraisalSourceDetails = $this->ciReportModel->getCIReportAppraisalSource($ci_report_appraisal_source_id);

            $response = [
                'success' => true,
                'source' => $ciReportAppraisalSourceDetails['source'],
                'amount' => $ciReportAppraisalSourceDetails['amount'],
                'remarks' => $ciReportAppraisalSourceDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_bank_id = isset($_POST['ci_report_bank_id']) ? htmlspecialchars($_POST['ci_report_bank_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $ci_bank_bank_id = $_POST['ci_bank_bank_id'];
        $ci_bank_bank_account_type_id = $_POST['ci_bank_bank_account_type_id'];
        $ci_bank_account_name = $_POST['ci_bank_account_name'];
        $ci_bank_account_number = $_POST['ci_bank_account_number'];
        $ci_bank_currency_id = $_POST['ci_bank_currency_id'];
        $ci_bank_bank_handling_type_id = $_POST['ci_bank_bank_handling_type_id'];
        $ci_bank_date_open = $this->systemModel->checkDate('empty', $_POST['ci_bank_date_open'], '', 'Y-m-d', '');
        $ci_bank_bank_adb = $_POST['ci_bank_bank_adb'];
        $ci_bank_informant = $_POST['ci_bank_informant'];
        $remarks = $_POST['ci_bank_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBankExist = $this->ciReportModel->checkCIReportBankExist($ci_report_bank_id);
        $total = $checkCIReportBankExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportBank(
                $ci_report_bank_id, $ci_report_id, $ci_bank_bank_id, $ci_bank_account_name, $ci_bank_account_number,
                $ci_bank_bank_account_type_id, $ci_bank_currency_id, $ci_bank_bank_handling_type_id, $ci_bank_date_open, $ci_bank_bank_adb,
                $ci_bank_informant, $remarks, $userID
            );
            
            echo json_encode(['success' => true]);
            exit;
        } else {
            $this->ciReportModel->insertCIReportBank(
                $ci_report_id, $ci_bank_bank_id, $ci_bank_account_name, $ci_bank_account_number, $ci_bank_bank_account_type_id,
                $ci_bank_currency_id, $ci_bank_bank_handling_type_id, $ci_bank_date_open, $ci_bank_bank_adb,
                $ci_bank_informant, $remarks, $userID
            );
            
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function deleteCIReportBank() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_bank_id = isset($_POST['ci_report_bank_id']) ? htmlspecialchars($_POST['ci_report_bank_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportBankExist = $this->ciReportModel->checkCIReportBankExist($ci_report_bank_id);
        $total = $checkCIReportBankExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportBank($ci_report_bank_id);
            
        echo json_encode(['success' => true]);
        exit;
    }

    public function getCIReportBankDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_bank_id']) && !empty($_POST['ci_report_bank_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_bank_id = $_POST['ci_report_bank_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportBankDetails = $this->ciReportModel->getCIReportBank($ci_report_bank_id);

            $response = [
                'success' => true,
                'bank_id' => $ciReportBankDetails['bank_id'],
                'account_name' => $ciReportBankDetails['account_name'],
                'account_number' => $ciReportBankDetails['account_number'],
                'bank_account_type_id' => $ciReportBankDetails['bank_account_type_id'],
                'currency_id' => $ciReportBankDetails['currency_id'],
                'bank_handling_type_id' => $ciReportBankDetails['bank_handling_type_id'],
                'date_open' =>  $this->systemModel->checkDate('empty', $ciReportBankDetails['date_open'], '', 'm/d/Y', ''),
                'adb' => $ciReportBankDetails['adb'],
                'informant' => $ciReportBankDetails['informant'],
                'remarks' => $ciReportBankDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportLoan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_loan_id = isset($_POST['ci_report_loan_id']) ? htmlspecialchars($_POST['ci_report_loan_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $company = $_POST['ci_loan_company'];
        $informant = $_POST['ci_loan_informant'];
        $account_name = $_POST['ci_loan_account_name'];
        $loan_type_id = $_POST['ci_loan_loan_type_id'];
        $availed_date = $this->systemModel->checkDate('empty', $_POST['ci_loan_availed_date'], '', 'Y-m-d', '');
        $maturity_date = $this->systemModel->checkDate('empty', $_POST['ci_loan_maturity_date'], '', 'Y-m-d', '');
        $term = $_POST['ci_loan_term'];
        $pn_amount = $_POST['ci_loan_pn_amount'];
        $outstanding_balance = $_POST['ci_loan_outstanding_balance'];
        $repayment = $_POST['ci_loan_repayment'];
        $handling = $_POST['ci_loan_handling'];
        $remarks = $_POST['ci_loan_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportLoanExist = $this->ciReportModel->checkCIReportLoanExist($ci_report_loan_id);
        $total = $checkCIReportLoanExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportLoan(
                $ci_report_loan_id, $ci_report_id, $company, $informant, $account_name,
                $loan_type_id, $availed_date, $maturity_date, $term, $pn_amount,
                $outstanding_balance, $repayment, $handling, $remarks, $userID
            );
        } else {
            $this->ciReportModel->insertCIReportLoan(
                $ci_report_id, $company, $informant, $account_name, $loan_type_id,
                $availed_date, $maturity_date, $term, $pn_amount, $outstanding_balance,
                $repayment, $handling, $remarks, $userID
            );
        }

        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteCIReportLoan() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_loan_id = isset($_POST['ci_report_loan_id']) ? htmlspecialchars($_POST['ci_report_loan_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportLoanExist = $this->ciReportModel->checkCIReportLoanExist($ci_report_loan_id);
        $total = $checkCIReportLoanExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportLoan($ci_report_loan_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function getCIReportLoanDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_loan_id']) && !empty($_POST['ci_report_loan_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_loan_id = $_POST['ci_report_loan_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportLoanDetails = $this->ciReportModel->getCIReportLoan($ci_report_loan_id);

            $response = [
                'success' => true,
                'company' => $ciReportLoanDetails['company'],
                'informant' => $ciReportLoanDetails['informant'],
                'account_name' => $ciReportLoanDetails['account_name'],
                'loan_type_id' => $ciReportLoanDetails['loan_type_id'],
                'availed_date' =>  $this->systemModel->checkDate('empty', $ciReportLoanDetails['availed_date'], '', 'm/d/Y', ''),
                'maturity_date' =>  $this->systemModel->checkDate('empty', $ciReportLoanDetails['maturity_date'], '', 'm/d/Y', ''),
                'term' => $ciReportLoanDetails['term'],
                'pn_amount' => $ciReportLoanDetails['pn_amount'],
                'outstanding_balance' => $ciReportLoanDetails['outstanding_balance'],
                'repayment' => $ciReportLoanDetails['repayment'],
                'handling' => $ciReportLoanDetails['handling'],
                'remarks' => $ciReportLoanDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportCMAP() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_cmap_id = isset($_POST['ci_report_cmap_id']) ? htmlspecialchars($_POST['ci_report_cmap_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $cmap_report_type_id = $_POST['ci_cmap_cmap_report_type_id'];
        $defendant = $_POST['ci_cmap_defendant'];
        $plaintiff = $_POST['ci_cmap_plaintiff'];
        $nature_of_case = $_POST['ci_cmap_nature_of_case'];
        $trial_court = $_POST['ci_cmap_trial_court'];
        $sala_no = $_POST['ci_cmap_sala_no'];
        $case_no = $_POST['ci_cmap_case_no'];
        $reported_date = $this->systemModel->checkDate('empty', $_POST['ci_cmap_reported_date'], '', 'Y-m-d', '');
        $remarks = $_POST['ci_cmap_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportCMAPExist = $this->ciReportModel->checkCIReportCMAPExist($ci_report_cmap_id);
        $total = $checkCIReportCMAPExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportCMAP(
                $ci_report_cmap_id, $ci_report_id, $cmap_report_type_id, $defendant, $plaintiff,
                $nature_of_case, $trial_court, $sala_no, $case_no, $reported_date,
                $remarks, $userID
            );
        } else {
            $this->ciReportModel->insertCIReportCMAP(
                $ci_report_id, $cmap_report_type_id, $defendant, $plaintiff, $nature_of_case,
                $trial_court, $sala_no, $case_no, $reported_date, $remarks, $userID
            );
        }

        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteCIReportCMAP() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_cmap_id = isset($_POST['ci_report_cmap_id']) ? htmlspecialchars($_POST['ci_report_cmap_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportCMAPExist = $this->ciReportModel->checkCIReportCMAPExist($ci_report_cmap_id);
        $total = $checkCIReportCMAPExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportCMAP($ci_report_cmap_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function getCIReportCMAPDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_cmap_id']) && !empty($_POST['ci_report_cmap_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_cmap_id = $_POST['ci_report_cmap_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportCMAPDetails = $this->ciReportModel->getCIReportCMAP($ci_report_cmap_id);

            $response = [
                'success' => true,
                'cmap_report_type_id' => $ciReportCMAPDetails['cmap_report_type_id'],
                'defendants' => $ciReportCMAPDetails['defendants'],
                'plaintiff' => $ciReportCMAPDetails['plaintiff'],
                'nature_of_case' => $ciReportCMAPDetails['nature_of_case'],
                'trial_court' => $ciReportCMAPDetails['trial_court'],
                'sala_no' => $ciReportCMAPDetails['sala_no'],
                'case_no' => $ciReportCMAPDetails['case_no'],
                'reported_date' =>  $this->systemModel->checkDate('empty', $ciReportCMAPDetails['reported_date'], '', 'm/d/Y', ''),
                'remarks' => $ciReportCMAPDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------

    public function saveCIReportCollateral() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_collateral_id = isset($_POST['ci_report_collateral_id']) ? htmlspecialchars($_POST['ci_report_collateral_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $appraisal_date = $this->systemModel->checkDate('empty', $_POST['ci_collateral_appraisal_date'], '', 'Y-m-d', '');
        $brand_id = $_POST['ci_collateral_brand_id'];
        $description = $_POST['ci_collateral_description'];
        $color_id = $_POST['ci_collateral_color_id'];
        $year_model = $_POST['ci_collateral_year_model'];
        $plate_no = $_POST['ci_collateral_plate_no'];
        $motor_no = $_POST['ci_collateral_motor_no'];
        $serial_no = $_POST['ci_collateral_serial_no'];
        $mvr_file_no = $_POST['ci_collateral_mvr_file_no'];
        $cr_no = $_POST['ci_collateral_cr_no'];
        $or_no = $_POST['ci_collateral_or_no'];
        $registered_owner = $_POST['ci_collateral_registered_owner'];
        $appraised_value = $_POST['ci_collateral_appraised_value'];
        $loannable_value = $_POST['ci_collateral_loannable_value'];
        $remarks = $_POST['ci_collateral_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportCollateralExist = $this->ciReportModel->checkCIReportCollateralExist($ci_report_collateral_id);
        $total = $checkCIReportCollateralExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportCollateral(
                $ci_report_collateral_id, $ci_report_id, $appraisal_date, $brand_id, $description, $color_id,
                $year_model, $plate_no, $motor_no, $serial_no, $mvr_file_no,
                $cr_no, $or_no, $registered_owner, $appraised_value, $loannable_value,
                $remarks, $userID
            );
        } else {
            $this->ciReportModel->insertCIReportCollateral(
                $ci_report_id, $appraisal_date, $brand_id, $description, $color_id,
                $year_model, $plate_no, $motor_no, $serial_no, $mvr_file_no,
                $cr_no, $or_no, $registered_owner, $appraised_value, $loannable_value,
                $remarks, $userID
            );
        }


        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteCIReportCollateral() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_collateral_id = isset($_POST['ci_report_collateral_id']) ? htmlspecialchars($_POST['ci_report_collateral_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportCollateralExist = $this->ciReportModel->checkCIReportCollateralExist($ci_report_collateral_id);
        $total = $checkCIReportCollateralExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportCollateral($ci_report_collateral_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function getCIReportCollateralDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_collateral_id']) && !empty($_POST['ci_report_collateral_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_collateral_id = $_POST['ci_report_collateral_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportCollateralDetails = $this->ciReportModel->getCIReportCollateral($ci_report_collateral_id);

            $response = [
                'success' => true,
                'appraisal_date' =>  $this->systemModel->checkDate('empty', $ciReportCollateralDetails['appraisal_date'], '', 'm/d/Y', ''),
                'brand_id' => $ciReportCollateralDetails['brand_id'],
                'description' => $ciReportCollateralDetails['description'],
                'color_id' => $ciReportCollateralDetails['color_id'],
                'year_model' => $ciReportCollateralDetails['year_model'],
                'plate_no' => $ciReportCollateralDetails['plate_no'],
                'motor_no' => $ciReportCollateralDetails['motor_no'],
                'serial_no' => $ciReportCollateralDetails['serial_no'],
                'mvr_file_no' => $ciReportCollateralDetails['mvr_file_no'],
                'cr_no' => $ciReportCollateralDetails['cr_no'],
                'or_no' => $ciReportCollateralDetails['or_no'],
                'registered_owner' => $ciReportCollateralDetails['registered_owner'],
                'appraised_value' => $ciReportCollateralDetails['appraised_value'],
                'loannable_value' => $ciReportCollateralDetails['loannable_value'],
                'remarks' => $ciReportCollateralDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }
    
    # -------------------------------------------------------------
    
    public function saveCIReportAsset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_asset_id = isset($_POST['ci_report_asset_id']) ? htmlspecialchars($_POST['ci_report_asset_id'], ENT_QUOTES, 'UTF-8') : null;
        $ci_report_id = $_POST['ci_report_id'];
        $asset_type_id = $_POST['ci_asset_asset_type_id'];
        $description = $_POST['ci_asset_description'];
        $value = $_POST['ci_asset_value'];
        $remarks = $_POST['ci_asset_remarks'];
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportAssetExist = $this->ciReportModel->checkCIReportAssetExist($ci_report_asset_id);
        $total = $checkCIReportAssetExist['total'] ?? 0;
    
        if ($total > 0) {
            $this->ciReportModel->updateCIReportAsset($ci_report_asset_id, $ci_report_id, $asset_type_id, $description, $value, $remarks, $userID);
        } else {
            $this->ciReportModel->insertCIReportAsset($ci_report_id, $asset_type_id, $description, $value, $remarks, $userID);
        }


        echo json_encode(['success' => true]);
        exit;
    }
    public function deleteCIReportAsset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
       if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $ci_report_asset_id = isset($_POST['ci_report_asset_id']) ? htmlspecialchars($_POST['ci_report_asset_id'], ENT_QUOTES, 'UTF-8') : null;
       
    
        $user = $this->userModel->getUserByID($userID);
    
        if (!$user || !$user['is_active']) {
            echo json_encode(['success' => false, 'isInactive' => true]);
            exit;
        }
    
        $checkCIReportAssetExist = $this->ciReportModel->checkCIReportAssetExist($ci_report_asset_id);
        $total = $checkCIReportAssetExist['total'] ?? 0;
    
        if($total === 0){
            echo json_encode(['success' => false, 'notExist' =>  true]);
            exit;
        }

        $this->ciReportModel->deleteCIReportAsset($ci_report_asset_id);
            
        echo json_encode(['success' => true]);
        exit;
    }
    public function getCIReportAssetDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_asset_id']) && !empty($_POST['ci_report_asset_id'])) {
            $userID = $_SESSION['user_id'];
            $ci_report_asset_id = $_POST['ci_report_asset_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportAssetDetails = $this->ciReportModel->getCIReportAsset($ci_report_asset_id);

            $response = [
                'success' => true,
                'asset_type_id' => $ciReportAssetDetails['asset_type_id'],
                'description' => $ciReportAssetDetails['description'],
                'value' => $ciReportAssetDetails['value'],
                'remarks' => $ciReportAssetDetails['remarks'],
            ];

            echo json_encode($response);
            exit;
        }
    }

    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCIReportDetails
    # Description: 
    # Handles the retrieval of ci report details such as current level, etc.
    #
    # Parameters: None
    #
    # Returns: Array
    #
    # -------------------------------------------------------------
    public function getCIReportDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }
    
        if (isset($_POST['ci_report_id']) && !empty($_POST['ci_report_id'])) {
            $userID = $_SESSION['user_id'];
            $ciReportID = $_POST['ci_report_id'];
    
            $user = $this->userModel->getUserByID($userID);
    
            if (!$user || !$user['is_active']) {
                echo json_encode(['success' => false, 'isInactive' => true]);
                exit;
            }
    
            $ciReportDetails = $this->ciReportModel->getCIReport($ciReportID);

            $response = [
                'success' => true,
                'appraiser' => $ciReportDetails['appraiser'],
                'investigator' => $ciReportDetails['investigator'],
                'narrative_summary' => $ciReportDetails['narrative_summary']
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
require_once '../model/ci-report-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';

$controller = new CIReportController(new CIReportModel(new DatabaseModel), new UserModel(new DatabaseModel, new SystemModel), new SystemModel(), new SecurityModel());
$controller->handleRequest();
?>