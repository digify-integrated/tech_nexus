<?php
/**
* Class NationalityModel
*
* The NationalityModel class handles nationality related operations and interactions.
*/
class NationalityModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    }

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateNationality
    # Description: Updates the nationality.
    #
    # Parameters:
    # - $p_nationality_id (int): The nationality ID.
    # - $p_nationality_name (string): The nationality name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateNationality($p_nationality_id, $p_nationality_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateNationality(:p_nationality_id, :p_nationality_name, :p_last_log_by)');
        $stmt->bindValue(':p_nationality_id', $p_nationality_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_nationality_name', $p_nationality_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertNationality
    # Description: Inserts the nationality.
    #
    # Parameters:
    # - $p_nationality_name (string): The nationality name.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertNationality($p_nationality_name, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertNationality(:p_nationality_name, :p_last_log_by, @p_nationality_id)');
        $stmt->bindValue(':p_nationality_name', $p_nationality_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_nationality_id AS p_nationality_id");
        $p_nationality_id = $result->fetch(PDO::FETCH_ASSOC)['p_nationality_id'];

        return $p_nationality_id;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkNationalityExist
    # Description: Checks if a nationality exists.
    #
    # Parameters:
    # - $p_nationality_id (int): The nationality ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkNationalityExist($p_nationality_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkNationalityExist(:p_nationality_id)');
        $stmt->bindValue(':p_nationality_id', $p_nationality_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteNationality
    # Description: Deletes the nationality.
    #
    # Parameters:
    # - $p_nationality_id (int): The nationality ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteNationality($p_nationality_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteNationality(:p_nationality_id)');
        $stmt->bindValue(':p_nationality_id', $p_nationality_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getNationality
    # Description: Retrieves the details of a nationality.
    #
    # Parameters:
    # - $p_nationality_id (int): The nationality ID.
    #
    # Returns:
    # - An array containing the nationality details.
    #
    # -------------------------------------------------------------
    public function getNationality($p_nationality_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getNationality(:p_nationality_id)');
        $stmt->bindValue(':p_nationality_id', $p_nationality_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateNationality
    # Description: Duplicates the nationality.
    #
    # Parameters:
    # - $p_nationality_id (int): The nationality ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateNationality($p_nationality_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateNationality(:p_nationality_id, :p_last_log_by, @p_new_nationality_id)');
        $stmt->bindValue(':p_nationality_id', $p_nationality_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_nationality_id AS nationality_id");
        $systemActionID = $result->fetch(PDO::FETCH_ASSOC)['nationality_id'];

        return $systemActionID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateNationalityOptions
    # Description: Generates the nationality options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateNationalityOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateNationalityOptions()');
        $stmt->execute();
        $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $htmlOptions = '';
        foreach ($options as $row) {
            $nationalityID = $row['nationality_id'];
            $nationalityName = $row['nationality_name'];

            $htmlOptions .= '<option value="' . htmlspecialchars($nationalityID, ENT_QUOTES) . '">' . htmlspecialchars($nationalityName, ENT_QUOTES) .'</option>';
        }

        return $htmlOptions;
    }
    # -------------------------------------------------------------
}
?>