<?php
/**
* Class WorkScheduleTypeModel
*
* The WorkScheduleTypeModel class handles work schedule type related operations and interactions.
*/
class WorkScheduleTypeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateWorkScheduleType
    # Description: Updates the work schedule type.
    #
    # Parameters:
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    # - $p_work_schedule_type_name (string): The work schedule type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateWorkScheduleType($p_work_schedule_type_id, $p_work_schedule_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateWorkScheduleType(:p_work_schedule_type_id, :p_work_schedule_type_name, :p_last_log_by)');
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_schedule_type_name', $p_work_schedule_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertWorkScheduleType
    # Description: Inserts the work schedule type.
    #
    # Parameters:
    # - $p_work_schedule_type_name (string): The work schedule type name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertWorkScheduleType($p_work_schedule_type_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertWorkScheduleType(:p_work_schedule_type_name, :p_last_log_by, @p_work_schedule_type_id)');
        $stmt->bindValue(':p_work_schedule_type_name', $p_work_schedule_type_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_work_schedule_type_id AS p_work_schedule_type_id");
        $p_work_schedule_type_id = $result->fetch(PDO::FETCH_ASSOC)['p_work_schedule_type_id'];

        return $p_work_schedule_type_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkWorkScheduleTypeExist
    # Description: Checks if a work schedule type exists.
    #
    # Parameters:
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkWorkScheduleTypeExist($p_work_schedule_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkWorkScheduleTypeExist(:p_work_schedule_type_id)');
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkScheduleType
    # Description: Deletes the work schedule type.
    #
    # Parameters:
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteWorkScheduleType($p_work_schedule_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteWorkScheduleType(:p_work_schedule_type_id)');
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkScheduleType
    # Description: Retrieves the details of a work schedule type.
    #
    # Parameters:
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    #
    # Returns:
    # - An array containing the work schedule type details.
    #
    # -------------------------------------------------------------
    public function getWorkScheduleType($p_work_schedule_type_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getWorkScheduleType(:p_work_schedule_type_id)');
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateWorkScheduleType
    # Description: Duplicates the work schedule type.
    #
    # Parameters:
    # - $p_work_schedule_type_id (int): The work schedule type ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateWorkScheduleType($p_work_schedule_type_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateWorkScheduleType(:p_work_schedule_type_id, :p_last_log_by, @p_new_work_schedule_type_id)');
        $stmt->bindValue(':p_work_schedule_type_id', $p_work_schedule_type_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_work_schedule_type_id AS work_schedule_type_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['work_schedule_type_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWorkScheduleTypeOptions
    # Description: Generates the work schedule type options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWorkScheduleTypeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWorkScheduleTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $workScheduleTypeID = $row['work_schedule_type_id'];
            $workScheduleTypeName = $row['work_schedule_type_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($workScheduleTypeID, ENT_QUOTES) . '">' . htmlspecialchars($workScheduleTypeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWorkScheduleTypeCheckBox
    # Description: Generates the work schedule type check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWorkScheduleTypeCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWorkScheduleTypeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $workScheduleTypeID = $row['work_schedule_type_id'];
            $workScheduleTypeName = $row['work_schedule_type_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input work-schedule-type-filter" type="checkbox" id="work-schedule-type-' . htmlspecialchars($workScheduleTypeID, ENT_QUOTES) . '" value="' . htmlspecialchars($workScheduleTypeID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="work-schedule-type-' . htmlspecialchars($workScheduleTypeID, ENT_QUOTES) . '">' . htmlspecialchars($workScheduleTypeName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>