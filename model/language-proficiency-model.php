<?php
/**
* Class LanguageProficiencyModel
*
* The LanguageProficiencyModel class handles language proficiency related operations and interactions.
*/
class LanguageProficiencyModel {
    public $db;

    public function __construct(DatabaseModel $db) {
        $this->db = $db;
    } 

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: generateLanguageProficiencyOptions
    # Description: Generates the language proficiency options.
    #
    # Parameters:None
    #
    # Returns: String.
    #
    # -------------------------------------------------------------
    public function generateLanguageProficiencyOptions() {
        $stmt = $this->db->getConnection()->prepare('CALL generateLanguageProficiencyOptions()');
        $stmt->execute();
        $count = $stmt->rowCount();

        if($count > 0){
            $options = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            $htmlOptions = '';

            foreach ($options as $row) {
                $languageProficiencyID = $row['language_proficiency_id'];
                $languageProficiencyName = $row['language_proficiency_name'];
                $description = $row['description'];

                $htmlOptions .= "<option value='". htmlspecialchars($languageProficiencyID, ENT_QUOTES) ."'>". htmlspecialchars($languageProficiencyName, ENT_QUOTES) ." - ". htmlspecialchars($description, ENT_QUOTES) ."</option>";
            }

            return $htmlOptions;
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Update methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: updateLanguageProficiency
    # Description: Updates the language proficiency.
    #
    # Parameters:
    # - $p_language_proficiency_id (int): The language proficiency ID.
    # - $p_language_proficiency_name (string): The language proficiency name.
    # - $p_details (int): The details.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function updateLanguageProficiency($p_language_proficiency_id, $p_language_proficiency_name, $p_details, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL updateLanguageProficiency(:p_language_proficiency_id, :p_language_proficiency_name, :p_details, :p_last_log_by)');
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_language_proficiency_name', $p_language_proficiency_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_details', $p_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Insert methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: insertLanguageProficiency
    # Description: Inserts the language proficiency.
    #
    # Parameters:
    # - $p_language_proficiency_name (string): The language proficiency name.
    # - $p_details (int): The details.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: String
    #
    # -------------------------------------------------------------
    public function insertLanguageProficiency($p_language_proficiency_name, $p_details, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL insertLanguageProficiency(:p_language_proficiency_name, :p_details, :p_last_log_by, @p_language_proficiency_id)');
        $stmt->bindValue(':p_language_proficiency_name', $p_language_proficiency_name, PDO::PARAM_STR);
        $stmt->bindValue(':p_details', $p_details, PDO::PARAM_STR);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_language_proficiency_id AS language_proficiency_id");
        $languageProficiencyID = $result->fetch(PDO::FETCH_ASSOC)['language_proficiency_id'];

        return $languageProficiencyID;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Check exist methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: checkLanguageProficiencyExist
    # Description: Checks if a language proficiency exists.
    #
    # Parameters:
    # - $p_language_proficiency_id (int): The language proficiency ID.
    #
    # Returns: The result of the query as an associative array.
    #
    # -------------------------------------------------------------
    public function checkLanguageProficiencyExist($p_language_proficiency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL checkLanguageProficiencyExist(:p_language_proficiency_id)');
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Delete methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: deleteLanguageProficiency
    # Description: Deletes the language proficiency.
    #
    # Parameters:
    # - $p_language_proficiency_id (int): The language proficiency ID.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function deleteLanguageProficiency($p_language_proficiency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL deleteLanguageProficiency(:p_language_proficiency_id)');
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->execute();
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: getLanguageProficiency
    # Description: Retrieves the details of a language proficiency.
    #
    # Parameters:
    # - $p_language_proficiency_id (int): The language proficiency ID.
    #
    # Returns:
    # - An array containing the language proficiency details.
    #
    # -------------------------------------------------------------
    public function getLanguageProficiency($p_language_proficiency_id) {
        $stmt = $this->db->getConnection()->prepare('CALL getLanguageProficiency(:p_language_proficiency_id)');
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Duplicate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Function: duplicateLanguageProficiency
    # Description: Duplicates the language proficiency.
    #
    # Parameters:
    # - $p_language_proficiency_id (int): The language proficiency ID.
    # - $p_last_log_by (int): The last logged user.
    #
    # Returns: None
    #
    # -------------------------------------------------------------
    public function duplicateLanguageProficiency($p_language_proficiency_id, $p_last_log_by) {
        $stmt = $this->db->getConnection()->prepare('CALL duplicateLanguageProficiency(:p_language_proficiency_id, :p_last_log_by, @p_new_language_proficiency_id)');
        $stmt->bindValue(':p_language_proficiency_id', $p_language_proficiency_id, PDO::PARAM_INT);
        $stmt->bindValue(':p_last_log_by', $p_last_log_by, PDO::PARAM_INT);
        $stmt->execute();

        $result = $this->db->getConnection()->query("SELECT @p_new_language_proficiency_id AS language_proficiency_id");
        $languageProficiencyID = $result->fetch(PDO::FETCH_ASSOC)['language_proficiency_id'];

        return $languageProficiencyID;
    }
    # -------------------------------------------------------------
}
?>