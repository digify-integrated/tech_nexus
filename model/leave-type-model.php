<?php
/**
* Class LeaveTypeModel
*
* The LeaveTypeModel class handles leave type related operations and interactions.
*/
class LeaveTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLeaveType
    # Description: Updates the leave type.
    #
    # Parameters:
    # - $p_leave_type_id (int): The leave type ID.
    # - $p_leave_type_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLeaveType($p_leave_type_id, $p_leave_type_name, $p_is_paid, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLeaveType(:p_leave_type_id, :p_leave_type_name, :p_is_paid, :p_last_log_by)');
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_leave_type_name', $p_leave_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_paid', $p_is_paid, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLeaveType
    # Description: Inserts the leave type.
    #
    # Parameters:
    # - $p_leave_type_name (string): The leave type name.
    # - $p_is_paid (string): The is paid.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLeaveType($p_leave_type_name, $p_is_paid, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLeaveType(:p_leave_type_name, :p_is_paid, :p_last_log_by, @p_leave_type_id)');
        $stmt->bindValue(':p_leave_type_name', $p_leave_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_paid', $p_is_paid, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_leave_type_id AS p_leave_type_id");
        $p_leave_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_leave_type_id'];

        return $p_leave_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLeaveTypeExist
    # Description: Checks if a leave type exists.
    #
    # Parameters:
    # - $p_leave_type_id (int): The leave type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLeaveTypeExist($p_leave_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLeaveTypeExist(:p_leave_type_id)');
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLeaveType
    # Description: Deletes the leave type.
    #
    # Parameters:
    # - $p_leave_type_id (int): The leave type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLeaveType($p_leave_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLeaveType(:p_leave_type_id)');
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLeaveType
    # Description: Retrieves the details of a leave type.
    #
    # Parameters:
    # - $p_leave_type_id (int): The leave type ID.
    #
    # Returns:
    # - An array containing the leave type details.
    #
    # -------------------------------------------------------------
    public function getLeaveType($p_leave_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLeaveType(:p_leave_type_id)');
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateLeaveType
    # Description: Duplicates the leave type.
    #
    # Parameters:
    # - $p_leave_type_id (int): The leave type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateLeaveType($p_leave_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateLeaveType(:p_leave_type_id, :p_last_log_by, @p_new_leave_type_id)');
        $stmt->bindValue(':p_leave_type_id', $p_leave_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_leave_type_id AS leave_type_id");
        $jobPostionID = $result->fetch(PDO::FETCH_ASSOC)['leave_type_id'];

        return $jobPostionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLeaveTypeOptions
    # Description: Generates the leave type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLeaveTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLeaveTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $leaveTypeID = $row['leave_type_id'];
            $leaveTypeName = $row['leave_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($leaveTypeID, ENT_QUOTES) . '">' . htmlspecialchars($leaveTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    
    public function generateLeaveTypeWithoutAWOLOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLeaveTypeWithoutAWOLOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $leaveTypeID = $row['leave_type_id'];
            $leaveTypeName = $row['leave_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($leaveTypeID, ENT_QUOTES) . '">' . htmlspecialchars($leaveTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>