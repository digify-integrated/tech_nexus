<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/customer-model.php';
require_once '../model/company-model.php';
require_once '../model/gender-model.php';
require_once '../model/civil-status-model.php';
require_once '../model/religion-model.php';
require_once '../model/blood-type-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/job-level-model.php';
require_once '../model/branch-model.php';
require_once '../model/address-type-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/id-type-model.php';
require_once '../model/educational-stage-model.php';
require_once '../model/relation-model.php';
require_once '../model/language-model.php';
require_once '../model/language-proficiency-model.php';
require_once '../model/bank-model.php';
require_once '../model/work-schedule-model.php';
require_once '../model/bank-account-type-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/contact-information-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: customer card
        # Description:
        # Generates the customer card.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'customer card':
            if(isset($_POST['current_page']) && isset($_POST['customer_search']) && isset($_POST['gender_filter']) && isset($_POST['civil_status_filter']) && isset($_POST['blood_type_filter']) && isset($_POST['religion_filter']) && isset($_POST['age_filter'])){
                $initialEmployeesPerPage = 9;
                $loadMoreEmployeesPerPage = 6;
                $customerPerPage = $initialEmployeesPerPage;
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $customerSearch = htmlspecialchars($_POST['customer_search'], ENT_QUOTES, 'UTF-8');
                $genderFilter = htmlspecialchars($_POST['gender_filter'], ENT_QUOTES, 'UTF-8');
                $civilStatusFilter = htmlspecialchars($_POST['civil_status_filter'], ENT_QUOTES, 'UTF-8');
                $bloodTypeFilter = htmlspecialchars($_POST['blood_type_filter'], ENT_QUOTES, 'UTF-8');
                $religionFilter = htmlspecialchars($_POST['religion_filter'], ENT_QUOTES, 'UTF-8');
                $ageFilter = htmlspecialchars($_POST['age_filter'], ENT_QUOTES, 'UTF-8');
                $ageExplode = explode(',', $ageFilter);
                $minAge = $ageExplode[0];
                $maxAge = $ageExplode[1];
                $offset = ($currentPage - 1) * $customerPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateCustomerCard(:offset, :customerPerPage, :customerSearch, :genderFilter, :civilStatusFilter, :bloodTypeFilter, :religionFilter, :minAge, :maxAge)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':customerPerPage', $customerPerPage, PDO::PARAM_INT);
                $sql->bindValue(':customerSearch', $customerSearch, PDO::PARAM_STR);
                $sql->bindValue(':genderFilter', $genderFilter, PDO::PARAM_STR);
                $sql->bindValue(':civilStatusFilter', $civilStatusFilter, PDO::PARAM_STR);
                $sql->bindValue(':bloodTypeFilter', $bloodTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':religionFilter', $religionFilter, PDO::PARAM_STR);
                $sql->bindValue(':minAge', $minAge, PDO::PARAM_INT);
                $sql->bindValue(':maxAge', $maxAge, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $customerDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 68, 'delete');

                foreach ($options as $row) {
                    $contactID = $row['contact_id'];
                    $fileAs = $row['file_as'];
                    $customerID = $row['customer_id'];
                    $customerStatus = $row['contact_status'];
                    $customerImage = $systemModel->checkImage($row['contact_image'], 'profile');
                   
                    $customerIDEncrypted = $securityModel->encryptData($contactID);

                    $delete = '';
                    if($customerDeleteAccess['total'] > 0){
                        $delete = '<div class="btn-prod-cart card-body position-absolute end-0 bottom-0">
                                        <button class="btn btn-danger delete-customer" data-customer-id="'. $contactID .'" title="Delete Customer">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }
    
                    $response[] = [
                        'customerCard' => '<div class="col-sm-6 col-xl-4">
                                                <div class="card product-card">
                                                    <div class="card-img-top">
                                                        <a href="customer.php?id='. $customerIDEncrypted .'">
                                                            <img src="'. $customerImage .'" alt="image" class="img-prod img-fluid" />
                                                        </a>
                                                        <div class="card-body position-absolute start-0 top-0">
                                                           '. $customerStatus .'
                                                        </div>
                                                        '. $delete .'
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="customer.php?id='. $customerIDEncrypted .'">
                                                            <div class="d-flex align-items-center justify-content-between mt-3">
                                                                <h4 class="mb-0 text-truncate text-primary"><b>'. $fileAs .'</b></h4>
                                                            </div>
                                                            <div class="mt-3">
                                                                <h6 class="prod-content mb-0">'. $customerID .'</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>'
                    ];
                }

                if ($customerPerPage === $initialEmployeesPerPage) {
                    $customerPerPage = $loadMoreEmployeesPerPage;
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>