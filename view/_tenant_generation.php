<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/tenant-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$tenantModel = new TenantModel($databaseModel);
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
        # Type: tenant table
        # Description:
        # Generates the tenant table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'tenant table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateTenantTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $tenantDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 79, 'delete');

            foreach ($options as $row) {
                $tenantID = $row['tenant_id'];
                $tenantName = $row['tenant_name'];
                $address = $row['address'];
                $cityID = $row['city_id'];

                $cityDetails = $cityModel->getCity($cityID);
                $cityName = $cityDetails['city_name'];
                $stateID = $cityDetails['state_id'];

                $stateDetails = $stateModel->getState($stateID);
                $stateName = $stateDetails['state_name'];
                $countryID = $stateDetails['country_id'];

                $countryName = $countryModel->getCountry($countryID)['country_name'];

                $tenantAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                $tenantIDEncrypted = $securityModel->encryptData($tenantID);

                $delete = '';
                if($tenantDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-tenant" data-tenant-id="'. $tenantID .'" title="Delete Tenant">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $tenantID .'">',
                    'TENANT_NAME' => '<div class="row">
                                        <div class="col">
                                            <h6 class="mb-0">'. $tenantName .'</h6>
                                            <p class="f-12 mb-0">'. $tenantAddress .'</p>
                                        </div>
                                    </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="tenant.php?id='. $tenantIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
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