<?php
/**
* Class DisbursementModel
*
* The DisbursementModel class handles disbursement related operations and interactions.
*/
class DailyEmployeeStatusModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------
    public function updateDailyEmployeeStatusRemarks($p_employee_daily_status_id, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDailyEmployeeStatusRemarks(:p_employee_daily_status_id, :p_remarks, :p_last_log_by)');
        $stmt->bindValue(':p_employee_daily_status_id', $p_employee_daily_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    public function updateDailyEmployeeStatus($p_employee_daily_status_id, $p_status, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateDailyEmployeeStatus(:p_employee_daily_status_id, :p_status, :p_last_log_by)');
        $stmt->bindValue(':p_employee_daily_status_id', $p_employee_daily_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
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

    public function getDailyEmployeeStatusCount($p_status, $p_attendance_date): mixed {
        $stmt = $this->db->getConnection()->prepare('CALL getDailyEmployeeStatusCount(:p_status, :p_attendance_date)');
        $stmt->bindValue(':p_status', $p_status, PDO::PARAM_STR);
        $stmt->bindValue(':p_attendance_date', $p_attendance_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>