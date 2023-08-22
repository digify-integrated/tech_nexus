<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
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
        # Type: state table
        # Description:
        # Generates the state table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'state table':
            if(isset($_POST['filter_country'])){
                $filterCountry = htmlspecialchars($_POST['filter_country'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateStateTable(:filterCountry)');
                $sql->bindValue(':filterCountry', $filterCountry, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $stateDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 14, 'delete');

                foreach ($options as $row) {
                    $stateID = $row['state_id'];
                    $stateName = $row['state_name'];
                    $countryID = $row['country_id'];

                    $countryName = $countryModel->getCountry($countryID)['country_name'];

                    $stateIDEncrypted = $securityModel->encryptData($stateID);

                    $delete = '';
                    if($stateDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-state" data-state-id="'. $stateID .'" title="Delete System Setting">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $stateID .'">',
                        'STATE_NAME' => $stateName,
                        'COUNTRY_ID' => $countryName,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="state.php?id='. $stateIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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