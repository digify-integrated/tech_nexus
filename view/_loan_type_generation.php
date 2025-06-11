<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/loan-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$loanTypeModel = new LoanTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: loan type table
        # Description:
        # Generates the loan type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'loan type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLoanTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $loanTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 150, 'delete');

            foreach ($options as $row) {
                $loanTypeID = $row['loan_type_id'];
                $loanTypeName = $row['loan_type_name'];

                $loanTypeIDEncrypted = $securityModel->encryptData($loanTypeID);

                $delete = '';
                if($loanTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-loan-type" data-loan-type-id="'. $loanTypeID .'" title="Delete Loan Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $loanTypeID .'">',
                    'LOAN_TYPE_NAME' => $loanTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="loan-type.php?id='. $loanTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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