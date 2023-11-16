<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/company-model.php';
require_once '../model/gender-model.php';
require_once '../model/civil-status-model.php';
require_once '../model/religion-model.php';
require_once '../model/blood-type-model.php';
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
require_once '../model/language-model.php';
require_once '../model/language-proficiency-model.php';
require_once '../model/bank-model.php';
require_once '../model/bank-account-type-model.php';
require_once '../model/system-setting-model.php';
require_once '../model/contact-information-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel, $systemSettingModel);
$companyModel = new CompanyModel($databaseModel);
$genderModel = new GenderModel($databaseModel);
$civilStatusModel = new CivilStatusModel($databaseModel);
$religionModel = new ReligionModel($databaseModel);
$bloodTypeModel = new BloodTypeModel($databaseModel);
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
$languageModel = new LanguageModel($databaseModel);
$languageProficiencyModel = new LanguageProficiencyModel($databaseModel);
$bankModel = new BankModel($databaseModel);
$bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: employee card
        # Description:
        # Generates the employee card.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employee card':
            if(isset($_POST['current_page']) && isset($_POST['employee_search']) && isset($_POST['employment_status_filter']) && isset($_POST['department_filter']) && isset($_POST['job_position_filter']) && isset($_POST['branch_filter']) && isset($_POST['employee_type_filter']) && isset($_POST['job_level_filter']) && isset($_POST['gender_filter']) && isset($_POST['civil_status_filter']) && isset($_POST['blood_type_filter']) && isset($_POST['religion_filter']) && isset($_POST['age_filter'])){
                $initialEmployeesPerPage = 9;
                $loadMoreEmployeesPerPage = 6;
                $employeePerPage = $initialEmployeesPerPage;
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $employeeSearch = htmlspecialchars($_POST['employee_search'], ENT_QUOTES, 'UTF-8');
                $employementStatusFilter = htmlspecialchars($_POST['employment_status_filter'], ENT_QUOTES, 'UTF-8');
                $departmentFilter = htmlspecialchars($_POST['department_filter'], ENT_QUOTES, 'UTF-8');
                $jobPositionFilter = htmlspecialchars($_POST['job_position_filter'], ENT_QUOTES, 'UTF-8');
                $branchFilter = htmlspecialchars($_POST['branch_filter'], ENT_QUOTES, 'UTF-8');
                $employeeTypeFilter = htmlspecialchars($_POST['employee_type_filter'], ENT_QUOTES, 'UTF-8');
                $jobLevelFilter = htmlspecialchars($_POST['job_level_filter'], ENT_QUOTES, 'UTF-8');
                $genderFilter = htmlspecialchars($_POST['gender_filter'], ENT_QUOTES, 'UTF-8');
                $civilStatusFilter = htmlspecialchars($_POST['civil_status_filter'], ENT_QUOTES, 'UTF-8');
                $bloodTypeFilter = htmlspecialchars($_POST['blood_type_filter'], ENT_QUOTES, 'UTF-8');
                $religionFilter = htmlspecialchars($_POST['religion_filter'], ENT_QUOTES, 'UTF-8');
                $ageFilter = htmlspecialchars($_POST['age_filter'], ENT_QUOTES, 'UTF-8');
                $ageExplode = explode(',', $ageFilter);
                $minAge = $ageExplode[0];
                $maxAge = $ageExplode[1];
                $offset = ($currentPage - 1) * $employeePerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeCard(:offset, :employeePerPage, :employeeSearch, :employementStatusFilter, :departmentFilter, :jobPositionFilter, :branchFilter, :employeeTypeFilter, :jobLevelFilter, :genderFilter, :civilStatusFilter, :bloodTypeFilter, :religionFilter, :minAge, :maxAge)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':employeePerPage', $employeePerPage, PDO::PARAM_INT);
                $sql->bindValue(':employeeSearch', $employeeSearch, PDO::PARAM_STR);
                $sql->bindValue(':employementStatusFilter', $employementStatusFilter, PDO::PARAM_STR);
                $sql->bindValue(':departmentFilter', $departmentFilter, PDO::PARAM_STR);
                $sql->bindValue(':jobPositionFilter', $jobPositionFilter, PDO::PARAM_STR);
                $sql->bindValue(':branchFilter', $branchFilter, PDO::PARAM_STR);
                $sql->bindValue(':employeeTypeFilter', $employeeTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':jobLevelFilter', $jobLevelFilter, PDO::PARAM_STR);
                $sql->bindValue(':genderFilter', $genderFilter, PDO::PARAM_STR);
                $sql->bindValue(':civilStatusFilter', $civilStatusFilter, PDO::PARAM_STR);
                $sql->bindValue(':bloodTypeFilter', $bloodTypeFilter, PDO::PARAM_STR);
                $sql->bindValue(':religionFilter', $religionFilter, PDO::PARAM_STR);
                $sql->bindValue(':minAge', $minAge, PDO::PARAM_INT);
                $sql->bindValue(':maxAge', $maxAge, PDO::PARAM_INT);
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
                    $offboardDate = $systemModel->checkDate('empty', $row['offboard_date'], '', 'F Y', '');
                    $employeeImage = $systemModel->checkImage($row['contact_image'], 'profile');

                    $employmentStatus = empty($offboardDate) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';

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
                        $delete = '<div class="btn-prod-cart card-body position-absolute end-0 bottom-0">
                                        <button class="btn btn-danger delete-employee" data-employee-id="'. $employeeID .'" title="Delete Employee">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }
    
                    $response[] = [
                        'employeeCard' => '<div class="col-sm-6 col-xl-4">
                                                <div class="card product-card">
                                                    <div class="card-img-top">
                                                        <a href="employee.php?id='. $employeeIDEncrypted .'">
                                                            <img src="'. $employeeImage .'" alt="image" class="img-prod img-fluid" />
                                                        </a>
                                                        <div class="card-body position-absolute start-0 top-0">
                                                           '. $employmentStatus .'
                                                        </div>
                                                        '. $delete .'
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="employee.php?id='. $employeeIDEncrypted .'">
                                                            <div class="d-flex align-items-center justify-content-between mt-3">
                                                                <h4 class="mb-0 text-truncate text-primary"><b>'. $employeeName .'</b></h4>
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="prod-content mb-0 text-dark">'. $jobPositionName .'</p>
                                                            </div>
                                                            <div>
                                                                <p class="prod-content mb-0 text-dark">'. $departmentName .'</p>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>'
                    ];
                }

                if ($employeePerPage === $initialEmployeesPerPage) {
                    $employeePerPage = $loadMoreEmployeesPerPage;
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generatePersonalInformationSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $index => $row) {
                    $firstName = $row['first_name'];
                    $middleName = $row['middle_name'];
                    $lastName = $row['last_name'];
                    $suffix = $row['suffix'];
                    $nickname = $row['nickname'] ?? '--';
                    $civilStatusID = $row['civil_status_id'];
                    $genderID = $row['gender_id'];
                    $religionID = $row['religion_id'];
                    $bloodTypeID = $row['blood_type_id'];
                    $birthday = $systemModel->checkDate('summary', $row['birthday'], '', 'F d, Y', '');
                    $birthPlace = $row['birth_place'] ?? '--';
                    $height = !empty($row['height']) ? $row['height'] . ' cm' : '--';
                    $weight = !empty($row['weight']) ? $row['weight'] . ' kg' : '--';

                    $employeeName = $systemSettingModel->getSystemSetting(4)['value'];
                    $employeeName = str_replace('{last_name}', $lastName, $employeeName);
                    $employeeName = str_replace('{first_name}', $firstName, $employeeName);
                    $employeeName = str_replace('{suffix}', $suffix, $employeeName);
                    $employeeName = str_replace('{middle_name}', $middleName, $employeeName);

                    $civilStatusName = $civilStatusModel->getCivilStatus($civilStatusID)['civil_status_name'] ?? '--';
                    $religionName = $religionModel->getReligion($religionID)['religion_name'] ?? '--';
                    $bloodTypeName = $bloodTypeModel->getBloodType($bloodTypeID)['blood_type_name'] ?? '--';
                    $genderName = $genderModel->getGender($genderID)['gender_name'] ?? '--';

                    $details .= '<ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 pt-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Full Name</b></p>
                                                <p class="mb-0">'. $employeeName .'</p>
                                            </div> 
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Nickname</b></p>
                                                <p class="mb-0">'. $nickname .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Birthday</b></p>
                                                <p class="mb-0">'. $birthday .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Birth Place</b></p>
                                                <p class="mb-0">'. $birthPlace .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Gender</b></p>
                                                <p class="mb-0">'. $genderName .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Civil Status</b></p>
                                                <p class="mb-0">'. $civilStatusName .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Blood type</b></p>
                                                <p class="mb-0">'. $bloodTypeName .'</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Religion</b></p>
                                                <p class="mb-0">'. $religionName .'</p>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1 text-primary"><b>Height</b></p>
                                                <p class="mb-0">'. $height .'</p>
                                            </div>
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
        # Type: employment information summary
        # Description:
        # Generates the employment information summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employment information summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmploymentInformationSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $index => $row) {
                    $badgeID = !empty($row['badge_id']) ? $row['badge_id'] : '--';
                    $companyID = $row['company_id'];
                    $employeeTypeID = $row['employee_type_id'];
                    $departmentID = $row['department_id'];
                    $jobPositionID = $row['job_position_id'];
                    $jobLevelID = $row['job_level_id'];
                    $branchID = $row['branch_id'];
                    $onboardDate = $systemModel->checkDate('summary', $row['onboard_date'], '', 'F d, Y', '');

                    $companyName = $companyModel->getCompany($companyID)['company_name'] ?? '--';
                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? '--';
                    $employeeTypeName = $employeeTypeModel->getEmployeeType($employeeTypeID)['employee_type_name'] ?? '--';
                    $jobLevelName = $jobLevelModel->getJobLevel($jobLevelID)['rank'] ?? '--';
                    $branchName = $branchModel->getBranch($branchID)['branch_name'] ?? '--';

                    $details .= '<div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>ID Number</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $badgeID .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>Company</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $companyName .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>Department</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $departmentName .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>Employee Type</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $employeeTypeName .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>Job Level</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $jobLevelName .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>Branch</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $branchName .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-sm-6 mb-2 mb-sm-0">
                                        <p class="mb-0 text-primary"><b>On-Board Date</b></p>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 w-100">
                                                <p class="mb-0 text-truncate text-end">'. $onboardDate .'</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>';
                }

                if(empty($details)){
                    $details = 'No employment information found.';
                }

                $response[] = [
                    'employmentInformationSummary' => $details
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactInformationSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 33);
                $deleteEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 34);
                $tagEmployeeContactInformation = $userModel->checkSystemActionAccessRights($user_id, 35);

                foreach ($options as $index => $row) {
                    $contactInformationID = $row['contact_information_id'];
                    $contactInformationTypeID = $row['contact_information_type_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $isPrimary = $row['is_primary'];

                    $isPrimaryBadge = $isPrimary ? '<span class="badge bg-light-success mt-3">Primary</span>' : '<span class="badge bg-light-info mt-3">Alternate</span>';

                    $contactInformationTypeName = $contactInformationTypeModel->getContactInformationType($contactInformationTypeID)['contact_information_type_name'] ?? null;

                    $mobile = !empty($mobile) ? '<p class="mb-0"><i class="ti ti-device-mobile me-2"></i> ' . $mobile . '</p>' : '';
                    $email = !empty($email) ? '<p class="mb-0"><i class="ti ti-mail me-2"></i> ' . $email . '</p>' : '';
                    $telephone = !empty($telephone) ? '<p class="mb-0"><i class="ti ti-phone me-2"></i> ' . $telephone . '</p>' : '';

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
                    
                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactInformation['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-information mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-information-offcanvas" aria-controls="contact-information-offcanvas" data-contact-information-id="'. $contactInformationID .'" title="Edit Contact Information">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }
                    
                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeContactInformation['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-outline-warning tag-contact-information-as-primary mt-3" data-contact-information-id="'. $contactInformationID .'" title="Tag Contact Information As Primary">
                        <i class="ti ti-check"></i>
                                </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactInformation['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-information mt-3" data-contact-information-id="'. $contactInformationID .'" title="Delete Contact Information">
                        <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-2">
                                    <p class="mb-1 text-primary"><b>'. $contactInformationTypeName .'</b></p>
                                    '. $email .'
                                    '. $mobile .'
                                    '. $telephone .'
                                    <div class="mt-2 d-flex gap-2">
                                        '. $update .'
                                        '. $tag .'
                                        '. $delete .'
                                    </div>
                                </div>
                                <div class="me-2">
                                    '. $isPrimaryBadge .'
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactAddressSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 37);
                $deleteEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 38);
                $tagEmployeeAddress = $userModel->checkSystemActionAccessRights($user_id, 39);

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeAddress['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-address mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-address-offcanvas" aria-controls="contact-address-offcanvas" data-contact-address-id="'. $contactAddressID .'" title="Edit Contact Address">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-outline-warning tag-contact-address-as-primary mt-3" data-contact-address-id="'. $contactAddressID .'" title="Tag Contact Address As Primary">
                                    <i class="ti ti-check"></i>
                                </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeAddress['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-address mt-3" data-contact-address-id="'. $contactAddressID .'" title="Delete Contact Address">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-2">
                                                <p class="mb-2 text-primary"><b>'. $addressTypeName .'</b></p>
                                                <p class="mb-2">' . $contactAddress . '</p>
                                                <div class="d-flex gap-2">
                                                    '. $update .'
                                                    '. $tag .'
                                                    '. $delete .'
                                                </div>
                                            </div>
                                            <div class="me-2">
                                                '. $isPrimaryBadge .'
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
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactIdentificationSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 41);
                $deleteEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 42);
                $tagEmployeeContactIdentification = $userModel->checkSystemActionAccessRights($user_id, 43);

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeContactIdentification['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-identification mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-identification-offcanvas" aria-controls="contact-identification-offcanvas" data-contact-identification-id="'. $contactIdentificationID .'" title="Edit Employee Identification">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $tag = '';
                    if($employeeWriteAccess['total'] > 0 && $tagEmployeeContactIdentification['total'] > 0 && !$isPrimary){
                        $tag = '<button type="button" class="btn btn-icon btn-outline-warning tag-contact-identification-as-primary mt-3" data-contact-identification-id="'. $contactIdentificationID .'" title="Tag Employee Identification As Primary">
                                    <i class="ti ti-check"></i>
                                </button>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeContactIdentification['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-identification mt-3" data-contact-identification-id="'. $contactIdentificationID .'" title="Delete Employee Identification">
                                    <i class="ti ti-trash"></i>
                                </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="me-2">
                                            <p class="mb-2 text-primary"><b>'. $idTypeName .'</b></p>
                                            <p class="mb-2">' . $idNumber . '</p>
                                            <div class="d-flex gap-2">
                                                '. $update .'
                                                '. $tag .'
                                                '. $delete .'
                                            </div>
                                        </div>
                                        <div class="me-2">
                                            '. $isPrimaryBadge .'
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

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEducationalBackgroundSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 44);
                $deleteEmployeeEducationalBackground = $userModel->checkSystemActionAccessRights($user_id, 45);

                foreach ($options as $index => $row) {
                    $contactEducationalBackgroundID = $row['contact_educational_background_id'];
                    $educationalStageID = $row['educational_stage_id'];
                    $institutionName = $row['institution_name'];
                    $degreeEarned = $row['degree_earned'];
                    $fieldOfStudy = $row['field_of_study'];
                    $startMonth = $systemModel->getMonthNameFromNumber($row['start_month']);
                    $startYear = $row['start_year'];
                    $startDate = $startMonth . ' ' . $startYear;
                    $endMonth = $systemModel->getMonthNameFromNumber($row['end_month']);
                    $endYear = $row['end_year'];
                    $courseHighlight = $row['course_highlights'];

                    if(!empty($endMonth) && !empty($endYear)){
                        $endDate = $endMonth . ' ' . $endYear;
                    }
                    else{
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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeEducationalBackground['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-educational-background mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-educational-background-offcanvas" aria-controls="contact-educational-background-offcanvas" data-contact-educational-background-id="'. $contactEducationalBackgroundID .'" title="Edit Educational Background">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeEducationalBackground['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-educational-background mt-3" data-contact-educational-background-id="'. $contactEducationalBackgroundID .'" title="Delete Educational Background">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-2">
                                                    <p class="mb-1 text-primary"><b>'. $institutionName .'</b></p>
                                                    <p class="mb-1">' . $degreeEarned . '</p>
                                                    <p class="mb-1">' . $fieldOfStudy . '</p>
                                                    <p class="mb-3">'. $startDate .' - '. $endDate .'</p>
                                                    <p class="mb-2">' . $courseHighlight . '</p>
                                                    <div class="d-flex gap-2">
                                                        '. $update .'
                                                        '. $delete .'
                                                    </div>
                                                </div>
                                                <div class="me-2">
                                                   
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

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactFamilyBackgroundSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 48);
                $deleteEmployeeFamilyBackground = $userModel->checkSystemActionAccessRights($user_id, 49);

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeFamilyBackground['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-family-background mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-family-background-offcanvas" aria-controls="contact-family-background-offcanvas" data-contact-family-background-id="'. $contactFamilyBackgroundID .'" title="Edit Family Background">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeFamilyBackground['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-family-background mt-3" data-contact-family-background-id="'. $contactFamilyBackgroundID .'" title="Delete Family Background">
                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-2">
                                                    <p class="mb-1 text-primary"><b>'. $familyName .'</b></p>
                                                    <p class="mb-3">' . $relationName . '</p>
                                                    <p class="mb-2"><i class="ti ti-calendar-event"></i> ' . $birthday . '</p>
                                                    <div class="d-flex gap-2">
                                                        '. $update .'
                                                        '. $delete .'
                                                    </div>
                                                </div>
                                                <div class="me-2 text-end">                                            
                                                    '. $email .'
                                                    '. $mobile .'
                                                    '. $telephone .'
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

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEmergencyContactSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 51);
                $deleteEmployeeEmergencyContact = $userModel->checkSystemActionAccessRights($user_id, 52);

                foreach ($options as $index => $row) {
                    $contactEmergencyContactID = $row['contact_emergency_contact_id'];
                    $emergencyContactName = $row['emergency_contact_name'];
                    $relationID = $row['relation_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
    
                    $relationName = $relationModel->getRelation($relationID)['relation_name'] ?? null;

                    $mobile = !empty($mobile) ? '<p class="mb-1"><i class="ti ti-device-mobile me-2"></i> ' . $mobile . '</p>' : '';
                    $email = !empty($email) ? '<p class="mb-1"><i class="ti ti-mail me-2"></i> ' . $email . '</p>' : '';
                    $telephone = !empty($telephone) ? '<p class="mb-1"><i class="ti ti-phone me-2"></i> ' . $telephone . '</p>' : '';

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeEmergencyContact['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-emergency-contact mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-emergency-contact-offcanvas" aria-controls="contact-emergency-contact-offcanvas" data-contact-emergency-contact-id="'. $contactEmergencyContactID .'" title="Edit Emergency Contact">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeEmergencyContact['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-emergency-contact mt-3" data-contact-emergency-contact-id="'. $contactEmergencyContactID .'" title="Delete Emergency Contact">
                                    <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-2">
                                                <p class="mb-1 text-primary"><b>'. $emergencyContactName .'</b></p>
                                                <p class="mb-2">' . $relationName . '</p>
                                                <div class="d-flex gap-2">
                                                    '. $update .'
                                                    '. $delete .'
                                                </div>
                                            </div>
                                            <div class="me-2 text-end">
                                                '. $email .'
                                                '. $mobile .'
                                                '. $telephone .'
                                            </div>
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

        # -------------------------------------------------------------
        #
        # Type: contact training summary
        # Description:
        # Generates the contact training summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact training summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactTrainingSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeTraining = $userModel->checkSystemActionAccessRights($user_id, 54);
                $deleteEmployeeTraining = $userModel->checkSystemActionAccessRights($user_id, 55);

                foreach ($options as $index => $row) {
                    $contactTrainingID = $row['contact_training_id'];
                    $trainingName = $row['training_name'];
                    $trainingDate = $systemModel->checkDate('empty', $row['training_date'], '', 'F d, Y', '');
                    $trainingLocation = $row['training_location'];
                    $trainingProvider = $row['training_provider'];

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeTraining['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-training mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-training-offcanvas" aria-controls="contact-training-offcanvas" data-contact-training-id="'. $contactTrainingID .'" title="Edit Trainings & Seminars">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeTraining['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-training mt-3" data-contact-training-id="'. $contactTrainingID .'" title="Delete Trainings & Seminars">
                                    <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="me-2">
                                                <p class="mb-1 text-primary"><b>'. $trainingName .'</b></p>
                                                <p class="mb-1">' . $trainingProvider . '</p>
                                                <p class="mb-2">' . $trainingLocation . '</p>
                                                <div class="d-flex gap-2">
                                                    '. $update .'
                                                    '. $delete .'
                                                </div>
                                            </div>
                                            <div class="me-2">
                                                <p class="mb-0">' . $trainingDate . '</p>
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No trainings & seminars found.';
                }

                $response[] = [
                    'contactTrainingSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact skills summary
        # Description:
        # Generates the contact skills summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact skills summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactSkillsSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeSkills = $userModel->checkSystemActionAccessRights($user_id, 57);
                $deleteEmployeeSkills = $userModel->checkSystemActionAccessRights($user_id, 58);

                foreach ($options as $index => $row) {
                    $contactSkillsID = $row['contact_skills_id'];
                    $skillName = $row['skill_name'];

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeSkills['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-skills" data-bs-toggle="offcanvas" data-bs-target="#contact-skills-offcanvas" aria-controls="contact-skills-offcanvas" data-contact-skills-id="'. $contactSkillsID .'" title="Edit Skills">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeSkills['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-skills" data-contact-skills-id="'. $contactSkillsID .'" title="Delete Skills">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary"><b>'. $skillName .'</b></p>
                                            <div class="d-flex gap-2">
                                                '. $update .'
                                                '. $delete .'
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No skills found.';
                }

                $response[] = [
                    'contactSkillsSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact talents summary
        # Description:
        # Generates the contact talents summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact talents summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactTalentsSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeTalents = $userModel->checkSystemActionAccessRights($user_id, 60);
                $deleteEmployeeTalents = $userModel->checkSystemActionAccessRights($user_id, 61);

                foreach ($options as $index => $row) {
                    $contactTalentsID = $row['contact_talents_id'];
                    $talentName = $row['talent_name'];

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeTalents['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-talents" data-bs-toggle="offcanvas" data-bs-target="#contact-talents-offcanvas" aria-controls="contact-talents-offcanvas" data-contact-talents-id="'. $contactTalentsID .'" title="Edit Talents">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeTalents['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-talents" data-contact-talents-id="'. $contactTalentsID .'" title="Delete Talents">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary"><b>'. $talentName .'</b></p>
                                            <div class="d-flex gap-2">
                                                '. $update .'
                                                '. $delete .'
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No talents found.';
                }

                $response[] = [
                    'contactTalentsSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact hobby summary
        # Description:
        # Generates the contact hobby summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact hobby summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactHobbySummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeHobby = $userModel->checkSystemActionAccessRights($user_id, 63);
                $deleteEmployeeHobby = $userModel->checkSystemActionAccessRights($user_id, 64);

                foreach ($options as $index => $row) {
                    $contactHobbyID = $row['contact_hobby_id'];
                    $hobbyName = $row['hobby_name'];

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeHobby['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-hobby" data-bs-toggle="offcanvas" data-bs-target="#contact-hobby-offcanvas" aria-controls="contact-hobby-offcanvas" data-contact-hobby-id="'. $contactHobbyID .'" title="Edit Hobby">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeHobby['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-hobby" data-contact-hobby-id="'. $contactHobbyID .'" title="Delete Hobby">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p class="mb-0 text-primary"><b>'. $hobbyName .'</b></p>
                                            <div class="d-flex gap-2">
                                                 '. $update .'
                                                '. $delete .'
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No hobby found.';
                }

                $response[] = [
                    'contactHobbySummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact employment history summary
        # Description:
        # Generates the contact employment history summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact employment history summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactEmploymentHistorySummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeEmploymentHistory = $userModel->checkSystemActionAccessRights($user_id, 66);
                $deleteEmployeeEmploymentHistory = $userModel->checkSystemActionAccessRights($user_id, 67);

                foreach ($options as $index => $row) {
                    $contactEmploymentHistoryID = $row['contact_employment_history_id'];
                    $company = $row['company'];
                    $address = $row['address'];
                    $lastPositionHeld = $row['last_position_held'];
                    $basicFunction = $row['basic_function'];
                    $startingSalary = $row['starting_salary'];
                    $finalSalary = $row['final_salary'];
                    $durationParts = [];

                    $startMonth = $row['start_month'];
                    $startYear = $row['start_year'];
                    $endMonth = $row['end_month'];
                    $endYear = $row['end_year'];
                    $employmentStartDate = $systemModel->checkDate('empty', date('Y-m-d', strtotime($startYear . '-' . $startMonth . '-01')), '', 'F Y', '');
                    $employmentEndDate = $systemModel->checkDate('empty', date('Y-m-d', strtotime($endYear . '-' . $endMonth . '-01')), '', 'F Y', '');

                    if(!empty($endMonth) && !empty($endYear)){
                        $endDateTime = new DateTime($employmentEndDate);
                    }
                    else{                        
                        $employmentEndDate = 'Present';
                        $endDateTime = new DateTime(date('F Y'));
                    }

                    $startDateTime = new DateTime($employmentStartDate);

                    $interval = $startDateTime->diff($endDateTime);

                    if ($interval->y > 0) {
                        $yearLabel = ($interval->y === 1) ? 'year' : 'years';
                        $durationParts[] = $interval->format('%y ' . $yearLabel);
                    }

                    if ($interval->m > 0) {
                        $monthLabel = ($interval->m === 1) ? 'month' : 'months';
                        $durationParts[] = $interval->format('%m ' . $monthLabel);
                    }

                    $employmentDuration = implode(' and ', $durationParts);

                    if (empty($employmentDuration)) {
                        $employmentDuration = 'Less than a month';
                    }

                    if ($startingSalary > 0 || $finalSalary > 0) {
                        $salary = number_format($startingSalary, 2) . ' Php';
                        if ($finalSalary > 0) {
                            $salary .= ' - ' . number_format($finalSalary, 2) . ' Php';
                        }
                    } else {
                        $salary = '';
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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeEmploymentHistory['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-employment-history mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-employment-history-offcanvas" aria-controls="contact-employment-history-offcanvas" data-contact-employment-history-id="'. $contactEmploymentHistoryID .'" title="Edit Employment History">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeEmploymentHistory['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-employment-history mt-3" data-contact-employment-history-id="'. $contactEmploymentHistoryID .'" title="Delete Employment History">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-2">
                                                    <p class="mb-1 text-primary"><b>'. $lastPositionHeld .'</b></p>
                                                    <p class="mb-1">'. $company .'</p>
                                                    <p class="mb-1">'. $address  .'</p>
                                                    <p class="mb-1">'. $employmentStartDate .' - '. $employmentEndDate .' ('. $employmentDuration .')</p>
                                                    <p class="mb-3">'. $salary .'</p>                                                                      
                                                    <p class="mb-2">'. $basicFunction .'</p>
                                                    <div class="d-flex gap-2">
                                                        '. $update .'
                                                        '. $delete .'
                                                    </div>
                                                </div>
                                            </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No employment history found.';
                }

                $response[] = [
                    'contactEmploymentHistorySummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact license summary
        # Description:
        # Generates the contact license summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact license summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactLicenseSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateupdateEmployeeLicense = $userModel->checkSystemActionAccessRights($user_id, 69);
                $deleteEmployeeLicense = $userModel->checkSystemActionAccessRights($user_id, 70);

                foreach ($options as $index => $row) {
                    $contactLicenseID = $row['contact_license_id'];
                    $licenseName = $row['license_name'];
                    $issuingOrganization = $row['issuing_organization'];
                    $description = $row['description'];
                    $startMonth = $row['start_month'];
                    $startYear = $row['start_year'];
                    $endMonth = $row['end_month'];
                    $endYear = $row['end_year'];
                    $issueDate = $systemModel->checkDate('empty', date('Y-m-d', strtotime($startYear . '-' . $startMonth . '-01')), '', 'F Y', '');

                    $currentDateTime = new DateTime();

                    $expiryStatus = '';

                    if(!empty($endMonth) && !empty($endYear)){
                        $expiryDate = $systemModel->checkDate('empty', date('Y-m-d', strtotime($endYear . '-' . $endMonth . '-01')), '', 'F Y', '');

                        $expiryDateTime = DateTime::createFromFormat('F Y', $expiryDate);

                        if ($expiryDateTime < $currentDateTime) {
                            $expiryStatus = '(Expired)';
                        }
                    }
                    else{
                        $expiryDate = 'Current';
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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateupdateEmployeeLicense['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-employee-license mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-license-offcanvas" aria-controls="contact-employee-license-offcanvas" data-contact-employee-license-id="'. $contactLicenseID .'" title="Edit License">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeLicense['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-employee-license mt-3" data-contact-employee-license-id="'. $contactLicenseID .'" title="Delete License">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="me-2">
                                                    <p class="mb-1 text-primary"><b>'. $licenseName .'</b></p>
                                                    <p class="mb-1">'. $issuingOrganization .'</p>
                                                    <p class="mb-2">'. $issueDate .' - '. $expiryDate .' ' . $expiryStatus .'</p>
                                                    <p class="mb-2">'. $description .'</p>
                                                    <div class="d-flex gap-2">
                                                        '. $update .'
                                                        '. $delete .'
                                                    </div>
                                                </div>
                                            </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No license & certification found.';
                }

                $response[] = [
                    'contactLicenseSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact language summary
        # Description:
        # Generates the contact language summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact language summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactLanguageSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeLanguage = $userModel->checkSystemActionAccessRights($user_id, 72);
                $deleteEmployeeLanguage = $userModel->checkSystemActionAccessRights($user_id, 73);

                foreach ($options as $index => $row) {
                    $contactLanguageID = $row['contact_language_id'];
                    $languageID = $row['language_id'];
                    $languageProficiencyID = $row['language_proficiency_id'];

                    $languageName = $languageModel->getLanguage($languageID)['language_name'] ?? null;
                    $languageProficiencyDetails = $languageProficiencyModel->getLanguageProficiency($languageProficiencyID);
                    $languageProficiencyName = $languageProficiencyDetails['language_proficiency_name'];
                    $languageProficiencyDescription = $languageProficiencyDetails['description'];

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeLanguage['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-employee-language mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-language-offcanvas" aria-controls="contact-employee-language-offcanvas" data-contact-employee-language-id="'. $contactLanguageID .'" title="Edit Language">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeLanguage['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-employee-language mt-3" data-contact-employee-language-id="'. $contactLanguageID .'" title="Delete Language">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mb-0">
                                                <p class="mb-2 text-primary"><b>'. $languageName .'</b></p>
                                                <p class="mb-2">'. $languageProficiencyName .' - '. $languageProficiencyDescription .'</p>
                                                <div class="d-flex gap-2">
                                                    '. $update .'
                                                    '. $delete .'
                                                </div>
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No languages found.';
                }

                $response[] = [
                    'contactLanguageSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: contact bank summary
        # Description:
        # Generates the contact bank summary.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contact bank summary':
            if(isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                $details = '';
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateContactBankSummary(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $count = count($options);

                $employeeWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'write');
                $updateEmployeeBank = $userModel->checkSystemActionAccessRights($user_id, 75);
                $deleteEmployeeBank = $userModel->checkSystemActionAccessRights($user_id, 76);

                foreach ($options as $index => $row) {
                    $contactBankID = $row['contact_bank_id'];
                    $bankID = $row['bank_id'];
                    $bankAccountTypeID = $row['bank_account_type_id'];
                    $accountNumber = $row['account_number'];

                    $bankName = $bankModel->getBank($bankID)['bank_name'] ?? null;
                    $bankAccountTypeName = $bankAccountTypeModel->getBankAccountType($bankID)['bank_account_type_name'] ?? null;

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

                    $update = '';
                    if($employeeWriteAccess['total'] > 0 && $updateEmployeeBank['total'] > 0){
                        $update = '<a href="javascript:void(0);" class="btn btn-icon btn-outline-primary update-contact-employee-bank mt-3" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-bank-offcanvas" aria-controls="contact-employee-bank-offcanvas" data-contact-employee-bank-id="'. $contactBankID .'" title="Edit Bank">
                                    <i class="ti ti-pencil"></i>
                                </a>';
                    }

                    $delete = '';
                    if($employeeWriteAccess['total'] > 0 && $deleteEmployeeBank['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-outline-danger delete-contact-employee-bank mt-3" data-contact-employee-bank-id="'. $contactBankID .'" title="Delete Bank">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $details .= '<li class="list-group-item px-0 '. $listMargin .'">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="mb-0">
                                                <p class="mb-1 text-primary"><b>'. $bankName .'</b></p>
                                                <p class="mb-2">'. $bankAccountTypeName .'</p>
                                                <p class="mb-2">'. $accountNumber .'</p>
                                                <div class="d-flex gap-2">
                                                    '. $update .'
                                                    '. $delete .'
                                                </div>
                                            </div>
                                        </div>
                                    </li>';
                }

                if(empty($details)){
                    $details = 'No bank account found.';
                }

                $response[] = [
                    'contactBankSummary' => $details
                ];
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>