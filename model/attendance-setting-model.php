<?php
/**
* Class AttendanceSettingModel
*
* The AttendanceSettingModel class handles attendance setting related operations and interactions.
*/
class AttendanceSettingModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateAttendanceSetting
    # Description: Updates the attendance setting.
    #
    # Parameters:
    # - $p_attendance_setting_id (int): The attendance setting ID.
    # - $p_attendance_setting_name (string): The attendance setting name.
    # - $p_attendance_setting_description (string): The attendance setting description.
    # - $p_value (string): The value of the attendance setting.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateAttendanceSetting($p_attendance_setting_id, $p_attendance_setting_name, $p_attendance_setting_description, $p_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateAttendanceSetting(:p_attendance_setting_id, :p_attendance_setting_name, :p_attendance_setting_description, :p_value, :p_last_log_by)');
        $stmt->bindValue(':p_attendance_setting_id', $p_attendance_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_attendance_setting_name', $p_attendance_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_attendance_setting_description', $p_attendance_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_value', $p_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertAttendanceSetting
    # Description: Inserts the attendance setting.
    #
    # Parameters:
    # - $p_attendance_setting_name (string): The attendance setting name.
    # - $p_attendance_setting_description (string): The attendance setting description.
    # - $p_value (string): The value of the attendance setting.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertAttendanceSetting($p_attendance_setting_name, $p_attendance_setting_description, $p_value, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertAttendanceSetting(:p_attendance_setting_name, :p_attendance_setting_description, :p_value, :p_last_log_by, @p_attendance_setting_id)');
        $stmt->bindValue(':p_attendance_setting_name', $p_attendance_setting_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_attendance_setting_description', $p_attendance_setting_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_value', $p_value, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_attendance_setting_id AS p_attendance_setting_id");
        $p_attendance_setting_id = $result->fetch(PDO::FETCH_ASSOC)['p_attendance_setting_id'];

        return $p_attendance_setting_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkAttendanceSettingExist
    # Description: Checks if a attendance setting exists.
    #
    # Parameters:
    # - $p_attendance_setting_id (int): The attendance setting ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkAttendanceSettingExist($p_attendance_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkAttendanceSettingExist(:p_attendance_setting_id)');
        $stmt->bindValue(':p_attendance_setting_id', $p_attendance_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteAttendanceSetting
    # Description: Deletes the attendance setting.
    #
    # Parameters:
    # - $p_attendance_setting_id (int): The attendance setting ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteAttendanceSetting($p_attendance_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteAttendanceSetting(:p_attendance_setting_id)');
        $stmt->bindValue(':p_attendance_setting_id', $p_attendance_setting_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getAttendanceSetting
    # Description: Retrieves the details of a attendance setting.
    #
    # Parameters:
    # - $p_attendance_setting_id (int): The attendance setting ID.
    #
    # Returns:
    # - An array containing the attendance setting details.
    #
    # -------------------------------------------------------------
    public function getAttendanceSetting($p_attendance_setting_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getAttendanceSetting(:p_attendance_setting_id)');
        $stmt->bindValue(':p_attendance_setting_id', $p_attendance_setting_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateAttendanceSetting
    # Description: Duplicates the attendance setting.
    #
    # Parameters:
    # - $p_attendance_setting_id (int): The attendance setting ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateAttendanceSetting($p_attendance_setting_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateAttendanceSetting(:p_attendance_setting_id, :p_last_log_by, @p_new_attendance_setting_id)');
        $stmt->bindValue(':p_attendance_setting_id', $p_attendance_setting_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_attendance_setting_id AS attendance_setting_id");
        $attendanceSettingID = $result->fetch(PDO::FETCH_ASSOC)['attendance_setting_id'];

        return $attendanceSettingID;
    }
    # -------------------------------------------------------------
}
?>