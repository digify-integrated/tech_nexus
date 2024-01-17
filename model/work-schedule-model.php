<?php
/**
* Class WorkScheduleModel
*
* The WorkScheduleModel class handles work schedule related operations and interactions.
*/
class WorkScheduleModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateWorkSchedule
    # Description: Updates the work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_work_schedule_name (string): The work schedule name.
    # - $p_work_schedule_description (string): The work schedule description.
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateWorkSchedule($p_work_schedule_id, $p_work_schedule_name, $p_work_schedule_description, $p_work_schedule_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateWorkSchedule(:p_work_schedule_id, :p_work_schedule_name, :p_work_schedule_description, :p_work_schedule_type_id, :p_last_log_by)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_name', $p_work_schedule_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_schedule_description', $p_work_schedule_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateWorkHours
    # Description: Updates the work schedule.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    # - $p_work_date (date): The work date.
    # - $p_day_of_week (string): The day of the week.
    # - $p_day_period (string): The day period.
    # - $p_start_time (time): The start time.
    # - $p_end_time (time): The end time.
    # - $p_notes (string): The notes.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateWorkHours($p_work_hours_id, $p_work_schedule_id, $p_work_date, $p_day_of_week, $p_day_period, $p_start_time, $p_end_time, $p_notes, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateWorkHours(:p_work_hours_id, :p_work_schedule_id, :p_work_date, :p_day_of_week, :p_day_period, :p_start_time, :p_end_time, :p_notes, :p_last_log_by)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_date', $p_work_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_of_week', $p_day_of_week, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_period', $p_day_period, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_time', $p_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_time', $p_end_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_notes', $p_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertWorkSchedule
    # Description: Inserts the work schedule.
    #
    # Parameters:
    # - $p_work_schedule_name (string): The work schedule name.
    # - $p_work_schedule_description (string): The work schedule description.
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertWorkSchedule($p_work_schedule_name, $p_work_schedule_description, $p_work_schedule_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertWorkSchedule(:p_work_schedule_name, :p_work_schedule_description, :p_work_schedule_type_id, :p_last_log_by, @p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_name', $p_work_schedule_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_schedule_description', $p_work_schedule_description, PDO::PARAM_STR);
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_work_schedule_id AS p_work_schedule_id");
        $p_work_schedule_id = $result->fetch(PDO::FETCH_ASSOC)['p_work_schedule_id'];

        return $p_work_schedule_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertWorkHours
    # Description: Inserts the work schedule.
    #
    # Parameters:
    # - $p_work_date (date): The work date.
    # - $p_day_of_week (string): The day of the week.
    # - $p_day_period (string): The day period.
    # - $p_start_time (time): The start time.
    # - $p_end_time (time): The end time.
    # - $p_notes (string): The notes.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function insertWorkHours($p_work_schedule_id, $p_work_date, $p_day_of_week, $p_day_period, $p_start_time, $p_end_time, $p_notes, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertWorkHours(:p_work_schedule_id, :p_work_date, :p_day_of_week, :p_day_period, :p_start_time, :p_end_time, :p_notes, :p_last_log_by)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_date', $p_work_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_of_week', $p_day_of_week, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_period', $p_day_period, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_time', $p_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_time', $p_end_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_notes', $p_notes, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkWorkScheduleExist
    # Description: Checks if a work schedule exists.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkWorkScheduleExist($p_work_schedule_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkWorkScheduleExist(:p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkWorkHoursExist
    # Description: Checks if a work hours exists.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkWorkHoursExist($p_work_hours_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkWorkHoursExist(:p_work_hours_id)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkFixedWorkHoursOverlap
    # Description: Checks if a fixed work hours overlap.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_day_of_week (string): The day of the week.
    # - $p_day_period (string): The day period.
    # - $p_start_time (time): The start time.
    # - $p_end_time (time): The end time.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkFixedWorkHoursOverlap($p_work_hours_id, $p_work_schedule_id, $p_day_of_week, $p_day_period, $p_start_time, $p_end_time) {
        $stmt = $this->db->getConnection()->prepare('CALL checkFixedWorkHoursOverlap(:p_work_hours_id, :p_work_schedule_id, :p_day_of_week, :p_day_period, :p_start_time, :p_end_time)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_day_of_week', $p_day_of_week, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_period', $p_day_period, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_time', $p_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_time', $p_end_time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkFlexibleWorkHoursOverlap
    # Description: Checks if a flexible work hours overlap.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_work_date (date): The work date.
    # - $p_day_period (string): The day period.
    # - $p_start_time (time): The start time.
    # - $p_end_time (time): The end time.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkFlexibleWorkHoursOverlap($p_work_hours_id, $p_work_schedule_id, $p_work_date, $p_day_period, $p_start_time, $p_end_time) {
        $stmt = $this->db->getConnection()->prepare('CALL checkFlexibleWorkHoursOverlap(:p_work_hours_id, :p_work_schedule_id, :p_work_date, :p_day_period, :p_start_time, :p_end_time)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_date', $p_work_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_day_period', $p_day_period, PDO::PARAM_STR);
        $stmt->bindValue(':p_start_time', $p_start_time, PDO::PARAM_STR);
        $stmt->bindValue(':p_end_time', $p_end_time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkSchedule
    # Description: Deletes the work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteWorkSchedule($p_work_schedule_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteWorkSchedule(:p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLinkedWorkHours
    # Description: Deletes the work hours linked to work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLinkedWorkHours($p_work_schedule_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLinkedWorkHours(:p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkHours
    # Description: Deletes the work hours.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteWorkHours($p_work_hours_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteWorkHours(:p_work_hours_id)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkSchedule
    # Description: Retrieves the details of a work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    #
    # Returns:
    # - An array containing the work schedule details.
    #
    # -------------------------------------------------------------
    public function getWorkSchedule($p_work_schedule_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getWorkSchedule(:p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkHours
    # Description: Retrieves the details of a work hours.
    #
    # Parameters:
    # - $p_work_hours_id (int): The work hours ID.
    #
    # Returns:
    # - An array containing the work hours details.
    #
    # -------------------------------------------------------------
    public function getWorkHours($p_work_hours_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getWorkHours(:p_work_hours_id)');
        $stmt->bindValue(':p_work_hours_id', $p_work_hours_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLinkedWorkHours
    # Description: Retrieves the details of a work hours linked to work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    #
    # Returns:
    # - An array containing the work hours details.
    #
    # -------------------------------------------------------------
    public function getLinkedWorkHours($p_work_schedule_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLinkedWorkHours(:p_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCurrentFixedWorkingHours
    # Description: Retrieves the details of a fixed work hours.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_day_of_week (string): The day of week.
    # - $p_current_time (time): The current time.
    #
    # Returns:
    # - An array containing the work hours details.
    #
    # -------------------------------------------------------------
    public function getCurrentFixedWorkingHours($p_work_schedule_id, $p_day_of_week, $p_current_time) {
        $stmt = $this->db->getConnection()->prepare('CALL getCurrentFixedWorkingHours(:p_work_schedule_id, :p_day_of_week, :p_current_time)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_day_of_week', $p_day_of_week, PDO::PARAM_STR);
        $stmt->bindValue(':p_current_time', $p_current_time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCurrentFlexibleWorkingHours
    # Description: Retrieves the details of a flexible work hours.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_work_date (string): The work date.
    # - $p_current_time (time): The current time.
    #
    # Returns:
    # - An array containing the work hours details.
    #
    # -------------------------------------------------------------
    public function getCurrentFlexibleWorkingHours($p_work_schedule_id, $p_work_date, $p_current_time) {
        $stmt = $this->db->getConnection()->prepare('CALL getCurrentFlexibleWorkingHours(:p_work_schedule_id, :p_work_date, :p_current_time)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_date', $p_work_date, PDO::PARAM_STR);
        $stmt->bindValue(':p_current_time', $p_current_time, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateWorkSchedule
    # Description: Duplicates the work schedule.
    #
    # Parameters:
    # - $p_work_schedule_id (int): The work schedule ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateWorkSchedule($p_work_schedule_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateWorkSchedule(:p_work_schedule_id, :p_last_log_by, @p_new_work_schedule_id)');
        $stmt->bindValue(':p_work_schedule_id', $p_work_schedule_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_work_schedule_id AS work_schedule_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['work_schedule_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWorkScheduleOptions
    # Description: Generates the work schedule options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWorkScheduleOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWorkScheduleOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $workScheduleID = $row['work_schedule_id'];
            $workScheduleName = $row['work_schedule_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($workScheduleID, ENT_QUOTES) . '">' . htmlspecialchars($workScheduleName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>