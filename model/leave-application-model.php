<?php
/**
* Class LeaveApplicationModel
*
* The LeaveApplicationModel class handles leave type related operations and interactions.
*/
class LeaveApplicationModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeaveApplication
    # Description: Updates the leave type.
    #
    # Parameters:
    # - $p_leave_application_id (int): The leave type ID.
    # - $p_leave_application_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeaveApplication($p_leave_application_id, $p_contact_id, $p_leave_type_id, $p_application_type, $p_reason, $p_leave_date, $p_leave_start_time, $p_leave_end_time, $p_number_of_hours, $p_creation_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveApplication(:p_leave_application_id, :p_contact_id, :p_leave_type_id, :p_application_type, :p_reason, :p_leave_date, :p_leave_start_time, :p_leave_end_time, :p_number_of_hours, :p_creation_type, :p_last_log_by)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_application_type', $p_application_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_date', $p_leave_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_start_time', $p_leave_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_end_time', $p_leave_end_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_hours', $p_number_of_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_creation_type', $p_creation_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateLeaveEntitlementAmount($p_contact_id, $p_leave_type_id, $p_leave_date, $p_application_amount, $p_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveEntitlementAmount(:p_contact_id, :p_leave_type_id, :p_leave_date, :p_application_amount, :p_type, :p_last_log_by)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_date', $p_leave_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_application_amount', $p_application_amount, PDO::PARAM_INT);
        $stmt->bindValue(':p_type', $p_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function insertLeaveDocument($p_leave_application_id, $p_document_name, $p_document_file_path, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeaveDocument(:p_leave_application_id, :p_document_name, :p_document_file_path, :p_last_log_by)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_name', $p_document_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_document_file_path', $p_document_file_path, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }

    
    public function deleteLeaveDocument($p_leave_document_id) {
        $stmt = $this->db->getConnection()->prepare( 'CALL deleteLeaveDocument(:p_leave_document_id)');
        $stmt->bindValue(':p_leave_document_id', $p_leave_document_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    public function getLeaveConfirmationDocument($p_leave_application_id) {
        $stmt = $this->db->getConnection()->prepare( 'SELECT COUNT(leave_document_id) AS total FROM leave_document WHERE document_name = :document_name AND leave_application_id = :p_leave_application_id');
        $stmt->bindValue(':document_name', 'Leave Confirmation', PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeaveApplicationStatus
    # Description: Updates the leave type.
    #
    # Parameters:
    # - $p_leave_application_id (int): The leave type ID.
    # - $p_leave_application_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeaveApplicationStatus($p_leave_application_id, $p_status, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveApplicationStatus(:p_leave_application_id, :p_status, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateLeaveForm($p_leave_application_id, $p_leave_form, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveForm(:p_leave_application_id, :p_leave_form, :p_last_log_by)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_form', $p_leave_form, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeaveApplication
    # Description: Inserts the leave type.
    #
    # Parameters:
    # - $p_leave_application_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeaveApplication($p_contact_id, $p_leave_type_id, $p_application_type, $p_reason, $p_leave_date, $p_leave_start_time, $p_leave_end_time, $p_number_of_hours, $p_creation_type, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeaveApplication(:p_contact_id, :p_leave_type_id, :p_application_type, :p_reason, :p_leave_date, :p_leave_start_time, :p_leave_end_time, :p_number_of_hours, :p_creation_type, :p_last_log_by, @p_leave_application_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_application_type', $p_application_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_reason', $p_reason, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_date', $p_leave_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_start_time', $p_leave_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_end_time', $p_leave_end_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_number_of_hours', $p_number_of_hours, PDO::PARAM_STR);
        $stmt->bindValue(':p_creation_type', $p_creation_type, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_leave_application_id AS p_leave_application_id");
        $p_leave_application_id = $result->fetch(PDO::FETCH_ASSOC)['p_leave_application_id'];

        return $p_leave_application_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeaveApplicationExist
    # Description: Checks if a leave type exists.
    #
    # Parameters:
    # - $p_leave_application_id (int): The leave type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeaveApplicationExist($p_leave_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeaveApplicationExist(:p_leave_application_id)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveApplication
    # Description: Deletes the leave type.
    #
    # Parameters:
    # - $p_leave_application_id (int): The leave type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeaveApplication($p_leave_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeaveApplication(:p_leave_application_id)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeaveApplication
    # Description: Retrieves the details of a leave type.
    #
    # Parameters:
    # - $p_leave_application_id (int): The leave type ID.
    #
    # Returns:
    # - An array containing the leave type details.
    #
    # -------------------------------------------------------------
    public function getLeaveApplication($p_leave_application_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeaveApplication(:p_leave_application_id)');
        $stmt->bindValue(':p_leave_application_id', $p_leave_application_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getEmployeeLeaveEntitlement($p_contact_id, $p_leave_date) {
        $stmt = $this->db->getConnection()->prepare('CALL getEmployeeLeaveEntitlement(:p_contact_id, :p_leave_date)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_date', $p_leave_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>