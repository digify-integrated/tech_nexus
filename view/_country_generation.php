<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/country-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$countryModel = new CountryModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: country table
        # Description:
        # Generates the country table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'country table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateCountryTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $countryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 14, 'delete');

            foreach ($options as $row) {
                $countryID = $row['country_id'];
                $countryName = $row['country_name'];
                $countryCode = $row['country_code'];

                $countryIDEncrypted = $securityModel->encryptData($countryID);

                $delete = '';
                if($countryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-country" data-country-id="'. $countryID .'" title="Delete System Setting">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $countryID .'">',
                    'COUNTRY_NAME' => $countryName,
                    'COUNTRY_CODE' => $countryCode,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="country.php?id='. $countryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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