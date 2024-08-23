<?php
/**
* Class ModeOfAcquisitionModel
*
* The ModeOfAcquisitionModel class handles mode of acquisition related operations and interactions.
*/
class ModeOfAcquisitionModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateModeOfAcquisition
    # Description: Updates the mode of acquisition.
    #
    # Parameters:
    # - $p_mode_of_acquisition_id (int): The mode of acquisition ID.
    # - $p_mode_of_acquisition_name (string): The mode of acquisition name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateModeOfAcquisition($p_mode_of_acquisition_id, $p_mode_of_acquisition_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateModeOfAcquisition(:p_mode_of_acquisition_id, :p_mode_of_acquisition_name, :p_last_log_by)');
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_mode_of_acquisition_name', $p_mode_of_acquisition_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertModeOfAcquisition
    # Description: Inserts the mode of acquisition.
    #
    # Parameters:
    # - $p_mode_of_acquisition_name (string): The mode of acquisition name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertModeOfAcquisition($p_mode_of_acquisition_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertModeOfAcquisition(:p_mode_of_acquisition_name, :p_last_log_by, @p_mode_of_acquisition_id)');
        $stmt->bindValue(':p_mode_of_acquisition_name', $p_mode_of_acquisition_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_mode_of_acquisition_id AS p_mode_of_acquisition_id");
        $p_mode_of_acquisition_id = $result->fetch(PDO::FETCH_ASSOC)['p_mode_of_acquisition_id'];

        return $p_mode_of_acquisition_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkModeOfAcquisitionExist
    # Description: Checks if a mode of acquisition exists.
    #
    # Parameters:
    # - $p_mode_of_acquisition_id (int): The mode of acquisition ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkModeOfAcquisitionExist($p_mode_of_acquisition_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkModeOfAcquisitionExist(:p_mode_of_acquisition_id)');
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteModeOfAcquisition
    # Description: Deletes the mode of acquisition.
    #
    # Parameters:
    # - $p_mode_of_acquisition_id (int): The mode of acquisition ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteModeOfAcquisition($p_mode_of_acquisition_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteModeOfAcquisition(:p_mode_of_acquisition_id)');
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getModeOfAcquisition
    # Description: Retrieves the details of a mode of acquisition.
    #
    # Parameters:
    # - $p_mode_of_acquisition_id (int): The mode of acquisition ID.
    #
    # Returns:
    # - An array containing the mode of acquisition details.
    #
    # -------------------------------------------------------------
    public function getModeOfAcquisition($p_mode_of_acquisition_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getModeOfAcquisition(:p_mode_of_acquisition_id)');
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateModeOfAcquisition
    # Description: Duplicates the mode of acquisition.
    #
    # Parameters:
    # - $p_mode_of_acquisition_id (int): The mode of acquisition ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateModeOfAcquisition($p_mode_of_acquisition_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateModeOfAcquisition(:p_mode_of_acquisition_id, :p_last_log_by, @p_new_mode_of_acquisition_id)');
        $stmt->bindValue(':p_mode_of_acquisition_id', $p_mode_of_acquisition_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_mode_of_acquisition_id AS mode_of_acquisition_id");
        $modeOfAcquisitionID = $result->fetch(PDO::FETCH_ASSOC)['mode_of_acquisition_id'];

        return $modeOfAcquisitionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateModeOfAcquisitionOptions
    # Description: Generates the mode of acquisition options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateModeOfAcquisitionOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateModeOfAcquisitionOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $modeOfAcquisitionID = $row['mode_of_acquisition_id'];
            $modeOfAcquisitionName = $row['mode_of_acquisition_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($modeOfAcquisitionID, ENT_QUOTES) . '">' . htmlspecialchars($modeOfAcquisitionName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>