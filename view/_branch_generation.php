<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/branch-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$branchModel = new BranchModel($databaseModel);
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
        # Type: branch table
        # Description:
        # Generates the branch table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'branch table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBranchTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $branchDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 26, 'delete');

            foreach ($options as $row) {
                $branchID = $row['branch_id'];
                $branchName = $row['branch_name'];
                $address = $row['address'];
                $cityID = $row['city_id'];

                $cityDetails = $cityModel->getCity($cityID);
                $cityName = $cityDetails['city_name'];
                $stateID = $cityDetails['state_id'];

                $stateDetails = $stateModel->getState($stateID);
                $stateName = $stateDetails['state_name'];
                $countryID = $stateDetails['country_id'];

                $countryName = $countryModel->getCountry($countryID)['country_name'];

                $branchAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                $branchIDEncrypted = $securityModel->encryptData($branchID);

                $delete = '';
                if($branchDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-branch" data-branch-id="'. $branchID .'" title="Delete Branch">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $branchID .'">',
                    'BRANCH_NAME' => '<div class="row">
                                        <div class="col">
                                            <h6 class="mb-0">'. $branchName .'</h6>
                                            <p class="f-12 mb-0">'. $branchAddress .'</p>
                                        </div>
                                    </div>',
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="branch.php?id='. $branchIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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