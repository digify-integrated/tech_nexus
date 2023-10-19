<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/job-level-model.php';
require_once '../model/branch-model.php';
require_once '../model/employee-type-model.php';
require_once '../model/address-type-model.php';
require_once '../model/city-model.php';
require_once '../model/state-model.php';
require_once '../model/country-model.php';
require_once '../model/id-type-model.php';
require_once '../model/educational-stage-model.php';
require_once '../model/relation-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/contact-information-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$jobPositionModel = new JobPositionModel($databaseModel);
$jobLevelModel = new JobLevelModel($databaseModel);
$branchModel = new BranchModel($databaseModel);
$employeeTypeModel = new EmployeeTypeModel($databaseModel);
$contactInformationTypeModel = new ContactInformationTypeModel($databaseModel);
$addressTypeModel = new AddressTypeModel($databaseModel);
$cityModel = new CityModel($databaseModel);
$stateModel = new StateModel($databaseModel);
$idTypeModel = new IDTypeModel($databaseModel);
$countryModel = new CountryModel($databaseModel);
$educationalStageModel = new EducationalStageModel($databaseModel);
$relationModel = new RelationModel($databaseModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: employee table
        # Description:
        # Generates the employee table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employee table':
            if(isset($_POST['filter_employment_status']) && isset($_POST['filter_company']) && isset($_POST['filter_department']) && isset($_POST['filter_job_position']) && isset($_POST['filter_job_level']) && isset($_POST['filter_branch']) && isset($_POST['filter_employee_type'])){
                $filterEmployeeStatus = htmlspecialchars($_POST['filter_employment_status'], ENT_QUOTES, 'UTF-8');
                $filterCompany = htmlspecialchars($_POST['filter_company'], ENT_QUOTES, 'UTF-8');
                $filterDepartment = htmlspecialchars($_POST['filter_department'], ENT_QUOTES, 'UTF-8');
                $filterJobPosition = htmlspecialchars($_POST['filter_job_position'], ENT_QUOTES, 'UTF-8');
                $filterJobLevel = htmlspecialchars($_POST['filter_job_level'], ENT_QUOTES, 'UTF-8');
                $filterBranch = htmlspecialchars($_POST['filter_branch'], ENT_QUOTES, 'UTF-8');
                $filterEmployeeType = htmlspecialchars($_POST['filter_employee_type'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeTable(:filterEmployeeStatus, :filterCompany, :filterDepartment, :filterJobPosition, :filterJobLevel, :filterBranch, :filterEmployeeType)');
                $sql->bindValue(':filterEmployeeStatus', $filterEmployeeStatus, PDO::PARAM_STR);
                $sql->bindValue(':filterCompany', $filterCompany, PDO::PARAM_INT);
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_INT);
                $sql->bindValue(':filterJobPosition', $filterJobPosition, PDO::PARAM_INT);
                $sql->bindValue(':filterJobLevel', $filterJobLevel, PDO::PARAM_INT);
                $sql->bindValue(':filterBranch', $filterBranch, PDO::PARAM_INT);
                $sql->bindValue(':filterEmployeeType', $filterEmployeeType, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'delete');

                foreach ($options as $row) {
                    $employeeID = $row['contact_id'];
                    $firstName = $row['first_name'];
                    $middleName = $row['middle_name'];
                    $lastName = $row['last_name'];
                    $suffix = $row['suffix'];
                    $departmentID = $row['department_id'];
                    $jobPositionID = $row['job_position_id'];
                    $branchID = $row['branch_id'];
                    $employeeImage = $systemModel->checkImage($row['contact_image'], 'profile');

                    $employeeName = $systemSettingModel->getSystemSetting(4)['value'];
                    $employeeName = str_replace('{last_name}', $lastName, $employeeName);
                    $employeeName = str_replace('{first_name}', $firstName, $employeeName);
                    $employeeName = str_replace('{suffix}', $suffix, $employeeName);
                    $employeeName = str_replace('{middle_name}', $middleName, $employeeName);

                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                    $jobPositionName = $jobPositionModel->getJobPosition($jobPositionID)['job_position_name'] ?? null;
                    $branchName = $branchModel->getBranch($branchID)['branch_name'] ?? null;
                   
                    $employeeIDEncrypted = $securityModel->encryptData($employeeID);

                    $delete = '';
                    if($employeeDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-employee" data-employee-id="'. $employeeID .'" title="Delete Employee">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employeeID .'">',
                        'EMPLOYEE' => '<div class="row">
                                        <div class="col-auto pe-0">
                                        <img src="'. $employeeImage .'" alt="employee-image" class="wid-40 hei-40 rounded-circle">
                                        </div>
                                        <div class="col">
                                        <h6 class="mb-0">'. $employeeName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $jobPositionName .'</p>
                                        </div>
                                    </div>',
                        'DEPARTMENT' => $departmentName,
                        'BRANCH' => $branchName,
                        'ACTION' => '<div class="d-flex gap-2">
                                    <a href="employee.php?id='. $employeeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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

        # -------------------------------------------------------------
        #
        # Type: contact information table
        # Description:
        # Generates the contact information table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact information table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactInformationTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 33);
                $deleteEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 34);
                $tagEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 35);

                foreach ($options as $row) {
                    $contactInformationID = $row['contact_information_id'];
                    $contactInformationTypeID = $row['contact_information_type_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Alternate</span>';

                    $contactInformationTypeName = $contactInformationTypeModel->getContactInformationType($contactInformationTypeID)['contact_information_type_name'] ?? null;

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactInformation['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-information" data-contact-information-id="'. $contactInformationID .'" title="Edit Contact Information">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeContactInformation['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-warning tag-contact-information-as-primary" data-contact-information-id="'. $contactInformationID .'" title="Tag Contact Information As Primary">
                                            <i class="ti ti-check"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactInformation['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-information" data-contact-information-id="'. $contactInformationID .'" title="Delete Contact Information">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CONTACT_INFORMATION_TYPE' => $contactInformationTypeName,
                        'EMAIL' => $email,
                        'MOBILE' => $mobile,
                        'TELEPHONE' => $telephone,
                        'STATUS' => $isPrimaryBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $tag .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactInformationTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $contactInformationID = $row['contact_information_id'];
                    $contactInformationTypeID = $row['contact_information_type_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Alternate</span>';

                    $contactInformationTypeName = $contactInformationTypeModel->getContactInformationType($contactInformationTypeID)['contact_information_type_name'] ?? null;

                    $mobileDetails = !empty($mobile) ? '<div class="me-2"><p class="mb-2">Mobile</p><p class="mb-0 text-muted">' . $mobile . '</p></div>' : '';
                    $emailDetails = !empty($email) ? '<div class="me-2"><p class="mb-2">Email</p><p class="mb-0 text-muted">' . $email . '</p></div>' : '';
                    $telephoneDetails = !empty($telephone) ? '<div class="me-2"><p class="mb-2">Telephone</p><p class="mb-0 text-muted">' . $telephone . '</p></div>' : '';

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="d-flex align-items-center justify-content-between">
                                    '. $emailDetails .'
                                    '. $mobileDetails .'
                                    '. $telephoneDetails .'
                                    <div class="">
                                        <div class="text-success d-inline-block me-2">
                                        '. $isPrimaryBadge .'
                                        </div>
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
        # Type: contact address table
        # Description:
        # Generates the contact address table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact address table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactAddressTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactAddress = $userModel->checkSystemActionAccessRights($user_id, 37);
                $deleteEmployeeContactAddress = $userModel->checkSystemActionAccessRights($user_id, 38);
                $tagEmployeeContactAddress = $userModel->checkSystemActionAccessRights($user_id, 39);

                foreach ($options as $row) {
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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactAddress['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-address" data-contact-address-id="'. $contactAddressID .'" title="Edit Contact Address">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeContactAddress['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-warning tag-contact-address-as-primary" data-contact-address-id="'. $contactAddressID .'" title="Tag Contact Address As Primary">
                                            <i class="ti ti-check"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactAddress['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-address" data-contact-address-id="'. $contactAddressID .'" title="Delete Contact Address">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ADDRESS_TYPE' => $addressTypeName,
                        'ADDRESS' => $contactAddress,
                        'STATUS' => $isPrimaryBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $tag .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactAddressTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

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

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-2">
                                        <p class="mb-2">'. $addressTypeName .'</p>
                                        <p class="mb-0 text-muted">' . $contactAddress . '</p>
                                    </div>
                                    <div class="">
                                        <div class="text-success d-inline-block me-2">
                                        '. $isPrimaryBadge .'
                                        </div>
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
        # Type: contact identification table
        # Description:
        # Generates the contact identification table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact identification table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactIdentificationTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 41);
                $deleteEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 42);
                $tagEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 23);

                foreach ($options as $row) {
                    $contactIdentificationID = $row['contact_identification_id'];
                    $idTypeID = $row['id_type_id'];
                    $idNumber = $row['id_number'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Secondary</span>';
    
                    $idTypeName = $idTypeModel->getIDType($idTypeID)['id_type_name'] ?? null;

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactIdentification['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-identification" data-contact-identification-id="'. $contactIdentificationID .'" title="Edit Employee Identification">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeContactIdentification['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-warning tag-contact-identification-as-primary" data-contact-identification-id="'. $contactIdentificationID .'" title="Tag Employee Identification As Primary">
                                            <i class="ti ti-check"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactIdentification['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-identification" data-contact-identification-id="'. $contactIdentificationID .'" title="Delete Employee Identification">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'ID_TYPE' => $idTypeName,
                        'ID_NUMBER' => $idNumber,
                        'STATUS' => $isPrimaryBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $tag .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactIdentificationTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $contactIdentificationID = $row['contact_identification_id'];
                    $idTypeID = $row['id_type_id'];
                    $idNumber = $row['id_number'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success">Primary</span>' : '<span class="badge bg-light-info">Secondary</span>';
    
                    $idTypeName = $idTypeModel->getIDType($idTypeID)['id_type_name'] ?? null;

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-2">
                                        <p class="mb-2">'. $idTypeName .'</p>
                                        <p class="mb-0 text-muted">' . $idNumber . '</p>
                                    </div>
                                    <div class="">
                                        <div class="text-success d-inline-block me-2">
                                        '. $isPrimaryBadge .'
                                        </div>
                                    </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No employee identification found.';
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
        # Type: contact educational background table
        # Description:
        # Generates the contact educational background table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact educational background table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEducationalBackgroundTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 44);
                $deleteEmployeeContactEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 45);

                foreach ($options as $row) {
                    $contactEducationalBackgroundID = $row['contact_educational_background_id'];
                    $educationalStageID = $row['educational_stage_id'];
                    $institutionName = $row['institution_name'];
                    $degreeEarned = $row['degree_earned'];
                    $fieldOfStudy = $row['field_of_study'];
                    $startDate = $systemModel->checkDate('empty', $row['start_date'], '', 'M Y', '');
                    $endDate = $systemModel->checkDate('empty', $row['end_date'], '', 'M Y', '');

                    if(empty($endDate)){
                       $endDate = 'Present'; 
                    }
    
                    $educationalStageName = $educationalStageModel->getEducationalStage($educationalStageID)['educational_stage_name'] ?? null;

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactEducationalBackground['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-educational-background" data-contact-educational-background-id="'. $contactEducationalBackgroundID .'" title="Edit Educational Background">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactEducationalBackground['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-educational-background" data-contact-educational-background-id="'. $contactEducationalBackgroundID .'" title="Delete Educational Background">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'EDUCATIONAL_STAGE' => $educationalStageName,
                        'INSTITUTION_NAME' => $institutionName,
                        'DEGREE_EARNED' => $degreeEarned,
                        'FIELD_OF_STUDY' => $fieldOfStudy,
                        'YEAR_ATTENDED' => $startDate .' - ' . $endDate,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact educational background summary
        # Description:
        # Generates the contact educational background summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact educational background summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEducationalBackgroundTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $contactEducationalBackgroundID = $row['contact_educational_background_id'];
                    $educationalStageID = $row['educational_stage_id'];
                    $institutionName = $row['institution_name'];
                    $degreeEarned = $row['degree_earned'];
                    $fieldOfStudy = $row['field_of_study'];
                    $startDate = $systemModel->checkDate('empty', $row['start_date'], '', 'M Y', '');
                    $endDate = $systemModel->checkDate('empty', $row['end_date'], '', 'M Y', '');

                    if(empty($endDate)){
                       $endDate = 'Present'; 
                    }
    
                    $educationalStageName = $educationalStageModel->getEducationalStage($educationalStageID)['educational_stage_name'] ?? null;

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="mb-1">'. $institutionName .'</p>
                                            <p class="mb-1 text-muted">'. $degreeEarned .'</p>
                                            <p class="mb-1 text-muted">'. $fieldOfStudy .'</p><br/>
                                            <p class="mb-0">'. $startDate .' - '. $endDate .'</p>
                                        </div>
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No educational background found.';
                }

                $response[] = [
                    'contactEducationalBackgroundSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact family background table
        # Description:
        # Generates the contact family background table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact family background table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactFamilyBackgroundTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 48);
                $deleteEmployeeContactFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 49);

                foreach ($options as $row) {
                    $contactFamilyBackgroundID = $row['contact_family_background_id'];
                    $familyName = $row['family_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $birthday = $systemModel->checkDate('empty', $row['birthday'], '', 'm/d/Y', '');
    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactFamilyBackground['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-family-background" data-contact-family-background-id="'. $contactFamilyBackgroundID .'" title="Edit Family Background">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactFamilyBackground['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-family-background" data-contact-family-background-id="'. $contactFamilyBackgroundID .'" title="Delete Family Background">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'FAMILY_NAME' => $familyName,
                        'RELATION' => $relationName,
                        'BIRTHDAY' => $birthday,
                        'EMAIL' => $email,
                        'MOBILE' => $mobile,
                        'TELEPHONE' => $telephone,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactFamilyBackgroundTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $contactFamilyBackgroundID = $row['contact_family_background_id'];
                    $familyName = $row['family_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $birthday = $systemModel->checkDate('empty', $row['birthday'], '', 'm/d/Y', '');
                    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    if(!empty($email)){
                        $email = '<div class="col-md-3"><p class="mb-1">Email</p><p class="mb-1 text-muted">'. $email .'</p></div>';
                    }

                    if(!empty($mobile)){
                        $mobile = '<div class="col-md-3"><p class="mb-1">Mobile</p><p class="mb-1 text-muted">'. $mobile .'</p></div>';
                    }

                    if(!empty($telephone)){
                        $telephone = '<div class="col-md-3"><p class="mb-1">Telephone</p><p class="mb-1 text-muted">'. $telephone .'</p></div>';
                    }

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p class="mb-1">'. $familyName .'</p>
                                            <p class="mb-1 text-muted">'. $relationName .'</p>
                                        </div>
                                        '. $email .'
                                        '. $mobile .'
                                        '. $telephone .'
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
        # Type: contact emergency contact table
        # Description:
        # Generates the contact emergency contact table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact emergency contact table':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEmergencyContactTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 51);
                $deleteEmployeeContactEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 52);

                foreach ($options as $row) {
                    $contactEmergencyContactID = $row['contact_emergency_contact_id'];
                    $emergencyContactName = $row['emergency_contact_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactEmergencyContact['total'] > 0){
                        $update = '<button type="button" class="btn btn-icon btn-info update-contact-emergency-contact" data-contact-emergency-contact-id="'. $contactEmergencyContactID .'" title="Edit Emergency Contact">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactEmergencyContact['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-contact-emergency-contact" data-contact-emergency-contact-id="'. $contactEmergencyContactID .'" title="Delete Emergency Contact">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'EMERGENCY_CONTACT_NAME' => $emergencyContactName,
                        'RELATION' => $relationName,
                        'EMAIL' => $email,
                        'MOBILE' => $mobile,
                        'TELEPHONE' => $telephone,
                        'ACTION' => '<div class="d-flex gap-2">
                                    '. $update .'
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact emergency contact summary
        # Description:
        # Generates the contact emergency contact summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact emergency contact summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEmergencyContactTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                foreach ($options as $index => $row) {
                    $contactEmergencyContactID = $row['contact_emergency_contact_id'];
                    $emergencyContactName = $row['emergency_contact_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    if(!empty($email)){
                        $email = '<div class="col-md-3"><p class="mb-1">Email</p><p class="mb-1 text-muted">'. $email .'</p></div>';
                    }

                    if(!empty($mobile)){
                        $mobile = '<div class="col-md-3"><p class="mb-1">Mobile</p><p class="mb-1 text-muted">'. $mobile .'</p></div>';
                    }

                    if(!empty($telephone)){
                        $telephone = '<div class="col-md-3"><p class="mb-1">Telephone</p><p class="mb-1 text-muted">'. $telephone .'</p></div>';
                    }

                    if ($count === 1) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === 0) {
                        $listMargin = 'pt-0';
                    }
                    else if ($index === $count - 1) {
                        $listMargin = 'pb-0';
                    }
                    else {
                        $listMargin = '';
                    }
    
                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p class="mb-1">'. $emergencyContactName .'</p>
                                            <p class="mb-1 text-muted">'. $relationName .'</p>
                                        </div>
                                        '. $email .'
                                        '. $mobile .'
                                        '. $telephone .'
                                    </div>
                                </li>';
                }

                if(empty($details)){
                    $details = 'No emergency contact found.';
                }

                $response[] = [
                    'contactEmergencyContactSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>