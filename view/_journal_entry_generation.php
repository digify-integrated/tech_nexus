<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/product-category-model.php';
require_once '../model/product-subcategory-model.php';
require_once '../model/miscellaneous-client-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$productCategoryModel = new ProductCategoryModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: journal entry table
        # Description:
        # Generates the journal entry table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'journal entry table':
            $filterJournalEntryDateStartDate = $systemModel->checkDate('empty', $_POST['filter_journal_entry_date_start_date'], '', 'Y-m-d', '');
            $filterJournalEntryDateEndDate = $systemModel->checkDate('empty', $_POST['filter_journal_entry_date_end_date'], '', 'Y-m-d', '');
            $journal_id_filter = $_POST['journal_id_filter'] ?? '';

            if (!empty($journal_id_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $journal_id_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_journal_id = implode(', ', $quoted_values_array);
            } else {
                $filter_journal_id = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateJournalEntryTable(:filterJournalEntryDateStartDate, :filterJournalEntryDateEndDate, :filter_journal_id)');
            $sql->bindValue(':filterJournalEntryDateStartDate', $filterJournalEntryDateStartDate, PDO::PARAM_STR);
            $sql->bindValue(':filterJournalEntryDateEndDate', $filterJournalEntryDateEndDate, PDO::PARAM_STR);
            $sql->bindValue(':filter_journal_id', $filter_journal_id, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {                
                $journal_entry_date = $systemModel->checkDate('empty', $row['journal_entry_date'], '', 'm/d/Y', '');
                $loan_number = $row['loan_number'];
                $reference_code = $row['reference_code'];
                $journal_id = $row['journal_id'];
                $journal_item = $row['journal_item'];
                $debit = $row['debit'];
                $credit = $row['credit'];
                $journal_label = $row['journal_label'];
                $analytic_lines = $row['analytic_lines'];
                $analytic_distribution = $row['analytic_distribution'];

                $response[] = [
                    'LOAN_NUMBER' => $loan_number,
                    'JOURNAL_ENTRY_DATE' => $journal_entry_date,
                    'REFERENCE_CODE' => $reference_code,
                    'JOURNAL_ID' => $journal_id,
                    'JOURNAL_ITEM' => $journal_item,
                    'DEBIT' => $debit,
                    'CREDIT' => $credit,
                    'JOURNAL_LABEL' => $journal_label,
                    'ANALYTIC_LINES' => $analytic_lines,
                    'ANALYTIC_DISTRIBUTION' => $analytic_distribution
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>