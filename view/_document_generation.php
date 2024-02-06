<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/document-model.php';
require_once '../model/employee-model.php';
require_once '../model/company-model.php';
require_once '../model/department-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$documentModel = new DocumentModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: document card
        # Description:
        # Generates the document card.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document card':
            if(isset($_POST['current_page']) && isset($_POST['document_search']) && isset($_POST['employment_status_filter']) && isset($_POST['department_filter']) && isset($_POST['job_position_filter']) && isset($_POST['branch_filter']) && isset($_POST['document_type_filter']) && isset($_POST['job_level_filter']) && isset($_POST['gender_filter']) && isset($_POST['civil_status_filter']) && isset($_POST['blood_type_filter']) && isset($_POST['religion_filter']) && isset($_POST['age_filter'])){
                $initialEmployeesPerPage = 9;
                $loadMoreEmployeesPerPage = 6;
                $documentPerPage = $initialEmployeesPerPage;
                
                $currentPage = htmlspecialchars($_POST['current_page'], ENT_QUOTES, 'UTF-8');
                $documentSearch = htmlspecialchars($_POST['document_search'], ENT_QUOTES, 'UTF-8');
                $employementStatusFilter = htmlspecialchars($_POST['employment_status_filter'], ENT_QUOTES, 'UTF-8');
                $companyFilter = htmlspecialchars($_POST['company_filter'], ENT_QUOTES, 'UTF-8');
                $departmentFilter = htmlspecialchars($_POST['department_filter'], ENT_QUOTES, 'UTF-8');
                $jobPositionFilter = htmlspecialchars($_POST['job_position_filter'], ENT_QUOTES, 'UTF-8');
                $branchFilter = htmlspecialchars($_POST['branch_filter'], ENT_QUOTES, 'UTF-8');
                $documentTypeFilter = htmlspecialchars($_POST['document_type_filter'], ENT_QUOTES, 'UTF-8');
                $jobLevelFilter = htmlspecialchars($_POST['job_level_filter'], ENT_QUOTES, 'UTF-8');
                $genderFilter = htmlspecialchars($_POST['gender_filter'], ENT_QUOTES, 'UTF-8');
                $civilStatusFilter = htmlspecialchars($_POST['civil_status_filter'], ENT_QUOTES, 'UTF-8');
                $bloodTypeFilter = htmlspecialchars($_POST['blood_type_filter'], ENT_QUOTES, 'UTF-8');
                $religionFilter = htmlspecialchars($_POST['religion_filter'], ENT_QUOTES, 'UTF-8');
                $ageFilter = htmlspecialchars($_POST['age_filter'], ENT_QUOTES, 'UTF-8');
                $ageExplode = explode(',', $ageFilter);
                $minAge = $ageExplode[0];
                $maxAge = $ageExplode[1];
                $offset = ($currentPage - 1) * $documentPerPage;

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeCard(:offset, :documentPerPage, :documentSearch, :employementStatusFilter, :companyFilter, :departmentFilter, :jobPositionFilter, :branchFilter, :documentTypeFilter, :jobLevelFilter, :genderFilter, :civilStatusFilter, :bloodTypeFilter, :religionFilter, :minAge, :maxAge)');
                $sql->bindValue(':offset', $offset, PDO::PARAM_INT);
                $sql->bindValue(':documentPerPage', $documentPerPage, PDO::PARAM_INT);
                $sql->bindValue(':documentSearch', $documentSearch, PDO::PARAM_STR);
                $sql->bindValue(':employementStatusFilter', $employementStatusFilter, PDO::PARAM_STR);
                $sql->bindValue(':companyFilter', $companyFilter, PDO::PARAM_STR);
                $sql->bindValue(':departmentFilter', $departmentFilter, PDO::PARAM_STR);
                $sql->bindValue(':jobPositionFilter', $jobPositionFilter, PDO::PARAM_STR);
                $sql->bindValue(':branchFilter', $branchFilter, PDO::PARAM_STR);
                $sql->bindValue(':documentTypeFilter', $documentTypeFilter, PDO::PARAM_STR);
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
                
                $documentDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'delete');

                foreach ($options as $row) {
                    $documentID = $row['contact_id'];
                    $fileAs = $row['file_as'];
                    $departmentID = $row['department_id'];
                    $jobPositionID = $row['job_position_id'];
                    $branchID = $row['branch_id'];
                    $employmentStatus = $row['employment_status'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>';
                    $documentImage = $systemModel->checkImage($row['contact_image'], 'profile');

                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                    $jobPositionName = $jobPositionModel->getJobPosition($jobPositionID)['job_position_name'] ?? null;
                    $branchName = $branchModel->getBranch($branchID)['branch_name'] ?? null;
                   
                    $documentIDEncrypted = $securityModel->encryptData($documentID);

                    $delete = '';
                    if($documentDeleteAccess['total'] > 0){
                        $delete = '<div class="btn-prod-cart card-body position-absolute end-0 bottom-0">
                                        <button class="btn btn-danger delete-document" data-document-id="'. $documentID .'" title="Delete Employee">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>';
                    }
    
                    $response[] = [
                        'documentCard' => '<div class="col-sm-6 col-xl-4">
                                                <div class="card product-card">
                                                    <div class="card-img-top">
                                                        <a href="document.php?id='. $documentIDEncrypted .'">
                                                            <img src="'. $documentImage .'" alt="image" class="img-prod img-fluid" />
                                                        </a>
                                                        <div class="card-body position-absolute start-0 top-0">
                                                           '. $employmentStatus .'
                                                        </div>
                                                        '. $delete .'
                                                    </div>
                                                    <div class="card-body">
                                                        <a href="document.php?id='. $documentIDEncrypted .'">
                                                            <div class="d-flex align-items-center justify-content-between mt-3">
                                                                <h4 class="mb-0 text-truncate text-primary"><b>'. $fileAs .'</b></h4>
                                                            </div>
                                                            <div class="mt-3">
                                                                <h6 class="prod-content mb-0">'. $jobPositionName .'</h6>
                                                            </div>
                                                            <div>
                                                                <h6 class="prod-content mb-0">'. $departmentName .'</h6>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>'
                    ];
                }

                if ($documentPerPage === $initialEmployeesPerPage) {
                    $documentPerPage = $loadMoreEmployeesPerPage;
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>