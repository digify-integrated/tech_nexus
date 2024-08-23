<?php
/**
* Class CabinModel
*
* The CabinModel class handles cabin related operations and interactions.
*/
class CabinModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateCabin
    # Description: Updates the cabin.
    #
    # Parameters:
    # - $p_cabin_id (int): The cabin ID.
    # - $p_cabin_name (string): The cabin name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateCabin($p_cabin_id, $p_cabin_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateCabin(:p_cabin_id, :p_cabin_name, :p_last_log_by)');
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_cabin_name', $p_cabin_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertCabin
    # Description: Inserts the cabin.
    #
    # Parameters:
    # - $p_cabin_name (string): The cabin name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertCabin($p_cabin_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertCabin(:p_cabin_name, :p_last_log_by, @p_cabin_id)');
        $stmt->bindValue(':p_cabin_name', $p_cabin_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_cabin_id AS p_cabin_id");
        $p_cabin_id = $result->fetch(PDO::FETCH_ASSOC)['p_cabin_id'];

        return $p_cabin_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkCabinExist
    # Description: Checks if a cabin exists.
    #
    # Parameters:
    # - $p_cabin_id (int): The cabin ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkCabinExist($p_cabin_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkCabinExist(:p_cabin_id)');
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteCabin
    # Description: Deletes the cabin.
    #
    # Parameters:
    # - $p_cabin_id (int): The cabin ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteCabin($p_cabin_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteCabin(:p_cabin_id)');
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getCabin
    # Description: Retrieves the details of a cabin.
    #
    # Parameters:
    # - $p_cabin_id (int): The cabin ID.
    #
    # Returns:
    # - An array containing the cabin details.
    #
    # -------------------------------------------------------------
    public function getCabin($p_cabin_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getCabin(:p_cabin_id)');
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateCabin
    # Description: Duplicates the cabin.
    #
    # Parameters:
    # - $p_cabin_id (int): The cabin ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateCabin($p_cabin_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateCabin(:p_cabin_id, :p_last_log_by, @p_new_cabin_id)');
        $stmt->bindValue(':p_cabin_id', $p_cabin_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_cabin_id AS cabin_id");
        $cabiniD = $result->fetch(PDO::FETCH_ASSOC)['cabin_id'];

        return $cabiniD;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCabinOptions
    # Description: Generates the cabin options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCabinOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCabinOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $cabinID = $row['cabin_id'];
            $cabinName = $row['cabin_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($cabinID, ENT_QUOTES) . '">' . htmlspecialchars($cabinName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateCabinCheckBox
    # Description: Generates the cabin check box.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateCabinCheckBox() {
        $stmt = $this->db->getConnection()->prepare('CALL generateCabinOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $cabinID = $row['cabin_id'];
            $cabinName = $row['cabin_name'];

            $htmlOptions .= '<div class="form-check my-2">
                                <input class="form-check-input cabin-filter" type="checkbox" id="cabin-' . htmlspecialchars($cabinID, ENT_QUOTES) . '" value="' . htmlspecialchars($cabinID, ENT_QUOTES) . '" />
                                <label class="form-check-label" for="cabin-' . htmlspecialchars($cabinID, ENT_QUOTES) . '">' . htmlspecialchars($cabinName, ENT_QUOTES) .'</label>
                            </div>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>