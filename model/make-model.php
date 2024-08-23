<?php
/**
* Class MakeModel
*
* The MakeModel class handles make related operations and interactions.
*/
class MakeModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateMake
    # Description: Updates the make.
    #
    # Parameters:
    # - $p_make_id (int): The make ID.
    # - $p_make_name (string): The make name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateMake($p_make_id, $p_make_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateMake(:p_make_id, :p_make_name, :p_last_log_by)');
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_make_name', $p_make_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertMake
    # Description: Inserts the make.
    #
    # Parameters:
    # - $p_make_name (string): The make name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertMake($p_make_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertMake(:p_make_name, :p_last_log_by, @p_make_id)');
        $stmt->bindValue(':p_make_name', $p_make_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_make_id AS p_make_id");
        $p_make_id = $result->fetch(PDO::FETCH_ASSOC)['p_make_id'];

        return $p_make_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkMakeExist
    # Description: Checks if a make exists.
    #
    # Parameters:
    # - $p_make_id (int): The make ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkMakeExist($p_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkMakeExist(:p_make_id)');
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteMake
    # Description: Deletes the make.
    #
    # Parameters:
    # - $p_make_id (int): The make ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteMake($p_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteMake(:p_make_id)');
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getMake
    # Description: Retrieves the details of a make.
    #
    # Parameters:
    # - $p_make_id (int): The make ID.
    #
    # Returns:
    # - An array containing the make details.
    #
    # -------------------------------------------------------------
    public function getMake($p_make_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getMake(:p_make_id)');
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateMake
    # Description: Duplicates the make.
    #
    # Parameters:
    # - $p_make_id (int): The make ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateMake($p_make_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateMake(:p_make_id, :p_last_log_by, @p_new_make_id)');
        $stmt->bindValue(':p_make_id', $p_make_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_make_id AS make_id");
        $makeiD = $result->fetch(PDO::FETCH_ASSOC)['make_id'];

        return $makeiD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateMakeOptions
    # Description: Generates the make options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateMakeOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateMakeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $makeID = $row['make_id'];
            $makeName = $row['make_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($makeID, ENT_QUOTES) . '">' . htmlspecialchars($makeName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateMakeCheckBox
    # Description: Generates the make check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateMakeCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateMakeOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $makeID = $row['make_id'];
            $makeName = $row['make_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input make-filter" type="checkbox" id="make-' . htmlspecialchars($makeID, ENT_QUOTES) . '" value="' . htmlspecialchars($makeID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="make-' . htmlspecialchars($makeID, ENT_QUOTES) . '">' . htmlspecialchars($makeName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>