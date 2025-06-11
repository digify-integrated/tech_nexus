<?php
/**
* Class IncomeLevelModel
*
* The IncomeLevelModel class handles income level related operations and interactions.
*/
class IncomeLevelModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateIncomeLevel
    # Description: Updates the income level.
    #
    # Parameters:
    # - $p_income_level_id (int): The income level ID.
    # - $p_income_level_name (string): The income level name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateIncomeLevel($p_income_level_id, $p_income_level_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateIncomeLevel(:p_income_level_id, :p_income_level_name, :p_last_log_by)');
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_income_level_name', $p_income_level_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertIncomeLevel
    # Description: Inserts the income level.
    #
    # Parameters:
    # - $p_income_level_name (string): The income level name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertIncomeLevel($p_income_level_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertIncomeLevel(:p_income_level_name, :p_last_log_by, @p_income_level_id)');
        $stmt->bindValue(':p_income_level_name', $p_income_level_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_income_level_id AS p_income_level_id");
        $p_income_level_id = $result->fetch(PDO::FETCH_ASSOC)['p_income_level_id'];

        return $p_income_level_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkIncomeLevelExist
    # Description: Checks if a income level exists.
    #
    # Parameters:
    # - $p_income_level_id (int): The income level ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkIncomeLevelExist($p_income_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkIncomeLevelExist(:p_income_level_id)');
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteIncomeLevel
    # Description: Deletes the income level.
    #
    # Parameters:
    # - $p_income_level_id (int): The income level ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteIncomeLevel($p_income_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteIncomeLevel(:p_income_level_id)');
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getIncomeLevel
    # Description: Retrieves the details of a income level.
    #
    # Parameters:
    # - $p_income_level_id (int): The income level ID.
    #
    # Returns:
    # - An array containing the income level details.
    #
    # -------------------------------------------------------------
    public function getIncomeLevel($p_income_level_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getIncomeLevel(:p_income_level_id)');
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateIncomeLevel
    # Description: Duplicates the income level.
    #
    # Parameters:
    # - $p_income_level_id (int): The income level ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateIncomeLevel($p_income_level_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateIncomeLevel(:p_income_level_id, :p_last_log_by, @p_new_income_level_id)');
        $stmt->bindValue(':p_income_level_id', $p_income_level_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_income_level_id AS income_level_id");
        $incomeLevelID = $result->fetch(PDO::FETCH_ASSOC)['income_level_id'];

        return $incomeLevelID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateIncomeLevelOptions
    # Description: Generates the income level options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateIncomeLevelOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateIncomeLevelOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $incomeLevelID = $row['income_level_id'];
            $incomeLevelName = $row['income_level_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($incomeLevelID, ENT_QUOTES) . '">' . htmlspecialchars($incomeLevelName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>