<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$jobPositionModel = new JobPositionModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
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
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_STR);
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

        # -------------------------------------------------------------
        #
        # Type: job position responsibility table
        # Description:
        # Generates the job position responsibility table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'job position responsibility table':
            if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateJobPositionResponsibilityTable(:jobPositionID)');
                $sql->bindValue(':jobPositionID', $jobPositionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $updateJobPositionResponsibility = $userModel->checkSystemActionAccessRights($user_id, 19);
                $deleteJobPositionResponsibility = $userModel->checkSystemActionAccessRights($user_id, 20);

                foreach ($options as $row) {
                    $jobPositionResponsibilityID = $row['job_position_responsibility_id'];
                    $responsibility = $row['responsibility'];

                    $update = '';
                    if($updateJobPositionResponsibility['total'] > 0){
                        $update = '<button class="btn btn-icon btn-success update-job-position-responsibility" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-responsibility-offcanvas" aria-controls="job-position-responsibility-offcanvas" data-job-position-responsibility-id="'. $jobPositionResponsibilityID .'" title="Edit Responsibility">
                                        <i class="ti ti-pencil"></i>
                                    </button>';
                    }

                    $delete = '';
                    if($deleteJobPositionResponsibility['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-job-position-responsibility" data-job-position-responsibility-id="'. $jobPositionResponsibilityID .'" title="Delete Responsibility">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'RESPONSIBILITY' => $responsibility,
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
        # Type: job position requirement table
        # Description:
        # Generates the job position requirement table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'job position requirement table':
            if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateJobPositionRequirementTable(:jobPositionID)');
                $sql->bindValue(':jobPositionID', $jobPositionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $updateJobPositionRequirement = $userModel->checkSystemActionAccessRights($user_id, 22);
                $deleteJobPositionRequirement = $userModel->checkSystemActionAccessRights($user_id, 23);

                foreach ($options as $row) {
                    $jobPositionRequirementID = $row['job_position_requirement_id'];
                    $requirement = $row['requirement'];

                    $update = '';
                    if($updateJobPositionRequirement['total'] > 0){
                        $update = '<button class="btn btn-icon btn-success update-job-position-requirement" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-requirement-offcanvas" aria-controls="job-position-requirement-offcanvas" data-job-position-requirement-id="'. $jobPositionRequirementID .'" title="Edit Requirement">
                                        <i class="ti ti-pencil"></i>
                                    </button>';
                    }

                    $delete = '';
                    if($deleteJobPositionRequirement['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-job-position-requirement" data-job-position-requirement-id="'. $jobPositionRequirementID .'" title="Delete Requirement">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'REQUIREMENT' => $requirement,
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
        # Type: job position qualification table
        # Description:
        # Generates the job position qualification table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'job position qualification table':
            if(isset($_POST['job_position_id']) && !empty($_POST['job_position_id'])){
                $jobPositionID = htmlspecialchars($_POST['job_position_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateJobPositionQualificationTable(:jobPositionID)');
                $sql->bindValue(':jobPositionID', $jobPositionID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                $updateJobPositionQualification = $userModel->checkSystemActionAccessRights($user_id, 25);
                $deleteJobPositionQualification = $userModel->checkSystemActionAccessRights($user_id, 26);

                foreach ($options as $row) {
                    $jobPositionQualificationID = $row['job_position_qualification_id'];
                    $qualification = $row['qualification'];

                    $update = '';
                    if($updateJobPositionQualification['total'] > 0){
                        $update = '<button class="btn btn-icon btn-success update-job-position-qualification" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-qualification-offcanvas" aria-controls="job-position-qualification-offcanvas" data-job-position-qualification-id="'. $jobPositionQualificationID .'" title="Edit Qualification">
                                        <i class="ti ti-pencil"></i>
                                    </button>';
                    }

                    $delete = '';
                    if($deleteJobPositionQualification['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-job-position-qualification" data-job-position-qualification-id="'. $jobPositionQualificationID .'" title="Delete Qualification">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }

                    $response[] = [
                        'QUALIFICATION' => $qualification,
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
    }
}

?>