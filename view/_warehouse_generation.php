<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/warehouse-model.php';
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$warehouseModel = new WarehouseModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
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
        # Type: warehouse table
        # Description:
        # Generates the warehouse table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'warehouse table':
            if(isset($_POST['filter_company']) && isset($_POST['filter_city'])){
                $filterCompany = htmlspecialchars($_POST['filter_company'], ENT_QUOTES, 'UTF-8');
                $filterCity = htmlspecialchars($_POST['filter_city'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateWarehouseTable(:filterCompany, :filterCity)');
                $sql->bindValue(':filterCompany', $filterCompany, PDO::PARAM_STR);
                $sql->bindValue(':filterCity', $filterCity, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
    
                $warehouseDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 26, 'delete');
    
                foreach ($options as $row) {
                    $warehouseID = $row['warehouse_id'];
                    $warehouseName = $row['warehouse_name'];
                    $address = $row['address'];
                    $cityID = $row['city_id'];
                    $companyID = $row['company_id'];
    
                    $companyDetails = $companyModel->getCompany($companyID);
                    $companyName = $companyDetails['company_name'] ?? null;
    
                    $cityDetails = $cityModel->getCity($cityID);
                    $cityName = $cityDetails['city_name'] ?? null;
                    $stateID = $cityDetails['state_id'] ?? null;
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'] ?? null;
                    $countryID = $stateDetails['country_id'] ?? null;
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];
    
                    $warehouseAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;
    
                    $warehouseIDEncrypted = $securityModel->encryptData($warehouseID);
    
                    $delete = '';
                    if($warehouseDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-warehouse" data-warehouse-id="'. $warehouseID .'" title="Delete Warehouse">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $warehouseID .'">',
                        'WAREHOUSE_NAME' => '<div class="row">
                                            <div class="col">
                                                <h6 class="mb-0">'. $warehouseName .'</h6>
                                                <p class="f-12 mb-0">'. $warehouseAddress .'</p>
                                            </div>
                                        </div>',
                        'COMPANY_NAME' => $companyName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="warehouse.php?id='. $warehouseIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
    }
}

?>