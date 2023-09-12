<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/job-level-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$jobLevelModel = new JobLevelModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: job level table
        # Description:
        # Generates the job level table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'job level table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateJobLevelTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $jobLevelDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 29, 'delete');

            foreach ($options as $row) {
                $jobLevelID = $row['job_level_id'];
                $currentLevel = $row['current_level'];
                $rank = $row['rank'];
                $functionalLevel = $row['functional_level'];

                $jobLevelIDEncrypted = $securityModel->encryptData($jobLevelID);

                $delete = '';
                if($jobLevelDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-job-level" data-job-level-id="'. $jobLevelID .'" title="Delete Job Level">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $jobLevelID .'">',
                    'CURRENT_LEVEL' => $currentLevel,
                    'RANK' => $rank,
                    'FUNCTIONAL_LEVEL' => $functionalLevel,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="job-level.php?id='. $jobLevelIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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