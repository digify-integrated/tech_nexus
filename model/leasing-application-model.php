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
    public function updateLeasingApplication($p_leasing_application_id, $p_tenant_id, $p_property_id, $p_term_length, $p_term_type, $p_payment_frequency, $p_renewal_tag, $p_start_date, $p_maturity_date, $p_security_deposit, $p_floor_area, $p_initial_basic_rental, $p_escalation_rate, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeasingApplication(:p_leasing_application_id, :p_tenant_id, :p_property_id, :p_term_length, :p_term_type, :p_payment_frequency, :p_renewal_tag, :p_start_date, :p_maturity_date, :p_security_deposit, :p_floor_area, :p_initial_basic_rental, :p_escalation_rate, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
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
    public function insertLeasingApplication($p_leasing_application_number, $p_tenant_id, $p_property_id, $p_term_length, $p_term_type, $p_payment_frequency, $p_renewal_tag, $p_start_date, $p_maturity_date, $p_security_deposit, $p_floor_area, $p_initial_basic_rental, $p_escalation_rate, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeasingApplication(:p_leasing_application_number, :p_tenant_id, :p_property_id, :p_term_length, :p_term_type, :p_payment_frequency, :p_renewal_tag, :p_start_date, :p_maturity_date, :p_security_deposit, :p_floor_area, :p_initial_basic_rental, :p_escalation_rate, :p_remarks, :p_last_log_by, @p_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_number', $p_leasing_application_number, PDO::PARAM_STR);
        $stmt->bindValue(':p_tenant_id', $p_tenant_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_property_id', $p_property_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_term_length', $p_term_length, PDO::PARAM_STR);
        $stmt->bindValue(':p_term_type', $p_term_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_payment_frequency', $p_payment_frequency, PDO::PARAM_STR);
        $stmt->bindValue(':p_renewal_tag', $p_renewal_tag, PDO::PARAM_STR);
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
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateLeasingApplication
    # Description: Duplicates the leasing application.
    #
    # Parameters:
    # - $p_leasing_application_id (int): The leasing application ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateLeasingApplication($p_leasing_application_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateLeasingApplication(:p_leasing_application_id, :p_last_log_by, @p_new_leasing_application_id)');
        $stmt->bindValue(':p_leasing_application_id', $p_leasing_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_leasing_application_id AS leasing_application_id");
        $leasingApplicationiD = $result->fetch(PDO::FETCH_ASSOC)['leasing_application_id'];

        return $leasingApplicationiD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLeasingApplicationOptions
    # Description: Generates the leasing application options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLeasingApplicationOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLeasingApplicationOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $leasingApplicationID = $row['leasing_application_id'];
            $leasingApplicationName = $row['leasing_application_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($leasingApplicationID, ENT_QUOTES) . '">' . htmlspecialchars($leasingApplicationName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLeasingApplicationCheckBox
    # Description: Generates the leasing application check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLeasingApplicationCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLeasingApplicationOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $leasingApplicationID = $row['leasing_application_id'];
            $leasingApplicationName = $row['leasing_application_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input body-type-filter" type="checkbox" id="body-type-' . htmlspecialchars($leasingApplicationID, ENT_QUOTES) . '" value="' . htmlspecialchars($leasingApplicationID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="body-type-' . htmlspecialchars($leasingApplicationID, ENT_QUOTES) . '">' . htmlspecialchars($leasingApplicationName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>