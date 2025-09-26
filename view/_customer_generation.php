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
require_once '../model/address-type-model.php';
require_once '../model/id-type-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/contact-information-type-model.php';
require_once '../model/relation-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/contact-information-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$civilStatusModel = new CivilStatusModel($databaseModel);
$religionModel = new ReligionModel($databaseModel);
$bloodTypeModel = new BloodTypeModel($databaseModel);
$genderModel = new GenderModel($databaseModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$idTypeModel = new IDTypeModel($databaseModel);
$addressTypeModel = new AddressTypeModel($databaseModel);
$contactInformationTypeModel = new ContactInformationTypeModel($databaseModel);
$relationModel = new RelationModel($databaseModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
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
                $customerStatusFilter = htmlspecialchars($_POST['customer_status_filter'], ENT_QUOTES, 'UTF-8');
                $genderFilter = htmlspecialchars($_POST['gender_filter'], ENT_QUOTES, 'UTF-8');
                $civilStatusFilter = htmlspecialchars($_POST['civil_status_filter'], ENT_QUOTES, 'UTF-8');
                $bloodTypeFilter = htmlspecialchars($_POST['blood_type_filter'], ENT_QUOTES, 'UTF-8');
                $religionFilter = htmlspecialchars($_POST['religion_filter'], ENT_QUOTES, 'UTF-8');
                $ageFilter = htmlspecialchars($_POST['age_filter'], ENT_QUOTES, 'UTF-8');
                $ageExplode = explode(',', $ageFilter);
                $minAge = $ageExplode[0];
                $maxAge = $ageExplode[1];
                $offset = ($currentPage - 1) * $customerPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateCustomerCard(:offset, :customerPerPage, :customerSearch, :customerStatusFilter, :genderFilter, :civilStatusFilter, :bloodTypeFilter, :religionFilter, :minAge, :maxAge)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':customerPerPage', $customerPerPage, PDO::PARAM_INT);
                $sql->bindValue(':customerStatusFilter', $customerStatusFilter, PDO::PARAM_STR);
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
                    $customerStatus = $customerModel->getCustomerStatus($row['contact_status']);
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

        # -------------------------------------------------------------
        #
        # Type: personal information summary
        # Description:
        # Generates the personal information summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'personal information summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $customerDetails = $customerModel->getCustomer($customerID);
                $customerUniqueID = $customerDetails['customer_id'];

                $sql = $databaseModel->getConnection()->prepare('CALL generatePersonalInformationSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $index => $row) {
                    $fileAs = $row['file_as'];
                    $nickname = $row['nickname'] ?? '--';
                    $corporateName = $row['corporate_name'] ?? '--';
                    $civilStatusID = $row['civil_status_id'];
                    $genderID = $row['gender_id'];
                    $religionID = $row['religion_id'];
                    $bloodTypeID = $row['blood_type_id'];
                    $birthday = $systemModel->checkDate('summary', $row['birthday'], '', 'F d, Y', '');
                    $birthPlace = $row['birth_place'] ?? '--';
                    $height = !empty($row['height']) ? $row['height'] . ' cm' : '--';
                    $weight = !empty($row['weight']) ? $row['weight'] . ' kg' : '--';

                    $civilStatusName = $civilStatusModel->getCivilStatus($civilStatusID)['civil_status_name'] ?? '--';
                    $religionName = $religionModel->getReligion($religionID)['religion_name'] ?? '--';
                    $bloodTypeName = $bloodTypeModel->getBloodType($bloodTypeID)['blood_type_name'] ?? '--';
                    $genderName = $genderModel->getGender($genderID)['gender_name'] ?? '--';

                    $details .= '<ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Customer ID</b></p>
                                                <p class="mb-0">'. $customerUniqueID .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Business/Corporate Name</b></p>
                                                <p class="mb-0">'. $corporateName .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Nickname</b></p>
                                                <p class="mb-0">'. $nickname .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Birthday</b></p>
                                                <p class="mb-0">'. $birthday .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Birth Place</b></p>
                                                <p class="mb-0">'. $birthPlace .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Gender</b></p>
                                                <p class="mb-0">'. $genderName .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Civil Status</b></p>
                                                <p class="mb-0">'. $civilStatusName .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Blood type</b></p>
                                                <p class="mb-0">'. $bloodTypeName .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Religion</b></p>
                                                <p class="mb-0">'. $religionName .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Height</b></p>
                                                <p class="mb-0">'. $height .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Weight</b></p>
                                                <p class="mb-0">'. $weight .'</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>';
                }

                if(empty($details)){
                    $details = 'No personal information found.';
                }

                $response[] = [
                    'personalInformationSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact information summary
        # Description:
        # Generates the contact information summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact information summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactInformationSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
                $updateEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 100);
                $deleteEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 101);
                $tagEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 102);

                foreach ($options as $index => $row) {
                    $contactInformationID = $row['contact_information_id'];
                    $contactInformationTypeID = $row['contact_information_type_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $facebook = $row['facebook'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success mt-3">Primary</span>' : '<span class="badge bg-light-info mt-3">Alternate</span>';

                    $contactInformationTypeName = $contactInformationTypeModel->getContactInformationType($contactInformationTypeID)['contact_information_type_name'] ?? null;

                    $mobile = !empty($mobile) ? '<p class="mb-0"><i class="ti ti-device-mobile me-2"></i> ' . $mobile . '</p>' : '';
                    $email = !empty($email) ? '<p class="mb-0"><i class="ti ti-mail me-2"></i> ' . $email . '</p>' : '';
                    $telephone = !empty($telephone) ? '<p class="mb-0"><i class="ti ti-phone me-2"></i> ' . $telephone . '</p>' : '';
                    $facebook = !empty($facebook) ? '<p class="mb-0"><i class="ti ti-brand-facebook me-2"></i> ' . $facebook . '</p>' : '';

                    $dropdown = '';
                    if ($customerWriteAccess['total'] > 0) {
                        $update = ($customerWriteAccess['total'] > 0 && $updateEmployeeContactInformation['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-information" data-bs-toggle="offcanvas" data-bs-target="#contact-information-offcanvas" aria-controls="contact-information-offcanvas" data-contact-information-id="' . $contactInformationID . '">Edit</a>' : '';
                    
                        $tag = ($customerWriteAccess['total'] > 0 && $tagEmployeeContactInformation['total'] > 0 && !$isPrimary) ? '<a href="javascript:void(0);" class="dropdown-item tag-contact-information-as-primary" data-contact-information-id="' . $contactInformationID . '">Tag As Primary</a>' : '';
                    
                        $delete = ($customerWriteAccess['total'] > 0 && $deleteEmployeeContactInformation['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-contact-information" data-contact-information-id="' . $contactInformationID . '">Delete</a>' : '';
                    
                        $dropdown = ($update || $tag || $delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $tag . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }
                    
                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-1 text-primary"><b>'. $contactInformationTypeName .'</b></p>
                                            '. $facebook .'
                                            '. $email .'
                                            '. $mobile .'
                                            '. $telephone .'
                                            '. $isPrimaryBadge .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No contact information found.';
                }

                $response[] = [
                    'contactInformationSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact address summary
        # Description:
        # Generates the contact address summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact address summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $customerDetails = $customerModel->getCustomer($customerID);
                $customerStatus = $customerDetails['contact_status'];

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactAddressSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
                $updateEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 104);
                $deleteEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 105);
                $tagEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 106);

                foreach ($options as $index => $row) {
                    $contactAddressID = $row['contact_address_id'];
                    $addressTypeID = $row['address_type_id'];
                    $address = $row['address'];
                    $cityID = $row['city_id'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Alternate</span>';

                    $cityDetails = $cityModel->getCity($cityID);
                    $cityName = $cityDetails['city_name'];
                    $stateID = $cityDetails['state_id'];
    
                    $stateDetails = $stateModel->getState($stateID);
                    $stateName = $stateDetails['state_name'];
                    $countryID = $stateDetails['country_id'];
    
                    $countryName = $countryModel->getCountry($countryID)['country_name'];
    
                    $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;

                    $addressTypeName = $addressTypeModel->getAddressType($addressTypeID)['address_type_name'] ?? null;

                    $dropdown = '';
                    if ($customerWriteAccess['total'] > 0 && ($customerStatus == 'Draft' || $customerStatus == 'For Updating')) {
                        $update = ($customerWriteAccess['total'] > 0 && $updateEmployeeAddress['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-address" data-bs-toggle="offcanvas" data-bs-target="#contact-address-offcanvas" aria-controls="contact-address-offcanvas" data-contact-address-id="'. $contactAddressID . '">Edit</a>' : '';
                    
                        $tag = ($customerWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0 && !$isPrimary) ? '<a href="javascript:void(0);" class="dropdown-item tag-contact-address-as-primary" data-contact-address-id="'. $contactAddressID . '">Tag As Primary</a>' : '';
                    
                        $delete = ($customerWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-contact-address" data-contact-address-id="'. $contactAddressID . '">Delete</a>' : '';
                    
                        $dropdown = ($update || $tag || $delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $tag . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }

                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-2 text-primary"><b>'. $addressTypeName .'</b></p>
                                            <p class="mb-2">' . $contactAddress . '</p>
                                            '. $isPrimaryBadge .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No address found.';
                }

                $response[] = [
                    'contactAddressSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact identification summary
        # Description:
        # Generates the contact identification summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact identification summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactIdentificationSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
                $updateEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 108);
                $deleteEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 109);
                $tagEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 110);

                foreach ($options as $index => $row) {
                    $contactIdentificationID = $row['contact_identification_id'];
                    $idTypeID = $row['id_type_id'];
                    $idNumber = $row['id_number'];
                    $isPrimary = $row['is_primary'];
                    $id_image = $row['id_image'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Secondary</span>';
    
                    $idTypeName = $idTypeModel->getIDType($idTypeID)['id_type_name'] ?? null;

                    $dropdown = '';
                    if ($customerWriteAccess['total'] > 0) {
                        $update = ($customerWriteAccess['total'] > 0 && $updateEmployeeContactIdentification['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-identification" data-bs-toggle="offcanvas" data-bs-target="#contact-identification-offcanvas" aria-controls="contact-identification-offcanvas" data-contact-identification-id="'. $contactIdentificationID . '">Edit</a>' : '';
                    
                        $tag = ($customerWriteAccess['total'] > 0 && $tagEmployeeContactIdentification['total'] > 0 && !$isPrimary) ? '<a href="javascript:void(0);" class="dropdown-item tag-contact-identification-as-primary" data-contact-identification-id="'. $contactIdentificationID . '">Tag As Primary</a>' : '';
                    
                        $delete = ($customerWriteAccess['total'] > 0 && $deleteEmployeeContactIdentification['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-contact-identification" data-contact-identification-id="'. $contactIdentificationID . '">Delete</a>' : '';
                    
                        $dropdown = ($update || $tag || $delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $tag . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }

                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-0">
                                            <img class="img-fluid wid-100 mr-4" src="'. $id_image .'" alt="User image" />
                                        </div>
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-2 text-primary"><b>'. $idTypeName .'</b></p>
                                            <p class="mb-2">' . $idNumber . '</p>
                                            '. $isPrimaryBadge .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No customer identification found.';
                }

                $response[] = [
                    'contactIdentificationSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact family background summary
        # Description:
        # Generates the contact family background summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact family background summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactFamilyBackgroundSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
                $updateEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 112);
                $deleteEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 113);

                foreach ($options as $index => $row) {
                    $contactFamilyBackgroundID = $row['contact_family_background_id'];
                    $familyName = $row['family_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $birthday = $systemModel->checkDate('empty', $row['birthday'], '', 'F d, Y', '');
                    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    $mobile = !empty($mobile) ? '<p class="mb-1"><i class="ti ti-device-mobile me-2"></i> ' . $mobile . '</p>' : '';
                    $email = !empty($email) ? '<p class="mb-1"><i class="ti ti-mail me-2"></i> ' . $email . '</p>' : '';
                    $telephone = !empty($telephone) ? '<p class="mb-1"><i class="ti ti-phone me-2"></i> ' . $telephone . '</p>' : '';

                    $dropdown = '';
                    if ($customerWriteAccess['total'] > 0) {
                        $update = ($customerWriteAccess['total'] > 0 && $updateEmployeeFamilyBackground['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-family-background" data-bs-toggle="offcanvas" data-bs-target="#contact-family-background-offcanvas" aria-controls="contact-family-background-offcanvas" data-contact-family-background-id="'. $contactFamilyBackgroundID . '">Edit</a>' : '';
                    
                        $delete = ($customerWriteAccess['total'] > 0 && $deleteEmployeeFamilyBackground['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-contact-family-background" data-contact-family-background-id="'. $contactFamilyBackgroundID . '">Delete</a>' : '';
                    
                        $dropdown = ($update || $delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }
                    
                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-1 text-primary"><b>'. $familyName .'</b></p>
                                            <p class="mb-3">' . $relationName . '</p>
                                            <p class="mb-1"><i class="ti ti-calendar-event me-2"></i> ' . $birthday . '</p>
                                            '. $email .'
                                            '. $mobile .'
                                            '. $telephone .'
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No family background found.';
                }

                $response[] = [
                    'contactFamilyBackgroundSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: search comaker result table
        # Description:
        # Generates the search comaker result table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'search comaker result table':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');
                $firstName = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
                $lastName = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateCustomerComakerSearchResultTable(:customerID, :firstName, :lastName)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->bindValue(':firstName', $firstName, PDO::PARAM_STR);
                $sql->bindValue(':lastName', $lastName, PDO::PARAM_STR);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $row) {
                    $contactID = $row['contact_id'];
                    $fileAs = $row['file_as'];

                    $response[] = [
                        'FILE_AS' => $fileAs,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <button type="button" class="btn btn-icon btn-success assign-comaker" data-comaker-id="'. $contactID .'" title="Assign Co-maker">
                                            <i class="ti ti-check"></i>
                                        </button>
                                    </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
      
        # -------------------------------------------------------------
        #
        # Type: comaker summary
        # Description:
        # Generates the co-maker summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'comaker summary':
            if(isset($_POST['customer_id']) && !empty($_POST['customer_id'])){
                $details = '';
                $customerID = htmlspecialchars($_POST['customer_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactComakerSummary(:customerID)');
                $sql->bindValue(':customerID', $customerID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $customerWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 69, 'write');
                $deleteCustomerComaker = $userModel->checkSystemActionAccessRights($user_id, 121);

                foreach ($options as $index => $row) {
                    $contactComakerID = $row['contact_comaker_id'];
                    $comakerID = $row['comaker_id'];
                    $relationID = $row['relation_id'];
                    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? '--';

                    $comakerDetails = $customerModel->getPersonalInformation($comakerID);
                    $comakerName = $comakerDetails['file_as'] ?? null;

                    $dropdown = '';
                    if ($deleteCustomerComaker['total'] > 0 || $customerWriteAccess['total'] > 0) {
                        $delete = ($deleteCustomerComaker['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item delete-comaker" data-contact-comaker-id="'. $contactComakerID . '">Delete</a>' : '';

                        $update = ($customerWriteAccess['total'] > 0) ? '<a href="javascript:void(0);" class="dropdown-item update-contact-comaker-relation" data-bs-toggle="offcanvas" data-bs-target="#contact-comaker-relation-offcanvas" aria-controls="contact-comaker-relation-offcanvas" data-contact-comaker-id="' . $contactComakerID . '">Edit</a>' : '';
                    
                        $dropdown = ($delete) ? '<div class="dropdown">
                            <a class="avtar avtar-s btn-link-primary dropdown-toggle arrow-none" href="javascript:void(0);" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical f-18"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                ' . $update . '
                                ' . $delete . '
                            </div>
                        </div>' : '';
                    }
                    
                    $listMargin = ($index === 0) ? 'pt-0' : '';

                    $details .= ' <li class="list-group-item '. $listMargin .'">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1 me-2">
                                            <p class="mb-1 text-primary"><b>'. $comakerName .'</b></p>
                                            <p class="mb-3">' . $relationName . '</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            '. $dropdown .'
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No co-maker found.';
                }

                $response[] = [
                    'contactComakerSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>