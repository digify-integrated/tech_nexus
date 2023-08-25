<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: city table
        # Description:
        # Generates the city table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'city table':
            if(isset($_POST['filter_state'])){
                $filterState = htmlspecialchars($_POST['filter_state'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateCityTable(:filterState)');
                $sql->bindValue(':filterState', $filterState, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $cityDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 17, 'delete');

                foreach ($options as $row) {
                    $cityID = $row['city_id'];
                    $cityName = $row['city_name'];
                    $stateID = $row['state_id'];

                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
                    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];

                    $cityIDEncrypted = $securityModel->encryptData($cityID);

                    $delete = '';
                    if($cityDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-city" data-city-id="'. $cityID .'" title="Delete City">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $cityID .'">',
                        'CITY_NAME' => $cityName,
                        'STATE_ID' => $stateName,
                        'COUNTRY_ID' => $countryName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="city.php?id='. $cityIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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