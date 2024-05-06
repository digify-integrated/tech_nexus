<?php
/**
* Class LeasingApplicationModel
*
* The LeasingApplicationModel class handles leasing application related operations and interactions.
*/
class LeasingApplicationModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeasingApplication
    # Description: Updates the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingApplication($p_leasing_application_id, $p_tenant_id, $p_property_id, $p_term_length, $p_term_type, $p_payment_frequency, $p_vat, $p_withholding_tax, $p_renewal_tag, $p_contract_date, $p_start_date, $p_maturity_date, $p_security_deposit, $p_floor_area, $p_initial_basic_rental, $p_escalation_rate, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplication(:p_leasing_application_id, :p_tenant_id, :p_property_id, :p_term_length, :p_term_type, :p_payment_frequency, :p_vat, :p_withholding_tax, :p_renewal_tag, :p_contract_date, :p_start_date, :p_maturity_date, :p_security_deposit, :p_floor_area, :p_initial_basic_rental, :p_escalation_rate, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_vat', $p_vat, PDO::PARAM_STR);
        $stmt->bindValue(':p_withholding_tax', $p_withholding_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
        $stmt->bindValue(':p_contract_date', $p_contract_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_maturity_date', $p_maturity_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_security_deposit', $p_security_deposit, PDO::PARAM_STR);
        $stmt->bindValue(':p_floor_area', $p_floor_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_initial_basic_rental', $p_initial_basic_rental, PDO::PARAM_STR);
        $stmt->bindValue(':p_escalation_rate', $p_escalation_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeasingApplicationContactImage
    # Description: Updates the leasing application client confirmation.
    #
    # Parameters:
    # - $p_leasing_application_accessories_id (int): The leasing application accessories ID.
    # - $p_client_confirmation (string): The leasing application client confirmation image.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingApplicationContactImage($p_leasing_application_id, $p_contract_image, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplicationContactImage(:p_leasing_application_id, :p_contract_image, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contract_image', $p_contract_image, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateLeasingApplicationStatus
    # Description: Updates the sales proposal accessories.
    #
    # Parameters:
    # - $p_leasing_application_accessories_id (int): The sales proposal accessories ID.
    # - $p_leasing_application_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingApplicationStatus($p_leasing_application_id, $p_changed_by, $p_application_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplicationStatus(:p_leasing_application_id, :p_changed_by, :p_application_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_changed_by', $p_changed_by, PDO::PARAM_INT);
        $stmt->bindValue(':p_application_status', $p_application_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateLeasingOtherChargesStatus
    # Description: Updates the sales proposal accessories.
    #
    # Parameters:
    # - $p_leasing_application_accessories_id (int): The sales proposal accessories ID.
    # - $p_leasing_application_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingOtherChargesStatus() {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingOtherChargesStatus()');
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateLeasingApplicationRepaymentStatus
    # Description: Updates the sales proposal accessories.
    #
    # Parameters:
    # - $p_leasing_application_accessories_id (int): The sales proposal accessories ID.
    # - $p_leasing_application_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingApplicationRepaymentStatus() {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplicationRepaymentStatus()');
        $stmt->execute();
    }
    # -------------------------------------------------------------
    
    # -------------------------------------------------------------
    #
    # Function: updateLeasingApplicationStatusToClosed
    # Description: Updates the sales proposal accessories.
    #
    # Parameters:
    # - $p_leasing_application_accessories_id (int): The sales proposal accessories ID.
    # - $p_leasing_application_id (int): The sales proposal ID.
    # - $p_accessories (string): The accessories.
    # - $p_cost (double): The cost.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeasingApplicationStatusToClosed() {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplicationStatusToClosed()');
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeasingApplication
    # Description: Inserts the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeasingApplication($p_leasing_application_number, $p_tenant_id, $p_property_id, $p_term_length, $p_term_type, $p_payment_frequency, $p_vat, $p_withholding_tax, $p_renewal_tag, $p_contract_date, $p_start_date, $p_maturity_date, $p_security_deposit, $p_floor_area, $p_initial_basic_rental, $p_escalation_rate, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingApplication(:p_leasing_application_number, :p_tenant_id, :p_property_id, :p_term_length, :p_term_type, :p_payment_frequency, :p_vat, :p_withholding_tax, :p_renewal_tag, :p_contract_date, :p_start_date, :p_maturity_date, :p_security_deposit, :p_floor_area, :p_initial_basic_rental, :p_escalation_rate, :p_remarks, :p_last_log_by, @p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_number', $p_leasing_application_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_vat', $p_vat, PDO::PARAM_STR);
        $stmt->bindValue(':p_withholding_tax', $p_withholding_tax, PDO::PARAM_STR);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
        $stmt->bindValue(':p_contract_date', $p_contract_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_date', $p_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_maturity_date', $p_maturity_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_security_deposit', $p_security_deposit, PDO::PARAM_STR);
        $stmt->bindValue(':p_floor_area', $p_floor_area, PDO::PARAM_STR);
        $stmt->bindValue(':p_initial_basic_rental', $p_initial_basic_rental, PDO::PARAM_STR);
        $stmt->bindValue(':p_escalation_rate', $p_escalation_rate, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_leasing_application_id AS p_leasing_application_id");
        $p_leasing_application_id = $result->fetch(PDO::FETCH_ASSOC)['p_leasing_application_id'];

        return $p_leasing_application_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeasingApplicationRepayment
    # Description: Inserts the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeasingApplicationRepayment($p_leasing_application_id, $p_reference, $p_due_date, $p_unpaid_rental, $p_outstanding_balance, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingApplicationRepayment(:p_leasing_application_id, :p_reference, :p_due_date, :p_unpaid_rental, :p_outstanding_balance, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference', $p_reference, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_date', $p_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_unpaid_rental', $p_unpaid_rental, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeasingOtherCharges
    # Description: Inserts the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeasingOtherCharges($p_leasing_application_repayment_id, $p_leasing_application_id, $p_other_charges_type, $p_due_amount, $p_due_paid, $p_due_date, $p_coverage_start_date, $p_coverage_end_date, $p_outstanding_balance, $p_reference_number, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingOtherCharges(:p_leasing_application_repayment_id, :p_leasing_application_id, :p_other_charges_type, :p_due_amount, :p_due_paid, :p_due_date, :p_coverage_start_date, :p_coverage_end_date, :p_outstanding_balance, :p_reference_number, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_other_charges_type', $p_other_charges_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_amount', $p_due_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_paid', $p_due_paid, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_date', $p_due_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_coverage_start_date', $p_coverage_start_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_coverage_end_date', $p_coverage_end_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_outstanding_balance', $p_outstanding_balance, PDO::PARAM_STR);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeasingRentalPayment
    # Description: Inserts the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeasingRentalPayment($p_leasing_application_repayment_id, $p_leasing_application_id, $p_payment_for, $p_payment_id, $p_reference_number, $p_payment_mode, $p_payment_date, $p_payment_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingRentalPayment(:p_leasing_application_repayment_id, :p_leasing_application_id, :p_payment_for, :p_payment_id, :p_reference_number, :p_payment_mode, :p_payment_date, :p_payment_amount, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_for', $p_payment_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_id', $p_payment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_mode', $p_payment_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeasingOtherChargesPayment
    # Description: Inserts the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_name (string): The leasing application name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeasingOtherChargesPayment($p_leasing_application_repayment_id, $p_leasing_application_id, $p_payment_for, $p_payment_id, $p_reference_number, $p_payment_mode, $p_payment_date, $p_payment_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingOtherChargesPayment(:p_leasing_application_repayment_id, :p_leasing_application_id, :p_payment_for, :p_payment_id, :p_reference_number, :p_payment_mode, :p_payment_date, :p_payment_amount, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_for', $p_payment_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_id', $p_payment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_reference_number', $p_reference_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_mode', $p_payment_mode, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_date', $p_payment_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeasingApplicationExist
    # Description: Checks if a leasing application exists.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeasingApplicationExist($p_leasing_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeasingApplicationExist(:p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeasingOtherChargesExist
    # Description: Checks if a leasing application exists.
    #
    # Parameters:
    # - $p_leasing_other_charges_id (int): The leasing application ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeasingOtherChargesExist($p_leasing_other_charges_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeasingOtherChargesExist(:p_leasing_other_charges_id)');
        $stmt->bindValue(':p_leasing_other_charges_id', $p_leasing_other_charges_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeasingCollectionExist
    # Description: Checks if a leasing application exists.
    #
    # Parameters:
    # - $p_leasing_other_charges_id (int): The leasing application ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeasingCollectionExist($p_leasing_collections_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeasingCollectionExist(:p_leasing_collections_id)');
        $stmt->bindValue(':p_leasing_collections_id', $p_leasing_collections_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingApplication
    # Description: Deletes the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeasingApplication($p_leasing_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeasingApplication(:p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingApplicationRepayment
    # Description: Deletes the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeasingApplicationRepayment($p_leasing_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeasingApplicationRepayment(:p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingOtherCharges
    # Description: Deletes the leasing application.
    #
    # Parameters:
    # - $p_leasing_other_charges_id (int): The leasing application ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeasingOtherCharges($p_leasing_other_charges_id, $p_leasing_application_repayment_id, $p_other_charges_type, $p_due_amount, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeasingOtherCharges(:p_leasing_other_charges_id, :p_leasing_application_repayment_id, :p_other_charges_type, :p_due_amount, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_other_charges_id', $p_leasing_other_charges_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_other_charges_type', $p_other_charges_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_due_amount', $p_due_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeasingCollections
    # Description: Deletes the leasing application.
    #
    # Parameters:
    # - $p_leasing_other_charges_id (int): The leasing application ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeasingCollections($p_leasing_collections_id, $p_leasing_application_repayment_id, $p_payment_for, $p_payment_id, $p_payment_amount) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeasingCollections(:p_leasing_collections_id, :p_leasing_application_repayment_id, :p_payment_for, :p_payment_id, :p_payment_amount)');
        $stmt->bindValue(':p_leasing_collections_id', $p_leasing_collections_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_for', $p_payment_for, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_id', $p_payment_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_payment_amount', $p_payment_amount, PDO::PARAM_STR);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingApplication
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingApplication($p_leasing_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingApplication(:p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingCollections
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingCollections($p_leasing_collections_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingCollections(:p_leasing_collections_id)');
        $stmt->bindValue(':p_leasing_collections_id', $p_leasing_collections_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingApplicationRepaymentCount
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationRepaymentCount($p_leasing_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingApplicationRepaymentCount(:p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingApplicationRepayment
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationRepayment($p_leasing_application_repayment_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingApplicationRepayment(:p_leasing_application_repayment_id)');
        $stmt->bindValue(':p_leasing_application_repayment_id', $p_leasing_application_repayment_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingOtherCharges
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingOtherCharges($p_leasing_other_charges_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingOtherCharges(:p_leasing_other_charges_id)');
        $stmt->bindValue(':p_leasing_other_charges_id', $p_leasing_other_charges_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingAplicationRepaymentTotal
    # Description: Retrieves the details of a leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    # - $p_transcation_type (int): The leasing application ID.
    #
    # Returns:
    # - An array containing the leasing application details.
    #
    # -------------------------------------------------------------
    public function getLeasingAplicationRepaymentTotal($p_leasing_application_id, $p_as_of_date, $p_transcation_type) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeasingAplicationRepaymentTotal(:p_leasing_application_id, :p_as_of_date, :p_transcation_type)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_as_of_date', $p_as_of_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_transcation_type', $p_transcation_type, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeasingApplicationStatus
    # Description: Retrieves the leasing application status badge.
    #
    # Parameters:
    # - $p_application_status (string): The leasing application status.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getLeasingApplicationStatus($p_application_status) {
        $statusClasses = [
            'Draft' => 'secondary',
            'For Approval' => 'info',
            'Approved' => 'success',
            'Closed' => 'warning',
            'Active' => 'success'
        ];
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_application_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_application_status . '</span>';
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLoanApplicationRepaymentStatus
    # Description: Retrieves the leasing application status badge.
    #
    # Parameters:
    # - $p_application_status (string): The leasing application status.
    #
    # Returns:
    # - An array containing the product details.
    #
    # -------------------------------------------------------------
    public function getLoanApplicationRepaymentStatus($p_repayment_status) {
        $statusClasses = [
            'Unpaid' => 'danger',
            'Partially Paid' => 'warning',
            'Fully Paid' => 'success'
        ];
        
        $defaultClass = 'dark';
        
        $class = $statusClasses[$p_repayment_status] ?? $defaultClass;
        
        return '<span class="badge bg-' . $class . '">' . $p_repayment_status . '</span>';
    }
    # -------------------------------------------------------------
}
?>