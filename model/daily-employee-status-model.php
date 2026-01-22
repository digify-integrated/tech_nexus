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
    public function updateDailyEmployeeStatusDetails($p_employee_daily_status_id, $p_is_present, $p_is_late, $p_late_minutes, $p_is_undertime, $p_undertime_minutes, $p_is_on_unpaid_leave, $p_unpaid_leave_minutes, $p_is_on_paid_leave, $p_is_on_official_business, $p_remarks, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('UPDATE employee_daily_status SET is_present = :p_is_present, is_late = :p_is_late, late_minutes = :p_late_minutes, is_undertime = :p_is_undertime, undertime_minutes = :p_undertime_minutes, is_on_unpaid_leave = :p_is_on_unpaid_leave, unpaid_leave_minutes = :p_unpaid_leave_minutes, is_on_paid_leave = :p_is_on_paid_leave, is_on_official_business = :p_is_on_official_business, remarks = :p_remarks, last_log_by = :p_last_log_by WHERE employee_daily_status_id = :p_employee_daily_status_id');
        $stmt->bindValue(':p_employee_daily_status_id', $p_employee_daily_status_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_is_present', $p_is_present, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_late', $p_is_late, PDO::PARAM_STR);
        $stmt->bindValue(':p_late_minutes', $p_late_minutes);
        $stmt->bindValue(':p_is_undertime', $p_is_undertime, PDO::PARAM_STR);
        $stmt->bindValue(':p_undertime_minutes', $p_undertime_minutes);
        $stmt->bindValue(':p_is_on_unpaid_leave', $p_is_on_unpaid_leave, PDO::PARAM_STR);
        $stmt->bindValue(':p_unpaid_leave_minutes', $p_unpaid_leave_minutes);
        $stmt->bindValue(':p_is_on_paid_leave', $p_is_on_paid_leave, PDO::PARAM_STR);
        $stmt->bindValue(':p_is_on_official_business', $p_is_on_official_business, PDO::PARAM_STR);
        $stmt->bindValue(':p_remarks', $p_remarks, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_STR);
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
    public function getEmployeeDailyStatusDetails($p_employee_daily_status_id): mixed {
        $stmt = $this->db->getConnection()->prepare('SELECT * FROM employee_daily_status WHERE employee_daily_status_id = :p_employee_daily_status_id');
        $stmt->bindValue(':p_employee_daily_status_id', $p_employee_daily_status_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------
}
?>