<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/role-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$jobPositionModel = new JobPositionModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$roleModel = new RoleModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: job position table
        # Description:
        # Generates the job position table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'job position table':
            if(isset($_POST['filter_recruitment_status']) && isset($_POST['filter_department'])){
                $filterRecruitmentStatus = htmlspecialchars($_POST['filter_recruitment_status'], ENT_QUOTES, 'UTF-8');
                $filterDepartment = htmlspecialchars($_POST['filter_department'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateJobPositionTable(:filterRecruitmentStatus, :filterDepartment)');
                $sql->bindValue(':filterRecruitmentStatus', $filterRecruitmentStatus, PDO::PARAM_STR);
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $jobPositionDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 28, 'delete');

                foreach ($options as $row) {
                    $jobPositionID = $row['job_position_id'];
                    $jobPositionName = $row['job_position_name'];
                    $jobPositionDescription = $row['job_position_description'];
                    $recruitmentStatus = $row['recruitment_status'];
                    $departmentID = $row['department_id'];

                    $recruitmentStatusBadge = $recruitmentStatus ? '<span class="badge bg-light-success">Recruitment In Progress</span>' : '<span class="badge bg-light-warning">Not Recruiting</span>';

                    $departmentDetails = $departmentModel->getDepartment($departmentID);
                    $departmentName = $departmentDetails['department_name'] ?? null;

                    $jobPositionIDEncrypted = $securityModel->encryptData($jobPositionID);

                    $delete = '';
                    if($jobPositionDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-job-position" data-job-position-id="'. $jobPositionID .'" title="Delete Job Position">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $jobPositionID .'">',
                        'JOB_POSITION_NAME' => '<div class="col">
                                                    <h6 class="mb-0">'. $jobPositionName .'</h6>
                                                    <p class="text-muted f-12 mb-0">'. $jobPositionDescription .'</p>
                                                </div>',
                        'DEPARTMENT_NAME' => $departmentName,
                        'RECRUITMENT_STATUS' => $recruitmentStatusBadge,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="job-position.php?id='. $jobPositionIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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