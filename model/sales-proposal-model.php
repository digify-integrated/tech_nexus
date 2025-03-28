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
    public function updateSalesProposal($p_sales_proposal_id, $p_customer_id, $p_comaker_id, $p_additional_maker_id, $p_comaker_id2, $p_product_type, $p_transaction_type, $p_financing_institution, $p_referred_by, $p_release_date, $p_start_date, $p_first_due_date, $p_term_length, $p_term_type, $p_number_of_payments, $p_payment_frequency, $p_remarks, $p_initial_approving_officer, $p_final_approving_officer, $p_renewal_tag, $p_application_source_id, $p_commission_amount, $p_company_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposal(:p_sales_proposal_id, :p_customer_id, :p_comaker_id, :p_additional_maker_id, :p_comaker_id2, :p_product_type, :p_transaction_type, :p_financing_institution, :p_referred_by, :p_release_date, :p_start_date, :p_first_due_date, :p_term_length, :p_term_type, :p_number_of_payments, :p_payment_frequency, :p_remarks, :p_initial_approving_officer, :p_final_approving_officer, :p_renewal_tag, :p_application_source_id, :p_commission_amount, :p_company_id, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id', $p_comaker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_additional_maker_id', $p_additional_maker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id2', $p_comaker_id2, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_type', $p_product_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_financing_institution', $p_financing_institution, PDO::PARAM_STR);
        $stmt->bindValue(':p_referred_by', $p_referred_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_date', $p_release_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_due_date', $p_first_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_payments', $p_number_of_payments, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_initial_approving_officer', $p_initial_approving_officer, PDO::PARAM_STR);
        $stmt->bindValue(':p_final_approving_officer', $p_final_approving_officer, PDO::PARAM_STR);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_commission_amount', $p_commission_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalUnit
    # Description: Updates the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalUnit($p_sales_proposal_id, $p_product_id, $p_for_registration, $p_with_cr, $p_for_transfer, $p_for_change_color, $p_new_color, $p_for_change_body, $p_new_body, $p_for_change_engine, $p_new_engine, $p_final_orcr_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalUnit(:p_sales_proposal_id, :p_product_id, :p_for_registration, :p_with_cr, :p_for_transfer, :p_for_change_color, :p_new_color, :p_for_change_body, :p_new_body, :p_for_change_engine, :p_new_engine, :p_final_orcr_name, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_for_registration', $p_for_registration, PDO::PARAM_STR);
        $stmt->bindValue(':p_with_cr', $p_with_cr, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_transfer', $p_for_transfer, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_change_color', $p_for_change_color, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_color', $p_new_color, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_change_body', $p_for_change_body, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_body', $p_new_body, PDO::PARAM_STR);
        $stmt->bindValue(':p_for_change_engine', $p_for_change_engine, PDO::PARAM_STR);
        $stmt->bindValue(':p_new_engine', $p_new_engine, PDO::PARAM_STR);
        $stmt->bindValue(':p_final_orcr_name', $p_final_orcr_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalFuel
    # Description: Updates the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalFuel($p_sales_proposal_id, $p_diesel_fuel_quantity, $p_diesel_price_per_liter, $p_regular_fuel_quantity, $p_regular_price_per_liter, $p_premium_fuel_quantity, $p_premium_price_per_liter, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalFuel(:p_sales_proposal_id, :p_diesel_fuel_quantity, :p_diesel_price_per_liter, :p_regular_fuel_quantity, :p_regular_price_per_liter, :p_premium_fuel_quantity, :p_premium_price_per_liter, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_diesel_fuel_quantity', $p_diesel_fuel_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_diesel_price_per_liter', $p_diesel_price_per_liter, PDO::PARAM_STR);
        $stmt->bindValue(':p_regular_fuel_quantity', $p_regular_fuel_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_regular_price_per_liter', $p_regular_price_per_liter, PDO::PARAM_STR);
        $stmt->bindValue(':p_premium_fuel_quantity', $p_premium_fuel_quantity, PDO::PARAM_STR);
        $stmt->bindValue(':p_premium_price_per_liter', $p_premium_price_per_liter, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalRefinancing
    # Description: Updates the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalRefinancing($p_sales_proposal_id, $p_ref_stock_no, $p_ref_engine_no, $p_ref_chassis_no, $p_ref_plate_no, $p_orcr_no, $p_orcr_date, $p_orcr_expiry_date, $p_received_from, $p_received_from_address, $p_received_from_id_type, $p_received_from_id_number, $p_unit_description, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalRefinancing(:p_sales_proposal_id, :p_ref_stock_no, :p_ref_engine_no, :p_ref_chassis_no, :p_ref_plate_no, :p_orcr_no, :p_orcr_date, :p_orcr_expiry_date, :p_received_from, :p_received_from_address, :p_received_from_id_type, :p_received_from_id_number, :p_unit_description, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ref_stock_no', $p_ref_stock_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_ref_engine_no', $p_ref_engine_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_ref_chassis_no', $p_ref_chassis_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_ref_plate_no', $p_ref_plate_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_no', $p_orcr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_date', $p_orcr_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_orcr_expiry_date', $p_orcr_expiry_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from', $p_received_from, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_address', $p_received_from_address, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_type', $p_received_from_id_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_received_from_id_number', $p_received_from_id_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_unit_description', $p_unit_description, PDO::PARAM_STR);
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
    # Function: updateSalesProposalStatus
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
    public function updateSalesProposalStatus($p_sales_proposal_id, $p_changed_by, $p_sales_proposal_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalStatus(:p_sales_proposal_id, :p_changed_by, :p_sales_proposal_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_changed_by', $p_changed_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_status', $p_sales_proposal_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalCIStatus
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
    public function updateSalesProposalCIStatus($p_sales_proposal_id, $p_ci_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalCIStatus(:p_sales_proposal_id, :p_ci_status, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_ci_status', $p_ci_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateSalesProposalJobOrderProgress($p_sales_proposal_job_order_id, $p_cost, $p_progress, $p_contractor_id, $p_work_center_id, $p_backjob, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalJobOrderProgress(:p_sales_proposal_job_order_id, :p_cost, :p_progress, :p_contractor_id, :p_work_center_id, :p_backjob, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_job_order_id', $p_sales_proposal_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_backjob', $p_backjob, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function updateSalesProposalAdditionalJobOrderProgress($sales_proposal_additional_job_order_id, $p_cost, $p_progress, $p_contractor_id, $p_work_center_id, $p_backjob, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalAdditionalJobOrderProgress(:sales_proposal_additional_job_order_id, :p_cost, :p_progress, :p_contractor_id, :p_work_center_id, :p_backjob, :p_last_log_by)');
        $stmt->bindValue(':sales_proposal_additional_job_order_id', $sales_proposal_additional_job_order_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cost', $p_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_progress', $p_progress, PDO::PARAM_STR);
        $stmt->bindValue(':p_contractor_id', $p_contractor_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_backjob', $p_backjob, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalClientConfirmation
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalClientConfirmation($p_sales_proposal_id, $p_client_confirmation, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalClientConfirmation(:p_sales_proposal_id, :p_client_confirmation, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_client_confirmation', $p_client_confirmation, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalQualityControlForm
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalQualityControlForm($p_sales_proposal_id, $p_quality_control_form, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalQualityControlForm(:p_sales_proposal_id, :p_quality_control_form, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_quality_control_form', $p_quality_control_form, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalSetToDraft
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalSetToDraft($p_sales_proposal_id, $p_sales_proposal_form, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalSetToDraft(:p_sales_proposal_id, :p_sales_proposal_form, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_proposal_form', $p_sales_proposal_form, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    public function updateSalesProposalOtherDocument($p_sales_proposal_id, $p_other_document_file, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalOtherDocument(:p_sales_proposal_id, :p_other_document_file, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_other_document_file', $p_other_document_file, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalOutgoingChecklist
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalOutgoingChecklist($p_sales_proposal_id, $p_outgoing_checklist, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalOutgoingChecklist(:p_sales_proposal_id, :p_outgoing_checklist, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_outgoing_checklist', $p_outgoing_checklist, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalUnitImage
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalUnitImage($p_sales_proposal_id, $p_unit_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalUnitImage(:p_sales_proposal_id, :p_unit_image, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_unit_image', $p_unit_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalChangeRequestStatus
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalChangeRequestStatus($p_sales_proposal_id, $p_change_request_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalChangeRequestStatus(:p_sales_proposal_id, :p_change_request_status, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_change_request_status', $p_change_request_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalAdditionalJobOrderConfirmationImage
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_client_confirmation (string): The sales proposal client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalAdditionalJobOrderConfirmationImage($p_sales_proposal_id, $p_additional_job_order_confirmation, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalAdditionalJobOrderConfirmationImage(:p_sales_proposal_id, :p_additional_job_order_confirmation, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_additional_job_order_confirmation', $p_additional_job_order_confirmation, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalCreditAdvice
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_credit_advice (string): The sales proposal credit advice image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalCreditAdvice($p_sales_proposal_id, $p_credit_advice, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalCreditAdvice(:p_sales_proposal_id, :p_credit_advice, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_credit_advice', $p_credit_advice, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalStencil
    # Description: Updates the sales proposal client confirmation.
    #
    # Parameters:
    # - $p_sales_proposal_accessories_id (int): The sales proposal accessories ID.
    # - $p_new_engine_stencil (string): The sales proposal credit advice image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateSalesProposalStencil($p_sales_proposal_id, $p_new_engine_stencil, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalStencil(:p_sales_proposal_id, :p_new_engine_stencil, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_new_engine_stencil', $p_new_engine_stencil, PDO::PARAM_STR);
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
    public function updateSalesProposalPricingComputation($p_sales_proposal_id, $p_delivery_price, $p_cost_of_accessories, $p_reconditioning_cost, $p_subtotal, $p_downpayment, $p_outstanding_balance, $p_amount_financed, $p_pn_amount, $p_repayment_amount, $p_interest_rate, $p_nominal_discount, $total_delivery_price, $p_add_on_charge, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalPricingComputation(:p_sales_proposal_id, :p_delivery_price, :p_cost_of_accessories, :p_reconditioning_cost, :p_subtotal, :p_downpayment, :p_outstanding_balance, :p_amount_financed, :p_pn_amount, :p_repayment_amount, :p_interest_rate, :p_nominal_discount, :total_delivery_price, :p_add_on_charge, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_delivery_price', $p_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost_of_accessories', $p_cost_of_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_reconditioning_cost', $p_reconditioning_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_subtotal', $p_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_downpayment', $p_downpayment, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_amount_financed', $p_amount_financed, PDO::PARAM_STR);
        $stmt->bindValue(':p_pn_amount', $p_pn_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_repayment_amount', $p_repayment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_interest_rate', $p_interest_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_nominal_discount', $p_nominal_discount, PDO::PARAM_STR);
        $stmt->bindValue(':total_delivery_price', $total_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_add_on_charge', $p_add_on_charge, PDO::PARAM_STR);
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
    public function updateSalesProposalOtherCharges($p_sales_proposal_id, $p_insurance_coverage, $p_insurance_premium, $p_handling_fee, $p_transfer_fee, $p_registration_fee, $p_doc_stamp_tax, $p_transaction_fee, $p_total_other_charges, $p_insurance_premium_discount, $p_insurance_premium_subtotal, $p_handling_fee_discount, $p_handling_fee_subtotal, $p_transfer_fee_discount, $p_transfer_fee_subtotal, $p_doc_stamp_tax_discount, $p_doc_stamp_tax_subtotal, $p_transaction_fee_discount, $p_transaction_fee_subtotal, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalOtherCharges(:p_sales_proposal_id, :p_insurance_coverage, :p_insurance_premium, :p_handling_fee, :p_transfer_fee, :p_registration_fee, :p_doc_stamp_tax, :p_transaction_fee, :p_total_other_charges, :p_insurance_premium_discount, :p_insurance_premium_subtotal, :p_handling_fee_discount, :p_handling_fee_subtotal, :p_transfer_fee_discount, :p_transfer_fee_subtotal, :p_doc_stamp_tax_discount, :p_doc_stamp_tax_subtotal, :p_transaction_fee_discount, :p_transaction_fee_subtotal, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_insurance_coverage', $p_insurance_coverage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium', $p_insurance_premium, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee', $p_handling_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee', $p_transfer_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fee', $p_registration_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax', $p_doc_stamp_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee', $p_transaction_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_other_charges', $p_total_other_charges, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_discount', $p_insurance_premium_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_subtotal', $p_insurance_premium_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee_discount', $p_handling_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee_subtotal', $p_handling_fee_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee_discount', $p_transfer_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee_subtotal', $p_transfer_fee_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax_discount', $p_doc_stamp_tax_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax_subtotal', $p_doc_stamp_tax_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee_discount', $p_transaction_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee_subtotal', $p_transaction_fee_subtotal, PDO::PARAM_STR);
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
    #
    # Function: updateSalesProposalOtherProductDetails
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
    public function updateSalesProposalOtherProductDetails($p_sales_proposal_id, $p_year_model, $p_cr_no, $p_mv_file_no, $p_make, $p_product_description, $p_business_style, $p_si, $p_di, $p_invoice_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalOtherProductDetails(:p_sales_proposal_id, :p_year_model, :p_cr_no, :p_mv_file_no, :p_make, :p_product_description, :p_business_style, :p_si, :p_di, :p_invoice_number, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_cr_no', $p_cr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_mv_file_no', $p_mv_file_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_make', $p_make, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_description', $p_product_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_business_style', $p_business_style, PDO::PARAM_STR);
        $stmt->bindValue(':p_si', $p_si, PDO::PARAM_STR);
        $stmt->bindValue(':p_di', $p_di, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_number', $p_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalActualStartDate
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
    public function updateSalesProposalActualStartDate($p_sales_proposal_id, $p_dr_number, $p_release_to, $p_actual_start_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalActualStartDate(:p_sales_proposal_id, :p_dr_number, :p_release_to, :p_actual_start_date, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_dr_number', $p_dr_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_to', $p_release_to, PDO::PARAM_STR);
        $stmt->bindValue(':p_actual_start_date', $p_actual_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateSalesProposalAsReleased
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
    public function updateSalesProposalAsReleased($p_sales_proposal_id, $p_loan_number, $p_sales_proposal_status, $p_release_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesProposalAsReleased(:p_sales_proposal_id, :p_loan_number, :p_sales_proposal_status, :p_release_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_sales_proposal_status', $p_sales_proposal_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_remarks', $p_release_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateSaleProposalValues
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
    public function updateSaleProposalValues($p_sales_proposal_id, $p_term_length, $p_add_on_charge, $p_nominal_discount, $p_interest_rate, $p_downpayment, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSaleProposalValues(:p_sales_proposal_id, :p_term_length, :p_add_on_charge, :p_nominal_discount, :p_interest_rate, :p_downpayment, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_INT);
        $stmt->bindValue(':p_add_on_charge', $p_add_on_charge, PDO::PARAM_STR);
        $stmt->bindValue(':p_nominal_discount', $p_nominal_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_interest_rate', $p_interest_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_downpayment', $p_downpayment, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateSalesInstallmentStatus
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
    public function updateSalesInstallmentStatus($p_sales_proposal_id, $p_sales_installment_status, $p_installment_sales_approval_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateSalesInstallmentStatus(:p_sales_proposal_id, :p_sales_installment_status, :p_installment_sales_approval_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_sales_installment_status', $p_sales_installment_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_installment_sales_approval_remarks', $p_installment_sales_approval_remarks, PDO::PARAM_STR);
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
    public function insertSalesProposal($p_sales_proposal_number, $p_customer_id, $p_comaker_id, $p_additional_maker_id, $p_comaker_id2, $p_product_type, $p_transaction_type, $p_financing_institution, $p_referred_by, $p_release_date, $p_start_date, $p_first_due_date, $p_term_length, $p_term_type, $p_number_of_payments, $p_payment_frequency, $p_remarks, $p_created_by, $p_initial_approving_officer, $p_final_approving_officer, $p_renewal_tag, $p_application_source_id, $p_commission_amount, $p_company_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposal(:p_sales_proposal_number, :p_customer_id, :p_comaker_id, :p_additional_maker_id, :p_comaker_id2, :p_product_type, :p_transaction_type, :p_financing_institution, :p_referred_by, :p_release_date, :p_start_date, :p_first_due_date, :p_term_length, :p_term_type, :p_number_of_payments, :p_payment_frequency, :p_remarks, :p_created_by, :p_initial_approving_officer, :p_final_approving_officer, :p_renewal_tag, :p_application_source_id, :p_commission_amount, :p_company_id, :p_last_log_by, @p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_number', $p_sales_proposal_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id', $p_comaker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_additional_maker_id', $p_additional_maker_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_comaker_id2', $p_comaker_id2, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_type', $p_product_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_financing_institution', $p_financing_institution, PDO::PARAM_STR);
        $stmt->bindValue(':p_referred_by', $p_referred_by, PDO::PARAM_STR);
        $stmt->bindValue(':p_release_date', $p_release_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_first_due_date', $p_first_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_payments', $p_number_of_payments, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_created_by', $p_created_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_initial_approving_officer', $p_initial_approving_officer, PDO::PARAM_INT);
        $stmt->bindValue(':p_final_approving_officer', $p_final_approving_officer, PDO::PARAM_INT);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
        $stmt->bindValue(':p_application_source_id', $p_application_source_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_commission_amount', $p_commission_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
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
    public function create_journal_entry($p_loan_number, $p_company_id, $p_transaction_type, $p_product_type, $p_product_type_code, $p_sales_proposal_id, $p_product_id, $p_journal_entry_date, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL create_journal_entry(:p_loan_number, :p_company_id, :p_transaction_type, :p_product_type, :p_product_type_code, :p_sales_proposal_id, :p_product_id, :p_journal_entry_date, :p_last_log_by)');
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transaction_type', $p_transaction_type, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_type', $p_product_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_type_code', $p_product_type_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_product_id', $p_product_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_journal_entry_date', $p_journal_entry_date, PDO::PARAM_STR);
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
    public function insertSalesProposalPricingComputation($p_sales_proposal_id, $p_delivery_price, $p_cost_of_accessories, $p_reconditioning_cost, $p_subtotal, $p_downpayment, $p_outstanding_balance, $p_amount_financed, $p_pn_amount, $p_repayment_amount, $p_interest_rate, $p_nominal_discount, $total_delivery_price, $p_add_on_charge, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalPricingComputation(:p_sales_proposal_id, :p_delivery_price, :p_cost_of_accessories, :p_reconditioning_cost, :p_subtotal, :p_downpayment, :p_outstanding_balance, :p_amount_financed, :p_pn_amount, :p_repayment_amount, :p_interest_rate, :p_nominal_discount, :total_delivery_price, :p_add_on_charge, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_delivery_price', $p_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_cost_of_accessories', $p_cost_of_accessories, PDO::PARAM_STR);
        $stmt->bindValue(':p_reconditioning_cost', $p_reconditioning_cost, PDO::PARAM_STR);
        $stmt->bindValue(':p_subtotal', $p_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_downpayment', $p_downpayment, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_amount_financed', $p_amount_financed, PDO::PARAM_STR);
        $stmt->bindValue(':p_pn_amount', $p_pn_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_repayment_amount', $p_repayment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_interest_rate', $p_interest_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_nominal_discount', $p_nominal_discount, PDO::PARAM_STR);
        $stmt->bindValue(':total_delivery_price', $total_delivery_price, PDO::PARAM_STR);
        $stmt->bindValue(':p_add_on_charge', $p_add_on_charge, PDO::PARAM_STR);
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
    public function insertSalesProposalOtherCharges($p_sales_proposal_id, $p_insurance_coverage, $p_insurance_premium, $p_handling_fee, $p_transfer_fee, $p_registration_fee, $p_doc_stamp_tax, $p_transaction_fee, $p_total_other_charges, $p_insurance_premium_discount, $p_insurance_premium_subtotal, $p_handling_fee_discount, $p_handling_fee_subtotal, $p_transfer_fee_discount, $p_transfer_fee_subtotal, $p_doc_stamp_tax_discount, $p_doc_stamp_tax_subtotal, $p_transaction_fee_discount, $p_transaction_fee_subtotal, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalOtherCharges(:p_sales_proposal_id, :p_insurance_coverage, :p_insurance_premium, :p_handling_fee, :p_transfer_fee, :p_registration_fee, :p_doc_stamp_tax, :p_transaction_fee, :p_total_other_charges, :p_insurance_premium_discount, :p_insurance_premium_subtotal, :p_handling_fee_discount, :p_handling_fee_subtotal, :p_transfer_fee_discount, :p_transfer_fee_subtotal, :p_doc_stamp_tax_discount, :p_doc_stamp_tax_subtotal, :p_transaction_fee_discount, :p_transaction_fee_subtotal, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_insurance_coverage', $p_insurance_coverage, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium', $p_insurance_premium, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee', $p_handling_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee', $p_transfer_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_registration_fee', $p_registration_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax', $p_doc_stamp_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee', $p_transaction_fee, PDO::PARAM_STR);
        $stmt->bindValue(':p_total_other_charges', $p_total_other_charges, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_discount', $p_insurance_premium_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_insurance_premium_subtotal', $p_insurance_premium_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee_discount', $p_handling_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_handling_fee_subtotal', $p_handling_fee_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee_discount', $p_transfer_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_transfer_fee_subtotal', $p_transfer_fee_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax_discount', $p_doc_stamp_tax_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_doc_stamp_tax_subtotal', $p_doc_stamp_tax_subtotal, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee_discount', $p_transaction_fee_discount, PDO::PARAM_STR);
        $stmt->bindValue(':p_transaction_fee_subtotal', $p_transaction_fee_subtotal, PDO::PARAM_STR);
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
    #
    # Function: insertSalesProposalOtherProductDetails
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
    public function insertSalesProposalOtherProductDetails($p_sales_proposal_id, $p_year_model, $p_cr_no, $p_mv_file_no, $p_make, $p_product_description, $p_business_style, $p_si, $p_di, $p_invoice_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalOtherProductDetails(:p_sales_proposal_id, :p_year_model, :p_cr_no, :p_mv_file_no, :p_make, :p_product_description, :p_business_style, :p_si, :p_di, :p_invoice_number, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_year_model', $p_year_model, PDO::PARAM_STR);
        $stmt->bindValue(':p_cr_no', $p_cr_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_mv_file_no', $p_mv_file_no, PDO::PARAM_STR);
        $stmt->bindValue(':p_make', $p_make, PDO::PARAM_STR);
        $stmt->bindValue(':p_product_description', $p_product_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_business_style', $p_business_style, PDO::PARAM_STR);
        $stmt->bindValue(':p_si', $p_si, PDO::PARAM_STR);
        $stmt->bindValue(':p_di', $p_di, PDO::PARAM_STR);
        $stmt->bindValue(':p_invoice_number', $p_invoice_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalManualPDCInput
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
    public function insertSalesProposalManualPDCInput($p_sales_proposal_id, $p_account_number, $p_bank_branch, $p_check_date, $p_check_number, $p_payment_for, $p_gross_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalManualPDCInput(:p_sales_proposal_id, :p_account_number, :p_bank_branch, :p_check_date, :p_check_number, :p_payment_for, :p_gross_amount, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_account_number', $p_account_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_for', $p_payment_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_gross_amount', $p_gross_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertSalesProposalRepayment
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
    public function insertSalesProposalRepayment($p_sales_proposal_id, $p_loan_number, $p_reference, $p_due_date, $p_due_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertSalesProposalRepayment(:p_sales_proposal_id, :p_loan_number, :p_reference, :p_due_date, :p_due_amount, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference', $p_reference, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_date', $p_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_amount', $p_due_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPDCCollection
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
    public function insertPDCCollection($p_sales_proposal_id, $p_loan_number, $p_customer_id, $p_payment_amount, $p_check_number, $p_check_date, $p_bank_branch, $p_account_number, $p_company_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPDCCollection(:p_sales_proposal_id, :p_loan_number, :p_customer_id, :p_payment_amount, :p_check_number, :p_check_date, :p_bank_branch, :p_account_number, :p_company_id, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_number', $p_check_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_check_date', $p_check_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_bank_branch', $p_bank_branch, PDO::PARAM_STR);
        $stmt->bindValue(':p_account_number', $p_account_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertPDCManualInputCollection
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
    public function insertPDCManualInputCollection($p_sales_proposal_id, $p_loan_number, $p_customer_id, $p_company_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertPDCManualInputCollection(:p_sales_proposal_id, :p_loan_number, :p_customer_id, :p_company_id, :p_last_log_by)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_loan_number', $p_loan_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_customer_id', $p_customer_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_company_id', $p_company_id, PDO::PARAM_INT);
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

    public function getJobOrderBackjobCount($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobOrderBackjobCount(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAdditionalJobOrderBackjobCount($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAdditionalJobOrderBackjobCount(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    public function countSalesProposalOtherChargesExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL countSalesProposalOtherChargesExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalRepaymentExist
    # Description: Checks if a sales proposal exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalRepaymentExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalRepaymentExist(:p_sales_proposal_id)');
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
    #
    # Function: checkSalesProposalOtherProductDetailsExist
    # Description: Checks if a sales proposal renewal amount exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalOtherProductDetailsExist($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalOtherProductDetailsExist(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkSalesProposalManualPDCInputExist
    # Description: Checks if a sales proposal renewal amount exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkSalesProposalManualPDCInputExist($p_manual_pdc_input_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkSalesProposalManualPDCInputExist(:p_manual_pdc_input_id)');
        $stmt->bindValue(':p_manual_pdc_input_id', $p_manual_pdc_input_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: countSalesProposalAdditionalJobOrder
    # Description: Checks if a sales proposal renewal amount exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function countSalesProposalAdditionalJobOrder($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL countSalesProposalAdditionalJobOrder(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
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
    # Function: deleteSalesProposalRepayment
    # Description: Deletes the sales proposal.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalRepayment($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalRepayment(:p_sales_proposal_id)');
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
    #
    # Function: deleteSalesProposalManualPDCInput
    # Description: Deletes the sales proposal deposit amount.
    #
    # Parameters:
    # - $p_sales_proposal_deposit_amount_id (int): The sales proposal deposit amount ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteSalesProposalManualPDCInput($p_manual_pdc_input_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteSalesProposalManualPDCInput(:p_manual_pdc_input_id)');
        $stmt->bindValue(':p_manual_pdc_input_id', $p_manual_pdc_input_id, PDO::PARAM_INT);
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

    public function getJobOrderMonitoringTotalProgress($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getJobOrderMonitoringTotalProgress(:p_sales_proposal_id)');
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
    public function getSalesProposalAmountOfDepositTotal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalAmountOfDepositTotal(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getPDCManualInputOtherChargesTotal($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getPDCManualInputOtherChargesTotal(:p_sales_proposal_id)');
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
    # Function: getSalesProposalOtherProductDetails
    # Description: Retrieves the details of a sales proposal renewal amount.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns:
    # - An array containing the sales proposal details.
    #
    # -------------------------------------------------------------
    public function getSalesProposalOtherProductDetails($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalOtherProductDetails(:p_sales_proposal_id)');
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
            'For Initial Approval' => 'info',
            'For DR' => 'info',
            'For Final Approval' => 'success',
            'Released' => 'success',
            'On-Process' => 'info',
            'For CI' => 'info',
            'Ready For Release ' => 'warning',
            'Cancelled' => 'warning',
            'Rejected' => 'danger',
            'Proceed' => 'success'
        ];
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_sales_proposal_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_sales_proposal_status . '</span>';
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateForDrSalesProposalOptions
    # Description: Generates the sales proposal tagged for DR.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateForDrSalesProposalOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateForDrSalesProposalOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $salesProposalID = $row['sales_proposal_id'];
            $salesProposalNumber = $row['sales_proposal_number'];
            $fileAs = strtoupper($row['file_as']);

            $htmlOptions .= '<option value="' . htmlspecialchars($salesProposalID, ENT_QUOTES) . '">' . htmlspecialchars($salesProposalNumber, ENT_QUOTES) . ' - '. htmlspecialchars($fileAs, ENT_QUOTES)  .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

     # -------------------------------------------------------------
    #
    # Function: generateLoanAccountOptions
    # Description: Generates the sales proposal tagged for DR.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLoanAccountOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLoanAccountOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $salesProposalID = $row['sales_proposal_id'];
            $loanNumber = $row['loan_number'];
            $fileAs = strtoupper($row['file_as']);

            $htmlOptions .= '<option value="' . htmlspecialchars($salesProposalID, ENT_QUOTES) . '">' . htmlspecialchars($loanNumber, ENT_QUOTES) . ' - '. htmlspecialchars($fileAs, ENT_QUOTES)  .'</option>';
        }

        return $htmlOptions;
    }

    public function getSalesProposalOtherChargesPDCManualInputDetails($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalOtherChargesPDCManualInputDetails(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $formattedEntries = [];
    
        foreach ($options as $row) {
            $check_date = $row['check_date'];
            $formattedDate = date('j F Y', strtotime($check_date)); // Example: 24 June 2025
            $gross_amount = $row['gross_amount'];
    
            $formattedEntries[] = 'Php ' . number_format($gross_amount, 2) . ' payable on ' . $formattedDate;
        }
    
        // Format the output with commas and "and" before the last item
        if (count($formattedEntries) > 1) {
            $lastEntry = array_pop($formattedEntries);
            return implode(', ', $formattedEntries) . ' and ' . $lastEntry;
        }
    
        return $formattedEntries[0] ?? ''; // Return the single entry or empty string if no data
    }

    public function getSalesProposalRenewalPDCManualInputDetails($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalRenewalPDCManualInputDetails(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $formattedEntries = [];
    
        foreach ($options as $row) {
            $check_date = $row['check_date'];
            $formattedDate = date('j F Y', strtotime($check_date)); // Example: 24 June 2025
            $gross_amount = $row['gross_amount'];
    
            $formattedEntries[] = 'Php ' . number_format($gross_amount, 2) . ' payable on ' . $formattedDate;
        }
    
        // Format the output with commas and "and" before the last item
        if (count($formattedEntries) > 1) {
            $lastEntry = array_pop($formattedEntries);
            return implode(', ', $formattedEntries) . ' and ' . $lastEntry;
        }
    
        return $formattedEntries[0] ?? ''; // Return the single entry or empty string if no data
    }

    public function getSalesProposalRegistrationRenewalPDCManualInputDetails($p_sales_proposal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getSalesProposalRegistrationRenewalPDCManualInputDetails(:p_sales_proposal_id)');
        $stmt->bindValue(':p_sales_proposal_id', $p_sales_proposal_id, PDO::PARAM_INT);
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        $formattedEntries = [];
    
        foreach ($options as $row) {
            $check_date = $row['check_date'];
            $formattedDate = date('j F Y', strtotime($check_date)); // Example: 24 June 2025
            $gross_amount = $row['gross_amount'];
    
            $formattedEntries[] = 'Php ' . number_format($gross_amount, 2) . ' payable on ' . $formattedDate;
        }
    
        // Format the output with commas and "and" before the last item
        if (count($formattedEntries) > 1) {
            $lastEntry = array_pop($formattedEntries);
            return implode(', ', $formattedEntries) . ' and ' . $lastEntry;
        }
    
        return $formattedEntries[0] ?? ''; // Return the single entry or empty string if no data
    }
    
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLoanCollectionsOptions
    # Description: Generates the sales proposal tagged for DR.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLoanCollectionsOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLoanCollectionsOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $salesProposalID = $row['sales_proposal_id'];
            $loanNumber = $row['loan_number'];
            $fileAs = strtoupper($row['file_as']);
            $stockNumber = strtoupper($row['stock_number']);

            $htmlOptions .= '<option value="' . htmlspecialchars($salesProposalID, ENT_QUOTES) . '">' . htmlspecialchars($loanNumber, ENT_QUOTES) . ' - ' . $stockNumber . ' - '. htmlspecialchars($fileAs, ENT_QUOTES)  .'</option>';
        }

        return $htmlOptions;
    }
    public function generateJobOrderBackjobOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateJobOrderBackjobOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $salesProposalID = $row['sales_proposal_id'];
            $sales_proposal_number = $row['sales_proposal_number'];
            $fileAs = strtoupper($row['file_as']);
            $stockNumber = strtoupper($row['stock_number']);

            $htmlOptions .= '<option value="' . htmlspecialchars($salesProposalID, ENT_QUOTES) . '">' . htmlspecialchars($sales_proposal_number, ENT_QUOTES) . ' - ' . $stockNumber . ' - '. htmlspecialchars($fileAs, ENT_QUOTES)  .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    

    # -------------------------------------------------------------
    #   CRON methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: cronSalesProposalCancelDraft
    # Description: Checks if a sales proposal exists.
    #
    # Parameters:
    # - $p_sales_proposal_id (int): The sales proposal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function cronSalesProposalCancelDraft($p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL cronSalesProposalCancelDraft(:p_last_log_by)');
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>