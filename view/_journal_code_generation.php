<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/journal-code-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$journalCodeModel = new JournalCodeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: journal code table
        # Description:
        # Generates the journal code table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'journal code table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateJournalCodeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $journalCodeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 118, 'delete');

            foreach ($options as $row) {
                $journalCodeID = $row['journal_code_id'];
                $company_id = $row['company_id'];
                $transaction_type = $row['transaction_type'];
                $product_type_id = $row['product_type_id'];
                $transaction = $row['transaction'];
                $item = $row['item'];
                $debit = $row['debit'];
                $credit = $row['credit'];
                $reference_code = $row['reference_code'];

                $journalCodeIDEncrypted = $securityModel->encryptData($journalCodeID);

                $delete = '';
                if($journalCodeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-journal-code" data-journal-code-id="'. $journalCodeID .'" title="Delete Journal Code">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $journalCodeID .'">',
                    'COMPANY_ID' => $company_id,
                    'TRANSACTION_TYPE' => $transaction_type,
                    'PRODUCT_TYPE' => $product_type_id,
                    'TRANSACTION' => $transaction,
                    'ITEM' => $item,
                    'DEBIT' => $debit,
                    'CREDIT' => $credit,
                    'REFERENCE_CODE' => $reference_code,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="journal-code.php?id='. $journalCodeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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