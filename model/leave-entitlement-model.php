<?php
/**
* Class LeaveEntitlementModel
*
* The LeaveEntitlementModel class handles leave type related operations and interactions.
*/
class LeaveEntitlementModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeaveEntitlement
    # Description: Updates the leave type.
    #
    # Parameters:
    # - $p_leave_entitlement_id (int): The leave type ID.
    # - $p_leave_entitlement_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeaveEntitlement($p_leave_entitlement_id, $p_contact_id, $p_leave_type_id, $p_entitlement_amount, $p_remaining_entitlement, $p_leave_period_start, $p_leave_period_end, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveEntitlement(:p_leave_entitlement_id, :p_contact_id, :p_leave_type_id, :p_entitlement_amount, :p_remaining_entitlement, :p_leave_period_start, :p_leave_period_end, :p_last_log_by)');
        $stmt->bindValue(':p_leave_entitlement_id', $p_leave_entitlement_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_entitlement_amount', $p_entitlement_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_remaining_entitlement', $p_remaining_entitlement, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_period_start', $p_leave_period_start, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_period_end', $p_leave_period_end, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeaveEntitlement
    # Description: Inserts the leave type.
    #
    # Parameters:
    # - $p_leave_entitlement_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeaveEntitlement($p_contact_id, $p_leave_type_id, $p_entitlement_amount, $p_remaining_entitlement, $p_leave_period_start, $p_leave_period_end, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeaveEntitlement(:p_contact_id, :p_leave_type_id, :p_entitlement_amount, :p_remaining_entitlement, :p_leave_period_start, :p_leave_period_end, :p_last_log_by, @p_leave_entitlement_id)');
        $stmt->bindValue(':p_contact_id', $p_contact_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_entitlement_amount', $p_entitlement_amount, PDO::PARAM_STR);
        $stmt->bindValue(':p_remaining_entitlement', $p_remaining_entitlement, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_period_start', $p_leave_period_start, PDO::PARAM_STR);
        $stmt->bindValue(':p_leave_period_end', $p_leave_period_end, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_leave_entitlement_id AS p_leave_entitlement_id");
        $p_leave_entitlement_id = $result->fetch(PDO::FETCH_ASSOC)['p_leave_entitlement_id'];

        return $p_leave_entitlement_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeaveEntitlementExist
    # Description: Checks if a leave type exists.
    #
    # Parameters:
    # - $p_leave_entitlement_id (int): The leave type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeaveEntitlementExist($p_leave_entitlement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeaveEntitlementExist(:p_leave_entitlement_id)');
        $stmt->bindValue(':p_leave_entitlement_id', $p_leave_entitlement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveEntitlement
    # Description: Deletes the leave type.
    #
    # Parameters:
    # - $p_leave_entitlement_id (int): The leave type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeaveEntitlement($p_leave_entitlement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeaveEntitlement(:p_leave_entitlement_id)');
        $stmt->bindValue(':p_leave_entitlement_id', $p_leave_entitlement_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeaveEntitlement
    # Description: Retrieves the details of a leave type.
    #
    # Parameters:
    # - $p_leave_entitlement_id (int): The leave type ID.
    #
    # Returns:
    # - An array containing the leave type details.
    #
    # -------------------------------------------------------------
    public function getLeaveEntitlement($p_leave_entitlement_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeaveEntitlement(:p_leave_entitlement_id)');
        $stmt->bindValue(':p_leave_entitlement_id', $p_leave_entitlement_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>