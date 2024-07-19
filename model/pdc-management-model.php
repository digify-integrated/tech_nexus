<?php
/**
* Class PDCManagementModel
*
* The PDCManagementModel class handles pdc management related operations and interactions.
*/
class PDCManagementModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updatePDCManagement
    # Description: Updates the pdc management.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    # - $p_product_category_name (string): The pdc management name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updatePDCManagement($p_loan_collection_id, $p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_check_number, $p_check_date, $p_payment_amount, $p_payment_details, $p_bank_branch, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updatePDCManagement(:p_loan_collection_id, :p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_check_number, :p_check_date, :p_payment_amount, :p_payment_details, :p_bank_branch, :p_last_log_by)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLoanCollectionStatus
    # Description: Updates the pdc management.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    # - $p_product_category_name (string): The pdc management name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLoanCollectionStatus($p_loan_collection_id, $p_collection_status, $p_reason, $p_remarks, $p_new_deposit_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLoanCollectionStatus(:p_loan_collection_id, :p_collection_status, :p_reason, :p_remarks, :p_new_deposit_date, :p_last_log_by)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_collection_status', $p_collection_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_deposit_date', $p_new_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPDCManagement
    # Description: Inserts the pdc management.
    #
    # Parameters:
    # - $p_product_category_name (string): The pdc management name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertPDCManagement($p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_check_number, $p_check_date, $p_payment_amount, $p_payment_details, $p_bank_branch, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPDCManagement(:p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_check_number, :p_check_date, :p_payment_amount, :p_payment_details, :p_bank_branch, :p_last_log_by, @p_loan_collection_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_loan_collection_id AS p_loan_collection_id");
        $p_loan_collection_id = $result->fetch(PDO::FETCH_ASSOC)['p_loan_collection_id'];

        return $p_loan_collection_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPDCManagement
    # Description: Inserts the pdc management.
    #
    # Parameters:
    # - $p_product_category_name (string): The pdc management name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function updateImportPDC($p_loan_collection_id, $p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_payment_details, $p_payment_amount, $p_collection_status, $p_check_date, $p_check_number, $p_bank_branch, $p_payment_date, $p_transaction_date, $p_onhold_date, $p_onhold_reason, $p_deposit_date, $p_for_deposit_date, $p_redeposit_date, $p_new_deposit_date, $p_clear_date, $p_cancellation_date, $p_cancellation_reason, $p_reversal_date, $p_pulled_out_date, $p_pulled_out_reason, $p_reversal_reason, $p_reversal_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateImportPDC(:p_loan_collection_id, :p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_payment_details, :p_payment_amount, :p_collection_status, :p_check_date, :p_check_number, :p_bank_branch, :p_payment_date, :p_transaction_date, :p_onhold_date, :p_onhold_reason, :p_deposit_date, :p_for_deposit_date, :p_redeposit_date, :p_new_deposit_date, :p_clear_date, :p_cancellation_date, :p_cancellation_reason, :p_reversal_date, :p_pulled_out_date, :p_pulled_out_reason, :p_reversal_reason, :p_reversal_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_collection_status', $p_collection_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_date', $p_transaction_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_onhold_date', $p_onhold_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_onhold_reason', $p_onhold_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_deposit_date', $p_for_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_redeposit_date', $p_redeposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_deposit_date', $p_new_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_clear_date', $p_clear_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_date', $p_cancellation_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_reason', $p_cancellation_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_date', $p_reversal_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_pulled_out_date', $p_pulled_out_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_pulled_out_reason', $p_pulled_out_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_reason', $p_reversal_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_remarks', $p_reversal_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPDCManagement
    # Description: Inserts the pdc management.
    #
    # Parameters:
    # - $p_product_category_name (string): The pdc management name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertImportPDC($p_sales_proposal_id, $p_loan_number, $p_product_id, $p_customer_id, $p_pdc_type, $p_payment_details, $p_payment_amount, $p_collection_status, $p_check_date, $p_check_number, $p_bank_branch, $p_payment_date, $p_transaction_date, $p_onhold_date, $p_onhold_reason, $p_deposit_date, $p_for_deposit_date, $p_redeposit_date, $p_new_deposit_date, $p_clear_date, $p_cancellation_date, $p_cancellation_reason, $p_reversal_date, $p_pulled_out_date, $p_pulled_out_reason, $p_reversal_reason, $p_reversal_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertImportPDC(:p_sales_proposal_id, :p_loan_number, :p_product_id, :p_customer_id, :p_pdc_type, :p_payment_details, :p_payment_amount, :p_collection_status, :p_check_date, :p_check_number, :p_bank_branch, :p_payment_date, :p_transaction_date, :p_onhold_date, :p_onhold_reason, :p_deposit_date, :p_for_deposit_date, :p_redeposit_date, :p_new_deposit_date, :p_clear_date, :p_cancellation_date, :p_cancellation_reason, :p_reversal_date, :p_pulled_out_date, :p_pulled_out_reason, :p_reversal_reason, :p_reversal_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_pdc_type', $p_pdc_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_details', $p_payment_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_collection_status', $p_collection_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_date', $p_transaction_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_onhold_date', $p_onhold_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_onhold_reason', $p_onhold_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_deposit_date', $p_for_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_redeposit_date', $p_redeposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_deposit_date', $p_new_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_clear_date', $p_clear_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_date', $p_cancellation_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_cancellation_reason', $p_cancellation_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_date', $p_reversal_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_pulled_out_date', $p_pulled_out_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_pulled_out_reason', $p_pulled_out_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_reason', $p_reversal_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_reversal_remarks', $p_reversal_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLoanCollectionExist
    # Description: Checks if a pdc management exists.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLoanCollectionExist($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLoanCollectionExist(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLoanCollectionConflict
    # Description: Checks if a pdc management exists.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLoanCollectionConflict($p_sales_proposal_id, $p_check_number) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLoanCollectionConflict(:p_sales_proposal_id, :p_check_number)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deletePDCManagement
    # Description: Deletes the pdc management.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deletePDCManagement($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deletePDCManagement(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getPDCManagement
    # Description: Retrieves the details of a pdc management.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    #
    # Returns:
    # - An array containing the pdc management details.
    #
    # -------------------------------------------------------------
    public function getPDCManagement($p_loan_collection_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPDCManagement(:p_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicatePDCManagement
    # Description: Duplicates the pdc management.
    #
    # Parameters:
    # - $p_loan_collection_id (int): The pdc management ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicatePDCManagement($p_loan_collection_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicatePDCManagement(:p_loan_collection_id, :p_last_log_by, @p_new_loan_collection_id)');
        $stmt->bindValue(':p_loan_collection_id', $p_loan_collection_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_loan_collection_id AS loan_collection_id");
        $productCategoryID = $result->fetch(PDO::FETCH_ASSOC)['loan_collection_id'];

        return $productCategoryID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePDCManagementOptions
    # Description: Generates the pdc management options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePDCManagementOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePDCManagementOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productCategoryID = $row['loan_collection_id'];
            $productCategoryName = $row['product_category_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productCategoryName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generatePDCManagementCheckbox
    # Description: Generates the pdc management check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generatePDCManagementCheckbox() {
        $stmt = $this->db->getConnection()->prepare('CALL generatePDCManagementOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $productCategoryID = $row['loan_collection_id'];
            $productCategoryName = $row['product_category_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input product-category-filter" type="checkbox" id="product-category-' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '" value="' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="product-category-' . htmlspecialchars($productCategoryID, ENT_QUOTES) . '">' . htmlspecialchars($productCategoryName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>