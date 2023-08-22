<?php
/**
* Class StateModel
*
* The StateModel class handles state related operations and interactions.
*/
class StateModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateState
    # Description: Updates the state.
    #
    # Parameters:
    # - $p_state_id (int): The state ID.
    # - $p_state_name (string): The state name.
    # - $p_state_code (string): The state code.
    # - $p_country_id (string): The phone code.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateState($p_state_id, $p_state_name, $p_country_id, $p_state_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateState(:p_state_id, :p_state_name, :p_country_id, :p_state_code, :p_last_log_by)');
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_state_name', $p_state_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_country_id', $p_country_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_state_code', $p_state_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertState
    # Description: Inserts the state.
    #
    # Parameters:
    # - $p_state_name (string): The state name.
    # - $p_country_id (string): The phone code.
    # - $p_state_code (string): The state code.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertState($p_state_name, $p_country_id, $p_state_code, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertState(:p_state_name, :p_country_id, :p_state_code, :p_last_log_by, @p_state_id)');
        $stmt->bindValue(':p_state_name', $p_state_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_country_id', $p_country_id, PDO::PARAM_STR);
        $stmt->bindValue(':p_state_code', $p_state_code, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_state_id AS p_state_id");
        $p_state_id = $result->fetch(PDO::FETCH_ASSOC)['p_state_id'];

        return $p_state_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkStateExist
    # Description: Checks if a state exists.
    #
    # Parameters:
    # - $p_state_id (int): The state ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkStateExist($p_state_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkStateExist(:p_state_id)');
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteState
    # Description: Deletes the state.
    #
    # Parameters:
    # - $p_state_id (int): The state ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteState($p_state_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteState(:p_state_id)');
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getState
    # Description: Retrieves the details of a state.
    #
    # Parameters:
    # - $p_state_id (int): The state ID.
    #
    # Returns:
    # - An array containing the state details.
    #
    # -------------------------------------------------------------
    public function getState($p_state_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getState(:p_state_id)');
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateState
    # Description: Duplicates the state.
    #
    # Parameters:
    # - $p_state_id (int): The state ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateState($p_state_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateState(:p_state_id, :p_last_log_by, @p_new_state_id)');
        $stmt->bindValue(':p_state_id', $p_state_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_state_id AS state_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['state_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateStateOptions
    # Description: Generates the state options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateStateOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateStateOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $stateID = $row['state_id'];
            $stateName = $row['state_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($stateID, ENT_QUOTES) . '">' . htmlspecialchars($stateName, ENT_QUOTES) . '</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>