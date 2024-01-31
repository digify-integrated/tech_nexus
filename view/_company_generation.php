<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/company-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
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
        # Type: company table
        # Description:
        # Generates the company table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'company table':
            if(isset($_POST['filter_city'])){
                $filterCity = htmlspecialchars($_POST['filter_city'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateCompanyTable(:filterCity)');
                $sql->bindValue(':filterCity', $filterCity, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
    
                $companyDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');
    
                foreach ($options as $row) {
                    $companyID = $row['company_id'];
                    $companyName = $row['company_name'];
                    $address = $row['address'];
                    $cityID = $row['city_id'];
                    $companyLogo = $systemModel->checkImage($row['company_logo'], 'company logo');
    
                    $cityDetails = $cityModel->getCity($cityID);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];
    
                    $companyAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;
    
                    $companyIDEncrypted = $securityModel->encryptData($companyID);
    
                    $delete = '';
                    if($companyDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-company" data-company-id="'. $companyID .'" title="Delete Company">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $companyID .'">',
                        'COMPANY_NAME' => '<div class="row">
                                            <div class="col-auto pe-0">
                                                <img src="'. $companyLogo .'" alt="user-image" class="wid-40 hei-40 rounded-circle">
                                            </div>
                                            <div class="col">
                                                <h6 class="mb-0">'. $companyName .'</h6>
                                                <p class="f-12 mb-0">'. $companyAddress .'</p>
                                            </div>
                                        </div>',
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="company.php?id='. $companyIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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