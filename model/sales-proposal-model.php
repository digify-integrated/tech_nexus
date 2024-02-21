<?php
/**
* Class SalesProposalModel
*
* The SalesProposalModel class handles sales proposal related operations and interactions.
*/
class SalesProposalModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposal
    # Description: Updates the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_sales_proposal_number (string): The sales proposal number.
    # - $p_customer_id (int): The customer ID.
    # - $p_comaker_id (int): The comaker ID.
    # - $p_product_id (int): The product ID.
    # - $p_referred_by (string): The referred by.
    # - $p_release_date (date): The release date.
    # - $p_start_date (date): The start date.
    # - $p_first_due_date (date): The first due date.
    # - $p_term_length (int): The term length.
    # - $p_term_type (string): The term type.
    # - $p_number_of_payments (int): The number of payments.
    # - $p_payment_frequency (string): The payment frequency.
    # - $p_for_registration (string): The for registration.
    # - $p_with_cr (string): The with cr.
    # - $p_for_transfer (string): The for transfer.
    # - $p_remarks (string): The remarks.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposal($p_sales_proposal_id, $p_sales_proposal_number, $p_customer_id, $p_comaker_id, $p_product_id, $p_referred_by, $p_release_date, $p_start_date, $p_first_due_date, $p_term_length, $p_term_type, $p_number_of_payments, $p_payment_frequency, $p_for_registration, $p_with_cr, $p_for_transfer, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposal(:p_sales_proposal_id, :p_sales_proposal_number, :p_customer_id, :p_comaker_id, :p_product_id, :p_referred_by, :p_release_date, :p_start_date, :p_first_due_date, :p_term_length, :p_term_type, :p_number_of_payments, :p_payment_frequency, :p_for_registration, :p_with_cr, :p_for_transfer, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_number', $p_sales_proposal_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id', $p_comaker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_referred_by', $p_referred_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_date', $p_release_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_due_date', $p_first_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_payments', $p_number_of_payments, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_registration', $p_for_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_transfer', $p_for_transfer, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalAccessories
    # Description: Updates the sales proposal accessories.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalAccessories($p_sales_proposal_accessories_id, $p_sales_proposal_id, $p_accessories, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalAccessories(:p_sales_proposal_accessories_id, :p_sales_proposal_id, :p_accessories, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_accessories_id', $p_sales_proposal_accessories_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_accessories', $p_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalJobOrder
    # Description: Updates the sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_job_order_id (int): The sales proposal job order ID.
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_job_order (string): The job order.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalJobOrder($p_sales_proposal_job_order_id, $p_sales_proposal_id, $p_job_order, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalJobOrder(:p_sales_proposal_job_order_id, :p_sales_proposal_id, :p_job_order, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_job_order_id', $p_sales_proposal_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_order', $p_job_order, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalAdditionalJobOrder
    # Description: Updates the sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_additional_job_order_id (int): The sales proposal job order ID.
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_job_order_number (string): The job order number.
    # - $p_job_order_date (string): The job order date.
    # - $p_particulars (string): The particulars.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalAdditionalJobOrder($p_sales_proposal_additional_job_order_id, $p_sales_proposal_id, $p_job_order_number, $p_job_order_date, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalAdditionalJobOrder(:p_sales_proposal_additional_job_order_id, :p_sales_proposal_id, :p_job_order_number, :p_job_order_date, :p_particulars, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_additional_job_order_id', $p_sales_proposal_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_order_number', $p_job_order_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_date', $p_job_order_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposal
    # Description: Inserts the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_number (string): The sales proposal number.
    # - $p_customer_id (int): The customer ID.
    # - $p_comaker_id (int): The comaker ID.
    # - $p_product_id (int): The product ID.
    # - $p_referred_by (string): The referred by.
    # - $p_release_date (date): The release date.
    # - $p_start_date (date): The start date.
    # - $p_first_due_date (date): The first due date.
    # - $p_term_length (int): The term length.
    # - $p_term_type (string): The term type.
    # - $p_number_of_payments (int): The number of payments.
    # - $p_payment_frequency (string): The payment frequency.
    # - $p_for_registration (string): The for registration.
    # - $p_with_cr (string): The with cr.
    # - $p_for_transfer (string): The for transfer.
    # - $p_remarks (string): The remarks.
    # - $p_created_by (int): The created by.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposal($p_sales_proposal_number, $p_customer_id, $p_comaker_id, $p_product_id, $p_referred_by, $p_release_date, $p_start_date, $p_first_due_date, $p_term_length, $p_term_type, $p_number_of_payments, $p_payment_frequency, $p_for_registration, $p_with_cr, $p_for_transfer, $p_remarks, $p_created_by, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposal(:p_sales_proposal_number, :p_customer_id, :p_comaker_id, :p_product_id, :p_referred_by, :p_release_date, :p_start_date, :p_first_due_date, :p_term_length, :p_term_type, :p_number_of_payments, :p_payment_frequency, :p_for_registration, :p_with_cr, :p_for_transfer, :p_remarks, :p_created_by, :p_last_log_by, @p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_number', $p_sales_proposal_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id', $p_comaker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_referred_by', $p_referred_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_date', $p_release_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_due_date', $p_first_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_payments', $p_number_of_payments, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_registration', $p_for_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_transfer', $p_for_transfer, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_created_by', $p_created_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_sales_proposal_id AS p_sales_proposal_id");
        $salesProposalID = $result->fetch(PDO::FETCH_ASSOC)['p_sales_proposal_id'];

        return $salesProposalID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalAccessories
    # Description: Inserts the sales proposal accessories.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalAccessories($p_sales_proposal_id, $p_accessories, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalAccessories(:p_sales_proposal_id, :p_accessories, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_accessories', $p_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalJobOrder
    # Description: Inserts the sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_job_order (string): The job order.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalJobOrder($p_sales_proposal_id, $p_job_order, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalJobOrder(:p_sales_proposal_id, :p_job_order, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_order', $p_job_order, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalAdditionalJobOrder
    # Description: Inserts the sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_job_order_number (string): The job order number.
    # - $p_job_order_date (string): The job order date.
    # - $p_particulars (string): The particulars.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalAdditionalJobOrder($p_sales_proposal_id, $p_job_order_number, $p_job_order_date, $p_cost, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalAdditionalJobOrder(:p_sales_proposal_id, :p_job_order_number, :p_job_order_date, :p_particulars, :p_cost, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_job_order_number', $p_job_order_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_job_order_date', $p_job_order_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_particulars', $p_particulars, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalExist
    # Description: Checks if a sales proposal exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalAccessoriesExist
    # Description: Checks if a sales proposal accessories exists.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalAccessoriesExist($p_sales_proposal_accessories_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalAccessoriesExist(:p_sales_proposal_accessories_id)');
        $stmt->bindValue(':p_sales_proposal_accessories_id', $p_sales_proposal_accessories_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalJobOrderExist
    # Description: Checks if a sales proposal job order exists.
    #
    # Parameters:
    # - $p_sales_proposal_job_order_id (int): The sales proposal job order ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalJobOrderExist($p_sales_proposal_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalJobOrderExist(:p_sales_proposal_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_job_order_id', $p_sales_proposal_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalAdditionalJobOrderExist
    # Description: Checks if a sales proposal job order exists.
    #
    # Parameters:
    # - $p_sales_proposal_additional_job_order_id (int): The sales proposal additional job order ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalAdditionalJobOrderExist($p_sales_proposal_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalAdditionalJobOrderExist(:p_sales_proposal_additional_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_additional_job_order_id', $p_sales_proposal_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposal
    # Description: Deletes the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAccessories
    # Description: Deletes the sales proposal accessories.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAccessories($p_sales_proposal_accessories_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalAccessories(:p_sales_proposal_accessories_id)');
        $stmt->bindValue(':p_sales_proposal_accessories_id', $p_sales_proposal_accessories_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalJobOrder
    # Description: Deletes the sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_job_order_id (int): The sales proposal job order ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalJobOrder($p_sales_proposal_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalJobOrder(:p_sales_proposal_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_job_order_id', $p_sales_proposal_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteSalesProposalAdditionalJobOrder
    # Description: Deletes the sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_additional_job_order_id (int): The sales proposal additional job order ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalAdditionalJobOrder($p_sales_proposal_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalAdditionalJobOrder(:p_sales_proposal_additional_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_additional_job_order_id', $p_sales_proposal_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposal
    # Description: Retrieves the details of a sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAccessories
    # Description: Retrieves the details of a sales proposal accessories.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessories($p_sales_proposal_accessories_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalAccessories(:p_sales_proposal_accessories_id)');
        $stmt->bindValue(':p_sales_proposal_accessories_id', $p_sales_proposal_accessories_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalJobOrder
    # Description: Retrieves the details of a sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_job_order_id (int): The sales proposal job order ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrder($p_sales_proposal_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalJobOrder(:p_sales_proposal_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_job_order_id', $p_sales_proposal_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalAdditionalJobOrder
    # Description: Retrieves the details of a sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_additional_job_order_id (int): The sales proposal additional job order ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrder($p_sales_proposal_additional_job_order_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalAdditionalJobOrder(:p_sales_proposal_additional_job_order_id)');
        $stmt->bindValue(':p_sales_proposal_additional_job_order_id', $p_sales_proposal_additional_job_order_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalStatus
    # Description: Retrieves the sales proposal status badge.
    #
    # Parameters:
    # - $p_sales_proposal_status (string): The sales proposal status.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalStatus($p_sales_proposal_status) {
        $statusClasses = [
            'Draft' => 'secondary',
            'For Approval' => 'primary',
            'For Final Approval' => 'info',
            'Cancelled' => 'warning',
            'Rejected' => 'danger',
            'Approved' => 'success'
        ];
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_sales_proposal_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_sales_proposal_status . '</span>';
    }
    # -------------------------------------------------------------
}
?>