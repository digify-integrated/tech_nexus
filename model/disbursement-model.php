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
    public function updateDisbursement($p_disbursement_id, $p_payable_type, $p_customer_id, $p_department_id, $p_company_id, $p_transaction_number, $p_transaction_type, $p_fund_source, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursement(:p_disbursement_id, :p_payable_type, :p_customer_id, :p_department_id, :p_company_id, :p_transaction_number, :p_transaction_type, :p_fund_source, :p_particulars, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payable_type', $p_payable_type, PDO::PARAM_STR);
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
    
    public function updateDisbursementCheckStatus($p_disbursement_check_id, $p_disburse_status, $p_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursementCheckStatus(:p_disbursement_check_id, :p_disburse_status, :p_reason, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_check_id', $p_disbursement_check_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_disburse_status', $p_disburse_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function cancelAllDisbursementCheck($p_disbursement_id, $p_reason, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL cancelAllDisbursementCheck(:p_disbursement_id, :p_reason, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateLiquidationParticularsStatus($p_liquidation_particulars_id, $p_liquidation_particulars_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLiquidationParticularsStatus(:p_liquidation_particulars_id, :p_liquidation_particulars_status, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_liquidation_particulars_status', $p_liquidation_particulars_status, PDO::PARAM_STR);
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

    public function updateDisbursementCheck($p_disbursement_check_id, $p_disbursement_id, $p_bank_branch, $p_check_number, $p_check_date, $p_check_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDisbursementCheck(:p_disbursement_check_id, :p_disbursement_id, :p_bank_branch, :p_check_number, :p_check_date, :p_check_amount, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_check_id', $p_disbursement_check_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, type: PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_amount', $p_check_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateLiquidationParticulars($p_liquidation_particulars_id, $p_liquidation_id, $p_particulars, $p_particulars_amount, $p_reference_type, $p_reference_number, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLiquidationParticulars(:p_liquidation_particulars_id, :p_liquidation_id, :p_particulars, :p_particulars_amount, :p_reference_type, :p_reference_number, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_liquidation_id', $p_liquidation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars_amount', $p_particulars_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateLiquidationBalance($p_liquidation_particulars_id, $p_particulars_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLiquidationBalance(:p_liquidation_particulars_id, :p_particulars_amount, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
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
    public function insertDisbursement($p_payable_type, $p_customer_id, $p_department_id, $p_company_id, $p_transaction_number, $p_transaction_type, $p_fund_source, $p_particulars, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDisbursement(:p_payable_type, :p_customer_id, :p_department_id, :p_company_id, :p_transaction_number, :p_transaction_type, :p_fund_source, :p_particulars, :p_last_log_by, @p_disbursement_id)');
        $stmt->bindValue(':p_payable_type', $p_payable_type, PDO::PARAM_STR);
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

    public function insertDisbursementCheck($p_disbursement_id, $p_bank_branch, $p_check_number, $p_check_date, $p_check_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertDisbursementCheck(:p_disbursement_id, :p_bank_branch, :p_check_number, :p_check_date, :p_check_amount, :p_last_log_by)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_bank_branch',  $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_amount', $p_check_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertLiquidationParticulars($p_liquidation_id, $p_particulars, $p_particulars_amount, $p_reference_type, $p_reference_number, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLiquidationParticulars(:p_liquidation_id, :p_particulars, :p_particulars_amount, :p_reference_type, :p_reference_number, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_id', $p_liquidation_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars_amount', $p_particulars_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_type', $p_reference_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
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

    public function createLiquidationParticularsEntry($p_liquidation_particulars_id, $p_transaction_number, $p_company_id, $p_chart_of_account_id, $p_transaction_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL createLiquidationParticularsEntry(:p_liquidation_particulars_id, :p_transaction_number, :p_company_id, :p_chart_of_account_id, :p_transaction_type, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transaction_number', $p_transaction_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_chart_of_account_id', $p_chart_of_account_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
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

    public function checkDisbursementCheckExist($p_disbursement_check_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkDisbursementCheckExist(:p_disbursement_check_id)');
        $stmt->bindValue(':p_disbursement_check_id', $p_disbursement_check_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkLiquidationExist($p_liquidation_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLiquidationExist(:p_liquidation_id)');
        $stmt->bindValue(':p_liquidation_id', $p_liquidation_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkLiquidationParticularsExist($p_liquidation_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLiquidationParticularsExist(:p_liquidation_particulars_id)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
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

    public function deleteDisbursementCheck($p_disbursement_check_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteDisbursementCheck(:p_disbursement_check_id)');
        $stmt->bindValue(':p_disbursement_check_id', $p_disbursement_check_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteLiquidationParticulars($p_liquidation_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLiquidationParticulars(:p_liquidation_particulars_id)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function deleteLiquidationBalance($p_liquidation_id, $particulars_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLiquidationBalance(:p_liquidation_id, :particulars_amount, :p_last_log_by)');
        $stmt->bindValue(':p_liquidation_id', $p_liquidation_id, PDO::PARAM_INT);
        $stmt->bindValue(':particulars_amount', $particulars_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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

    public function getUnreplishedDisbursement($p_fund_source) {
        $stmt = $this->db->getConnection()->prepare('CALL getUnreplishedDisbursement(:p_fund_source)');
        $stmt->bindValue(':p_fund_source', $p_fund_source, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisbursementTotal($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementTotal(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisbursementCheckTotal($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementCheckTotal(:p_disbursement_id)');
        $stmt->bindValue(':p_disbursement_id', $p_disbursement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getDisbursementNegotiatedCheckTotal($p_disbursement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementNegotiatedCheckTotal(:p_disbursement_id)');
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

    public function getDisbursementCheck($p_disbursement_particulars_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getDisbursementCheck(:p_disbursement_particulars_id)');
        $stmt->bindValue(':p_disbursement_particulars_id', $p_disbursement_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLiquidation($p_liquidation_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getLiquidation(:p_liquidation_id)');
        $stmt->bindValue(':p_liquidation_id', $p_liquidation_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getLiquidationParticulars($p_liquidation_particulars_id): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getLiquidationParticulars(:p_liquidation_particulars_id)');
        $stmt->bindValue(':p_liquidation_particulars_id', $p_liquidation_particulars_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>