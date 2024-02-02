<?php
/**
* Class TransmittalModel
*
* The TransmittalModel class handles transmittal related operations and interactions.
*/
class TransmittalModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateTransmittal
    # Description: Updates the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    # - $p_transmittal_name (string): The transmittal name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateTransmittal($p_transmittal_id, $p_transmittal_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateTransmittal(:p_transmittal_id, :p_transmittal_name, :p_last_log_by)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_transmittal_name', $p_transmittal_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertTransmittal
    # Description: Inserts the transmittal.
    #
    # Parameters:
    # - $p_transmittal_name (string): The transmittal name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertTransmittal($p_transmittal_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertTransmittal(:p_transmittal_name, :p_last_log_by, @p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_name', $p_transmittal_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_transmittal_id AS p_transmittal_id");
        $p_transmittal_id = $result->fetch(PDO::FETCH_ASSOC)['p_transmittal_id'];

        return $p_transmittal_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkTransmittalExist
    # Description: Checks if a transmittal exists.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkTransmittalExist($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkTransmittalExist(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteTransmittal
    # Description: Deletes the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteTransmittal($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteTransmittal(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getTransmittal
    # Description: Retrieves the details of a transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    #
    # Returns:
    # - An array containing the transmittal details.
    #
    # -------------------------------------------------------------
    public function getTransmittal($p_transmittal_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getTransmittal(:p_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateTransmittal
    # Description: Duplicates the transmittal.
    #
    # Parameters:
    # - $p_transmittal_id (int): The transmittal ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateTransmittal($p_transmittal_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateTransmittal(:p_transmittal_id, :p_last_log_by, @p_new_transmittal_id)');
        $stmt->bindValue(':p_transmittal_id', $p_transmittal_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_transmittal_id AS transmittal_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['transmittal_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateTransmittalOptions
    # Description: Generates the transmittal options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateTransmittalOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateTransmittalOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $transmittalID = $row['transmittal_id'];
            $transmittalName = $row['transmittal_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($transmittalID, ENT_QUOTES) . '">' . htmlspecialchars($transmittalName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>