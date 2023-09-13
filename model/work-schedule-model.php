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