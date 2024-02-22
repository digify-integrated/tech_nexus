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
    public function updateSalesProposalAdditionalJobOrder($p_sales_proposal_additional_job_order_id, $p_sales_proposal_id, $p_job_order_number, $p_job_order_date, $p_particulars, $p_cost, $p_last_log_by) {
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
    #
    # Function: updateSalesProposalPricingComputation
    # Description: Updates the sales proposal pricing computation.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_delivery_price (int): The sales proposal ID.
    # - $p_cost_of_accessories (int): The cost of accessories.
    # - $p_reconditioning_cost (int): The reconditioning cost.
    # - $p_subtotal (int): The subtotal.
    # - $p_downpayment (int): The downpayment.
    # - $p_outstanding_balance (int): The outstanding balance.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalPricingComputation($p_sales_proposal_id, $p_delivery_price, $p_cost_of_accessories, $p_reconditioning_cost, $p_subtotal, $p_downpayment, $p_outstanding_balance, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalPricingComputation(:p_sales_proposal_id, :p_delivery_price, :p_cost_of_accessories, :p_reconditioning_cost, :p_subtotal, :p_downpayment, :p_outstanding_balance, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_delivery_price', $p_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost_of_accessories', $p_cost_of_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_reconditioning_cost', $p_reconditioning_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_subtotal', $p_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_downpayment', $p_downpayment, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalOtherCharges
    # Description: Updates the sales proposal other charges.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_insurance_coverage (int): The insurance coverage.
    # - $p_insurance_premium (int): The insurance premium.
    # - $p_handling_fee (int): The handling fee.
    # - $p_transfer_fee (int): The transfer fee.
    # - $p_registration_fee (int): The registration fee.
    # - $p_doc_stamp_tax (int): The doc stamp tax.
    # - $p_transaction_fee (int): The transaction fee.
    # - $p_total_other_charges (int): The total other charges.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalOtherCharges($p_sales_proposal_id, $p_insurance_coverage, $p_insurance_premium, $p_handling_fee, $p_transfer_fee, $p_registration_fee, $p_doc_stamp_tax, $p_transaction_fee, $p_total_other_charges, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalOtherCharges(:p_sales_proposal_id, :p_insurance_coverage, :p_insurance_premium, :p_handling_fee, :p_transfer_fee, :p_registration_fee, :p_doc_stamp_tax, :p_transaction_fee, :p_total_other_charges, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_insurance_coverage', $p_insurance_coverage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium', $p_insurance_premium, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee', $p_handling_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee', $p_transfer_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fee', $p_registration_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax', $p_doc_stamp_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee', $p_transaction_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_other_charges', $p_total_other_charges, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalRenewalAmount
    # Description: Updates the sales proposal renewal amount.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_registration_second_year (int): The registration second year.
    # - $p_registration_third_year (int): The registration third year.
    # - $p_registration_fourth_year (int): The registration fourth year.
    # - $p_insurance_coverage_second_year (int): The insurance coverage second year.
    # - $p_insurance_coverage_third_year (int): The insurance coverage third year.
    # - $p_insurance_coverage_fourth_year (int): The insurance coverage fourth year.
    # - $p_insurance_premium_second_year (int): The insurance premium second year.
    # - $p_insurance_premium_third_year (int): The insurance premium third year.
    # - $p_insurance_premium_fourth_year (int): The insurance premium fourth year.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalRenewalAmount($p_sales_proposal_id, $p_registration_second_year, $p_registration_third_year, $p_registration_fourth_year, $p_insurance_coverage_second_year, $p_insurance_coverage_third_year, $p_insurance_coverage_fourth_year, $p_insurance_premium_second_year, $p_insurance_premium_third_year, $p_insurance_premium_fourth_year, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalRenewalAmount(:p_sales_proposal_id, :p_registration_second_year, :p_registration_third_year, :p_registration_fourth_year, :p_insurance_coverage_second_year, :p_insurance_coverage_third_year, :p_insurance_coverage_fourth_year, :p_insurance_premium_second_year, :p_insurance_premium_third_year, :p_insurance_premium_fourth_year, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_registration_second_year', $p_registration_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_third_year', $p_registration_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fourth_year', $p_registration_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_second_year', $p_insurance_coverage_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_third_year', $p_insurance_coverage_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_fourth_year', $p_insurance_coverage_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_second_year', $p_insurance_premium_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_third_year', $p_insurance_premium_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_fourth_year', $p_insurance_premium_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalDepositAmount
    # Description: Updates the sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_deposit_amount_id (int): The sales proposal deposit amount ID.
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_deposit_date (date): The deposit date.
    # - $p_reference_number (string): The reference number.
    # - $p_deposit_amount (double): The particulars.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalDepositAmount($p_sales_proposal_deposit_amount_id, $p_sales_proposal_id, $p_deposit_date, $p_reference_number, $p_deposit_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalDepositAmount(:p_sales_proposal_deposit_amount_id, :p_sales_proposal_id, :p_deposit_date, :p_reference_number, :p_deposit_amount, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_deposit_amount_id', $p_sales_proposal_deposit_amount_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_amount', $p_deposit_amount, PDO::PARAM_STR);
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
    public function insertSalesProposalAdditionalJobOrder($p_sales_proposal_id, $p_job_order_number, $p_job_order_date, $p_particulars, $p_cost, $p_last_log_by) {
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
    #
    # Function: insertSalesProposalPricingComputation
    # Description: Inserts the sales proposal pricing computation.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_delivery_price (int): The sales proposal ID.
    # - $p_cost_of_accessories (int): The cost of accessories.
    # - $p_reconditioning_cost (int): The reconditioning cost.
    # - $p_subtotal (int): The subtotal.
    # - $p_downpayment (int): The downpayment.
    # - $p_outstanding_balance (int): The outstanding balance.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalPricingComputation($p_sales_proposal_id, $p_delivery_price, $p_cost_of_accessories, $p_reconditioning_cost, $p_subtotal, $p_downpayment, $p_outstanding_balance, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalPricingComputation(:p_sales_proposal_id, :p_delivery_price, :p_cost_of_accessories, :p_reconditioning_cost, :p_subtotal, :p_downpayment, :p_outstanding_balance, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_delivery_price', $p_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost_of_accessories', $p_cost_of_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_reconditioning_cost', $p_reconditioning_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_subtotal', $p_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_downpayment', $p_downpayment, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalOtherCharges
    # Description: Inserts the sales proposal other charges.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_insurance_coverage (int): The insurance coverage.
    # - $p_insurance_premium (int): The insurance premium.
    # - $p_handling_fee (int): The handling fee.
    # - $p_transfer_fee (int): The transfer fee.
    # - $p_registration_fee (int): The registration fee.
    # - $p_doc_stamp_tax (int): The doc stamp tax.
    # - $p_transaction_fee (int): The transaction fee.
    # - $p_total_other_charges (int): The total other charges.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalOtherCharges($p_sales_proposal_id, $p_insurance_coverage, $p_insurance_premium, $p_handling_fee, $p_transfer_fee, $p_registration_fee, $p_doc_stamp_tax, $p_transaction_fee, $p_total_other_charges, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalOtherCharges(:p_sales_proposal_id, :p_insurance_coverage, :p_insurance_premium, :p_handling_fee, :p_transfer_fee, :p_registration_fee, :p_doc_stamp_tax, :p_transaction_fee, :p_total_other_charges, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_insurance_coverage', $p_insurance_coverage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium', $p_insurance_premium, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee', $p_handling_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee', $p_transfer_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fee', $p_registration_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax', $p_doc_stamp_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee', $p_transaction_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_other_charges', $p_total_other_charges, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalRenewalAmount
    # Description: Inserts the sales proposal renewal amount.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_registration_second_year (int): The registration second year.
    # - $p_registration_third_year (int): The registration third year.
    # - $p_registration_fourth_year (int): The registration fourth year.
    # - $p_insurance_coverage_second_year (int): The insurance coverage second year.
    # - $p_insurance_coverage_third_year (int): The insurance coverage third year.
    # - $p_insurance_coverage_fourth_year (int): The insurance coverage fourth year.
    # - $p_insurance_premium_second_year (int): The insurance premium second year.
    # - $p_insurance_premium_third_year (int): The insurance premium third year.
    # - $p_insurance_premium_fourth_year (int): The insurance premium fourth year.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalRenewalAmount($p_sales_proposal_id, $p_registration_second_year, $p_registration_third_year, $p_registration_fourth_year, $p_insurance_coverage_second_year, $p_insurance_coverage_third_year, $p_insurance_coverage_fourth_year, $p_insurance_premium_second_year, $p_insurance_premium_third_year, $p_insurance_premium_fourth_year, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalRenewalAmount(:p_sales_proposal_id, :p_registration_second_year, :p_registration_third_year, :p_registration_fourth_year, :p_insurance_coverage_second_year, :p_insurance_coverage_third_year, :p_insurance_coverage_fourth_year, :p_insurance_premium_second_year, :p_insurance_premium_third_year, :p_insurance_premium_fourth_year, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_registration_second_year', $p_registration_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_third_year', $p_registration_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fourth_year', $p_registration_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_second_year', $p_insurance_coverage_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_third_year', $p_insurance_coverage_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_coverage_fourth_year', $p_insurance_coverage_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_second_year', $p_insurance_premium_second_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_third_year', $p_insurance_premium_third_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_fourth_year', $p_insurance_premium_fourth_year, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalDepositAmount
    # Description: Inserts the sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    # - $p_deposit_date (date): The deposit date.
    # - $p_reference_number (string): The reference number.
    # - $p_deposit_amount (double): The particulars.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertSalesProposalDepositAmount($p_sales_proposal_id, $p_deposit_date, $p_reference_number, $p_deposit_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalDepositAmount(:p_sales_proposal_id, :p_deposit_date, :p_reference_number, :p_deposit_amount, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_deposit_date', $p_deposit_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_deposit_amount', $p_deposit_amount, PDO::PARAM_STR);
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
    #
    # Function: checkSalesProposalPricingComputationExist
    # Description: Checks if a sales proposal pricing computation exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalPricingComputationExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalPricingComputationExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalPricingOtherChargesExist
    # Description: Checks if a sales proposal other charges exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalPricingOtherChargesExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalPricingOtherChargesExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalRenewalAmountExist
    # Description: Checks if a sales proposal renewal amount exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalRenewalAmountExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalRenewalAmountExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalDepositAmountExist
    # Description: Checks if a sales proposal deposit amount exists.
    #
    # Parameters:
    # - $p_sales_proposal_deposit_amount_id (int): The sales proposal deposit amount ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalDepositAmountExist($p_sales_proposal_deposit_amount_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalDepositAmountExist(:p_sales_proposal_deposit_amount_id)');
        $stmt->bindValue(':p_sales_proposal_deposit_amount_id', $p_sales_proposal_deposit_amount_id, PDO::PARAM_INT);
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
    #
    # Function: deleteSalesProposalDepositAmount
    # Description: Deletes the sales proposal deposit amount.
    #
    # Parameters:
    # - $p_sales_proposal_deposit_amount_id (int): The sales proposal deposit amount ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalDepositAmount($p_sales_proposal_deposit_amount_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalDepositAmount(:p_sales_proposal_deposit_amount_id)');
        $stmt->bindValue(':p_sales_proposal_deposit_amount_id', $p_sales_proposal_deposit_amount_id, PDO::PARAM_INT);
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
    # Function: getSalesProposalAccessoriesTotal
    # Description: Retrieves the details of a sales proposal accessories.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalAccessoriesTotal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalAccessoriesTotal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
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
    # Function: getSalesProposalJobOrderTotal
    # Description: Retrieves the details of a sales proposal job order.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalJobOrderTotal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalJobOrderTotal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
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
    # Function: getSalesProposalAdditionalJobOrderTotal
    # Description: Retrieves the details of a sales proposal additional job order.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalAdditionalJobOrderTotal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalAdditionalJobOrderTotal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalPricingComputation
    # Description: Retrieves the details of a sales proposal pricing computation.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalPricingComputation($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalPricingComputation(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalOtherCharges
    # Description: Retrieves the details of a sales proposal other charges.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalOtherCharges($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalOtherCharges(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getSalesProposalRenewalAmount
    # Description: Retrieves the details of a sales proposal renewal amount.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalRenewalAmount($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalRenewalAmount(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: getSalesProposalDepositAmount
    # Description: Retrieves the details of a sales proposal deposit amount.
    #
    # Parameters:
    # - $p_sales_proposal_deposit_amount_id (int): The sales proposal deposit amount ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalDepositAmount($p_sales_proposal_deposit_amount_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalDepositAmount(:p_sales_proposal_deposit_amount_id)');
        $stmt->bindValue(':p_sales_proposal_deposit_amount_id', $p_sales_proposal_deposit_amount_id, PDO::PARAM_INT);
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