<?php
/**
* Class WorkCenterModel
*
* The WorkCenterModel class handles work center related operations and interactions.
*/
class WorkCenterModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateWorkCenter
    # Description: Updates the work center.
    #
    # Parameters:
    # - $p_work_center_id (int): The work center ID.
    # - $p_work_center_name (string): The work center name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateWorkCenter($p_work_center_id, $p_work_center_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateWorkCenter(:p_work_center_id, :p_work_center_name, :p_last_log_by)');
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_work_center_name', $p_work_center_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertWorkCenter
    # Description: Inserts the work center.
    #
    # Parameters:
    # - $p_work_center_name (string): The work center name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertWorkCenter($p_work_center_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertWorkCenter(:p_work_center_name, :p_last_log_by, @p_work_center_id)');
        $stmt->bindValue(':p_work_center_name', $p_work_center_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_work_center_id AS p_work_center_id");
        $p_work_center_id = $result->fetch(PDO::FETCH_ASSOC)['p_work_center_id'];

        return $p_work_center_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkWorkCenterExist
    # Description: Checks if a work center exists.
    #
    # Parameters:
    # - $p_work_center_id (int): The work center ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkWorkCenterExist($p_work_center_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkWorkCenterExist(:p_work_center_id)');
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteWorkCenter
    # Description: Deletes the work center.
    #
    # Parameters:
    # - $p_work_center_id (int): The work center ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteWorkCenter($p_work_center_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteWorkCenter(:p_work_center_id)');
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getWorkCenter
    # Description: Retrieves the details of a work center.
    #
    # Parameters:
    # - $p_work_center_id (int): The work center ID.
    #
    # Returns:
    # - An array containing the work center details.
    #
    # -------------------------------------------------------------
    public function getWorkCenter($p_work_center_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getWorkCenter(:p_work_center_id)');
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateWorkCenter
    # Description: Duplicates the work center.
    #
    # Parameters:
    # - $p_work_center_id (int): The work center ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateWorkCenter($p_work_center_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateWorkCenter(:p_work_center_id, :p_last_log_by, @p_new_work_center_id)');
        $stmt->bindValue(':p_work_center_id', $p_work_center_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_work_center_id AS work_center_id");
        $workCenteriD = $result->fetch(PDO::FETCH_ASSOC)['work_center_id'];

        return $workCenteriD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWorkCenterOptions
    # Description: Generates the work center options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWorkCenterOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWorkCenterOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $workCenterID = $row['work_center_id'];
            $workCenterName = $row['work_center_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($workCenterID, ENT_QUOTES) . '">' . htmlspecialchars($workCenterName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateWorkCenterCheckBox
    # Description: Generates the work center check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateWorkCenterCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateWorkCenterOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $workCenterID = $row['work_center_id'];
            $workCenterName = $row['work_center_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input work-center-filter" type="checkbox" id="work-center-' . htmlspecialchars($workCenterID, ENT_QUOTES) . '" value="' . htmlspecialchars($workCenterID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="work-center-' . htmlspecialchars($workCenterID, ENT_QUOTES) . '">' . htmlspecialchars($workCenterName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>