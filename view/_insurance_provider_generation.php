<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-provider-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insuranceProviderModel = new InsuranceProviderModel($databaseModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: insurance provider table
        # Description:
        # Generates the insurance provider table using dynamic raw queries.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'insurance provider table':
            if(isset($_POST['filter_city'])){
                $filterCity = htmlspecialchars($_POST['filter_city'], ENT_QUOTES, 'UTF-8');

                // Base dynamic SQL statement structural construction
                $queryString = 'SELECT provider_id, provider_name, address, city_id FROM insurance_provider WHERE 1';
                $params = [];

                // Replicating stored procedure logic safely without raw string concatenations
                if (!empty($filterCity)) {
                    // Explode comma-separated IDs if multiple filters are supplied
                    $cityIDs = array_map('trim', explode(',', $filterCity));
                    $placeholders = [];
                    
                    foreach ($cityIDs as $index => $id) {
                        $placeholder = ':city_id_' . $index;
                        $placeholders[] = $placeholder;
                        $params[$placeholder] = $id;
                    }
                    
                    if (!empty($placeholders)) {
                        $queryString .= ' AND city_id IN (' . implode(',', $placeholders) . ')';
                    }
                }

                $queryString .= ' ORDER BY provider_name ASC;';

                $sql = $databaseModel->getConnection()->prepare($queryString);
                foreach ($params as $placeholder => $value) {
                    $sql->bindValue($placeholder, $value, PDO::PARAM_INT);
                }
                
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
    
                $insuranceProviderDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 196, 'delete');
    
                foreach ($options as $row) {
                    $insuranceProviderID = $row['provider_id'];
                    $insuranceProviderName = $row['provider_name'];
                    $address = $row['address'];
                    $cityID = $row['city_id'];
    
                    $cityDetails = $cityModel->getCity($cityID);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];
    
                    $insuranceProviderAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;
    
                    $insuranceProviderIDEncrypted = $securityModel->encryptData($insuranceProviderID);
    
                    $delete = '';
                    if($insuranceProviderDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-provider" data-insurance-provider-id="'. $insuranceProviderID .'" title="Delete Insurance Provider">
                                                <i class="ti ti-trash"></i>
                                            </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insuranceProviderID .'">',
                        'INSURANCE_PROVIDER_NAME' => '<div class="row">
                                            <div class="col">
                                                <h6 class="mb-0">'. $insuranceProviderName .'</h6>
                                                <p class="f-12 mb-0">'. $insuranceProviderAddress .'</p>
                                            </div>
                                        </div>',
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="insurance-provider.php?id='. $insuranceProviderIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        '. $delete .'
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        case 'coverage table':
            $insurance_provider_id = htmlspecialchars($_POST['insurance_provider_id'], ENT_QUOTES, 'UTF-8');
            $sql = $databaseModel->getConnection()->prepare('
                SELECT * 
                FROM insurance_coverage_rate 
                WHERE insurance_provider = :insurance_provider_id
                ORDER BY coverage_amount_start ASC;
            ');
            $sql->bindValue(':insurance_provider_id', $insurance_provider_id, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();
    
            foreach ($options as $row) {
                $insurance_coverage_rate_id = $row['insurance_coverage_rate_id'];
                $coverageAmountStart = $row['coverage_amount_start'] ?? 0;
                $coverageAmountEnd = $row['coverage_amount_end'] ?? 0;
                $bi_rate = $row['bi_rate'] ?? 0;
                $pd_amount = $row['pd_amount'] ?? 0;

                $delete = '<button type="button" class="btn btn-icon btn-danger delete-coverage" data-insurance-coverage-rate-id="'. $insurance_coverage_rate_id .'" title="Delete Coverage">
                                        <i class="ti ti-trash"></i>
                                    </button>';

                $response[] = [
                    'COVERAGE' => number_format($coverageAmountStart, 2) . ' - ' . number_format($coverageAmountEnd, 2),
                    'BI' => number_format($bi_rate, 2),
                    'PD' => number_format($pd_amount, 2),
                    'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success update-coverage" data-bs-toggle="offcanvas" data-bs-target="#coverage-offcanvas" data-insurance-coverage-rate-id="'. $insurance_coverage_rate_id .'" title="Update Coverage">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        '. $delete .'
                                    </div>'
                ];
            }
    
            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}
?>