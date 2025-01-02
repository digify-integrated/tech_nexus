<?php
/**
* Class DisbursementModel
*
* The DisbursementModel class handles disbursement related operations and interactions.
*/
class DisbursementModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDisbursement
    # Description: Updates the disbursement.
    #
    # Parameters:
    # - $p_disbursement_id (int): The disbursement ID.
    # - $p_product_category_name (string): The disbursement name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDisbursement($p_disbursement_id, $p_customer_id, $p_department_id, $p_company_id, $p_transaction_number, $p_transaction_type, $p_fund_source, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursement(:p_disbursement_id, :p_customer_id, :p_department_id, :p_company_id, :p_transaction_number, :p_transaction_type, :p_fund_source, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_number', $p_transaction_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_fund_source', $p_fund_source, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateDisbursementStatus
    # Description: Updates the disbursement.
    #
    # Parameters:
    # - $p_disbursement_id (int): The disbursement ID.
    # - $p_product_category_name (string): The disbursement name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateDisbursementStatus($p_disbursement_id, $p_disbursement_status, $p_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursementStatus(:p_disbursement_id, :p_disbursement_status, :p_reason, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_disbursement_status', $p_disbursement_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateDisbursementParticulars($p_disbursement_particulars_id, $p_disbursement_id, $p_chart_of_account_id, $p_remarks, $p_particulars_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursementParticulars(:p_disbursement_particulars_id, :p_disbursement_id, :p_chart_of_account_id, :p_remarks, :p_particulars_amount, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_particulars_id', $p_disbursement_particulars_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars_amount', $p_particulars_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertDisbursement
    # Description: Inserts the disbursement.
    #
    # Parameters:
    # - $p_product_category_name (string): The disbursement name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertDisbursement($p_customer_id, $p_department_id, $p_company_id, $p_transaction_number, $p_transaction_type, $p_fund_source, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDisbursement(:p_customer_id, :p_department_id, :p_company_id, :p_transaction_number, :p_transaction_type, :p_fund_source, :p_particulars, :p_last_log_by, @p_disbursement_id)');
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_department_id', $p_department_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_number', $p_transaction_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_fund_source', $p_fund_source, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_disbursement_id AS p_disbursement_id");
        $p_disbursement_id = $result->fetch(PDO::FETCH_ASSOC)['p_disbursement_id'];

        return $p_disbursement_id;
    }

    public function insertDisbursementParticulars($p_disbursement_id, $p_chart_of_account_id, $p_remarks, $p_particulars_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDisbursementParticulars(:p_disbursement_id, :p_chart_of_account_id, :p_remarks, :p_particulars_amount, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars_amount', $p_particulars_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createDisbursementEntry($p_disbursement_id, $p_transaction_number, $p_fund_source, $p_entry_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createDisbursementEntry(:p_disbursement_id, :p_transaction_number, :p_fund_source, :p_entry_type, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transaction_number', $p_transaction_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_fund_source', $p_fund_source, PDO::PARAM_STR);
        $stmt->bindValue(':p_entry_type', $p_entry_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createLiquidation($p_disbursement_id, $p_last_log_by, $p_created_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createLiquidation(:p_disbursement_id, :p_last_log_by, :p_created_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_created_by', $p_created_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkDisbursementExist
    # Description: Checks if a disbursement exists.
    #
    # Parameters:
    # - $p_disbursement_id (int): The disbursement ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkDisbursementExist($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDisbursementExist(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkDisbursementParticularsExist($p_disbursement_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDisbursementParticularsExist(:p_disbursement_particulars_id)');
        $stmt->bindValue(':p_disbursement_particulars_id', $p_disbursement_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteDisbursement
    # Description: Deletes the disbursement.
    #
    # Parameters:
    # - $p_disbursement_id (int): The disbursement ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteDisbursement($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDisbursement(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteDisbursementParticulars($p_disbursement_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDisbursementParticulars(:p_disbursement_particulars_id)');
        $stmt->bindValue(':p_disbursement_particulars_id', $p_disbursement_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getDisbursement
    # Description: Retrieves the details of a disbursement.
    #
    # Parameters:
    # - $p_disbursement_id (int): The disbursement ID.
    #
    # Returns:
    # - An array containing the disbursement details.
    #
    # -------------------------------------------------------------
    public function getDisbursement($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursement(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisbursementTotal($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementTotal(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisbursementParticulars($p_disbursement_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementParticulars(:p_disbursement_particulars_id)');
        $stmt->bindValue(':p_disbursement_particulars_id', $p_disbursement_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>